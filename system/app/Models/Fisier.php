<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Fisier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'name',
        'path',
    ];

    protected $table = 'fisiere';

    protected static $allowed_mimes = ['png','jpg','jpeg','webp','gif','ai','tiff','bmp','doc','docx','dot','webk','docm','dotx','dotm','docb','xls','xlsx','pdf','ppt','pot','pps','pptx','potx','ppsx','webm','mcv','flv','avi','wmv','mp4','m4p','m4v','3gp','mp3','ogg','wma','txt'];

    public function getAllowedMimes()
    {
        return static::$allowed_mimes;
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function stream()
    {
        if(Storage::disk('s3')->exists($this->path.$this->name)) {
            return Storage::disk('s3')->response($this->path.$this->name);
        }
        return abort(404);
    }

    public function attachment()
    {
        if(Storage::disk('s3')->exists($this->path.$this->name)) {
            return \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('s3', $this->path.$this->name);
                // ->as('name.pdf')
                // ->withMime('application/pdf')
        }
        return abort(404);
    }

    public function download()
    {
        if(Storage::disk('s3')->exists($this->path.$this->name)) {
            return Storage::disk('s3')->download($this->path.$this->name);
        }
        return abort(404);
    }

    public static function getRuleAllowedMines()
    {
        return 'mimes:'.implode(', ', static::$allowed_mimes);
    }

    public static function store($file, $path, $model_type, $model_id, $user_id = null, $name = null, $same_name = null)
    {
        // $file = $request->file('file');
        if($same_name) {
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename .= '_'.time().rand(100,999);
            $filename .= '.'.$file->getClientOriginalExtension();
        } elseif($name) {
            $filename = $name;
        } else {
            $filename = \Str::uuid().rand(100,999).'.'.$file->getClientOriginalExtension();
        }
        $path = substr($path, -1) == '/' ? $path : $path.'/';
        try {
            if($file->storeAs($path, $filename, 's3')) {
                $fisier = Fisier::create([
                    'user_id' => $user_id ?? auth()->id(),
                    'model_type' => $model_type,
                    'model_id' => $model_id,
                    'name' => $filename,
                    'path' => $path
                ]);
                return $fisier->id;

            } else {
                return ['error' => __('File could not be saved.')];
            }
        } catch(\Exception $e) {
            \Log::info($e);
            return ['error' => __('File could not be saved.')];
        }
        return ['error' => __('File could not be saved. Please try again later.')];
    }

    public static function booted()
    {
        static::deleted(function ($model) {
            try {
                \Storage::disk('s3')->delete($model->path.$model->name);
            } catch(\Exception $e) { \Log::info($e); }
        });
    }
}
