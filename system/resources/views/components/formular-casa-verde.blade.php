@pushOnce('scripts')
<script src="{{ asset('js/forms.js') }}" defer></script>
@endPushOnce
<div id="submit-message-casa-verde" class="px-0"><div class="sb-msg"></div></div>

<form id="casa-verde" action="javascript:void(0)" 
    data-form-ajax="1" 
    data-message-wrapper="#submit-message-casa-verde" 
    data-action="https://www.old.genway.ro/pagina/formular_componente_2023/{{ isset($partener) ? '1' : '' }}">

    <input type="text" name="nume" id="nume" class="form-control required mt-2 mb-2" value="" placeholder="Nume">
    <input type="text" name="prenume" id="prenume" class="form-control required mt-2 mb-2" value="" placeholder="Prenume">
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="judet_domiciliu" id="judet-domiciliu"
        data-url="{{ route('localitati.html') }}" 
        onchange="getLocalitatiInOptions(this, '#localitate-domiciliu')">
        <option value="">Selecteaza judet domiciliu</option>
        @foreach($judete as $judet)
            <option value="{{ $judet->id }}">{{ $judet->nume }}</option>
        @endforeach
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="localitate_domiciliu" id="localitate-domiciliu">
        @forelse($localitati as $localitate)
            <option value="{{ $localitate->id }}" {{ old('localitate_domiciliu') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
        @empty
            <option value="">{{ __('Selecteaza localitate domiciliu') }}</option>
        @endforelse
    </select>
    <!-- Dropdown END-->
    <input type="text" name="strada_domiciliu" id="strada-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Strada domiciliu">
    <input type="text" name="numar_domiciliu" id="numar-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Numar domiciliu">
    <input type="text" name="telefon" id="telefon" class="form-control required mt-2 mb-2" value="" placeholder="Telefon">
    <input type="email" name="email" id="email" class="form-control required mt-2 mb-2" value="" placeholder="Email">

    
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="date-casa-verde" class="checkbox-style" name="date" type="checkbox" >
        <label for="date-casa-verde" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0"><span>Sunt de acord cu <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') }}">&nbsp;Prelucrarea datelor cu caracter personal</a></span></label>
    </span>
    <!-- Checkbox END-->

    <button type="submit" name="data-plan-submit" class="btn bg-color text-white btn-lg mt-4 w-100">Trimite</button>
</form>

{{-- <form id="casa-verde" action="javascript:void(0)" 
    data-form-ajax="1" 
    data-message-wrapper="#submit-message-casa-verde" 
    data-action="https://www.old.genway.ro/pagina/formular_componente_2022/{{ isset($partener) ? '1' : '' }}">

    <input type="text" name="nume" id="nume" class="form-control required mt-2 mb-2" value="" placeholder="Nume">
    <input type="text" name="prenume" id="prenume" class="form-control required mt-2 mb-2" value="" placeholder="Prenume">
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="judet_domiciliu" id="judet-domiciliu"
        data-url="{{ route('localitati.html') }}" 
        onchange="getLocalitatiInOptions(this, '#localitate-domiciliu')">
        <option value="">Selecteaza judet domiciliu</option>
        @foreach($judete as $judet)
            <option value="{{ $judet->id }}">{{ $judet->nume }}</option>
        @endforeach
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="localitate_domiciliu" id="localitate-domiciliu">
        @forelse($localitati as $localitate)
            <option value="{{ $localitate->id }}" {{ old('localitate_domiciliu') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
        @empty
            <option value="">{{ __('Selecteaza localitate domiciliu') }}</option>
        @endforelse
    </select>
    <!-- Dropdown END-->
    <input type="text" name="strada_domiciliu" id="strada-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Strada domiciliu">
    <input type="text" name="numar_domiciliu" id="numar-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Numar domiciliu">
    <input type="text" name="bloc_domiciliu" id="bloc-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Bloc domiciliu (optional)">
    <input type="text" name="scara_domiciliu" id="scara-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Scara domiciliu (optional)">
    <input type="text" name="et_domiciliu" id="et-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Etaj domiciliu (optional)">
    <input type="text" name="ap_domiciliu" id="ap-domiciliu" class="form-control required mt-2 mb-2" value="" placeholder="Apartament domiciliu (optional)">
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="isBuildingAdress" class="checkbox-style" name="adrese" type="checkbox" >
        <label for="isBuildingAdress" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0">Adresa imobilului este aceeasi cu adresa domiciliului</label>
    </span>
    <!-- Checkbox END-->
    <input type="text" name="cnp" id="cnp" class="form-control required mt-2 mb-2" value="" placeholder="CNP">
    <input type="text" name="serie_ci" id="serie_ci" class="form-control required mt-2 mb-2" value="" placeholder="Serie CI">
    <input type="text" name="numar_ci" id="numar_ci" class="form-control required mt-2 mb-2" value="" placeholder="Numar CI">
    <input type="text" name="valabilitate_ci" id="valabilitate_ci" class="form-control required mt-2 mb-2" value="" placeholder="Valabilitatea actului de identitate">
    <input type="text" name="telefon" id="telefon" class="form-control required mt-2 mb-2" value="" placeholder="Telefon">
    <input type="email" name="email" id="email" class="form-control required mt-2 mb-2" value="" placeholder="Email">
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2 buildingAdress" name="judet_imobil" id="judet-imobil"
        data-url="{{ route('localitati.html') }}" 
        onchange="getLocalitatiInOptions(this, '#localitate-imobil')">
        <option value="">Selecteaza judet imobil</option>
        @foreach($judete as $judet)
            <option value="{{ $judet->id }}">{{ $judet->nume }}</option>
        @endforeach
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2 buildingAdress" name="localitate_imobil" id="localitate-imobil">
        @forelse($localitatiImobil as $localitate)
            <option value="{{ $localitate->id }}" {{ old('localitate_imobil') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
        @empty
            <option value="">{{ __('Selecteaza localitate domiciliu') }}</option>
        @endforelse
    </select>
    <!-- Dropdown END-->
    <input type="text" name="strada_imobil" id="strada-imobil" class="form-control required mt-2 mb-2 buildingAdress" value="" placeholder="Strada imobil">
    <input type="text" name="numar_imobil" id="numar-imobil" class="form-control required mt-2 mb-2 buildingAdress" value="" placeholder="Numar imobil">
    <input type="text" name="bloc_imobil" id="bloc-imobil" class="form-control required mt-2 mb-2 buildingAdress" value="" placeholder="Bloc imobil (optional)">
    <input type="text" name="sc_imobil" id="sc-imobil" class="form-control required mt-2 mb-2 buildingAdress" value="" placeholder="Scara imobil (optional)">
    <input type="text" name="et_imobil" id="et-imobil" class="form-control required mt-2 mb-2 buildingAdress" value="" placeholder="Etaj imobil (optional)">
    <input type="text" name="ap_imobil" id="ap-imobil" class="form-control required mt-2 mb-2 buildingAdress" value="" placeholder="Apartament imobil (optional)">
    <input type="text" name="nr_carte" id="nr-carte" class="form-control required mt-2 mb-2" value="" placeholder="Numar carte funciara">
    <input type="text" name="nr_cadastral" id="nr-cadastral" class="form-control required mt-2 mb-2" value="" placeholder="Numar cadastral">
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="isCoOwner" class="checkbox-style" name="coproprietari" type="checkbox" >
        <label for="isCoOwner" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0">Exista coproprietari</label>
    </span>
    <!-- Checkbox END-->
    <!--  -->
    <div>
        <h3 class="mb-1 coOwner"><b>Coproprietar</b></h3>
        <input type="text" name="nume_copro[]" class="form-control required mt-2 mb-2 coOwner" value="" placeholder="Nume coproprietar">
        <input type="text" name="prenume_copro[]" class="form-control required mt-2 mb-2 coOwner" value="" placeholder="Prenume coproprietar">
        <input type="text" name="cnp_copro[]" class="form-control required mt-2 mb-2 coOwner" value="" placeholder="CNP coproprietar">
        <input type="text" name="domiciliu_copro[]" class="form-control required mt-2 mb-2 coOwner" value="" placeholder="Domiciliu coproprietar">
        <a href="javascript:void(0)" id="deleteParent-1" class="btn text-white bg-danger text-decoration-none coOwner coOwner-btn mt-2 mb-2"><span class="align-items-center"><i class="icon-user-minus"></i></span>&nbsp; Sterge coproprietar</a>
    </div>
    <a href="javascript:void(0)" class="btn text-white bg-color text-decoration-none coOwner coOwner-btn coOwner-btn-add mb-3 mt-2" ><span class="align-items-center"><i class="icon-user-plus"></i></span>&nbsp; Adauga coproprietar</a>
    <!--  -->
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="isSecondRequestAFM" class="checkbox-style" name="cerere2" value="1" type="checkbox" >
        <label for="isSecondRequestAFM" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0">Este a doua cerere depusa la AFM</label>
    </span>
    <input type="text" name="nr_cerere_precedenta" id="nr-cerere-precedenta" class="form-control required mt-2 mb-2 secondRequestAFM" value="" placeholder="Am mai depus cererea numarul:">
    <textarea name="motive_cerere_noua" id="motive-cerere-noua" class="form-control secondRequestAFM" cols="10" rows="4" placeholder="Doresc sa redepun o noua cerere, din urmatoarele motive"></textarea>
    <!-- Checkbox END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="tipul_bransamentului" id="tipul-bransamentului">
        <option value="">Tipul bransamentului existent</option>
        <option value="monofazat">Monofazat</option>
        <option value="trifazat">Trifazat</option>
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="pozitia_contoarului" id="pozitia-contoarului">
        <option value="">Pozitia contoarului</option>
        <option value="in casa">In casa</option>
        <option value="la limita de proprietate">La limita de proprietate</option>
    </select>
    <!-- Dropdown END-->
    <!-- Dropdown -->
    <select class="form-select required mt-2 mb-2" name="tipul_invelitorii" id="pozitia-contoarului">
        <option value="">Tipul invelitorii</option>
        <option value="tigla">Tigla</option>
        <option value="tabla">Tabla</option>
        <option value="altele">Altele</option>
    </select>
    <!-- Dropdown END-->
    <textarea name="observatii" id="observatii" class="form-control" cols="10" rows="6" placeholder="Observatii (optional)"></textarea>
    
    <h2 class="text-center mt-4 mb-0">COMPONENTELE SISTEMULUI PROPUS PENTRU FINANTARE:</h2><br>
    <div class="row gutterY-finantare mb-4">
        
        <label for="data-plan-1" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-1" autocomplete="off" data-price="30" value="1">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">2.300 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 3kW, monofazat sau trifazat, Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">8 panouri fotovoltaice monocristaline Ja Solar 385W/buc (3,08kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare si manoperă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 16mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-2" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-2" autocomplete="off" data-price="30" value="2">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">13.000 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 5kW, monofozat sau trifazat, Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">13 panouri fotovoltaice monocristaline Ja Solar 385W/buc (5kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 26mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-3" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-3" autocomplete="off" data-price="30" value="3">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">18.150 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 6kW, monofozat sau trifazat, Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">16 panouri fotovoltaice monocristaline Ja Solar 385W/buc (6,16kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 30mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-4" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-4" autocomplete="off" data-price="30" value="4">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">29.350 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 8.2kW, monofozat sau trifazat, Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">22 panouri fotovoltaice monocristaline Ja Solar 385W/buc (8,47kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 40mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label> 
        <label for="data-plan-5" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-5" autocomplete="off" data-price="30" value="5">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">38.100 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 10kW, trifazat, Fronius</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">26 panouri fotovoltaice monocristaline Ja Solar 385W/buc (10kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 50mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-6" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-6" autocomplete="off" data-price="30" value="6">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">62.250 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 15kW, trifazat, Fronius</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">39 panouri fotovoltaice monocristaline Ja Solar 385W/buc (15,01kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 75mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-7" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-7" autocomplete="off" data-price="30" value="7">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">80.250 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor 20kW, trifazat, Fronius</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">52 panouri fotovoltaice monocristaline Ja Solar 385W/buc (20kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 100mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-8" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-8" autocomplete="off" data-price="30" value="8">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">62.850 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor hibrid 8.2kW, monofazat sau trifazat, Fronius</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">22 panouri fotovoltaice monocristaline Ja Solar 385W/buc (8,47kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Acumulator 5kWh BYD</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 40mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        <label for="data-plan-9" class="col-sm-6 col-md-4">
            <div class="pricing-box text-center shadow-none border h-100">
                <input type="radio" name="componente" class="required mt-3" id="data-plan-9" autocomplete="off" data-price="30" value="9">
                <div class="pricing-title bg-transparent">
                    <h3 class="nott ls0">Aport propriu<span class="fs-1">97.350 Lei</span></h3>
                </div>
                <div class="pricing-features border-0 bg-transparent p-4">
                    <ul>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Invertor hibrid 10kW, trifazat, Fronius</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Modul de comunicație pentru vizualizarea datelor de la distanță</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">32 panouri fotovoltaice monocristaline Ja Solar 385W/buc (12,3kWp)</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Acumulator 11kWh BYD</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Suport de prindere pe plan înclinat pentru învelitoare din țiglă ceramică sau tablă</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contor inteligent de energie electrică Fronius sau Huawei</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Cofret protecții AC, DC</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Accesorii cablare și manoperă.</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Verificarea prizei de pământ în momentul instalării</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Necesită 50mp suprafață liberă pe acoperiș, cu orientare S, V sau E</li>
                        <li class="nott ls0 fw-normal pt-2 pb-2">Contribuție AFM: 20.000 Lei</li>
                    </ul>
                </div>
            </div>
        </label>
        
        

    </div>
    
    <!-- Checkbox -->
    <span class="mt-2 mb-2">
        <input id="date-casa-verde" class="checkbox-style" name="date" type="checkbox" >
        <label for="date-casa-verde" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0"><span>Sunt de acord cu <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') }}">&nbsp;Prelucrarea datelor cu caracter personal</a></span></label>
    </span>
    <!-- Checkbox END-->

    <button type="submit" name="data-plan-submit" class="btn bg-color text-white btn-lg mt-4 w-100">Trimite</button>
</form> --}}
