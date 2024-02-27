<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Http\Livewire\Maps\AfmMap;
use Carbon\Carbon;

class DosareDepuseAfmMap extends AfmMap
{
    public $luna = 1;
    public $an = 2023;
    public $inceput_luna = 11;
    public $sfarsit_luna = 10;

    public $months = [
        '1' => 'Ianuarie',
        '2' => 'Februarie',
        '3' => 'Martie',
        '4' => 'Aprilie',
        '5' => 'Mai',
        '6' => 'Iunie',
        '7' => 'Iulie',
        '8' => 'August',
        '9' => 'Septembrie',
        '10' => 'Octombrie',
        '11' => 'Noiembrie',
        '12' => 'Decembrie',
        '' => 'Toate',
    ];

    protected $statusColors = [
        'dosar decontare depus' => [
            'name' => 'Total',
            'color' => self::TEXT_COLOR['black'],
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-black',
            'total' => 0,
            'ids' => '',
        ],
    ];

    public function updateMonth()
    {
        $validatedData = $this->validate([
            'luna' => ['nullable','integer','min:1','max:12']
        ]);
        $this->luna = $validatedData['luna'] ?? null;
        $this->main_settings['auto_load'] = 'no';
        $this->build($this->items());
        $this->dispatch('updatedMonth', ...$this->variables());
    }

    public function updateYear()
    {
        $validatedData = $this->validate([
            'an' => ['required','integer','min:2022','max:'.date('Y')]
        ]);
        $this->an = $validatedData['an'] ?? 2023;
        $this->main_settings['auto_load'] = 'no';
        $this->build($this->items());
        $this->dispatch('updatedYear', ...$this->variables());
    }

    protected function createDate($end = false)
    {
        if($this->luna) {
            return Carbon::create()->day($end ? $this->inceput_luna : $this->sfarsit_luna)
                ->month($end ? ($this->luna == '12' ? '1' : $this->luna + 1) : $this->luna)
                ->year($this->an + ($end && ($this->luna == '12') ? 1 : 0));
        } else {
            return $end
                ? Carbon::create()->year($this->an)->endOfYear()->addDays($this->sfarsit_luna)
                : Carbon::create()->year($this->an)->startOfYear()->addDays($this->inceput_luna);
        }
    }

    // protected function items()
    // {
    //     $values = $this->query();
    //     return AfmForm::setSection($this->section)->withInfo()->visible()->select(\DB::raw('COUNT(*) as result'), 'judet_imobil')
    //         ->where('situatie_dosar', 'dosar decontare depus')
    //         ->where(function($query) {
    //             $query->where('data_dosar_decontare', '<>', '')->whereNotNull('data_dosar_decontare');
    //         })->where('data_dosar_decontare', '>=', $this->createDate()->format('Y-m-d'))
    //         ->where('data_dosar_decontare', '<=', $this->createDate(true)->format('Y-m-d'))
    //         ->groupBy('judet_imobil')->get()
    //     ->groupBy([
    //         function ($item, $key) {
    //             return $item['judet_imobil'];
    //         }
    //     ])->mapWithKeys(function ($item, $key) {
    //         return [$key => ['total' => $item[0]['result']]];
    //     });
    // }

    protected function itemsConditions($query)
    {
        return $query->where('situatie_dosar', 'dosar decontare depus')
            ->where(function($query) {
                $query->where('data_dosar_decontare', '<>', '')->whereNotNull('data_dosar_decontare');
            })->where('data_dosar_decontare', '>=', $this->createDate()->format('Y-m-d'))
            ->where('data_dosar_decontare', '<=', $this->createDate(true)->format('Y-m-d'));
    }

    protected function constColumns()
    {
        return array_merge(parent::constColumns(), ['situatie_dosar']);
    }

    protected function query($status = null, $value = null)
    {
        return [
            'situatie_dosar' => 'dosar decontare depus',
            'data_dosar_decontare' => [
                'start' => $this->createDate()->format('Y-m-d'),
                'end' => $this->createDate(true)->format('Y-m-d'),
            ],
        ];
    }

    protected function sumAllValues()
    {
        return false;
    }

    protected function title()
    {
        return  __('Raport dosare depuse lunar per judet');
    }

    protected function customId()
    {
        return 'dosare-depuse-map';
    }
}
