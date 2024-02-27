<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meniu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nume',
        'link',
        'ordonare',
        'activ',
    ];

    protected $table = 'categorii_pagini';

    public function pagini()
    {
        return $this->belongsToMany(Pagina::class, 'pagini_categorii', 'id_categorie', 'id_pagina');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('categorii_pagini.activ', '1');
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderBy('categorii_pagini.ordonare');
        });
    }
}
