<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueuedExport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tip',
        'sectiune',
        'user_id', 
        'nume', 
        'folder', 
        'status',
    ];

    protected $table = 'exporturi';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case '1':
                return __('In curs');
            case '2':
                return __('Finalizat');
            case '-1':
                return __('Esuat');
            default:
                return __('Nedefinit');
        }
    }

    public function canAccess()
    {
        return $this->user_id == auth()->id();
    }

    public function download()
    {
        if(\Storage::exists($this->folder.$this->nume) && $this->canAccess()) {
            return \Storage::download($this->folder.$this->nume);
        }
        return abort(404);
    }

    public static function booted()
    {
        static::deleted(function ($model) {
            try {
                \Storage::delete($model->folder.$model->nume);
            } catch(\Exception $e) { \Log::info($e); }
        });
    }
}
