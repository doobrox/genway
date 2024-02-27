<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Http\Livewire\Reports\AfmReport;

class VerificationLimitAfmReport extends AfmReport
{
    protected const LIMITE = [
        '0_4' => 4, 
        '5_10' => 10, 
        '11_20' => 20, 
        '21_25' => 25, 
        'intarziate' => null, 
    ];

    protected function itemsConditions($query)
    {

        $query->where(function($query) {
            $query->where('status_aprobare_dosar', '')->orWhereNull('status_aprobare_dosar');
        })->where(function($query) {
            $query->where('data_alegere_instalator', '<>', '')->whereNotNull('data_alegere_instalator');
        })->whereDate('data_alegere_instalator', '<=', now()->format('Y-m-d'));
        if(!$this->per_regiuni) {
            $query->whereNull('judet_imobil');
        }
        return $query;
    }

    protected function selectColumns()
    {
        $raw_query = 'CASE WHEN data_alegere_instalator > DATE(NOW()) THEN "in_termeni"';
        foreach (self::LIMITE as $key => $value) {
            $raw_query .= $value 
                ? ' WHEN DATE_ADD(data_alegere_instalator, INTERVAL '.$value.' DAY) >= DATE(NOW()) THEN "'.$key.'"'
                : ' ELSE "'.$key.'"';
        }
        $raw_query .= ' END AS limita_verificare';

        return array_merge([\DB::raw($raw_query)]);
        // return [
        //     \DB::raw('CASE 
        //         WHEN data_alegere_instalator > DATE(NOW()) THEN "in_termeni"
        //         WHEN DATE_ADD(data_alegere_instalator, INTERVAL 4 DAY) >= DATE(NOW()) THEN "0_4"
        //         WHEN DATE_ADD(data_alegere_instalator, INTERVAL 10 DAY) >= DATE(NOW()) THEN "5_10"
        //         WHEN DATE_ADD(data_alegere_instalator, INTERVAL 20 DAY) >= DATE(NOW()) THEN "11_20"
        //         WHEN DATE_ADD(data_alegere_instalator, INTERVAL 25 DAY) >= DATE(NOW()) THEN "21_25"
        //         ELSE "intarziate"
        //     END AS limita_verificare')
        // ];
    }

    protected function groupByColumns()
    {
        return array_merge(['limita_verificare'], ($this->per_regiuni ? ['regiune'] : []));
    }

    protected function query($status = null, $value = null)
    {
        $conditions = ['status_aprobare_dosar' => ['empty' => true]];

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
        return $conditions + parent::query($status, $value);
    }

    protected function limits()
    {
        $limits = [];
        foreach (self::LIMITE as $key => $value) {
            $limits[$key] = $value ? __(':limit zile', ['limit' => str_replace('_', '-', $key)]) : __(ucfirst($key));
        }
        return $limits;
    }

    public function statusHeader()
    {
        return __('Zile ramase pana la verificare');
    }
}