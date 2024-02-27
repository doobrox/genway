<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Http\Livewire\Reports\AfmReport;

class ProgramareMontajAfmReport extends AfmReport
{
    protected function itemsConditions($query)
    {
        return $query->where(function($query) {
            $query->whereIn('stare_montaj', [
                'sistem nemontat', 
                'sistem nemontat, pregatit pentru instalare', 
                'sistem livrat', 
                'invertor lipsa', 
                'alte componente lipsa',
            ]);
        })->whereNotNull('data_aprobare_afm');
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE WHEN `data_inceput_estimare_montaj` IS NOT NULL 
                    AND `data_sfarsit_estimare_montaj` IS NOT NULL 
                    AND `data_trimitere_informare_montaj` IS NOT NULL
                THEN "lucrare programata" 
                ELSE "lucrare de programat" 
            END AS `programare_montaj`')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['programare_montaj'], parent::groupByColumns());
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
            'data_aprobare_afm' => ['not_empty' => true],
            'stare_montaj' => [
                'sistem nemontat', 
                'sistem nemontat, pregatit pentru instalare', 
                'sistem livrat', 
                'invertor lipsa', 
                'alte componente lipsa',
            ],
        ];
        switch ($status) {
            case 'lucrare programata':
                $conditions['data_inceput_estimare_montaj'] = ['not_empty' => true];
                $conditions['data_sfarsit_estimare_montaj'] = ['not_empty' => true];
                $conditions['data_trimitere_informare_montaj'] = ['not_empty' => true];
                break;
            case 'lucrare de programat':
                $conditions['data_trimitere_informare_montaj'] = ['empty' => true];
                break;
        }

        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        return [
            'lucrare programata' => 'Lucrare programata',
            'lucrare de programat' => 'Lucrare de programat',
        ];
    }

    public function statusHeader()
    {
        return __('Status programare montaj');
    }

    protected function title()
    {
        return __('Raport programare montaj per regiune');
    }

    protected function customId()
    {
        return 'programare-montaj-report';
    }
}