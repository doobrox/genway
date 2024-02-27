<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class InginerVizitaAfmMap extends AfmMap
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
        'vizita realizata' => [
            'name' => 'Vizite realizate',
            'color' => self::TEXT_COLOR['green'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        'vizita de realizat' => [
            'name' => 'Vizite de realizat',
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
        return array_merge($this->constColumns(), ['status_vizita']);
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
        return __('Raport vizite per judet');
    }

    protected function customId()
    {
        return 'inginer-vizita-map';
    }
}