<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\SablonDocument;
use App\Models\Ofertare\Oferta;
use App\Models\Ofertare\OfertaAFM;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Dotlogics\Grapesjs\App\Traits\EditorTrait;
use Spatie\Permission\Models\Permission;

class SablonDocumentController extends Controller
{
    use EditorTrait;

    public function index()
    {
        return view('ofertare.sabloane_documente.index', [
            'sectiune' => __('Sabloane documente'),
            'items' => SablonDocument::paginate(25),
            'add_route' => route('ofertare.sabloane_documente.create'),
            'can_delete' => true
        ]);
    }

    public function show(SablonDocument $sablon)
    {
        if($sablon && $sablon->pagebuilder) {
            $oferta = OfertaAFM::where('oferta_id', 16715)->where('section', $sablon->pivot_key ?? '2023')->first();
            $panou = $oferta->panou ?? collect();
            $invertor = $oferta->invertor ?? collect();
            if($oferta) {
                $oferta->setAppends($oferta->getMutatedAttributes());
            }

            return SablonDocument::generateDocument($sablon, null, 
                affix_array_keys($panou->toArray(), '_panouri') 
                + affix_array_keys($invertor->toArray(), '_invertor') 
                + ($oferta ? $oferta->toArray() : [])
            );
        }
        if($sablon) {
            return SablonDocument::generateRawDocument($sablon);
        }
        return abort(404);
    }

    public function create()
    {
        return view('ofertare.sabloane_documente.create', $this->commonParameters() + [
            'save_route' => route('ofertare.sabloane_documente.store'),
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate($this->rules(), [], $this->names());

        $item = SablonDocument::create([
            'nume' => $input['nume'],
            'subiect' => $input['subiect'],
            'continut' => $input['continut'],
            'detalii' => $input['detalii'] ?? null,
            'styles' => $input['styles'] ?? null,
            'scripts' => $input['scripts'] ?? null,
        ]);

        return redirect()->route('ofertare.sabloane_documente.create')->with([
            'status' => __('Sablonul :title a fost creat.', ['title' => $item->nume])
        ]);
    }

    public function edit(SablonDocument $sablon)
    {
        return view('ofertare.sabloane_documente.create', $this->commonParameters() + [
            'item' => $sablon,
            'save_route' => route('ofertare.sabloane_documente.update', $sablon),
        ]);
    }

    public function update(Request $request, SablonDocument $sablon)
    {
        $input = $request->validate($this->rules($sablon->id), [], $this->names());

        $sablon->update([
            'nume' => $input['nume'],
            'subiect' => $input['subiect'],
            'continut' => $input['continut'],
            'detalii' => $input['detalii'] ?? null,
            'styles' => $input['styles'] ?? null,
            'scripts' => $input['scripts'] ?? null,
        ]);

        return redirect()->route('ofertare.sabloane_documente.update', $sablon->id)->with([
            'status' => __('Sablonul :title a fost actualizat.', ['title' => $sablon->nume])
        ]);
    }

    public function delete(Request $request, SablonDocument $sablon)
    {
        $sablon->delete();

        session()->flash('status', __('Sablonul :nume a fost sters.', ['nume' => $sablon->nume]));

        if($request->has('redirect_url')) {
            return redirect($request['redirect_url']);
        }
        return ['refresh' => true];
    }

    public function editor(Request $request, SablonDocument $sablon)
    {
        if($sablon->pagebuilder === 1) {
            $sablon->activatePagebuilderPlugin('grapesjs-default-panels');
            return $this->show_gjs_editor($request, $sablon);
        }
        return redirect()->back();
    }

    // public function raw(SablonDocument $sablon)
    // {
    //     return view('admin.pages.raw', [
    //         'page' => $sablon,
    //     ]);
    // }

    protected function rules($id = null)
    {
        return [
            'nume' => ['required', 'max:255'],
            'subiect' => ['required', 'max:255'],
            'continut' => ['required'],
            'detalii' => ['nullable'],
            'styles' => ['nullable'],
            'scripts' => ['nullable'],
        ];
    }

    protected function names()
    {
        return [
            'nume' => __('nume'),
            'subiect' => __('subiect'),
            'continut' => __('continut'),
            'detalii' => __('detalii'),
            'styles' => __('styles'),
            'scripts' => __('scripts'),
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Sabloane documente'),
            'browse_route' => route('ofertare.sabloane_documente.browse'),
        ];
    }
}
