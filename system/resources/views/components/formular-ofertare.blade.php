@props([
    'tip' => 0,
    'section' => 2023,
])
@pushOnce('scripts')
<script src="{{ asset('js/forms.js') }}" defer></script>
@endPushOnce
<div id="submit-message-ofertare" class="px-0"><div class="sb-msg"></div></div>

<form id="ofertare" action="javascript:void(0)"
    data-form-ajax="1"
    data-form-ajax-header="1"
    data-message-wrapper="#submit-message-ofertare"
    data-action="{{ route('form.ofertare') }}">
    <input type="hidden" name="tip" id="tip" value="{{ $tip }} ">
    <input type="hidden" name="sectiune" id="sectiune" value="{{ $section }}">
    <input type="text" name="nume" id="nume" class="form-control required mt-2 mb-2" value="{{ old('nume') }}" placeholder="Nume">
    <input type="text" name="prenume" id="prenume" class="form-control required mt-2 mb-2" value="{{ old('prenume') }}" placeholder="Prenume">
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="judet" id="judet">
        <option value="">{{ __('Selecteaza judet') }}</option>
        @foreach($judete as $judet)
            <option value="{{ $judet->id }}">{{ $judet->nume }}</option>
        @endforeach
    </select>
    <!-- Dropdown END-->
    <input type="text" name="telefon" id="telefon" class="form-control required mt-2 mb-2" value="{{ old('telefon') }}" placeholder="Telefon">
    <input type="email" name="email" id="email" class="form-control required mt-2 mb-2" value="{{ old('email') }}" placeholder="Adresa de email">
    <textarea name="cerinta" id="cerinta" class="form-control required mt-2 mb-2" placeholder="Cerinta">{{ old('cerinta') }}</textarea>
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="date" class="checkbox-style" name="date" type="checkbox" >
        <label for="date" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0"><span>Sunt de acord cu <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') }}">&nbsp;Prelucrarea datelor cu caracter personal</a></span></label>
    </span>
    <!-- Checkbox END-->
    <button type="submit" name="data-plan-submit" class="btn bg-color text-white btn-lg mt-4 w-100">Trimite</button>
</form>
