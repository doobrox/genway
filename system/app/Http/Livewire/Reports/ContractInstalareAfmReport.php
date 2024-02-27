<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Http\Livewire\Reports\AfmReport;

class ContractInstalareAfmReport extends AfmReport
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
        })->where('status_aprobare_dosar', 'dosar admis');
    }

    protected function constColumns()
    {
        return array_merge(parent::constColumns(), []);
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE WHEN `fisier_semnat_instalare` IS NULL OR `fisier_semnat_instalare` = "" 
                THEN "contract de semnat" 
                ELSE "contract semnat" 
            END AS `status_contract_instalare`')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['status_contract_instalare'], parent::groupByColumns());
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
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
            case 'contract semnat':
                $conditions['fisier_semnat_instalare']['not_empty'] = true;
                break;
            case 'contract de semnat':
                $conditions['fisier_semnat_instalare']['empty'] = true;
                break;
        }

        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        return [
            'contract semnat' => 'Contracte semnate',
            'contract de semnat' => 'Contracte de semnat',
        ];
    }

    public function statusHeader()
    {
        return __('Status contracte');
    }
}