<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class VerificareSchitaPanouriAfmMap extends AfmMap
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
        'schita verificata' => [
            'name' => 'Schite verificate',
            'color' => self::TEXT_COLOR['green'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        'schita de verificat' => [
            'name' => 'Schite de verificat',
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
        return array_merge($this->constColumns(), ['status_schita']);
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
        return __('Raport schite verificate per judet');
    }

    protected function customId()
    {
        return 'verificare-schita-panouri-map';
    }
}