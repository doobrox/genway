<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\AfmForm;
use App\Models\Fisier;
use App\Models\Judet;
use App\Models\Localitate;
use App\Models\User;
use App\Models\Ofertare\SablonAFM;
use App\Models\Ofertare\Sarcina;
use App\Models\Ofertare\SarcinaMesaj;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class SarcinaController extends Controller
{
    public function index($section = null)
    {
        $items = Sarcina::query();
        if($section) {
            $items->where('year', '=', $section);
        }
        if(request()->input('status') == 0) {
            $items->where('status', '=', 0);
        } elseif(request()->input('status') == 1) {
            $items->whereIn('status', [1, 2]);
        }
        if(!auth()->user()->can('sarcini.*')) {
            $items->where(function ($query) {
                $query->where('from_id', '=', auth()->id())
                    ->orWhere('to_id', '=', auth()->id());
            });
        }

        return view('ofertare.sarcini.index', [
            'sectiune' => __('Sarcini'),
            'items' => $items->paginate(20),
            'types' => Sarcina::$types,
            'stats' => Sarcina::$stats,
            'add_route' => route('ofertare.sarcini.create', $section ?? 2021),
            'can_delete' => true
        ]);
    }

    public function create($section = 2021)
    {
        return view('ofertare.sarcini.create', $this->commonParameters() + [
            'section' => $section,
            'save_route' => route('ofertare.sarcini.store', $section),
        ]);
    }

    public function getUsersOfertare()
    {
        $items = User::select(['id', \DB::raw('CONCAT(nume, " ", prenume) as coloana_nume')])
            ->where('agent', '>=', 1)
            ->orWhere('admin', '=', 1);

        return $items->get()->mapWithKeys(function ($item, $key) {
                return [$item->id => $item->coloana_nume];
            })->toJson();
    }

    public function searchAfm(Request $request, $section = 2021, $id = null)
    {
        $t = AfmForm::setSection($section)->getTable();
        $i = AfmForm::setSection($section)->getInfoTable();
        $j = app(Judet::class)->getTable();
        $l = app(Localitate::class)->getTable();

        $select = [
            $t.'.id',
            \DB::raw('CONCAT("#", '.$t.'.id, " ", '.$t.'.nume, " ", '.$t.'.prenume, " ", '.$t.'.telefon, ", ", '.$j.'.nume, ", ", '.$l.'.nume, ", ", '.$t.'.strada_imobil, ", ", '.$t.'.numar_imobil, ", ", '.$i.'.marca_invertor) as text')
        ];

        if($id) {
            return AfmForm::setSection($section)
            ->select($select)
            ->join($i, $t.'.id', '=', $i.'.id_formular')
            ->join($j, $t.'.judet_imobil', '=', $j.'.id')
            ->join($l, $t.'.localitate_imobil', '=', $l.'.id')
            ->where($t.'.id', '=', $id)
            ->first();
        }

        $input = $request->validate(['search' => ['required']]);

        return AfmForm::setSection($section)
            ->select($select)
            ->join($i, $t.'.id', '=', $i.'.id_formular')
            ->join($j, $t.'.judet_imobil', '=', 'geo_judete.id')
            ->join($l, $t.'.localitate_imobil', '=', $l.'.id')
            ->where($t.'.nume', 'like', '%'.$input['search']."%")
            ->orWhere($t.'.prenume', 'like', '%'.$input['search']."%")
            ->orWhere($t.'.id', '=', $input['search'])
            ->get();
    }

    public function store(Request $request, $section = 2021)
    {
        $input = $request->validate($this->rules(), [], $this->names());
        // TODO: daca nu a bifat client manual, formular_id trebuie sa fie obligatoriu
        // daca a bifat client manual, atunci acele informatii trebuie sa fie obligatorii

        $sarcina = Sarcina::create([
            'title' => $input['title'],
            'description' => $input['description'] ?? null,
            'year' => $section,
            'from_id' => auth()->id(),
            'to_id' => $input['to_id'],
            // 'return_id' => $input['return_id'] ?? null,
            'formular_id' => $input['formular_id'] ?? null,
            'type' => $input['type'],
            'status' => $input['status'],
            'client_manual' => $input['client_manual'] ?? 0,
        ]);
        if($sarcina->client_manual == 1) {
            $sarcina->info_client = [
                'nume' => $input['client_nume'],
                'prenume' => $input['client_prenume'],
                'telefon' => $input['client_telefon'],
                'judet_imobil' => $input['client_judet_imobil'],
                'localitate_imobil' => $input['client_localitate_imobil'],
                'strada_imobil' => $input['client_strada_imobil'],
                'numar_imobil' => $input['client_numar_imobil'],
                'marca_invertor' => $input['client_marca_invertor'],
            ];
            $sarcina->save();
        }

        $erori_fisiere = [];
        foreach($input['attachments'] ?? [] as $key => $file) {

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename .= '_'.time().rand(100,999);
            $filename .= '.'.$file->getClientOriginalExtension();
            $path = 'fisiere_sarcini/'.$section.'/'.$sarcina->id.'/';
            try {
                if($file->storeAs($path, $filename, 's3')) {
                    $fisier = Fisier::create([
                        'user_id' => auth()->id(),
                        'model_type' => 'App\\Models\\Ofertare\\Sarcina',
                        'model_id' => $sarcina->id,
                        'name' => $filename,
                        'path' => $path
                    ]);
                } else {
                    $erori_fisiere[] = __('Fisierul '.$filename.' nu a fost incarcat.');
                }
            } catch(\Exception $e) {
                \Log::info($e);
                $erori_fisiere[] = __('Fisierul '.$filename.' nu a fost incarcat.');
            }
        }
        if(!empty($erori_fisiere)) {
            return redirect()->route('ofertare.sarcini.update', $section)->withErrors($erori_fisiere);
        }

        return redirect()->route('ofertare.sarcini.create', $section)->with([
            'status' => __('Sarcina :title a fost creata.', ['title' => $sarcina->title])
        ]);
    }

    public function edit(Sarcina $sarcina)
    {
        return view('ofertare.sarcini.create', $this->commonParameters() + [
            'item' => $sarcina,
            'section' => $sarcina->year,
            'save_route' => route('ofertare.sarcini.update', $sarcina),
        ]);
    }

    public function update(Request $request, Sarcina $sarcina)
    {
        $input = $request->validate($this->rules($sarcina->id), [], $this->names());

        $sarcina->update([
            'title' => $input['title'],
            'description' => $input['description'] ?? null,
            'to_id' => $input['to_id'],
            // 'return_id' => $input['return_id'] ?? null,
            'formular_id' => $input['formular_id'] ?? null,
            'type' => $input['type'],
            'status' => $input['status'],
            'client_manual' => $input['client_manual'] ?? 0,
        ]);

        if($sarcina->client_manual == 1) {
            $sarcina->info_client = [
                'nume' => $input['client_nume'],
                'prenume' => $input['client_prenume'],
                'telefon' => $input['client_telefon'],
                'judet_imobil' => $input['client_judet_imobil'],
                'localitate_imobil' => $input['client_localitate_imobil'],
                'strada_imobil' => $input['client_strada_imobil'],
                'numar_imobil' => $input['client_numar_imobil'],
                'marca_invertor' => $input['client_marca_invertor'],
            ];
            $sarcina->save();
        }

        $txt_update = '<p>Modificari facute:</p>';
        foreach($sarcina->toArray() as $col => $val) {
            if(isset($input[$col]) && !is_iterable($input[$col]) && $sarcina->$col != $input[$col]) {
                $txt_update .= '<p>- "'.$col.'"" a fost modificat din "'.$sarcina->$col.'" in "'.$input[$col].'"<p>';
            }
        }

        $erori_fisiere = [];
        foreach($input['attachments'] ?? [] as $key => $file) {

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename .= '_'.time().rand(100,999);
            $filename .= '.'.$file->getClientOriginalExtension();
            $path = 'fisiere_sarcini/'.$sarcina->year.'/'.$sarcina->id.'/';
            try {
                if($file->storeAs($path, $filename, 's3')) {
                    $fisier = Fisier::create([
                        'user_id' => auth()->id(),
                        'model_type' => 'App\\Models\\Ofertare\\Sarcina',
                        'model_id' => $sarcina->id,
                        'name' => $filename,
                        'path' => $path
                    ]);
                    $txt_update .= '<p>- "Adaugare fisier: "'.$filename.'"<p>';
                } else {
                    $erori_fisiere[] = __('Fisierul '.$filename.' nu a fost incarcat.');
                }
            } catch(\Exception $e) {
                \Log::info($e);
                $erori_fisiere[] = __('Fisierul '.$filename.' nu a fost incarcat.');
            }
        }

        SarcinaMesaj::create([
            'sarcina_id' => $sarcina->id,
            'user_id' => auth()->id(),
            'description' => $txt_update,
        ]);

        return redirect()->route('ofertare.sarcini.update', $sarcina->id)->with([
            'status' => __('Sarcina :title a fost actualizata.', ['title' => $sarcina->title])
        ] + $erori_fisiere);
    }

    public function show(Sarcina $sarcina)
    {
        return view('ofertare.sarcini.show', $this->commonParameters() + [
            'item' => $sarcina,
            'afm' => $sarcina->getAfmForm(),
            'section' => $sarcina->year,
            'types' => Sarcina::$types,
            'stats' => Sarcina::$stats,
        ]);
    }

    public function delete(Request $request, Sarcina $sarcina)
    {

        foreach($sarcina->fisiere ?? [] as $fisier) {
            if($f = Fisier::find($fisier->id)) {
                $f->delete();
            }
        }

        $sarcina->delete();

        session()->flash('status', __('Sarcina :title a fost stearsa.', ['title' => $sarcina->title]));

        if($request->has('redirect_url')) {
            return redirect($request['redirect_url']);
        }
        return ['refresh' => true];
    }

    public function mesajStore(Request $request, Sarcina $sarcina)
    {
        $input = $request->validate([
            'description' => ['required'],
            'attachments.*' => ['nullable', 'file', "max:2048"],
        ], [], [
            'description' => __('Descriere'),
            'attachments' => __('Fisiere'),
        ]);

        $mesaj = SarcinaMesaj::create([
            'sarcina_id' => $sarcina->id,
            'user_id' => auth()->id(),
            'description' => $input['description'],
        ]);

        $erori_fisiere = [];
        foreach($input['attachments'] ?? [] as $key => $file) {

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename .= '_'.time().rand(100,999);
            $filename .= '.'.$file->getClientOriginalExtension();
            $path = 'fisiere_sarcini/'.$sarcina->year.'/'.$sarcina->id.'/';
            try {
                if($file->storeAs($path, $filename, 's3')) {
                    $fisier = Fisier::create([
                        'user_id' => auth()->id(),
                        'model_type' => 'App\\Models\\Ofertare\\SarcinaMesaj',
                        'model_id' => $mesaj->id,
                        'name' => $filename,
                        'path' => $path
                    ]);
                } else {
                    $erori_fisiere[] = __('Fisierul '.$filename.' nu a fost incarcat.');
                }
            } catch(\Exception $e) {
                \Log::info($e);
                $erori_fisiere[] = __('Fisierul '.$filename.' nu a fost incarcat.');
            }
        }
        if(!empty($erori_fisiere)) {
            return redirect()->back()->withErrors($erori_fisiere);
        }

        return redirect()->back()->with([
            'status' => __('Mesajul a fost adaugat cu succes.')
        ]);
    }

    public function check(Request $request, Sarcina $sarcina)
    {
        // if($sarcina->status == 0 && auth()->id() == $sarcina->to_id) {
        //     $sarcina->status = 1;
        //     $msg = __('Sarcina :title a fost marcata ca finalizata.', ['title' => $sarcina->title]);
        // } elseif($sarcina->type == 1 && $sarcina->status == 1 && auth()->id() == $sarcina->from_id) {
        //     $sarcina->status = 2;
        //     $msg = __('Sarcina :title a fost marcata ca verificata.', ['title' => $sarcina->title]);
        // } else {
        //     return back()->withErrors(['status' => __('Ce nu a functionat, va rugam incercati din nou')]);
        // }

        // $sarcina->finished_at = now();
        // $sarcina->save();

        // SarcinaMesaj::create([
        //     'sarcina_id' => $sarcina->id,
        //     'user_id' => auth()->id(),
        //     'description' => $msg,
        // ]);

        // return back()->with(['status' => $msg]);

        if($sarcina->status == 0 && auth()->id() == $sarcina->to_id) {

            $sarcina->status = 1;
            $sarcina->finished_at = now();
            $sarcina->save();

            SarcinaMesaj::create([
                'sarcina_id' => $sarcina->id,
                'user_id' => auth()->id(),
                'description' => __('Sarcina :title a fost marcata ca finalizata.', ['title' => $sarcina->title]),
            ]);

            return back()->with(['status' => __('Sarcina :title a fost marcata ca finalizata.', ['title' => $sarcina->title])]);

        } elseif($sarcina->type == 1 && $sarcina->status == 1 && auth()->id() == $sarcina->from_id) {

            $sarcina->status = 2;
            $sarcina->finished_at = now();
            $sarcina->save();

            SarcinaMesaj::create([
                'sarcina_id' => $sarcina->id,
                'user_id' => auth()->id(),
                'description' => __('Sarcina :title a fost marcata ca verificata.', ['title' => $sarcina->title]),
            ]);

            return back()->with(['status' => __('Sarcina :title a fost marcata ca verificata.', ['title' => $sarcina->title])]);
        }

        return back()->withErrors(['status' => __('Ce nu a functionat, va rugam incercati din nou')]);
    }

    protected function rules($id = null)
    {
        return [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'attachments.*' => ['nullable', 'file', "max:2048"],
            'to_id' => ['required', 'integer', 'min:1', 'exists:\\App\\Models\\User,id'],
            'return_id' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\User,id'],
            'formular_id' => ['nullable', 'integer', 'min:1'],
            'type' => ['required', 'numeric', 'min:0', 'max:1'],
            'status' => ['required', 'numeric', 'min:0', 'max:1'],
            'client_manual' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'client_nume' => ['exclude_unless:client_manual,1', 'required', 'max:255'],
            'client_prenume' => ['exclude_unless:client_manual,1', 'required', 'max:255'],
            'client_telefon' => ['exclude_unless:client_manual,1', 'required', 'max:255'],
            'client_judet_imobil' => ['exclude_unless:client_manual,1', 'required', 'numeric', 'max:50'],
            'client_localitate_imobil' => ['exclude_unless:client_manual,1', 'required', 'numeric', 'max:14000'],
            'client_strada_imobil' => ['exclude_unless:client_manual,1', 'required', 'max:255'],
            'client_numar_imobil' => ['exclude_unless:client_manual,1', 'required', 'max:255'],
            'client_marca_invertor' => ['exclude_unless:client_manual,1', 'required', 'max:255'],
        ];
    }

    protected function names()
    {
        return [
            'title' => __('Titlu'),
            'description' => __('Descriere'),
            'attachments' => __('Fisiere'),
            'to_id' => __('Catre'),
            'return_id' => __('Intoarcere'),
            'formular_id' => __('Formular afm'),
            'type' => __('Tip'),
            'status' => __('Status'),
            'client_manual' => __(' Client negasit in formulare/cu plata'),
            'client_nume' => __('Nume client'),
            'client_prenume' => __('Prenume client'),
            'client_telefon' => __('Telefon client'),
            'client_judet_imobil' => __('Judet imobil client'),
            'client_localitate_imobil' => __('Localitate imobil client'),
            'client_strada_imobil' => __('Strada imobil client'),
            'client_numar_imobil' => __('Numar imobil client'),
            'client_marca_invertor' => __('Marca invertor client'),
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Sarcini'),
            'browse_route' => route('ofertare.sarcini.browse'),
        ];
    }
}
