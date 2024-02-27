<?php

namespace App\Models;

use App\Traits\ConversionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comentariu extends Model
{
    use HasFactory, ConversionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'id_produs',
        'nota',
        'comentarii',
        'ip',
        'data_adaugare',
        'activ',
    ];

    protected $table = 'produse_comentarii';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

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
            $builder->where('activ', '1');
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderByDesc('created_at');
        });
    }
}
