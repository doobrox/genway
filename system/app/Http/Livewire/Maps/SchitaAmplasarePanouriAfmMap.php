<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class SchitaAmplasarePanouriAfmMap extends AfmMap
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
        'schita realizata' => [
            'name' => 'Schite realizate',
            'color' => self::TEXT_COLOR['green'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        'schita de realizat' => [
            'name' => 'Schite de realizat',
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
        return array_merge($this->constColumns(), ['status_schita']);
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
        return __('Raport schite realizate per judet');
    }

    protected function customId()
    {
        return 'schita-amplasare-panouri-map';
    }
}