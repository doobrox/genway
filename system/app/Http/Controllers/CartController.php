<?php

namespace App\Http\Controllers;

use App\Billing\PaymentGateway;
use App\Events\SendMails;
use App\Mail\OrderEmail;
use App\Mail\TemplateEmail;
use App\Models\Comanda;
use App\Models\Curier;
use App\Models\Produs;
use App\Models\User;
use App\Models\Voucher;
use App\Traits\CartCalculatorTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Blade;

class CartController extends Controller
{
    use CartCalculatorTrait;

    protected $generalErrorMsg;

    public function __construct() {
        $this->generalErrorMsg = __('Eroare la calcularea sumei, va rog incercati mai tarziu.');
    }

    public function index( )
    {
        return view('cos', [
            'cart' => session('cart') ?? [],
            'curieri' => Curier::all(),
            'plati' => [
                '1' => __('Ramburs'), //selectat default
                '2' => __('Plata cu cardul'),
                '3' => __('Transfer Bancar'),
            ],
        ]);
    }

    public function add(Request $request, Produs $produs, Int $qty = 1)
    {
        $stoc = $produs->stoc - (session('cart.items.'.$produs->id.'.qty') ?? 0);
        $stoc = $stoc > 0 ? $stoc : 0;

        $errorMsg = trans_choice('{0} Nu mai sunt ":produs" in stoc.|{1} Nu sunt destule ":produs" in stoc. Un singur produs mai este disponibil.|[2,*] Nu sunt destule ":produs" in stoc. Cantitatea ramasa este de :stoc produse.', $stoc, ['produs' => $produs->nume, 'stoc' => $stoc]);
        $successMsg = __('Produsul a fost adaugat in cos.');

        $validator = Validator::make($request->all() + (!$request->has('cantitate') ? ['qty' => $qty] : []), [
            'cantitate' => ['nullable', 'integer', 'min:1', 'max:'.$stoc],
            'qty' => ['nullable', 'integer', 'min:1', 'max:'.$stoc],
        ],['*' => $errorMsg],[ 'qty' => __('cantitate') ]);

        if($validator->fails()) {
            if($request->has('redirect')) {
                session()->flash('errors', $validator->errors());
                return response($validator->errors(), 422);
            } else {
                $validator->validateWithBag('cart');
            }
        }

        $inputs = array_filter($validator->validate());

        if(isset($inputs['cantitate'])) {
            $qty = $inputs['cantitate'];
            $redirect = true;
        }
        if($produs->stoc >= $qty) {
            $cart = session('cart') ?? [];

            $price = $produs->pret_normal;
            $priceWithTva = $produs->pret_cu_tva;
            $valueTva = $priceWithTva - $price;
            if(isset($cart['items'][$produs->id])) {
                $cart['items'][$produs->id]['qty'] += $qty;
                $cart['items'][$produs->id]['total'] = $price * $cart['items'][$produs->id]['qty'];
                $cart['items'][$produs->id]['total_with_tva'] = $priceWithTva * $cart['items'][$produs->id]['qty'];
            } else {
                $cart['items'][$produs->id] = [
                    'id' => $produs->id,
                    'name' => $produs->nume,
                    'route' => $produs->route,
                    'image' => route('images', 'produse/'.$produs->image),
                    'price_with_tva' => $priceWithTva,
                    'price' => $price,
                    'qty' => $qty,
                    'value_tva' => $valueTva,
                    'total' => $price * $qty,
                    'total_with_tva' => $priceWithTva * $qty,
                ];
            }

            $response = $this->calculate($cart);
            $msg = $this->generalErrorMsg;
        } else {
            $response = false;
            $msg = $errorMsg;
        }
        if($redirect ?? false) {
            $response == true
                ? session()->flash('cart_status', $successMsg) 
                : $this->addSessionError($msg, 'cart');
            return back();
        } else {
            if($request->has('redirect') && $response != true) {
                $this->addSessionError($msg, 'cart');
            }
            return $response == true
                ? response(Blade::render('<x-cart-layout />'), 200) 
                : response($msg, 422);
        }
    }

