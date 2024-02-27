<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Fisier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FileController extends Controller
{
    protected $error_message = 'Fisierul nu a putut fi incarcat.';
    protected $delete_error_message = 'Fisierul nu a putut fi sters.';

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:6144'],
            'ajax' => ['nullable'],
            'user_id' => ['nullable', 'integer', Rule::exists(User::class, 'id')],
            'model_type' => ['required', 'string', 'max:255'],
            'model_id' => ['required', 'integer', 'min:1', Rule::exists($request->input('model_type'), 'id')],
            'path' => ['required', 'string', 'max:255'],
            'tabel' => ['nullable', 'string', 'max:255'],
            'foreign_column' => ['nullable', 'required_with:tabel', 'string', 'max:255'],
            'coloana' => ['nullable', 'required_with:tabel', 'string', 'max:255'],
        ]);


        $ajax = $request->has('ajax');
        if(!$validator->fails()) {
            if($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename .= '_'.time().rand(100,999);
                $filename .= '.'.$file->getClientOriginalExtension();
                // $filename = \Str::uuid().rand(100,999).'.'.$file->getClientOriginalExtension();
                $path = substr($request->input('path'), -1) == '/' 
                    ? $request->input('path') 
                    : $request->input('path').'/';
                try {
                    if($file->storeAs($path, $filename, 's3')) {
                        $fisier = Fisier::create([
                            'user_id' => $request->input('user_id'),
                            'model_type' => $request->input('model_type'),
                            'model_id' => $request->input('model_id'),
                            'name' => $filename,
                            'path' => $path
                        ]);
                        if($request->has('tabel') 
                            && Schema::hasColumn($request->input('tabel'), $request->input('foreign_column'))
                            && Schema::hasColumn($request->input('tabel'), $request->input('coloana'))
                        ) {

                            $query = DB::table($request->input('tabel'))
                                ->where($request->input('foreign_column'), $fisier->model_id);

                            if($query->exists()) {
                                $old_image = $query->first()->{$request->input('coloana')};
                                $query->update([$request->input('coloana') => $fisier->id]);
                                $old_file = $old_image ? Fisier::find($old_image) : null;

                                if($old_file) {
                                    $old_file->delete();
                                }
                            } else {
                                DB::table($request->input('tabel'))->insert([
                                    $request->input('foreign_column') => $fisier->model_id,
                                    $request->input('coloana') => $fisier->id
                                ]);
                            }
                        }
                        return $ajax 
                            ? response(route('ofertare.aws.get', ['path' => $fisier->path.$fisier->name]), 200)
                            : back()->with(['status' => __('Fisierul a fost incarcat.')], 200);

                    } else {
                        return $this->errorResponse($ajax);
                    }
                } catch(\Exception $e) {
                    \Log::info($e);
                    return $this->errorResponse($ajax);
                }
            } else {
                return $this->errorResponse($ajax);
            }
        } else {
            return $this->errorResponse($ajax, $validator->errors()->first(), $validator->errors());
        }

        return $ajax 
            ? response(__('Fisierul a fost incarcat.'), 200)
            : back()->with(['status' => __('Fisierul a fost incarcat.')], 200);
    }

    public function delete(Request $request, Fisier $fisier)
    {
        $validator = Validator::make($request->all(), [
            'ajax' => ['nullable'],
            'foreign_column' => ['nullable', 'required_with:tabel', 'string', 'max:255'],
            'tabel' => ['nullable', 'string', 'max:255'],
            'coloana' => ['nullable', 'required_with:tabel', 'string', 'max:255'],
        ]);


        $ajax = $request->has('ajax');
        if(!$validator->fails()) {
            try {
                if(Storage::disk('s3')->exists($fisier->path.$fisier->name)) {
                    $fisier->delete();
                    if($request->has('tabel') 
                        && Schema::hasColumn($request->input('tabel'), $request->input('foreign_column'))
                        && Schema::hasColumn($request->input('tabel'), $request->input('coloana'))
                    ) {
                        $query = DB::table($request->input('tabel'))
                            ->where($request->input('foreign_column'), $fisier->model_id);
                        $old_image = $query->first()->{$request->input('coloana')};
                        $query->update([$request->input('coloana') => null]);
                    }
                    return $ajax 
                        ? response(route('ofertare.aws.get', ['path' => $fisier->path.$fisier->name]), 200)
                        : back()->with(['status' => __('Fisierul a fost sters.')], 200);

                } else {
                    return $this->errorResponse($ajax, $this->delete_error_message);
                }
            } catch(\Exception $e) {
                \Log::info($e);
                return $this->errorResponse($ajax, $this->delete_error_message);
            }
        } else {
            return $this->errorResponse($ajax, $validator->errors()->first(), $validator->errors());
        }

        return $ajax 
            ? response(__('Fisierul a fost incarcat.'), 200)
            : back()->with(['status' => __('Fisierul a fost incarcat.')], 200);
    }

    protected function errorResponse($ajax = false, $message = null)
    {
        $message = $message ?? $this->error_message;
        return $ajax 
            ? response(__($message), 500)
            : back()->withInput()->withErrors(is_array($message) ? $message : ['error' => __($message)], 500);
    }

    public function get($path)
    {
        if(Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->download($path);
        }
        return abort(404);
    }
}
