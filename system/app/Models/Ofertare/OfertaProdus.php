<?php

namespace App\Models\Ofertare;

use App\Models\User;
use App\Models\Produs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfertaProdus extends Model
{
    use HasFactory;

    protected $fillable = [
        'oferta_id',
        'produs_id',
        'nume',   
        'cantitate',
        'pret',
        'nr_ordine',
    ];

    protected $casts = [];

    protected $table = 'oferte_produse';

    public $timestamps = false;

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function produs()
    {
        return $this->belongsTo(Produs::class, 'produs_id')->withoutGlobalScopes(['active', 'quantity']);
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    */

    public function getValoareAttribute()
    {
        return $this->pret * $this->cantitate;
    }

    /*
    |--------------------------------------------------------------------------
    | Local scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithValoare($query, $alias = 'valoare')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select($this->getTable().'.*');
        }
        $query->addSelect('('.$this->getTable().'.pret * '.$this->getTable().'.cantitate) AS '.$alias);
    }
}
