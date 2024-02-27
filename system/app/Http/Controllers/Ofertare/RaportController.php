<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Exports\Ofertare\VerificationLimitExport;
use App\Models\AfmForm;
use App\Models\User;
use App\Models\Judet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class RaportController extends Controller
{
    public function index(Request $request, $section = 2021, $filter = 'stare-montaj')
    {
        $filters = [
            'stare-montaj',
            'situatie-dosar',
            'evenimente',
            'data-alegere-instalator',
            'status-aprobare-dosar',
            'contract-instalare',
            'inginer-vizita',
            'schita-amplasare-panouri',
            'verificare-schita-panouri',
            'programare-montaj',
            'urmarire-programari'
        ];
        if(AfmForm::validateSection($section) && in_array($filter, $filters)) {
            return view('ofertare.rapoarte.index', [
                'sectiune' => __('Rapoarte AFM'),
                'judete' => Judet::get(['id','nume']),
                'filter' => $filter,
                'section' => $section,
            ]);
        }
        abort(404);
    }

    public function export($section = 2021, $filter = 'data-alegere-instalator')
    {
        if(AfmForm::validateSection($section) && in_array($filter, ['data-alegere-instalator'])) {
            return (new VerificationLimitExport($section))->download(
                config('app.name').'_raport_'.$section.'_per_data_alegere_instalator_'.date('Y-m-d H:i:s').'.xlsx'
            );
        }
        abort(404);
    }
}
