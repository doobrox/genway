<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invertor extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'an',
        'cod',
        'putere',
        'marca',
        'tip',
        'grid',
        'contor',
        'descriere'
    ];

    protected $casts = [];

    protected $table = 'invertoare_afm';

    public const MARCA_VALUES = [
        'Fronius snap' => 'Fronius snap',
        'Fronius GEN24' => 'Fronius GEN24',
        'Huawei' => 'Huawei',
        'Sungrow' => 'Sungrow',
    ];

    public const TIP_VALUES = [
        'monofazat' => 'monofazat',
        'trifazat' => 'trifazat',
    ];

    public const GRID_VALUES = [
        'on-grid' => 'on-grid',
        'on-grid-hybrid' => 'on-grid-hybrid',
    ];

    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
