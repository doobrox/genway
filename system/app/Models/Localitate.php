<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Localitate extends Model
{
    use HasFactory, HasJsonRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_judet',
        'slug',
        'nume',
        'cod_postal',
        'km_exteriori',
    ];

    protected $table = 'geo_localitati';

    public function judet()
    {
        return $this->belongsTo(Judet::class, 'id_judet');
    }
}
