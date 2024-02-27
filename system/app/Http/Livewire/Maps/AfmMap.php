<?php

namespace App\Http\Livewire\Maps;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Ofertare\ColoanaTabelAFM;
use Livewire\Component;
use Livewire\WithPagination;

class AfmMap extends Component
{
    public $component_id = 'map';
    public $title = null;
    public $section = 2021;
    public $per_regiuni = false;

    protected const TEXT_COLOR = [
        'black' => '#000',
        'green' => '#00af50',
        'pink' => '#ffc0cb',
        'red' => '#fc0003',
        'orange' => '#ff6f00', // #f8d609 / ffff01
        'yellow' => '#f8d609',
        'brown' => '#c55911',
    ];

    public $countrymapIds = [
        1 => ["id" => "ROU294", "x" => 347.9, "y" => 330.4],
        2 => ["id" => "ROU302", "x" => 489.2, "y" => 459],
        3 => ["id" => "ROU276", "x" => 163.9, "y" => 307.4],
        4 => ["id" => "ROU128", "x" => 617.2, "y" => 589.8],
        5 => ["id" => "ROU312", "x" => 700.5, "y" => 293.5],
        6 => ["id" => "ROU277", "x" => 203.8, "y" => 212.6],
        7 => ["id" => "ROU295", "x" => 450.7, "y" => 164.2],
        8 => ["id" => "ROU313", "x" => 785.7, "y" => 490.5],
        9 => ["id" => "ROU287", "x" => 678.8, "y" => 52.1],
        10 => ["id" => "ROU305", "x" => 521.7, "y" => 380.7],
        11 => ["id" => "ROU314", "x" => 686.7, "y" => 461.8],
        12 => ["id" => "ROU296", "x" => 366.5, "y" => 237.4],
        13 => ["id" => "ROU129", "x" => 713, "y" => 605.4],
        14 => ["id" => "ROU278", "x" => 190.2, "y" => 482.8],
        15 => ["id" => "ROU133", "x" => 847.6, "y" => 639.1],
        16 => ["id" => "ROU306", "x" => 617, "y" => 369.3],
        17 => ["id" => "ROU130", "x" => 558.1, "y" => 539],
        18 => ["id" => "ROU122", "x" => 360.2, "y" => 625],
        19 => ["id" => "ROU123", "x" => 329, "y" => 507.1],
        20 => ["id" => "ROU315", "x" => 795.8, "y" => 387.6],
        21 => ["id" => "ROU131", "x" => 604.3, "y" => 633.6],
        22 => ["id" => "ROU297", "x" => 286.5, "y" => 396.6],
        23 => ["id" => "ROU307", "x" => 561.9, "y" => 275.1],
        24 => ["id" => "ROU4844", "x" => 627.5, "y" => 555.9],
        25 => ["id" => "ROU132", "x" => 787.8, "y" => 559.8],
        26 => ["id" => "ROU308", "x" => 744.3, "y" => 157.9],
        27 => ["id" => "ROU124", "x" => 283.1, "y" => 593.2],
        28 => ["id" => "ROU298", "x" => 392.2, "y" => 94.2],
        29 => ["id" => "ROU299", "x" => 460, "y" => 274.7],
        30 => ["id" => "ROU309", "x" => 636, "y" => 203.8],
        31 => ["id" => "ROU126", "x" => 446.5, "y" => 596.7],
        32 => ["id" => "ROU310", "x" => 622.2, "y" => 504.4],
        33 => ["id" => "ROU303", "x" => 427.3, "y" => 380.7],
        34 => ["id" => "ROU300", "x" => 301.4, "y" => 182.9],
        35 => ["id" => "ROU301", "x" => 266.6, "y" => 108.8],
        36 => ["id" => "ROU311", "x" => 563.5, "y" => 109.9],
        37 => ["id" => "ROU4847", "x" => 872.8, "y" => 504.2],
        38 => ["id" => "ROU280", "x" => 102.9, "y" => 402.9],
        39 => ["id" => "ROU127", "x" => 527.2, "y" => 651.5],
        40 => ["id" => "ROU304", "x" => 415.7, "y" => 489],
        41 => ["id" => "ROU317", "x" => 708, "y" => 384.1],
        42 => ["id" => "ROU316", "x" => 800.6, "y" => 269.7]
    ];

