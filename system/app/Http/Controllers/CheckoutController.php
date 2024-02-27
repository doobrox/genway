<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Billing\PaymentGateway;
use App\Events\SendMails;
use App\Mail\OrderEmail;
use App\Mail\TemplateEmail;
use App\Models\Comanda;
use App\Models\ComandaProdus;
use App\Models\Curier;
use App\Models\Localitate;
use App\Models\Produs;
use App\Models\User;
use App\Models\Judet;
use App\Models\Voucher;
use App\Traits\CartCalculatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class CheckoutController extends Controller
{
    use CartCalculatorTrait;

    protected $generalErrorMsg;

    public function __construct() 
    {
        $this->generalErrorMsg = __('Eroare la calcularea sumei, va rog incercati mai tarziu.');
    } 

    public function index(Request $request)
    {
        $validationParams = $this->getCartValidationParams();
        $validator = Validator::make($request->merge([
            'produse' => collect(session('cart.items'))->map(function ($item, $key) {
                return $item['qty'];
            })->toArray(),
            'voucher' => session('cart.voucher.code'),
            'curier' => session('cart.courier.id'),
            'tip_plata' => session('cart.payment.id'),
            'detalii_comanda' => session('cart.message'),
        ])->all(), $validationParams['rules'], $validationParams['messages'], $validationParams['names']);

        if($validator->fails()) {
            return redirect()->route('cart')->withErrors($validator->errors());
        }

        $user = auth()->user();
        if($user) {
            $localitateFacturare = $user->localitateFacturare();
            $localitateLivrare = $user->localitateLivrare();
        }

        $id_judet = old('id_judet', auth()->check() && $localitateFacturare ? $localitateFacturare->id_judet : '');
        $livrare_id_judet = old(
            'livrare_id_judet', 
            auth()->check() && $localitateLivrare ? $localitateLivrare->id_judet : ''
        );

        return view('checkout', [
            'cart' => session('cart'),
            'user' => auth()->user(),
            'judete' => Judet::select('id','nume')->get(),
            'id_judet' => $id_judet,
            'localitati' => $id_judet 
                ? Localitate::select('id','nume')->where('id_judet', $id_judet)->get()
                : [],
            'livrare_id_judet' => $livrare_id_judet,
            'localitatiLivrare' => $livrare_id_judet 
                ? Localitate::select('id','nume')->where('id_judet', $livrare_id_judet)->get()
                : [],
        ]);
    }

    public function calculateShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'localitate' => ['required', 'integer', Rule::exists(Localitate::class, 'id')],
        ],['*' => __('Localitatea nu se afla in baza de date')]);
        if($validator->fails()) {
            return response($validator->messages()->first(), 422);
        }
        if($curier = Curier::firstWhere('id', session('cart.courier.id'))) {
            $price = $curier->pret(session('cart'), $request->localitate);
            return response([
                $price,
                round(session('cart.total_cart') - session('cart.courier.price') + $price, 2),
            ], 200);
        }
        return response($this->generalErrorMsg, 422);
    }

    public function checkout(Request $request)
    {
        $validationCartParams = $this->getCartValidationParams();
        $validationUserParams = User::getUserValidationParams(null, auth()->id());
        $validationUserParams['rules']['new_account'] = ['nullable', 'in:1'];
        $validationUserParams['rules']['password'] = [
            'nullable', 
            'string', 
            new Password, 
            'confirmed',
            'required_with:new_account',
            'exclude_without:new_account'
        ];

        $inputs = array_filter(Validator::make($request->all(), 
            $validationCartParams['rules'] + $validationUserParams['rules'], 
            $validationCartParams['messages'] + $validationUserParams['messages'], 
            $validationCartParams['names'] + $validationUserParams['names']
        )->validate());

        if(auth()->check()){
            $user = auth()->user();
        } else {
            if(isset($inputs['new_account'])) {
                $user = app(CreateNewUser::class)->create($inputs + [
                    'password_confirmation' => $inputs['password'],
                ]);
                auth()->loginUsingId($user->id);
            } else {
                $user = new User($request->only(app(User::class)->getFillable()));
                $user->id = null;
            }
        }

        $cantitati = $inputs['produse'];
        $produse = Produs::whereIn('id', array_keys($cantitati))->get();

        $cart['courier']['id'] = $inputs['curier'];
        $cart['payment']['id'] = $inputs['tip_plata'];
        $cart['courier']['locality'] = $inputs['livrare_id_localitate'] ?? $inputs['id_localitate'];

        if(isset($inputs['voucher'])) {
            $cart['voucher']['code'] = $inputs['voucher'];
        }

        // organize products 
        $produseComanda = [];
        foreach($produse as $produs) {
            // prepare product values
            $price = $produs->pret_normal;
            $priceWithTva = $produs->pret_cu_tva;
            $valueTva = $priceWithTva - $price;
            $qty = $cantitati[$produs->id];

            // create new cart
            $cart['items'][$produs->id] = [
                'id' => $produs->id,
                'price_with_tva' => $priceWithTva,
                'price' => $price,
                'qty' => $qty,
                'value_tva' => $valueTva,
                'total' => $price * $qty,
                'total_with_tva' => $priceWithTva * $qty,
            ];

            // build products array for the order
            $produseComanda[] = new ComandaProdus([
                'id_produs' => $produs->id,
                'cantitate' => $qty,
                'nume' => $produs->nume,
                'cod_ean13' => $produs->cod_ean13,
                'pret' => $price,
                'tva' => $produs->tva ?? setare('COTA_TVA'),
                'filtre' => '',
                'sincronizat' => '0',
            ]);
        }

        // calculate cart
        $cart = $this->calculate($cart, 1);

        // create order
        $comanda = Comanda::create([
            'id_user' => $user->id,
            'id_curier' => $inputs['curier'],
            'id_tip_plata' => $inputs['tip_plata'], 
            'mesaj' => $inputs['detalii_comanda'] ?? null, 
            'nr_factura' => Comanda::getNextNumarFactura(),
            'tva' => setare('COTA_TVA'),
            'stare' => '0',
            'taxa_livrare' => $cart['courier']['price'] + ($cart['payment']['return_price'] ?? 0),
            'ip' => $request->ip(),
            'data_adaugare' => now(),
            'nota_interna' => '',
        ] + (isset($cart['voucher']) ? [
            'cod_voucher' => $cart['voucher']['code'],
            'valoare_voucher' => -1 * $cart['voucher']['value'],
        ] : [
            'cod_voucher' => '',
        ]) + (isset($cart['discount_fidelitate']) ? [
            'discount_fidelitate' => $cart['discount_fidelitate']['percent'],
            'valoare_discount_fidelitate' => -1 * $cart['discount_fidelitate']['value'],
        ] : []) + (isset($cart['discount_plata_op']) ? [
            'discount_plata_op' => $cart['discount_plata_op']['percent'],
            'valoare_discount_plata_op' => -1 * $cart['discount_plata_op']['value'],
        ] : []));

        // add products to the new order
        $comanda->produse()->saveMany($produseComanda);

        $comanda->setMetas([
            'curier' => $comanda->nume_curier,
        ], 'comanda_');

        // add client info to the new invoice order
        $comanda->setMetas([
            'tip' => $inputs['tip'],
            'nume' => $inputs['nume'],
            'prenume' => $inputs['prenume'],
            'user_email' => $inputs['user_email'],
            'cnp' => $inputs['cnp'],
            'telefon' => $inputs['telefon'],
            'adresa' => $inputs['adresa'],
            'localitate' => remove_accents(Localitate::firstWhere('id', $inputs['id_localitate'])->nume),
            'judet' => remove_accents(Judet::firstWhere('id', $inputs['id_judet'])->nume),
        ] + (isset($inputs['livrare_adresa_1']) && $inputs['livrare_adresa_1'] != null ? [
            'livrare_adresa_1' => $inputs['livrare_adresa_1'],
            'livrare_adresa' => $inputs['livrare_adresa'],
            'livrare_localitate' => remove_accents(Localitate::firstWhere('id', $inputs['livrare_id_localitate'])->nume),
            'livrare_judet' => remove_accents(Judet::firstWhere('id', $inputs['livrare_id_judet'])->nume),
        ] : []) + ($inputs['tip'] == '2' ? [
            'nume_firma' => $inputs['nume_firma'],
            'cui' => $inputs['cui'],
            'nr_reg_comert' => $inputs['nr_reg_comert']
        ] : []), 'client_');

        // add provider info to the new invoice order
        $setari = setari([
            'FACTURARE_NUME_FIRMA',
            'FACTURARE_ADRESA',
            'FACTURARE_LOCALITATE',
            'FACTURARE_JUDET',
            'FACTURARE_CUI',
            'FACTURARE_COD_FISCAL',
            'FACTURARE_NR_REG_COMERT',
            'FACTURARE_BANCA'
        ]);
        $comanda->setMetas([
            'nume_firma' => $setari['FACTURARE_NUME_FIRMA'],
            'adresa' => $setari['FACTURARE_ADRESA'],
            'localitate' => $setari['FACTURARE_LOCALITATE'],
            'judet' => $setari['FACTURARE_JUDET'],
            'cui' => $setari['FACTURARE_CUI'],
            'nr_reg_comert' => $setari['FACTURARE_NR_REG_COMERT'],
            'cod_fiscal' => $setari['FACTURARE_COD_FISCAL'],
            'banca' => $setari['FACTURARE_BANCA'],
        ], 'furnizor_');

        // clear cart session
        session()->pull('cart');

        // update stoc
        foreach($produse as $produs) {
            $produs->stoc -= $cantitati[$produs->id];
            $produs->save();
        }

        try {
            // prepare data for emails
            $details = [
                'comanda' => $comanda,
            ] + setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']);

            // send emails
            SendMails::dispatch($user, $details, [2]);

        } catch(\Exception $e) { \Log::info($e); }

        switch ($comanda->id_tip_plata) {
            case 1:
                $data['status'] = __('Comanda a fost salvata cu succes. Modalitatea de plata aleasa este ramburs.'.(auth()->check() ? ' <a href=":url">Vezi datele comenzii</a>' : ''), ['url' => route('profile.orders.show', $comanda->id)]); 
                break;
            case 2:
                $data['status'] = __('Comanda a fost salvata cu succes. Veti fi redirectionat spre pagina de plata... <br/> <img class="mt-4" src=":src">', ['src' => asset('images/netopia_payments.png')]);

                $data['form'] = null;

                $valoare = $comanda->valoare;

                $paymentGateway = app(PaymentGateway::class, [
                    'returnURL'     => route('checkout.order', $comanda->id),
                    'confirmURL'    => route('cart.confirm'),
                    'amount'        => $valoare,
                    'details'       => __('Plata cu cardul pentru suma :sum', ['sum' => $valoare]),
                    'signature'     => setare('MOBILPAY_CHEIE_UNICA'),
                    'firstName'     => $user->prenume,
                    'lastName'      => $user->nume,
                    'email'         => $user->user_email,
                    'address'       => $user->adresa,
                    'phone'         => $user->telefon,
                    'params'        => array('comanda_id' => $comanda->id ),
                    'type'          => $user->tip == '2' ? 'company' : 'person', // 'company',
                ]);
                // generate a mobilpay order id until it is unique
                do {
                    $orderId = $paymentGateway->generateOrderId();
                } while(Comanda::where('order_id', $orderId)->count() > 1);

                // retrieve the mobilpayform
                $data['form'] = $paymentGateway->setForm();

                // save the mobilpay id to the new order
                $comanda->order_id = $orderId;
                $comanda->save();
                break;
            case 3:
                $data['status'] = __('Comanda a fost salvata cu succes. O factura proforma a fost generata si trimisa catre adresa dvs. de email. <br />'.(auth()->check() ? ' <a href=":url">Vezi datele comenzii</a>' : ''), ['url' => route('profile.orders.show', $comanda->id)]); 
                break;
        }

        $data['urmarire_conversie'] = '<!-- Google Code for Comanda Conversion Page -->
            <script type="text/javascript">
                /* <![CDATA[ */
                var google_conversion_id = 1000677025;
                var google_conversion_language = "ro";
                var google_conversion_format = "3";
                var google_conversion_color = "ffffff";
                var google_conversion_label = "jmRdCK-S0gMQob2U3QM";
                var google_conversion_value = 0;
                var google_remarketing_only = false;
                /* ]]> */
                </script>
                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
            </script>
            <noscript>
                <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1000677025/?value=0&amp;label=jmRdCK-S0gMQob2U3QM&amp;guid=ON&amp;script=0"/>
                </div>
            </noscript>';

        return view('order', $data);
    }

    public function confirm()
    {
        $paymentGateway = app(PaymentGateway::class);
        $data = $paymentGateway->confirm();
        
        if(isset($data['status']) && $data['status'] == 'confirmed') {
            $comanda = Comanda::where('id', $data['params']['comanda_id'])->first();
            $comanda->update(['stare_plata' => 1]);

            try {
                // prepare data for emails
                $details = setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']) + [
                    'comanda' => $comanda,
                ];
                // send email
                SendMails::dispatch($comanda->user, $details, [3]);

            } catch(\Exception $e) { \Log::info($e); }

        } elseif(isset($data['status']) && in_array($data['status'], ['canceled','credit','default','rejected'])) {
            Comanda::where('id', $data['params']['comanda_id'])->update(['stare_plata' => -1]);
        } else {
            Comanda::where('id', $data['params']['comanda_id'])->update(['stare_plata' => 0]);
        }
    }

    public function order(Request $request, Comanda $comanda)
    {
        if($request->has('orderId') && $comanda->order_id == $request->orderId) {
            if(auth()->check() && $comanda->id_user == auth()->id()) {
                return redirect()->route('profile.orders.show', [
                    'comanda' => $comanda->id, 
                    'orderId' => $request->orderId
                ]);
            } elseif(!auth()->check()) {
                return view('order', [
                    'comanda' => $comanda,
                    'produse' => $comanda->produse,
                    'info' => $comanda->getMetas(),
                    'status' => __('Starea comenzi se va schimba in cateva momente. Veti primi un email de confirmare a platii daca aceasta a fost confirmata.')
                ]);
            }
        }
        abort(404);
    }
}
