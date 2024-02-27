<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nume',
        'pret_primul_kg',
        'pret_kg_aditional',
        'taxa_ramburs',
        'procent_ramburs',
        'taxa_km_exteriori',
        'default',
        'activ',
    ];

    protected $table = 'curieri';

    public function pret($cart = null, $id_localitate = null)
    {
        $pret = $this->pret_primul_kg;
        $cart = $cart ?? session('cart') ?? [];
        if(isset($cart['items'])) {
            $produse = Produs::whereIn('id', array_keys($cart['items']))->get();
            $greutate = 0;
            foreach($produse as $produs) {
                $greutate += $produs->greutate * $cart['items'][$produs->id]['qty'];
            }
            $greutate = $greutate - 1 > 0 ? $greutate - 1 : 0;
            $km_exteriori = 0;
            if($id_localitate && $localitate = Localitate::firstWhere('id', $id_localitate)) {
                $km_exteriori = $localitate->km_exteriori;
            } elseif(auth()->check()) {
                $km_exteriori = auth()->user()->localitate ? auth()->user()->localitate->km_exteriori : 0;
            }
            // calculate price
            $pret += ($greutate * $this->pret_kg_aditional) + ($km_exteriori * $this->taxa_km_exteriori) + ($cart['total'] * $this->procent_ramburs / 100);
            // add tva
            $pret *= (1 + setare('COTA_TVA')/100) ;
            return round($pret, 2);
        }
        return 0;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('activ', function (Builder $builder) {
            $builder->where('curieri.activ', '1');
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderBy('curieri.nume');
        });
    }
}