    public function subtract(Request $request, Produs $produs, Int $qty = 1)
    {
        $inputs = array_filter(Validator::make($request->all(), [
            'cantitate' => ['nullable', 'integer', 'min:1', 'max:'.$produs->stoc],
        ])->validateWithBag('cart'));
        
        if(isset($inputs['cantitate'])) {
            $qty = $inputs['cantitate'];
            $redirect = true;
        }
        $cart = session('cart') ?? [];

        $price = $produs->pret_normal;
        $priceWithTva = $produs->pret_cu_tva;
        $valueTva = $priceWithTva - $price;
        if(isset($cart['items'][$produs->id])) {
            $cart['items'][$produs->id]['qty'] -= $qty;
            if($cart['items'][$produs->id]['qty'] > 0) {
                $cart['items'][$produs->id]['total'] = $price * $cart['items'][$produs->id]['qty'];
                $cart['items'][$produs->id]['total_with_tva'] = $priceWithTva * $cart['items'][$produs->id]['qty'];
            } else {
                unset($cart['items'][$produs->id]);
            }
        }

        $response = $this->calculate($cart);
        $msg = $this->generalErrorMsg;
        
        if($redirect ?? false) {
            if($response != true) {
                $this->addSessionError($msg, 'cart');
            }
            return $response == true
                ? back()->with('cart_status', __('Cantitatea produsului a fost actualizat.')) 
                : back()->withErrors(['cart', $msg]) ;
        } else {
            return $response == true
                ? response(Blade::render('<x-cart-layout />'), 200) 
                : response($msg, 422);
        }
    }

    public function remove(Produs $produs)
    {
        $cart = session('cart') ?? [];

        $response = true;
        if(isset($cart['items'][$produs->id])) {
            unset($cart['items'][$produs->id]);
            $response = $this->calculate($cart);
            $msg = $this->generalErrorMsg;
        }

        return $response == true
            ? response(Blade::render('<x-cart-layout />'), 200) 
            : response($msg, 422);
    }

    public function voucher(Request $request)
    {
        $errorMsg = __('Voucher-ul nu este valabil.');
        $successMsg = __('Voucher-ul a fost adaugat.');

        $cart = session('cart') ?? [];
        $validator = Validator::make($request->all(), [
            'voucher' => ['required', 'string', 'min:1', 'max:255', 
                Rule::exists('vouchere', 'cod')->where(function ($query) use($cart) {
                    return $query->where('activ', 1)
                        ->whereDate('data_expirare', '>=', now())
                        // 0 means that the voucher is applied to the entire cart
                        ->whereIn('id_produs', array_keys(($cart['items'] ?? []) + ['0']));
                }),
            ],
        ],['*' => $errorMsg]);

        if($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return response($validator->errors(), 422);
        }
        $inputs = array_filter($validator->validate());

        $voucher = Voucher::firstWhere('cod', $inputs['voucher']);
        $response = true;

        // if the cart has items and the voucher can be used multiple times
        // a voucher "caracter" (the number of times that can be used) can be permanent(1) or a single time(2)
        if($voucher->caracter != '2' || $voucher->comenzi()->count() <= 1) {
            $cart['voucher']['code'] = $voucher->cod;
            // $cart['voucher']['name'] = $voucher->nume;
            // $cart['voucher']['number'] = $voucher->valoare;

            $response = $this->calculate($cart);
            $msg = $this->generalErrorMsg;

        } else {
            $response = false;
            $msg = $errorMsg;
        }

        if($response == true) {
            session()->flash('status', $successMsg);
        } else {
            $this->addSessionError($msg, 'voucher');
        }

        return $response == true
            ? response(Blade::render('<x-cart-layout />'), 200) 
            : response($msg, 422);
    }

    public function removeVoucher(Request $request)
    {
        $cart = session('cart') ?? [];
        $response = true;

        if(isset($cart['voucher'])) {
            unset($cart['voucher']);
            $response = $this->calculate($cart);
            $msg = $this->generalErrorMsg;
        }

        return $response == true
            ? response(Blade::render('<x-cart-layout />'), 200) 
            : response($msg, 422);
    }

    public function courier(Request $request)
    {
        $errorMsg = __('Curierul nu exista.');
        $successMsg = __('Curierul a fost schimbat.');

        $validator = Validator::make($request->all(), [
            'curier' => ['required', 'integer', 'min:1', 
                Rule::exists('curieri', 'id')->where(function ($query) {
                    return $query->where('activ', 1)
                        ->where('activ', '1');
                }),
            ],
        ],['*' => $errorMsg]);

        if($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return response($validator->errors(), 422);
        }
        $inputs = array_filter($validator->validate());

        $cart = session('cart') ?? [];
        $curier = Curier::firstWhere('id', $inputs['curier']);
        $response = true;

        $cart['courier']['id'] = $curier->id;
        $cart['courier']['price'] = $curier->pret();

        $response = $this->calculate($cart);

        if($response == true) {
            session()->flash('status', $successMsg);
        } else {
            $this->addSessionError($this->generalErrorMsg, 'curier');
        }

        return $response == true
            ? response(Blade::render('<x-cart-layout />'), 200) 
            : response($msg, 422);
    }

