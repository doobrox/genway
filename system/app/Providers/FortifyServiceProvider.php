<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Judet;
use App\Models\Localitate;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->user_email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {

            $request->validateWithBag('login', [
                'user_email'    => 'required|email',
                'password' => 'required|max:255',
                'remember' => 'nullable|max:255',
            ]);

            $user = User::where('user_email', $request->input(Fortify::username()))->first();

            if ($user && (\Hash::check($request->password, $user->password))) {
                return $user;
            } elseif($user && md5($request->password) == $user->user_pass) {
                $user->password = \Hash::make($request->password);
                $user->save();
                return $user;
            }

        });

        Fortify::loginView(fn() => view('auth.login', [
            'judete' => Judet::select('id','nume')->get(),
            'localitati' => old('id_judet') 
                ? Localitate::select('id','nume')->where('id_judet', old('id_judet'))->get()
                : [],
            'localitatiLivrare' => old('livrare_id_judet') 
                ? Localitate::select('id','nume')->where('id_judet', old('livrare_id_judet'))->get()
                : [],
        ]));
        Fortify::registerView(fn() => redirect()->route('login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn(Request $request) => view('auth.reset-password', ['request' => $request]));
    }
}
