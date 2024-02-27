<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_parinte',
        'slug',
        'nume',
        'descriere',
        'descriere_jos',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'imagine',
        'ordonare',
        'data_adaugare',
        'activ',
    ];

    protected $table = 'categorii';

    public function parinte()
    {
        return $this->belongsTo(Categorie::class, 'id_parinte');
    }

    public function subcategorii()
    {
        return $this->hasMany(Categorie::class, 'id_parinte');
    }

    public function produse()
    {
        return $this->belongsToMany(Produs::class, 'produse_categorii', 'id_categorie', 'id_produs');
    }

    public function breadcrumbs(Produs $produs = null)
    {
        $breadcrumbs = [];
        if($produs) {
            $breadcrumbs[$produs->nume] = 'active';
            $breadcrumbs[$this->nume] = route('category', $this->slug);
        } else {
            $breadcrumbs[$this->nume] = 'active';
        }
        $parinte = $this->parinte;
        while($parinte && $parinte = $parinte->parinte) {
            $breadcrumbs[$parinte->nume] = route('category', $parinte->slug);
        }
        return array_reverse($breadcrumbs);
    }

    public static function breadcrumb(Produs $produs)
    {
        return [$produs->nume => 'active'];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('categorii.activ', '1');
        });
        static::addGlobalScope('ordine', function (Builder $builder) {
            $builder->orderBy('ordonare');
        });
    }
}
