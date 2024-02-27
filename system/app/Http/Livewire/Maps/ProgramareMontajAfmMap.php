<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class ProgramareMontajAfmMap extends AfmMap
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
        'lucrare programata' => [
            'name' => 'Lucrare programata',
            'color' => self::TEXT_COLOR['green'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        'lucrare de programat' => [
            'name' => 'Lucrare de programat',
            'color' => self::TEXT_COLOR['red'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-red',
            'total' => 0,
            'ids' => '',
        ],
    ];

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
        return array_merge($this->constColumns(), ['programare_montaj']);
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
                $conditions['or'] = [
                    'data_inceput_estimare_montaj' => ['empty' => true],
                    'data_sfarsit_estimare_montaj' => ['empty' => true],
                    'data_trimitere_informare_montaj' => ['empty' => true],
                ];
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
        return __('Raport programare montaj per judet');
    }

    protected function customId()
    {
        return 'programare-montaj-map';
    }
}