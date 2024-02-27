<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagineProdus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_produs',
        'titlu',
        'fisier',
        'data_adaugare',
        'principala',
        'activ',
    ];

    protected $table = 'general_galerie';

    public function produs()
    {
        return $this->belongsTo(Produs::class, 'id_produs');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('general_galerie.activ', '1');
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderByDesc('principala');
        });
    }
}
