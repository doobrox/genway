<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Produs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_producator',
        'cod_ean13',
        'nume',
        'descriere',
        'greutate',
        'pret',
        'pret_multiplicator',
        'pret_user',
        'tva',
        'reducere_tip',     // 0 - fara reducere / 1 - valorica / 2 - procentuala   
        'reducere_valoare',
        'stoc',
        'stoc_la_comanda',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'promovat_index',
        'promotie',
        'ordonare',
        'ordonare_popular',
        'data_adaugare',
        'activ',
        'norma_lucru',
    ];

    protected $table = 'produse';

    public function galerie()
    {
        return $this->hasMany(ImagineProdus::class, 'id_produs');
    }

    public function comentarii()
    {
        return $this->hasMany(Comentariu::class, 'id_produs');
    }

    public function producator()
    {
        return $this->belongsTo(Producator::class, 'id_producator');
    }

    public function categorie()
    {
        return $this->categorii()->orderBy('id_parinte');
    }

    public function categorii()
    {
        return $this->belongsToMany(Categorie::class, 'produse_categorii', 'id_produs', 'id_categorie');
    }

    public function recomandate()
    {
        return Produs::image()->join('produse_recomandate as r', function ($join) {
            $join->on('r.id_produs_recomandat', '=', 'produse.id')
                ->where('produse.id', $this->id);
        });
    }

    public function getCategorieAttribute()
    {
        return $this->categorie()->first();
    }

    public function getRecomandateAttribute()
    {
        return $this->recomandate()->get();
    }

    public function getFisiereTehniceAttribute()
    {
        $fisiere = DB::table('produse_fisiere')->where('id_produs', $this->id);
        $user = auth()->user();
        if(!($user && $user->reseller == '1')) {
            $fisiere->where('reseller', '0');
        }
        return $fisiere->get();
    }

    public function getDiscountAttribute()
    {
        return $this->reducere_tip > '0' ? true : false;
    }

    public function getImageAttribute()
    {
        return $this->galerie()->firstWhere('principala', 1)->fisier ?? null;
    }

    public function getDiscountValoareAttribute()
    {
        return [
            'value' => $this->reducere_tip == '1' 
                ? $this->pret_intreg - $this->pret_normal
                : $this->reducere_valoare, 
            'unit' => $this->reducere_tip == '1' ? ' lei' : '%',
        ];
    }

    public function getDiscountValoareCuTvaAttribute()
    {
        return [
            'value' => $this->reducere_tip == '1' 
                ? $this->pret_intreg_cu_tva - $this->pret_cu_tva
                : $this->reducere_valoare, 
            'unit' => $this->reducere_tip == '1' ? ' lei' : '%',
        ];
    }

    public function getRatingAttribute()
    {
        return round( $this->comentarii()->avg('nota') , 1);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->nume);
    }

    public function getPretNormalAttribute()
    {
        return $this->applyDiscount();
    }

    public function getPretCuTvaAttribute()
    {
        return $this->applyDiscount(true);
    }

    public function getPretIntregAttribute()
    {
        $camp = self::campPret();
        $pret_intreg = round($this->$camp, 2);
        return $this->pret_normal == $pret_intreg ? null : $pret_intreg;
    }

    public function getPretIntregCuTvaAttribute()
    {
        $camp = self::campPret();
        $tva = $this->produsCotaTva(1);
        $pret_intreg_cu_tva = round($this->$camp * $tva, 2);
        return $this->pret_cu_tva == $pret_intreg_cu_tva ? null : $pret_intreg_cu_tva;
    }

    public function getRouteAttribute()
    {
        return route('product', [
            'categorie' => $this->categorie->slug ?? 'general' ?? Categorie::find(1)->slug,
            'slug' => $this->slug,
            'produs' => $this->id,
        ]);
    }



    public static function produsePromovate()
    {
        return Produs::image()->where('produse.promovat_index', '1')
            ->orderBy('produse.ordonare_popular')
            ->get();
    }

    public static function produseNoi()
    {
        return Produs::image()
            ->orderByDesc('produse.data_adaugare')
            ->orderBy('produse.ordonare_popular')
            ->limit(5)->get();
    }

    protected function applyDiscount($tva = false)
    {
        $value = $this->{self::campPret()};
        $tva = $tva ? $this->produsCotaTva(1) : 1;
        switch ($this->reducere_tip) {
            case '1':
                $result = ($value - $this->reducere_valoare) * $tva;
                break;
            case '2':
                $result = ($value - ($value * $this->reducere_valoare / 100)) * $tva;
                break;
            default:
                $result = $value * $tva;
                break;
        }
        return $result > 0 ? round($result, 2) : 0;
    }

    protected static function campPret()
    {
        return auth()->check() && auth()->user()->reseller == '1' ? 'pret' : 'pret_user';
    }

    protected static function cotaTva($index = 0)
    {
        // 0: get tva (ex: 19%)
        // 1: add tva (ex: 1.19)
        return $index ? 1 + (setare('COTA_TVA') / 100) : setare('COTA_TVA');
    }

    public function produsCotaTva($index = 0)
    {
        // 0: get tva (ex: 19%)
        // 1: add tva (ex: 1.19)
        $tva = $this->tva ?? setare('COTA_TVA');
        return $index ? 1 + ($tva / 100) : $tva;
    }

    public function scopeImage($query)
    {
        return $query->selectRaw('IFNULL(g.fisier, "default.png") as imagine')
            ->leftJoin('general_galerie as g', function ($join) {
                $join->on('g.id_produs', '=', 'produse.id')
                    ->where('g.principala', '1');
            });
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('all', function (Builder $builder) {
            $builder->addSelect('produse.*');
        });
        static::addGlobalScope('quantity', function (Builder $builder) {
            $builder->where('produse.stoc', '>', '0');
        });
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('produse.activ', '1');
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderBy('produse.id');
        });
    }
}