    protected $statusColors = [
        'total' => [
            'name' => 'Total',
            'color' => '#000',
            'type' => 'location',
            'shape' => 'square',
            'total' => 0,
            'ids' => '',
        ],
        'sistem montat complet' => [
            'name' => 'Sistem montat complet',
            'color' => '#00af50',
            'type' => 'location',
            'shape' => 'circle',
            'class' => 'pill-green',
            'total' => 0,
            'ids' => '',
        ],
        'sistem nemontat' => [
            'name' => 'Sistem nemontat',
            'color' => '#fc0003',
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-red',
            'total' => 0,
            'ids' => '',
        ],
        'sistem nemontat, pregatit pentru instalare' => [
            'name' => 'Sistem nemontat, pregatit pentru instalare',
            'color' => '#ff6f00',
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-orange',
            'total' => 0,
            'ids' => '',
        ],
        'sistem livrat' => [
            'name' => 'Sistem livrat',
            'color' => '#ffc0cb',
            'type' => 'location',
            'shape' => 'square',
            'class' => 'pill-pink',
            'total' => 0,
            'ids' => '',
        ],
        'alte componente lipsa' => [
            'name' => 'Alte componente lipsa',
            'color' => '#f8d609', //#ffff01
            'type' => 'location',
            'shape' => 'triangle',
            'class' => 'pill-yellow',
            'total' => 0,
            'ids' => '',
        ],
        'invertor lipsa' => [
            'name' => 'Invertor lipsa',
            'color' => '#f8d609', //#ffff01
            'type' => 'location',
            'shape' => 'triangle',
            'class' => 'pill-yellow',
            'total' => 0,
            'ids' => '',
        ],
        'contract anulat' => [
            'name' => 'Contract anulat',
            'color' => '#c55911',
            'type' => 'location',
            'shape' => 'diamond',
            'class' => 'pill-brown',
            'total' => 0,
            'ids' => '',
        ],
    ];

    public $main_settings = [
        //General settings
        'width' => "responsive", //'700' or 'responsive'
        'background_color' => "#FFFFFF",
        'background_transparent' => "yes",
        'border_color' => "#ffffff",

        //State defaults
        'state_description' => "Descriere judet",
        'state_color' => "#88A4BC",
        'state_hover_color' => "#3B729F",
        'state_url' => "",
        'border_size' => 1.5,
        'all_states_inactive' => "no",
        'all_states_zoomable' => "no",

        //Location defaults
        'location_description' => "Deschide link catre Tabel AFM",
        'location_url' => "",
        'location_color' => "#FF0067",
        'location_opacity' => 0,
        'location_hover_opacity' => 0,
        'location_size' => 12,
        'location_type' => "square",
        'location_image_source' => null,
        'location_border_color' => "transparent",
        'location_border' => 0,
        'location_hover_border' => 0,
        'all_locations_inactive' => "no",
        'all_locations_hidden' => "no",

        //Label defaults
        'label_color' => "#000",
        'label_hover_color' => "#000",
        'label_size' => "18",
        'label_font' => "Arial",
        'hide_labels' => "no",
        'hide_eastern_labels' => "no",

        //Zoom settings
        'zoom' => "yes",
        'manual_zoom' => "yes",
        'back_image' => "no",
        'initial_back' => "no",
        'initial_zoom' => "-1",
        'initial_zoom_solo' => "no",
        'region_opacity' => 1,
        'region_hover_opacity' => 0.6,
        'zoom_out_incrementally' => "yes",
        'zoom_percentage' => 0.99,
        'zoom_time' => 0.5,

        //Popup settings
        'popup_color' => "white",
        'popup_opacity' => 0.9,
        'popup_shadow' => 1,
        'popup_corners' => 5,
        'popup_font' => "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
        'popup_nocss' => "no",

        //Advanced settings
        'div' => 'map',
        'auto_load' => "yes",
        'url_new_tab' => "no",
        'images_directory' => "default",
        'fade_time' => 0.1,
        'link_text' => "Catre site",
        'popups' => "detect",
        'state_image_url' => "",
        'state_image_position' => "",
        'location_image_url' => "",
        'legend_position' => "outside"
    ];

    public $judete = [];
    public $state_specific = [];
    protected $labels = [];
    protected $locations = [];
    protected $items = [];
    protected $index_label = 0;

