<?php

namespace App\Models\Ofertare;

use App\Models\AfmForm;
use App\Casts\FormatDate;
use App\Models\Fisier;
use App\Models\Judet;
use App\Models\Localitate;
use App\Models\User;
use App\Models\Ofertare\SarcinaMesaj;
use App\Traits\FileAccessTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Sarcina extends Model
{
    use FileAccessTrait, HasJsonRelationships;
    // use FileAccessTrait;

    protected $fillable = [
        'title',
        'description',
        'attachments',
        'year',     // trebuie redenumita section
        'from_id',
        'to_id',
        'return_id',
        'formular_id',
        'type', // 0 = cu finalizare / 1 = cu intoarcere / 2 = automata
        'status', // 0 = in lucru / 1 = finalizat / 2 = verificat (la intoarcere)
        'client_manual', // 1 = informatiile clientului sunt puse manual, 0 = informatiile clientului sunt luate din formular
        'info_client',
        'finished_at',
    ];

    protected $table = 'sarcini';

    protected $casts = [
        'info_client' => 'object',
    ];

    public static $types = [
        0 => 'Cu finalizare',
        1 => 'Cu intoarcere',
        2 => 'Automata',
    ];

    public static $stats = [
        0 => 'In lucru',
        1 => 'Finalizat',
        2 => 'Verificat',
    ];

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function mesaje()
    {
        return $this->hasMany(SarcinaMesaj::class, 'sarcina_id');
    }

    public function fisiere()
    {
        return $this->morphMany(Fisier::class, 'model');
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    // public function getUserName($column = 'from_id')
    // {
    //     return User::select(['id', \DB::raw('CONCAT(nume, " ", prenume) as nume_complet')])->find($this->$column)->nume_complet;
    // }

    public function judetImobil()
    {
        return $this->client_manual == 1 ? $this->belongsTo(Judet::class, 'info_client->judet_imobil') : $this->getAfmForm()->judetImobil();
    }

    public function localitateImobil()
    {
        return $this->belongsTo(Localitate::class, 'info_client->localitate_imobil');
    }

    public function getAfmForm() {
        return AfmForm::setSection($this->year)->where('id', '=', $this->formular_id)->withinfo()->first() ?? $this->info_client;
    }

    protected function numeJudetImobil(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->judetImobil ? $this->judetImobil->nume : null,
        );
    }

    protected function numeLocalitateImobil(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->localitateImobil ? $this->localitateImobil->nume : null,
        );
    }

    protected function numeFromComplet(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->from ? $this->from->nume_complet : null,
        );
    }

    protected function numeToComplet(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->to ? $this->to->nume_complet : null,
        );
    }
}
