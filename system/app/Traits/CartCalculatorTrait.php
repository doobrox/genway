<?php

namespace App\Traits;

use App\Models\Curier;
use App\Models\Produs;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Validation\Rule;

trait CartCalculatorTrait
{
    protected function calculate($cart, $return = 0)
    {
        $cart['total'] = 0;
        $cart['total_tva'] = 0;
        $cart['total_with_tva'] = 0;
        $cart['total_cart'] = 0;
        foreach($cart['items'] ?? [] as $item) {
            $cart['total'] += $item['total'];
            $cart['total_tva'] += ($item['value_tva'] * $item['qty']);
            $cart['total_with_tva'] += $item['total_with_tva'];
        }
        $cart['total_cart'] = $cart['total_with_tva'];
        if($cart['total'] == 0) {
            session()->pull('cart');
        } else {
            // check if the user has added a voucher
            if(isset($cart['voucher']) && $cart['voucher']['value'] = $this->calculateVoucher($cart, $return)) {
                $cart['total_cart'] = $cart['total_cart'] > $cart['voucher']['value']
                    ? $cart['total_cart'] - $cart['voucher']['value']
                    : 0;
            } else {
                unset($cart['voucher']);
            }
            // check if the user selected a payment method
            // and apply a discount or a tax if available
            if(!isset($cart['payment']['id'])) {
                $cart['payment']['id'] = '1';
                $cart['payment']['return_price'] = 5.95;
                $cart['total_cart'] += $cart['payment']['return_price'];
            } elseif($cart['payment']['id'] == '1') {
                $cart['payment']['return_price'] = 5.95;
                $cart['total_cart'] += $cart['payment']['return_price'];
            } elseif($cart['payment']['id'] == '3') {
                $cart['discount_plata_op'] = User::discount($cart);
                if($cart['discount_plata_op']['value'] > 0) {
                    $cart['total_cart'] -= $cart['discount_plata_op']['value'];
                } else { 
                    unset($cart['discount_plata_op']);
                }
                unset($cart['payment']['return_price']);
            } else {
                unset($cart['discount_plata_op']);
                unset($cart['payment']['return_price']);
            }
            // check if the user has a special discount
            if(auth()->check()) {
                $cart['discount_fidelitate'] = User::discount($cart, 2);
                if($cart['discount_fidelitate']['value'] > 0) {
                    $cart['total_cart'] -= $cart['discount_fidelitate']['value'];
                } else { 
                    unset($cart['discount_fidelitate']);
                }
            } else {
                unset($cart['discount_fidelitate']);
            }
            // check if the user selected a courier
            if(isset($cart['courier']['id'])) {
                $curier = Curier::firstWhere('id', $cart['courier']['id']);
            } else {
                $curier = Curier::where('default', '1')->orWhereNotNull('id')->first();
                $cart['courier']['id'] = $curier ? $curier->id : null;
            }

            if($curier) {
                // calculate new courier price
                $cart['courier']['price'] = $curier->pret($cart, $cart['courier']['locality'] ?? null);
                $cart['total_cart'] += $cart['courier']['price'];
            }
            session()->put('cart', $cart);
        }
        if($return) {
            return $cart;
        } else {
            return true;
        }
    }

    protected function calculateVoucher($cart = null, $return = 0)
    {
        $cart = $cart ?? session('cart');
        $voucher = isset($cart['voucher']['code']) 
            ? Voucher::where('cod', $cart['voucher']['code'])
                ->where('activ', 1)
                ->whereDate('data_expirare', '>=', now())
                // 0 means that the voucher is applied to the entire cart
                ->whereIn('id_produs', array_keys(($cart['items'] ?? []) + ['0']))
                ->first()
            : null;

        // if the cart has items and the voucher can be used multiple times
        // a voucher "caracter" (the number of times that can be used) can be permanent(1) or a single time(2)
        if($voucher && ($voucher->caracter != '2' || $voucher->comenzi()->count() <= 1)) {
            // if the voucher is applied to the entire cart
            if($voucher->id_produs == '0') {
                
                // if the voucher can be used a single time
                // and the order was created
                if($voucher->caracter == '2' && $return != 0) {
                    $voucher->activ = 0;
                    $voucher->save();
                }
                return $voucher->tip == '1' 
                    ? $voucher->valoare
                    : round($cart['total'] * $voucher->valoare / 100, 2);

            } elseif($produs = Produs::firstWhere('id', $voucher->id_produs)) { // if the voucher is applied to a single product

                if($voucher->caracter == '2' && $return != 0) {
                    $voucher->activ = 0;
                    $voucher->save();
                }
                $price = $produs->pret_normal;
                return $voucher->tip == '1' 
                    ? ( $price > $voucher->valoare ? $voucher->valoare : $voucher->valoare - $price)
                    : round($price * $voucher->valoare / 100, 2);
                    
            } else {
                // unset voucher, it is not available
                return false;
            }
        }
        return false;
    }

    protected function getCartValidationParams($param = null)
    {
        switch($param) {
            case 1:
                return $this->cartRules();
            case 2:
                return $this->cartMessages();
            case 3:
                return $this->cartNames();
            default:
                return [
                    'rules' => $this->cartRules(),
                    'messages' => $this->cartMessages(),
                    'names' => $this->cartNames(),
                ];
        }
        return false;
    }

    private function cartRules()
    {
        return [
            'produse' => ['required', 'array', 'min:1'],
            'produse.*' => ['required', 'numeric', 'min:1',
                function($attribute, $value, $fail) {
                    if (
                        Produs::where('id', explode('.', $attribute)[1])
                            ->where('stoc', '>=', $value)
                            ->count() < 1
                    ) {
                        return $fail(__('Nu mai sunt destule produse de acest tip in stoc.'));
                    }
                },
            ],
            'voucher' => ['nullable', 'string', 'min:1', 'max:255', 
                Rule::exists('vouchere', 'cod')->where(function ($query) {
                    return $query->where('activ', 1)
                        ->whereDate('data_expirare', '>=', now())
                        // 0 means that the voucher is applied to the entire cart
                        ->whereIn('id_produs', array_keys((request()->get('produse') ?? []) + ['0']));
                }),
            ],
            'curier' => ['required', 'integer', 'min:1', 
                Rule::exists('curieri', 'id')->where(function ($query) {
                    return $query->where('activ', 1)
                        ->where('activ', '1');
                }),
            ],
            'tip_plata' => ['required', 'integer', 'min:1', 'max:3'],
            'detalii_comanda' => ['nullable', 'string', 'min:1', 'max:3000'],
        ];
    }
    
    private function cartMessages()
    {
        return [
            // 'produse.*' => __('Produsul nu este valabil.'),
            'voucher.*' => __('Voucher-ul nu este valabil.'),
            'tip_plata.*' => __('Metoda de plata nu este valabila.'),
            'curier.*' => __('Modalitatea de expediere nu este valabila.'),
        ];
    }

    private function cartNames()
    {
        return [
            'produse' => __('produse'),
            'produse.*' => __('produs'),
            'voucher' => __('voucher'),
            'curier' => __('curier'),
            'tip_plata' => __('metoda de plata'),
        ];
    }
}
