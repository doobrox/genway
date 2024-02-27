<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class NecesarPanouriExport implements FromView, ShouldAutoSize
{
    protected $section = 2021;

    public function __construct($section = 2021)
    {
        $this->section = $section;
    }

    public function view(): View
    {
        $panouri = AfmForm::setSection($this->section)->withInfo()->select(DB::raw('SUM(numar_panouri) as total'),'putere_panouri','numar_panouri')
            ->whereNotNull("putere_panouri")
            ->where('putere_panouri', '<>', '""')
            ->whereNotNull("numar_panouri")
            ->where('numar_panouri', '<>', '""')
            ->whereNotNull('siruri_panouri')
            ->whereNotNull('tipul_invelitorii')
            // ->where('stare_montaj', 'sistem nemontat')
            ->whereIn('stare_montaj', ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'])
            // ->where('status', '6')
            ->groupBy('putere_panouri','numar_panouri')
            ->get()->groupBy([
                function ($item, $key) {
                    return $item['putere_panouri'];
                }
            ])->mapWithKeys(function($item, $key) {
                return [$key => $item->sum('total')];
            })->sortKeys()->toArray();

        // Old
        // $panouri = AfmForm::select(DB::raw('SUM(numar_panouri) as total'),'putere_panouri','numar_panouri')
        //     ->from(AfmForm::getInfoTableName())
        //     ->where('stare_montaj', 'sistem nemontat')
        //     ->groupBy('putere_panouri','numar_panouri')
        //     ->get()->groupBy([
        //         function ($item, $key) {
        //             return $item['putere_panouri'];
        //         }
        //     ])->sortKeys()->toArray();

        return view('exports.ofertare.necesar-panouri', [
            'panouri' => $panouri,
        ]);
    }
}
