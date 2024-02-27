<?php

namespace App\Http\Controllers\Ofertare;

use App\Exports\Ofertare\AfmTableExport;
use App\Events\SendMails;
use App\Http\Controllers\Controller;
use App\Models\AfmForm;
use App\Models\DynamicModel;
use App\Models\Fisier;
use App\Models\Judet;
use App\Models\SablonDocument;
use App\Models\User;
use App\Models\QueuedExport;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Models\Ofertare\DescriereColoanaAFM;
use App\Models\Ofertare\Eveniment;
use App\Models\Ofertare\Invertor;
use App\Models\Ofertare\Oferta;
use App\Models\Ofertare\SablonAFM;
use App\Models\Ofertare\SectiuneAFM;
use App\Traits\FileAccessTrait;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class AfmController extends Controller
{
    use FileAccessTrait;

    public function index(Request $request, $section = 2023)
    {
        // $text = file_get_contents(storage_path('tabs2.txt'));
        // $codes = explode("\r\n", $text);
        // $data = [];
        // $codes = collect($codes)->map(function($item) {
        //     return (string)($item + 0);
        // })->splitIn(100);

        // foreach ($codes as $item) {
        //     AfmForm::setSection(2023)->withInfo()->whereNotNull('numar_dosar_afm')->whereNull('data_aprobare_afm')->whereIn('numar_dosar_afm', $item->toArray())->update([
        //         'data_aprobare_afm' => '2024-02-02',
        //         'stare_montaj' => 'sistem nemontat',
        //     ]);
        //     // dd(AfmForm::setSection(2023)->withInfo()->whereIn('numar_dosar_afm', $item->toArray())->pluck('numar_dosar_afm')->toArray());

        //     // $data = array_merge($data, AfmForm::setSection(2023)->withInfo()->whereIn('numar_dosar_afm', $item->toArray())->pluck('numar_dosar_afm')->toArray());
            
        // }
        // $afm = AfmForm::setSection(2023)->withInfo()->whereNull('stare_montaj')->whereNotNull('data_aprobare_afm')->update([
        //     'stare_montaj' => 'sistem nemontat'
        // ]);
        // $afm = AfmForm::setSection(2023)->withInfo()->whereNull('stare_montaj')->whereNotNull('data_aprobare_afm')->count();
        // dd('done');
        // if(auth()->id() == 413) {
        //     $sabloane = DynamicModel::table('email_template')->all();
        //     foreach ($sabloane as $sablon) {
        //         // $sablon->continut = mb_convert_encoding($sablon->continut, 'UTF-8', 'UTF-32');
        //         // $sablon->timestamps = false;
        //         // $sablon->save();
        //     }
        // }

        abort(501);

        return view('ofertare.afm.index', [
            'sectiune' => __('Tabel AFM'),
            'item' => AfmForm::setSection(2023)->withInfo()->find(7),
            'coloane' => ColoanaTabelAFM::all()->map(function ($column) use($section) {
                return $column->setCurrentSection($section);
            }),
            'types' => ColoanaTabelAFM::$types,
            // 'add_route' => route('ofertare.sabloane.create'),
            // 'can_delete' => true
        ]);
    }

    public function table(Request $request, $section = 2023)
    {
        return view('ofertare.afm.table', [
            'section' => $section,
            'sectiune' => __('Tabel AFM :section', ['section' => \Str::headline($section)]),
            'add_route' => route('ofertare.afm.create', $section),
        ]);
    }

    public function tableDescription()
    {
        return view('ofertare.afm.description', [
            'sectiune' => __('Explicatii coloane tabel AFM'),
            'coloane' => ColoanaTabelAFM::with('descriere')->get(),
            'coloane_editabile' => DescriereColoanaAFM::getEditableColumns(),
            'roluri' => Role::all(),
        ]);
    }

    public function tableDescriptionColumnUpdate(Request $request, ColoanaTabelAFM $column)
    {
        $validator = Validator::make($request->all(), DescriereColoanaAFM::rules())->stopOnFirstFailure();
        if($validator->passes() || !$request->expectsJson()) {
            $inputs = $validator->validate();
            $column->descriere()->updateOrCreate(['coloana_id' => $column->id], $inputs);
        }
        return $request->expectsJson() ? response([
            'value' => isset($inputs) && is_array($inputs) ? nl2br(array_shift($inputs)) : null,
            'errors' => $validator->errors()
        ], 200) : back()->with(['status' => __('Descriere actualizata')]);
    }

    public function create($section = 2021)
    {
        $columns = auth()->user()->coloaneSablonImplicit()
            ->whereNotNull('editare')->where('tip', '<>', '9')->whereNull('rules->relationship')
            ->get();

        return view('ofertare.afm.create', $this->commonParameters($section) + [
            'columns' => $columns,
            'types' => ColoanaTabelAFM::types(),
            'save_route' => route('ofertare.afm.store', $section),
        ]);
    }

    public function store(Request $request, $section = 2021)
    {
        $columns = auth()->user()->coloaneSablonImplicit()->whereNotNull('editare')->where('tip', '<>', '9')->get();

        $rules = [];
        $names = [];
        foreach($columns as $column) {
            if(is_null($column) || $column->nume == "id" || in_array($column->tip, [8, 9])) {
                continue;
            }

            if(isset($column->rules) && isset($column->rules['validation'])) {
                $rules[$column->nume] = $column->rules['validation'];
                $names[$column->nume] = $column->titlu;
            }
        }

        $input = array_filter($request->validate($rules, [], $names), function($var){return $var !== null;});

        // rules
        $afmForm = AfmForm::setSection($section);
        $infoTable = $afmForm::getInfoTableName();
        $coproTable = $afmForm::getCoproTableName();

        $cols_fisiere = $columns->where('tip', 7)->pluck('nume')->toArray();
        $cols = $columns->groupBy('tabel')->map(function($item) use ($cols_fisiere) {
            return $item->pluck('nume')->diff($cols_fisiere);
        })->toArray();

        $afm = $afmForm::create(
            collect($input)->only($cols[""] ?? [])->toArray() + ['vizibilitate' => 2]
        );
        $afm_info = $afmForm::table($infoTable)::create(collect($input)->only($cols["info"] ?? [])->toArray() + [
            'id_formular' => $afm->id,
        ]);

        if(isset($cols["copro"]) && isset($input['coproprietari'])) {
            $copro = explode('|', $input['coproprietari']);
            if($copro && !empty($copro)) {
                foreach($copro as $c) {
                    $c = explode(';', $c);
                    $afm_copro = $afmForm::table($coproTable)::create([
                        'id_formular' => $afm->id,
                        'nume_copro' => $c[0],
                        'prenume_copro' => $c[1],
                        'cnp_copro' => $c[2],
                        'domiciliu_copro' => $c[3],
                    ]);
                }
            }
        }

        $erori_fisiere = [];
        foreach(collect($input)->only($cols_fisiere) as $key => $file) {
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename .= '_'.time().rand(100,999);
            $filename .= '.'.$file->getClientOriginalExtension();
            $path = 'fisiere_afm/'.$section.'/'.$afm->id.'/';
            try {
                if($file->storeAs($path, $filename, 's3')) {
                    $fisier = Fisier::create([
                        'user_id' => auth()->user()->id,
                        'model_type' => 'App\\Models\\AfmForm',
                        'model_id' => $afm->id,
                        'name' => $filename,
                        'path' => $path
                    ]);
                    if(Schema::hasColumn($infoTable, $key)) {

                        $query = \DB::table($infoTable)->where($key, $fisier->model_id);
                        if($query->exists()) {
                            $old_image = $query->first()->{$key};
                            $query->update([$key => $fisier->id]);
                            $old_file = $old_image ? Fisier::find($old_image) : null;

                            if($old_file) {
                                $old_file->delete();
                            }
                        } else {
                            \DB::table($infoTable)->insert([
                                'id_formular' => $fisier->model_id,
                                $key => $fisier->id
                            ]);
                        }
                    } else {
                        $erori_fisiere[] = __('Fisierul '.$key.' nu a fost incarcat.');
                    }
                } else {
                    $erori_fisiere[] = __('Fisierul '.$key.' nu a fost incarcat.');
                }
            } catch(\Exception $e) {
                \Log::info($e);
                $erori_fisiere[] = __('Fisierul '.$key.' nu a fost incarcat.');
            }
        }
        if(!empty($erori_fisiere)) {
            return redirect()->route('ofertare.afm.create', [$section])->withInput()->withErrors($erori_fisiere);
        }

        // custom actions per column
        AfmForm::customActionsPerColumn($request, $columns->pluck('nume')->toArray(), $section, $afm->id);

        return redirect()->route('ofertare.afm.create', [$section])->with('status', __('Formularul a fost adaugat cu succes'));
    }

    public function edit(Request $request, $section = 2021, int $formular)
    {
        $formular = AfmForm::setSection($section)->withInfo()->find($formular);
        $columns = auth()->user()->coloaneSablonImplicit()
            ->where('tip', '<>', '9')->whereNull('rules->relationship')
            ->get();

        return view('ofertare.afm.create', $this->commonParameters($section) + [
            'item' => $formular,
            'columns' => $columns,
            'types' => ColoanaTabelAFM::types(),
            'save_route' => route('ofertare.afm.update', [$section, $formular->id]),
        ]);
    }

    public function update(Request $request, AfmForm $afmForm)
    {
        // return $this->save($request, $programare);
    }

    public function getColumn(Request $request, $section = 2021, int $formular, ColoanaTabelAFM $column)
    {
        $formular = AfmForm::setSection($section)->withInfo()->withFullCopro()->find($formular);
        $html = '';
        if($column->editare === 2) {
            $html = \Blade::render('<x-ofertare.columns.edit.'.$column->nume.'
                :section="$section" :column="$column" :item="$item" :form="$form" />'
            , [
                'section' => $section,
                'column' => $column,
                'item' => $formular,
                'form' => false
            ]);
        } elseif($column->editare === 1) {
            $html = \Blade::render('<x-dynamic-component :component="$component"
                :label="$label"
                :name="$name"
                :options="$options"
                :data_url="$data_url"
                :form="$form"
                :required="$required"
                :value="$value" />'
            , [
                'component' => 'ofertare.fields.edit.'.ColoanaTabelAFM::types($column->tip),
                'label' => $column->titlu,
                'name' => $column->nume,
                'options' => $column->default_values,
                'data_url' => $column->getAdvancedDataUrl($section, $formular->id),
                'required' => isset($column->rules['validation']) && in_array('required', $column->rules['validation'])
                    ? true : false,
                'value' => old($column->nume, $formular[$column->nume] ?? ''),
                'form' => false
            ]);
        }

        return response()->json(['html' => $html], 200);
    }

    public function updateColumn(Request $request, $section = 2021, int $formular, ColoanaTabelAFM $column)
    {
        $reload = false;
        $columns_edited = array_merge([$column->nume], ($column->rules['edit_with'] ?? []));
        $validator = Validator::make(
            $request->all(), 
            ColoanaTabelAFM::getColumnsRules($columns_edited) + (
                !empty($column->rules['additional_validation']) ? $column->rules['additional_validation'] : []
            )
        )->stopOnFirstFailure();
        $errors = false;

        // if column can be edited && the model instance exists && passes validation
        // && can be modified only once and doesn't have a value
        if(
            $column->editare && AfmForm::setSection($section)->where('id', $formular)->exists() && (
                !isset($column->rules['edit_once']) 
                || AfmForm::table($column->getColumnTable($section), $column->getColumnTableKey($section), $section)
                    ->where($column->getColumnTableForeignKey($section), $formular)
                    ->whereNull($column->nume)->exists()
            ) && (
                !isset($column->rules['edit_without']) 
                || AfmForm::setSection($section)->withInfo()->where('id', $formular)->whereNull($column->rules['edit_without'])->exists()
            ) && $validator->passes()
        ) {
            if($column->editare !== null) {
                // if column is a file
                if($column->isTypeFile() && $request->hasFile($column->nume)) {
                    $file = Fisier::store(
                        $request->file($column->nume), 'fisiere_afm/'.$section.'/'.$formular.'/', 
                        AfmForm::class, $formular, null, null, true
                    );
                    if(isset($file['error'])) {
                        $validator->errors()->add($column->nume, $file['error']);
                        $errors = true;
                    } else {
                        // because file inputs are protected in $request
                        // they can't be replaced directly
                        $request = new Request($request->all());
                        $request->merge([$column->nume => $file]);
                    }
                }
                if(!$errors && (!$column->isTypeFile() || $column->isTypeFile() && isset($file))) {
                    $afm_old = AfmForm::setSection($section)->withInfo()->find($formular);
                    // if column is a relationship save in model mutator
                    if(!empty($column->rules['relationship'])) {
                        $afm_info = $afm_old->forceFill(collect($request->all())->only($columns_edited ?? [])->toArray());
                    } else {
                        $afm_info = AfmForm::table($column->getColumnTable($section), $column->getColumnTableKey($section), $section)->updateOrCreate([
                            $column->getColumnTableForeignKey($section) => $formular,
                        ], collect($request->all())->only($columns_edited ?? [])->toArray());
                    }

                    // if column is a file, delete the old file
                    if(isset($file) && !empty($afm_info[$column->nume]) && $afm_info[$column->nume] !== $afm_old[$column->nume]) {
                        $old_file = Fisier::find($afm_old[$column->nume]);
                        $old_file ? $old_file->delete() : null;
                    }

                    // custom actions per column
                    AfmForm::customActionsPerColumn($request, $columns_edited, $section, $formular, $afm_old ?? null);

                    // return new values
                    $value = $request->input($column->nume);

                    $afm_new = AfmForm::setSection($section)->withInfo()->find($formular);
                    if($column->afisare === null && isset($column->rules['db'])) {
                        $afm_new['nume_'.$column->nume] = $this->getColumnDbOptions($request, $column, $section, null, $afm_new[$column->nume]);
                    }
                    $label = \Blade::render('<x-dynamic-component :component="\''.($column->afisare == 1 
                        ? 'ofertare.columns.view.'.$column->nume 
                        : 'ofertare.fields.view.'.$column->getType()
                    ).'\'"
                        :item="$item"
                        :column="$column"
                    />', [
                        'item' => $afm_new,
                        'column' => $column
                    ]);

                    $reload = isset($column->rules['edit_once']) 
                        || isset($column->rules['edit_with']) 
                        || isset($column->rules['edit_without']);
                }
            }
        }
        return response([
            'value' => isset($value) && is_array($value) ? 'Deschide' : $value ?? null,
            'label' => $label ?? null,
            'reload' => $reload,
            'errors' => $validator->errors()
        ], 200);
    }

    public function getColumnDbOptions(Request $request, ColoanaTabelAFM $column, $section = '2021', $formular = null, $value = '-1')
    {
        $column->setCurrentSection($section);
        if($formular) {
            $afm = AfmForm::setSection($section)->withInfo()->where('id', $formular)->first();
        }
        if(isset($column->rules) && isset($column->rules['db'])) {

            $cols = ['id', \DB::raw('CONCAT('.implode(",' ',", $column->rules['db']['cols']).') as text')];

            $items = isset($column->rules['db']['model'])
                ? app($column->rules['db']['model'])->query()
                : \DB::table($column->rules['db']['table']);
            $items->select($cols);

            if(isset($column->rules['db']['where'])) {
                $where = json_encode($column->rules['db']['where']);
                $where = str_replace([
                    ':section',
                    ':judet_domiciliu',
                    ':judet_imobil',
                ], [
                    $section,
                    !empty($afm) ? $afm->judet_domiciliu ?? '' : '',
                    !empty($afm) ? $afm->judet_imobil ?? '' : '',
                ], $where);

                $items->where(json_decode($where, true));
                // $items->where($column->rules['db']['where']);
            }
            if(isset($column->rules['db']['roles'])) {
                $items->role($column->rules['db']['roles']);
            }
            if($request->input('q')){
                $items->where(\DB::raw('CONCAT('.implode(",' ',", $column->rules['db']['cols']).')'), 'like', $request->input('q').'%');
            }
            // -1 when value is null and needs to return null instead of array
            if($value !== '-1') {
                $item = $items->where('id', $value)->first();
                return $item->text ?? null; 
            }

            $items = $items->pluck('text','id')->toJson();

            return $items;
        }
        return $value ? null : [];
    }

    public function generateDocument($section = 2021, $formular, $slug, $key = null, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $formular)->withInfo()->first();
        if($afm) {
            return SablonDocument::generateDocument($slug, $key, $this->dynamicDataForDocuments($afm), '__', $download);
        }
        return abort(404);
    }

    public function generatePvPredarePrimire($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();
        /**
         *  select(
            'id','id_formular','siruri_panouri','stare_montaj','tipul_invelitorii', 'numar_sp-uri',
            'judet_domiciliu', 'localitate_domiciliu', 'judet_imobil', 'localitate_imobil', 'firma_instalatoare',
            'putere_invertor', 'marca_invertor', 'tipul_bransamentului'
        )->
        */

        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;

        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $siruri = collect([$afm])->groupBy([
            function ($item, $key) {
                $panouri = is_array($item['siruri_panouri'])
                    ? $item['siruri_panouri']
                    : json_decode($item['siruri_panouri'], true);
                return $panouri;
            },
            function ($item, $key) {
                return $item['tipul_invelitorii'] == 'tigla' ? 'tigla' : 'non_tigla';
            }
        ])->sortKeys();

        $total = [
            'Sina R1 - 2.2m' => 0,
            'Sina R1 - 3.3m' => 0,
            'Sina de imbinare RS' => 0,
            'MRH-01 - TABLA' => 0,
            'TRH-01A - TIGLA' => 0,
            'Clema capat EC-01' => 0,
            'Clema de mijloc  MC-01' => 0
        ];

        foreach($siruri as $sir => $invelitori) {
            $nr = (int)str_replace('1x', '', $sir);
            $count_tigla = count($invelitori['tigla'] ?? []);
            $count_non_tigla = count($invelitori['non_tigla'] ?? []);
            $cant = $count_tigla + $count_non_tigla;
            // $total['cant'] += $cant;

            $total['Sina R1 - 2.2m'] += $cant * ($nr % 3);
            $total['Sina R1 - 3.3m'] += $cant * (intval($nr / 3) * 2);
            $total['Sina de imbinare RS'] += $cant * (intval(($nr - 1) / 3) * 2);
            $total['MRH-01 - TABLA'] += $count_non_tigla * ($nr * 2 + 4);
            $total['TRH-01A - TIGLA'] += $count_tigla * ($nr * 2 + 4);
            $total['Clema capat EC-01'] += $cant * 4;
            $total['Clema de mijloc  MC-01'] += $cant * (($nr - 1) * 2);
        }

        // $data = $afm->toArray() + [
        //     'numar_sp_uri' => $afm->{'numar_sp-uri'},
        //     'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
        //     'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
        //     'nume_judet_imobil' => $judetI ? $judetI->nume : '',
        //     'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
        //     'fi' => $fi,
        //     'base64_stamp_img' => 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : ''),
        //     'invertor' => Invertor::where('marca', $afm->marca_invertor)
        //         ->where('putere', $afm->putere_invertor)
        //         ->where('tip', $afm->tipul_bransamentului)
        //         ->first(),
        //     'marca_panouri' => $afm->putere_panouri == 430 ? 'TRINA' : 'JA SOLAR',
        //     'total' => $total
        // ];

        $invertor = Invertor::where('marca', $afm->marca_invertor)
            ->where('putere', $afm->putere_invertor)
            ->where('tip', $afm->tipul_bransamentului)
            ->first();

        $data = SablonDocument::getFormatedData(8, [
            'data_contract_instalare' => $afm->data_contract_instalare ? date("d.m.Y", strtotime($afm->data_contract_instalare)) : '',
            'bloc_domiciliu' => isset($afm->bloc_domiciliu) && !empty($afm->bloc_domiciliu) ? "bloc ".$afm->bloc_domiciliu.", " : "",
            'scara_domiciliu' =>  isset($afm->scara_domiciliu) && !empty($afm->scara_domiciliu) ? "scara ".$afm->scara_domiciliu.", " : "",
            'et_domiciliu' => isset($afm->et_domiciliu) && !empty($afm->et_domiciliu) ? "et. ".$afm->et_domiciliu.", " : "",
            'ap_domiciliu' =>  isset($afm->ap_domiciliu) && !empty($afm->ap_domiciliu) ? "ap. ".$afm->ap_domiciliu.", " : "",
            'putere_invertor' => $invertor['putere'] ?? 0 + 0,
            'tip_invertor' => $invertor['tip'] ?? '',
            'cod_invertor'=> $invertor['cod'] ?? '',
            'contor_inventor'=> $invertor['contor'] ?? '',
            'numar_sp_uri' => $afm->{'numar_sp-uri'},
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
            'marca_panouri' => $afm->putere_panouri == 430 ? 'TRINA' : 'JA SOLAR',
            'total' => $total,
            'tabel_echipamente' => \Blade::render(
                '<x-documente.tabel-echipamente-pv-predare-primire
                    :invertor="$invertor"
                    :numar_sp_uri="$numar_sp_uri"
                    :numar_panouri="$numar_panouri"
                    :marca_panouri="$marca_panouri"
                    :putere_panouri="$putere_panouri"
                    :total="$total"
                />',
                [
                    "invertor" => $invertor,
                    "numar_sp_uri" => $afm->{'numar_sp-uri'},
                    "numar_panouri" => $afm->numar_panouri,
                    "marca_panouri" => $afm->marca_panouri,
                    "putere_panouri" => $afm->putere_panouri,
                    "total" => $total,
                ]
            ),
        ]   + $afm->toArray()
            + affix_array_keys($fi ? $fi->toArray() : [], '_firma')
            + affix_array_keys($invertor ? $invertor->toArray() : [], '_invertor')
        );

        $pdf = \PDF::loadView('sablon-pdf', $data);

        // $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.pv_predare_primire', $data);

        if (!$download) {
            return $pdf->stream('Pv_predare_primire.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download('Pv_predare_primire.pdf');
        }
    }

    public function generateNotificareRacordare($section = 2021, $afm, $distribuitor = null, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $sir = is_array($afm->siruri_panouri) ? $afm->siruri_panouri : json_decode($afm->siruri_panouri, true);
        // if(auth()->id() == '413') {
        //     $data = [
        //         'numar_sp_uri' => $afm->{'numar_sp-uri'},
        //         'total_putere_panouri' => $afm->total_putere_panouri,
        //         'min_putere' => $afm->total_putere_panouri < $afm->putere_invertor
        //             ? $afm->total_putere_panouri
        //             : $afm->putere_invertor,
        //         'judet_domiciliu' => $judetD != null ? $judetD->nume : '',
        //         'localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
        //         'judet_imobil' => $judetI ? $judetI->nume : '',
        //         'localitate_imobil' => $localitateI ? $localitateI->nume : '',
        //         'ca_invertor' => $afm->tipul_bransamentului == 'monofazat' ? 230 : 380,
        //         'distribuitor_energie' => $distribuitor,
        //         'fi' => $fi,
        //         'siruri_panouri' => is_array($sir) ? implode(' x ', $sir) : $sir,
        //         'base64_stamp_img' => 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : ''),
        //         'invertor' => Invertor::where('marca', $afm->marca_invertor)
        //             ->where('putere', $afm->putere_invertor)
        //             ->where('tip', $afm->tipul_bransamentului)
        //             ->first(),
        //     ] + $afm->toArray();


        //     $file = 'DEER';
        //     if(in_array($data['distribuitor_energie'], ColoanaTabelAFM::coloana('anexa_2_distribuitor_energie')->default_values ?? [])) {
        //         $file = $data['distribuitor_energie'];
        //     }
        //     if($data['distribuitor_energie'] == 'Imputernicire') {
        //         $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.notificari-racordare.imputernicire', $data);
        //     } else {
        //         $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.notificari-racordare.notificare_racordare_'.$file, $data);
        //     }
        //     $dompdf = $pdf->getDomPDF();
        //     $dompdf->set_option("isPhpEnabled", true);

        //     if($file == 'E-distributie') {

        //         // $dompdf->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        //         //     // add image in the header after third page
        //         //     if ($pageNumber > 3) {
        //         //         $image = route('images', 'e-distributie.png');
        //         //         $width = 150;
        //         //         $height = 35;
        //         //         $x = 35;
        //         //         $y = 45;
        //         //         $canvas->image($image, $x, $y, $width, $height);
        //         //     }
        //         // });
        //     }
        //     return $pdf->stream('Notificare_racordare_'.$afm->id.'.pdf')->header('Content-Type','application/pdf');
        // }

        // if(auth()->id() == 413 || auth()->id() == 8950) {

        switch ($distribuitor) {
            case 'DEO':
                $id_sablon = 11;
                break;
            case 'DEER':
                $id_sablon = 13;
                break;
            case 'E-distributie':
                $id_sablon = 14;
                break;
            case 'Delgaz':
                $id_sablon = 15;
                break;
            case 'Imputernicire':
                $id_sablon = 16;
                break;
            default:
                $id_sablon = 16;
                break;
        }

        $data = SablonDocument::getFormatedData($id_sablon, [
            'numar_sp_uri' => $afm->{'numar_sp-uri'},
            'nr_autorizare'=> $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '................',
            'nume_firma_fi'=> $fi ? $fi->nume_firma : '...................',
            'putere_panouri_kw'=> $afm->putere_panouri / 1000,
            'data_curenta' => date('d.m.Y'),
            'total_putere_panouri' => $afm->total_putere_panouri,
            'nr_autorizare_2018'=> $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '13692/28.09.2018',
            'cod_invertor'=> $afm->invertor->cod ?? '',
            'min_putere' => $afm->total_putere_panouri < $afm->putere_invertor
                    ? $afm->total_putere_panouri
                    : $afm->putere_invertor,
            'judet_domiciliu' => $judetD != null ? $judetD->nume : '',
            'localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'judet_imobil' => $judetI ? $judetI->nume : '',
            'localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'ca_invertor' => $afm->tipul_bransamentului == 'monofazat' ? 230 : 380,
            'distribuitor_energie' => $distribuitor,
            'fi' => $fi,
            'siruri_panouri' => is_array($sir) ? implode(' x ', $sir) : $sir,
            'stampila_firma' => '<img src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
            'tabel_generatoare_asincrone' => \Blade::render('<x-documente.tabel-generatoare-asincrone-si-sincrone-notificare-racordare-e-distributie />'),
            'invertor' => Invertor::where('marca', $afm->marca_invertor)
                ->where('putere', $afm->putere_invertor)
                ->where('tip', $afm->tipul_bransamentului)
                ->first(),
        ] + $afm->toArray()
            + affix_array_keys($fi ? $fi->toArray() : [], '_firma')
        );

        $pdf = \PDF::loadView('sablon-pdf', $data);
        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option("isPhpEnabled", true);
            // return $pdf->stream('Notificare_racordare_'.$afm->id.'.pdf')->header('Content-Type','application/pdf');
        // }


         // $dompdf = $pdf->getDomPDF();
         // $dompdf->set_option("isPhpEnabled", true);
         // return $pdf->stream('Notificare_racordare_'.$afm->id.'.pdf')->header('Content-Type','application/pdf');


        if (!$download) {
            return $pdf->stream('Notificare_racordare_'.$afm->id.'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download('Notificare_racordare'.$afm->id.'.pdf');
        }
    }

    public function generateMonofilara($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();


        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $numar_fisier = $afm->putere_invertor >= 10 ? '10' : $afm->putere_invertor;
        $numar_fisier = $afm->putere_invertor == 8.2 ? '8' : $afm->putere_invertor;
        $numar_fisier = $afm->putere_invertor == 4 ? '5' : $afm->putere_invertor;

        $nume_pdf = 'Monofilara_'.($numar_fisier ?? '').'kW_'.($afm->tipul_bransamentului ?? '').'.pdf';

        $base64_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/monofilare/monofilara_'.($numar_fisier ?? '').'kW_'.($afm->tipul_bransamentului ?? '').'.png', 2));

        $base64_logo_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));
        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');

        $clasificari_sigurante = [1, 6, 10, 16, 20, 25, 32, 40, 50, 63];
        $val_inferioara_siguranta = $clasificari_sigurante[0];
        if($afm->capacitate_disjunctor) {
            foreach($clasificari_sigurante as $clasificare) {
                $val_inferioara_siguranta = $afm->capacitate_disjunctor > $clasificare
                    ? $clasificare : $val_inferioara_siguranta;
            }
        }

        $default_settings = [
            'monofazat' => [
                '3' => ['siguranta' => 16, 'sectiune_cablu' => 2.5],
                '4' => ['siguranta' => 20, 'sectiune_cablu' => 4],
                '5' => ['siguranta' => 25, 'sectiune_cablu' => 4],
                '6' => ['siguranta' => 32, 'sectiune_cablu' => 4],
                '7' => ['siguranta' => 32, 'sectiune_cablu' => 6],
                '8' => ['siguranta' => 40, 'sectiune_cablu' => 6],
            ],
            'trifazat' => [
                '3' => ['siguranta' => 16, 'sectiune_cablu' => 2.5],
                '4' => ['siguranta' => 16, 'sectiune_cablu' => 2.5],
                '5' => ['siguranta' => 16, 'sectiune_cablu' => 2.5],
                '6' => ['siguranta' => 16, 'sectiune_cablu' => 4],
                '7' => ['siguranta' => 20, 'sectiune_cablu' => 4],
                '8' => ['siguranta' => 25, 'sectiune_cablu' => 6],
                '10' => ['siguranta' => 32, 'sectiune_cablu' => 6],
                '15' => ['siguranta' => 40, 'sectiune_cablu' => 6],
                '20' => ['siguranta' => 40, 'sectiune_cablu' => 6],
            ]
        ];
        $setari = $default_settings[$afm->tipul_bransamentului][$numar_fisier];

        $data = $afm->toArray() + [
            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'base64_img' => $base64_img,
            'base64_logo_img' => $base64_logo_img,
            'base64_stamp_img' => $base64_stamp_img,
            'fi' => $fi,
            'setari' => $setari,
            'val_inferioara_siguranta' => $afm->capacitate_disjunctor == $setari['siguranta']
                ? $afm->capacitate_disjunctor
                : $val_inferioara_siguranta,
            'tip_curba' => $afm->capacitate_disjunctor == $setari['siguranta'] ? 'B' : 'C',
            'invertor' => $afm->invertor,
            // 'invertor' => Invertor::where('marca', $afm->marca_invertor)
            //     ->where('putere', $afm->putere_invertor)
            //     ->where('tip', $afm->tipul_bransamentului)
            //     ->first(),
        ];
        // ini_set( 'memory_limit', '64M');
        // ini_set( 'max_execution_time', '120');

        $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.monofilara', $data)->setPaper('a4', 'landscape');
        if (!$download)
        {
            return $pdf->stream($nume_pdf)->header('Content-Type','application/pdf');
        }
        else
        {
            return $pdf->download($nume_pdf);
        }
    }

    public function generateProcesVerbalPIF($section = 2021, $afm, $distribuitor = null, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;
        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $base64_logo_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));
        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');

        $data = [
            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'adresa_domiciliu' => $afm ? $afm->adresa_domiciliu : '',
            'max_putere' => $afm->total_putere_panouri > $afm->putere_invertor
                ? $afm->total_putere_panouri
                : $afm->putere_invertor,
            'distribuitor_energie' => $distribuitor,
            'base64_logo_img' => $base64_logo_img,
            'base64_stamp_img' => $base64_stamp_img,
            'fi' => $fi,
            'invertor' => Invertor::where('marca', $afm->marca_invertor)
                ->where('putere', $afm->putere_invertor)
                ->where('tip', $afm->tipul_bransamentului)
                ->first(),
        ] + $afm->toArray();

        $file = 'DEER';

        if(in_array($data['distribuitor_energie'], ColoanaTabelAFM::coloana('pif_distribuitor')->default_values ?? [])) {
            $file = $data['distribuitor_energie'];
        }
        // if(auth()->id() == '413') {
        //     $file = 'Delgaz';
        //     // $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.procese-verbale.proces_verbal_'.$file, $data);
        //     // return view('ofertare.afm.sabloane-pdf.procese-verbale.proces_verbal_'.$file, $data);
        // }


        // if(auth()->id() == 413 || auth()->id() == 8950) {

        switch ($distribuitor) {
            case 'DEO':
                $id_sablon = 18;
                break;
            case 'DEER':
                $id_sablon = 19;
                break;
            case 'DEER_TN':
                $id_sablon = 20;
                break;
            case 'DEER_GL':
                $id_sablon = 21;
                break;
            case 'E-distributie':
                $id_sablon = 22;
                break;
            case 'Delgaz':
                $id_sablon = 23;
                break;
            default:
                $id_sablon = 23;
                break;
        }

        $invertor = Invertor::where('marca', $afm->marca_invertor)
            ->where('putere', $afm->putere_invertor)
            ->where('tip', $afm->tipul_bransamentului)
            ->first();

        $data = SablonDocument::getFormatedData($id_sablon, [
            'data_contract_instalare' => $afm->data_contract_instalare ? date("d.m.Y", strtotime($afm->data_contract_instalare)) : '',
            'bloc_domiciliu' => isset($afm->bloc_domiciliu) && !empty($afm->bloc_domiciliu) ? "bloc ".$afm->bloc_domiciliu.", " : "",
            'scara_domiciliu' => isset($afm->scara_domiciliu) && !empty($afm->scara_domiciliu) ? "scara ".$afm->scara_domiciliu.", " : "",
            'et_domiciliu' => isset($afm->et_domiciliu) && !empty($afm->et_domiciliu) ? "et. ".$afm->et_domiciliu.", " : "",
            'ap_domiciliu' => isset($afm->ap_domiciliu) && !empty($afm->ap_domiciliu) ? "ap. ".$afm->ap_domiciliu.", " : "",
            'tip_invertor' => $invertor['tip'] ?? '',
            'cod_invertor'=> $invertor['cod'] ?? '',
            'contor_inventor'=> $invertor['contor'] ?? '',
            'nr_cerere_pif_default'=> $afm->nr_cerere_pif ?? '........',
            'cod_loc_consum_default'=> $afm->cod_loc_consum ?? '................',
            'pod_default'=> $afm->pod ?? '........',
            'reg_com_firma_fi'=> $afm->firma->reg_com_firma ?? 'J40/4862/23.04.2015',
            'cod_fiscal_firma_fi'=> $afm->firma->cod_fiscal_firma ?? 'RO21241885',
            'nr_autorizare_firma_fi'=> $afm->firma->nr_autorizare ?? '13692/28.09.2018',
            'nr_autorizare'=> $afm->firma->nr_autorizare ?? '...................',
            'data_curenta' => date('d.m.Y'),
            'adresa_domiciliu' => $afm ? $afm->adresa_domiciliu : '',
            'nr_autorizare_default'=> $afm->firma->nr_autorizare ?? '13692/28.09.2018',
            'data_valabilitate_autorizare_default' => $afm->firma->data_valabilitate_autorizare ?? '28.09.2023',
            'nume_firma_fi_default' => $afm->firma->nume_firma ?? 'S.C. ELECTRO SERVICE DISTRIBUTIE S.R.L.',
            'nume_firma_fi' => $afm->firma->nume_firma ?? '...................',
            'max_putere' => $afm->total_putere_panouri > $afm->putere_invertor
                ? $afm->total_putere_panouri
                : $afm->putere_invertor,
            'numar_sp_uri' => $afm->{'numar_sp-uri'},
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'stampila_firma' => '<img class="stampila" class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
            'marca_panouri' => $afm->putere_panouri == 430 ? 'TRINA' : 'JA SOLAR',
        ]   + $afm->toArray()
            + affix_array_keys($fi ? $fi->toArray() : [], '_firma')
            + affix_array_keys($invertor ? $invertor->toArray() : [], '_invertor')
        );

        $pdf = \PDF::loadView('sablon-pdf', $data);
        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option("isPhpEnabled", true);

        // } else {
        //     $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.procese-verbale.proces_verbal_'.$file, $data);
        //     $dompdf = $pdf->getDomPDF();
        //     $dompdf->set_option("isPhpEnabled", true);
        // }

        // $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.procese-verbale.proces_verbal_'.$file, $data);
        // $dompdf = $pdf->getDomPDF();
        // $dompdf->set_option("isPhpEnabled", true);

        if (!$download) {
            return $pdf->stream('Proces verbal PIF '.$file.'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download('Proces verbal PIF '.$file.'.pdf');
        }
    }

    public function generateDosarReglajePram($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $base64_logo_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));
        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');

        // $data = [
        //     'nume_judet_imobil' => $judetI ? $judetI->nume : '',
        //     'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
        //     'base64_logo_img' => $base64_logo_img,
        //     'base64_stamp_img' => $base64_stamp_img,
        //     'fi' => $fi,
        //     'min_putere' => $afm->total_putere_panouri < $afm->putere_invertor
        //         ? $afm->total_putere_panouri
        //         : $afm->putere_invertor,
        //     'invertor' => Invertor::where('marca', $afm->marca_invertor)
        //         ->where('putere', $afm->putere_invertor)
        //         ->where('tip', $afm->tipul_bransamentului)
        //         ->first(),
        // ] + $afm->toArray();


        // if(auth()->id() == 413 || auth()->id() == 8950) {

        $data = SablonDocument::getFormatedData(17, [
            'numar_sp_uri' => $afm->{'numar_sp-uri'},
            'judet_imobil' => $judetI ? $judetI->nume : '',
            'localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'date_plus_1year'=> date('d.m.Y', strtotime("+1 Year")),
            'tipul_bransamentului'=> $afm->tipul_bransamentului == 'monofazat' ? 230 : 380,
            'nr_autorizare'=> $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '................',
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'putere_panouri_kw'=> $afm->putere_panouri / 1000,
            'data_curenta' => date('d.m.Y'),
            'total_putere_panouri' => $afm->total_putere_panouri,
            'nr_autorizare_2018'=> $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '13692/28.09.2018',
            'cod_invertor'=> $afm->invertor->cod ?? '',
            'min_putere' => $afm->total_putere_panouri < $afm->putere_invertor
                ? $afm->total_putere_panouri
                : $afm->putere_invertor,
            'ca_invertor' => $afm->tipul_bransamentului == 'monofazat' ? 230 : 380,
            'fi' => $fi,
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
            'invertor' => Invertor::where('marca', $afm->marca_invertor)
                ->where('putere', $afm->putere_invertor)
                ->where('tip', $afm->tipul_bransamentului)
                ->first(),
        ] + $afm->toArray()
            + affix_array_keys($fi ? $fi->toArray() : [], '_firma')
        );

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // } else {
        //      $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.dosar_reglaje_pram', $data);
        // }

        // $pdf = \PDF::loadView('ofertare.afm.sabloane-pdf.dosar_reglaje_pram', $data);

        if (!$download) {
            return $pdf->stream('Dosar, reglaje, pram.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download('Dosar, reglaje, pram.pdf');
        }
    }

    public function generateFisaVizita($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        // $fi = $afm->firma;

        $base64_logo_img = base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));

        $data = SablonDocument::getFormatedData(25, [
            'data_curenta' => date('d.m.Y'),
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'logo' => '<img src="data:image/png;base64,'.$base64_logo_img.'" style="width:150px;height:auto;" />',
        ] + $afm->toArray() + affix_array_keys($afm->invertor ? $afm->invertor->toArray() : [], '_invertor'));

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }

    public function generateContractInstalare($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $fi = $afm->firma;

        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');
        $base64_logo_img = base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));
        $data = SablonDocument::getFormatedData(1, [
            'data_curenta' => date('d.m.Y'),
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'logo' => '<img src="data:image/png;base64,'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'fi' => $fi,
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'cont_firma'=> $fi->cont_firma ?? 'RO72 BACX 0000 0009 3487 6000',
            'banca_firma'=> $fi->banca_firma ?? 'Unicredit Bank, Sucursala Râmnicu Vâlcea',
            'total_putere_panouri' => $afm->total_putere_panouri ?? '',
            'valoare_contract' => $afm->valoare_contract ?? '',
            'descriere_componenta' => $afm->descriere_componenta ?? '',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
        ] + $afm->toArray() + affix_array_keys($afm->invertor ? $afm->invertor->toArray() : [], '_invertor'));

            // dd($afm);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }
    
    public function generateActAditionalContractInstalare($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');
        $base64_logo_img = base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));

        $data = SablonDocument::getFormatedData(5, [
            'data_curenta' => date('d.m.Y'),
            'data_contract_instalare' =>  date("d.m.Y", strtotime($afm->data_contract_instalare)),

            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'logo' => '<img src="data:image/png;base64,'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'fi' => $fi,
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'cont_firma'=> $fi->cont_firma ?? 'RO72 BACX 0000 0009 3487 6000',
            'banca_firma'=> $fi->banca_firma ?? 'Unicredit Bank, Sucursala Râmnicu Vâlcea',
            'total_putere_panouri' => $afm->total_putere_panouri ?? '',
            'valoare_contract' => $afm->valoare_contract ?? '',
            'descriere_componenta' => $afm->descriere_componenta ?? '',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
        ] + $afm->toArray() + affix_array_keys($afm->invertor ? $afm->invertor->toArray() : [], '_invertor'));

            // dd($afm);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }


    public function generateActAditionalUpgrade($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');
        $base64_logo_img = base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));

        $data = SablonDocument::getFormatedData(10, [
            'data_curenta' => date('d.m.Y'),
            'data_contract_instalare' =>  date("d.m.Y", strtotime($afm->data_contract_instalare)),

            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'logo' => '<img src="data:image/png;base64,'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'fi' => $fi,
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'cont_firma'=> $fi->cont_firma ?? 'RO72 BACX 0000 0009 3487 6000',
            'banca_firma'=> $fi->banca_firma ?? 'Unicredit Bank, Sucursala Râmnicu Vâlcea',
            'total_putere_panouri' => $afm->total_putere_panouri ?? '',
            'valoare_contract' => $afm->valoare_contract ?? '',
            'descriere_componenta' => $afm->descriere_componenta ?? '',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
        ] + $afm->toArray() + affix_array_keys($afm->invertor ? $afm->invertor->toArray() : [], '_invertor'));

            // dd($afm);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }

    public function generateAnexaFactura($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;
        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');
        $base64_logo_img = base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));

        $invertor = $afm->invertor;
        $invertor = $invertor ? $invertor->toArray() : [];
        // if(empty($invertor)) {
        //     return back()->withErrors(['error' => __('Nu a fost gasit un invertor pentru acest formular.')]);
        // }
        $invertor['putere'] = $afm['putere_invertor'];
        $invertor['serie'] = $afm['serie_invertor'];
        $invertor['nr_buc'] = 1;
        switch ($invertor['putere'] + 0) {
            case 3:
                $invertor['pret_unitar'] = 5669;
                break;
            case 5:
                $invertor['pret_unitar'] = 7662;
                break;
            case 6:
                $invertor['pret_unitar'] = 7830;
                break;
            case 8.2:
                $invertor['pret_unitar'] = 10747;
                break;
            case 10:
                $invertor['pret_unitar'] = 10747;
                break;
            case 15:
                $invertor['pret_unitar'] = 12768;
                break;
            case 20:
                $invertor['pret_unitar'] = 12768;
                break;
            default:
                $invertor['pret_unitar'] = 5669;
                break;
        }
        $invertor['val_total'] = $invertor['pret_unitar'] * $invertor['nr_buc'];
        $invertor['val_tva'] = round($invertor['val_total'] * 0.05, 2);

        // panouri
        $panouri = $afm->panou;
        $panouri = $panouri ? $panouri->toArray() : [];
        // if(empty($panouri)) {
        //     return back()->withErrors(['error' => __('Nu a fost gasit tipul de panou pentru acest formular.')]);
        // }
		$panouri['serie'] = str_replace(', ', ',<br>', $afm['serie_panouri']);
		$panouri['nr_buc'] = $afm['numar_panouri'];
		$panouri['putere'] = $afm['putere_panouri'];
		$panouri['pret_unitar'] = 1100;
		$panouri['val_total'] = $panouri['pret_unitar'] * $panouri['nr_buc'];
		$panouri['val_tva'] = round($panouri['val_total'] * 0.05, 2);

		switch ($panouri['putere']) {
			case '385':
				$panouri['cod'] = 'JA SOLAR JAM60S20-385/MR';
				break;
			case '405':
				$panouri['cod'] = 'JA SOLAR JAM54S30-405/MR';
				break;
			case '410':
				$panouri['cod'] = 'JA SOLAR JAM54S30-410/MR';
				break;
			case '430':
				$panouri['cod'] = 'TRINA SOLAR 430Wp';
				break;
			case '460':
				$panouri['cod'] = 'JA SOLAR JAM72S20-460/MR';
				break;
			case '545':
				$panouri['cod'] = 'JA SOLAR JAM72S30-545/MR';
				break;
			default:
				$panouri['cod'] = 'JA SOLAR JAM60S20-385/MR';
				break;
		}
    
        $contor['cod'] = $invertor['contor'] ?? '';
		$contor['serie'] = $afm['serie_contor'];
		$contor['nr_buc'] = 1;
		$contor['pret_unitar'] = 787.11;
		$contor['val_total'] = $contor['pret_unitar'] * $contor['nr_buc'];
		$contor['val_tva'] = round($contor['val_total'] * 0.05, 2);

        // tablou_electric
		$tablou_electric['nr_buc'] = 1;
		$tablou_electric['pret_unitar'] = 1060;
		$tablou_electric['val_total'] = $tablou_electric['pret_unitar'] * $tablou_electric['nr_buc'];
		$tablou_electric['val_tva'] = round($tablou_electric['val_total'] * 0.05, 2);

		// cabluri_accesorii
		$cabluri_accesorii['nr_buc'] = 1;
		$cabluri_accesorii['pret_unitar'] = round((
			$invertor['val_total'] 
			+ $panouri['val_total'] 
			+ $contor['val_total'] 
			+ $tablou_electric['val_total']
		) * 0.05, 2);
		$cabluri_accesorii['val_total'] = $cabluri_accesorii['pret_unitar'] * $cabluri_accesorii['nr_buc'];
		$cabluri_accesorii['val_tva'] = round($cabluri_accesorii['val_total'] * 0.05, 2);

		$spp['nr_buc'] = 1;
		// Formula de calcul: Valoare contract – (12% * Valoare contract) – Valoare totala invertor – Valoare totala panouri – Valoare totala Contor – Valoare totala tablou electric
		$spp['pret_unitar'] = $afm['valoare_contract'] 
			- ($afm['valoare_contract'] * 0.12) 
			- $invertor['val_total'] 
			- $panouri['val_total'] 
			- $contor['val_total'] 
			- $tablou_electric['val_total']
			- $cabluri_accesorii['val_total'];
		$spp['val_total'] = $spp['pret_unitar'] * $spp['nr_buc'];
		$spp['val_tva'] = round($spp['val_total'] * 0.05, 2);

		$afm['total_fara_tva'] = $invertor['val_total'] 
			+ $panouri['val_total'] 
			+ $contor['val_total'] 
			+ $tablou_electric['val_total']
			+ $cabluri_accesorii['val_total']
			+ $spp['val_total'];

		$afm['total_tva'] = $invertor['val_tva'] 
			+ $panouri['val_tva'] 
			+ $contor['val_tva'] 
			+ $tablou_electric['val_tva']
			+ $cabluri_accesorii['val_tva']
			+ $spp['val_tva'];

		$afm['total'] = $afm['total_fara_tva'] + $afm['total_tva'];

        $data = SablonDocument::getFormatedData(7, [
            'serie_factura' => $afm->serie_factura_fiscala ?? '',
            'nr_factura' => $afm->nr_factura_finala ?? '',
            'data_factura' => date("d.m.Y", strtotime($afm->data_factura_finala)),
            'logo' => '<img src="data:image/png;base64,'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'fi' => $fi,
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'total_putere_panouri' => $afm->total_putere_panouri ?? '',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
            // 'cod_panouri' => $afm->putere_panouri == 430 ? 'TRINA' : 'JA SOLAR',
            'nr_buc_panouri'=> $afm->numar_panouri ?? '..................',
            
            
        ] + $afm->toArray() + affix_array_keys($invertor ? $invertor : [], '_invertor') 
        + affix_array_keys($panouri ? $panouri : [], '_panouri') 
        + affix_array_keys($spp ? $spp : [], '_spp')
        + affix_array_keys($contor ? $contor : [], '_contor')
        + affix_array_keys($cabluri_accesorii ? $cabluri_accesorii : [], '_cabluri_accesorii')
        + affix_array_keys($tablou_electric ? $tablou_electric : [], '_tablou_electric'));

            // dd($afm);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }

    public function generatePvReceptie($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;
        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');
        $base64_logo_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));
        
        $panouri = $afm->panou;
        $panouri['noct_grade'] = '45';
		switch ($afm['putere_panouri']) {
			case '385':
				$panouri['cod'] = 'JA SOLAR JAM60S20-385/MR';
				$panouri['eficienta'] = '20,60';
				$panouri['tensiune'] = '41,78';
				break;
			case '405':
				$panouri['cod'] = 'JA SOLAR JAM54S30-405/MR';
				$panouri['eficienta'] = '20,70';
				$panouri['tensiune'] = '31,21';
				break;
			case '410':
				$panouri['cod'] = 'JA SOLAR JAM54S30-410/MR';
				$panouri['eficienta'] = '21,50';
				$panouri['tensiune'] = '31,21';
				break;
			case '430':
				$panouri['cod'] = 'TRINA SOLAR 430Wp';
				$panouri['eficienta'] = '21,50';
				$panouri['tensiune'] = '42,30';
				$panouri['noct_grade'] = '43';
				break;
			case '460':
				$panouri['cod'] = 'JA SOLAR JAM72S20-460/MR';
				$panouri['eficienta'] = '21,50';
				$panouri['tensiune'] = '31,21';
				break;
			case '545':
				$panouri['cod'] = 'JA SOLAR JAM72S30-545/MR';
				$panouri['eficienta'] = '21,10';
				$panouri['tensiune'] = '41,80';
				break;
			default:
				$panouri['cod'] = 'JA SOLAR JAM60S20-385/MR';
				$panouri['eficienta'] = '20,60';
				$panouri['tensiune'] = '41,78';
				break;
		}
        
		$afm['panouri'] = $panouri;

        $data = SablonDocument::getFormatedData(9, [
            'data_curenta' => date('d.m.Y'),
            'data_contract_instalare' =>  date("d.m.Y", strtotime($afm->data_contract_instalare)),
            'noct_grade'=>$panouri['noct_grade'],
            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'nr_contract_firma'=> $fi->nr_contract ?? '',
            'data_pv_receptie'=> date("d.m.Y", strtotime($afm->data_pv_receptie)) ?? '',
            'numar_dosar_afm'=> $afm->numar_dosar_afm ?? '',
            'data_semnare_afm'=> date("d.m.Y", strtotime($afm->data_semnare_afm)) ?? '',
            'data_factura_finala'=> date("d.m.Y", strtotime($afm->data_factura_finala)) ?? '',
            'contributie_afm'=>$afm->contributie_afm ?? '',
            'aport_propriu'=>$afm->aport_propriu ?? '',
            'aport_propriu_plus_contributie_afm'=>$afm->contributie_afm + $afm->aport_propriu ?? '',
            'aport_propriu_plus_contributie_afm_rotunjire' => round(($afm->contributie_afm + $afm->aport_propriu) / 1.05, 2),
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'base64_logo_img' => $base64_logo_img,
            'logo' => '<img src="'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'fi' => $fi,
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'cont_firma'=> $fi->cont_firma ?? 'RO72 BACX 0000 0009 3487 6000',
            'banca_firma'=> $fi->banca_firma ?? 'Unicredit Bank, Sucursala Râmnicu Vâlcea',
            'total_putere_panouri' => $afm->total_putere_panouri ?? '',
            'valoare_contract' => $afm->valoare_contract ?? '',
            'descriere_componenta' => $afm->descriere_componenta ?? '',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
        ] + $afm->toArray() + affix_array_keys($afm->invertor ? $afm->invertor->toArray() : [], '_invertor')
          + affix_array_keys($afm->panou ? $afm->panou->toArray() : [], '_panouri'));

            // dd($afm);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }

    public function generateActAditionalCupon($section = 2021, $afm, $download = false)
    {
        $afm = AfmForm::setSection($section)->where('id', $afm)->withInfo()->first();

        $judetI = $afm->judetImobil;
        $localitateI = $afm->localitateImobil;
        $judetD = $afm->judetDomiciliu;
        $localitateD = $afm->localitateDomiciliu;
        $fi = $afm->firma;
        $fi = $fi ? $fi->updateDetailsForSection($afm->getModelSection()) : $fi;

        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');
        $base64_logo_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));

        $data = SablonDocument::getFormatedData(26, [
            'data_curenta' => date('d.m.Y'),
            'data_contract_instalare' =>  date("d.m.Y", strtotime($afm->data_contract_instalare)),
            // date("d.m.Y", strtotime($data_pv_receptie)),
            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            // 'nr_contract_firma'=> $afm->nr_contract ?? '',
            // 'data_contract_firma'=> $afm->data_contract ?? '',

            'nr_contract_firma'=> $fi->nr_contract ?? '',
            // 'data_contract_firma'=> date("d.m.Y", strtotime($fi->data_contract)) ?? '',
            'data_pv_receptie'=> date("d.m.Y", strtotime($afm->data_pv_receptie)) ?? '',
            'numar_dosar_afm'=> $afm->numar_dosar_afm ?? '',
            'data_semnare_afm'=> date("d.m.Y", strtotime($afm->data_semnare_afm)) ?? '',
            'data_factura_finala'=> date("d.m.Y", strtotime($afm->data_factura_finala)) ?? '',
            'contributie_afm'=>$afm->contributie_afm ?? '',
            'aport_propriu'=>$afm->aport_propriu ?? '',
            'aport_propriu_plus_contributie_afm'=>$afm->contributie_afm + $afm->aport_propriu ?? '',
            'aport_propriu_plus_contributie_afm_rotunjire' => round(($afm->contributie_afm + $afm->aport_propriu) / 1.05, 2),
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'base64_logo_img' => $base64_logo_img,
            'logo' => '<img src="'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'fi' => $fi,
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'cont_firma'=> $fi->cont_firma ?? 'RO72 BACX 0000 0009 3487 6000',
            'banca_firma'=> $fi->banca_firma ?? 'Unicredit Bank, Sucursala Râmnicu Vâlcea',
            'total_putere_panouri' => $afm->total_putere_panouri ?? '',
            'valoare_contract' => $afm->valoare_contract ?? '',
            'descriere_componenta' => $afm->descriere_componenta ?? '',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',
            'stampila_firma' => '<img class="stampila" src="data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '').'" style="width:150px;height:auto;" />',
        ] + $afm->toArray() + affix_array_keys($afm->invertor ? $afm->invertor->toArray() : [], '_invertor'));

            // dd($afm);

        $pdf = \PDF::loadView('sablon-pdf', $data);
        // return view('ofertare.afm.sabloane-pdf.fisa-vizita', $data);
        if (!$download) {
            return $pdf->stream($data['subiect'].'.pdf')->header('Content-Type','application/pdf');
        } else {
            return $pdf->download($data['subiect'].'.pdf');
        }
    }

    // public function exportAfmTable(Request $request, $section = 2021)
    // {
    //     if(
    //         !QueuedExport::where('sectiune', 'ofertare_afm_'.$section)
    //             ->where('user_id', auth()->id())
    //             ->whereIn('status', ['0','1'])->exists()
    //     ) {
    //         $name = 'Ofertare_formulare_afm_'.$section.'__'.date('Y-m-d_H:i:s').'.xlsx';
    //         $folder = 'exports/ofertare/afm/';
    //         $export = QueuedExport::create([
    //             'user_id' => auth()->id(),
    //             'nume' => $name,
    //             'folder' => $folder,
    //             'status' => '1',
    //             'sectiune' => 'ofertare_afm_'.$section,
    //         ]);
    //         (new AfmTableExport($request->input('coloane'), $request->except(['coloane']), $section))
    //             ->queue($folder.$name)->chain([
    //                 new \App\Jobs\UpdateExportStatusJob($export),
    //             ]);
    //             // ->download($name);
    //         return redirect(env('OLD_SITE_NAME').'ofertare/afm/index/'.$section.'?export_start=1');
    //     }
    //     return redirect(env('OLD_SITE_NAME').'ofertare/afm/index/'.$section.'?export_in_curs=1');
    // }

    public function sendMailContractInstalare($section = 2021, $formular)
    {
        $afm = AfmForm::setSection($section)->where('id', $formular)->withInfo()->withRegiune()->first();
        if($afm) {
            $responsabil = Judet::getResponsabilRegiune($afm->regiune);
            if($afm && $afm->colaboratorAfm) {
                $responsabil = $afm->colaboratorAfm;
            }

            try {
                SendMails::dispatch([
                    'nume' => $afm->nume, 
                    'prenume' => $afm->prenume, 
                    'email' => $afm->email, 
                ], [
                    'template' => 24,
                    'cc' => $responsabil->user_email ?? auth()->user()->user_email,
                    'nume_responsabil' => $responsabil->nume_complet ?? auth()->user()->nume_complet,
                    'telefon_responsabil' => $responsabil->telefon ?? auth()->user()->telefon,
                    'email_responsabil' => $responsabil->user_email ?? auth()->user()->user_email,
                    'attachments' => [$this->generateDocument($section, $formular, 'contract-instalare', $section, 2)]
                ], [0]);

                AfmForm::setSection($section)->where('id', $formular)->withInfo()->update([
                    'data_trimitere_contract_instalare' => date('Y-m-d H:i:s'),
                    'utilizator_generare_contract_instalare' => auth()->id(),
                ]);

                return back()->with(['status' => __('Emailul a fost trimis.')]);

            } catch(\Exception $e) { 
                \Log::info($e); 
                return back()->withErrors(['error' => __('Emailul nu a putut fi trimis, va rugam sa contactati un developer.')]);
            }
        }
        return back()->withErrors(['error' => __('Formularul afm nu a putut fi gasit.')]);
    }

    public function sendMailDataEstimataMontaj($section = 2021, $formular)
    {
        $afm = AfmForm::setSection($section)->where('id', $formular)->withInfo()->first();
        if($afm) {

            // $fisier = $afm->fisier('schita_amplasare_panouri')->first();

            try {
                SendMails::dispatch([
                    'nume' => $afm->nume, 
                    'prenume' => $afm->prenume, 
                    'email' => $afm->email, 
                ], [
                    'template' => auth()->id() == 413 ? 33 : 32,
                    'cc' => auth()->user()->user_email,
                    'nume_utilizator' => auth()->user()->nume_complet,
                    'telefon_utilizator' => auth()->user()->telefon,
                    'email_utilizator' => auth()->user()->user_email,
                    'data_inceput_estimare_montaj' => $afm->data_inceput_estimare_montaj 
                        ? date('d.m.Y', strtotime($afm->data_inceput_estimare_montaj)) : '',
                    'data_sfarsit_estimare_montaj' => $afm->data_sfarsit_estimare_montaj 
                        ? date('d.m.Y', strtotime($afm->data_sfarsit_estimare_montaj)) : '',
                ]/* + ($fisier ? [
                    'attachments' => [$fisier->attachment()]
                ] : [])*/, [0]);

                AfmForm::setSection($section)->where('id', $formular)->withInfo()->update([
                    'data_trimitere_informare_montaj' => date('Y-m-d H:i:s'),
                    'utilizator_trimitere_informare_montaj' => auth()->id(),
                ]);

                return back()->with(['status' => __('Emailul a fost trimis.')]);

            } catch(\Exception $e) { 
                \Log::info($e); 
                return back()->withErrors(['error' => __('Emailul nu a putut fi trimis, va rugam sa contactati un developer.')]);
            }
        }
        return back()->withErrors(['error' => __('Formularul afm nu a putut fi gasit.')]);
    }

    protected function dynamicDataForDocuments($item)
    {
        $judetI = $item->judetImobil;
        $localitateI = $item->localitateImobil;
        $judetD = $item->judetDomiciliu;
        $localitateD = $item->localitateDomiciliu;
        $fi = $item->firma;
        $fi = $fi ? $fi->updateDetailsForSection($item->getModelSection()) : $fi;

        $invertor = $item->invertor;
        $panouri = $item->panou;
        $sir = is_array($item->siruri_panouri) ? $item->siruri_panouri : json_decode($item->siruri_panouri, true);

        $base64_logo_img = 'data:image/png;base64,' . base64_encode($this->file_assets('assets/ofertare/genway_logo_small.png', 2));
        $base64_stamp_img = 'data:image/png;base64,' . base64_encode($fi ? $fi->stampila : '');

        $item->setAppends($item->getMutatedAttributes());

        // specific for pv predare primire
        $siruri = collect([$item])->groupBy([
            function ($item, $key) {
                $panouri = is_array($item['siruri_panouri'])
                    ? $item['siruri_panouri']
                    : json_decode($item['siruri_panouri'], true);
                return $panouri;
            },
            function ($item, $key) {
                return $item['tipul_invelitorii'] == 'tigla' ? 'tigla' : 'non_tigla';
            }
        ])->sortKeys();

        $total = [
            'Sina R1 - 2.2m' => 0,
            'Sina R1 - 3.3m' => 0,
            'Sina de imbinare RS' => 0,
            'MRH-01 - TABLA' => 0,
            'TRH-01A - TIGLA' => 0,
            'Clema capat EC-01' => 0,
            'Clema de mijloc  MC-01' => 0
        ];

        foreach($siruri as $sir => $invelitori) {
            $nr = (int)str_replace('1x', '', $sir);
            $count_tigla = count($invelitori['tigla'] ?? []);
            $count_non_tigla = count($invelitori['non_tigla'] ?? []);
            $cant = $count_tigla + $count_non_tigla;

            $total['Sina R1 - 2.2m'] += $cant * ($nr % 3);
            $total['Sina R1 - 3.3m'] += $cant * (intval($nr / 3) * 2);
            $total['Sina de imbinare RS'] += $cant * (intval(($nr - 1) / 3) * 2);
            $total['MRH-01 - TABLA'] += $count_non_tigla * ($nr * 2 + 4);
            $total['TRH-01A - TIGLA'] += $count_tigla * ($nr * 2 + 4);
            $total['Clema capat EC-01'] += $cant * 4;
            $total['Clema de mijloc  MC-01'] += $cant * (($nr - 1) * 2);
        }
        // END for pv predare primire

        return $this->convertDates([
            'data_curenta' => date('d.m.Y'),
            'date_plus_1year'=> date('d.m.Y', strtotime('+1 Year')),
            'data_contract_instalare' => $item->data_contract_instalare ? date('d.m.Y', strtotime($item->data_contract_instalare)) : '',
            'data_factura_finala' => $item->data_factura_finala ? date('d.m.Y', strtotime($item->data_factura_finala)) : '',
            'valoare_contract_fara_tva' => round($item->valoare_contract / 1.09, 2),    // tva de 9%

            // 'distribuitor_energie' => $distribuitor,
            'base64_logo_img' => $base64_logo_img,
            'base64_stamp_img' => $base64_stamp_img,
            'stampila_firma' => '<img class="stampila" class="stampila" src="'.$base64_stamp_img.'" style="width:150px;height:auto;" />',
            'logo' => '<img src="'.$base64_logo_img.'" style="width:150px;height:auto;" />',
            'numar_act_aditional_cota_tva' => $this->getNumarActAditional($item, 'act_aditional_cota_tva'),
            'numar_act_aditional_upgrade' => $this->getNumarActAditional($item, 'act_aditional_upgrade'),
            'numar_act_aditional_cupon' => $this->getNumarActAditional($item, 'act_aditional_cupon'),
            'siruri_panouri' => is_array($sir) ? implode(' x ', $sir) : $sir,

            
            'nume_judet_domiciliu' => $judetD ? $judetD->nume : '',
            'nume_localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'judet_domiciliu' => $judetD ? $judetD->nume : '',
            'localitate_domiciliu' => $localitateD ? $localitateD->nume : '',
            'bloc_domiciliu' => isset($item->bloc_domiciliu) && !empty($item->bloc_domiciliu) ? 'bloc '.$item->bloc_domiciliu.', ' : '',
            'scara_domiciliu' => isset($item->scara_domiciliu) && !empty($item->scara_domiciliu) ? 'scara '.$item->scara_domiciliu.', ' : '',
            'et_domiciliu' => isset($item->et_domiciliu) && !empty($item->et_domiciliu) ? 'et. '.$item->et_domiciliu.', ' : '',
            'ap_domiciliu' => isset($item->ap_domiciliu) && !empty($item->ap_domiciliu) ? 'ap. '.$item->ap_domiciliu.', ' : '',
            'adresa_domiciliu' => $item ? $item->adresa_domiciliu : '',

            'nume_judet_imobil' => $judetI ? $judetI->nume : '',
            'nume_localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'judet_imobil' => $judetI ? $judetI->nume : '',
            'localitate_imobil' => $localitateI ? $localitateI->nume : '',
            'bloc_imobil' => isset($item->bloc_imobil) && !empty($item->bloc_imobil) ? 'bloc '.$item->bloc_imobil.', ' : '',
            'scara_imobil' => isset($item->scara_imobil) && !empty($item->scara_imobil) ? 'scara '.$item->scara_imobil.', ' : '',
            'et_imobil' => isset($item->et_imobil) && !empty($item->et_imobil) ? 'et. '.$item->et_imobil.', ' : '',
            'ap_imobil' => isset($item->ap_imobil) && !empty($item->ap_imobil) ? 'ap. '.$item->ap_imobil.', ' : '',
            'adresa_imobil' => $item ? $item->adresa_imobil : '',

            'tipul_bransamentului'=> $item->tipul_bransamentului,
            'ca_invertor' => $item->tipul_bransamentului == 'monofazat' ? 230 : 380,
            // 'tip_invertor' => $invertor['tip'] ?? '',
            // 'cod_invertor'=> $invertor['cod'] ?? '',
            // 'contor_invertor'=> $invertor['contor'] ?? '',
            'descriere_invertor'=> nl2br($invertor['descriere'] ?? ''),
            'nr_cerere_pif_default'=> $item->nr_cerere_pif ?? '........',
            'cod_loc_consum_default'=> $item->cod_loc_consum ?? '................',
            'pod_default'=> $item->pod ?? '........',

            'reg_com_firma_fi'=> $fi->reg_com_firma ?? 'J40/4862/23.04.2015',
            'cod_fiscal_firma_fi'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'nr_autorizare_firma_fi'=> $fi->nr_autorizare ?? '13692/28.09.2018',
            'nr_autorizare'=> $fi->nr_autorizare ?? '...................',
            'nr_autorizare_2018'=> $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '13692/28.09.2018',
            'nr_autorizare_default'=> $fi->nr_autorizare ?? '13692/28.09.2018',
            'data_valabilitate_autorizare_default' => $fi->data_valabilitate_autorizare ?? '28.09.2023',
            'nume_firma_fi_default' => $fi->nume_firma ?? 'S.C. ELECTRO SERVICE DISTRIBUTIE S.R.L.',
            'nume_firma_fi' => $fi->nume_firma ?? '...................',
            'nume_firma'=> $fi ? $fi->nume_firma : '...................',
            'judet_firma' => $fi ? $fi->judet_firma : '...................',
            'reprezentant_firma' => $fi ? $fi->reprezentant_firma : '...................',
            'cod_fiscal_firma'=> $fi->cod_fiscal_firma ?? 'RO21241885',
            'reg_com_firma'=> $fi->reg_com_firma ?? 'J40/4862/2015',
            'cont_firma'=> $fi->cont_firma ?? 'RO72 BACX 0000 0009 3487 6000',
            'banca_firma'=> $fi->banca_firma ?? 'Unicredit Bank, Sucursala Râmnicu Vâlcea',
            'adresa_firma' => $fi ? $fi->adresa_firma : '...................',
            'localitate_firma' => $fi ? $fi->localitate_firma : '...................',

            'putere_panouri_kw'=> $item->putere_panouri / 1000,
            'cod_panouri' => $panouri ? $panouri->model_panouri : '',
            'total_putere_panouri' => $item->total_putere_panouri,
            // 'marca_panouri' => $item->putere_panouri == 430 ? 'TRINA' : 'JA SOLAR',

            'max_putere' => $item->total_putere_panouri > $item->putere_invertor
                ? $item->total_putere_panouri
                : $item->putere_invertor,
            'min_putere' => $item->total_putere_panouri < $item->putere_invertor
                ? $item->total_putere_panouri
                : $item->putere_invertor,
            'numar_sp_uri' => $item['numar_sp-uri'],
            'tabel_generatoare_asincrone' => \Blade::render('<x-documente.tabel-generatoare-asincrone-si-sincrone-notificare-racordare-e-distributie />'),
            'tabel_echipamente' => \Blade::render('<x-documente.tabel-echipamente-pv-predare-primire
                    :invertor="$invertor"
                    :numar_sp_uri="$numar_sp_uri"
                    :numar_panouri="$numar_panouri"
                    :marca_panouri="$marca_panouri"
                    :putere_panouri="$putere_panouri"
                    :total="$total"
                />',
                [
                    "invertor" => $invertor,
                    "numar_sp_uri" => $item->{'numar_sp-uri'},
                    "numar_panouri" => $item->numar_panouri,
                    "marca_panouri" => $item->marca_panouri,
                    "putere_panouri" => $item->putere_panouri,
                    "total" => $total,
                ]
            ),
        ]   + $item->toArray()
            + affix_array_keys($fi ? $fi->toArray() : [], '_firma')
            + affix_array_keys($invertor ? $invertor->toArray() : [], '_invertor')
            + affix_array_keys($panouri ? $panouri->toArray() : [], '_panouri')
        );
    }

    protected function convertDates($item)
    {
        $dates = [];

        if($item && (is_object($item) || is_array($item))) {
            foreach (is_object($item) ? $item->toArray() : $item as $key => $value) {
                if(preg_match('/^(?:\w+_)?data(?:_\w+)?$/', $key)) {
                    $dates[$key] = $value ? date('d.m.Y', strtotime($value)) : '';
                } else {
                    $dates[$key] = $value;
                }
            }
        }
        return $dates;
    }

    protected function getNumarActAditional($item, $act)
    {
        $nr = 1;
        if(isset($item['act_aditional_cota_tva']) && in_array($act, ['act_aditional_upgrade','act_aditional_cupon'])) {
            $nr++;
        }
        if(isset($item['act_aditional_upgrade']) && in_array($act, ['act_aditional_cota_tva','act_aditional_cupon'])) {
            $nr++;
        }
        if(isset($item['act_aditional_cupon']) && in_array($act, ['act_aditional_cota_tva','act_aditional_upgrade'])) {
            $nr++;
        }
        return $nr;
    }

    protected function commonParameters($section = 2021)
    {
        return [
            'section' => $section,
            'sectiune' => __('AFM :section', ['section' => $section]),
            'browse_route' => route('ofertare.afm.browse'),
            // 'form' => true,
            // 'columns' => SablonAFM::allColumns(),
        ];
    }
}
