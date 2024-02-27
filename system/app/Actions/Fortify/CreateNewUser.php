<?php

namespace App\Actions\Fortify;

use App\Events\SendMails;
use App\Mail\TemplateEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, User::rules(),User::messages(),User::names())->validateWithBag('register'); 

        $pass = $input['password'];
        $input['newsletter'] = isset( $input['newsletter'] ) ? 1 : 0;
        $input['reseller_cerere'] = isset( $input['reseller_cerere'] ) ? 1 : 0;
        $input['livrare_adresa_1'] = isset( $input['livrare_adresa_1'] ) ? 1 : 0;
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        try {
            $details = setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']);
            $details['pass'] = $pass;

            SendMails::dispatch($user, $details, [1]);

        } catch(\Exception $e) { \Log::info($e); }

        return $user;
    }
}
