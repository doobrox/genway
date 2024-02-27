<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Eveniment extends Model
{
    use HasFactory;

    protected $fillable = [
        'an',
        'tabel',
        'tabel_id',
        'user_id',
        'eveniment',
        'coloana',
        'valoare',
        'created_at',
        'updated_at'
    ];

    protected $casts = [];

    protected $table = 'evenimente_afm';

    public const OPTIUNI = [
        'clienti noi',
        'propuse pentru aporbare',
        'propuse pentru respingere',
        'dosare aprobate',
        'dosare respinse',
    ];

    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
