<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Componenta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'an',
        'sursa',            // sursa: 1 (genway) / 2 (sistemfotovoltaic)
        'componenta',
        'titlu',
        'putere_invertor',
        'marca_invertor',
        'numar_panouri',
        'putere_panouri',
        'marca_panouri',
        'tip_acumulatori',
        'capacitate_acumulatori',
        'aport_propriu',
        'contributie_afm',
        'descriere'
    ];

    protected $table = 'componente';

    protected $nr_componente = 9;


    public function getEditable()
    {
        return collect($this->getFillable())->diff(['sursa', 'componenta'])->values()->toArray();
    }

    public static function getEditableColumns()
    {
        return (new static)->getEditable();
    }

    public static function rules($id = null)
    {
        return [
            'an' => ['required', 'string', 'max:255', Rule::exists(Ofertare\SectiuneAFM::class, 'nume')],
            'titlu' => ['required', 'string', 'max:255'],
            'putere_invertor' => ['required', 'numeric', 'max:999', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'marca_invertor' => ['required', 'string', 'max:255', Rule::in(Ofertare\Invertor::MARCA_VALUES)],
            'numar_panouri' => ['required', 'integer', 'max:100000'],
            'putere_panouri' => ['required', 'numeric', 'max:999', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'marca_panouri' => ['required', 'string', 'max:255', Rule::in(Ofertare\Panou::MARCA_VALUES)],
            'tip_acumulatori' => ['nullable', 'string', 'max:255'],
            'capacitate_acumulatori' => ['nullable', 'numeric', 'max:999999', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'aport_propriu' => ['required', 'numeric', 'max:99999999', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'contributie_afm' => ['required', 'numeric', 'max:99999999', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
            'descriere' => ['nullable', 'string', 'max:100000']
        ];
    }

    public static function messages()
    {
        return [
            '*.regex' => __('Campul nu trebuie sa aiba mai mult de 2 decimale'),
        ];
    }

    public static function names()
    {
        return [
            'an' => __('sectiune'),
        ];
    }


    public static function getComponents($section = null, $sursa = 1, $nr_componente = 9)
    {
        $components = Componenta::where('sursa', $sursa)
            ->where('an', $section ?? date('Y'))
            ->limit($nr_componente)
            ->orderByDesc('created_at')
            ->orderBy('componenta')
            ->get();
        return $components->count() ? $components : Componenta::getLastComponents($section, $sursa, $nr_componente);
    }

    public static function getLastComponents($section = null, $sursa = 1, $nr_componente = 9)
    {
        return Componenta::where('sursa', $sursa)
            ->where('an', $section ?? date('Y'))
            ->limit($nr_componente)
            ->orderByDesc('created_at')
            ->orderBy('componenta')
            ->get();
    }

    public static function getComponenta($componenta = 1, $section = null, $sursa = 1)
    {
        $componenta = Componenta::where('sursa', $sursa)
            ->where('an', $section ?? date('Y'))
            ->where('componenta', $componenta)
            ->orderByDesc('created_at')
            ->first();
        return $componenta ? $componenta : Componenta::getLastComponenta($componenta, $section, $sursa);
    }

    public static function getLastComponenta($componenta = 1, $section = null, $sursa = 1)
    {
        return Componenta::where('sursa', $sursa)
            ->where('an', $section ?? date('Y'))
            ->where('componenta', $componenta)
            ->orderByDesc('created_at')
            ->first();
    }
}
