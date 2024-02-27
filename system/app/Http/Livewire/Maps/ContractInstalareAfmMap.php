<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class ContractInstalareAfmMap extends AfmMap
{
    protected $statusColors = [
        
        'total' => [
            'name' => 'Total',
            'color' => self::TEXT_COLOR['black'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-black',
            'total' => 0,
            'ids' => '',
        ],
        'contract semnat' => [
            'name' => 'Contracte semnate',
            'color' => self::TEXT_COLOR['green'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        'contract de semnat' => [
            'name' => 'Contracte de semnat',
            'color' => self::TEXT_COLOR['orange'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-orange',
            'total' => 0,
            'ids' => '',
        ],
    ];

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
        return array_merge($this->constColumns(), ['status_contract_instalare']);
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
        return $conditions;
    }

    protected function sumAllValues()
    {
        return true;
    }

    protected function allKeyName()
    {
        return 'total';
    }

    protected function includeAllKey()
    {
        return false;
    }

    protected function title()
    {
        return __('Raport contracte de instalare per judet');
    }

    protected function customId()
    {
        return 'contract-instalare-map';
    }
}