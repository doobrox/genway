<?php

namespace App\Models\Ofertare;

use App\Models\AfmForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programare extends Model
{
    use HasFactory;

    protected $fillable = [
        'formular_id',
        'an',
    ];

    protected $casts = [];

    protected $table = 'programari';

    public function formular()
    {
        // return $this->belongsTo(AfmForm::class, 'formular_id', 'id')->setQuery(
        //     (new AfmForm($this->an))->where('id', $this->formular_id)->getQuery()
        // )->setModel(new AfmForm($this->an));
        return $this->belongsTo(AfmForm::setSection($this->an), 'formular_id', 'id');
    }

    public function formularInfo()
    {
        $formular = AfmForm::setSection($this->an);
        return $this->hasOneThrough(AfmForm::table($formular->getInfoTable()), $formular, 'id', 'id_formular', 'formular_id', 'id');
    }

    public function echipa()
    {
        return $this->belongsToMany(User::class, 'echipa_programare', 'programare_id', 'user_id')
            ->withPivot('procent','lider');
    }

    public function pivotEchipa()
    {
        return $this->hasMany(EchipaProgramare::class, 'programare_id', 'id');
    }

    public function lider()
    {
        return $this->echipa()->wherePivot('lider', 1)->first();
    }

    public function montaj()
    {
        return $this->hasMany(Executie::class, 'programare_id', 'id');
    }

    public static function withFormularePerSection(&$items, Bool $return = false)
    {
        $bag = $items->items();
        if($bag) {
            $forms = collect();
            foreach(collect($bag)->groupBy('an') as $section => $formulare) {
                $forms = $forms->merge(
                    AfmForm::setSection($section)->whereIn('id', $formulare->pluck('formular_id'))
                        ->with('judetImobil','localitateImobil')
                        ->withInfo()->get()
                );
            }
            foreach($items->items() as &$item) {
                $item->setRelation('formular', $forms->where('id', $item->formular_id)->first());
            }
        }
        if($return) {
            return $items;
        }
    }

    public function listaEchipa()
    {
        $echipa = $this->echipa()->select('nume','prenume')->get()->map(function ($item, $key) {
            if($item['pivot']['lider']) {
                return '<b>'.$item['nume'].' '.$item['prenume'].' (Lider)</b>';
            } else {
                return $item['nume'].' '.$item['prenume'];
            }
        });
        return '<ul><li>'.$echipa->implode('</li><li>').'</li></ul>';
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function scopeWithMontaj($query)
    {
        $pTable = $this->getTable();
        $eTable = Executie::getTableName();
        return $query->select("*")->join($eTable, "{$pTable}.id", '=', "{$eTable}.programare_id");
    }

    public function scopeWithStatus($query)
    {
        $pTable = $this->getTable();
        $eTable = Executie::getTableName();
        return $query->select("{$pTable}.*", "{$eTable}.status", "{$eTable}.id as montaj_id")
            ->leftJoin($eTable, "{$pTable}.id", '=', "{$eTable}.programare_id");
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($subquery) {
            $subquery->whereNot('status', '2')
                ->orWhereNull('status');
        });
    }

    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                switch ($this->status) {
                    case '0':
                        return __('Noua');
                    case '1':
                        return __('Asteapta confirmare');
                    case '2':
                        return __('Confirmata');
                    case '3':
                        return __('Necesita schimbari');
                    default:
                        return __('Noua');
                        break;
                }
            },
        );
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // static::addGlobalScope('active', function (Builder $builder) {
        //
        // });
    }
}
