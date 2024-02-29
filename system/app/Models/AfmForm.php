<?php

namespace App\Models;

use App\Casts\FormatDate;
use App\Events\SendMails;
use App\Models\Ofertare\ColoanaTabelAFM;
use App\Models\Ofertare\Eveniment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class AfmForm extends Model
{
    use HasFactory;

    private static array $modelClassToTableMap = [];
    private static array $modelClassToPrimaryKeyMap = [];
    private static array $modelClassToTableSectionMap = [];

    protected $guarded = [];

    protected $table = 'fotovoltaice2021';

    protected $primaryKey = 'id';

    protected $section = '2021';

    protected $casts = [
        'putere_invertor' => 'float',
        'putere_panouri' => 'float',
        'capacitate_disjunctor' => 'float',
        'siruri_panouri' => 'array',
        // 'data_montare_panouri' => FormatDate::class,
        // 'data_montare_invertor_pif' => FormatDate::class,
    ];

    public $timestamps = false;

    public function __construct(string $section = null, array $attributes = array(), ?string $table = null, ?string $primaryKey = null)
    {
        parent::__construct($attributes);

        if (isset($table)) {
            // use table passed from AfmForm::table
            // used for info table
            $this->setTable($table);
            // used for info primary key
            $primaryKey ? $this->setKeyName($primaryKey) : null;
            // used for tables with section column
            $section ? $this->setModelSection($section) : null;
        } elseif (isset(self::$modelClassToTableMap[\get_class($this)])) {
            // restore used table from map while internally creating new instances
            $this->setTable(self::$modelClassToTableMap[\get_class($this)]);
            // restore used primaryKey from map while internally creating new instances
            isset(self::$modelClassToPrimaryKeyMap[\get_class($this)]) ? $this->setKeyName(self::$modelClassToPrimaryKeyMap[\get_class($this)]) : null;
            // restore used section from map while internally creating new instances
            isset(self::$modelClassToTableSectionMap[\get_class($this)]) ? $this->setModelSection(self::$modelClassToTableSectionMap[\get_class($this)]) : null;
        } elseif(static::validateSection($section)) {
            // change the table section
            if(method_exists($this, 'setModelSection')) {
                self::setModelSection($section);
            }
            $this->setTable('fotovoltaice'.(is_numeric($section) ? $section : '_'.str_replace([' ','-'],'_',$section)));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function judetDomiciliu()
    {
        return $this->belongsTo(Judet::class, 'judet_domiciliu');
    }

    public function localitateDomiciliu()
    {
        return $this->belongsTo(Localitate::class, 'localitate_domiciliu');
    }

    public function judetImobil()
    {
        return $this->belongsTo(Judet::class, 'judet_imobil');
    }

    public function localitateImobil()
    {
        return $this->belongsTo(Localitate::class, 'localitate_imobil');
    }

    public function fisier($coloana = 'fisier_vizita')
    {
        return $this->belongsTo(Fisier::class, $coloana);
    }

    public function info()
    {
        return $this->hasOne(AfmForm::table($this->getInfoTable()), 'id_formular');
    }

    public function firma()
    {
        return $this->belongsTo(Ofertare\Firma::class, 'firma_instalatoare');
    }

    public function programare()
    {
        return $this->hasOne(Ofertare\Programare::class, 'formular_id')->where('an', $this->getModelSection());
    }

    public function oferta()
    {
        return $this->belongsTo(Ofertare\Oferta::class, 'id_oferta');
    }

    // public function sectiuneAfm()
    // {
    //     return $this->belongsTo(Ofertare\SectiuneAFM::class, 'id_oferta');
    // }

    public function componenta()
    {
        return $this->belongsTo(Componenta::class, 'componente');
    }

    public function colaboratorAfm()
    {
        return $this->belongsTo(User::class, 'colaborator');
    }

    public function coproprietariFormular()
    {
        return $this->hasMany(DynamicModel::table($this->getCoproTable()), 'id_formular');
    }

    public function rateContract()
    {
        if(Schema::hasTable($this->getRateTable())) {
            return $this->hasMany(DynamicModel::table($this->getRateTable()), 'id_formular');
        }
        return null;
    }

    public function invertor()
    {
        return $this->hasOne(Ofertare\Invertor::class, 'marca', 'marca_invertor')
            // ->where('an', $this->getModelSection())
            ->where('marca', $this->marca_invertor)
            ->where('putere', $this->putere_invertor)
            ->where('tip', $this->tipul_bransamentului);
    }

    public function panou()
    {
        return $this->hasOne(Ofertare\Panou::class, 'marca', 'marca_panouri')
            // ->where('section', $this->getModelSection())
            ->where('putere', $this->putere_panouri)
            ->where('marca', $this->marca_panouri);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    */

    public function getTotalPuterePanouriAttribute()
    {
        return (($this->numar_panouri ?? 0) * ($this->putere_panouri ?? 0))/1000;
    }

    public function getValoareContractAttribute()
    {
        return $this->attributes['valoare_contract'] ?? (($this->contributie_afm ?? 0) + ($this->aport_propriu ?? 0));
    }

    public function getZileNastereAttribute()
    {
        if($this->data_nastere) {
            $nastere = Carbon::parse($this->data_nastere);
            $nastere->year(date('Y')); // date('Y', strtotime('+1 year'));
            return now()->isBefore($nastere)
                ? now()->diffInDays($nastere, false)
                : now()->diffInDays($nastere->year(date('Y', strtotime('+1 year'))), false);
        }
        return null;
    }

    public function getCoproprietariAttribute()
    {
        return $this->attributes['coproprietari'] ?? $this->coproprietariFormular()->get()->implode(function ($item) {
            return implode(';', $item->makeHidden(['id','id_formular', 'created_at', 'updated_at'])->toArray());
        }, '|');
    }

    public function getRateAttribute()
    {
        return $this->attributes['rate'] ?? ($this->rateContract() != null ? $this->rateContract->implode(function ($item) {
            return implode(' ', ['-', $item->contravaloare, 'RON']);
        }, "\n") : null);
    }

    public function getSumaRateAttribute()
    {
        return $this->rateContract() != null ? ($this->rateContract()->sum('contravaloare') ?? null) : null;
    }

    public function getValidareContravaloareRateAttribute()
    {
        return $this->attributes['validare_contravaloare_rate'] ?? ($this->suma_rate
            ? ($this->valoare_contract - $this->suma_rate == 0 ? 1 : 0) : null
        );
    }

    public function getBazaInvelitoareAttribute()
    {
        if(!empty($this->tipul_invelitorii)) {
            $value = strtolower($this->tipul_invelitorii);
            if(stripos($value, 'tigla') !== false) {
                return 'tigla';
            } elseif(stripos($value, 'tabla') !== false) {
                return 'tabla';
            } elseif(stripos($value, 'sandwich') !== false) {
                return 'sandwich';
            }
            return 'altele';
        }
        return null;
    }

    public function getNumeInvelitoareAttribute()
    {
        if(!empty($this->tipul_invelitorii)) {
            return str_replace(
                strtoupper($this->baza_invelitoare).': ', '',
                strtoupper($this->tipul_invelitorii)
            );
        }
        return null;
    }

    public function getAdresaDomiciliuAttribute()
    {
        return ($this->strada_domiciliu ? $this->strada_domiciliu : '')
            .($this->numar_domiciliu ? ', '.$this->numar_domiciliu : '')
            .($this->bloc_domiciliu ? ', '.$this->bloc_domiciliu : '')
            .($this->sc_domiciliu ? ', '.$this->sc_domiciliu : '')
            .($this->et_domiciliu ? ', '.$this->et_domiciliu : '')
            .($this->ap_domiciliu ? ', '.$this->ap_domiciliu : '');
    }

    public function getAdresaImobilAttribute()
    {
        return ($this->strada_imobil ? $this->strada_imobil : '')
            .($this->numar_imobil ? ', '.$this->numar_imobil : '')
            .($this->bloc_imobil ? ', '.$this->bloc_imobil : '')
            .($this->sc_imobil ? ', '.$this->sc_imobil : '')
            .($this->et_imobil ? ', '.$this->et_imobil : '')
            .($this->ap_imobil ? ', '.$this->ap_imobil : '');
    }

    public function getAdresaImobilCompletAttribute()
    {
        return ($this->nume_judet_imobil ? $this->nume_judet_imobil : '')
            .($this->nume_localitate_imobil ? ', '.$this->nume_localitate_imobil : '')
            .', '.$this->adresa_imobil;
    }

    public function getAdresaImobilScurtAttribute()
    {
        return ($this->nume_judet_imobil ? $this->nume_judet_imobil : '')
            .($this->nume_localitate_imobil ? ', '.$this->nume_localitate_imobil : '')
            .($this->strada_imobil ? ', Strada '.$this->strada_imobil : '')
            .($this->numar_imobil ? ', Nr. '.$this->numar_imobil : '');
    }

    public function getRegiuniAttribute()
    {
        return [
            'buc_ilfov' => [
                'camp' => 'judet_imobil',
                'valori' => [4, 24],
                'nume' => 'Bucuresti-Ilfov'
            ],
            'sud_muntenia' => [
                'camp' => 'judet_imobil',
                'valori' => [2, 13, 17, 21, 25, 32, 39],
                'nume' => 'Sud-Muntenia'
            ],
            'sud_vest' => [
                'camp' => 'judet_imobil',
                'valori' => [18, 19, 27, 31, 40],
                'nume' => 'Sud-Vest'
            ],
            'sud_est' => [
                'camp' => 'judet_imobil',
                'valori' => [8, 11, 15, 20, 41, 37],
                'nume' => 'Sud-Est'
            ],
            'nord_est' => [
                'camp' => 'judet_imobil',
                'valori' => [5, 9, 26, 30, 36, 42],
                'nume' => 'Nord Est'
            ],
            'vest' => [
                'camp' => 'judet_imobil',
                'valori' => [3, 14, 22, 38],
                'nume' => 'Vest'
            ],
            'nord_vest' => [
                'camp' => 'judet_imobil',
                'valori' => [6, 7, 12, 34, 35, 28],
                'nume' => 'Nord Vest'
            ],
            'centru' => [
                'camp' => 'judet_imobil',
                'valori' => [1, 10, 16, 23, 29, 33],
                'nume' => 'Centru'
            ],
            'partener_ialomita' => [
                'camp' => 'partener',
                'valori' => [1],
                'nume' => 'Partener Ialomita'
            ],
        ];
    }

    public function getNumeJudetImobilAttribute()
    {
        return $this->judetImobil ? $this->judetImobil->nume : '';
    }

    public function getNumeLocalitateImobilAttribute()
    {
        return $this->localitateImobil ? $this->localitateImobil->nume : '';
    }

    public function getNumeJudetDomiciliuAttribute()
    {
        return $this->judetDomiciliu ? $this->judetDomiciliu->nume : '';
    }

    public function getNumeLocalitateDomiciliuAttribute()
    {
        return $this->localitateDomiciliu ? $this->localitateDomiciliu->nume : '';
    }

    public function getDescriereComponentaAttribute()
    {
        return \Blade::render('<x-ofertare.columns.view.componente
            :item="$item"
            :column="$column"
            :all=true
        />', [
            'item' => $this,
            'column' => collect(['nume' => 'componente']),
        ]);
    }

    public function setCoproprietariAttribute($value)
    {
        $copro = explode('|', $value);
        static::table($this->getCoproTable(), $this->getCoproKeyName(), $this->getModelSection())
            ->where($this->getCoproForeignKeyName(), $this->id_formular)
            ->delete();
        if($copro && !empty($copro)) {
            foreach($copro as $c) {
                if(!empty($c)) {
                    $c = explode(';', $c);
                    $afm_copro = static::table($this->getCoproTable())::create([
                        'id_formular' => $this->id_formular,
                        'nume_copro' => $c[0] ?? '',
                        'prenume_copro' => $c[1] ?? '',
                        'cnp_copro' => $c[2] ?? '',
                        'domiciliu_copro' => $c[3] ?? '',
                    ]);
                }
            }
        }
        $this->attributes['coproprietari'] = $this->coproprietari;
    }

    public function setRateAttribute($value)
    {
        static::table($this->getRateTable(), $this->getRateKeyName(), $this->getModelSection())
            ->where($this->getRateForeignKeyName(), $this->id_formular)
            ->delete();
        if($value && !empty(array_filter($value))) {
            foreach($value ?? [] as $rata) {
                if(!empty(array_filter($rata))) {
                    $afm_rata = static::table($this->getRateTable())::create([
                        'id_formular' => $this->id_formular,
                        'contravaloare' => $rata['contravaloare'] ?? null,
                        'data_limita' => $rata['data_limita'] ?? null,
                        'data_platii' => $rata['data_platii'] ?? null,
                        'explicatii' => $rata['explicatii'] ?? null,
                        'validare_plata' => $rata['validare_plata'] ?? null,
                    ]);
                }
            }
        }
        $this->attributes['rate'] = $this->rate;
    }

    /*
    |--------------------------------------------------------------------------
    | Table related functions
    |--------------------------------------------------------------------------
    */

    public function getMainTable()
    {
        return 'fotovoltaice'.(
            is_numeric($this->getModelSection()) ? $this->getModelSection() : '_'.str_replace([' ','-'],'_',$this->getModelSection())
        );
        // return $this->getTable().'_info';
    }

    public function getInfoTable()
    {
        // return strpos($this->getTable(), '_info') === false
        //     ? $this->getTable().'_info'
        //     : $this->getTable();
        return $this->getMainTable().'_info';
    }

    public function getInfoKeyName()
    {
        return 'id_info';
    }

    public function getInfoForeignKeyName()
    {
        return $this->getInfoTable().'.id_formular';
    }

    public function getCoproTable()
    {
        // return strpos($this->getTable(), '_coproprietari') === false
        //     ? $this->getTable().'_coproprietari'
        //     : $this->getTable();
        return $this->getMainTable().'_coproprietari';
    }

    public function getCoproKeyName()
    {
        return 'id';
    }

    public function getCoproForeignKeyName()
    {
        return $this->getCoproTable().'.id_formular';
    }

    public function getRateTable()
    {
        // return strpos($this->getTable(), '_rate') === false
        //     ? $this->getTable().'_rate'
        //     : $this->getTable();
        return $this->getMainTable().'_rate';
    }

    public function getRateKeyName()
    {
        return 'id';
    }

    public function getRateForeignKeyName()
    {
        return $this->getRateTable().'.id_formular';
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public static function getMainTableName()
    {
        return (new static)->getMainTable();
    }


    public static function getInfoTableName()
    {
        // return strpos(static::getTableName(), '_info') === false
        //     ? static::getTableName().'_info'
        //     : static::getTableName();
        return static::getMainTableName().'_info';
    }

    public static function getCoproTableName()
    {
        // return strpos(static::getTableName(), '_coproprietari') === false
        //     ? static::getTableName().'_coproprietari'
        //     : static::getTableName();
        return static::getMainTableName().'_coproprietari';
    }

    public static function getRateTableName()
    {
        // return strpos(static::getTableName(), '_rate') === false
        //     ? static::getTableName().'_rate'
        //     : static::getTableName();
        return static::getMainTableName().'_rate';
    }

    public static function setSection(string $section = null)
    {
        if(static::validateSection($section)) {
            return new class($section, []) extends AfmForm {};
        }
        return new static;
    }

    public static function getSection()
    {
        return (new static)->getModelSection();
    }

    public static function table(string $table, string $primaryKey = null, string $section = null): self
    {
        return new class($section, [], $table, $primaryKey) extends AfmForm {};
    }

    public function setTable($table): self
    {
        self::$modelClassToTableMap[\get_class($this)] = $table;

        return parent::setTable($table);
    }

    public function setKeyName($primaryKey): self
    {
        self::$modelClassToPrimaryKeyMap[\get_class($this)] = $primaryKey;

        return parent::setKeyName($primaryKey);
    }

    public function getModelSection()
    {
        return (string) $this->section;
    }

    public function setModelSection(string $section): self
    {
        if(static::validateSection($section)) {
            self::$modelClassToTableSectionMap[\get_class($this)] = $section;
            $this->section = $section;
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Local scopes
    |--------------------------------------------------------------------------
    */

    public function scopeVisible($query)
    {
        $query->where('vizibilitate', 2)->where('arhivat', 0);
    }

    public function scopeCustomJoin($query, $table, $foreign_column, $param = [])
    {
        // create table alias from table name and column name
        // to avoid non unique table db error on join
        $table_alias = acronym($table).'_'.acronym($foreign_column);
        // add table alias to the columns retrieved (for the new name)
        $columns = preg_filter('/^/', $table_alias.'`.`', \Arr::wrap($param['retrieved_columns'] ?? ['nume']));
        // concatenate columns retrieved
        $columns = count($columns) > 1
            ? 'CONCAT(IFNULL(`'.implode("`, ''), '".($param['separator'] ?? ' ')."', IFNULL(`", $columns).'`, ""))'
            : 'IFNULL(`'.$columns[0].'`, "")';

        $query->leftJoin(
            $table.' AS '.$table_alias,
            ($param['join_table'] ?? $this->getTable()).'.'.$foreign_column, '=', $table_alias.'.'.($param['index'] ?? 'id')
        )->selectRaw($columns.' AS '.($param['alias'] ?? 'nume_'.$foreign_column));
    }

    public function scopeWithZileNastere($query, $alias = 'zile_nastere')
    {
        $column = '`'.$this->getTable().'`.`data_nastere`';
        $subquery = 'TIMESTAMPDIFF(DAY, CURRENT_TIMESTAMP,
            DATE_ADD('.$column.',
                INTERVAL
                (
                    TIMESTAMPDIFF(YEAR, '.$column.', CURRENT_TIMESTAMP)
                    + CASE WHEN DATE_ADD('.$column.', INTERVAL TIMESTAMPDIFF(YEAR, '.$column.', CURRENT_TIMESTAMP) YEAR) < CURRENT_TIMESTAMP
                    THEN 1 ELSE 0 END
                ) YEAR
            )
        )';
        $query->selectRaw('(CASE WHEN '.$column.' IS NOT NULL THEN '.$subquery.' ELSE NULL END) AS '.$alias);
    }

    public function scopeWithValoareContract($query, $alias = 'valoare_contract')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $query->selectRaw('IFNULL(`'.$this->getTable().'`.`aport_propriu`, 0) + IFNULL(`'.$this->getTable().'`.`contributie_afm`, 0) AS '.$alias);
    }

    public function scopeWithInfo($query)
    {
        // if the table was not joined and the model table is different from the join table
        if(!collect($query->getQuery()->joins)->pluck('table')->contains($this->getInfoTable()) && $query->getQuery()->from != $this->getInfoTable()) {
            $key = $this->getTable() === $this->getMainTable() ? 'id' : 'id_formular';
            $query->leftJoin($this->getInfoTable(), $this->getTable().'.'.$key, '=', $this->getInfoForeignKeyName());
        }
    }

    public function scopeWithTotalPuterePanouri($query, $alias = 'total_putere_panouri')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $info_table = $this->getInfoTable();
        $query->selectRaw('ROUND((IFNULL(`'.$info_table.'`.`numar_panouri`, 0) * IFNULL(`'.$info_table.'`.`putere_panouri`, 0))/1000,2) AS '.$alias);
    }

    public function scopeWithVerificareSiruri($query, $alias = 'verificare_siruri')
    {

        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $info_table = $this->getInfoTable();
        $query->selectSub(
            // can't be run on versions earlier than MariaDB 10.6.*
            /*DB::table('siruri_json')->selectRaw('CASE WHEN SUM(sir) == `numar_panouri` THEN 1 ELSE 0')->fromRaw('JSON_TABLE(REPLACE(siruri_panouri, "1x", ""), "$[*]" COLUMNS (sir TINYINTEGER PATH "$")) siruri_json'),*/
            // MariaDB version using SEQUENCE Engine
            DB::table('siruri_json')->selectRaw('CASE WHEN SUM(JSON_EXTRACT(REPLACE(`'.$info_table.'`.`siruri_panouri`, "1x", ""), CONCAT(\'$[\', seq, \']\'))) = `'.$info_table.'`.`numar_panouri` THEN 1 ELSE 0 END')
                ->from('seq_0_to_100 AS siruri_json')->where('siruri_json.seq', '<', DB::raw('JSON_LENGTH(`'.$info_table.'`.`siruri_panouri`)')),
            $alias
        );
    }

    public function scopeWithOnlyRegiune($query, $alias = 'regiune', $camp = null)
    {
        $conditions = '';
        foreach ($this->regiuni as $valoare => $attr) {
            $camp = $camp ?? $attr['camp'];
            $conditions .= ' WHEN '.$camp.' IN ('.implode(', ', $attr['valori']).') THEN "'.$valoare.'"';
        }
        return $query->selectRaw('CASE '.$conditions.' ELSE "" END AS '.$alias);
    }

    public function scopeWithRegiune($query, $alias = 'regiune', $camp = null)
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $query->withOnlyRegiune($alias, $camp);
    }

    public function scopeWithVerificareReducere($query, $alias = 'verificare_reducere')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $subquery = DB::table(OfertareForm::getTableName(), 'om')->selectRaw('1')
            ->where('om.an', '=', $this->getModelSection())
            ->whereColumn('om.email', '=', $this->getTable().'.email')
            ->whereColumn('om.telefon', '=', $this->getTable().'.telefon')
            ->whereColumn('om.cod', '=', $this->getInfoTable().'.cod_verificare_reducere')
            ->limit(1);

        $query->selectRaw('CASE WHEN EXISTS ('.$subquery->toRawSql().') THEN 1 ELSE 0 END AS '.$alias);
    }

    public function scopeWithCopro($query)
    {
        if(!collect($query->getQuery()->joins)->pluck('table')->contains($this->getCoproTable()) && $query->getQuery()->from != $this->getCoproTable()) {
            $copro_table = $this->getCoproTableName();
            $copro_foreign_key = $this->getCoproForeignKeyName();
            $key = $this->getTable() === $this->getMainTable() ? 'id' : 'id_formular';
            // $query->leftJoin($this->getCoproTableName(), $this->getTable().'.id', '=', $this->getCoproTableName().'.id_formular');
            $query->leftJoinSub(DB::table($copro_table)->select($copro_foreign_key)
                ->groupBy($copro_foreign_key), $copro_table, function($join) use ($copro_foreign_key, $key) {
                    $join->on($this->getTable().'.'.$key, '=', $copro_foreign_key);
                });
        }
    }

    public function scopeWithFullCopro($query)
    {
        $query->withExistaCopro()->withCoproprietari()->withCopro();
    }

    public function scopeWithExistaCopro($query, $alias = 'exista_copro')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $query->selectRaw('(CASE WHEN `'.$this->getCoproTableName().'`.`id_formular` IS NULL THEN NULL ELSE 1 END) AS '.$alias);
    }

    public function scopeWithCoproprietari($query, $alias = 'coproprietari')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $copro_table = $this->getCoproTableName();
        $columns = implode(", ';', ", array_diff(Schema::getColumnListing($this->getCoproTableName()), ['id','id_formular', 'created_at', 'updated_at']));
        $query->selectSub(
            DB::table($copro_table)->selectRaw('( CASE WHEN `'.$copro_table.'`.`id_formular` IS NOT NULL THEN GROUP_CONCAT(CONCAT('.$columns.') SEPARATOR \'|\') ELSE NULL END )')->whereColumn($this->getTable().'.id', '=', $copro_table.'.id_formular')->groupBy($copro_table.'.id_formular'),
            $alias
        );
    }

    public function scopeWithRate($query, $alias = 'rate')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $rate_table = $this->getRateTableName();
        // $columns = implode(", ';', ", array_diff(Schema::getColumnListing($rate_table), ['id','id_formular', 'created_at', 'updated_at']));
        $columns = '"- ", `contravaloare`, " RON"';
        $query->selectSub(
            DB::table($rate_table)->selectRaw('( CASE WHEN `'.$rate_table.'`.`id_formular` IS NOT NULL THEN GROUP_CONCAT(CONCAT('.$columns.') SEPARATOR "\\n") ELSE NULL END )')->whereColumn($this->getTable().'.id', '=', $rate_table.'.id_formular')->groupBy($rate_table.'.id_formular'),
            $alias
        );
    }

    public function scopeWithValidareContravaloareRate($query, $alias = 'validare_contravaloare_rate')
    {
        if (is_null($query->getQuery()->columns)) {
            $query->select('*', $this->getTable().'.id');
        }
        $rate_table = $this->getRateTableName();
        $subquery = 'CASE WHEN SUM(`'.$rate_table.'`.`contravaloare`) - (IFNULL(`'.$this->getTable().'`.`aport_propriu`, 0) + IFNULL(`'.$this->getTable().'`.`contributie_afm`, 0)) = 0 THEN 1 ELSE 0 END';
        $query->selectSub(
            DB::table($rate_table)->selectRaw('( CASE WHEN `'.$rate_table.'`.`id_formular` IS NOT NULL THEN ('.$subquery.') ELSE NULL END )')
                ->whereColumn($this->getTable().'.id', '=', $rate_table.'.id_formular')
                ->groupBy($rate_table.'.id_formular'),
            $alias
        );
    }

    public function scopeWithEchipaMontaj($query, $alias = 'echipa_montaj')
    {
        $info_table = $this->getInfoTableName();
        $users_table = (new User)->getTable();
        $columns = '`nume`, " ", `prenume`, " - ", `ep_em`.`procent`, CASE WHEN `ep_em`.`lider` IS NOT NULL THEN " (Lider)" ELSE "" END';
        // $query->leftJoin(Ofertare\Programare::getTableName().' AS p_em', $this->getTable().'.id', '=', 'p_em.formular_id')
        $query->leftJoin(Ofertare\Programare::getTableName().' AS p_em', function($join) {
            $join->on($this->getTable().'.id', '=', 'p_em.formular_id')
                ->where('p_em.an', $this->getModelSection());
        })->selectSub(
            DB::table($users_table)->selectRaw('( CASE WHEN `ep_em`.`programare_id` IS NOT NULL THEN GROUP_CONCAT(CONCAT('.$columns.') SEPARATOR "\\n") ELSE NULL END )')
                ->leftJoin('echipa_programare AS ep_em', $users_table.'.id', '=', 'ep_em.user_id')
                ->whereColumn('p_em.id', '=', 'ep_em.programare_id')
                ->groupBy('ep_em.programare_id'),
            $alias
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validation parameters
    |--------------------------------------------------------------------------
    */

    public static function ruleSection($key = 'an')
    {
        return [
            $key => ['required', 'string', 'in:2021,2023,fonduri-proprii'],
        ];
    }

    public static function ruleValidAfmForm($key = 'formular_id', $key_section = 'an')
    {
        return [
            $key => ['required', 'max:255',
                Rule::exists(AfmForm::setSection(request()->input($key_section))->getTable(), 'id')
            ],
        ];
    }

    public static function validateSection($value)
    {
        $validator = Validator::make(['an' => (string) $value], static::ruleSection());
        return $validator->passes();
    }

    /*
    |--------------------------------------------------------------------------
    | Custom functions
    |--------------------------------------------------------------------------
    */

    /*
     * $column - Ofertare\ColoanaTabelAFM::class|array
     * $id - form id
     * $type - 1: for table view / 2: for export
     */
    public function getCustomColumnTypeValues($column, $type = 1, $id = null, $section = null)
    {
        $result = [];
        $id = $id ?? $this->id;
        $section = $section ?? $this->getModelSection();
        $default_values = $column['default_values'] ?? ['' => ''];
        // used for checking if there are more than 1 default value
        // because excel does not accept more than one link per cell
        $count = count($default_values);
        foreach($default_values as $value => $name) {
            // common route parameters
            $route_params = [
                'section' => $section,
                'formular' => $id,
            ];
            if($column['nume'] == 'anexa_2_distribuitor_energie') {
                $params = [
                    'titlu' => $value == 'Imputernicire' ? $value : 'Notificare racordare '.$name,
                    'color' => $value == 'Imputernicire' ? 'yellow' : 'purple',
                    'icon' => 'fa fa-file-pdf-o',
                    'url' => route('ofertare.afm.generate.notificare.racordare', $route_params + [
                        'distribuitor' => $value,
                    ]),
                ];
            } elseif($column['nume'] == 'pif_distribuitor') {
                $params = [
                    'titlu' => 'Proces verbal PIF '.$name,
                    'color' => 'purple',
                    'icon' => 'fa fa-file-pdf-o',
                    'url' => route('ofertare.afm.generate.proces.verbal.pif', $route_params + [
                        'distribuitor' => $value,
                    ]),
                ];
            } elseif($column['nume'] == 'schema_monofilara') {
                $params = [
                    'titlu' => 'Generare monofilara',
                    'color' => 'yellow-mint',
                    'icon' => 'fa fa-map',
                    'url' => route('ofertare.afm.generate.monofilara', $route_params),
                ];
            } elseif($column['nume'] == 'dosar_reglaje_pram') {
                $params = [
                    'titlu' => 'Generare dosar, reglaje, pram',
                    'color' => 'grey-mint',
                    'icon' => 'fa fa-file-pdf-o',
                    'url' => route('ofertare.afm.generate.dosar.reglaje.pram', $route_params),
                ];
            } elseif($column['nume'] == 'link_cerere') {
                $params = [
                    'titlu' => 'Link cerere',
                    'color' => 'green-dark',
                    'icon' => 'fa fa-file-pdf-o',
                    'url' => old('OLD_SITE_NAME').'descarca_cerere_afm/'.old_enc_dec($id),
                ];
            } else {
                $params = [
                    'titlu' => 'Nedefinit',
                    'color' => 'red',
                    'icon' => 'fa fa-close',
                    'url' => 'javascript:void(0)',
                    'empty' => true,
                ];
            }
            // return anchor tag if it is for view
            // return link if it is only one value
            // return list of url if it is more then one value
            $result[] = $type == 1
                ? '<a href="'.$params['url'].'" class="btn btn-sm '.$params['color'].'"><i class="'.$params['icon'].'"></i>'.$params['titlu'].'</a>'
                : (isset($params['empty']) ? '' : ($count > 1 ? $params['url'] : '=HYPERLINK("'.$params['url'].'", "Link")'));
        }
        return implode("\n", $result);
    }

    public static function customActionsPerColumn(Request $request, $columns_edited, $section, $formular, $old_afm = null)
    {
        try {
            $user = AfmForm::setSection($section)->select('id','nume','prenume','email')->withRegiune('regiune', 'judet_domiciliu')->find($formular);
            $user = $user ? $user->toArray() : [];

            $columns = ColoanaTabelAFM::whereIn('nume', $columns_edited)->get();

            $afm_model = static::setSection($section);

            foreach($columns as $column) {
                $value = $request->input($column->nume);
                $value = is_array($value) ? json_encode($value) : $value;

                // save changes in history table
                DynamicModel::table('istoric_modificari')->create([
                    'user_id' => auth()->id(),
                    'text' => 'Utilizatorul #'.auth()->id().' '.auth()->user()->nume.' '.auth()->user()->prenume.' a modificat campul '.$column->nume.' cu valoarea '.$value.' de la formularul afm '.$section.' #'.$formular,
                ]);

                // custom actions per column
                if(in_array($column->nume, ['cnp', 'preverificare_dosar', 'status_aprobare_dosar']) && empty($afm_old[$column->nume])) {

                    if($column->nume == 'preverificare_dosar') {
                        $eveniment = $value == 'aprobare' ? 'propuse pentru aporbare' : 'propuse pentru respingere';
                    } elseif($column->nume == 'status_aprobare_dosar') {
                        $eveniment = $value == 'dosar admis' ? 'dosare aprobate' : 'dosare respinse';
                    } else {
                        $eveniment = 'clienti noi';
                    }

                    Eveniment::create([
                        'an' => $section,
                        'tabel' => $column->nume == 'cnp'
                            ? $afm_model->getTableName()
                            : $afm_model->getInfoTableName(),
                        'tabel_id' => $formular,
                        'user_id' => auth()->id(),
                        'eveniment' => $eveniment,
                        'coloana' => $column->nume,
                        'valoare' => $value,
                    ]);
                }

                if(in_array($column->nume, ['componente', 'id_oferta']) && !empty($value)) {

                    if($column->nume === 'componente') {
                        $data = Componenta::find($value)->toArray();
                    } else {
                        $oferta = Ofertare\OfertaAFM::with('invertor','panou','oferta.produse')
                            ->where('oferta_id', $value)->where('section', $section)
                            ->first();
                        $contributie_afm = $section !== 'fonduri-proprii' ? 20000 : 0;
                        $data = [
                            'contributie_afm' => $contributie_afm,
                            'aport_propriu' => $oferta->oferta->calculateAportPropriu($contributie_afm),
                        ] + affix_array_keys($oferta->panou ? $oferta->panou->toArray() : [], '_panouri')
                            + affix_array_keys($oferta->invertor ? $oferta->invertor->toArray() : [], '_invertor')
                            + $oferta->toArray();
                    }


                    static::table($afm_model->getInfoTable(), $afm_model->getInfoKeyName(), $section)
                        ->where($afm_model->getInfoForeignKeyName(), $formular)
                        ->update(
                            collect($data)->only([
                                'marca_invertor', 'putere_invertor',
                                'marca_panouri', 'numar_panouri', 'putere_panouri',
                                'tip_acumulatori', 'capacitate_acumulatori',
                            ])->toArray()
                        );

                    $afm_model->where('id', $formular)->update(
                        collect($data)->only([
                            'aport_propriu', 'contributie_afm', 'tipul_bransamentului'
                        ])->toArray()
                    );
                }

                if(in_array($column->nume, ['cod_verificare_reducere'])) {
                    // if "aport_propriu" > 0 and "cod_verificare_reducere" is correct
                    $afm = $afm_model->withInfo()->withVerificareReducere()->find($formular);

                    // 2000 - minimum value for "aport propriu"
                    // 5 - 5% discount
                    if(!empty($afm->aport_propriu) && $afm->aport_propriu > 2000 && !empty($afm->verificare_reducere)) {
                        $value = round($afm->aport_propriu - ($afm->aport_propriu * 5/100));
                        $afm->aport_propriu = $value < 2000 ? 2000 : $value;
                        $afm->save();
                    }
                }

                if(in_array($column->nume, ['status_aprobare_dosar', 'preverificare_dosar'])) {

                    // get the user in charge for this region
                    $afm = AfmForm::setSection($section)->withInfo()->find($formular);
                    if(($responsabil = Judet::getResponsabilRegiune($user['regiune'])) || ($afm && $afm->colaboratorAfm)) {
                        if($afm && $afm->colaboratorAfm) {
                            $responsabil = $afm->colaboratorAfm;
                        }

                        $info_mail['email_responsabil'] = $info_mail['cc'] = $responsabil['user_email'];
                        $info_mail['nume_responsabil'] = $responsabil['prenume'].' '.$responsabil['nume'];
                        $info_mail['telefon_responsabil'] = $responsabil['telefon'];

                        if($column->nume == 'preverificare_dosar') {
                            $user['email'] = $info_mail['email_responsabil']; // adresa la care va fi trimis emalul
                            $info_mail['cc'] = null;
                        }
                    } elseif($column->nume == 'preverificare_dosar') {
                        $user['email'] = null;
                    }
                }

                if(in_array($column->nume, ['id_oferta', 'componente', 'numar_panouri', 'putere_panouri','schita_amplasare_panouri'])) {
                    static::table($afm_model->getInfoTable(), $afm_model->getInfoKeyName(), $section)
                        ->where($afm_model->getInfoForeignKeyName(), $formular)
                        ->update(['verificare_schita_panouri' => 0]);
                }

                if($section != 2021 && preg_match('/^(.*)domiciliu$/', $column->nume, $matches)) {
                    static::setSection($section)->where('id', $formular)->update([
                        ($matches[1] == 'scara_' ? 'sc_' : $matches[1]).'imobil' => $value
                    ]);

                    // if(auth()->id() == '413') {
                    //     AfmForm::setSection(2023)->whereNull('judet_imobil')->update([
                    //         'judet_imobil' => \DB::raw('`judet_domiciliu`'),
                    //         'localitate_imobil' => \DB::raw('`localitate_domiciliu`'),
                    //         'strada_imobil' => \DB::raw('`strada_domiciliu`'),
                    //         'numar_imobil' => \DB::raw('`numar_domiciliu`'),
                    //         'bloc_imobil' => \DB::raw('`bloc_domiciliu`'),
                    //         'sc_imobil' => \DB::raw('`scara_domiciliu`'),
                    //         'et_imobil' => \DB::raw('`et_domiciliu`'),
                    //         'ap_imobil' => \DB::raw('`ap_domiciliu`'),
                    //     ]);
                    // }
                }

                if($section === 'fonduri-proprii' && $column->nume === 'cnp' && strlen((string) $value) > 6) {
                    static::setSection($section)->where('id', $formular)->update([
                        'data_nastere' => Carbon::createFromFormat('ymd', substr((string) $value, 1, 6))->format('Y-m-d')
                    ]);

                }

                // send mail depending of the column
                try {
                    if($template = $column->getColumnMailTemplate($value)) {
                        SendMails::dispatch($user, [
                            'id' => $formular,
                            'nume' => $user['nume'],
                            'prenume' => $user['prenume'],
                            'template' => $template,
                            'utilizator_actiune' => auth()->user()->nume.' '.auth()->user()->prenume, // userul care a facut actiunea
                            'additional_info' => $request->input('mail_body') ?? ''
                        ] + ($info_mail ?? []), [0]);
                    }
                } catch(\Exception $e) { \Log::info($e); }
            }
        } catch(\Exception $e) { \Log::info($e); }
    }

    public static function prepareQueryData($coloane, $conditions = [], $section = null)
    {
        $section = $section ?? static::getSection();

        $table = AfmForm::setSection($section)->getTableName();
        $info_table = AfmForm::setSection($section)->getInfoTableName();
        // $permisiuni = $this->session->userdata('permisiuni');
        $permisiuni = array_flip(auth()->user()->getAllPermissions()->pluck('name','id')->all());
        $coloane = \Arr::wrap($coloane);

        // if it doesn't have permission to access all table columns
        if(!isset($permisiuni['afm.2021.*'])) {
            $coloane_permise = null;
            preg_match_all(
                '/afm\.[^\.]*\.((?!generare[^\.]*|buton[^\.]*|mail[^\.]*|view|\*)[^\.]*)(?:\.view);?/',
                implode(';',array_keys($permisiuni)).';',
                $coloane_permise
            );

            $coloane_permise = $coloane_permise[1] ?? [];
            $coloane = array_intersect($coloane, $coloane_permise);
        }

        $coloane = ColoanaTabelAFM::whereIn('nume', $coloane)
            ->orderByRaw('FIELD(nume, "'.implode('", "', $coloane).'")')
            ->get();

        $coloane_afisare = $coloane;

        // for columns that are not found in database
        $coloane_db = $coloane->where('tip', '<>', '9')->pluck('nume')->toArray();

        // for columns that are not saved in database
        // but are composed from 'n' diferent columns
        $coloane_necesare = [];
        foreach ($coloane as $coloana) {
            if(isset($coloana['rules']['callback']['columns'])) {
                $coloane_necesare += array_flip($coloana['rules']['callback']['columns']);
            }
            if(isset($coloana['rules']['edit_without']) && !isset($coloane_necesare[$coloana['rules']['edit_without']])) {
                $coloane_necesare[$coloana['rules']['edit_without']] = 0;
            }
        }

        // add conditions columns if they are not selected
        $coloane_necesare = is_array($coloane_necesare) && is_array($conditions)
            ? $coloane_necesare + $conditions + (isset($conditions['or']) && is_array($conditions['or']) ? $conditions['or'] : [])
            : $coloane_necesare;
        if($coloane_necesare != []) {
            $coloane_db = array_keys(array_flip($coloane_db) + $coloane_necesare);
        }

        // add scopes and joins for query
        $coloane_necesare = ColoanaTabelAFM::whereIn('nume', $coloane_db)
            ->orderByRaw('FIELD(nume, "'.implode('", "', $coloane_db).'")')
            ->get();

        // reset db columns in case a custom condition is added that is not a column name
        $coloane_db = [];
        $scopes = [];
        $joins = [];
        foreach($coloane_necesare as $index => $coloana) {
            // add table name to columns
            $coloane_db[$index] = $coloana->getColumnTable($section).'.'.$coloana->nume;

            // remove columns that are retrieved from scopes
            if(isset($coloana['rules']['scope'])) {
                $scopes[] = $coloana['rules']['scope'] === true
                    ? \Str::camel('with_'.$coloana['nume'])
                    : $coloana['rules']['scope'];
                unset($coloane_db[$index]);
            }

            if(isset($coloana['rules']['db']['table']) && !isset($coloana['rules']['relationship'])) {
                $joins[] = [
                    'tabel' => $coloana['rules']['db']['table'],
                    'join_tabel' => $coloana->getColumnTable($section),
                    'coloane_concat' => $coloana['rules']['db']['cols'] ?? null,
                    'coloana' => $coloana['nume'],
                ];
            }
            if($coloana['tip'] == '7') {
                $joins[] = [
                    'tabel' => (new Fisier)->getTable(),
                    'join_tabel' => $coloana->getColumnTable($section),
                    'coloane_concat' => ['path','name'],
                    'separator' => '',
                    'coloana' => $coloana['nume'],
                ];
            }
        }
        // $this->scopes = $scopes;
        // $this->joins = $joins;

        // add table to the 'id' column
        // or add the column if it is not in the array of columns
        $temp = array_flip($coloane_db);
        if(!isset($temp[$table.'.id'])) {
            $coloane_db[] = $table.'.id';
        }

        // $this->coloane_db = $coloane_db;
        // $this->coloane_necesare = $coloane_necesare;

        return [
            'coloane' => $coloane_afisare,
            'coloane_db' => $coloane_db,
            'coloane_necesare' => $coloane_necesare,
            'scopes' => $scopes,
            'joins' => $joins,
        ];
    }

    public static function getQueryFromData($query_data = [], $conditions = [], $section = null)
    {
        $coloane_db = $query_data['coloane_db'] ?? $query_data['coloane'];
        $coloane_necesare = $query_data['coloane_necesare'] ?? $coloane_db;
        $scopes = $query_data['scopes'] ?? [];
        $joins = $query_data['joins'] ?? [];
        $section = $section ?? static::getSection();


        $table = AfmForm::setSection($section)->getTableName();
        // visible scope includes arhivat
        $query = AfmForm::setSection($section)->distinct()->visible()->select(...$coloane_db);
        if($coloane_necesare->where('tabel', 'info')->count() > 0) {
            $query->withInfo();
        }
        if($coloane_necesare->where('tabel', 'copro')->count() > 0) {
            $query->withCopro();
        }
        foreach($scopes as $scope) {
            $query->{$scope}();
        }
        foreach($joins as $join) {
            $query->customJoin($join['tabel'], $join['coloana'], [
                'retrieved_columns' => $join['coloane_concat'] ?? null,
                'join_table' => $join['join_tabel'] ?? null,
                'separator' => $join['separator'] ?? null,
            ]);
        }

        // $conditions = $query;
        if($conditions != [] && is_array($conditions)) {
            if(isset($conditions['formular'])) {
                $query->whereIn($table.'.id', \Arr::wrap($conditions['formular']));
                unset($conditions['formular']);
            }
            if(
                isset($conditions['order_by']['column'])
                && $coloana_tabel = $coloane_necesare->where('nume', $conditions['order_by']['column'])->first()
            ) {
                $coloana_cu_tabel = isset($coloana_tabel['rules']['scope'])
                    ? $conditions['order_by']['column']
                    : $coloana_tabel->getColumnTable($section).'.'.$conditions['order_by']['column'];
                $query->orderBy($coloana_cu_tabel, $conditions['order_by']['order']);
                unset($conditions['order_by']);
            }
            if(isset($conditions['or'])) {
                $or_conditions = $conditions['or'];
                unset($conditions['or']);
            }
            $query = static::addConditionsToQuery($query, $coloane_necesare, $conditions, $section);
            if(isset($or_conditions)) {
                $query->where(function($subquery) use($coloane_necesare, $or_conditions, $section) {
                    static::addConditionsToQuery($subquery, $coloane_necesare, $or_conditions, $section, false);
                });
            }
            // foreach ($conditions as $coloana => $val) {
            //     if($coloana_tabel = $coloane_necesare->where('nume', $coloana)->first()) {
            //         // don't add table name if the column is created from a scope/join
            //         $coloana_cu_tabel = isset($coloana_tabel['rules']['scope'])
            //             ? $coloana
            //             : $coloana_tabel->getColumnTable($section).'.'.$coloana;
            //         if(isset($coloana_tabel->rules['relationship']['name']) && isset($coloana_tabel->rules['relationship']['table'])) {
            //             $rel = $coloana_tabel->rules['relationship'];
            //             $query->whereHas($rel['name'], function ($query) use ($val, $rel) {
            //                 $query->whereIn($rel['table'].'.id', \Arr::wrap($val));
            //             });
            //         } elseif($coloana_tabel->tip == '7') { // file conditions
            //             if(is_array($val)) {
            //                 $query->where(function($subquery) use($coloana_cu_tabel, $val) {
            //                     $func = 'where';
            //                     if(isset($val['empty'])) {
            //                         $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
            //                         $func = 'orWhere';
            //                     }
            //                     if(isset($val['not_empty'])) {
            //                         $subquery->{$func}($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
            //                         $func = 'where';
            //                     }
            //                 });
            //             } elseif(!empty($val)) {
            //                 $query->where(function($subquery) use($coloana_cu_tabel) {
            //                     $subquery->where($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
            //                 });
            //             }
            //         } elseif($coloana_tabel->tip == '3') { // date conditions
            //             if(is_array($val)) {
            //                 $query->where(function($subquery) use($coloana_cu_tabel, $val) {
            //                     $func = 'where';
            //                     if(isset($val['empty'])) {
            //                         $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
            //                         $func = 'orWhere';
            //                     }
            //                     if(isset($val['not_empty'])) {
            //                         $subquery->{$func}($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
            //                         $func = 'where';
            //                     }
            //                     if(isset($val['start']) && $val['start']) {
            //                         $subquery->{$func}($coloana_cu_tabel, '>=', $val['start']);
            //                         $func = 'where';
            //                     }
            //                     if(isset($val['end']) && $val['end']) {
            //                         $subquery->{$func}($coloana_cu_tabel, '<=', $val['end']);
            //                     }
            //                 });
            //             } elseif(isset($conditions[$coloana.'_sign'])){
            //                 $query->where($coloana_cu_tabel, $conditions[$coloana.'_sign'], $val);
            //             } else {
            //                 $query->where($coloana_cu_tabel, 'like', $val.'%');
            //             }
            //         } else { // text, textarea, number, select conditions
            //             if(is_array($val)) {
            //                 $flipped_val = array_flip($val);
            //                 $query->where(function($subquery) use($coloana_cu_tabel, $val, $flipped_val) {
            //                     $func = 'whereIn';
            //                     if(isset($flipped_val['']) || isset($val['empty'])) {
            //                         $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
            //                         $func = 'orWhereIn';
            //                         unset($val['empty']);
            //                     }
            //                     if(isset($val['not_empty'])) {
            //                         $subquery->whereNotNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '<>', '');
            //                         $func = 'orWhereIn';
            //                         unset($val['not_empty']);
            //                     }
            //                     if(count($val) > 0) {
            //                         $subquery->{$func}($coloana_cu_tabel, $val);
            //                     }
            //                 });
            //             } else {
            //                 $func = isset($coloana_tabel->rules['scope']) ? 'having' : 'where';
            //                 if(in_array($coloana_tabel->tip, ['2', '5', '6', '7'])) {
            //                     $query->{$func}($coloana_cu_tabel, $val);
            //                 } else {
            //                     $query->{$func}($coloana_cu_tabel, 'like', '%'.$val.'%');
            //                 }
            //             }
            //         }
            //     }
            // }
        }

        return $query;
    }

    public static function addConditionsToQuery($query, $coloane_necesare, $conditions = [], $section = null, $and = true)
    {
        foreach ($conditions as $coloana => $val) {
            if($coloana_tabel = $coloane_necesare->where('nume', $coloana)->first()) {
                // don't add table name if the column is created from a scope/join
                $coloana_cu_tabel = isset($coloana_tabel['rules']['scope'])
                    ? $coloana
                    : $coloana_tabel->getColumnTable($section).'.'.$coloana;

                if(isset($coloana_tabel->rules['relationship']['name']) && isset($coloana_tabel->rules['relationship']['table'])) {
                    $rel = $coloana_tabel->rules['relationship'];
                    $main_func = $and || is_null($query->getQuery()->wheres)
                        ? 'whereHas' : 'orWhereHas';

                    $query->{$main_func}($rel['name'], function ($query) use ($val, $rel) {
                        $query->whereIn($rel['table'].'.id', \Arr::wrap($val));
                    });
                } elseif($coloana_tabel->tip == '7') { // file conditions
                    $main_func = $and || is_null($query->getQuery()->wheres)
                        ? 'where' : 'orWhere';
                    if(is_array($val)) {
                        $query->{$main_func}(function($subquery) use($coloana_cu_tabel, $val) {
                            $func = 'where';
                            if(isset($val['empty'])) {
                                $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
                                $func = 'orWhere';
                            }
                            if(isset($val['not_empty'])) {
                                $subquery->{$func}($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
                                $func = 'where';
                            }
                        });
                    } elseif(!empty($val)) {
                        $query->{$main_func}(function($subquery) use($coloana_cu_tabel) {
                            $subquery->where($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
                        });
                    }
                } elseif($coloana_tabel->tip == '3') { // date conditions
                    $main_func = $and || is_null($query->getQuery()->wheres)
                        ? 'where' : 'orWhere';
                    if(is_array($val)) {
                        $query->{$main_func}(function($subquery) use($coloana_cu_tabel, $val) {
                            $func = 'where';
                            if(isset($val['empty'])) {
                                $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
                                $func = 'orWhere';
                            }
                            if(isset($val['not_empty'])) {
                                $subquery->{$func}($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
                                $func = 'where';
                            }
                            if(isset($val['start']) && $val['start']) {
                                $subquery->{$func}($coloana_cu_tabel, '>=', $val['start']);
                                $func = 'where';
                            }
                            if(isset($val['end']) && $val['end']) {
                                $subquery->{$func}($coloana_cu_tabel, '<=', $val['end']);
                            }
                        });
                    } elseif(isset($conditions[$coloana.'_sign'])){
                        $query->{$main_func}($coloana_cu_tabel, $conditions[$coloana.'_sign'], $val);
                    } else {
                        $query->{$main_func}($coloana_cu_tabel, 'like', $val.'%');
                    }
                } else { // text, textarea, number, select conditions
                    if(is_array($val)) {
                        $flipped_val = array_flip($val);
                        $main_func = $and || is_null($query->getQuery()->wheres)
                            ? 'where' : 'orWhere';
                        $query->{$main_func}(function($subquery) use($coloana_cu_tabel, $val, $flipped_val) {
                            $func = 'whereIn';
                            if(isset($flipped_val['']) || isset($val['empty'])) {
                                $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
                                $func = 'orWhereIn';
                                unset($val['empty']);
                            }
                            if(isset($val['not_empty'])) {
                                $subquery->whereNotNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '<>', '');
                                $func = 'orWhereIn';
                                unset($val['not_empty']);
                            }
                            if(count($val) > 0) {
                                $subquery->{$func}($coloana_cu_tabel, $val);
                            }
                        });
                    } else {
                        $main_func = $and || is_null($query->getQuery()->wheres)
                            ? (isset($coloana_tabel->rules['scope']) ? 'having' : 'where')
                            : (isset($coloana_tabel->rules['scope']) ? 'orHaving' : 'orWhere');
                        if(in_array($coloana_tabel->tip, ['2', '5', '6', '7'])) {
                            $query->{$main_func}($coloana_cu_tabel, $val);
                        } else {
                            $query->{$main_func}($coloana_cu_tabel, 'like', '%'.$val.'%');
                        }
                    }
                }

            }
        }

        return $query;
    }

    public static function getQuery($coloane, $conditions = [], $section = null)
    {
        $section = $section ?? static::getSection();
        return static::getQueryFromData(static::prepareQueryData($coloane, $conditions, $section), $conditions, $section);
    }

    // // for multi table model to work correctly with save method
    // public function bind(string $connection, string $table)
    // {
    //     $this->setConnection($connection);
    //     $this->setTable($table);
    // }

    // public function newInstance($attributes = [], $exists = false)
    // {
    //     // Overridden in order to allow for late table binding.

    //     $model = parent::newInstance($attributes, $exists);
    //     $model->setTable($this->table);

    //     return $model;
    // }

    protected static function booted(): void
    {
        static::addGlobalScope('colaborator', function ($builder) {
            if(auth()->check() && auth()->user()->hasRole('Colaborator')) {
                // check if table in the section has the column
                $section_has_column = cache()->remember('section_'.static::getSection().'_colaborator', 21600, function () {
                    $section = Ofertare\SectiuneAFM::firstWhere('nume', static::getSection());
                    return $section ? $section->hasColumn('colaborator') : false;
                });
                if($section_has_column) {
                    $builder->withInfo()->where('colaborator', auth()->id());
                } else { // don't show any formular if it doesn't
                    $builder->withInfo()->whereNull((new static)->setSection(static::getSection())->getInfoForeignKeyName());
                }
            }
        });

        static::creating(function($afm) {
            if(auth()->check() && auth()->user()->hasRole('Colaborator') && array_key_exists('colaborator', $afm->attributes)) {
                $afm->colaborator = auth()->id();
            }
        });

        static::updating(function($afm) {
            if(auth()->check() && auth()->user()->hasRole('Colaborator') && array_key_exists('colaborator', $afm->attributes)) {
                $afm->colaborator = auth()->id();
            }
        });
    }
}
