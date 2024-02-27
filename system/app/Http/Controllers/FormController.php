<?php

namespace App\Http\Controllers;

use App\Events\SendMails;
use App\Models\DynamicModel;
use App\Models\Localitate;
use App\Models\Judet;
use App\Models\Ofertare\SectiuneAFM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FormController extends Controller
{
    public function storeOfertare(Request $request)
    {
        $section = $request->input('sectiune') ?? '2023';
        $tip = $request->input('tip') ?? '0';

        $validator = Validator::make(
            $request->input(),
            $this->rulesOfertare($section, $tip),
            $this->messagesOfertare(),
            $this->namesOfertare(),
        );

        if($validator->fails()) {
            if($request->has('ajax')) {
                return response()->json([
                    'status' => 422,
                    'errors' => \Blade::render('<x-ajax-validation-errors :errors="$errors" />', [
                        'errors' => $validator->errors()
                    ]),
                ]);
            } else {
                $validator->validate();
            }
        }

        $inputs = array_filter($validator->validate());
        $inputs['an'] = $section;
        $cerinta = $inputs['cerinta'];
        unset($inputs['date']);
        unset($inputs['cerinta']);
        unset($inputs['sectiune']);

        $form = DynamicModel::table('fotovoltaice_ofertare')->create($inputs);

        try {
            if($form->tip == 1) {
                SendMails::dispatch($inputs, $form->toArray() + [
                    'template' => 30,
                    'cerinta' => $cerinta,
                    'telefon' => $inputs['telefon'],
                    'cc' => Judet::responsabilRegiuneEmail($inputs['judet']),
                    'attachments' => [resource_path('assets/Oferta-Genway-fonduri-proprii-15Feb.pdf')]
                ], [0]);
            } elseif($section == 2024) {
                SendMails::dispatch($inputs, $form->toArray() + [
                    'template' => 31,
                    'cerinta' => $cerinta,
                    'telefon' => $inputs['telefon'],
                    'cc' => Judet::responsabilRegiuneEmail($inputs['judet']),
                    'attachments' => [resource_path('assets/Oferta Casa Verde.pdf')]
                ], [0]);
            } else {
                SendMails::dispatch($inputs, $form->toArray() + [
                    'template' => 25,
                    'cerinta' => $cerinta,
                    'telefon' => $inputs['telefon'],
                    'cc' => Judet::responsabilRegiuneEmail($inputs['judet']),
                    'attachments' => [resource_path('assets/Oferta Casa Verde.pdf')]
                ], [0]);
            }
        } catch(\Exception $e) { \Log::info($e); }

        return $request->has('ajax')
            ? response()->json([
                'status' => 200,
                'message' => __('Multumim pentru inscriere! Veti primi un email de informare in curand.'),
            ]) : back()->with([
                'status' => __('Multumim pentru inscriere! Veti primi un email de informare in curand.')
            ], 200);
    }

    public function rulesOfertare($section = 2023, $tip = 0)
    {
        return [
            'tip' => ['nullable', 'integer', 'max:2'],
            'sectiune' => ['nullable', 'string', 'max:255', Rule::exists(SectiuneAFM::class, 'nume')],
            'nume' => ['required', 'string', 'max:255'],
            'prenume' => ['required', 'string', 'max:255'],
            'judet' => ['required', 'integer', Rule::exists(Judet::class, 'id')],
            'telefon' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255'],
            'cerinta' => ['required', 'string', 'max:2500'],
            'date' => ['accepted'],
        ];
    }

    public function messagesOfertare()
    {
        return [
            'judet.exists' => __('Judetul selectat nu face parte din lista de judete'),
        ];
    }

    public function namesOfertare()
    {
        return [
            'tip' => __('Tip'),
            'nume' => __('Nume'),
            'prenume' => __('Prenume'),
            'judet' => __('Judet'),
            'telefon' => __('Telefon'),
            'email' => __('Adresa de email'),
            'cerinta' => __('Cerinta'),
            'date' => __('Prelucrarea datelor cu caracter personal'),
        ];
    }

    // public function storeTransferDosar(Request $request)
    // {
    //     $inputs = array_filter(Validator::make($input, [
    //         'nume' => ['required', 'string', 'max:255'],
    //         'judet' => ['required', 'integer', 'exists:geo_judete,id'],
    //         'localitate' => ['required', 'integer', 'exists:geo_localitati,id'],
    //         'strada' => ['required', 'string', 'max:10000'],
    //         'adrese_transfer' => ['nullable', 'string', 'in:1'],
    //         'judet_implementare' => ['nullable', 'required_unless:adrese_transfer,1', 'integer', 'exists:geo_judete,id'],
    //         'localitate_implementare' => ['nullable', 'required_unless:adrese_transfer,1', 'integer', 'exists:geo_localitati,id'],
    //         'strada_implementare' => ['nullable', 'required_if:adrese_transfer,1', 'string', 'max:10000'],
    //         'email' => ['required', 'string', 'email', 'max:255'],
    //         'cnp' => ['required', 'string', 'max:15', 'digits:13'],
    //         'serie_ci' => ['required', 'required_if:tip,2', 'string', 'max:255'],
    //         'nr_ci' => ['required', 'required_if:tip,2', 'string', 'max:255'],
    //         'nr_contract' => ['required', 'string', 'max:255'],
    //         'telefon' => ['required', 'string', 'max:255'],
    //         'numar_inregistrare' => ['required', 'string', 'max:255'],
    //         'numar_instalator' => ['required', 'string', 'max:255'],
    //         'nume_instalator' => ['required', 'string', 'max:255'],
    //         'adresa_instalator' => ['required', 'string', 'max:255'],
    //         'nr_contract_instalator' => ['required', 'string', 'max:255'],
    //         'nr_contract_finantare' => ['required', 'string', 'max:255'],
    //         'motiv' => ['required', 'string', 'max:10000'],
    //         'terms' => ['required', 'accepted'],
    //     ],[
    //         'judet.exists' => __('Judetul selectat nu face parte din lista de judete'),
    //         'judet_implementare.exists' => __('Judetul de implementare selectat nu face parte din lista de judete'),
    //         'judet_implementare.required_unless' => __('Judetul de implementare este necesar cand adresa de domiciliu nu corespunde cu adresa implementarii proiectului'),
    //         'judet.exists' => __('Localitatea selectata nu face parte din lista de localitati'),
    //         'judet_implementare.exists' => __('Localitatea de implementare selectata nu face parte din lista de localitati'),
    //         'localitate_implementare.required_unless' => __('Localitatea de implementare este necesara cand adresa de domiciliu nu corespunde cu adresa implementarii proiectului'),
    //     ],[
    //         'cnp' => __('CNP'),
    //         'serie_ci' => __('serie act de identitate'),
    //         'nr_ci' => __('numar act de identitate'),
    //         'nr_contract' => __('numarul contractului de finantare incheiat cu AFM'),
    //         'numar_inregistrare' => __('numarul de inregistrare al dosarului'),
    //         'nume_instalator' => __('numele instalatorului actual'),
    //         'adresa_instalator' => __('adresa instalatorului actual'),
    //         'nr_contract_instalator' => __('numarul contractului dintre instalatorul actual si AFM'),
    //         'nr_contract_finantare' => __('numarul contractului de finantare incheiat cu AFM'),
    //         'motiv' => __('motivul transferului de la instalatorul actual'),
    //         'terms' => __('sunt de acord cu prelucrarea datelor cu caracter personal'),
    //     ])->validateWithBag('transferDosar'));

    //     $form = DB::table('formular_cerere_transfer')->insert([
    //         'nume' => $inputs['nume'],
    //         'judet' => $inputs['judet'],
    //         'localitate' => $inputs['localitate'],
    //         'strada' => $inputs['strada'],
    //             'judet_implementare' => isset($inputs['adrese_transfer'])
    //                 ? $inputs['judet']
    //                 : $inputs['judet_implementare'],
    //             'localitate_implementare' => isset($inputs['adrese_transfer'])
    //                 ? $inputs['localitate']
    //                 : $inputs['localitate_implementare'],
    //             'strada_implementare' => isset($inputs['adrese_transfer'])
    //                 ? $inputs['strada']
    //                 : $inputs['strada_implementare'],
    //         'email' => $inputs['email'],
    //         'cnp' => $inputs['cnp'],
    //         'serie_ci' => $inputs['serie_ci'],
    //         'nr_ci' => $inputs['nr_ci'],
    //         'nr_contract' => $inputs['nr_contract'],
    //         'telefon' => $inputs['telefon'],
    //         'numar_inregistrare' => $inputs['numar_inregistrare'],
    //         'nume_instalator' => $inputs['nume_instalator'],
    //         'adresa_instalator' => $inputs['adresa_instalator'],
    //         'nr_contract_instalator' => $inputs['nr_contract_instalator'],
    //         'nr_contract_finantare' => $inputs['nr_contract_finantare'],
    //         'motiv' => $inputs['motiv'],
    //     ]);

    //     // generate document


    //     try {
    //         $details = setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']) + [
    //             'cerere' => true,
    //         ];

    //         SendMails::dispatch($user, $details, [1]);

    //     } catch(\Exception $e) { \Log::info($e); }

    //     return back()->with([
    //         'status_transfer_dosar' => __('Formularul a fost trimis cu succes.')
    //     ]);
    // }
}
