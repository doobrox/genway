<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class Galerie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titlu',
        'data_adaugare',
    ];

    protected $table = 'pagini_galerii';

    public function imagini()
    {
        return DB::table('pagini_galerii_imagini')->where('id_galerie', $this->id);
    }

    public function getImaginiAttribute()
    {
        return $this->imagini()->get();
    }
}
