<?php

namespace App\Models\Ofertare;

use App\Casts\FormatDate;
use App\Models\Fisier;
use App\Models\User;
use App\Traits\FileAccessTrait;
use App\Models\Ofertare\Sarcina;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SarcinaMesaj extends Model
{
    use FileAccessTrait;

    protected $fillable = [
        'sarcina_id',
        'user_id',
        'description',
        'attachments',
    ];

    protected $casts = [
        // 'created_at' => FormatDate::class,
    ];

    protected $table = 'sarcini_mesaje';

    public static function getTableName()
    {
        return (new static)->getTable();
    }

    public function sarcina()
    {
        return $this->belongsTo(Sarcina::class, 'id');
    }

    public function fisiere()
    {
        return $this->morphMany(Fisier::class, 'model');
    }

    // public function getUserName()
    // {
    //     return User::select(['id', \DB::raw('CONCAT(nume, " ", prenume) as nume_complet')])->find($this->user_id)->nume_complet;
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function numeUserComplet(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user ? $this->user->nume_complet : null,
        );
    }
}
