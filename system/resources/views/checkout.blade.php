<x-app-layout 
	:title="__('Checkout')" 
	>
	<div id="checkout" class="row gutter-10 checkout-wrapper">
		<!-- Session Status -->
	    <x-auth-session-status class="mb-0" :status="session('status')" />
	    <!-- Validation Errors -->
	    <x-auth-validation-errors class="mb-0" :errors="$errors" />
	    <form action="{{ route('checkout.post') }}" method="post">
			<div class="row col-mb-30 gutter-50">
				<div class="row col-lg-6">
					@csrf
					
					<input type="hidden" name="curier" value="{{ $cart['courier']['id'] }}">
					<input type="hidden" name="tip_plata" value="{{ $cart['payment']['id'] }}">
					<input type="hidden" name="message" value="{{ $cart['message'] ?? '' }}">

                    <h4 class="my-3">{{ __('Adresa de facturare') }}</h4>
                    <div class="col-12 form-group">
                        <label for="tip">{{ __('Tip cont') }}:</label>
                        <select class="form-select" id="tip" name="tip" 
                            onchange="$(this).val() == '1' ? $('#company-fields').hide() : $('#company-fields').show();">
                            <option value="1" {{ old('tip', $user->tip ?? '') == '1' ? 'selected' : '' }}>{{ __('Persoana fizica') }}</option>
                            <option value="2" {{ old('tip', $user->tip ?? '') == '2' ? 'selected' : '' }}>{{ __('Firma') }} - {{ __('Persoana juridica') }}</option>
                        </select>
                    </div>

                    <div id="company-fields" class="row col-12 m-0 p-0" style="display: {{ old('tip', $user->tip ?? '') == '2' ? 'flex' : 'none' }};">
                        <div class="col-12 col-sm-6 form-group">
                            <label for="nume_firma">{{ __('Firma') }}*:</label>
                            <input type="text" id="nume_firma" name="nume_firma" value="{{ old('nume_firma', $user->nume_firma ?? '') }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="cui">{{ __('CUI') }}*:</label>
                            <input type="text" id="cui" name="cui" value="{{ old('cui', $user->cui ?? '') }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="nr_reg_comert">{{ __('Nr. Reg. Com.') }}*:</label>
                            <input type="text" id="nr_reg_comert" name="nr_reg_comert" value="{{ old('nr_reg_comert', $user->nr_reg_comert ?? '') }}" class="form-control">
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="autorizatie_igpr">{{ __('Autorizatie IGPR (optional)') }}:</label>
                            <input type="text" id="autorizatie_igpr" name="autorizatie_igpr" value="{{ old('autorizatie_igpr', $user->autorizatie_igpr ?? '') }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="nume">{{ __('Nume') }}*:</label>
                        <input type="text" id="nume" name="nume" value="{{ old('nume', $user->nume ?? '') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="prenume">{{ __('Prenume') }}*:</label>
                        <input type="text" id="prenume" name="prenume" value="{{ old('prenume', $user->prenume ?? '') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="cnp">{{ __('CNP') }}*:</label>
                        <input type="text" id="cnp" name="cnp" value="{{ old('cnp', $user->cnp ?? '') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="telefon">{{ __('Telefon') }}*:</label>
                        <input type="text" id="telefon" name="telefon" value="{{ old('telefon', $user->telefon ?? '') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="email">{{ __('Adresa de email') }}*:</label>
                        <input type="email" id="email" name="user_email" value="{{ old('user_email', $user->user_email ?? '') }}" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="judet">{{ __('Judet') }}*:</label>
                        <select class="form-select" id="judet" name="id_judet"
                                data-url="{{ route('localitati.html') }}" 
                                onchange="getLocalitatiInOptions(this, '#localitate')">
                            <option value="">{{ __('Selecteaza judet') }}</option>
                            @foreach($judete as $judet)
                                <option value="{{ $judet->id }}" {{ $id_judet == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="localitate">{{ __('localitate') }}*:</label>
                        <select class="form-select" id="localitate" name="id_localitate"
                        	data-url="{{ route('calculate.shipping') }}"
                        	data-input="#livrare_adresa_1"
                        	data-value="false"
                        	onchange="getNewShippingPrice(this)">
                            @forelse($localitati as $localitate)
                                <option value="{{ $localitate->id }}" {{ old('id_localitate', $user->id_localitate ?? '') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
                            @empty
                                <option value="">{{ __('Selecteaza localitate') }}</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 form-group">
                        <label for="adresa">{{ __('Adresa') }}*:</label>
                        <input type="text" id="adresa" name="adresa" value="{{ old('adresa', $user->adresa ?? '') }}" class="form-control">
                    </div>

                    @guest
                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="terms">
                            <input type="checkbox" name="terms" class="form-check-input" id="terms" value="1"
                                {{ old('terms') ? 'checked' : '' }}>
                            <span>{!! __('Sunt de acord cu <a href=":href">Termenii si conditiile</a> siteului', [
                                'href' => route('page', 'termeni-si-conditii'),
                            ]) !!}</span>
                        </label>
                         <label class="form-check-label fw-semibold" for="new_account">
                            <input type="checkbox" name="new_account" class="form-check-input" id="new_account" 
                                value="1" 
                                {{ old('new_account') ? 'checked' : '' }}
                                onchange="$(this).prop('checked') ? $('#new-account').show() : $('#new-account').hide();">
                            <span>{{ __('Vrei sa iti creezi un cont folosind datele introduse?') }}</span>
                        </label>
                    </div>

                    <div id="new-account" class="row col-12 m-0 p-0" 
                    	style="display: {{ old('new_account') ? 'flex' : 'none' }};"
                    	>
                    	<div class="col-12 col-sm-6 form-group">
	                        <label for="password">{{ __('Parola') }}*:</label>
	                        <input type="password" id="password" name="password" class="form-control">
	                    </div>

	                    <div class="col-12 col-sm-6 form-group">
	                        <label for="password_confirmation">{{ __('Confirma Parola') }}*:</label>
	                        <input type="password" id="password_confirmation" name="password_confirmation" value="" class="form-control">
	                    </div>
					</div>
					@endguest

                    <h4 class="my-3">{{ __('Adresa de livrare') }}</h4>

                    <div class="col-12 form-group">
                        <label class="form-check-label fw-semibold" for="livrare_adresa_1">
                            <input type="checkbox" name="livrare_adresa_1" class="form-check-input" id="livrare_adresa_1" 
                                value="1" 
                                {{ old('livrare_adresa_1', $user->livrare_adresa_1 ?? '') ? 'checked' : '' }}
                                onchange="if($(this).prop('checked')) { $('#shipping-address').show(); $('#livrare_localitate').trigger('change'); } else { $('#shipping-address').hide(); $('#localitate').trigger('change');}">
                            <span>{{ __('Adresa de livrarea difera de adresa de contact') }}</span>
                        </label>
                    </div>

                    <div id="shipping-address" class="row col-12 m-0 p-0" style="display: {{ old('livrare_adresa_1', $user->livrare_adresa_1 ?? '') ? 'flex' : 'none' }};">

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_judet">{{ __('Judet') }}*:</label>
                            <select class="form-select" id="livrare_judet" name="livrare_id_judet"
                                data-url="{{ route('localitati.html') }}" 
                                onchange="getLocalitatiInOptions(this, '#livrare_localitate')">
                                <option value="">{{ __('Selecteaza judet') }}</option>
                                @foreach($judete as $judet)
                                    <option value="{{ $judet->id }}" {{ $livrare_id_judet == $judet->id ? 'selected' : '' }}>{{ $judet->nume }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_localitate">{{ __('localitate') }}*:</label>
                            <select class="form-select" id="livrare_localitate" name="livrare_id_localitate"
                            	data-url="{{ route('calculate.shipping') }}"
                        		data-input="#livrare_adresa_1"
                        		data-value="true"
                            	onchange="getNewShippingPrice(this)">
                                @forelse($localitatiLivrare as $localitate)
                                    <option value="{{ $localitate->id }}" {{ old('livrare_id_localitate', $user->livrare_id_localitate ?? '') == $localitate->id ? 'selected' : '' }}>{{ $localitate->nume }}</option>
                                @empty
                                    <option value="">{{ __('Selecteaza localitate') }}</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 form-group">
                            <label for="livrare_adresa">{{ __('Adresa') }}*:</label>
                            <input type="text" id="livrare_adresa" name="livrare_adresa" value="{{ old('livrare_adresa', $user->livrare_adresa ?? '') }}" class="form-control">
                        </div>
                    </div>
				</div>
				<div class="row col-lg-6">
					<div class="table-responsive">
						<table class="table cart mb-5 mt-3">
							<thead>
								<tr>
									<th class="cart-product-thumbnail">&nbsp;</th>
									<th class="cart-product-name">{{ __('Produs') }}</th>
									<th class="cart-product-quantity">{{ __('Cantitate') }}</th>
									<th class="cart-product-subtotal">{{ __('Total') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($cart['items'] ?? [] as $item)
									<tr class="cart_item">
										<td class="cart-product-thumbnail">
											<a href="{{ $item['route'] }}"><img class="w-auto h-auto mx-auto" src="{{ $item['image'] }}" alt="{{ $item['name'] }}"></a>
											<input type="hidden" name="produse[{{ $item['id'] }}]" value="{{ $item['qty'] }}">
										</td>
										<td class="cart-product-name">
											<a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
										</td>
										<td class="cart-product-quantity">
											x {{ $item['qty'] }}
										</td>
										<td class="cart-product-subtotal">
											<span class="amount">{{ $item['total'] }}</span>
										</td>
									</tr>
								@empty
									<tr class="cart_item">
										<td class="cart-product-subtotal" colspan="6">
											<span class="amount">{{ __('Nu ai niciun produs in cos') }}</span>
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@isset($cart['message'])
						<div class="mb-4">
							<h4 class="mb-2">{{ __('Detalii comanda') }}</h4>
							<span class="ps-3">{{ $cart['message'] ?? '' }}</span>
						</div>
					@endisset
					<div>
						<h4 class="mb-2">{{ __('Cos Total') }}</h4>
						<div class="table-responsive">
							<table class="table cart cart-totals mb-5">
								<tbody>
									<tr class="cart_item">
										<td class="cart-product-name">
											<strong>{{ __('Subtotal') }}</strong>
										</td>
										<td class="cart-product-name">
											<span class="amount">{{ $cart['total'] ?? '0' }} lei</span>
										</td>
									</tr>
									<tr class="cart_item">
										<td class="cart-product-name">
											<strong>{{ __('Total TVA') }}</strong>
										</td>
										<td class="cart-product-name">
											<span class="amount">{{ $cart['total_tva'] ?? '0' }} lei</span>
										</td>
									</tr>
									@isset($cart['voucher'])
										<tr class="cart_item">
											<td class="cart-product-name">
												<strong>{{ __('Voucher') }}: {{ $cart['voucher']['code'] ?? '' }}</strong>
											</td>
											<td class="cart-product-name">
												<span class="amount">-{{ $cart['voucher']['value'] ?? '0' }} lei</span>
											</td>
										</tr>
									@endisset
									@if(
										isset($cart['discount_plata_op']) 
										&& isset($cart['payment']['id']) 
										&& $cart['payment']['id'] == '3'
									)
										<tr class="cart_item">
											<td class="cart-product-name">
												<strong>{{ __('Discount plata in avans :percent%', ['percent' => $cart['discount_plata_op']['percent']]) }}</strong>
											</td>
											<td class="cart-product-name">
												<span class="amount">-{{ $cart['discount_plata_op']['value'] ?? '0' }} lei</span>
											</td>
										</tr>
									@endif
									@isset($cart['discount_fidelitate'])
										<tr class="cart_item">
											<td class="cart-product-name">
												<strong>{{ __('Discount fidelitate :percent%', ['percent' => $cart['discount_fidelitate']['percent']]) }}</strong>
											</td>
											<td class="cart-product-name">
												<span class="amount">-{{ $cart['discount_fidelitate']['value'] ?? '0' }} lei</span>
											</td>
										</tr>
									@endisset
									@isset($cart['payment']['return_price'])
										<tr class="cart_item">
											<td class="cart-product-name">
												<strong>{{ __('Taxa retur bani') }}</strong>
											</td>
											<td class="cart-product-name">
												<span class="amount">{{ $cart['payment']['return_price'] ?? '0' }} lei</span>
											</td>
										</tr>
									@endisset
									<tr class="cart_item">
										<td class="cart-product-name">
											<strong>{{ __('Transport') }}</strong>
										</td>
										<td class="cart-product-name">
											<span class="amount shipping">{{ $cart['courier']['price'] ?? '0' }}</span>
											<span>lei</span>
										</td>
									</tr>
									<tr class="cart_item">
										<td class="cart-product-name">
											<strong>{{ __('Total') }}</strong>
										</td>
										<td class="cart-product-name">
											<span class="amount total color lead">
												{{ $cart['total_cart'] ?? '0' }}
											</span> 
											<span class="color lead">lei</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="right">
					<a href="{{ route('cart') }}" class="button button-3d button-black mt-2 mt-sm-0 me-0">{{ __('Inapoi') }}</a>
					<button type="submit" class="button button-3d mt-2 mt-sm-0 me-0">{{ __('Finalizeaza Comanda') }}</button>
				</div>
			</div>
		</form>
	</div>
</x-app-layout>