    public function payment(Request $request)
    {
        $errorMsg = __('Metoda de plata nu exista.');
        $successMsg = __('Metoda de plata a fost schimbata.');

        $validator = Validator::make($request->all(), [
            'tip_plata' => ['required', 'integer', 'min:1', 'max:3'],
        ],['*' => $errorMsg]);

        if($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return response($validator->errors(), 422);
        }
        $inputs = array_filter($validator->validate());

        $cart = session('cart') ?? [];
        $response = true;

        $cart['payment']['id'] = $inputs['tip_plata'];

        $response = $this->calculate($cart);

        if($response == true) {
            session()->flash('status', $successMsg);
        } else {
            $this->addSessionError($this->generalErrorMsg, 'curier');
        }

        return $response == true
            ? response(Blade::render('<x-cart-layout />'), 200) 
            : response($msg, 422);
    }

    public function message(Request $request)
    {
        $errorMsg = __('Metoda de plata nu este valabila.');
        $successMsg = __('Metoda de plata a fost schimbata.');

        $validator = Validator::make($request->all(), [
            'detalii_comanda' => ['nullable', 'string', 'min:1', 'max:3000'],
        ],['*' => $errorMsg]);

        if($validator->fails()) {
            session()->flash('errors', $validator->errors());
            return response($validator->errors(), 422);
        }
        $inputs = array_filter($validator->validate());

        $response = true;
        $cart = session('cart') ?? [];
        if(isset($inputs['detalii_comanda'])) {
            $cart['message'] = $inputs['detalii_comanda'];
        } else {
            unset($cart['message']);
        }
        $this->updateCart($cart);

        return $response == true
            ? response(Blade::render('<x-cart-layout />'), 200) 
            : response($msg, 422);
    }

    protected function addSessionError($msg, $key = 'error')
    {
        if(is_array($msg)) {
            $messageBag = new MessageBag;
            foreach($msg as $key => $message) {
                $messageBag->add($key, $message);
            }
            session()->flash('errors', (new ViewErrorBag)->put($key, $messageBag));
        } else {
            session()->flash('errors', (new ViewErrorBag)->put($key, (new MessageBag)->add($key, $msg)));
        }
    }

    protected function updateCart($cart)
    {
        if($cart['total'] == 0) {
            session()->pull('cart');
        } else {
            session()->put('cart', $cart);
        }
    }

    // public function checkout(Request $request)
    // {
    //     $inputs = array_filter(Validator::make($request->all(), [
    //         'produse' => ['required', 'array', 'min:1'],
    //         'produse.*' => ['required', 'numeric', 'min:1',
    //             function($attribute, $value, $fail) {
    //                 if (
    //                     Produs::where('id', explode('.', $attribute)[1])
    //                         ->where('stoc', '>=', $value)
    //                         ->count() < 1
    //                 ) {
    //                     return $fail(__('Nu mai sunt destule produse de acest tip in stoc.'));
    //                 }
    //             },
    //         ],
    //         'voucher' => ['nullable', 'string', 'min:1', 'max:255', 
    //             Rule::exists('vouchere', 'cod')->where(function ($query) use($request) {
    //                 return $query->where('activ', 1)
    //                     ->whereDate('data_expirare', '>=', now())
    //                     // 0 means that the voucher is applied to the entire cart
    //                     ->whereIn('id_produs', array_keys(($request->get('produse') ?? []) + ['0']));
    //             }),
    //         ],
    //         'curier' => ['required', 'integer', 'min:1', 
    //             Rule::exists('curieri', 'id')->where(function ($query) {
    //                 return $query->where('activ', 1)
    //                     ->where('activ', '1');
    //             }),
    //         ],
    //         'tip_plata' => ['required', 'integer', 'min:1', 'max:3'],
    //         'detalii_comanda' => ['nullable', 'string', 'min:1', 'max:3000'],
    //     ],[
    //         // 'produse.*' => __('Produsul nu este valabil.'),
    //         'voucher.*' => __('Voucher-ul nu este valabil.'),
    //         'tip_plata.*' => __('Metoda de plata nu este valabila.'),
    //         'curier.*' => __('Modalitatea de expediere nu este valabila.'),
    //     ],[
    //         'produse' => __('produse'),
    //         'produse.*' => __('produs'),
    //         'voucher' => __('voucher'),
    //         'curier' => __('curier'),
    //         'tip_plata' => __('metoda de plata'),
    //     ])->validate());

