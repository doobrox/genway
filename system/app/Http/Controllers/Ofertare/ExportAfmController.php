<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\AfmForm;
use App\Models\User;
use App\Models\QueuedExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ExportAfmController extends Controller
{
    public function index(Request $request, $section = 2021)
    {
        if(AfmForm::validateSection($section)) {
            return view('ofertare.exporturi.index', [
                'sectiune' => __('Exporturi AFM'),
                'can_delete' => true,
                'items' => auth()->user()->exporturi()
                    ->where('sectiune', 'ofertare_afm_'.$section)
                    ->orderByDesc('created_at')->paginate(20),
            ]);
        } else {
            abort(404);
        }
    }

    public function download(QueuedExport $export)
    {
        return $export->download();
    }

    public function delete(QueuedExport $export)
    {
        $export->delete();
        return back()->with(['status' => __('Exportul a fost sters.')], 200);
    }
}
