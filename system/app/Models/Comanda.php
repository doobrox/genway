<?php

namespace App\Models;

use App\Traits\ConversionTrait;
use App\Traits\MetaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class Comanda extends Model
{
    use HasFactory, ConversionTrait, MetaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'id_curier',
        'cod_voucher',
        'id_tip_plata',
        'bancar',
        'nr_factura',
        'mesaj',
        'nota_interna',
        'taxa_livrare',
        'valoare_voucher',
        'discount_fidelitate',
        'valoare_discount_fidelitate',
        'discount_plata_op',
        'valoare_discount_plata_op',
        'tva',
        'data_adaugare',
        'stare',
        'stare_plata',
        'ip',
        'order_id'
    ];

    protected $table = 'comenzi';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function curier()
    {
        return $this->belongsTo(Curier::class, 'id_curier');
    }

    public function produse()
    {
        return $this->hasMany(ComandaProdus::class, 'id_comanda', 'id');
    }

    public function getTextTipPlataAttribute()
    {
        switch ($this->id_tip_plata) {
            case "1":
                return "Ramburs";
            case "2":
                return "Online Mobilpay.ro";
            case "3":
                return "Transfer bancar";
        }
        return null;
    }

    public function getTextFacturaTipPlataAttribute()
    {
        switch ($this->id_tip_plata) {
            case "1":
                return "prin ramburs";
            case "2":
                return "online";
            case "3":
                return "prin transfer bancar";
        }
        return null;
    }

    public function getTextStareAttribute()
    {
        switch ($this->stare) {
            case "-1":
                return __('Anulata');
            case "0":
                return __('Comanda noua');
            case "1":
                return __('Comanda preluata');
            case "2":
                return __('Comanda livrata');
            case "3":
                return __('Comanda finalizata');
        }
        return null;
    }

    public function getTextStarePlataAttribute()
    {
        switch ($this->stare_plata) {
            case "-2":
                return __('Respinsa');
            case "-1":
                return __('Anulata');
            case "0":
                return __('In procesare');
            case "1":
                return __('Confirmata');
        }
        return null;
    }

    public function getNumeCurierAttribute()
    {
        switch ($this->id_curier) {
            case "1":
                return "Curier rapid";
            case "2":
                return "Curier rapid";
                // return "Livrare bucuresti";
            case "3":
                return "Preluare personala";
        }
        return null;
    }

    public function getValoareProduseAttribute()
    {
        return $this->produse()->sum(DB::raw('comenzi_produse.pret * cantitate'));
    }

    public function getValoareTvaAttribute()
    {
        // $sum = 0;
        // foreach($this->produse as $produs) {
        //     $sum += round($produs->cantitate * ($produs->pret_cos * $this->tva/100), 2);
        // }
        // return $sum;
        return round($this->valoare_produse * ($this->tva/100), 2);
    }

    public function getValoareAttribute()
    {
        return round($this->valoare_produse + $this->valoare_tva + $this->taxa_livrare + $this->valoare_discount_fidelitate + $this->valoare_discount_plata_op + $this->valoare_voucher, 2);
    }

    public static function getNextNumarFactura()
    {
        $nr = self::max('nr_factura');
        return $nr != '' && $nr != null ? str_pad(((int)$nr + 1), 6, '0', STR_PAD_LEFT) : '001000';
    }
}
