<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfertareForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'an',
        'nume',
        'prenume',
        'email',
        'judet',
        'telefon',
        'cod',
    ];

    protected $casts = [];

    protected $table = 'fotovoltaice_ofertare';

    public function formular()
    {
        // return $this->belongsTo(AfmForm::class, 'formular_id', 'id')->setQuery(
        //     (new AfmForm($this->an))->where('id', $this->formular_id)->getQuery()
        // )->setModel(new AfmForm($this->an));
        return $this->belongsTo(AfmForm::setSection($this->an), 'email', 'email')->where('telefon', $this->telefon);
    }

    public function formularInfo()
    {
        $formular = AfmForm::setSection($this->an);
        return $this->hasOneThrough(AfmForm::table($formular->getInfoTable()), $formular, 'id', 'id_formular', 'email', 'email');
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
