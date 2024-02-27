<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod',
        'nume',
        'tip',
        'valoare',
        'data_adaugare',
        'data_expirare',
        'caracter', // 1: permanent / 2: unic
        'id_produs',
        'activ',
    ];

    protected $table = 'vouchere';

    public $timestamps = false;

    public function comenzi()
    {
        return $this->hasMany(Comanda::class, 'cod_voucher', 'cod');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('vouchere.activ', '1');
        });

        static::addGlobalScope('available', function (Builder $builder) {
            $builder->whereDate('vouchere.data_expirare', '>=', now());
        });
    }
}
