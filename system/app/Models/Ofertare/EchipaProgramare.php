<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EchipaProgramare extends Pivot
{
    protected $fillable = [
        'programare_id',
        'user_id',
        'procent',
        'lider'
    ];

    public $timestamps = false;

    public $incrementing = true;

    protected $table = 'echipa_programare';
}
