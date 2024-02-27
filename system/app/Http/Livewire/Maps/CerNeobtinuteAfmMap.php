<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class CerNeobtinuteAfmMap extends AfmMap
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
        '0_30' => [
            'name' => '0-30 de zile de la montaj',
            'color' => self::TEXT_COLOR['orange'],
            'type' => 'location',
            'shape' => 'triangle',
            'class' => 'pill-orange',
            'total' => 0,
            'ids' => '',
        ],
        '31_60' => [
            'name' => '31-60 de zile de la montaj',
            'color' => self::TEXT_COLOR['pink'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-pink',
            'total' => 0,
            'ids' => '',
        ],
        'peste_60' => [
            'name' => 'Peste 60 de zile de la montaj',
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
        return $query/*->where(function($query) {
            $query->where('stare_montaj', 'sistem complet montat')
                ->orWhere(function($subquery) {
                    $subquery->where('data_pv_receptie', '<>', '')->whereNotNull('data_pv_receptie');
                });
        })*/->where(function($subquery) {
            $subquery->where('data_pv_receptie', '<>', '')->whereNotNull('data_pv_receptie');
        })->where(function($query) {
            $query->whereNull('situatie_dosar')->orwhereIn('situatie_dosar', ['', 'nedepus', 'dosar tehnic depus']);
        });
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE WHEN DATE_ADD(data_pv_receptie, INTERVAL 30 DAY) >= NOW() THEN "0_30"
                WHEN  NOW() BETWEEN DATE_ADD(data_pv_receptie, INTERVAL 31 DAY) AND DATE_ADD(data_pv_receptie, INTERVAL 60 DAY) THEN "31_60"                          
                ELSE "peste_60"
            END AS stare_pv_receptie')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['stare_pv_receptie']);
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
            'situatie_dosar' => ['empty' => true, 'nedepus', 'dosar tehnic depus'],
            // 'data_pv_receptie' => ['not_empty' => true]
        ];

        switch ($status) {
            case '0_30':
                $conditions['data_pv_receptie']['start'] = now()->subDays(30)->format('Y-m-d');
                break;
            case '31_60':
                $conditions['data_pv_receptie'] = [
                    'start' => now()->subDays(60)->format('Y-m-d'),
                    'end' => now()->subDays(31)->format('Y-m-d'), 
                ];
                break;
            case 'peste_60':
                $conditions['data_pv_receptie']['end'] = now()->subDays(61)->format('Y-m-d');
                break;
            case 'total':
                $conditions['data_pv_receptie']['end'] = now()->format('Y-m-d');
                break;
        }
        return $conditions;
    }

    protected function includeAllKey()
    {
        return true;
    }

    protected function allKeyName()
    {
        return 'total';
    }

    protected function title()
    {
        return __('Raport CER-uri neobtinute per judet');
    }

    protected function customId()
    {
        return 'cer-neobtinute-map';
    }
}