    //     $user = auth()->user();
    //     $cantitati = $inputs['produse'];
    //     $produse = Produs::whereIn('id', array_keys($cantitati))->get();

    //     $cart['courier']['id'] = $inputs['curier'];
    //     $cart['payment']['id'] = $inputs['tip_plata'];
    //     if(isset($inputs['voucher'])) {
    //         $cart['voucher']['code'] = $inputs['voucher'];
    //     }

    //     // organize products 
    //     $produseComanda = [];
    //     foreach($produse as $produs) {
    //         // prepare product values
    //         $price = $produs->pret_normal;
    //         $priceWithTva = $produs->pret_cu_tva;
    //         $valueTva = $priceWithTva - $price;
    //         $qty = $cantitati[$produs->id];

    //         // create new cart
    //         $cart['items'][$produs->id] = [
    //             'id' => $produs->id,
    //             'price_with_tva' => $priceWithTva,
    //             'price' => $price,
    //             'qty' => $qty,
    //             'value_tva' => $valueTva,
    //             'total' => $price * $qty,
    //             'total_with_tva' => $priceWithTva * $qty,
    //         ];

    //         // build products array for the order
    //         $produseComanda[$produs->id] = [
    //             'cantitate' => $qty,
    //             'pret' => $price,
    //             'filtre' => '',
    //             'sincronizat' => '0',
    //         ];
    //     }

    //     // calculate cart
    //     $cart = $this->calculate($cart, 1);

    //     // create order
    //     $comanda = Comanda::create([
    //         'id_user' => $user->id,
    //         'id_curier' => $inputs['curier'],
    //         'id_tip_plata' => $inputs['tip_plata'], 
    //         'mesaj' => $inputs['detalii_comanda'] ?? null, 
    //         'nr_factura' => Comanda::getNextNumarFactura(),
    //         'tva' => setare('COTA_TVA'),
    //         'stare' => '0',
    //         'taxa_livrare' => $cart['courier']['price'] + ($cart['payment']['return_price'] ?? 0),
    //         'ip' => $request->ip(),
    //         'data_adaugare' => now(),
    //         'nota_interna' => '',
    //     ] + (isset($cart['voucher']) ? [
    //         'cod_voucher' => $cart['voucher']['code'],
    //         'valoare_voucher' => -1 * $cart['voucher']['value'],
    //     ] : [
    //         'cod_voucher' => '',
    //     ]) + (isset($cart['discount_fidelitate']) ? [
    //         'discount_fidelitate' => $cart['discount_fidelitate']['percent'],
    //         'valoare_discount_fidelitate' => -1 * $cart['discount_fidelitate']['value'],
    //     ] : []) + (isset($cart['discount_plata_op']) ? [
    //         'discount_plata_op' => $cart['discount_plata_op']['percent'],
    //         'valoare_discount_plata_op' => -1 * $cart['discount_plata_op']['value'],
    //     ] : []));

    //     // add products to the new order
    //     $comanda->produse()->sync($produseComanda);

    //     // clear cart session
    //     session()->pull('cart');

    //     // update stoc
    //     foreach($produse as $produs) {
    //         $produs->stoc -= $cantitati[$produs->id];
    //         $produs->save();
    //     }

    //     try {
    //         // prepare data for emails
    //         $details = [
    //             'comanda' => $comanda,
    //         ] + setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']);

    //         // send emails
    //         SendMails::dispatch($user, $details, [2]);

    //     } catch(\Exception $e) { \Log::info($e); }

    //     switch ($comanda->id_tip_plata) {
    //         case 1:
    //             $data['status'] = __('Comanda a fost salvata cu succes. Modalitatea de plata aleasa este ramburs. <a href=":url">Vezi datele comenzii</a>', ['url' => route('profile.orders.show', $comanda->id)]); 
    //             break;
    //         case 2:
    //             $data['status'] = __('Comanda a fost salvata cu succes. Veti fi redirectionat spre pagina de plata... <br/> <img class="mt-4" src=":src">', ['src' => asset('images/netopia_payments.png')]);

    //             $data['form'] = null;

    //             $valoare = $comanda->valoare;

