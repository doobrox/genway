<x-app-layout 
	:title="__('Cos de cumparaturi')" 
	>
	@isset($urmarire_conversie)
		@push('scripts')
	        {!! $urmarire_conversie !!}
		@endpush
	@endisset
	<div id="cart" class="row gutter-10 checkout-wrapper">
		<!-- Session Status -->
	    <x-auth-session-status class="mb-0" :status="session('status')" />
	    <!-- Validation Errors -->
	    <x-auth-validation-errors class="mb-0" :errors="$errors" />

	    <div class="heading-block text-center border-bottom-0 my-5">
			<h1>{{ __('Comanda a fost salvata cu succes') }}</h1>
			<span>{!! $status !!}</span>
			@isset($comanda)
				<h3 class="mt-3 mb-2">Detalii comanda</h3>
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
	                                    <a href="{{ $produs->route }}" title="{{ $produs->nume }}">{{ $produs->nume }}</a>
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

	            <div class="line my-4"></div>

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
			@endisset
			@isset($form)
			    {!! $form !!}
			@endisset
		</div>
	</div>
</x-app-layout>