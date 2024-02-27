@pushOnce('scripts')
<script src="{{ asset('js/forms.js') }}" defer></script>
@endPushOnce
<div id="submit-message-electric-up" class="px-0"><div class="sb-msg"></div></div>

<form id="electric-up" action="javascript:void(0)" 
    data-form-ajax="1" 
    data-message-wrapper="#submit-message-electric-up" 
    data-action="https://www.old.genway.ro/pagina/formular_electricup">
    <input type="text" name="nume_societate" id="nume-societate" class="form-control required mt-2 mb-2" value="" placeholder="Nume Societate">
    <input type="text" name="cui" id="cui" class="form-control required mt-2 mb-2" value="" placeholder="Cod de identitate fiscala">
    {{-- <input type="text" name="nr_reg_com" id="nr-reg-com" class="form-control required mt-2 mb-2" value="" placeholder="Numar Registrul Comerului"> --}}
    {{-- <input type="text" name="caen" id="caen" class="form-control required mt-2 mb-2" value="" placeholder="Cod CAEN principal"> --}}
    <input type="text" name="adresa" id="adresa" class="form-control required mt-2 mb-2" value="" placeholder="Adresa punctului de lucru in care se va implementa proiectul">
    {{-- <input type="text" name="consum" id="consum" class="form-control required mt-2 mb-2" value="" placeholder="Consumul mediu lunar din ultimele 19 luni (MWh)"> --}}
    <input type="text" name="persoana_contact" id="persoana_contact" class="form-control required mt-2 mb-2" value="" placeholder="Persoana de contact">
    <input type="text" name="telefon" id="telefon" class="form-control required mt-2 mb-2" value="" placeholder="Telefon">
    <input type="email" name="email" id="email" class="form-control required mt-2 mb-4" value="" placeholder="Email">
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="date" class="checkbox-style" name="date" type="checkbox" >
        <label for="date" class="checkbox-style-3-label custom-checkbox-style-3-label"><span>Sunt de acord cu <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') }}">&nbsp;Prelucrarea datelor cu caracter personal</a></span></label>
    </span>
    <!-- Checkbox END-->
    <button type="submit" name="data-plan-submit" class="btn bg-color text-white btn-lg mt-4 w-100">Trimite</button>
</form>