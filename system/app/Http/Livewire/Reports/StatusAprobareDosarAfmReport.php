<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Http\Livewire\Reports\AfmReport;

class StatusAprobareDosarAfmReport extends AfmReport
{
    protected function itemsConditions($query)
    {
        return $query->where(function($query) {
            $query->whereIn('stare_montaj', [
                'sistem montat complet', 
                'sistem nemontat', 
                'sistem nemontat, pregatit pentru instalare', 
                'sistem livrat', 
                'invertor lipsa', 
                'alte componente lipsa',
            ])->orWhereNull('stare_montaj');
        })->where(function($query) {
            $query->where('status_aprobare_dosar', '<>', '')->whereNotNull('status_aprobare_dosar');
        });
    }

    protected function constColumns()
    {
        return ['status_aprobare_dosar'];
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
            'stare_montaj' => [
                'sistem montat complet', 
                'sistem nemontat', 
                'sistem nemontat, pregatit pentru instalare', 
                'sistem livrat', 
                'invertor lipsa', 
                'alte componente lipsa',
                'empty' => true
            ],
            'status_aprobare_dosar' => $status
        ];

        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        return ColoanaTabelAFM::coloana('status_aprobare_dosar')['default_values'] ?? [];
    }

    public function statusHeader()
    {
        return __('Status dosare');
    }
}