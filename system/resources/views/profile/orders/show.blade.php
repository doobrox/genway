<x-app-layout :title="__('Comanda #:nr din :date', [
        'nr' => $comanda->nr_factura,
        'date' => $comanda->transformDate('data_adaugare', 'd.m.Y'),
    ])">
    @push('styles')
    @endpush
    @push('scripts')
    @endpush
    <div class="row gutter-10">
        <!-- Post Content
        ============================================= -->
        <div class="postcontent col-lg-10 order-lg-last ps-lg-3">
            <h2 class="mt-2 mb-2">Comanda <span>#{{ $comanda->nr_factura }}</span></h2>
            <div class="line mt-0 mb-5"></div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-3" :status="session('status')" />
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr class="border-color">
                            <th colspan="4" class="text-center bg-color text-white">INFORMATII COMANDA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-3 border-end-0"><b>Numar comanda:</b></td>
                            <td class="col-3 border-start-0">#{{ $comanda->nr_factura }}</td>
                            <td class="col-3 border-end-0"><b>Metoda de plata:</b></td>
                            <td class="col-3 border-start-0">{{ $comanda->text_tip_plata }}</td>
                        </tr>
                        <tr>
                            <td class="col-3 border-end-0"><b>Data adaugarii</b></td>
                            <td class="col-3 border-start-0">{{ $comanda->transformDate('data_adaugare', 'd.m.Y') }}</td>
                            <td class="col-3 border-end-0"><b>Modalitate expediere</b></td>
                            <td class="col-3 border-start-0">{{ $info['comanda_curier'] }}</td>
                        </tr>
                        <tr>
                            <td class="col-3 border-end-0"><b>Stare plata:</b></th>
                            <td class="col-3 border-start-0">{{ $comanda->text_stare_plata }}</td>
                            <td class="col-3 border-end-0"><b>Status comanda:</b></th>
                            <td class="col-3 border-start-0">{{ $comanda->text_stare }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-5">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr class="border-color">
                            <th colspan="2" class="text-center bg-color text-white col-6">BENEFICIAR</th>
                            <th colspan="2" class="text-center bg-color text-white col-6">FURNIZOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-end-0"><b>Nume</b></td>
                            <td class="border-start-0">
                                @if( $info['client_tip'] == '2')
                                    {{ $info['client_nume_firma'] }}<br>
                                    CUI: {{ $info['client_cui'] }}<br>
                                    Nr. reg. comert.: {{ $info['client_nr_reg_comert'] }}<br>
                                    Reprezentant: 
                                @endif
                                {{ $info['client_nume'] }} {{ $info['client_prenume'] }}
                            </td>
                            <td>
                                {{ $info['furnizor_nume_firma'] }}<br>
                                CUI: {{ $info['furnizor_cui'] }}<br>
                                Nr. reg. comert.: {{ $info['furnizor_nr_reg_comert'] }}<br>
                                Cont: {{ $info['furnizor_cod_fiscal'] ? $info['furnizor_cod_fiscal'].' - ' : '' }}{{ $info['furnizor_banca'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-end-0"><b>Email</b></td>
                            <td class="border-start-0">{{ $info['client_user_email'] }}</td>
                            <td>{{ $setari['EMAIL_SUPORT_ONLINE'] }}</td>
                        </tr>
                        <tr>
                            <td class="border-end-0"><b>Telefon</b></td>
                            <td class="border-start-0">{{ $info['client_telefon'] }}</td>
                            <td>{{ $setari['TELEFON_CONTACT'] }}</td>
                        </tr>
                        <tr>
                            <td class="border-end-0"><b>Adresa</b></td>
                            <td class="border-start-0">
                                {{ isset($info['client_livrare_adresa_1']) && $info['client_livrare_adresa_1'] == '1' 
                                    ? $info['client_livrare_adresa'] 
                                    : $info['client_adresa'] }}
                            </td>
                            <td>{{ $info['furnizor_adresa'] }}</td>
                        </tr>
                        <tr>
                            <td class="border-end-0"><b>Localitate</b></td>
                            <td class="border-start-0">{{ $info['client_livrare_localitate'] ?? $info['client_localitate'] }}</td>
                            <td>{{ $info['furnizor_localitate'] }}</td>
                        </tr>
                        <tr>
                            <td class="border-end-0"><b>Judet</b></td>
                            <td class="border-start-0">{{ $info['client_livrare_judet'] ?? $info['client_judet'] }}</td>
                            <td>{{ $info['furnizor_judet'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h3 class="mt-4 mb-2">Factura proforma</h3>
            <div class="line mt-0 mb-4"></div>
            <div class="table-responsive mt-2">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr class="border-color">
                            <th class="text-center bg-color text-white">PRODUS</th>
                            <th class="text-center bg-color text-white">COD</th>
                            <th class="text-center bg-color text-white">CANTITATE</th>
                            <th class="text-center bg-color text-white">PRET</th>
                            <th class="text-center bg-color text-white">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produse as $produs)
                            <tr class="text-center fw-bold">
                                <td class="text-start">
                                    <a href="{{ $produs ? $produs->route : 'javascript:void(0)' }}" title="{{ $produs->nume }}">{{ $produs->nume }}</a>
                                    {{-- @if(!empty( $produs->filtre ))
                                        <br />
                                        @foreach($produs->filtre as $filtru)
                                            <span style="padding-left: 20px">    
                                                <strong>{{ $filtru['nume_parinte'] }}</strong>: {{ $filtru['nume_filtru'] }}
                                            </span><br />
                                        @endforeach
                                    @endif --}}
                                </td>
                                <td>{{ $produs->cod_ean13 }}</td>
                                <td>{{ $produs->cantitate }}</td>
                                <td>{{ $produs->pret }} lei</td>
                                <td class="fw-normal">{{ round($produs->pret * $produs->cantitate, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="text-center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Subtotal</b></td>
                            <td>{{ round($comanda->valoare_produse, 2) }} lei</td>
                        </tr>
                        @if($comanda->tva > 0)
                            <tr class="text-center">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>TVA {{ $comanda->tva }}%</b></td>
                                <td>+ {{ $comanda->valoare_tva }} lei</td>
                            </tr>
                        @endif
                        @if($comanda->valoare_discount_fidelitate != 0)
                            <tr class="text-center">
                                <td class="text-start"><b>Discount fidelitate {{ $comanda->discount_fidelitate }}%</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $comanda->valoare_discount_fidelitate }} lei</td>
                            </tr>
                        @endif
                        @if($comanda->valoare_discount_plata_op != 0)
                            <tr class="text-center">
                                <td class="text-start"><b>Discount plata in avans {{ $comanda->discount_plata_op }}%</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $comanda->valoare_discount_plata_op }} lei</td>
                            </tr>
                        @endif
                        @if($comanda->cod_voucher != "" && $comanda->cod_voucher != null)
                            <tr class="text-center">
                                <td class="text-start"><b>Cod cupon / discount {{ $comanda->cod_voucher }}</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $comanda->valoare_voucher }} lei</td>
                            </tr>
                        @endif
                        <tr class="text-center">
                            <td class="text-start"><b>Modalitate expediere: {{ $info['comanda_curier'] }}</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $comanda->taxa_livrare > 0 ? "+ ".$comanda->taxa_livrare : 0 }} lei</td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"><b>Total</b></td>
                            <td class="text-center">{{ $comanda->valoare }} lei</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('profile.orders.invoice', ['comanda' => $comanda->id, 'stream' => true]) }}" class="btn bg-danger text-white mt-3 mb-3 justify-content-end"><i class="icon-file-pdf"></i>&nbsp; Descarca factura</a>
            </div>
            <h3 class="mt-0 mb-2">Detalii factura</h3>
            <div class="line mt-0 mb-4"></div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr class="border-color">
                            <th colspan="" class="text-center bg-color text-white col-4">DATA ADAUGARII</th>
                            <th colspan="" class="text-center bg-color text-white col-4">STATUS</th>
                            <th colspan="" class="text-center bg-color text-white col-4">OBSERVATII</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><b>{{ $comanda->transformDate('data_adaugare', 'd.m.Y') }}</b></td>
                            <td class="text-center"><b>{{ $comanda->text_stare }}</b></td>
                            <td class="text-center"><b>{{ $comanda->mesaj }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('profile.orders') }}" class="btn bg-color text-white mt-3 mb-2"><i class="icon-arrow-alt-circle-left"></i>&nbsp; Inapoi la istoric</a>
            </div>
        </div><!-- .postcontent end -->
        <x-sidebar-layout />
    </div>
</x-app-layout>