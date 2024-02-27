<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ofertare\ColoanaTabelAFM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if(auth()->id() == '413') {

            $cols = [
                'id' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'ID',
                    'rules' => [
                        'validation' => ['required', 'integer', 'min:1'],
                    ],
                ],
                'data' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'ip' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'IP',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'nume' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Nume',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'prenume' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Prenume',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'telefon' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Telefon',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'email' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Email',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'email', 'max:255'],
                    ],
                ],
                'cnp' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'CNP',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'serie_ci' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Serie CI',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'numar_ci' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Numar CI',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'valabilitate_ci' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Valabilitate CI',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'judet_domiciliu' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Judet domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\Judet,id'],
                        'db' => [
                            'table' => 'geo_judete',
                            'cols' => 'id, nume as label',
                        ],
                    ],
                ],
                'localitate_domiciliu' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Localitate domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\Localitate,id'],
                        'db' => [
                            'table' => 'geo_localitati',
                            'cols' => 'id, nume as label',
                            'where' => [
                                ['label', 'like', '%:value']
                            ],
                        ],
                    ],
                ],
                'strada_domiciliu' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Strada domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'numar_domiciliu' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Numar domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'bloc_domiciliu' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Bloc domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'scara_domiciliu' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Scara domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'et_domiciliu' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Etaj domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'ap_domiciliu' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Ap. domiciliu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'adrese' => [
                    'tip' => '5', // 'select',
                    'editare' => 1,
                    'titlu' => 'Aceeasi adresa',
                    'default_values' => ['1' => 'Da'],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'judet_imobil' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Judet imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\Judet,id'],
                        'db' => [
                            'table' => 'geo_judete',
                            'cols' => 'id, nume as label',
                        ],
                    ],

                ],
                'localitate_imobil' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Localitate imobil',
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\Localitate,id'],
                        'db' => [
                            'table' => 'geo_localitati',
                            'cols' => 'id, nume as label',
                            'where' => [
                                ['label', 'like', '%:value']
                            ],
                        ],
                    ],
                ],
                'strada_imobil' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Strada imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'numar_imobil' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Numar imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'bloc_imobil' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Bloc imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'sc_imobil' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Scara imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'et_imobil' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Etaj imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'ap_imobil' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Ap. imobil',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'nr_carte' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Nr. carte funciara',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'nr_cadastral' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Numar cadastral',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'exista_copro' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Exista coproprietari',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'coproprietari' => [
                    'tip' => '4', // 'textarea',
                    'titlu' => 'Coproprietari',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:4096'],
                    ],
                ],
                'cerere2' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'A doua cerere',
                    'editare' => 1,
                    'default_values' => ['' => 'Nu', '1' => 'Da'],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'nr_cerere_precedenta' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Nr. cerere precedenta',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'motive_cerere_noua' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Motive cerere noua',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'tipul_bransamentului' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Tipul bransamentului',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Tipul bransamentului', 
                        'monofazat' => 'Monofazat',
                        'trifazat' => 'Trifazat',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'pozitia_contoarului' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Pozitia contoarului',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Pozitia contoarului',
                        'in casa' => 'In casa',
                        'la limita de proprietate' => 'La limita de proprietate',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],

                ],
                'tipul_invelitorii' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Tipul invelitorii',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Tipul invelitorii',
                        'tigla' => 'Tigla',
                        'tabla' => 'Tabla',
                        'altele' => 'Altele',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'link_cerere' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Link cerere',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'status' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Status',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Status',
                        1 => 'Acte depuse',
                        2 => 'Clarificari trimise',
                        3 => 'Dosare complete', // dosar verificat
                        6 => 'Dosare aprobate',
                        7 => 'Dosare respins',
                        0 => 'Fara acte',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'dosar_depus' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Dosar depus',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Status compus',
                        1 => 'Dosare verificate fără dosare depuse',
                        2 => 'Dosare verificate + Dosare depuse',
                        3 => 'Dosare verificate + Dosare nedepuse',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'componente' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Componente',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Componente', 1 => 1,2 => 2,3 => 3,4 => 4,5 => 5,6 => 6,7 => 7,8 => 8,9 => 9
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'observatii' => [
                    'tip' => '4', // 'textarea',
                    'titlu' => 'Observatii',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:4096'],
                    ],
                ],
                'firma_instalatoare' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Firma instalatoare',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\Ofertare\\Firma,id'],
                        'db' => [
                            'table' => 'firme',
                            'cols' => 'id, nume_firma as label',
                            'where' => [
                                ['label', 'like', '%:value']
                            ],
                        ],
                    ],
                ],
                'data_aprobare_afm' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data aprobare AFM',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'data_semnare_afm' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data semnare AFM',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'data_semnare_instalare' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data instalare',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'data_vizita' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data vizita',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'ing_vizita' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Inginer vizita',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\User,id'],
                        'db' => [
                            'table' => 'useri',
                            'cols' => 'id, nume_firma as label',
                            'where' => [
                                ['agent', '=', '2'],
                                ['label', 'like', '%:value']
                            ],
                        ],
                    ],

                ],
                'fisier_vizita' => [
                    'tip' => '7', // 'file',
                    'titlu' => 'Fisier vizita',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'file', 'max:2048'],
                        'additional_info' => [
                            'model_type' => 'App\\Models\\AfmForm',
                            'model_id' => ':value',
                            'foreign_column' => 'id_formular',
                        ],
                    ],
                ],
                'siruri_panouri' => [
                    'tip' => '8', // 'popup',
                    'titlu' => 'Siruri panouri',
                    'editare' => 1,
                ],
                'data_programare_montaj' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data programare montaj',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'echipa_montaj' => [
                    'tip' => '8', // 'popup',
                    'titlu' => 'Echipa montaj',
                    'editare' => 1,
                ],
                'data_pv_receptie' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data PV receptie',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'data_dosar_prosumator' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data dosar prosumator',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'pers_int_dosar' => [
                    'tip' => '5', // 'select_db',
                    'titlu' => 'Persoana intocmire dosar prosumator',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'exists:\\App\\Models\\User,id'],
                        'db' => [
                            'table' => 'useri',
                            'cols' => 'id, nume_firma as label',
                            'where' => [
                                ['agent', '>=', '1'],
                                ['label', 'like', '%:value']
                            ],
                        ],
                    ],
                ],
                'data_cer' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data obtinere CER',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'fisier_cer' => [
                    'tip' => '7', // 'file',
                    'titlu' => 'Fisier CER',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'file', 'max:2048'],
                        'additional_info' => [
                            'model_type' => 'App\\Models\\AfmForm',
                            'model_id' => ':value',
                            'foreign_column' => 'id_formular',
                        ],
                    ],
                ],
                'data_dosar_decontare' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data dosar decontare',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'data_aport_propriu' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data aport propriu',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                'stare_montaj' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Stare montaj',
                    'editare' => 1,
                    'default_values' => [
                        '' => '',
                        'sistem montat complet'  => 'Sistem montat complet',
                        'sistem nemontat' => 'Sistem nemontat',
                        'invertor lipsa'  => 'Invertor lipsa',
                        'alte componente lipsa' => 'Alte componente lipsa',
                        'contract anulat' => 'Contract anulat',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'situatie_dosar' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Situatie dosar',
                    'editare' => 1,
                    'default_values' => [
                        '' => '',
                        'nedepus' => 'Nedepus',
                        'dosar tehnic depus' => 'Dosar tehnic depus',
                        'CER obtinut' => 'CER obtinut',
                        'dosar decontare depus' => 'Dosar decontare depus',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'bon_consum' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Bon consum',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'comision_agent' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Comision agent',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric'],
                    ],
                ],
                'fisier_semnat_afm' => [
                    'tip' => '7', // 'file',
                    'titlu' => 'Fisier semnat afm',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'file', 'max:2048'],
                        'additional_info' => [
                            'model_type' => 'App\\Models\\AfmForm',
                            'model_id' => ':value',
                            'foreign_column' => 'id_formular',
                        ],
                    ],
                ],
                'fisier_semnat_instalare' => [
                    'tip' => '7', // 'file',
                    'titlu' => 'Fisier semnat instalare',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'file', 'max:2048'],
                        'additional_info' => [
                            'model_type' => 'App\\Models\\AfmForm',
                            'model_id' => ':value',
                            'foreign_column' => 'id_formular',
                        ],
                    ],
                ],
                'sursa_contract' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Sursa contract',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Toate',
                        'firma' => 'Firma',
                        'agent' => 'Agent',
                        'transfer' => 'Transfer',
                    ],
                ],
                'numar_dosar_afm' => [
                    'tip' => '1', // 'text',
                    'titlu' => 'Numar dosar afm',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'regiune' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Regiune',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Toate',
                        'buc_ilfov' => 'București-Ilfov',
                        'sud_muntenia' => 'Sud-Muntenia',
                        'sud_vest' => 'Sud-Vest',
                        'sud_est' => 'Sud-Est',
                        'nord_est' => 'Nord Est',
                        'vest' => 'Vest',
                        'nord_vest' => 'Nord Vest',
                        'centru' => 'Centru',
                        'partener_ialomita' => 'Partener Ialomita',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'putere_invertor' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Putere invertor',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0', 'max:999'],
                    ],
                ],
                'marca_invertor' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Marca invertor',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Toate',
                        'Fronius' => 'Fronius',
                        'Fronius GEN24' => 'Fronius GEN24',
                        'Huawei' => 'Huawei',
                        'Sungrow' => 'Sungrow',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'string', 'max:255'],
                    ],
                ],
                'putere_panouri' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Putere panouri',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0', 'max:999'],
                    ],
                ],
                'total_putere_panouri' => [
                    'tip' => '9', // 'dinamic',
                    'titlu' => 'Total putere panouri',
                    'afisare' => 2,
                ],
                'numar_panouri' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Numar panouri',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:0', 'max:999'],
                    ],
                ],
                'aport_propriu' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Aport propriu',
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'contributie_afm' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Contributie AFM',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'valoare_contract' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Valoare contract',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'vizita' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Vizita',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Toate',
                        '0' => 'Nu',
                        '1' => 'Da',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:0', 'max:1'],
                    ],
                ],
                'nr_factura_avans' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Nr. factura avans',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0'],
                    ],
                ],
                'val_factura_avans' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Valoare factura avans',
                    'editare' => 1,
                ],
                'doc_incasare_factura_avans' => [
                    'tip' => '7', // 'file',
                    'titlu' => 'Document incasare factura avans',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'file', 'max:2048'],
                        'additional_info' => [
                            'model_type' => 'App\\Models\\AfmForm',
                            'model_id' => ':value',
                            'foreign_column' => 'id_formular',
                        ],
                    ],
                ],
                'data_decontare_afm' => [
                    'tip' => '3', // 'date',
                    'titlu' => 'Data decontare AFM',
                    'editare' => 1,
                ],
                'comision_salarizare' => [
                    'tip' => '2', // 'numeric',
                    'titlu' => 'Comision salarizare',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'numeric', 'min:0', 'max:999'],
                    ],
                ],
                'schita_amplasare_panouri' => [
                    'tip' => '7', // 'file',
                    'titlu' => 'Schita amplasare panouri',
                    'editare' => 1,
                    'rules' => [
                        'validation' => ['nullable', 'file', 'max:2048'],
                        'additional_info' => [
                            'model_type' => 'App\\Models\\AfmForm',
                            'model_id' => ':value',
                            'foreign_column' => 'id_formular',
                        ],
                    ],
                ],
                'numar_sp-uri' => [
                    'tip' => '5', // 'select',
                    'titlu' => 'Numar sp-uri',
                    'editare' => 1,
                    'default_values' => [
                        '' => 'Toate',
                        '1' => '1',
                        '2' => '2',
                    ],
                    'rules' => [
                        'validation' => ['nullable', 'integer', 'min:1', 'max:2'],
                    ],
                ],
            ];

            // foreach ($cols as $name => $value) {
            //     if($value['tip'] == '5' && isset($value['rules']['db']['cols'])) {
            //         $value['rules']['db']['cols'] = explode(', ', $value['rules']['db']['cols']);
            //         ColoanaTabelAFM::where('nume', $name)->update([
            //             'rules' => $value['rules'],
            //         ]);
            //     }
            // }
        }
        return view('ofertare.roles.index', [
            'sectiune' => __('Roluri'),
            'roles' => Role::all(),
            'add_route' => route('ofertare.roles.create'),
            'can_delete' => true
        ]);
    }

    public function create()
    {
        return view('ofertare.roles.create', $this->commonParameters());
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(),[],$this->names());

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('ofertare.roles.edit', $role)->with([
            'status' => __('Rolul :name a fost creat.', ['name' => $role->name])
        ], 200);
    }

    public function edit(Role $role)
    {
        return view('ofertare.roles.create', $this->commonParameters() + [
            'item' => $role,
            'itemPermission' => $this->permissionsList($role->permissions)
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate($this->rules($role->id),[],$this->names());

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return back()->with(['status' => __('Rolul :name a fost actualizat.', ['name' => $role->name])], 200);
    }

    public function delete(Request $request, Role $role)
    {
        $role->delete();
        return back()->with(['status' => __('Rolul :name a fost sters.', ['name' => $role->name])], 200);
    }

    protected function rules($id = null)
    {
        return [
            'name' => ['required', 'max:255', Rule::unique(Role::class, 'name')->ignore($id)],
            'permissions' => ['nullable', 'array', 'min:1'],
            'permissions.*' => ['nullable', 'integer', Rule::exists(Permission::class, 'id')],
        ];
    }

    protected function names()
    {
        return [
            'name' => 'nume',
            'permissions' => 'permisiuni',
            'permissions.*' => 'permisiune',
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Roluri'),
            'browse_route' => route('ofertare.roles.browse'),
            'form' => true,
            'sections' => $this->permissionsList(),
        ];
    }

    protected function permissionsList($permissions = null)
    {
        $arr = $permissions != null 
            ? \Arr::undot($permissions->pluck('id', 'name')->toArray())
            : \Arr::undot(Permission::all()->pluck('id', 'name')->toArray());
        $this->recur_uasort($arr);
        return $arr;
    }

    // sort array values last
    protected function recur_uasort(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)){
                $this->recur_uasort($value);
            }
        }
        uasort($array, function($item1, $item2) {
            if(is_array($item1) && !is_array($item2)) {
                return 1;
            } elseif(!is_array($item1) && is_array($item2)) {
                return -1;
            } else {
                return 0;
            }
        });
    }
}
