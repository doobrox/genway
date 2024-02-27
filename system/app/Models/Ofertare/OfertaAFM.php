<?php

namespace App\Models\Ofertare;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfertaAFM extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'oferta_id',
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

    protected $table = 'oferte_afm';

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
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
        return $this->through('oferta')->has('client');
    }

    public function agent()
    {
        return $this->through('oferta')->has('agent');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    */

    public function getTvaAttribute()
    {
        return $this->oferta ? $this->oferta->tva : 0;
    }

    public function getSubtotalAttribute()
    {
        return $this->oferta ? $this->oferta->subtotal : 0;
    }

    public function getValoareTvaAttribute()
    {
        return $this->oferta ? $this->oferta->valoare_tva : 0;
    }

    public function getValoareAttribute()
    {
        return $this->oferta ? $this->oferta->valoare : 0;
    }

    public function getTotalOfertaAttribute()
    {
        return $this->oferta ? $this->oferta->total_oferta : 0;
    }

    public function getNumeClientAttribute()
    {
        return $this->oferta->nume_client;
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
        return $this->oferta ? $this->oferta->aport_propriu : 0;
    }

    public function getAportPropriuCasaVerdeAttribute()
    {
        return $this->oferta ? $this->oferta->aport_propriu_casa_verde : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    public static function rules($all = null)
    {
        return [
            'tip_invertor' => ['required', 'string', 'max:255'],
            'marca_invertor' => ['required', 'string', 'max:255'],
            'model_invertor' => ['required', 'string', 'max:255'],
            'marca_panouri' => ['required', 'string', 'max:255'],
            'model_panouri' => ['required', 'string', 'max:255'],
            'numar_panouri' => ['required', 'integer', 'min:1', 'max:1000'],
            'tip_acumulatori' => ['nullable', 'string', 'max:255'],
            'capacitate_acumulatori' => ['nullable', 'numeric', 'min:0', 'max:255'],
            'limita_cablare_dc' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'limita_cablare_ac' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ] + ($all ? [
            'section' => ['required', 'string', 'max:255'],
            'oferta_id' => ['required', 'string', 'max:255'],
        ] : []);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom functions
    |--------------------------------------------------------------------------
    */
}
