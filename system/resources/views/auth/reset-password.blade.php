<x-app-layout :title="__('Recuperare parola')">

    <div class="well well-lg my-5 mw-sm mx-auto">
        <h3 class="mb-2">{{ __('Resetare parola') }}</h3>
        <p class="mb-3">{{ __('Pentru a recupera parola introduceti adresa dvs. de email si veti primi un link prin care veti putea confirma recuperarea parolei, apoi parola dvs. va fi schimbata.') }}</p>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" class="row" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="col-12 form-group">
                <label for="email">{{ __('Email') }}:</label>
                <input id="email" class="form-control" type="email" name="user_email" value="{{ old('email', $request->email) }}" required />
            </div>

            <!-- Password -->
            <div class="col-12 col-sm-6 form-group">
                <label for="password">{{ __('Password') }}:</label>
                <input id="password" class="form-control" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="col-12 col-sm-6 form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}:</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
            </div>

            <div class="col-12 form-group">
                <button class="btn btn-dark m-0" id="login-form-submit" name="login-form-submit">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>

</x-guest-layout>
