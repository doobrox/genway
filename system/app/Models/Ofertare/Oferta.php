<?php

namespace App\Models\Ofertare;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oferta extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'firma_id',
        'client_id',   
        'nume_firma_client',
        'tip_client',
        'cui_client',
        'reg_com_client',
        'nume', 
        'email',
        'telefon',
        'id_localitate',   
        'adresa',
        'reseller',
        'platitor_tva',
        'stare',  
        'denumire',
        'data',
        'tva',
        'mesaj',
        'nota_interna', 
        'contract_id',
        'oferta_casa_verde',
        'tip_invertor',
        'marca_invertor',
        'model_invertor',
        'marca_panouri',
        'model_panouri',
        'numar_panouri',
        'tip_acumulatori',
        'capacitate_acumulatori',
        'limita_cablare_dc',
        'limita_cablare_ac',
    ];

    protected $casts = [];

    protected $table = 'oferte';

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

    public function oferteAfm()
    {
        return $this->hasMany(OfertaAFM::class, 'oferta_id');
    }

    public function invertor()
    {
        return $this->belongsTo(Invertor::class, 'model_invertor');
    }

    public function panou()
    {
        return $this->belongsTo(Panou::class, 'model_panouri');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function compartiment()
    {
        return $this->hasOneThrough(Compartiment::class, User::class, 'id', 'id', 'agent_id', 'compartiment_id');
    }

    public function produse()
    {
        return $this->hasMany(OfertaProdus::class, 'oferta_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    */

    public function getSubtotalAttribute()
    {
        return round($this->produse->sum('valoare'), 2);
    }

    public function getValoareTvaAttribute()
    {
        return round($this->subtotal * ($this->tva / 100), 2);
    }

    public function getValoareAttribute()
    {
        return $this->subtotal + $this->valoare_tva;
    }

    public function getTotalOfertaAttribute()
    {
        $discount = 0;
        if($this->client && $this->client->discount_fidelitate != 0) {
            $discount = $this->valoare * $this->client->discount_fidelitate / 100;
        }
        $norma_lucru = 0;
        $produse = $this->produse()->with('produs')->get();
        foreach($produse as $item) {
            $norma_lucru += $item->cantitate * ($item->produs ? $item->produs->norma_lucru : 0);
        }
        $manopera = 0;
        if($this->reseller != 1 && $norma_lucru !== 0) {
            $manopera = $norma_lucru * ($this->compartiment ? $this->compartiment->pret_manopera : 0);
        }

        return round($this->valoare - $discount + $manopera);
    }

    public function getNumeClientAttribute()
    {
        if($this->tip_client === null && $this->client) {
            return $this->client->tip == 1 ? $this->client->nume_complet : $this->client->nume_firma;
        }
        return $this->tip_client == 1 ? $this->nume : $this->nume_firma_client;
    }

    public function getPutereSistemAttribute()
    {
        return ($this->panou ? $this->panou->putere : 1) * $this->numar_panouri / 1000;
    }

    public function getImgMarcaInvertorAttribute()
    {
        return $this->marca_invertor === 'Huawei' ? asset('images/huawei-logo-negru.png') : asset('images/fronius.png');
    }

    public function getImgPackAttribute()
    {
        return $this->marca_invertor === 'Huawei' ? asset('images/silver-package.jpg') : asset('images/gold-package.jpg');
    }

    public function getProductieAnualaAttribute()
    {
        return round($this->putere_sistem * 1227, 2);
    }

    public function getAportPropriuAttribute()
    {
        return $this->calculateAportPropriu();
    }

    public function getAportPropriuCasaVerdeAttribute()
    {
        return $this->calculateAportPropriu(20000);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom functions
    |--------------------------------------------------------------------------
    */

    public function calculateAportPropriu($contributie_afm = 0)
    {
        return $this->total_oferta > $contributie_afm ? $this->total_oferta - $contributie_afm : 0;
    }
}
