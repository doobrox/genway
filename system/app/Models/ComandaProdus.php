<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComandaProdus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_comanda',
        'id_produs',
        'nume', 
        'cod_ean13', 
        'cantitate',
        'pret',
        'tva',
        'filtre',
        'sincronizat'
    ];

    protected $table = 'comenzi_produse';

    public $timestamps = false;

    public function produs()
    {
        return $this->belongsTo(Produs::class, 'id_produs');
    }

    public function getRouteAttribute()
    {
        $produs = $this->id_produs ? $this->produs : null;
        return $produs ? $produs->route : 'javascript:void(0)';
    }
}
