<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FileAccessTrait;
use Illuminate\Http\Request;


class OfertareController extends Controller
{
    use FileAccessTrait;

    public function views($path, $stream = 1)
    {
        return $this->file_assets('views/ofertare/'.$path, $stream);
    }

    public function assets($path, $stream = 1)
    {
        return $this->file_assets('assets/ofertare/'.$path, $stream);
    }

    public function tehnicieni(Request $request)
    {
        return User::tehnicieni()->select(\DB::raw('CONCAT(`nume`," ",`prenume`) AS nume'), 'id')->where('nume', 'like', $request->input('search').'%')
                ->limit(10)->get()->toJson();
    }
}
