<?php

namespace App\Traits;

use App\Models\Fisier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait FileAccessTrait
{
    protected function file_assets($path, $stream = 1)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if(!in_array($extension, (new Fisier)->getAllowedMimes())) {
            return abort(404);
        }
        if (Storage::disk('resources')->exists($path)) {
            return $stream 
                ? $stream == 1 ? Storage::disk('resources')->response($path) : Storage::disk('resources')->get($path) 
                : Storage::disk('resources')->download($path);
        }
        return $stream ? abort(404) : redirect()->back()->withErrors([
            'error' => __('Fișierul nu există'),
        ]);
    }
}
