<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CIAuth
{
    public function handle(Request $request, Closure $next)
    {
        // daca userul este deja autentificat, dar are alt email decat cel primit in request, facem logout
        if(auth()->check() && $request->input('user_email') && auth()->user()->user_email != $request->input('user_email')) {
            auth()->logout();
        }

        // if(auth()->check() && auth()->id() == '413' || $request->input('user_email') == 'victor@zao.media') {
        //     dd($request->input('user_email'));
        // }

        //TEST ONLY !!!
        if(1==1){
            $user = User::where('user_email', 'antonio@facturis-online.ro')->first();
            Auth::login($user);
        }


        if(auth()->guest()) {
            if(
                $this->getHost('https://www.old.genway.ro/') == $this->getHost($request->header('Origin'))
                || $this->getHost('https://www.old.genway.ro/') == $this->getHost($request->header('Referer'))
            ) {
                $user = $request->input('user_email')
                    ? User::where('user_email', $request->input('user_email'))->first()
                    : User::where('id', $request->input('user_id'))->first();
                $key = openssl_decrypt($request->key, 'AES-256-CBC', date('Y-m-d H'));

                if (
                    $user
                    && (\Hash::check($key, $user->password) || $key == $user->user_pass)
                    // && $user->can('platforme.ofertare')
                ) {
                    Auth::login($user);
                    $request->request->remove('user_email');
                    $request->request->remove('key');
                } else {
                    return redirect('https://www.old.genway.ro/ofertare');
                }
            } else {
                return redirect('https://www.old.genway.ro/ofertare');
            }
        // } elseif(!auth()->user()->can('platforme.ofertare')) {
        //     return redirect('https://www.old.genway.ro/ofertare');
        } else {
            $request->request->remove('user_email');
            $request->request->remove('key');
        }

        return $next($request);
    }

    protected function getHost($address)
    {
        $parseUrl = parse_url(trim($address));
        $path = isset($parseUrl['path']) ? $parseUrl['path'] : '';
        $host = isset($parseUrl['host']) ? $parseUrl['host'] : '';
        if(trim(!empty($host))) {
            return $host;
        } else {
            $path = explode('/', $path, 2);
            return array_shift($path);
        }
    }
}
