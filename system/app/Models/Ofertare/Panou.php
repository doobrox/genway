<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Panou extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'model',
        'putere',
        'marca',
        'descriere'
    ];

    protected $casts = [];

    protected $table = 'panouri_afm';

    public const MARCA_VALUES = [
        'JA Solar' => 'JA Solar',
        'Trina Solar' => 'Trina Solar'
    ];

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function getEditable()
    {
        return collect($this->getFillable())->diff(['sursa', 'componenta'])->values()->toArray();
    }

    public static function getEditableColumns()
    {
        return (new static)->getEditable();
    }

    public static function rules($id = null)
    {
        return [
            'section' => ['required', 'string', 'max:255', Rule::exists(SectiuneAFM::class, 'nume')],
            'model' => ['required', 'string', 'max:255'],
            'putere' => ['required', 'numeric', 'max:9999', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'marca' => ['required', 'string', 'max:255'],
            'descriere' => ['nullable', 'string', 'max:100000']
        ];
    }

    public static function messages()
    {
        return [
            '*.regex' => __('Campul nu trebuie sa aiba mai mult de 2 decimale'),
        ];
    }

    public static function names()
    {
        return [
            'section' => __('sectiune'),
        ];
    }
}
