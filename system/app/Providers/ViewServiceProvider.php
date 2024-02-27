<?php
namespace App\Providers;

use App\Models\Localitate;
use App\Models\Judet;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.navigation', 'layouts.footer', 'contact'], function ($view) {
            $setari = setari([
                'TELEFON_CONTACT',
                'TELEFON_CONTACT_2',
                'EMAIL_SUPORT_ONLINE',
            ]);
            $view->with([
                'tel_contact' => $setari['TELEFON_CONTACT'],
                'tel2_contact' => $setari['TELEFON_CONTACT_2'],
                'mail_contact' => $setari['EMAIL_SUPORT_ONLINE'],
            ]);
        });

        View::composer(['components.formular-transfer-dosar'], function ($view) {
            $view->with([
                'judete' => Judet::select('id','nume')->get(),
                'localitati' => old('judet') 
                    ? Localitate::select('id','nume')->where('id_judet', old('judet'))->get()
                    : [],
                'localitatiImplementare' => old('judet_implementare') 
                    ? Localitate::select('id','nume')->where('id_judet', old('judet_implementare'))->get()
                    : [],
            ]);
        });

        View::composer(['components.formular-casa-verde'], function ($view) {
            $view->with([
                'judete' => Judet::select('id','nume')->get(),
                'localitati' => old('judet_domiciliu') 
                    ? Localitate::select('id','nume')->where('id_judet', old('judet_domiciliu'))->get()
                    : [],
                'localitatiImobil' => old('judet_imobil') 
                    ? Localitate::select('id','nume')->where('id_judet', old('judet_imobil'))->get()
                    : [],
            ]);
        });

        View::composer(['components.formular-ofertare'], function ($view) {
            $view->with([
                'judete' => Judet::select('id','nume')->get(),
            ]);
        });
    }
}
