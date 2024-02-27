<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Dotlogics\Grapesjs\App\Editor\Config;
use Dotlogics\Grapesjs\App\Contracts\Editable;
use Dotlogics\Grapesjs\App\Traits\EditableTrait;

class SablonDocument extends Model implements Editable
{
    use EditableTrait;

    protected $fillable = [
        'nume',
        'slug',
        'pivot_key',
        'subiect',
        'continut',
        'detalii', // legenda
        'styles',
        'scripts',
        'gjs_data',
        'pagebuilder',  // null: nu se foloseste / 1: se foloseste pagebuilder
    ];

    protected $table = 'sabloane_documente';

    public function getStilizariAttribute()
    {
        return $this->getAttributes()['styles'];
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public static function formatData($info = array(), $tag = '__')
    {
        $info['spatiu'] = '<span style="visibility:hidden;">_</span>';
        $info['tab'] = '<span style="visibility:hidden;">____</span>';
        foreach($info as $key => $value) {
            $info[$tag.strtoupper($key).$tag] = is_array($value) ? ($value['id'] ?? '') : $value;
            unset($info[$key]);
        }
        return $info;
    }

    public static function getFormatedData($id, $info = array(), $tag = '__')
    {
        return full_document_template($id, static::formatData($info));
    }

    public static function generateDocument($slug, $key = null, $info = array(), $tag = '__', $download = false)
    {
        $sablon = $slug instanceof (static::class) 
            ? $slug 
            : (is_int($slug) ? static::findOrFail($slug) : static::getSablonFromSlug($slug, $key));
        $data = static::getFormatedData($sablon->id, $info, $tag) + ($sablon->pagebuilder ? [
            'pagebuilder' => $sablon->pagebuilder
        ] : []);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        if($sablon->scripts) {
            $dompdf = $pdf->getDomPDF();
            $dompdf->set_option("isPhpEnabled", true);
        }

        if (!$download) {
            return $pdf->stream($sablon->subiect.'.pdf')->header('Content-Type','application/pdf');
        } else { 
            if($download === 2) {
                return \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), $sablon->subiect.'.pdf')
                    ->withMime('application/pdf');
            } else {
                return $pdf->download($sablon->subiect.'.pdf');
            }
        }
    }

    public static function generateRawDocument($slug, $key = null, $download = false)
    {
        $sablon = $slug instanceof (static::class) ? $slug : static::getSablonFromSlug($slug, $key);
        $pdf = \PDF::loadView('sablon-pdf', [
            'subiect' => $sablon->subiect,
            'continut' => $sablon->pagebuilder ? ($sablon->html ?? '') : ($sablon->continut ?? ''),
            'styles' => $sablon->stilizari.($sablon->pagebuilder ? ($sablon->css ?? '') : ''),
            'scripts' => $sablon->scripts,
            'pagebuilder' => $sablon->pagebuilder,
        ]);
        if($sablon->scripts) {
            $dompdf = $pdf->getDomPDF();
            $dompdf->set_option("isPhpEnabled", true);
        }

        if (!$download) {
            return $pdf->stream($sablon->subiect.'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($sablon->subiect.'.pdf');
        }
    }

    public static function getSablonFromSlug($slug, $key = null)
    {
        $sabloane = static::where('slug', $slug)->get();
        if($key) {
            $sablon = $sabloane->where('pivot_key', $key)->first();
        } 
        if($key === null || $sablon === null) {
            $sablon = $sabloane->whereNull('pivot_key')->first();
        }
        return $sablon ?? abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Pagebuilder overwrite functions
    |--------------------------------------------------------------------------
    */

    public function getAllClassesAttribute()
    {
        return $this->extractClasses($this->gjs_data['html']);
    }

    public function getEditorConfigAttribute()
    {
        return app(Config::class)->initialize($this);
    }

    public function getCssFileClassesAttribute()
    {
        $classes = '';
        foreach($this->editor_config->getStyles() as $style) {
            if($css = $this->extractClasses(file_get_contents($style), 'css', $this->all_classes)) {
                $classes .= $css;
            }
        }
        return $classes;
    }

    public function extractClasses(string $origin, $type = 'html', $classes = [])
    {
        if(in_array($type, ['html', 'css'])) {
            $result = [];
            preg_match_all(self::{$type.'_string_pattern'}($classes), $origin, $result);
            if(isset($result[1]) && is_array($result[1])) {
                if($type == 'html') {
                    // return unique classes
                    return array_values(array_unique(explode(' ', implode(' ', $result[1]))));
                } else {
                    return implode('', $result[0]);
                }
            }
        }
        return null;
    }

    public function html_string_pattern(array $classes = [])
    {
        return '/class="([^"]*)"/';
    }

    public function css_string_pattern(array $classes = [])
    {
        $particular = count($classes) ? '(?:\.'.addcslashes(implode('|.', $classes), '-/\\[]:.').')' : '';
        return '/'.$particular.'\s*[^{]*{([^{}}]*)}/';
    }

    public function activatePagebuilderPlugin($name = null, $options = [])
    {
        if($name) {
            $plugin_key = null;
            foreach (config('laravel-grapesjs.plugins.custom') as $key => $value) {
                if(isset($value['name']) && $value['name'] === $name) {
                    $plugin_key = $key;
                    break;
                }
            }
            if($plugin_key) {
                config(['laravel-grapesjs.plugins.custom.'.$plugin_key.'.enabled' => true]);
            }
        }
    }
}
