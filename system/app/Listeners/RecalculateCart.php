<?php
namespace App\Listeners;

use App\Models\Produs;
use App\Traits\CartCalculatorTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecalculateCart
{
    use CartCalculatorTrait;
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // recalculate the items from the cart
        if(session()->has('cart.items')) {
            $cart = session('cart');
            $produse = Produs::whereIn('id', array_keys($cart['items']))->get();
            foreach ($produse as $produs) {
                if($produs->stoc > $cart['items'][$produs->id]['qty']) {
                    $qty = $cart['items'][$produs->id]['qty'];
                    $price = $produs->pret;
                    $priceWithTva = $produs->pret_cu_tva;
                    $valueTva = $priceWithTva - $price;
                    // cart product new prices
                    $cart['items'][$produs->id]['price_with_tva'] = $priceWithTva;
                    $cart['items'][$produs->id]['price'] = $price;
                    $cart['items'][$produs->id]['value_tva'] = $valueTva;
                    $cart['items'][$produs->id]['total'] = $price * $qty;
                    $cart['items'][$produs->id]['total_with_tva'] = $priceWithTva * $qty;
                } else {
                    unset($cart['items'][$produs->id]);
                }
            }
            $this->calculate($cart);
        }
    }
}
