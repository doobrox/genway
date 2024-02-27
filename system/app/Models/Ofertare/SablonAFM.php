<?php

namespace App\Models\Ofertare;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission;

class SablonAFM extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nume',
        'coloane',
        'ordine_coloane',
        'implicit'
    ];

    protected $casts = [
        'coloane' => 'array',
        'ordine_coloane' => 'array',
    ];

    protected $table = 'sabloane_afm';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function allColumns($section = null)
    {
        // OLD
        // Permission::where('name', 'like','afm.%.%')
        //     ->whereNot('name', 'like','afm.%.*')
        //     ->whereNot('name', 'like','afm.%.%.edit')
        //     ->whereNot('name', 'like','afm.%.generare%')
        //     ->whereNot('name', 'like','afm.%.mail%')
        //     ->pluck('name')->map(function ($item) {
        //         return preg_replace('/afm\.[^\.]*\.([^\.]*).*/', '\1', $item);
        //     })->toArray();

        $arr = [];
        if(auth()->check()) {
            foreach(auth()->user()->getAllPermissions()->sortBy('name') as $permission) {
                if(preg_match('/^afm\.[^\.]*\.((?!generare[^\.]*|mail[^\.]*|buton[^\.]*|view|\*)[^\.]*)(?:\.?view)?$/', $permission->name)) {
                    $arr[] = preg_replace('/afm\.[^\.]*\.([^\.]*).*/', '\1', $permission->name);
                }
            }
        }
        return $arr;
    }

    public static function getTableName()
    {
        return (new static)->getTable();
    }
}