    //             $paymentGateway = app(PaymentGateway::class, [
    //                 'returnURL'     => route('profile.orders.show', $comanda->id),
    //                 'confirmURL'    => route('cart.confirm'),
    //                 'amount'        => $valoare,
    //                 'details'       => __('Plata cu cardul pentru suma :sum', ['sum' => $valoare]),
    //                 'signature'     => setare('MOBILPAY_CHEIE_UNICA'),
    //                 'firstName'     => $user->prenume,
    //                 'lastName'      => $user->nume,
    //                 'email'         => $user->user_email,
    //                 'address'       => $user->adresa,
    //                 'phone'         => $user->telefon,
    //                 'params'        => array('comanda_id' => $comanda->id ),
    //                 'type'          => $user->tip == '2' ? 'company' : 'person', // 'company',
    //                 'livePublicFilePath'     => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_LIVE_PUBLIC_CERTIFICATE')),
    //                 'sandboxPublicFilePath'  => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_SANDBOX_PUBLIC_CERTIFICATE')),
    //                 'livePrivateFilePath'    => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_LIVE_PRIVATE_CERTIFICATE')),
    //                 'sandboxPrivateFilePath' => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_SANDBOX_PRIVATE_CERTIFICATE')),
    //             ]);
    //             // generate a mobilpay order id until it is unique
    //             do {
    //                 $orderId = $paymentGateway->generateOrderId();
    //             } while(Comanda::where('order_id', $orderId)->count() > 1);

    //             // retrieve the mobilpayform
    //             $data['form'] = $paymentGateway->setForm();

    //             // save the mobilpay id to the new order
    //             $comanda->order_id = $orderId;
    //             $comanda->save();
    //             break;
    //         case 3:
    //             $data['status'] = __('Comanda a fost salvata cu succes. O factura proforma a fost generata si trimisa catre adresa dvs. de email. <br /><a href=":url">Vezi datele comenzii</a>', ['url' => route('profile.orders.show', $comanda->id)]); 
    //             break;
    //     }

    //     $data['urmarire_conversie'] = '<!-- Google Code for Comanda Conversion Page -->
    //         <script type="text/javascript">
    //             /* <![CDATA[ */
    //             var google_conversion_id = 1000677025;
    //             var google_conversion_language = "ro";
    //             var google_conversion_format = "3";
    //             var google_conversion_color = "ffffff";
    //             var google_conversion_label = "jmRdCK-S0gMQob2U3QM";
    //             var google_conversion_value = 0;
    //             var google_remarketing_only = false;
    //             /* ]]> */
    //             </script>
    //             <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    //         </script>
    //         <noscript>
    //             <div style="display:inline;">
    //             <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1000677025/?value=0&amp;label=jmRdCK-S0gMQob2U3QM&amp;guid=ON&amp;script=0"/>
    //             </div>
    //         </noscript>';

    //     return view('checkout', $data);
    // }

    // public function confirm()
    // {
    //     $paymentGateway = app(PaymentGateway::class, [
    //         'livePublicFilePath'     => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_LIVE_PUBLIC_CERTIFICATE')),
    //         'sandboxPublicFilePath'  => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_SANDBOX_PUBLIC_CERTIFICATE')),
    //         'livePrivateFilePath'    => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_LIVE_PRIVATE_CERTIFICATE')),
    //         'sandboxPrivateFilePath' => storage_path('app/certificates/mobilpay/'.env('MOBILPAY_SANDBOX_PRIVATE_CERTIFICATE')),
    //     ]);
    //     $data = $paymentGateway->confirm();
        
    //     if(isset($data['status']) && $data['status'] == 'confirmed') {
    //         $comanda = Comanda::where('id', $data['params']['comanda_id'])->first();
    //         $comanda->update(['stare_plata' => 1]);

    //         try {
    //             // prepare data for emails
    //             $details = setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']) + [
    //                 'comanda' => $comanda,
    //             ];
    //             // send email
    //             SendMails::dispatch($comanda->user, $details, [3]);

    //         } catch(\Exception $e) { \Log::info($e); }

    //     } elseif(isset($data['status']) && in_array($data['status'], ['canceled','credit','default','rejected'])) {
    //         Comanda::where('id', $data['params']['comanda_id'])->update(['stare_plata' => -1]);
    //     } else {
    //         Comanda::where('id', $data['params']['comanda_id'])->update(['stare_plata' => 0]);
    //     }
    // }
}
