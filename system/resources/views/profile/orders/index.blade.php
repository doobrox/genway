<x-app-layout>
    @push('styles')
    @endpush
    @push('scripts')
    @endpush
    <div class="row gutter-10">
        <!-- Post Content
        ============================================= -->
        <div class="postcontent col-lg-10 order-lg-last mb-2 ps-lg-3">
            <h2 class="mt-2 mb-2">Istoric Comenzi</h2>
            <div class="line mt-0 mb-5"></div>
            @foreach($comenzi as $comanda)
                <div class="order-history">
                    <div class="mt-4 d-flex justify-content-between flex-wrap"><span><b>Numar comanda </b><span class="color me-2">#{{ $comanda->nr_factura }}</span></span><span><b>Stare:</b> {{ $comanda->text_stare }}</span></div>
                    <div class="line m-2"></div>
                    <div class=""></div>
                    <div class="grid--1x3-order">
                        <div>
                            <p class="mt-4 mb-4"><b>Data:</b>&nbsp; {{ $comanda->transformDate('data_adaugare', 'd.m.Y') }}</p>
                            <p class="mb-3"><b>Valoare:</b>&nbsp; {{ $comanda->valoare }} lei</p>
                        </div>
                        <div>
                            <p class="mt-4 mb-4"><b>Nr. produse:</b>&nbsp; {{ $comanda->produse()->count() }}</p>
                            <p><b>Stare plata:</b>&nbsp; {{ $comanda->text_stare_plata }}</p>
                        </div>
                        <div class="d-flex flex-column custom-btn-orders">
                            <a href="{{ route('profile.orders.show', $comanda->id) }}" class="btn bg-color text-white mt-3 mb-2"><i class="icon-info-circle"></i>&nbsp; Informatii comanda</a>
                            <a href="{{ route('profile.orders.invoice', $comanda->id) }}" target="_blank" class="btn bg-danger text-white mt-0 mb-4"><i class="icon-file-pdf"></i>&nbsp; Factura proforma</a>
                        </div>
                    </div>
                    <div class="line mt-0"></div>
                </div>
            @endforeach
            {{ $comenzi->links() }}
        </div><!-- .postcontent end -->
        <x-sidebar-layout />
    </div>
</x-app-layout>