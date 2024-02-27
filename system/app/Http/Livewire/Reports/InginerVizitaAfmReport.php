<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Http\Livewire\Reports\AfmReport;

class InginerVizitaAfmReport extends AfmReport
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
        })->where('status_aprobare_dosar', 'dosar admis');
    }

    protected function constColumns()
    {
        return array_merge(parent::constColumns(), []);
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE WHEN `ing_vizita` IS NULL OR `ing_vizita` = "" 
                THEN "vizita de realizat" 
                ELSE "vizita realizata" 
            END AS `status_vizita`')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['status_vizita'], parent::groupByColumns());
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
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
            case 'vizita realizata':
                $conditions['ing_vizita']['not_empty'] = true;
                break;
            case 'vizita de realizat':
                $conditions['ing_vizita']['empty'] = true;
                break;
        }

        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        return [
            'vizita realizata' => 'Vizite realizate',
            'vizita de realizat' => 'Vizite de realizat',
        ];
    }

    public function statusHeader()
    {
        return __('Status vizita');
    }
}