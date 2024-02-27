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

                <h3 class="mb-2">{{ __('Profilul meu') }}</h3>

                <p class="mb-3">{{ __('Editeaza informatiile de profil') }}</p>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-0" :status="session('status')" />
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form name="profile-form" class="row" action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <div class="col-12 form-group">
                        <label for="tip">{{ __('Tip cont') }}:</label>
                        <select class="form-select" id="tip" name="tip" 
                            onchange="$(this).val() == '1' ? $('#company-fields').hide() : $('#company-fields').show();">
                            <option value="1" {{ old('tip', $user->tip) == '1' ? 'selected' : '' }}>{{ __('Persoana fizica') }}</option>
                            <option value="2" {{ old('tip', $user->tip) == '2' ? 'selected' : '' }}>{{ __('Firma') }} - {{ __('Persoana juridica') }}</option>
                        </select>
                    </div>

                    <div id="company-fields" class="row col-12 m-0 p-0" style="display: {{ old('tip', $user->tip) == '2' ? 'flex' : 'none' }};">
                        <div class="col-12 col-sm-6 form-group">
                            <label for="nume_firma">{{ __('Firma') }}*:</label>
                            <input type="text" id="nume_firma" name="nume_firma" value="{{ old('nume_firma', $user->nume_firma) }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="cui">{{ __('CUI') }}*:</label>
                            <input type="text" id="cui" name="cui" value="{{ old('cui', $user->cui) }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="nr_reg_comert">{{ __('Nr. Reg. Com.') }}*:</label>
                            <input type="text" id="nr_reg_comert" name="nr_reg_comert" value="{{ old('nr_reg_comert', $user->nr_reg_comert) }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="autorizatie_igpr">{{ __('Autorizatie IGPR (optional)') }}:</label>
                            <input type="text" id="autorizatie_igpr" name="autorizatie_igpr" value="{{ old('autorizatie_igpr', $user->autorizatie_igpr) }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="nume">{{ __('Nume') }}*:</label>
                        <input type="text" id="nume" name="nume" value="{{ old('nume', $user->nume) }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="prenume">{{ __('Prenume') }}*:</label>
                        <input type="text" id="prenume" name="prenume" value="{{ old('prenume', $user->prenume) }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="cnp">{{ __('CNP') }}*:</label>
                        <input type="text" id="cnp" name="cnp" value="{{ old('cnp', $user->cnp) }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="telefon">{{ __('Telefon') }}*:</label>
                        <input type="text" id="telefon" name="telefon" value="{{ old('telefon', $user->telefon) }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="email">{{ __('Adresa de email') }}*:</label>
                        <input type="email" id="email" name="user_email" value="{{ old('user_email', $user->user_email) }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="judet">{{ __('Judet') }}*:</label>
                        <select class="form-select" id="judet" name="id_judet"
                                data-url="{{ route('localitati.html') }}" 
                                onchange="getLocalitatiInOptions(this, '#localitate')">
                            <option value="">{{ __('Selecteaza judet') }}</option>
                            @foreach($judete as $judet)
                                <option value="{{ $judet->id }}" {{ old('id_judet', $localitateFacturare->id_judet ?? null) == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="localitate">{{ __('localitate') }}*:</label>
                        <select class="form-select" id="localitate" name="id_localitate">
                            @forelse($localitati as $localitate)
                                <option value="{{ $localitate->id }}" {{ old('id_localitate', $localitateFacturare->id ?? null) == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
                            @empty
                                <option value="">{{ __('Selecteaza localitate') }}</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="adresa">{{ __('Adresa') }}*:</label>
                        <input type="text" id="adresa" name="adresa" value="{{ old('adresa', $user->adresa) }}" class="form-control">
                    </div>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="newsletter">
                            <input type="checkbox" name="newsletter" class="form-check-input" id="newsletter" value="1" 
                                {{ old('newsletter', $user->newsletter) != '1' ? '' : 'checked' }}>
                            <span>{{ __('Ma abonez la newsletter') }}</span>
                        </label>
                    </div>

                    <h4 class="mt-4">{{ __('Adresa de livrare') }}</h4>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="livrare_adresa_1">
                            <input type="checkbox" name="livrare_adresa_1" class="form-check-input" id="livrare_adresa_1" 
                                value="1" 
                                {{ old('livrare_adresa_1', $user->livrare_adresa_1) ? 'checked' : '' }}
                                onchange="$(this).prop('checked') ? $('#shipping-address').show() : $('#shipping-address').hide();">
                            <span>{{ __('Adresa de livrarea difera de adresa de contact') }}</span>
                        </label>
                    </div>

                    <div id="shipping-address" class="row col-12 m-0 p-0" style="display: {{ old('livrare_adresa_1', $user->livrare_adresa_1) ? 'flex' : 'none' }};">

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_judet">{{ __('Judet') }}*:</label>
                            <select class="form-select" id="livrare_judet" name="livrare_id_judet"
                                data-url="{{ route('localitati.html') }}" 
                                onchange="getLocalitatiInOptions(this, '#livrare_localitate')">
                                <option value="">{{ __('Selecteaza judet') }}</option>
                                @foreach($judete as $judet)
                                    <option value="{{ $judet->id }}" {{ old('livrare_id_judet', $localitateLivrare ? $localitateLivrare->id_judet : '') == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_localitate">{{ __('localitate') }}*:</label>
                            <select class="form-select" id="livrare_localitate" name="livrare_id_localitate">
                                @forelse($localitatiLivrare as $localitate)
                                    <option value="{{ $localitate->id }}" {{ old('livrare_id_localitate', $localitateLivrare ? $localitateLivrare->id : '') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
                                @empty
                                    <option value="">{{ __('Selecteaza localitate') }}</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_adresa">{{ __('Adresa') }}*:</label>
                            <input type="text" id="livrare_adresa" name="livrare_adresa" value="{{ old('livrare_adresa', $user->livrare_adresa) }}" class="form-control">
                        </div>
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