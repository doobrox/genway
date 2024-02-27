<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Http\Livewire\Reports\AfmReport;

class VerificareSchitaPanouriAfmReport extends AfmReport
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
            $query->where('fisier_semnat_instalare', '<>', '')->whereNotNull('fisier_semnat_instalare');
        })->where(function($query) {
            $query->where('ing_vizita', '<>', '')->whereNotNull('ing_vizita');
        })->where(function($query) {
            $query->where('schita_amplasare_panouri', '<>', '')->whereNotNull('schita_amplasare_panouri');
        })->where('status_aprobare_dosar', 'dosar admis');
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE WHEN `verificare_schita_panouri` = "1" 
                THEN "schita verificata" 
                ELSE "schita de verificat" 
            END AS `status_schita`')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['status_schita'], parent::groupByColumns());
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
            'schita_amplasare_panouri' => ['not_empty' => true],
            'ing_vizita' => ['not_empty' => true],
            'fisier_semnat_instalare' => ['not_empty' => true],
            'status_aprobare_dosar' => 'dosar admis',
            'stare_montaj' => [
                'sistem montat complet', 
                'sistem nemontat', 
                'sistem nemontat, pregatit pentru instalare', 
                'sistem livrat', 
                'invertor lipsa', 
                'alte componente lipsa',
                'empty' => true
            ],
        ];
        switch ($status) {
            case 'schita verificata':
                $conditions['verificare_schita_panouri'] = '1';
                break;
            case 'schita de verificat':
                $conditions['verificare_schita_panouri'] = '0';
                break;
        }

        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        return [
            'schita verificata' => 'Schite verificate',
            'schita de verificat' => 'Schite de verificat',
        ];
    }

    public function statusHeader()
    {
        return __('Status schita');
    }

    protected function title()
    {
        return __('Raport schite verificate per regiune');
    }

    protected function customId()
    {
        return 'verificare-schita-panouri-report';
    }
}