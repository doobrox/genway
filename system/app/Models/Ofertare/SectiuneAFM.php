<?php

namespace App\Models\Ofertare;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectiuneAFM extends Model
{
    use HasFactory;

    protected $fillable = [
        'nume',
        'titlu',
    ];

    protected $casts = [];

    protected $table = 'sectiuni_afm';

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function coloane()
    {
        return $this->belongsToMany(ColoanaTabelAFM::class, 'coloana_sectiune_afm', 'sectiune_id', 'coloana_id')->withTimestamps();
    }

    public function hasColumn($name)
    {
        return $this->coloane->contains(is_int($name) ? 'id' : 'nume', $name);
    }
}
