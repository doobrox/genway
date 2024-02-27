<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DescriereColoanaAFM extends Model
{
    use HasFactory;

    protected $fillable = [
        'coloana_id',
        'responsabil_2021',
        'responsabil_2023',
        'id_sablon_adaugare',
        'id_sablon_vizualizare',
        'obs',
    ];

    protected $casts = [];

    protected $table = 'coloane_descriere';

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function getEditable()
    {
        return collect($this->getFillable())->diff(['coloana_id'])->values()->toArray();
    }

    public static function getEditableColumns()
    {
        return (new static)->getEditable();
    }

    public static function rules($id = null)
    {
        return [
            'responsabil_2021' => ['nullable', 'string', 'max:30000'],
            'responsabil_2023' => ['nullable', 'string', 'max:30000'],
            'id_sablon_adaugare' => ['nullable', 'integer', 'min:1'],
            'id_sablon_vizualizare' => ['nullable', 'integer', 'min:1'],
            'obs' => ['nullable', 'string', 'max:30000'],
        ];
    }
}
