<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sectiune extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nume',
        'pozitie',
        'activ',
    ];

    protected $table = 'sectiuni';

    public function pagini()
    {
        return $this->belongsToMany(Pagina::class, 'pagini_sectiuni', 'sectiune_id', 'pagina_id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('sectiuni.activ', '1');
        });
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('sectiuni.pozitie', 'sidebar');
        });
    }
}
