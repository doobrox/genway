<x-app-layout :title="__('Recuperare parola')">

        <div class="well well-lg my-5 mw-sm mx-auto">
            <h3 class="mb-2">{{ __('Recuperare parola') }}</h3>
            <p class="mb-3">{{ __('Pentru a recupera parola introduceti adresa dvs. de email si veti primi un link prin care veti putea confirma recuperarea parolei, apoi parola dvs. va fi schimbata.') }}</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('password.email') }}" class="row">
                @csrf

                <!-- Email Address -->
                <div class="col-12 form-group">
                    <label for="email">{{ __('Email') }}:</label>
                    <input id="email" class="form-control" type="email" name="user_email" value="{{ old('email') }}" required autofocus />
                </div>

                <div class="col-12 form-group">
                    <button class="btn btn-dark m-0" id="login-form-submit" name="login-form-submit">
                        {{ __('Recupereaza parola') }}
                    </button>
                </div>
            </form>
        </div>
</x-app-layout>
