<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Ofertare\Eveniment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventsExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $section = 2021;
    protected $search = [];
    protected $dates = [];

    public function __construct($section = 2021, $search)
    {
        $this->section = $section;
        $this->search = $search;
    }

    public function view(): View
    {
        $query = Eveniment::where('an', $this->section)->select(\DB::raw('COUNT(*) as result'), ...($this->selectColumns()));
        if(!empty($this->search['data_inceput'])) {
            $query->whereDate('created_at', '>=', $this->search['data_inceput']);
        }
        if(!empty($this->search['data_sfarsit'])) {
            $query->whereDate('created_at', '<=', $this->search['data_sfarsit']);
        }
        $query->groupBy(...($this->groupByColumns()))->orderBy('created_at');
        $items = $query->get();

        $this->dates = [];
        $items = $items->groupBy($this->groupByFunctions())->mapWithKeys(function ($item, $key) {
            return $this->pipeDown($item, $key);
        });

        return view('exports.ofertare.evenimente', [
            'dates' => array_keys($this->dates),
            'items' => $items,
            'users' => User::whereIn('id', array_keys($items->toArray()))->get()->mapWithKeys(function($item, $key) {
                return [$item->id => $item];
            }),
            'evenimente' => Eveniment::OPTIUNI,
            'period' => !empty($this->search['per_zile'])
                ? CarbonPeriod::create(
                    Carbon::parse(collect(array_keys($this->dates))->min())->setTimezone(config('app.timezone'))->format('Y-m-d'),
                    Carbon::parse(collect(array_keys($this->dates))->max())->setTimezone(config('app.timezone'))->format('Y-m-d')
                ) : null
        ]);
    }

    protected function pipeDown($item, $key)
    {
        $result = [];
        foreach($item ?? [] as $column => $value) {
            if($key !== 0 && isset($item[0]['result'])) {
                if(!empty($this->search['per_zile'])) { $this->dates[strtotime($key)] = true; }
                $result = $item[0]['result'];
            } else {
                $result += $this->pipeDown($value, $column);
            }
        }
        return [$key => $result ?? null];
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

    protected function constColumns()
    {
        return ['user_id','eveniment'];
    }

    protected function selectColumns()
    {
        return array_merge($this->constColumns(), (!empty($this->search['per_zile']) ? [\DB::raw('DATE(created_at) as zi_eveniment')] : []));
    }

    protected function groupByColumns()
    {
        return array_merge($this->constColumns(), (!empty($this->search['per_zile']) ? ['zi_eveniment'] : []));
    }
}
