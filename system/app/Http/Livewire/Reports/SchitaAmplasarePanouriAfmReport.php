<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Http\Livewire\Reports\AfmReport;

class SchitaAmplasarePanouriAfmReport extends AfmReport
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
        })->where('status_aprobare_dosar', 'dosar admis');
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE WHEN `schita_amplasare_panouri` IS NULL OR `schita_amplasare_panouri` = "" 
                THEN "schita de realizat" 
                ELSE "schita realizata" 
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
            case 'schita realizata':
                $conditions['schita_amplasare_panouri']['not_empty'] = true;
                break;
            case 'schita de realizat':
                $conditions['schita_amplasare_panouri']['empty'] = true;
                break;
        }

        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        return [
            'schita realizata' => 'Schite realizate',
            'schita de realizat' => 'Schite de realizat',
        ];
    }

    public function statusHeader()
    {
        return __('Status schita');
    }

    protected function title()
    {
        return __('Raport schite realizate per regiune');
    }

    protected function customId()
    {
        return 'schita-amplasare-panouri-report';
    }
}