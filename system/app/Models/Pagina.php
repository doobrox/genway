<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Dotlogics\Grapesjs\App\Editor\Config;
use Dotlogics\Grapesjs\App\Contracts\Editable;
use Dotlogics\Grapesjs\App\Traits\EditableTrait;

class Pagina extends Model implements Editable
{
    use HasFactory, EditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'titlu',
        'continut',
        'link_extern',
        'ordonare',
        'posibilitate_stergere',
        'in_meniu',
        'in_meniu_principal',
        'activ',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'gjs_data',
        'pagebuilder',  // null: nu se foloseste / 1: se foloseste pagebuilder
    ];

    protected $table = 'pagini';

    protected $casts = [
        // 'created_at' => FormatDate::class,
    ];

    public static function getTableName()
    {
        return (new static)->getTable();
    }

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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            if(!auth()->check() || auth()->check() && !auth()->user()->isAdmin()) {
                $builder->where('pagini.activ', '1');
            }
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderBy('pagini.ordonare');
        });
    }
}
