<?php

namespace App\Providers;

use App\Billing\PaymentGateway;
use App\Billing\Mobilpay\MobilpayPaymentGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentGateway::class, function($app, $array = []) {
        	return new MobilpayPaymentGateway($array);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
