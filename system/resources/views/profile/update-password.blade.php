<x-app-layout>
    @push('styles')
    @endpush
    @push('scripts')
    @endpush
    <div class="row">
        <div class="col-lg-10 order-lg-last ps-lg-3">
            <div class="heading-block border-0">
                <h3>{{ auth()->user()->nume }} {{ auth()->user()->prenume }}</h3>
                <span>{{ auth()->user()->user_email }}</span>
            </div>
            <div class="well well-lg mb-0 ms-md-2">

                <h3 class="mb-2">{{ __('Schimba parola') }}</h3>

                <p class="mb-3">{{ __('Pentru a putea schimba este necesar sa introduceti parola actuala a contului.') }}</p>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-3" :errors="$errors" />

                <form name="profile-form" class="row" action="{{ route('profile.password.update') }}" method="post">
                    @csrf

                     <div class="col-12 col-sm-6 form-group">
                        <label for="current-password">{{ __('Parola curenta') }}*:</label>
                        <input type="password" id="current-password" name="current_password" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="password">{{ __('Parola Noua') }}*:</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="password-confirmation">{{ __('Confirma Parola Noua') }}*:</label>
                        <input type="password" id="password-confirmation" name="password_confirmation" class="form-control">
                    </div>

                    <div class="col-12 form-group">
                        <button class="btn btn-dark m-0" type="submit" id="profile-form-submit" name="profile-form-submit">{{ __('Salveaza') }}</button>
                    </div>

                </form>
            </div>
        </div>
        <x-sidebar-layout />
    </div>
</x-app-layout>