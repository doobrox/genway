<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class DosareNedepuseAfmMap extends AfmMap
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
        '0_32' => [
            'name' => '0-32 de zile de la obtinere CER',
            'color' => self::TEXT_COLOR['yellow'],
            'type' => 'location',
            'shape' => 'triangle',
            'class' => 'pill-yellow',
            'total' => 0,
            'ids' => '',
        ],
        'peste_32' => [
            'name' => 'Peste 32 de zile de la obtinere CER',
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
            $query->where('data_cer', '<>', '')->whereNotNull('data_cer');
        })->whereIn('situatie_dosar', ['CER obtinut']);
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
           \DB::raw('CASE WHEN DATE_ADD(`data_cer`, INTERVAL 32 DAY) >= NOW() THEN "0_32"                       
                ELSE "peste_32"
            END AS `stare_obtinere_cer`')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['stare_obtinere_cer']);
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [];

        switch ($status) {
            case '0_32':
                $conditions['data_cer']['start'] = now()->subDays(32)->format('Y-m-d');
                break;
            case 'peste_32':
                $conditions['data_cer']['end'] = now()->subDays(33)->format('Y-m-d');
                break;
        }
        return [
            'situatie_dosar' => ['CER obtinut'],
        ] + $conditions;
    }

    protected function allKeyName()
    {
        return 'total';
    }

    protected function includeAllKey()
    {
        return true;
    }

    protected function title()
    {
        return  __('Raport dosare nedepuse lunar per judet');
    }

    protected function customId()
    {
        return 'dosare-nedepuse-map';
    }
}