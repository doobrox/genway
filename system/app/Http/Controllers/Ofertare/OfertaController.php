<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\SablonDocument;
use App\Models\Ofertare\Oferta;
use App\Models\Ofertare\OfertaAFM;
use App\Models\Ofertare\SectiuneAFM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfertaController extends Controller
{
    public function index(Request $request)
    {
        return true;
    }

    public function createAfm(Request $request, Oferta $oferta, $section)
    {
        $section = SectiuneAFM::where('nume', $section)->firstOrFail();
        $validator = Validator::make($request->all(), OfertaAFM::rules());

        if($request->wantsJson() && $validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => \Blade::render('<x-ajax-validation-errors :errors="$errors" />', [
                    'errors' => $validator->errors()
                ]),
            ]);
        }

        $inputs = array_filter($validator->validate());
        $oferta->oferteAfm()->updateOrCreate([
            'section' => $section->nume
        ], $inputs);

        return $request->wantsJson() ? response()->json([
            'status' => 200,
            'message' => __('Oferta a fost actualizata.'),
        ]) : back()->with([
            'status' => __('Oferta a fost actualizata.'),
        ]);
    }

    public function generate(Request $request, Oferta $oferta, $section, $sablon)
    {
        if($request->has('user_email')) {
            return redirect()->route($request->route()->getName(), [$oferta->id, $section, $sablon]);
        }
        $oferta_afm = OfertaAFM::where('oferta_id', $oferta->id)->where('section', $section)->firstOrFail();
        
        $panou = $oferta_afm->panou ?? collect();
        $invertor = $oferta_afm->invertor ?? collect();
        $oferta_afm->setAppends($oferta_afm->getMutatedAttributes());

        return SablonDocument::generateDocument($sablon, $section, 
            $oferta_afm->only(['marca_invertor'])
            + affix_array_keys($panou->toArray(), '_panouri') 
            + affix_array_keys($invertor->toArray(), '_invertor') 
            + $oferta_afm->toArray()
        );
    }
}
