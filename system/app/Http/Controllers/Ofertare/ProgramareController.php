<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\AfmForm;
use App\Models\DynamicModel;
use App\Models\Fisier;
use App\Models\Judet;
use App\Models\Localitate;
use App\Models\User;
use App\Models\Ofertare\Programare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProgramareController extends Controller
{
    public function index(Request $request)
    {
        $items = $this->search($request);
        return view('ofertare.programari.index', [
            'sectiune' => __('Programari'),
            'items' => Programare::withFormularePerSection($items, true),
            // 'add_route' => route('ofertare.programari.create', ['an' => 2021]),
            'can_delete' => true,
            'search' => $request->input(),
            'judete' => Judet::select('id','nume')->get(),
            'localitati' => old('judet_imobil', $request->input('judet_imobil'))
                ? Localitate::select('id','nume')->where('id_judet', old('judet_imobil', $request->input('judet_imobil')))->get()
                : [],
        ]);
    }

    public function search(Request $request)
    {
        $items = Programare::with('echipa');
        $formular = ['nume','prenume','cnp','adresa_imobil','telefon'];
        $info = ['data_montare_panouri','data_montare_invertor_pif'];
        $adrese = ['judet_imobil','localitate_imobil'];
        $items->whereHas('formular', function($query) use($request, $formular, $adrese) {
            foreach($formular as $column) {
                if($column == 'adresa_imobil' && $request->$column) {
                    $query->where(
                        \DB::raw('CONCAT(strada_imobil,", ",numar_imobil,", ",bloc_imobil,", ",sc_imobil,", ",et_imobil,", ",ap_imobil)'),
                        'like', '%'.$request->$column.'%'
                    );
                } elseif($request->$column) {
                    $query->where($column, 'like', $request->$column.'%');
                }
            }
            foreach($adrese as $column) {
                if($request->$column) {
                    $query->where($column, $request->$column);
                }
            }
        })->whereHas('formular.info', function($query) use($request, $info) {
            foreach($info as $column) {
                if($request->$column) {
                    $query->whereDate($column, \Carbon\Carbon::createFromFormat('d/m/Y', $request->$column)->format('Y-m-d'));
                }
            }
        });
        return $items->orderBy('formular_id')->paginate(20);
    }

    public function download(Programare $programare, $slug, Fisier $fisier)
    {
        return $fisier->download();
    }

    public function create($section, int $formular = null)
    {
        if($formular != null && !AfmForm::setSection($section)->where('id', $formular)->exists()) {
            return abort(404);
        }
        if($programare = Programare::where('formular_id', $formular)->where('an', $section)->first()) {
            return redirect()->route('ofertare.programari.edit', $programare);
        }
        $formular = AfmForm::setSection($section)->withInfo()->find($formular);
        return view('ofertare.programari.create', $this->commonParameters() + [
            'an' => $section,
            'formular' => $formular,
            'save_route' => $formular
                ? route('ofertare.programari.store.form', [$section, $formular->id])
                : route('ofertare.programari.store', $section)
        ]);
    }

    public function store(Request $request, string $an = null, int $formular = null)
    {
        return $this->save($request->merge($an && $formular ? [
            'an' => $an,
            'formular_id' => $formular,
        ] : []));
    }

    public function edit(Programare $programare)
    {
        return view('ofertare.programari.create', $this->commonParameters() + [
            'item' => $programare,
            'formular' => $programare->formular()->withInfo()->with('judetImobil')->first(),
            'save_route' => route('ofertare.programari.update', $programare),
            'echipa' => $programare->pivotEchipa->toArray()
        ]);
    }

    public function update(Request $request, Programare $programare)
    {
        return $this->save($request, $programare);
    }

    public function delete(Request $request, Programare $programare)
    {
        $programare->delete();
        return back()->with(['status' => __('Programarea a fost stearsa.')], 200);
    }

    public function formulare(Request $request, string $an)
    {
        if(AfmForm::validateSection($an)) {
            return AfmForm::setSection($an)->select('id')
                ->where('id', 'like', $request->input('search').'%')
                ->limit(10)->get()->toJson();
        }
        return json_encode([]);
    }

    public function echipa(Request $request, int $echipa = null)
    {
        if(Validator::make(['echipa' => $echipa], ['echipa' => ['required', 'exists:echipe,id']])->passes()) {
            return DynamicModel::table('echipe_personal')->select('id_user','procent')
                // ->addSelectRaw('CONCAT(nume, " ", prenume) as nume')
                ->where('id_echipa', $echipa)->get()->toJson();
        }
        return json_encode([]);
    }

    protected function save(Request $request, Programare $programare = null)
    {
        $request = $this->mergeSumProcent($request);
        $input = $request->validate($this->rules($programare ? $programare->id : null),$this->messages(),$this->names());

        if($programare == null) {
            // get only the paramters for Programare model
            $programare = Programare::create(collect($input)->only($this->programareParameters())->toArray());
            $created = true;
        }
        // get only the paramters that must be updated in AfmForm model info table
        $programare->formularInfo()->update(collect($input)->only($this->datesParameters())->toArray());
        if(isset($input['echipa'])) {
            $programare->echipa()->sync($this->getFormatedEchipa($input['echipa'], $input['lider']));
        }
        return redirect()->route('ofertare.programari.edit', $programare)->with([
            'status' => isset($created) ? __('Programarea a fost creata.') : __('Programarea a fost actualizata.')
        ], 200);
    }

    protected function mergeSumProcent(Request $request)
    {
        return $request->merge([
            'procent_suma' => array_sum($request->input('echipa.*.procent'))
        ]);
    }

    protected function getFormatedEchipa($echipa, $lider)
    {
        return collect($echipa)->mapWithKeys(function($item, $key) use ($lider) {
            return [$item['user_id'] => [
                'procent' => $item['procent'],
                'lider' => $lider == $key ? 1 : null
            ]];
        });
    }

    // protected function getFormatedDates($dates)
    // {
    //     return collect($dates)->only($this->datesParameters())->mapWithKeys(function($item, $key) {
    //         return [$key => \Carbon\Carbon::createFromFormat('d/m/Y', $item)->format('Y-m-d')];
    //     })->toArray();
    // }

    protected function programareParameters()
    {
        return ['formular_id', 'an'];
    }

    protected function datesParameters()
    {
        return ['data_montare_panouri', 'data_montare_invertor_pif'];
    }

    protected function rules($id = null)
    {
        return [
            'data_montare_panouri' => ['required', 'date_format:Y-m-d'],
            'data_montare_invertor_pif' => ['required', 'date_format:Y-m-d'],
            'echipa.*' => ['required', 'array', 'min:1'],
            'echipa.*.user_id' => ['required', 'integer', 'min:1',
                Rule::exists(User::class, 'id')->where(function($query) {
                    return (new User)->tehnicieni($query);
                })
            ],
            'echipa.*.procent' => ['required', 'numeric', 'min:0', 'max:100'],
            'procent_suma' => ['required', 'integer', 'in:100'],
            'lider' => ['required', 'integer', 'min:0', 'max:100'],
        ] + ($id == null ? AfmForm::ruleSection() + AfmForm::ruleValidAfmForm() : []);
    }

    protected function messages()
    {
        return [
            'procent_suma.*' => __('Suma procentelor trebuie sa fie egala cu 100.'),
        ];
    }

    protected function names()
    {
        return [
            'formular_id' => 'formular',
            'echipa.*' => 'membru',
            'echipa.*.user_id' => 'membru',
            'echipa.*.procent' => 'procent membru',
            'procent_suma' => 'suma procent',
            'echipa.lider' => 'lider echipa',
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Programari'),
            'browse_route' => route('ofertare.programari.browse'),
            'form' => true,
            'tehnicieni' => User::tehnicieni()->get(),
            'sabloane' => DynamicModel::table('echipe')->get(),
        ];
    }
}
