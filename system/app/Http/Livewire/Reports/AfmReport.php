<?php

namespace App\Http\Livewire\Reports;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Models\Ofertare\Eveniment;
use Livewire\Component;
use Livewire\WithPagination;

class AfmReport extends Component
{
    use WithPagination;

    public $component_id = 'report';
    public $title = null;
    public $section = 2021;
    public $per_regiuni = false;

    protected $base_link = 'https://www.genway.ro/ofertare/afm_2/';
    protected $items = [];

    public function paginationView()
    {
        return 'livewire.pagination.bootstrap-4';
    }

    protected function paginate()
    {
        return false;
    }

    public function mount($items = null, $id = 'report', $section = 2021, $title = null, $regiuni = false)
    {
        $this->component_id = $id ?? $this->customId() ?? $this->component_id;
        $this->title = $title ?? $this->title();
        $this->section = $section;
        $this->title = $title;
        $this->per_regiuni = $regiuni;

        $this->items = $items ?? [];
        $this->build($this->items());
    }

    protected function title()
    {
        return null;
    }

    protected function customId()
    {
        return null;
    }

    protected function build($items = []) {}

    protected function items()
    {
        if($this->itemsModel()) {
            $query = $this->itemsConditions(
                $this->itemsModel()->addSelect(
                    \DB::raw('COUNT(*) as result'), ...($this->selectColumns())
                )
            )->groupBy(...($this->groupByColumns()));
            $items = $this->paginate() ? $query->paginate($this->paginate()) : $query->get();
            $grouped_items = $items->groupBy($this->groupByFunctions())->mapWithKeys(function ($item, $key) {
                return $this->pipeDown($item, $key);
            });
            $this->items = $this->paginate() ? $items->setCollection($grouped_items) : $grouped_items;
        }
        return $this->items;
    }

    protected function itemsModel()
    {
        $query = AfmForm::setSection($this->section)->withInfo()->visible();
        return $this->per_regiuni ? $query->withOnlyRegiune() : $query;
    }

    protected function itemsConditions($query)
    {
        return $query;
    }

    protected function constColumns()
    {
        return $this->per_regiuni ? [] : ['judet_imobil'];
    }

    protected function selectColumns()
    {
        return $this->constColumns();
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), ($this->per_regiuni ? ['regiune'] : []));
    }

    protected function pipeDown($item, $key)
    {
        $result = [];
        foreach($item ?? [] as $column => $value) {
            if($key !== 0 && isset($item[0]['result'])) {
                $this->pipeDownCallback($item, $key, $column, $value);
                $result = $item[0]['result'];
            } else {
                $result += $this->pipeDown($value, $column);
            }
        }
        return [$key => $result ?? null];
    }

    protected function pipeDownCallback($item, $key, $column, $value) {}

    protected function groupByFunctions()
    {
        $functions = [];
        foreach($this->groupByColumns() as $column) {
            $functions[] = function ($item, $key) use($column) {
                return $item[$column];
            };
        }
        return $functions;
    }

    protected function query($status = null, $value = null)
    {
        $conditions = [];

        if($this->per_regiuni) {
            $conditions['regiune'] = $value;
        } else {
            $conditions = $conditions + $this->queryNotRegiune($status, $value);
        }
        return $conditions;
    }

    protected function queryNotRegiune($status = null, $value = null)
    {
        return ['judet_imobil' => ['empty' => true]]; // null or empty value
    }

    public function link($status = null, $value = null)
    {
        return $this->base_link.$this->section.'?'.http_build_query($this->query($status, $value));
    }

    protected function limits()
    {
        return [];
    }

    protected function headers()
    {
        return $this->per_regiuni ? Judet::getNumeRegiuni() : [];
    }

    public function statusHeader()
    {
        return __('Status');
    }

    public function generalHeader()
    {
        return __('Numar dosare (doar cele fara judet)');
    }

    protected function bladeView()
    {
        return 'livewire.reports.report';
    }

    protected function variables()
    {
        return [
            'items' => $this->items(),
            'limits' => $this->limits(),
            'headers' => $this->headers(),
        ];
    }

    public function render()
    {
        return view($this->bladeView(), $this->variables());
    }
}
