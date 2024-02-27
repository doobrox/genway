<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Comentariu;
use App\Models\Produs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Categorie $categorie, $slug, Produs $produs)
    {
        return view('produs', [
            'breadcrumbs' => $categorie->breadcrumbs($produs),
            'produs' => $produs,
            'producator' => $produs->producator,
            'galerie' => $produs->galerie,
            'fisiere' => $produs->fisiere_tehnice,
            'comentarii' => $produs->comentarii,
            'recomandate' => $produs->recomandate()->limit(5)->get(),
            'asemanatoare' => $categorie->produse()->whereNot('produse.id', $produs->id)->image()->limit(5)->get(),
        ]);
    }

    public function review(Request $request, $slug, Produs $produs)
    {
        $inputs = Validator::make($request->all() + ['ip' => $request->ip()],[
            'evaluare' => ['required','integer','min:1','max:5'],
            'comentariu' => ['required','string','max:1500'],
            'ip' => ['required', 'ip', function ($attribute, $value, $fail) use ($produs) {

                    if (
                        Comentariu::where('id_produs', $produs->id)
                            ->where('ip', $value)
                            ->where('created_at', '>=', Carbon::parse('-24 hours'))
                            ->exists()
                    ) {
                        $fail(__('Puteti adauga doar un comentariu / produs o data la 24 de ore.'));
                    }
                },
            ],
        ],[
            'ip.required' => __('S-a intamplat ceva neasteptat, va rog incercati mai tarziu.'),
            'ip.ip' => __('S-a intamplat ceva neasteptat, va rog incercati mai tarziu.'),
        ])->validateWithBag('review');

        Comentariu::create([
            'id_user' => auth()->id(),
            'id_produs' => $produs->id,
            'nota' => $inputs['evaluare'],
            'comentarii' => $inputs['comentariu'],
            'ip' => $inputs['ip'],
            'data_adaugare' => now(),
            'activ' => 1,
        ]);

        return back()->with([
            'review' => __('Multumim pentru review. Va mai asteptam.'),
        ]);
    }
}
