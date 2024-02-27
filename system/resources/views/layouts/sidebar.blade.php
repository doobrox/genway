<!-- Sidebar
============================================= -->
<div class="sidebar col-lg-2 custom-sidebar.col-lg-2 text-center pb-0">
	<div class="custom-sidebar-widgets-wrap">
		<!-- Sectiuni -->
		<div class="widget custom-widget widget_links custom-widget_links border-top-0">
			@foreach($sectiuni as $sectiune)
				<div class="toggle custom-toggle m-2 ms-0" data-state="{{ $filtre ? 'closed' : 'open' }}">
					<div class="line-sidebar"></div>
					<div class="toggle-header custom-toggle-header justify-content-between">
						<div class="toggle-title custom-toggle-title ps-0">
							<h4 class="sidebar-category-title">{{ $sectiune->nume }}</h4>
						</div>
						<div class="toggle-icon custom-toggle-icon">
							<i class="toggle-closed icon-line-plus"></i>
							<i class="toggle-open icon-line-minus"></i>
						</div>
					</div>
					<div class="toggle-content custom-toggle-content">
						<div class="line-sidebar"></div>
						@if(count($pagini = $sectiune->pagini) > 0)
							<ul>
								@foreach($pagini as $pagina)
									<li class="sidebar-link m-0">
										<a href="{{ $pagina->link_extern ? $pagina->link_extern : route('page', $pagina->slug) }}" 
											target="{{ $pagina->link_extern ? '_blank' : '_self' }}"
											class="text-start">{{ $pagina->titlu }}
										</a>
									</li>
								@endforeach
							</ul>
						@endif
					</div>
				</div>
			@endforeach
			@if(count($categorii) > 0)
				<div class="toggle custom-toggle m-2 ms-0" data-state="{{ $filtre ? 'closed' : 'open' }}">
					<div class="line-sidebar"></div>
					<div class="toggle-header custom-toggle-header justify-content-between">
						<div class="toggle-title custom-toggle-title ps-0">
							<h4 class="sidebar-category-title">{{ __('Produse') }}</h4>
						</div>
						<div class="toggle-icon custom-toggle-icon">
							<i class="toggle-closed icon-line-plus"></i>
							<i class="toggle-open icon-line-minus"></i>
						</div>
					</div>
					<div class="toggle-content custom-toggle-content">
						<div class="line-sidebar"></div>
						<ul>
							@foreach($categorii as $categorie)
								<li class="sidebar-link m-0">
									<a href="{{ route('category', $categorie->slug) }}" 
										class="text-start">{{ $categorie->nume }}
									</a>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif
		</div>
		@if($filtre)
			<!-- Sorteaza dupa producator --> 
			<div class="line-sidebar"></div>
				<h4 class="sidebar-category-title">{{ __('Producator') }}</h4>
			<div class="line-sidebar"></div>
			<div class="flex-start" style="max-height: 250px;">
				<div class="w-100 overflow-auto scrollbar">
					@foreach($producatori as $index => $producator)
						<input id="provider-{{ $index }}" class="checkbox-style" name="search[provider][]" type="checkbox" 
							value="{{ $producator->id }}" {{ isset($search['provider']) && in_array($producator->id, $search['provider']) ? 'checked' : '' }}>
						<label for="provider-{{ $index }}" class="checkbox-style-3-label custom-checkbox-style-3-label">
							{{ $producator->nume }}
						</label>
					@endforeach
				</div>
			</div>
			<!-- Sorteaza dupa casute interval pret -->
			<div class="line-sidebar"></div>
				<h4 class="sidebar-category-title">{{ __('Pret') }}</h4>
			<div class="line-sidebar"></div>
			<div class="flex-start">
				@foreach($prices as $value => $text)
					<input id="price-{{ $value }}" class="checkbox-style uncheck-others" name="search[price]" type="checkbox" value="{{ $value }}"
						{{ isset($search['price']) && $search['price'] == $value ? 'checked' : '' }}>
					<label for="price-{{ $value }}" class="checkbox-style-3-label custom-checkbox-style-3-label">{{ $text }}</label>
				@endforeach
				
				<input id="price-range" class="checkbox-style uncheck-others" name="search[price]" type="checkbox" value="{{ $search['price'] ?? $min.'-'.$max }}" 
					data-range="#sidebar-range" 
					{{ isset($search['price']) && !in_array($search['price'], array_keys($prices)) ? 'checked' : '' }}>
				<label for="price-range" class="checkbox-style-3-label custom-checkbox-style-3-label">{{ __('Interval pret') }}</label>
				<span class="w-100">
					<input id="sidebar-range" class="range" value="{{ $search['price'] ?? $min.'-'.$max }}" 
						data-input="#price-range"
						data-type="double"
						data-step="1"
						data-min="{{ $min }}"
						data-max="{{ $max }}"
						data-postfix=" lei"
						data-hide-min-max="true"
						data-input-values-separator="-"
						/>
				</span>
			</div>
			<!-- Rating -->
			<div class="line-sidebar"></div>
				<h4 class="sidebar-category-title">{{ __('Rating') }}</h4>
			<div class="line-sidebar"></div>
			<div class="flex-start">
				@for($i = 5 ; $i >= 1 ; $i--)
					<input id="rating-{{ $i }}" class="checkbox-style uncheck-others" name="search[rating]" type="checkbox" value="{{ $i }}"
						{{ isset($search['rating']) &&  $search['rating'] == $i ? 'checked' : '' }}>
					<label for="rating-{{ $i }}" class="checkbox-style-3-label custom-checkbox-style-3-label">
						<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
							<div class="rating-stars custom-rating-stars-cursor-pointer" tabindex="0">
								<span class="empty-stars">
									@for($j = $i ; $j >= 1 ; $j--)
										<span class="star custom-star" title="{{ $j }} Star{{ $j > 1 ? 's' : '' }}">
											<span class="icon-star3"></span>
										</span>
									@endfor
									@for($j = 5 - $i ; $j >= 1 ; $j--)
										<span class="star custom-star" title="{{ $j }} Star{{ $j > 1 ? 's' : '' }}">
											<span class="icon-star-empty"></span>
										</span>
									@endfor
								</span>
							</div>
						</div>
					</label>
				@endfor
			</div>
		@endif
		<div class="widget my-4">
			<h4 class="newsletter_heading mb-0">{{ __('Descarca catalogul nostru de produse') }}</h4>
			<a href="http://www.genway.ro/catalog_Electro_Service_octombrie_2014.pdf" target="_blank">
				<img class="sidebar-image mt-4 mb-0" src="{{ asset('images/catalogpdf-2.png') }}" alt="">
			</a>
		</div>
		<div class="widget custom-subscribe-widget newsletter_section my-4 pb-0">
			<h4 class="newsletter_heading">Newsletter</h4>
			<x-newsletter-form />
		</div>
		<div class="widget my-4">
			<img class="sidebar-image mt-4 mb-0" src="{{ asset('images/netopia_payments.png') }}" alt="">
		</div>
	</div>
</div><!-- .sidebar end -->