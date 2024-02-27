<?php

namespace App\Http\Livewire\Tables;

use App\Exports\Ofertare\AfmTableExport;
use App\Exports\Ofertare\NecesarEchipaExport;
use App\Exports\Ofertare\NecesarInvertoareExport;
use App\Exports\Ofertare\NecesarPanouriExport;
use App\Exports\Ofertare\NecesarSuportiExport;
use App\Exports\Ofertare\NecesarMaterialeExport;
use App\Http\Livewire\Traits\ConvertEmptyStringsToNullTrait;
use App\Models\AfmForm;
use App\Models\QueuedExport;
use App\Models\Ofertare\ColoanaTabelAFM;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class AfmTable extends Component
{
    use WithPagination;

    public $section = '2021';
    public $conditions = [];
    public $formular = [];
    public $order_by_column;
    public $order_by_sort;

    public $all_columns_checked = false;
    public $current_columns = [];

    protected $listeners = ['update-select2' => 'updateSelect2'];

    public function paginationView()
    {
        return 'livewire.pagination.bootstrap-4';
    }

    public function mount($section = '2021', $conditions = [])
    {
        $this->section = $section;
        $this->current_columns = $this->implicit_columns ?: $this->all_columns->pluck('nume')->toArray();
        $this->all_columns_checked = $this->verifyAllColumnsSelected();
        $this->conditions = collect($conditions ?: request()->input())->only(
            array_merge(ColoanaTabelAFM::getSectionColumns($this->section)->pluck('nume')->toArray(), ['order_by', 'formular', 'or'])
            // $this->all_columns->pluck('nume')->toArray() + ['order_by', 'formular']
        )->filter(function ($value, $key) {
            return !empty($value) && $value !== 'undefined';
        })->map(function ($item, $key) {
            if(is_array($item)) {
                if(empty($item['empty']) || $item['empty'] == 'false') {
                    unset($item['empty']);
                }
                if(empty($item['not_empty']) || $item['not_empty'] == 'false') {
                    unset($item['not_empty']);
                }
            }
            return $item ?? null;
        })->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public function updated($property, $value)
    {
        if(\Str::startsWith($property, ['current_columns'])) {
            $this->verifyAllColumnsSelected();
            unset($this->columns);
        }
        if(\Str::startsWith($property, ['all_columns_checked'])) {
            $this->current_columns = $value ? $this->all_columns->pluck('nume')->toArray() : [];
            unset($this->columns);
        }

        if(\Str::startsWith($property, ['conditions']) && ($value === '' || $value === false)) {
            data_forget($this, $property);
        }
    }

    public function updateSelect2($item)
    {
        current($item) 
            ? data_set($this, key($item), current($item))
            : data_forget($this, key($item));
        $this->skipRender();
    }

    public function hydrate()
    {
        // used for select2
        $this->dispatch('reinit');
    }

    public function orderBy($column, $order = 1)
    {
        $this->order_by_column = $column;
        $this->order_by_sort = $order;
        $this->conditions['order_by'] = [
            'column' => $column,
            'order' => $order === 1 ? 'asc' : 'desc',
        ];
        unset($this->query);
    }

    public function changeSablon($sablon)
    {
        if(auth()->user()->sabloaneAFM->where('id', $sablon)->where('implicit', 0)->count()) {
            auth()->user()->sabloaneAFM()->where('implicit', 1)->update(['implicit' => 0]);
            auth()->user()->sabloaneAFM()->where('id', $sablon)->update(['implicit' => 1]);
            auth()->user()->load('sabloaneAFM');
            auth()->user()->load('implicitSablonAFM');
            unset($this->implicit_columns);
            unset($this->columns);
            unset($this->all_columns);
            $this->current_columns = $this->implicit_columns ?: $this->all_columns->pluck('nume')->toArray();
            $this->verifyAllColumnsSelected();
            $this->render();
        }
    }

    protected function verifyAllColumnsSelected()
    {
        $this->all_columns_checked = count($this->current_columns) == $this->all_columns->count();
    }

    public function updateQueryConditions()
    {
        $this->conditions = array_filter($this->conditions, function($value) {
            return ($value !== null && $value !== false && $value !== ''); 
        });
        // prevent livewire bug on checkbox input
        foreach($this->formular as $key => $value) {
            if($value === '__rm__') {
                unset($this->formular[$key]);
            }
        }
        // if(!empty($this->formular)) {
        //     $this->conditions['formular'] = $this->formular;
        // } else {
        //     unset($this->conditions['formular']);
        // }
    }

    /*
    |--------------------------------------------------------------------------
    | Computed properties
    |--------------------------------------------------------------------------
    */

    // preserve eloquent constains using computed property
    #[Computed(persist: true)]
    public function columns()
    {
        // return auth()->user()->coloaneSablonImplicit($this->section)->get();
        return $this->sortColumns($this->all_columns->whereIn('nume', $this->current_columns), $this->current_columns, 'nume');
    }

    #[Computed(persist: true)]
    public function implicit_columns()
    {
        return auth()->user()->implicitSablonAFM 
            ? auth()->user()->implicitSablonAFM->coloane
            : [];
    }

    #[Computed(persist: true)]
    public function all_columns()
    {
        return $this->sortColumns(ColoanaTabelAFM::getAllColumns($this->section), $this->implicit_columns, 'nume');
    }

    #[Computed]
    public function query()
    {
        $this->updateQueryConditions();
        // return session()->get('query'.$this->getId(), function () {
        //     return AfmForm::getQuery($this->columns->pluck('nume')->toArray(), $this->conditions, $this->section)->paginate(15);
        // });
        return AfmForm::getQuery($this->columns->pluck('nume')->toArray(), $this->conditions, $this->section)->paginate(15);        
    }

    /*
    |--------------------------------------------------------------------------
    | Exports
    |--------------------------------------------------------------------------
    */

    public function exportNecesarSuporti()
    {
        $this->skipRender();
        return \Excel::download(new NecesarSuportiExport($this->section), config('app.name').'_necesar_suporti.xlsx');
    }

    public function exportNecesarInvertoare()
    {
        $this->skipRender();
        return \Excel::download(new NecesarInvertoareExport($this->section), config('app.name').'_necesar_invertoare.xlsx');
    }

    public function exportNecesarPanouri()
    {
        $this->skipRender();
        return \Excel::download(new NecesarPanouriExport($this->section), config('app.name').'_necesar_panouri.xlsx');
    }

    public function exportNecesarMateriale($formular)
    {
        $this->skipRender();
        return \Excel::download(new NecesarMaterialeExport($formular, $this->section), config('app.name').'_necesar_materiale_formular_'.$formular.'.xlsx');
    }

    public function exportNecesarEchipa()
    {
        $this->skipRender();
        $this->updateQueryConditions();
        return \Excel::download(new NecesarEchipaExport($this->formular, $this->section), config('app.name').'_necesar_echipa.xlsx');
    }

    public function exportTabelSiConditii()
    {
        $this->updateQueryConditions();
        if(
            !QueuedExport::where('sectiune', 'ofertare_afm_'.$this->section)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['0','1'])->exists()
        ) {
            $name = 'Ofertare_formulare_afm_'.$this->section.'__'.date('Y-m-d_H:i:s').'.xlsx';
            $folder = 'exports/ofertare/afm/';
            $export = QueuedExport::create([
                'user_id' => auth()->id(),
                'nume' => $name,
                'folder' => $folder,
                'status' => '1',
                'sectiune' => 'ofertare_afm_'.$this->section,
            ]);
            (new AfmTableExport($this->columns->pluck('nume')->toArray(), $this->conditions + ($this->formular ? [
                'formular' => $this->formular
            ] : []), $this->section))->queue($folder.$name)->chain([
                new \App\Jobs\UpdateExportStatusJob($export),
            ]);// ->download($name);
            session()->now('status', 'Exportul a inceput');
        } else {
            session()->now('warning', 'Un export de acest tip este deja in curs de creare');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Custom functions
    |--------------------------------------------------------------------------
    */

    public function getExportOptions()
    {
        return [
            [
                'func' => 'exportNecesarSuporti',
                'title' => __('Export necesar suporti'),
                'color' => 'green-jungle',
                'icon' => 'fa fa-file-excel-o',
                'condition' => true,
            ], [
                'func' => 'exportNecesarInvertoare',
                'title' => __('Export necesar invertoare'),
                'color' => 'green-jungle',
                'icon' => 'fa fa-file-excel-o',
                'condition' => true,
            ], [
                'func' => 'exportNecesarPanouri',
                'title' => __('Export necesar panouri'),
                'color' => 'green-jungle',
                'icon' => 'fa fa-file-excel-o',
                'condition' => true,
            ], [
                'func' => 'exportNecesarEchipa',
                'title' => __('Export necesar echipa'),
                'color' => 'green-jungle',
                'icon' => 'fa fa-file-excel-o',
                'condition' => true,
            ], [
                'func' => 'exportTabelSiConditii',
                'title' => __('Export tabel si conditii'),
                'color' => 'green-jungle',
                'icon' => 'fa fa-file-excel-o',
                'condition' => auth()->user()->can('exporturi_afm.view'),
            ],
        ];
    }

    public function getOptions($item)
    {
        return [
            [
                'route' => route('ofertare.afm.generate.document', [$this->section, $item->id, 'contract-instalare', $this->section]),
                'title' => __('Contract instalare'),
                'color' => 'red',
                'icon' => 'fa fa-file-pdf-o',
                'condition' => auth()->user()->can('afm.2021.generare_contract_instalare'),
            ],
            [
                // 'route' => env('OLD_SITE_NAME').'ofertare/afm/generare_act_aditional_cota_tva/'.$item->id.'/'.$this->section,
                'route' => route('ofertare.afm.generate.document', [$this->section, $item->id, 'act-aditional-cota-tva', $this->section]),
                'title' => __('Act aditional la contract instalare'),
                'color' => 'blue-chambray',
                'icon' => 'fa fa-level-up',
                'condition' => auth()->user()->can('afm.2021.generare_act_aditional_cota_tva'),
            ],
            [
                // 'route' => env('OLD_SITE_NAME').'ofertare/afm/generare_act_aditional_upgrade/'.$item->id.'/'.$this->section,
                'route' => route('ofertare.afm.generate.document', [$this->section, $item->id, 'act-aditional-upgrade', $this->section]),
                'title' => __('Act aditional Upgrade'),
                'color' => 'blue-chambray',
                'icon' => 'fa fa-level-up',
                'condition' => auth()->user()->can('afm.2021.generare_act_aditional_upgrade'),
            ],
            [
                // 'route' => env('OLD_SITE_NAME').'ofertare/afm/generare_act_aditional_cupon/'.$item->id.'/'.$this->section,
                'route' => route('ofertare.afm.generate.document', [$this->section, $item->id, 'act-aditional-cupon', $this->section]),
                'title' => __('Act aditional cupon'),
                'color' => 'blue-chambray',
                'icon' => 'fa fa-level-up',
                'condition' => auth()->user()->can('afm.2021.generare_act_aditional_cupon') 
                    && $item->verificare_reducere == '1' && $item->aport_propriu == '2000',
            ],
            [
                'route' => route('ofertare.afm.generate.anexa.factura', [$this->section, $item->id]),
                'title' => __('Anexa factura'),
                'color' => 'yellow',
                'icon' => 'fa fa-file-pdf-o',
                'condition' => auth()->user()->can('afm.2021.generare_anexa_factura'),
            ],
            [
                // 'route' => route('ofertare.afm.generate.pv.predare.primire', [$this->section, $item->id]),
                'route' => route('ofertare.afm.generate.document', [$this->section, $item->id, 'pv-predare-primire', $this->section]),
                'title' => __('PV predare primire'),
                'color' => 'green',
                'icon' => 'fa fa-file-pdf-o',
                'condition' => auth()->user()->can('afm.2021.generare_pv_predare_primire'),
            ],
            [
                // 'route' => env('OLD_SITE_NAME').'ofertare/afm/generare_pv_receptie/'.$item->id.'/'.$this->section,
                'route' => route('ofertare.afm.generate.document', [$this->section, $item->id, 'pv-receptie', $this->section]),
                'title' => __('PV receptie'),
                'color' => 'blue-oleo',
                'icon' => 'fa fa-file-pdf-o',
                'condition' => auth()->user()->can('afm.2021.generare_pv_receptie'),
            ],
            [
                'route' => route('ofertare.afm.generate.fisa.vizita', [$this->section, $item->id]),
                'title' => __('Fisa vizita'),
                'color' => 'purple',
                'icon' => 'fa fa-file-pdf-o',
                'condition' => auth()->user()->can('afm.2021.generare_fisa_vizita'),
            ],
            [
                // 'route' => route('ofertare.necesar.materiale', [$this->section, $item->id]),
                'func' => 'exportNecesarMateriale('.$item->id.')',
                'title' => __('Export necesar materiale'),
                'color' => 'green-jungle',
                'icon' => 'fa fa-file-excel-o',
                'condition' => auth()->user()->can('afm.2021.export_necesar_materiale'),
            ],
            [
                // 'route' => env('OLD_SITE_NAME').'ofertare/afm/send_contract_instalare/'.$item->id.'/'.$this->section
                //     .'?r='.route('ofertare.afm.browse', ['section' => $this->section] + $this->conditions),
                'route' => route('ofertare.afm.mail.contract.instalare', [$this->section, $item->id]),
                'title' => __('Trimite contract instalare'),
                'color' => 'blue-dark',
                'target' => '_self',
                'icon' => 'fa fa-envelope-o',
                'condition' => auth()->user()->can('afm.2021.mail_contract_instalare'),
            ],
            [
                'route' => route('ofertare.afm.mail.data.estimata.montaj', [$this->section, $item->id]),
                'title' => __('Trimite email data montaj'),
                'color' => 'blue-dark',
                'target' => '_self',
                'icon' => 'fa fa-envelope-o',
                'condition' => auth()->user()->can('afm.2021.mail_data_montaj'),
            ],
        ];
    }

    public function getExportDescriptions()
    {
        return [
            [
                'title' => __('Conditii export necesar suporti'),
                'description' => $this->createDescriptionFromColumns([
                    'siruri_panouri' => ['not_empty' => 1],
                    'tipul_invelitorii' => ['not_empty' => 1],
                    'stare_montaj' => ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'],
                ]),
            ], [
                'title' => __('Conditii export necesar invertoare'),
                'description' => $this->createDescriptionFromColumns([
                    'putere_invertor' => ['not_empty' => 1],
                    'marca_invertor' => ['not_empty' => 1],
                    // 'status' => 'dosare aprobate',
                    'stare_montaj' => ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'],
                ]),
            ], [
                'title' => __('Conditii export necesar panouri'),
                'description' => $this->createDescriptionFromColumns([
                    'putere_panouri' => ['not_empty' => 1],
                    'numar_panouri' => ['not_empty' => 1],
                    'siruri_panouri' => ['not_empty' => 1],
                    'tipul_invelitorii' => ['not_empty' => 1],
                    // 'status' => 'dosare aprobate',
                    'stare_montaj' => ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'],
                ]),
            ], [
                'title' => __('Conditii export necesar echipa'),
                'description' => $this->createDescriptionFromColumns([
                    'tipul_invelitorii' => ['not_empty' => 1],
                    'marca_invertor' => ['not_empty' => 1],
                ], [
                    'unul sau mai multe <b>formulare</b> trebuie sa fie selectate'
                ]),
            ],
        ];
    }

    protected function queryString()
    {
        $filter = [
            'conditions.order_by.column' => ['as' => urlencode('order_by[column]')],
            'conditions.order_by.order' => ['as' => urlencode('order_by[order]')],
            'conditions.formular' => ['as' => 'formular'],
            'conditions.or' => ['as' => 'or'],
        ];
        foreach(ColoanaTabelAFM::getSectionColumns($this->section)->pluck('nume')->toArray() as $column) {
            $filter['conditions.'.$column] = ['as' => $column, 'keep' => false, 'alwaysShow' => false, 'except' => ''];
        }
        return $filter;
    }

    protected function sortColumns($collection, $order, $key = null)
    {
        return $collection->sortBy(function($item) use($order, $key) {
            $key = array_search($key ? $item->{$key} : $item, $order);
            return $key === false ? 999 : $key;
        });
    }

    protected function bladeView()
    {
        return 'livewire.tables.afm';
    }

    protected function variables()
    {
        return [
            'section' => $this->section,
            'items' => $this->query,
            'coloane' => $this->columns,
            'coloane_permise' => $this->all_columns->pluck('titlu', 'nume'),
            'types' => ColoanaTabelAFM::$types
        ];
    }

    public function render()
    {
        return view($this->bladeView(), $this->variables());
    }

    protected function createDescriptionFromColumns(array $columns = [], array $added_lines = [])
    {
        $description = [];
        $conditional_columns = [];
        foreach ($columns as $column => $value) {
            if(is_array($value)) {
                $empty = in_array('', $value) || isset($value['empty']) ? ' '.__('sau sa').' ' : '';
                $not_empty = isset($value['not_empty']) ? ' '.__('si sa').' ' : '';
                unset($value['empty'], $value['not_empty']);
                foreach (array_keys($value, '') as $key => $v) {
                    unset($value[$key]);
                }

                if(isset($value['start']) || isset($value['end'])) {
                    $values = '';
                    $values .= isset($value['start']) ? __('inceapa in data de :start_date', ['start_date' => $value['start']]) : '';
                    $values .= isset($value['end']) ? ($values ? '' :__(' fie')).__(' pana in data de :end_date', ['end_date' => $value['end']]) : '';
                } else {
                    $values = trans_choice('aiba valoarea :value|sa aiba una din valorile :value', count($value), [
                        'value' => '"'.implode('", "', $value).'"'
                    ]);
                }

                $description['columns'][$column] = __('<b>:coloana</b>: trebuie sa :empty:not_empty:values', [
                    'coloana' => str_replace('_', ' ', $column),
                    'empty' => $empty ? __('fie necompletata') : '',
                    'not_empty' => $not_empty ? $empty.__('fie completata') : '',
                    'values' => count($value) ? ($not_empty ?: $empty).$values : '',
                ]);
            } elseif($value !== null) {
                $description['columns'][$column] = __('<b>:coloana</b>: trebuie sa aiba valoarea ":value"', [
                    'coloana' => str_replace('_', ' ', $column),
                    'value' => $value,
                ]);
            } else {
                $conditional_columns[$column] = true;
            }
        }

        foreach ($added_lines as $key => $value) {
            $description['columns'][$key] = $value;
        }

        if($description) {
            $description['list_start'] = $this->descriptionStart();
        }
        return $description;
    }

    protected function descriptionStart()
    {
        return __('Conditiile coloanelor folosite in export:');
    }
}