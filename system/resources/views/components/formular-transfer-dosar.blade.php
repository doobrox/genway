@pushOnce('scripts')
<script src="{{ asset('js/forms.js') }}" defer></script>
@endPushOnce
<div id="submit-message-dosare" class="px-0"><div class="sb-msg"></div></div>

<form id="transfer-dosare" action="javascript:void(0)" 
    data-form-ajax="1" 
    data-message-wrapper="#submit-message-dosare" 
    data-action="https://www.old.genway.ro/pagina/formular_transfer">
    <input type="text" name="nume" id="nume" class="form-control required mt-2 mb-2" value="{{ old('nume') }}" placeholder="Nume si prenume">
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="judet" id="judet"
        data-url="{{ route('localitati.html') }}" 
        onchange="getLocalitatiInOptions(this, '#localitate')">
        <option value="">{{ __('Selecteaza judet') }}</option>
        @foreach($judete as $judet)
            <option value="{{ $judet->id }}">{{ $judet->nume }}</option>
        @endforeach
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="localitate" id="localitate">
        @forelse($localitati as $localitate)
            <option value="{{ $localitate->id }}" {{ old('localitate') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
        @empty
            <option value="">{{ __('Selecteaza localitate') }}</option>
        @endforelse
    </select>
    <!-- Dropdown END-->
    <input type="text" name="strada" id="" class="form-control required mt-2 mb-2" value="{{ old('strada') }}" placeholder="Strada">
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="adrese-transfer" class="checkbox-style" name="adrese_transfer" type="checkbox" value="1" 
            data-home-adress="true" 
            {{ old('adrese_transfer') == '1' || !old('adrese_transfer') ? 'checked' : '' }}>
        <label for="adrese-transfer" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0">Adresa de domiciliu corespunde cu adresa implementarii proiectului</label>
    </span>
    <!-- Checkbox END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2 home-adress" name="judet_implementare" id="judet-implementare"
        data-url="{{ route('localitati.html') }}" 
        onchange="getLocalitatiInOptions(this, '#localitate-implementare')">
        <option value="">Selecteaza judet</option>
        @foreach($judete as $judet)
            <option value="{{ $judet->id }}" {{ old('judet_implementare') == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
        @endforeach
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown-->
    <select class="form-select required mt-2 mb-2 home-adress" name="localitate_implementare" id="localitate-implementare">
        @forelse($localitatiImplementare as $localitate)
            <option value="{{ $localitate->id }}" {{ old('localitate_implementare') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
        @empty
            <option value="">{{ __('Selecteaza localitate') }}</option>
        @endforelse
    </select>
    <!-- Dropdown END-->
    <input type="text" name="strada_implementare" id="strada-implementare" class="form-control required mt-2 mb-2 home-adress" value="{{ old('strada_implementare') }}" placeholder="Strada">
    <input type="email" name="email" id="email" class="form-control required mt-2 mb-2" value="{{ old('email') }}" placeholder="Adresa email">
    <input type="text" name="cnp" id="cnp" class="form-control required mt-2 mb-2" value="{{ old('cnp') }}" placeholder="Cod numeric personal">
    <input type="text" name="serie_ci" id="serie-ci" class="form-control required mt-2 mb-2" value="{{ old('serie_ci') }}" placeholder="Serie act de identitate">
    <input type="text" name="nr_ci" id="nr-ci" class="form-control required mt-2 mb-2" value="{{ old('nr_ci') }}" placeholder="Numar act de identitate">
    <input type="text" name="nr_contract" id="nr-contract" class="form-control required mt-2 mb-2" value="{{ old('nr_contract') }}" placeholder="Numarul contractului de finantare incheiat cu AFM">
    <input type="text" name="telefon" id="telefon" class="form-control required mt-2 mb-2" value="{{ old('telefon') }}" placeholder="Telefon">
    {{-- <input type="text" name="numar_inregistrare" id="numar-inregistrare" class="form-control required mt-2 mb-2" value="{{ old('numar_inregistrare') }}" placeholder="Numarul de inregistrare al dosarului"> --}}
    <input type="text" name="nume_instalator" id="nume-instalator" class="form-control required mt-2 mb-2" value="{{ old('nume_instalator') }}" placeholder="Numele instalatorului actual">
    <input type="text" name="adresa_instalator" id="adresa-instalator" class="form-control required mt-2 mb-2" value="{{ old('adresa_instalator') }}" placeholder="Adresa instalatorului actual">
    <input type="text" name="nr_contract_instalator" id="nr-contract-instalator" class="form-control required mt-2 mb-2" value="{{ old('nr_contract_instalator') }}" placeholder="Numarul contractului dintre instalatorul actual si AFM">
    {{-- <input type="text" name="nr_contract_finantare" id="nr-contract-finantare" class="form-control required mt-2 mb-2" value="{{ old('nr_contract_finantare') }}" placeholder="Numarul contractului de finantare incheiat cu AFM"> --}}
    <!-- Dropdown-->
    <select class="form-select required mt-2 mb-4" name="motiv" id="motiv" required>
        <option value="">Motivul transferului de la instalatorul actual</option>
        <option value="nu sunt de acord cu clauzele contractuale impuse de acesta">Nu sunt de acord cu clauzele contractuale impuse de acesta</option>
        <option value="acesta este in imposibilitatea de a duce la finalizare acest proiect">Acesta este in imposibilitatea de a duce la finalizare acest proiect</option>
        <option value="acesta nu da curs de implementari serviciului de livrare si montaj al echipamentelor">Acesta nu da curs de implementari serviciului de livrare si montaj al echipamentelor</option>
    </select>
    <!-- Dropdown END-->
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="date" class="checkbox-style" name="date" type="checkbox" >
        <label for="date" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0"><span>Sunt de acord cu <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') }}">&nbsp;Prelucrarea datelor cu caracter personal</a></span></label>
    </span>
    <!-- Checkbox END-->
    <button type="submit" name="data-plan-submit" class="btn bg-color text-white btn-lg mt-4 w-100">Trimite</button>
</form>