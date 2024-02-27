<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_email',
        'user_pass',
        'user_pass_clean',
        'user_pass_salt',
        'password',
        'tip',
        'nume',
        'prenume',
        'cnp',
        'telefon',
        'id_localitate',
        'adresa',
        'livrare_adresa_1',
        'livrare_id_localitate',
        'livrare_adresa',
        'nume_firma',
        'cui',
        'nr_reg_comert',
        'autorizatie_igpr',
        'discount_fidelitate',
        'data_adaugare',
        'user_last_login',
        'cod_validare',
        'reset_pass_new',
        'reset_pass_validare',
        'reseller_cerere',
        'reseller',
        'newsletter',
        'admin',
        'valid',
        'agent',
        'compartiment_id',
        'ofertare_vizualizare',
        'ofertare_modificare',
        'ofertare_adaugare',
        'ofertare_reseller',
        'serie_contract',
        'nr_contract',
        'ofertare_verificare',
        'platitor_tva',
    ];

    protected $table = 'useri';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }

    public function routeNotificationForMail($notification)
    {
        return [$this->user_email => $this->nume.' '.$this->prenume];
    }

    //           Relationships
    //////////////////////////////////////

    public function comenzi()
    {
        return $this->hasMany(Comanda::class, 'id_user');
    }

    public function localitateFacturare()
    {
        return Localitate::where('id', $this->id_localitate)->first();
    }

    public function localitateLivrare()
    {
        return Localitate::where('id', $this->livrare_id_localitate)->first();
    }

    public function localitate()
    {
        return Localitate::where('id', $this->livrare_id_localitate)->firstOr(function () {
            return Localitate::where('id', $this->id_localitate)->first();
        });
    }

    public function getLocalitateAttribute()
    {
        return $this->localitate();
    }

    public function exporturi()
    {
        return $this->hasMany(QueuedExport::class, 'user_id');
    }

    public function compartiment()
    {
        return $this->belongsTo(Ofertare\Compartiment::class, 'compartiment_id');
    }

    public function programari()
    {
        return $this->belongsToMany(Ofertare\Programare::class, 'echipa_programare', 'user_id', 'programare_id')
            ->withPivot('procent','lider');
    }

    public function userProgramari()
    {
        return $this->programari()->wherePivot('lider', '1');
    }

    public function sabloaneAFM()
    {
        return $this->hasMany(Ofertare\SablonAFM::class);
    }

    public function implicitSablonAFM()
    {
        return $this->sabloaneAFM()->one()->ofMany('implicit', 'max');
    }

    public function getRoleAttribute()
    {
        return $this->roles()->first();
    }

    public function coloaneSablonImplicit($section = null)
    {
        // get SectiuneAFM when created
        $table = Ofertare\ColoanaTabelAFM::getTableName();

        $query = Ofertare\ColoanaTabelAFM::select($table.'.*', 
            \DB::raw('CAST(JSON_VALUE(sablon_implicit.ordine_coloane, CONCAT(\'$.\', '.$table.'.nume)) as float) as ordine')
        );
        if($section) {
            $query->whereHas('sectiuni', function ($query) use ($section) {
                $query->where('nume', $section);
            });
        }
        $query->joinSub(
            $this->sabloaneAFM()->where('implicit', 1)->limit(1), 'sablon_implicit', function ($join) use ($table) {
                $join->on(\DB::raw('JSON_CONTAINS(sablon_implicit.coloane, JSON_QUOTE('.$table.'.nume), "$")'), '=', \DB::raw(1));
            }
        )->orderByRaw('CAST(JSON_VALUE(sablon_implicit.ordine_coloane, CONCAT(\'$.\', '.$table.'.nume)) as float)')
        ->orderBy($table.'.nume');
        // JOIN
        return $this->hasMany(Ofertare\ColoanaTabelAFM::class)->setQuery(
            $query->getQuery()
        );

        // SUBQUERY
        // return $this->hasMany(Ofertare\ColoanaTabelAFM::class)->setQuery(
        //     Ofertare\ColoanaTabelAFM::where('nume', function ($query) use ($table) {
        //         $query->selectRaw($table.'.nume')->whereRaw('JSON_CONTAINS(sablon_implicit.coloane, JSON_QUOTE('.$table.'.nume), "$") = 1')
        //             ->from($this->sabloaneAFM()->where('implicit', 1)->limit(1), 'sablon_implicit');
        //     })->getQuery()
        // );
    }

    public function getNumeCompletAttribute()
    {
        return $this->nume.' '.$this->prenume;
    }

    //          Local scopes
    //////////////////////////////////////

    public function scopeTehnicieni($query)
    {
        return $query->where('valid', 1)->where('agent', '>=', 1);
    }

    //        Custom functions
    //////////////////////////////////////

    public static function discount($cart, $func = 1)
    {
        switch ($func) {
            case 1:
                return self::discountPlataInAvans($cart);
            case 2:
                return self::discountFidelitate($cart);
            default:
                return self::discountPlataInAvans($cart);
        }
        return self::discountPlataInAvans($cart);
    }

    public static function discountPlataInAvans($cart)
    {
        $result = [
            'percent' => 0,
            'value' => 0
        ];
        if(
            isset($cart['items'])
            && DB::table('produse_categorii')
                ->whereIn('id_produs', array_keys($cart['items']))
                ->where('id_categorie', 133)
                ->count() < 1
        ) {
            $user = auth()->user();
            if($user && $user->reseller == '1') {
                $result['percent'] = setare('REDUCERE_PLATA_OP_RESELLER');
            } else {
                $result['percent'] = setare('REDUCERE_PLATA_OP_PF');
            }
            if($result['percent'] > 0 && ($cart['total'] ?? 0) > 0) {
                $result['value'] = round($result['percent']/100 * $cart['total'], 2);
            }
        }
        return $result;
    }

    public static function discountFidelitate($cart)
    {
        $result = [
            'percent' => 0,
            'value' => 0
        ];

        $user = auth()->user();
        if($user) {
            $result['percent'] = $user->discount_fidelitate;
            if($result['percent'] > 0 && ($cart['total'] ?? 0) > 0) {
                $result['value'] = ($result['percent']/100 * $cart['total']);
            }
        }
        return $result;
    }

    public function isAdmin()
    {
        return $this->admin == '1';
    }

    //      Validation parameters
    //////////////////////////////////////

    public static function getUserValidationParams($param = null, $id = null)
    {
        switch($param) {
            case 1:
                return static::rules();
            case 2:
                return static::messages();
            case 3:
                return static::names();
            default:
                return [
                    'rules' => static::rules($id),
                    'messages' => static::messages(),
                    'names' => static::names(),
                ];
        }
        return false;
    }

    public static function rules($id = null)
    {
        return [
            'tip' => ['required', 'integer', 'in:1,2'],
            'nume' => ['required', 'string', 'max:255'],
            'prenume' => ['required', 'string', 'max:255'],
            'cnp' => ['required', 'string', 'max:15', 'digits:13'],
            'telefon' => ['required', 'string', 'max:255'],
            'id_judet' => ['required', 'integer', Rule::exists(Judet::class, 'id') ],
            'id_localitate' => ['required', 'integer', Rule::exists(Localitate::class, 'id') ],
            'adresa' => ['required', 'string', 'max:255'],
            'user_email' => [
                'required',
                'string',
                'email',
                'max:100',
                $id
                    ? Rule::unique(User::class)->ignore($id)
                    : Rule::unique(User::class),
            ],
            'nume_firma' => ['nullable', 'required_if:tip,2', 'string', 'max:255'],
            'cui' => ['nullable', 'required_if:tip,2', 'string', 'max:255'],
            'nr_reg_comert' => ['nullable', 'required_if:tip,2', 'string', 'max:255'],
            'autorizatie_igpr' => ['nullable', 'string', 'max:255'],
            'password' => [$id ? 'nullable' : 'required', 'string', new Password, 'confirmed'],
            'terms' => $id ? ['nullable'] : ['required', 'accepted'],
            'newsletter' => ['nullable', 'integer', 'in:1'],
            'reseller_cerere' => ['nullable', 'integer', 'in:1'],
            'livrare_adresa_1' => ['nullable', 'integer', 'in:1'],
            'livrare_id_judet' => [
                'nullable',
                'integer',
                'required_if:livrare_adresa_1,1',
                'exclude_unless:livrare_adresa_1,1',
                Rule::exists(Judet::class, 'id')
            ],
            'livrare_id_localitate' => [
                'nullable',
                'integer',
                'required_if:livrare_adresa_1,1',
                'exclude_unless:livrare_adresa_1,1',
                Rule::exists(Localitate::class, 'id')
            ],
            'livrare_adresa' => [
                'nullable',
                'string',
                'required_if:livrare_adresa_1,1',
                'exclude_unless:livrare_adresa_1,1',
                'max:255'
            ],
        ];
    }

    public static function messages()
    {
        return [];
    }

    public static function names()
    {
        return [
            'id_judet' => __('judet'),
            'id_localitate' => __('localitate'),
            'user_email' => __('email'),
            'password' => __('password'),
            'terms' => __('termeni si conditii'),
            'livrare_adresa_1' => __('adresa de livrare diferita'),
            'livrare_id_judet' => __('judet livrare'),
            'livrare_id_localitate' => __('localitate livrare'),
            'livrare_adresa' => __('adresa de livrare'),
        ];
    }
}
