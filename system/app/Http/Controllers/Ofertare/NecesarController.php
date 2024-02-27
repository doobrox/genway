<?php

namespace App\Http\Controllers\Ofertare;

use App\Models\AfmForm;
use App\Models\Ofertare\Invertor;
use App\Exports\Ofertare\NecesarInvertoareExport;
use App\Exports\Ofertare\NecesarPanouriExport;
use App\Exports\Ofertare\NecesarSuportiExport;
use App\Exports\Ofertare\NecesarEchipaExport;
use App\Exports\Ofertare\NecesarMaterialeExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NecesarController extends Controller
{
    public function index()
    {
        return view('ofertare.necesar.index', [
            'sectiune' => __('Export necesar')
        ]);
    }

    public function suporti($section = 2021)
    {
        return Excel::download(new NecesarSuportiExport($section), config('app.name').'_necesar_suporti.xlsx');
    }

    public function invertoare($section = 2021)
    {
        return Excel::download(new NecesarInvertoareExport($section), config('app.name').'_necesar_invertoare.xlsx');
    }

    public function panouri($section = 2021)
    {
        return Excel::download(new NecesarPanouriExport($section), config('app.name').'_necesar_panouri.xlsx');
    }

    public function echipa(Request $request, $section = 2021)
    {
        return Excel::download(new NecesarEchipaExport($request->input('formular'), $section), config('app.name').'_necesar_echipa.xlsx');
    }

    public function materiale($section = 2021, $formular)
    {
        return Excel::download(new NecesarMaterialeExport($formular, $section), config('app.name').'_necesar_materiale_formular_'.$formular.'.xlsx');
    }
}
