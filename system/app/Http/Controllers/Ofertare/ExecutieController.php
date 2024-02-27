<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\AfmForm;
use App\Models\Fisier;
use App\Models\User;
use App\Models\Ofertare\Executie;
use App\Models\Ofertare\Programare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ExecutieController extends Controller
{
    public function index()
    {
        return view('ofertare.montaje.index', [
            'sectiune' => __('Executii'),
            'items' => auth()->user()->userProgramari()->withStatus()->available()->paginate(20),
            'add_route' => null,
            'can_delete' => false
        ]);
    }

    public function create(Programare $programare)
    {
        return view('ofertare.montaje.create', $this->commonParameters() + [
            'programare' => $programare,
        ]);
    }

    public function store(Request $request, Programare $programare)
    {
        $input = $request->validate($this->rules(),[],$this->names());

        $var['siruri_panouri'] = $input['siruri_panouri'];
        unset($input['siruri_panouri']);
        $var['link_sistem'] = $input['link_sistem'];
        unset($input['link_sistem']);
        
        $montaj = Executie::create($var + [
            'programare_id' => $programare->id
        ]);
        // dd($request->file($key.'.'.$key2));

        $input['status'] = 1;
        foreach ($input as $key => $value) {
            if(is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if($request->hasFile($key.'.'.$key2)) {
                        $input[$key][$key2] = Fisier::store(
                            $request->file($key.'.'.$key2),
                            'ofertare/montaje/'.$montaj->id.'/',
                            Executie::class,
                            $montaj->id,
                            auth()->id()
                        );
                        if(isset($input[$key]['error'])) {
                            $input['status'] = 0;
                            break;
                        }
                    }
                }
            } elseif($request->hasFile($key)) {
                $input[$key] = Fisier::store(
                    $request->file($key), 
                    'ofertare/montaje/'.$montaj->id.'/',
                    Executie::class,
                    $montaj->id,
                    auth()->id()
                );
                if(isset($input[$key]['error'])) {
                    $input['status'] = 0;
                    break;
                }
            }
        }
        $montaj->update($input);
        if($input['status'] == 0) {
            return redirect()->route('ofertare.montaje.edit', $montaj)->with([
                'error' => __('Formularul nu a putut fi trimis. Incearca din nou.')
            ]);
        }

        return redirect()->route('ofertare.montaje.edit', $montaj)->with([
            'status' => __('Formularul de confirmare montaj a fost trimis.')
        ]);
    }

    public function edit(Executie $executie)
    {
        return view('ofertare.montaje.create', $this->commonParameters() + [
            'item' => $executie,
            'echipa' => $executie->pivotEchipa->toArray()
        ]);
    }

    public function update(Request $request, Executie $montaj)
    {
        $input = $request->validate($this->rules($montaj->id),[],$this->names());

        $var['siruri_panouri'] = $input['siruri_panouri'];
        unset($input['siruri_panouri']);
        $var['link_sistem'] = $input['link_sistem'];
        unset($input['link_sistem']);
        
        $montaj->update($var);

        $input['status'] = 1;
        foreach ($input as $key => $value) {
            if(is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if($request->hasFile($key.'.'.$key2)) {
                        $input[$key][$key2] = Fisier::store(
                            $request->file($key.'.'.$key2),
                            'ofertare/montaje/'.$montaj->id.'/',
                            Executie::class,
                            $montaj->id,
                            auth()->id()
                        );
                        if(isset($input[$key]['error'])) {
                            $input['status'] = 3;
                            break;
                        } elseif(isset($montaj[$key][$key2]) && $file = Fisier::firstWhere('id', $montaj[$key][$key2])) {
                            $file->delete();
                        }
                    }
                }
            } elseif($request->hasFile($key)) {
                $input[$key] = Fisier::store(
                    $request->file($key), 
                    'ofertare/montaje/'.$montaj->id.'/',
                    Executie::class,
                    $montaj->id,
                    auth()->id()
                );
                if(isset($input[$key]['error'])) {
                    $input['status'] = 3;
                    break;
                } elseif(isset($montaj[$key]) && $file = Fisier::firstWhere('id', $montaj[$key])) {
                    $file->delete();
                }
            }
        }
        $montaj->update($input);
        if($input['status'] == 3) {
            return redirect()->route('ofertare.montaje.edit', $montaj)->with([
                'error' => __('Formularul nu a putut fi retrimis. Incearca din nou.')
            ]);
        }
        return back()->with(['status' => __('Formularul de confirmare montaj a fost retrimis.')]);
    }

    public function get(Executie $executie, Fisier $fisier)
    {
        return $fisier->stream();
    }

    // public function delete(Request $request, Executie $executie)
    // {
    //     $executie->delete();
    //     return back()->with(['status' => __('Programarea a fost stearsa.')], 200);
    // }


    protected function rules($id = null)
    {
        $required = $id ? 'nullable' : 'required';
        return [
            // 'programare_id' => ['required', 'max:255', 
            //     Rule::exists(EchipaProgramare::class, 'programare_id')->where(function($query) {
            //         return $query->where('user_id', auth()->id())->where('lider', 1);
            //     })
            // ],
            'siruri_panouri' => ['required', 'array', 'min:1'],
            'siruri_panouri.*' => ['required', 'string', 'min:1', 'max:25'],
            'poze_panouri' => [$required, 'array', 'size:3'],
            'poze_panouri.*' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_invertor' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_smartmeter' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_dc_box' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_siguranta_ac' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_legare_structuri' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_cablu_casa' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'poza_valoare_masurata_priza' => [$required, 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'link_sistem' => ['required', 'string', 'max:1010'],
        ];
    }

    protected function names()
    {
        return [
            'siruri_panouri' => __('siruri_panouri'),
            'siruri_panouri.*' => __('siruri_panouri'),
            'poze_panouri' => __('poze_panouri'),
            'poze_panouri.*' => __('poze_panouri'),
            'poza_invertor' => __('poza_invertor'),
            'poza_smartmeter' => __('poza_smartmeter'),
            'poza_dc_box' => __('poza_dc_box'),
            'poza_siguranta_ac' => __('poza_siguranta_ac'),
            'poza_legare_structuri' => __('poza_legare_structuri'),
            'poza_cablu_casa' => __('poza_cablu_casa'),
            'poza_valoare_masurata_priza' => __('poza_valoare_masurata_priza'),
            'link_sistem' => __('link_sistem'),
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Executii'),
            'browse_route' => route('ofertare.montaje.browse'),
            'form' => true,
        ];
    }
}
