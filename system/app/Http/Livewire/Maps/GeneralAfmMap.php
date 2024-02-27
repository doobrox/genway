<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class GeneralAfmMap extends AfmMap
{
    protected function itemsConditions($query)
    {
        return $query->where(function($query) {
            $query->where('stare_montaj', '<>', '')->whereNotNull('stare_montaj');
        });
    }

    protected function constColumns()
    {
        return array_merge(parent::constColumns(), ['stare_montaj']);
    }

    protected function title()
    {
        return __('Raport general per judet');
    }
}