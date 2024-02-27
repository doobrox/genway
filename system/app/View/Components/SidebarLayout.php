<?php

namespace App\View\Components;

use App\Models\Categorie;
use App\Models\Producator;
use App\Models\Produs;
use App\Models\Sectiune;
use Illuminate\View\Component;

class SidebarLayout extends Component
{
    public $min;
    public $max;

    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function render()
    {
        return view('layouts.sidebar', [
            'sectiuni' => Sectiune::with('pagini')->get(),
            'categorii' => Categorie::where('id_parinte', '0')->get(),
            'producatori' => Producator::orderBy('nume')->get(),
            'filtre' => in_array(request()->route()->getName(), ['product','products','category','producer']),
            'search' => request()->input('search'),
            'min' => $this->min ?? Produs::min(Produs::campPret()),
            'max' => $this->max ?? Produs::max(Produs::campPret()),
            'prices' => [
                '0-50' => __('Sub :pret', ['pret' => 50]),
                '50-100' => '50-100',
                '100-200' => '100-200',
                '200' => __('Peste :pret', ['pret' => 200]),
            ],
        ]);
    }
}