    public function mount($judete = null, $items = null, $id = null, $section = 2021, $title = null)
    {
        $this->component_id = $id ?? $this->customId() ?? $this->component_id;
        $this->title = $title ?? $this->title();
        $this->section = $section;
        $this->main_settings['div'] = $this->component_id;
        $this->judete = $judete ?? Judet::all();
        $this->items = $items ?? [];
        // $countStatus = count($this->statusColors);
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

    protected function build($items = [])
    {
        $this->index_label = $this->judete ? $this->judete->max('id') + 1 : 0;
        foreach($this->judete as $judet) {
            $countrymapId = $this->countrymapIds[$judet->id];
            $this->state_specific[$countrymapId['id']] = [
                'name' => $judet->nume,
                'description' => $this->createDescriptionList($items[$judet->id] ?? [], $countrymapId, $judet->id),
            ];
            $this->labels[$judet->id] = [
                'name' => $judet->nume,
                'parent_type' => 'state',
                'parent_id' => $countrymapId['id'],
                'x' => $countrymapId['x'],
                'y' => $countrymapId['y'],
            ];
        }

        foreach($this->statusColors as $key => $status) {
            if($this->statusColors[$key]['total'] > 0) {
                $this->statusColors[$key]['name'] .= ' ('.$this->statusColors[$key]['total'].')';
            }
        }
    }

    protected function createDescriptionList($array, $countrymapId, $judet = null)
    {
        $list = '<div class="px-1 py-1">';
        $counter = 0;
        $count = count($array);
        $prevCountValue = 0;

        if($this->includeAllKey() && count($array) > 0) {
            $array[$this->allKeyName()] = 0;
        }
        foreach ($array as $status => $value) {
            // update total value per county
            if($this->includeAllKey() && $status == $this->allKeyName()) {
                $value = $array[$this->allKeyName()];
            }
            $list .= '<span style="color:'.(isset($this->statusColors[$status])
                ? $this->statusColors[$status]['color']
                : 'black'
            ).'">'.($this->statusColors[$status]['name'] ?? '').': '.$value.'<br>';

            $countValue = strlen((string)$value);
            $coordX = isset($coordX) ? $coordX + (13 * ($prevCountValue + $countValue)/2) : ($countrymapId['x'] - (25 * ($count/2 - 0.5))) + (25 * $counter);

            $this->labels[$this->index_label + $counter] = [
                'name' => $value,
                'parent_type' => 'state',
                'parent_id' => $countrymapId['id'],
                'size' => '12',
                'pill' => 'yes',
                'pill_class' => $this->statusColors[$status]['class'] ?? '',
                'color' => '#fff',
                'hover_color' => '#fff',
                'x' => $coordX,
                'y' => $countrymapId['y'] + 20,
            ];
            $prevCountValue = $countValue;

            $this->locations[$this->index_label + $counter] = [
                'name' => $value,
                'url' => 'https://www.genway.ro/ofertare/afm_2/'.$this->section.'?judet_imobil='.$judet.'&'.http_build_query($this->query($status, $value)),
                'scale' => 'no',
                'x' => $coordX,
                'y' => $countrymapId['y'] + 20,
            ];

            // $this->statusColors[$status]['ids'] .= ($this->statusColors[$status]['ids'] != ''
            //     ?  ', ' : '' ).($this->index_label + $counter);

            $this->statusColors[$status]['total'] += $value;
            $this->statusColors[$status]['url'] = 'https://www.genway.ro/ofertare/afm_2/'.$this->section.'?'.http_build_query($this->query($status));
            if($status != $this->allKeyName()) {
                if($this->sumAllValues() && !$this->includeAllKey()) {
                    $this->statusColors[$this->allKeyName()]['total'] += $value;
                }
                if($this->includeAllKey()) {
                    $array[$this->allKeyName()] += $value;
                }
            }

            $counter++;
        }
        $this->index_label += count($this->statusColors);
        $list .= '</div>';

        return $list;
    }

    protected function items()
    {
        if($this->itemsModel()) {
            $query = $this->itemsConditions(
                $this->itemsModel()->addSelect(
                    \DB::raw('COUNT(*) as result'), ...($this->selectColumns())
                )
            )->groupBy(...($this->groupByColumns()));
            // if(auth()->id() == '413') {
            //     dd(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
            // }
            $this->items = $query->get()->groupBy($this->groupByFunctions())->mapWithKeys(function ($item, $key) {
                return $this->pipeDown($item, $key);
            });
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
        return [
            'stare_montaj' => $status
        ];
    }

    protected function allKeyName()
    {
        return 'total';
    }

    protected function sumAllValues()
    {
        return true;
    }

    protected function legendLink()
    {
        return true;
    }

    protected function includeAllKey()
    {
        return false;
    }

    protected function description()
    {
        $description = [];
        $columns = $this->query();
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
        // check get the possbile values for the conditional columns
        $status = $this->statusColors;
        // remove the "all" column to make sure we have other values for the conditional column
        unset($status[$this->allKeyName()]);
        if($status || $conditional_columns) {
            // flip resulted arrays to eliminate the non string values
            $flipped_columns = array_flip(array_keys($columns));
            $flipped_cond_columns = array_flip(array_keys($this->query(array_key_first($status))));
            // add the conditional columns in a single array
            $conditional_columns += array_diff($flipped_cond_columns, $flipped_columns);
            $key = implode(', ', array_keys($conditional_columns));
            if($conditional_columns) {
                $description['columns'] = [
                    $key => trans_choice('<b>:coloane</b>: coloana|:coloane: coloanele', count($conditional_columns), [
                        'coloane' => str_replace('_', ' ', implode(', ', array_keys($conditional_columns))),
                    ]).' '.__('care se schimba in functie de valorile descrise in legenda'),
                ] + ($description['columns'] ?? []);
            }
        }
        if($description) {
            $description['list_start'] = $this->descriptionStart();
        }
        return $description;
    }

    protected function descriptionStart()
    {
        return __('Conditiile coloanelor din raport:');
    }

    protected function bladeView()
    {
        return 'livewire.maps.map';
    }

    protected function variables()
    {
        return [
            'settings' => json_encode([
                'main_settings' => $this->main_settings,
                'state_specific' => $this->state_specific,
                'locations' => $this->locations,
                'labels' => $this->labels,
                'legend' => ['entries' => array_values($this->statusColors),],
                'regions' => [],
                'data' => ['data' => []]
            ]),
            'description' => $this->description(),
        ];
    }

    public function render()
    {
        return view($this->bladeView(), $this->variables());
    }
}
