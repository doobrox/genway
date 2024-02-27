<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NecesarMaterialeExport implements FromView, ShouldAutoSize
{
    protected $formulare = null;
    protected $section = 2021;

    public function __construct($formular, $section = 2021)
    {
        $this->formular = $formular;
        $this->section = $section;
    }

    public function view(): View
    {
        $formular = AfmForm::setSection($this->section)->withInfo()->findOrFail($this->formular);
        $count_tigla = 0;
        $count_tabla = 0;
        $count_sandwich = 0;
        $count_altele = 0;
        $cant = 1;

        if($formular->baza_invelitoare) {
            ${'count_'.$formular->baza_invelitoare}++;
        }
        // $formular->tipul_invelitorii != 'tigla'
        //     ? $count_tabla++
        //     : $count_tigla++;

        $siruri = $formular->siruri_panouri
            ? json_decode(str_replace('1x', '', $formular->getAttributes()['siruri_panouri']), true)
            : [$formular->numar_panouri ?? 0];

        $total = [
            'sina_24' => 0,
            'sina_36' => 0,
            'RS' => 0,
            'MRH' => 0,
            'TRH' => 0,
            'MRH_SW' => 0,
            'EC' => 0,
            'MC' => 0
        ];

        if($formular->numar_panouri) {
            foreach ($siruri as $sir) {
                $total['sina_24'] += $cant * ($sir % 3);
                $total['sina_36'] += $cant * (intval($sir / 3) * 2);
                $total['RS'] += $cant * (intval(($sir - 1) / 3) * 2);
                $total['MRH'] += $count_tabla * ($sir * 2 + 4);
                $total['TRH'] += $count_tigla * ($sir * 2 + 4);
                $total['MRH_SW'] += $count_sandwich * (4 /* EC value */ + (($sir - 1) * 2) /* MC value */);
                $total['EC'] += $cant * 4;
                $total['MC'] += $cant * (($sir - 1) * 2);
            }
        }

        return view('exports.ofertare.necesar-materiale', [
            'formular' => $formular,
            'invertor' => $formular->invertor,
            'total' => $total,
            'logo' => resource_path('assets/ofertare/genway_logo_small.png')
        ]);
    }
}
