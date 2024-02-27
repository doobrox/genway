<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pagina;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Dotlogics\Grapesjs\App\Traits\EditorTrait;

class PaginaController extends Controller
{
    use EditorTrait;

    public function editor(Request $request, Pagina $pagina)
    {
        if($pagina->pagebuilder === 1) {
            return $this->show_gjs_editor($request, $pagina);
        }
        return redirect()->route('admin');
    }

    public function raw(Pagina $pagina)
    {
        return view('admin.pages.raw', [
            'page' => $pagina,
        ]);
    }
}