<?php

namespace App\Http\Livewire\Reports;

use App\Exports\Ofertare\EventsExport;
use App\Http\Livewire\Traits\ConvertEmptyStringsToNullTrait;
use App\Http\Livewire\Reports\AfmReport;
use App\Models\AfmForm;
use App\Models\User;
use App\Models\Ofertare\Eveniment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EventsAfmReport extends AfmReport
{
    use ConvertEmptyStringsToNullTrait;

    public $data_inceput = null;
    public $data_sfarsit = null;
    public $per_zile = null;
    protected $dates = [];

    public function search()
    {
        $this->dispatch('reloadInputs', ['wrapper' => '#'.$this->component_id.'-search']);
        $inputs = $this->validate([
            'data_inceput' => ['nullable','date'],
            'data_sfarsit' => array_merge(['nullable','date'], !empty($this->data_inceput) ? ['after_or_equal:data_inceput'] : []),
            'per_zile' => ['nullable','boolean'],
        ],[],[
            'data_inceput' => __('data inceput'),
            'data_sfarsit' => __('data sfarsit'),
        ]);
        $this->build($this->items());
    }

    public function export()
    {
        return (new EventsExport($this->section, [
            'data_inceput' => $this->data_inceput,
            'data_sfarsit' => $this->data_sfarsit,
            'per_zile' => $this->per_zile,
        ]))->download(
            config('app.name').'_raport_'.$this->section.'_per_evenimente_'.date('Y-m-d H:i:s').'.xlsx'
        );
    }

    protected function paginate()
    {
        return !empty($this->per_zile) ? 20 : false;
    }

    protected function itemsModel()
    {
        return Eveniment::where('an', $this->section);
    }

    protected function itemsConditions($query)
    {
        if(!empty($this->data_inceput)) {
            $query->whereDate('created_at', '>=', $this->data_inceput);
        }
        if(!empty($this->data_sfarsit)) {
            $query->whereDate('created_at', '<=', $this->data_sfarsit);
        }
        return $query->orderBy('created_at');
    }

    protected function users()
    {
        return User::whereIn('id', array_keys($this->paginate() ? $this->items->items() : $this->items->toArray()))->get()->mapWithKeys(function($item, $key) {return [$item->id => $item];});
    }
    protected function pipeDownCallback($item, $key, $column, $value)
    {
        if(!empty($this->per_zile)) {
            $this->dates[strtotime($key)] = true;
        }
    }

    protected function constColumns()
    {
        return ['user_id','eveniment'];
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), (!empty($this->per_zile) ? [\DB::raw('DATE(created_at) as zi_eveniment')] : []));
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), (!empty($this->per_zile) ? ['zi_eveniment'] : []));
    }

    protected function query($status = null, $value = null)
    {
        return [];
    }

    protected function variables()
    {
        return parent::variables() + [
            'dates' => array_keys($this->dates),
            'users' => $this->users(),
            'evenimente' => Eveniment::OPTIUNI,
            'period' => !empty($this->per_zile)
                ? CarbonPeriod::create(
                    Carbon::parse(collect(array_keys($this->dates))->min())->setTimezone(config('app.timezone'))->format('Y-m-d'),
                    Carbon::parse(collect(array_keys($this->dates))->max())->setTimezone(config('app.timezone'))->format('Y-m-d')
                ) : null
        ];
    }

    protected function bladeView()
    {
        return 'livewire.reports.events';
    }
}
