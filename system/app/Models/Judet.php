<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Judet extends Model
{
    use HasFactory, HasJsonRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'initiale',
        'slug',
        'nume',
        'vizite',
    ];

    protected $table = 'geo_judete';

    public const REGIUNI = [
        'buc_ilfov' => [
            'valori' => [4, 24],
            'nume' => 'Bucuresti-Ilfov',
            'responsabil' => 8533,
        ],
        'sud_muntenia' => [
            'valori' => [2, 13, 17, 21, 25, 32, 39],
            'nume' => 'Sud-Muntenia',
            'responsabil' => 10892,
        ],
        'sud_vest' => [
            'valori' => [18, 19, 27, 31, 40],
            'nume' => 'Sud-Vest',
            'responsabil' => 6928,
        ],
        'sud_est' => [
            'valori' => [8, 11, 15, 20, 41, 37],
            'nume' => 'Sud-Est',
            'responsabil' => 6929,
        ],
        'nord_est' => [
            'valori' => [5, 9, 26, 30, 36, 42],
            'nume' => 'Nord Est',
            'responsabil' => 6930,
        ],
        'vest' => [
            'valori' => [3, 14, 22, 38],
            'nume' => 'Vest',
            'responsabil' => 10928,
        ],
        'nord_vest' => [
            'valori' => [6, 7, 12, 34, 35, 28],
            'nume' => 'Nord Vest',
            'responsabil' => 6930,
        ],
        'centru' => [
            'valori' => [1, 10, 16, 23, 29, 33],
            'nume' => 'Centru',
            'responsabil' => 7651,
        ]
    ];

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public static function getNumeRegiuni()
    {
        return collect(self::REGIUNI)->mapWithKeys(function($item, $key) {
            return [$key => __($item['nume'])];
        });
    }

    public static function getResponsabilRegiune($regiune)
    {
        return isset(self::REGIUNI[$regiune]['responsabil']) ? User::find(self::REGIUNI[$regiune]['responsabil']) : null;
    }

    public static function responsabilRegiuneEmail($id)
    {
        if(in_array($id, [4, 24])) {    // Bucuresti-Ilfov
            return 'gabriel.chiroaba@genway.ro';
        } elseif (in_array($id, [2, 13, 17, 21, 25, 32, 39])) {     // Sud-Muntenia
            return 'radu.paun@genway.ro';
        } elseif (in_array($id, [18, 19, 27, 31, 40])) {    // Sud-Vest
            return 'magdalena.popa@genway.ro';
        } elseif (in_array($id, [8, 11, 15, 20, 41, 37])) {    // Sud-Est
            return 'alexandra.danaila@genway.ro';
        } elseif (in_array($id, [5, 9, 26, 30, 36, 42])) {    // Nord Est
            return 'mihaela.dumitriu@genway.ro';
        } elseif (in_array($id, [3, 14, 22, 38])) {    // Vest
            return 'andreea.lungu@genway.ro';
        } elseif (in_array($id, [6, 7, 12, 34, 35, 28])) {    // Nord Vest
            return 'mihaela.dumitriu@genway.ro';
        } elseif (in_array($id, [1, 10, 16, 23, 29, 33])) {    // Centru
            return 'mihai.radu@genway.ro';
        }
        return '';
    }

    public function localitati()
    {
        return $this->hasMany(Localitate::class, 'id_judet');
    }
}
