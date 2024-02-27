<?php

namespace App\View\Components;

use App\Models\Pagina;
use App\Models\User;
use Illuminate\View\Component;

class FooterLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $setari = setari([
            'EMAIL_CONTACT_PUBLIC',
            'FACTURARE_CAPITAL_SOCIAL'
        ]);
        return view('layouts.footer', [
            'pagini' => Pagina::where('in_meniu', 1)->get(),
            'contact_mails' => explode(';', $setari['EMAIL_CONTACT_PUBLIC']),
            'capital_social' => str_replace('.', '', explode(' RON', $setari['FACTURARE_CAPITAL_SOCIAL'])[0]),
            'clienti' => User::where('admin', '0')->where('valid','1')->count(),
        ]);
    }
}
