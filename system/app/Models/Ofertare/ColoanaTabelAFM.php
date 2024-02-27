<?php

namespace App\Models\Ofertare;

use App\Models\AfmForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ColoanaTabelAFM extends Model
{
    use HasFactory;

    protected $fillable = [
        'nume',
        'titlu',
        'tip', // 	1: text / 2: numeric / 3: date / 4: textarea / 5: select / 6: json / 7: file / 8: popup / 9: dinamic
        'tabel',
        'rules',
        'default_values',
        'afisare', // afisare in tabelul afm: null = normal / 1 = custom
        'cautare', // cautare in tabelul afm: null = nu nu se poate cauta / 1 = cautare normala in functie de tip / 2 = custom (fisier special)
        'editare', // editare in tabelul afm: null = nu nu se poate edita / 1 = editare normala in functie de tip / 2 = custom (fisier special)
        'ordonare', // ordonare in tabelul afm: null = nu se poate ordona / 1 = se poate ordona
        'um',
        'afix',
    ];

    protected $casts = [
        'rules' => 'array',
        'default_values' => 'array',
    ];

    protected $table = 'coloane_tabel_afm';

    protected $current_section = '2021';

    public static $types = [
        1 => 'text',
        2 => 'numeric',
        3 => 'date',
        4 => 'textarea',
        5 => 'select',
        6 => 'json',
        7 => 'file',
        8 => 'popup',
        9 => 'dinamic',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sectiuni()
    {
        return $this->belongsToMany(SectiuneAFM::class, 'coloana_sectiune_afm', 'coloana_id', 'sectiune_id')->withTimestamps();
    }

    public function descriere()
    {
        return $this->hasOne(DescriereColoanaAFM::class, 'coloana_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    */

    public function getDataUrlAttribute()
    {
        return (
            isset($this->rules) && isset($this->rules['db'])
            && !in_array($this->nume, ['localitate_domiciliu', 'localitate_imobil'])
        )
            ? route('ofertare.afm.get.column.db.options', [$this->nume, $this->current_section])
            : null;
        // return $this->nume == 'localitate_domiciliu' || $this->nume == 'localitate_imobil'
        //     ? null : $data_url;
    }

    /*
    |--------------------------------------------------------------------------
    | Table related functions
    |--------------------------------------------------------------------------
    */

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function getColumnTable($section = null)
    {
        return static::getColumnTableName($this->tabel, $section ?: $this->current_section);
    }

    public function getColumnTableKey($section = null)
    {
        return static::getColumnTableKeyName($this->tabel, $section ?: $this->current_section);
    }

    public function getColumnTableForeignKey($section = null)
    {
        return static::getColumnTableForeignKeyName($this->tabel, $section ?: $this->current_section);
    }

    public function setCurrentSection($section = '2021')
    {
        $this->current_section = $section;
        return $this;
    }

    public static function getColumnTableName($table_slug, $section = '2021')
    {
        if($table_slug === 'info') {
            return static::getAfmFormModel($section)->getInfoTable();
        } elseif($table_slug === 'copro') {
            return static::getAfmFormModel($section)->getCoproTable();
        } elseif($table_slug === 'rate') {
            return static::getAfmFormModel($section)->getRateTable();
        } else {
            return static::getAfmFormModel($section)->getTableName();
        }
    }

    public static function getColumnTableKeyName($table_slug, $section = '2021')
    {
        if($table_slug === 'info') {
            return static::getAfmFormModel($section)->getInfoKeyName();
        } elseif($table_slug === 'copro') {
            return static::getAfmFormModel($section)->getCoproKeyName();
        } elseif($table_slug === 'rate') {
            return static::getAfmFormModel($section)->getRateKeyName();
        } else {
            return static::getAfmFormModel($section)->getKeyName();
        }
    }

    public static function getColumnTableForeignKeyName($table_slug, $section = '2021')
    {
        if($table_slug === 'info') {
            return static::getAfmFormModel($section)->getInfoForeignKeyName();
        } elseif($table_slug === 'copro') {
            return static::getAfmFormModel($section)->getCoproForeignKeyName();
        } elseif($table_slug === 'rate') {
            return static::getAfmFormModel($section)->getRateForeignKeyName();
        } else {
            return static::getAfmFormModel($section)->getKeyName();
        }
    }

    public static function getAfmFormModel($section = '2021')
    {
        return AfmForm::setSection($section);
    }

    public static function setCurrentSectionName($section = '2021')
    {
        $self = new static;
        $self->current_section = $section;
        return $self;
    }

    /*
    |--------------------------------------------------------------------------
    | Custom functions
    |--------------------------------------------------------------------------
    */

    public function getType()
    {
        return static::types($this->tip);
    }

    public function isTypeFile()
    {
        return $this->tip == 7;
    }

    public function getAdvancedDataUrl($section = '2021', $formular = null)
    {
        return isset($this->rules) && isset($this->rules['db'])
            ? route('ofertare.afm.get.column.db.options', [$this->nume, $section ?? $this->current_section, $formular])
            : null;
        // return $this->nume == 'localitate_domiciliu' || $this->nume == 'localitate_imobil'
        //     ? null : $data_url;
    }

    public static function coloana($name)
    {
        return static::firstWhere('nume', $name);
    }

    public static function types($index = null)
    {
        $types = [
            1 => 'text',
            2 => 'numeric',
            3 => 'date',
            4 => 'textarea',
            5 => 'select',
            6 => 'json',
            7 => 'file',
            // 8 => 'popup',
            9 => 'dynamic', // nu se salveaza in db si nu poate fi obtinut prin laravel scope sau join
        ];
        return $index ? ($types[$index] ?? null) : $types;
    }

    public static function allColumnNames()
    {
        $arr = [];
        if(auth()->check()) {
            foreach(auth()->user()->getAllPermissions()->sortBy('name') as $permission) {
                if(preg_match('/^afm\.[^\.]*\.((?!generare[^\.]*|mail[^\.]*|buton[^\.]*|view|\*)[^\.]*)(?:\.?view)?$/', $permission->name)) {
                    $arr[] = preg_replace('/afm\.[^\.]*\.([^\.]*).*/', '\1', $permission->name);
                }
            }
        }
        return $arr;
    }

    public static function getAllColumns($section)
    {
        return static::whereIn('nume', static::allColumnNames())->whereHas('sectiuni', function ($query) use ($section) {
            $query->where('nume', $section);
        })->get();
    }

    public static function getSectionColumns($section)
    {
        return static::whereHas('sectiuni', function ($query) use ($section) {
            $query->where('nume', $section);
        })->get();
    }

    public static function getColumnsRules(array $columns)
    {
        return static::whereIn('nume', $columns)
            ->pluck('rules->validation as validation','nume')->map(function ($item, $key) {
                return json_decode($item, true);
            })->all();
    }

    public function getColumnMailTemplate($value)
    {
        return static::getColumnMailTemplateId($this->nume, $value);
    }

    public function canBeEdited($item)
    {
        if(
            auth()->user()->cannot('afm.2021.'.$this->nume.'.edit')
            || (isset($this->rules['edit_once']) && !empty($item[$this->nume]))
            || (isset($this->rules['edit_without']) && !empty($item[$this->rules['edit_without']]))
        ) {
            return false;
        }
        return true;
    }

    public static function getColumnMailTemplateId($column, $value)
    {
        if($column === 'status_aprobare_dosar') {
            return $value === 'dosar admis' ? 26 : 27;
        } elseif($column === 'preverificare_dosar') {
            return $value === 'aprobare' ? 28 : 29;
        }
        return null;
    }
}
