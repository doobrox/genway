<?php

namespace App\Http\Controllers\Ofertare;

use App\Models\Componenta;
use App\Models\Ofertare\SectiuneAFM;
use App\Models\Ofertare\Invertor;
use App\Models\Ofertare\Panou;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComponentaController extends Controller
{
    protected function model()
    {
        return app(Componenta::class);
    }

    protected function section()
    {
        return __('Componente');
    }

    protected function basePath()
    {
        return 'ofertare.componente.';
    }

    protected function searchColumns()
    {
        return $this->model()->getEditableColumns();
    }

    protected function createMessage($item)
    {
        return __('Componenta ":name" a fost creata.', ['name' => $item->titlu]);
    }

    protected function updateMessage($item)
    {
        return __('Componenta ":name" a fost actualizata.', ['name' => $item->titlu]);
    }

    protected function deleteMessage($item)
    {
        return __('Componenta ":name" a fost stearsa.', ['name' => $item->titlu]);
    }

    protected function notFoundMessage()
    {
        return __('Componenta nu a putut fi gasita.');
    }

    protected function defaultOptionText()
    {
        return __('Alege componenta');
    }

    protected function optionText($item)
    {
        return $item->titlu;
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
            'marci_invertor' => Invertor::MARCA_VALUES,
            'marci_panouri' => Panou::MARCA_VALUES,
        ] + parent::commonParameters();
    }
}
