<?php

namespace App\Models\Ofertare;

use App\Casts\FormatDate;
use App\Models\User;
use App\Models\Ofertare\Programare;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Executie extends Model
{
    use HasFactory;

    protected $fillable = [
        'programare_id',
        'status',
        'siruri_panouri',
        'poze_panouri',
        'poza_invertor',
        'poza_smartmeter',
        'poza_dc_box',
        'poza_siguranta_ac',
        'poza_legare_structuri',
        'poza_cablu_casa',
        'poza_valoare_masurata_priza',
        'poze_panouri',
        'link_sistem',
    ];

    protected $casts = [
        'siruri_panouri' => 'array',
        'poze_panouri' => 'array',
    ];

    protected $table = 'program_executie';

    public function echipa()
    {
        return $this->belongsToMany(User::class, 'echipa_programare', 'programare_id', 'user_id', 'programare_id')
            ->withPivot('procent','lider');
    }

    public function pivotEchipa()
    {
        return $this->hasMany(EchipaProgramare::class, 'programare_id', 'programare_id');
    }

    public function lider()
    {
        return $this->echipa()->wherePivot('lider', 1)->first();
    }

    public function listaEchipa()
    {
        $echipa = $this->echipa()->select('nume','prenume')->get()->map(function ($item, $key) {
            if($item['pivot']['lider']) {
                return '<b>'.$item['nume'].' '.$item['prenume'].' (Lider)</b>';
            } else {
                return $item['nume'].' '.$item['prenume'];
            }
        });
        return '<ul><li>'.$echipa->implode('</li><li>').'</li></ul>';
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function scopeAvailable($query)
    {
        return $query->whereNot('status', '2');
    }

    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                switch ($this->status) {
                    case '0':
                        return __('Noua');
                    case '1':
                        return __('Asteapta confirmare');
                    case '2':
                        return __('Confirmata');
                    case '3':
                        return __('Necesita schimbari');
                    default:
                        return __('Noua');
                        break;
                }
            },
        );
    }
}
