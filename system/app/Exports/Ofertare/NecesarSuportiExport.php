<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class NecesarSuportiExport implements FromView, ShouldAutoSize
{
    protected $section = 2021;

    public function __construct($section = 2021)
    {
        $this->section = $section;
    }

    public function view(): View
    {
        $siruri = AfmForm::setSection($this->section)->withInfo()->select('id','id_formular','siruri_panouri','stare_montaj','tipul_invelitorii')
            ->whereNotNull('siruri_panouri')
            ->whereNotNull('tipul_invelitorii')
            // ->where('stare_montaj', 'sistem nemontat')
            ->whereIn('stare_montaj', ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'])
            ->get()->groupBy([
                function ($item, $key) {
                    $panouri = $item['siruri_panouri'];
                    return $panouri;
                },
                function ($item, $key) {
                    return $item->baza_invelitoare;
                    // return $item['tipul_invelitorii'] == 'tigla' ? 'tigla' : 'non_tigla';
                }
            ])->sortKeys();

        return view('exports.ofertare.necesar-suporti', [
            'siruri' => $siruri,
        ]);
    }
}
