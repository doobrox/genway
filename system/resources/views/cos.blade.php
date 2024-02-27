<x-app-layout :title="__('Cos de cumparaturi')" >
	<div id="cart" class="row gutter-10">
		<!-- Session Status -->
	    <x-auth-session-status class="mb-0" :status="session('status')" />
	    <!-- Validation Errors -->
	    <x-auth-validation-errors class="mb-0" :errors="$errors" />
	    <form action="{{ route('cart.checkout') }}" method="post">
	    	@csrf
			<table class="table cart mb-5 mt-3">
				<thead>
					<tr>
						<th class="cart-product-remove">&nbsp;</th>
						<th class="cart-product-thumbnail">&nbsp;</th>
						<th class="cart-product-name">{{ __('Produs') }}</th>
						<th class="cart-product-price">{{ __('Pret/Unitate') }}</th>
						<th class="cart-product-quantity">{{ __('Cantitate') }}</th>
						<th class="cart-product-subtotal">{{ __('Total') }}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($cart['items'] ?? [] as $item)
						<tr class="cart_item">
							<td class="cart-product-remove feature-box fbox-effect d-table-cell">
								<div class="fbox-icon small">
									<a href="javascript:void(0)" data-cart-route="{{ route('cart.remove', $item['id']) }}" data-reload="true" data-open="false" class="remove" title="{{ __('Sterge produs') }}">
										<i class="icon-line-cross"></i>
									</a>
								</div>
							</td>
							<td class="cart-product-thumbnail">
								<a href="{{ $item['route'] }}"><img class="w-auto h-auto mx-auto" src="{{ $item['image'] }}" alt="{{ $item['name'] }}"></a>
							</td>
							<td class="cart-product-name">
								<a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
							</td>
							<td class="cart-product-price">
								<span class="amount">{{ $item['price'] }}</span>
							</td>
							<td class="cart-product-quantity">
								<div class="quantity">
									<button type="button" class="minus" 
										data-cart-route="{{ route('cart.subtract', $item['id']) }}" 
										data-open="false" 
										data-reload="true" 
										onclick="updateCart(this);">
										<i class="icon-line-minus"></i>
									</button>
									<input type="text" min="1" name="produse[{{ $item['id'] }}]" value="{{ $item['qty'] }}" class="qty" />
									<button type="button" class="plus" 
										data-cart-route="{{ route('cart.add', $item['id']) }}" 
										data-open="false" 
										data-reload="true" 
										onclick="updateCart(this);">
										<i class="icon-line-plus"></i>
									</button>
								</div>
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
					@isset($cart['items'])
						<tr class="cart_item">
							<td colspan="6">
								<div class="row justify-content-between py-2 col-mb-30">
									<div class="col-lg-auto ps-lg-0">
										<div class="row">
											<div class="col-md-8">
												<input type="text" id="voucher" name="voucher" value="{{ $cart['voucher']['code'] ?? '' }}" class="sm-form-control text-center text-md-start" placeholder="Adauga voucher-ul.." />
											</div>
											<div class="col-md-4 mt-3 mt-md-0">
												@isset($cart['voucher'])
													<a href="javascript:void(0)" class="button button-3d button-red m-0" 
														onclick="updateCart(this);"
														data-cart-route="{{ route('cart.voucher.remove') }}"
														data-reload="true">
														{{ __('Sterge Voucher') }}
													</a>
												@else
													<a href="javascript:void(0)" class="button button-3d button-black m-0" 
														onclick="updateCart(this);"
														data-cart-route="{{ route('cart.voucher') }}"
														data-value="#voucher"
														data-reload="true">
														{{ __('Aplica Voucher') }}
													</a>
												@endisset
											</div>
										</div>
									</div>
									<div class="col-lg-auto pe-lg-0">
										{{-- <button type="submit" class="button button-3d mt-2 mt-sm-0 me-0">{{ __('Finalizeaza Comanda') }}</a> --}}
										<a href="{{ route('checkout') }}" class="button button-3d mt-2 mt-sm-0 me-0">{{ __('Checkout') }}</a>
									</div>
								</div>
							</td>
						</tr>
					@endisset
				</tbody>
			</table>
			<div class="row">
				<div class="col-lg-6">
					@isset($cart['items'])
						<h4 class="mb-2">{{ __('Modalitate de expediere') }}</h4>
						<div class="row mb-3">
							<div class="col-12 form-group">
								<select id="curier" name="curier" class="sm-form-control" 
									data-cart-route="{{ route('cart.courier') }}"
									data-value="#curier"
									data-reload="true" 
									onchange="updateCart(this)"
									>
									@foreach($curieri as $curier)
										<option value="{{ $curier->id }}" @selected($cart['courier']['id'] == $curier->id)>{{ $curier->nume }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<h4 class="mb-2">{{ __('Metoda de plata') }}</h4>
						<div class="row">
							<div class="col-12 form-group">
								<select id="plata" name="tip_plata" class="sm-form-control"
									data-cart-route="{{ route('cart.payment') }}"
									data-value="#plata"
									data-reload="true" 
									onchange="updateCart(this)"
									>
									@foreach($plati as $key => $name)
										<option value="{{ $key }}" @selected($cart['payment']['id'] == $key)>{{ $name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<h4 class="mb-2">{{ __('Detalii comanda') }}</h4>
						<div class="row">
							<div class="col-12 form-group">
								<textarea id="message" name="detalii_comanda" class="sm-form-control" rows="3" 
									data-cart-route="{{ route('cart.message') }}"
									data-open="false" 
									data-value="#message"
									>{{ $cart['message'] ?? '' }}</textarea>
							</div>
						</div>
					@endisset
				</div>
				<div class="col-lg-6">
					<h4>{{ __('Cos Total') }}</h4>
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
										<span class="amount">{{ $cart['courier']['price'] ?? '0' }} lei</span>
									</td>
								</tr>
								<tr class="cart_item">
									<td class="cart-product-name">
										<strong>{{ __('Total') }}</strong>
									</td>
									<td class="cart-product-name">
										<span class="amount color lead"><strong>{{ $cart['total_cart'] ?? '0' }} lei</strong></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
</x-app-layout>