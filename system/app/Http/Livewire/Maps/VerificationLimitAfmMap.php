<?php

namespace App\Http\Livewire\Maps;

use App\Exports\Ofertare\VerificationLimitExport;
use App\Http\Livewire\Maps\AfmMap;
use App\Models\AfmForm;

class VerificationLimitAfmMap extends AfmMap
{
    public $export = true;
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
        '0_4' => [
            'name' => '0-4 de zile de la data alegere instalator',
            'color' => self::TEXT_COLOR['green'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        '5_10' => [
            'name' => '5-10 de zile de la data alegere instalator',
            'color' => self::TEXT_COLOR['pink'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-pink',
            'total' => 0,
            'ids' => '',
        ],
        '11_20' => [
            'name' => '11-20 de zile de la data alegere instalator',
            'color' => self::TEXT_COLOR['yellow'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-yellow',
            'total' => 0,
            'ids' => '',
        ],
        '21_25' => [
            'name' => '21-25 de zile de la data alegere instalator',
            'color' => self::TEXT_COLOR['orange'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-orange',
            'total' => 0,
            'ids' => '',
        ],
        'intarziate' => [
            'name' => 'Intarziate',
            'color' => self::TEXT_COLOR['red'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-red',
            'total' => 0,
            'ids' => '',
        ],
    ];

    public function exportExcel()
    {
        $this->skipRender();
        return (new VerificationLimitExport($this->section))->download(
            config('app.name').'_raport_'.$this->section.'_per_data_alegere_instalator_'.date('Y-m-d H:i:s').'.xlsx'
        );
    }

    protected function itemsConditions($query)
    {
        return $query->where(function($query) {
            $query->where('status_aprobare_dosar', '')->orWhereNull('status_aprobare_dosar');
        })->where(function($query) {
            $query->where('data_alegere_instalator', '<>', '')->whereNotNull('data_alegere_instalator');
        })->whereDate('data_alegere_instalator', '<=', now()->format('Y-m-d'));
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), [
            \DB::raw('CASE
                WHEN `data_alegere_instalator` > DATE(NOW()) THEN "in_termeni"
                WHEN DATE_ADD(`data_alegere_instalator`, INTERVAL 4 DAY) >= DATE(NOW()) THEN "0_4"
                WHEN DATE_ADD(`data_alegere_instalator`, INTERVAL 10 DAY) >= DATE(NOW()) THEN "5_10"
                WHEN DATE_ADD(`data_alegere_instalator`, INTERVAL 20 DAY) >= DATE(NOW()) THEN "11_20"
                WHEN DATE_ADD(`data_alegere_instalator`, INTERVAL 25 DAY) >= DATE(NOW()) THEN "21_25"
                ELSE "intarziate"
            END AS `limita_verificare`')
        ]);
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ['limita_verificare']);
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [
            'status_aprobare_dosar' => ['empty' => true]
        ];

        switch ($status) {
            case 'intarziate':
                $conditions['data_alegere_instalator']['end'] = now()->subDays(26)->format('Y-m-d');
                break;
            case '0_4':
                $conditions['data_alegere_instalator'] = [
                    'start' => now()->subDays(4)->format('Y-m-d'),
                    'end' => now()->format('Y-m-d'),
                ];
                break;
            case '5_10':
                $conditions['data_alegere_instalator'] = [
                    'start' => now()->subDays(10)->format('Y-m-d'),
                    'end' => now()->subDays(5)->format('Y-m-d'),
                ];
                break;
            case '11_20':
                $conditions['data_alegere_instalator'] = [
                    'start' => now()->subDays(20)->format('Y-m-d'),
                    'end' => now()->subDays(11)->format('Y-m-d'),
                ];
                break;
            case '21_25':
                $conditions['data_alegere_instalator'] = [
                    'start' => now()->subDays(25)->format('Y-m-d'),
                    'end' => now()->subDays(21)->format('Y-m-d'),
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
        return true;
    }

    protected function title()
    {
        return  __('Raport data alegere instalator per judet');
    }

    protected function customId()
    {
        return 'data-alegere-instalator-map';
    }
}
