<?php

namespace App\Http\Controllers\Ofertare;

use App\Models\Ofertare\Panou;
use App\Models\Ofertare\SectiuneAFM;
use App\Models\Ofertare\Invertor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PanouController extends Controller
{
    protected function model()
    {
        return app(Panou::class);
    }

    protected function section()
    {
        return __('Panouri');
    }

    protected function basePath()
    {
        return 'ofertare.panouri.';
    }

    protected function searchColumns()
    {
        return $this->model()->getEditableColumns();
    }

    protected function createMessage($item)
    {
        return __('Panoul ":name" a fost creata.', ['name' => $item->model]);
    }

    protected function updateMessage($item)
    {
        return __('Panoul ":name" a fost actualizata.', ['name' => $item->model]);
    }

    protected function deleteMessage($item)
    {
        return __('Panoul ":name" a fost stearsa.', ['name' => $item->model]);
    }

    protected function notFoundMessage()
    {
        return __('Panoul nu a putut fi gasita.');
    }

    protected function defaultOptionText()
    {
        return __('Alege panoul');
    }

    protected function optionText($item)
    {
        return $item->model;
    }

    protected function rules($id = null)
    {
        return $this->model()->rules($id);
    }

    protected function messages()
    {
        return $this->model()->messages();
    }

    protected function names()
    {
        return $this->model()->names();
    }

    protected function commonParameters()
    {
        return [
            'sections' => SectiuneAFM::pluck('titlu', 'nume')->toArray(),
            'marci' => Panou::MARCA_VALUES,
        ] + parent::commonParameters();
    }
}
