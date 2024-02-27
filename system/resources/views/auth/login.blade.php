<x-app-layout :title="__('Login')">
    @push('styles')
    @endpush
    @push('scripts')
        <script src="{{ asset('js/utility.js') }}"></script>
    @endpush
    <div class="row">
        <div class="col-md-4 md:border-end border-light">
            <div class="well well-lg mb-0 me-md-2">
                <h3 class="mb-2">{{ __('Intra in cont') }}</h3>
                <p class="mb-3">{{ __('Pentru a intra in cont introduceti informatiile pe care le-ati folosit la inregistrare.') }}</p>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" :bag="'login'" />

                <form id="login-form" name="login-form" class="row" action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="col-12 form-group">
                        <label for="login-email">{{ __('Adresa de email') }}:</label>
                        <input type="email" id="login-email" name="user_email" value="{{ old('user_email') }}" class="form-control" required>
                    </div>

                    <div class="col-12 form-group">
                        <label for="login-password">{{ __('Password') }}:</label>
                        <input type="password" id="login-password" name="password" class="form-control" required>
                    </div>

                    <div class="col-12 form-group">
                        <label class="form-check-label" for="remember">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <span>{{ __('Tine-ma minte') }}</span>
                        </label>
                    </div>

                    <div class="col-12 form-group">
                        <button class="btn btn-dark m-0" id="login-form-submit" name="login-form-submit">{{ __('Login') }}</button>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="float-end">{{ __('Ai uitat parola?') }}</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="well well-lg mb-0 ms-md-2">

                <h3 class="mb-2">{{ __('Nu ai cont? Inregistreaza-te') }}</h3>

                <p class="mb-3">{{ __('Daca iti vei creea un cont vei putea ulterior cumpara produse fara completarea tuturor datele de livrare si de contact. Va multumim!') }}</p>
                @if($errors->register->any())
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" :bag="'register'" />
                @endif

                <form id="register-form" name="register-form" class="row" action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="col-12 form-group">
                        <label for="tip">{{ __('Tip cont') }}:</label>
                        <select class="form-select" id="tip" name="tip" 
                            onchange="$(this).val() == '1' ? $('#company-fields').hide() : $('#company-fields').show();">
                            <option value="1" {{ old('tip') == '1' ? 'selected' : '' }}>{{ __('Persoana fizica') }}</option>
                            <option value="2" {{ old('tip') == '2' ? 'selected' : '' }}>{{ __('Firma') }} - {{ __('Persoana juridica') }}</option>
                        </select>
                    </div>

                    <div id="company-fields" class="row col-12 m-0 p-0" style="display: {{ old('tip') == '2' ? 'flex' : 'none' }};">
                        <div class="col-12 col-sm-6 form-group">
                            <label for="nume_firma">{{ __('Firma') }}*:</label>
                            <input type="text" id="nume_firma" name="nume_firma" value="{{ old('nume_firma') }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="cui">{{ __('CUI') }}*:</label>
                            <input type="text" id="cui" name="cui" value="{{ old('cui') }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="nr_reg_comert">{{ __('Nr. Reg. Com.') }}*:</label>
                            <input type="text" id="nr_reg_comert" name="nr_reg_comert" value="{{ old('nr_reg_comert') }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="autorizatie_igpr">{{ __('Autorizatie IGPR (optional)') }}:</label>
                            <input type="text" id="autorizatie_igpr" name="autorizatie_igpr" value="{{ old('autorizatie_igpr') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="nume">{{ __('Nume') }}*:</label>
                        <input type="text" id="nume" name="nume" value="{{ old('nume') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="prenume">{{ __('Prenume') }}*:</label>
                        <input type="text" id="prenume" name="prenume" value="{{ old('prenume') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="cnp">{{ __('CNP') }}*:</label>
                        <input type="text" id="cnp" name="cnp" value="{{ old('cnp') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="telefon">{{ __('Telefon') }}*:</label>
                        <input type="text" id="telefon" name="telefon" value="{{ old('telefon') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="email">{{ __('Adresa de email') }}*:</label>
                        <input type="email" id="email" name="user_email" value="{{ old('user_email') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="judet">{{ __('Judet') }}*:</label>
                        <select class="form-select" id="judet" name="id_judet"
                                data-url="{{ route('localitati.html') }}" 
                                onchange="getLocalitatiInOptions(this, '#localitate')">
                            <option value="">{{ __('Selecteaza judet') }}</option>
                            @foreach($judete as $judet)
                                <option value="{{ $judet->id }}" {{ old('id_judet') == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="localitate">{{ __('localitate') }}*:</label>
                        <select class="form-select" id="localitate" name="id_localitate">
                            @forelse($localitati as $localitate)
                                <option value="{{ $localitate->id }}" {{ old('id_localitate') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
                            @empty
                                <option value="">{{ __('Selecteaza localitate') }}</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="adresa">{{ __('Adresa') }}*:</label>
                        <input type="text" id="adresa" name="adresa" value="{{ old('adresa') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="password">{{ __('Parola') }}*:</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="password_confirmation">{{ __('Confirma Parola') }}*:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" value="" class="form-control">
                    </div>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="newsletter">
                            <input type="checkbox" name="newsletter" class="form-check-input" id="newsletter" value="1" 
                                {{ old('newsletter') != '1' ? '' : 'checked' }}>
                            <span>{{ __('Ma abonez la newsletter') }}</span>
                        </label>
                    </div>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="reseller_cerere">
                            <input type="checkbox" name="reseller_cerere" class="form-check-input" id="reseller_cerere" value="1"
                                {{ old('reseller_cerere') ? 'checked' : '' }}>
                            <span>{!! __('Doresc sa devin reseller. <a href=":href">Afla avantajele</a> devenirii reseller', [
                                'href' => route('page', 'avantaje-reseller'),
                            ]) !!}</span>
                        </label>
                    </div>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="terms">
                            <input type="checkbox" name="terms" class="form-check-input" id="terms" value="1"
                                {{ old('terms') ? 'checked' : '' }}>
                            <span>{!! __('Sunt de acord cu <a href=":href">Termenii si conditiile</a> siteului', [
                                'href' => route('page', 'termeni-si-conditii'),
                            ]) !!}</span>
                        </label>
                    </div>

                    <h4 class="mt-4">{{ __('Adresa de livrare') }}</h4>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="livrare_adresa_1">
                            <input type="checkbox" name="livrare_adresa_1" class="form-check-input" id="livrare_adresa_1" 
                                value="1" 
                                {{ old('livrare_adresa_1') ? 'checked' : '' }}
                                onchange="$(this).prop('checked') ? $('#shipping-address').show() : $('#shipping-address').hide();">
                            <span>{{ __('Adresa de livrarea difera de adresa de contact') }}</span>
                        </label>
                    </div>

                    <div id="shipping-address" class="row col-12 m-0 p-0" style="display: {{ old('livrare_adresa_1') ? 'flex' : 'none' }};">

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_judet">{{ __('Judet') }}*:</label>
                            <select class="form-select" id="livrare_judet" name="livrare_id_judet"
                                data-url="{{ route('localitati.html') }}" 
                                onchange="getLocalitatiInOptions(this, '#livrare_localitate')">
                                <option value="">{{ __('Selecteaza judet') }}</option>
                                @foreach($judete as $judet)
                                    <option value="{{ $judet->id }}" {{ old('livrare_id_judet') == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_localitate">{{ __('localitate') }}*:</label>
                            <select class="form-select" id="livrare_localitate" name="livrare_id_localitate">
                                @forelse($localitatiLivrare as $localitate)
                                    <option value="{{ $localitate->id }}" {{ old('livrare_id_localitate') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
                                @empty
                                    <option value="">{{ __('Selecteaza localitate') }}</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_adresa">{{ __('Adresa') }}*:</label>
                            <input type="text" id="livrare_adresa" name="livrare_adresa" value="{{ old('livrare_adresa') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 form-group">
                        <button class="btn btn-dark m-0" type="submit" id="register-form-submit" name="register-form-submit" value="register">{{ __('Register Now') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
