<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Comanda;
use App\Models\Curier;
use App\Models\Localitate;
use App\Models\Judet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $localitateFacturare = $user->localitateFacturare();
        $localitateLivrare = $user->localitateLivrare();
        return view('profile.account', [
            'user' => $user,
            'localitateFacturare' => $localitateFacturare,
            'localitateLivrare' => $localitateLivrare,
            'judete' => Judet::select('id','nume')->get(),
            'localitati' => old('id_localitate', $user->id_localitate) 
                ? Localitate::select('id','nume')->where('id_judet', old('id_judet', $localitateFacturare->id_judet))->get()
                : [],
            'localitatiLivrare' => old('livrare_id_judet', $localitateLivrare ? $localitateLivrare->id_judet : '') 
                ? Localitate::select('id','nume')->where('id_judet', old('livrare_id_judet', $localitateLivrare ? $localitateLivrare->id_judet : ''))->get()
                : [],
        ]);
    }

    public function update(Request $request)
    {
        $inputs = Validator::make($request->all(), 
            User::rules(auth()->id()), 
            User::messages(), 
            User::names()
        )->validate();

        $inputs['newsletter'] = isset( $input['newsletter'] ) ? 1 : 0;
        if(isset( $inputs['livrare_adresa_1'] )) {
            $inputs['livrare_adresa_1'] = 1;
        } else {
            $inputs['livrare_adresa_1'] = 0;
            $inputs['livrare_id_judet'] = null;
            $inputs['livrare_id_localitate'] = null;
            $inputs['livrare_adresa'] = null;
        }

        auth()->user()->update($inputs);

        return back()->with('status', __('Profilul a fost actualizat cu success'));
    }

    public function password()
    {
        return view('profile.update-password');
    }

    public function updatePassword(Request $request)
    {
        $inputs = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ],[],[
            'current_password' => __('parola curenta'),
            'password' => __('password'),
        ])->validate();

        auth()->user()->update(['password' => \Hash::make($inputs['password'])]);
        return back()->with('status', __('Parola a fost schimbata cu succes'));
    }

    public function orders()
    {
        return view('profile.orders.index', [
            'comenzi' => auth()->user()->comenzi()->orderByDesc('data_adaugare')->paginate(10),
        ]);
    }

    public function order(Request $request, Comanda $comanda)
    {
        if($comanda->id_user != auth()->id()) {
            return redirect()->route('home');
        }
        if($request->has('orderId')) {
            session()->flash('status', __('Starea comenzi se va schimba in cateva momente. Veti primi un email de confirmare a platii daca aceasta a fost confirmata.'));
        }
        $user = auth()->user();
        return view('profile.orders.show', [
            'user' => $user,
            'comanda' => $comanda,
            'produse' => $comanda->produse,
            'info' => $comanda->getMetas(),
            'setari' => setari([
                'EMAIL_SUPORT_ONLINE',
                'TELEFON_CONTACT'
            ]),
        ]);
    }

    public function pdf(Comanda $comanda, $download = false)
    {
        if($comanda->id_user != auth()->id()) {
            return redirect()->route('home');
        }

        $user = auth()->user();
        $data = [
            'user' => $user,
            'localitate' => $user->localitate,
            'comanda' => $comanda,
            'produse' => $comanda->produse,
            'info' => $comanda->getMetas(),
        ];
        $pdf = \PDF::loadView('invoice.factura-proforma', $data);
        if (!$download) 
        {
            return $pdf->stream('Factura '.'#'.$comanda->nr_factura.'.pdf')->header('Content-Type','application/pdf');
        } 
        else 
        {
            return $pdf->download('Factura '.'#'.$comanda->nr_factura.'.pdf');
        }
    }

}
