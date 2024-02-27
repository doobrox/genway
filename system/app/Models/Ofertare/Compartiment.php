<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compartiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'compartiment',
        'adresa',
        'pret_manopera',
        'puncte_manopera',
        'puncte_cablare',
        'puncte_manopera_tehnicieni',
        'puncte_agenti',
        'puncte_echipa_compartiment',
        'puncte_avarii',
    ];

    protected $casts = [];

    protected $table = 'compartimente';

    public $timestamps = false;

    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
