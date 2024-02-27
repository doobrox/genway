<?php

namespace App\Models\Ofertare;

use App\Traits\FileAccessTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Firma extends Model
{
    use HasFactory, FileAccessTrait;

    protected $fillable = [
        'nume_firma',
        'reprezentant_firma',
        'adresa_firma',
        'localitate_firma',
        'judet_firma',
        'cod_fiscal_firma',
        'reg_com_firma',
        'cont_firma',
        'banca_firma',
        'logo_firma',
        'nr_contract',
        'data_contract',
        'nr_autorizare',
        'data_valabilitate_autorizare',
        'activ_afm',
    ];

    protected $casts = [
        // 'coloane' => 'array',
        'data_valabilitate_autorizare' => 'datetime:d.m.Y',
    ];

    protected $table = 'firme';

    protected $pivot_overwritten_columns = [
        'nr_contract', 'data_contract'
    ]; 

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sectiuni()
    {
        return $this->belongsToMany(SectiuneAFM::class, 'firma_sectiune_afm', 'firma_id', 'sectiune_id')
            ->withPivot(...$this->pivot_overwritten_columns)->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Table related functions
    |--------------------------------------------------------------------------
    */

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    */

    public function getStampilaAttribute()
    {
        switch (true) {
            case in_array($this->id, [9]):
                return $this->file_assets('assets/ofertare/stampile/stampila_powertech.jpg', 2);
            case in_array($this->id, [16]):
                return $this->file_assets('assets/ofertare/stampile/stampila_satel.jpg', 2);
            case in_array($this->id, [15,18]):
                return $this->file_assets('assets/ofertare/stampile/stampila_motoma.jpg', 2);
            case in_array($this->id, [14]):
                return $this->file_assets('assets/ofertare/stampile/stampila_genway_videointerfoane.jpg', 2);
            default:
                return $this->file_assets('assets/ofertare/stampile/stampila_elsd.jpg', 2);
        }
    }

    public function getImagineStampilaAttribute()
    {
        return $this->stampila ? '<img src="data:image/png;base64,' . base64_encode($this->stampila).'" style="width:150px;height:auto;">' : '';
    }

    /*
    |--------------------------------------------------------------------------
    | Custom functions
    |--------------------------------------------------------------------------
    */

    public function getDetailsForSection($section)
    {
        $section = $this->sectiuni()->where('nume', $section)->first();
        return $section ? $section->pivot->only($this->pivot_overwritten_columns) : null;
    }

    public function updateDetailsForSection($section)
    {
        foreach ($this->getDetailsForSection($section) ?? [] as $key => $value) {
            $this[$key] = $value;
        }
        return $this;
    }
}
