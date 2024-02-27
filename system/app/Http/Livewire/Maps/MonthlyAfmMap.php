<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;

class MonthlyAfmMap extends AfmMap
{
    protected $statusColors = [
        'total' => [
            'name' => 'Total',
            'color' => self::TEXT_COLOR['black'],
            'type' => 'location',
            'shape' => 'square',
            'total' => 0,
            'ids' => '',
        ],
        'sistem nemontat' => [
            'name' => 'Sistem nemontat',
            'color' => self::TEXT_COLOR['red'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-red',
            'total' => 0,
            'ids' => '',
        ],
        'sistem nemontat, pregatit pentru instalare' => [
            'name' => 'Sistem nemontat, pregatit pentru instalare',
            'color' => self::TEXT_COLOR['orange'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-orange',
            'total' => 0,
            'ids' => '',
        ]
    ];

    public $luna = 5;

    public function updateMonth()
    {
        $validatedData = $this->validate([
            'luna' => ['nullable','integer','min:5','max:12']
        ]);
        $this->luna = $validatedData['luna'] ?? null;
        $this->main_settings['auto_load'] = 'no';
        $this->build($this->items());
        $this->dispatch('updatedMonth', ...$this->variables());
    }

    protected function itemsConditions($query)
    {
        if($this->luna) {
            $query->where('data_semnare_afm', '<=', now()->subMonths($this->luna));
        }
        return $query->whereIn('stare_montaj', ['sistem nemontat','sistem nemontat, pregatit pentru instalare']);
    }

    protected function constColumns()
    {
        return array_merge(parent::constColumns(), ['stare_montaj']);
    }

    protected function query($status = null, $value = null)
    {
        return [
            'stare_montaj' => $status,
        ] + ($this->luna ? [
            // 'data_semnare_afm_sign' => '<=',
            'data_semnare_afm' => ['end' => now()->subMonths($this->luna)->format('Y-m-d')],
        ] : []);
    }

    protected function sumAllValues()
    {
        return true;
    }

    protected function allKeyName()
    {
        return 'total';
    }

    protected function title()
    {
        return __('Raport sisteme nemontate per judet');
    }

    protected function customId()
    {
        return 'monthly-map';
    }
}