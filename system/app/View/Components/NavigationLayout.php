<?php

namespace App\View\Components;

use App\Models\Meniu;
use App\Models\Pagina;
use Illuminate\View\Component;

class NavigationLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.navigation', [
            'meniu' => Meniu::with('pagini')->get(),
        ]);
    }
}
