<x-app-layout>
    @push('styles')
        {{-- style-uri suplimentare daca e cazul --}}
        <link rel="stylesheet" href="{{ asset('css/pages/oferta-casa-verde-2023.css') }}" type="text/css" />
    @endpush

    @push('scripts')
        {{-- script-uri suplimentare daca e cazul --}}
    @endpush
    {!! $pagina->html !!}
    <header>
        <div class="solar-panel-1">
        <div class="overlay-banner"></div>
        </div>
        <div class="d-flex flex-column align-items-end company-details">
            <p class="mb-0 margin-right-1 font-weight-1 color-4 font-size-2 line-height-1">CUI: RO 21241885</p>
            <p class="mb-0 margin-right-1 font-weight-1 color-4 font-size-2 line-height-1">ATESTAT ANRE: E1,E2</p>
            <p class="mb-0 margin-right-1 font-weight-1 color-4 font-size-2 line-height-1">LICENTA IGPR: 3677/2015</p>
            <p class="mb-0 margin-right-1 font-weight-1 color-4 font-size-2 line-height-1">CERTIFICATE ISO 9001 - ISO 14001 - ISO 45001</p>
        </div>
    </header>
    <section>
        <div>
            <h1 class="font-size-1"><span class="color-1">Ofertă</span><br><span class="color-2">Casa Verde</span>&nbsp;<span class="color-1">2023</span></h1>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 d-flex justify-content-center">
                    <i class="icon-location icon-size-1 color-1"></i>
                </div>
                <div class="col-xl-10 p-0">
                    <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">Puncte de lucru:</p> 
                    <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">București:</span> Intrarea Tarcau nr. 3, sectorul 3.</p>
                    <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">Rm. Vâlcea:</span> b-dul General Magheru nr. 6 bl. V6, parter.</p>
                    <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">Brăila:</span> b-dul Dorobanti nr. 147 bl. B14, parter.</p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-2 d-flex justify-content-center align-items-center">
                    <i class="icon-whatsapp icon-size-2 color-5"></i>
                </div>
                <div class="col-9">
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">1.</span> București-Ilfov:</p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0786 719 400</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">gabriel.chiroaba@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">2.</span> Sud-Muntenia: </p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0786 173 341</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">radu.paun@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">3.</span> Sud-Vest:</p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0746 888 838</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">magdalena.popa@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">4.</span> Sud- Est:</p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0742 014 954</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">alexandra.danaila@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">5.</span> Nord Est:</p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0742 014 956</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">mihaela.dumitriu@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">6.</span> Vest:</p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0770 355 149</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">andreea.lungu@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">7.</span> Nord Vest:</p>
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0742 014 956</p> 
                        </div>
                        <div class="col-4 p-0">
                        <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">mihaela.dumitriu@genway.ro</p> 
                        </div>
                    </div>
                    <div class="row border-bottom-text">
                        <div class="col-4 p-0">
                            <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2"><span class="color-3">8.</span> Centru:</p>
                        </div>
                        <div class="col-4 p-0">
                            <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">0746 013 584</p> 
                        </div>
                        <div class="col-4 p-0">
                            <p class="font-size-2 mb-0 color-4 line-height-1 font-weight-2">mihai.radu@genway.ro</p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="solar-panel-2 mt-5"></div>
    </section>
    
</x-app-layout>