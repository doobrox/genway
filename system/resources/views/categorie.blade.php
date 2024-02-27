<x-app-layout
	:title="$meta_title ?? null"
	:keywords="$meta_keywords ?? null"
	:description="$meta_description ?? null"
	:canonical="$route ?? null"
	>
	@push('styles')
        <link rel="stylesheet" href="{{ asset('css/ion.rangeslider.css') }}" type="text/css" />
	@endpush
	@push('scripts')
        <script src="{{ asset('js/rangeslider.min.js') }}"></script>
	@endpush
	<div class="row gutter-10">
		<!-- Post Content
		============================================= -->
		<div class="postcontent col-lg-10 order-lg-last">
			<!-- Content
			============================================= -->
			<section id="content">
				<div class="">
					<div class="container">
						<div class="margin-low header-misc order-last m-0 my-4 my-lg-0 flex-grow-1 flex-lg-grow-0">
							<!-- Top Search
							============================================= -->
							<div class="w-100">
								<div class="input-group">
									<input type="text" name="search[c]" class="form-control" value="{{ $search['c'] ?? '' }}" placeholder="{{ __('Cauta un produs') }}" data-search-route="{{ $route }}">
									<button class="input-group-text"><i class="icon-line-search"></i></button>
								</div>
							</div>
							<!-- #top-search end -->
						</div>

						<div class="row justify-content-between align-items-center flex-center-category-name my-2">
							@isset($categorie)
                                <div class="col-auto">
                                    {{-- <h3 class="fw-medium h1">Interfoane <span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>Audio</span></span></h3> --}}
                                    <h3 class="fw-medium h1">{{ $categorie->nume }}</h3>
                                    <div class="description-box-styling mb-4">{!! $categorie->descriere !!}</div>
                                </div>
							@endisset

							<div class="col-auto d-flex custom-space-between-d-flex custom-dropdown-sort-filters">
								<div class="dropdown sortbuttons mx-2">
									<div class="col-lg-4">
										<div class="btn-group my-2" role="group" data-filter-group="sort">
											<select class="btn btn-secondary custom-btn-secondary" name="search[sort]" role="menu">
												{{-- <option value="">Sorteaza dupa</option> --}}
												@foreach($sort as $value => $array)
													<option value="{{ $value === 0 ? '' : $value }}"
														{{ isset($search['sort']) && $search['sort'] == $value ? 'selected' : '' }}>
														{{ $array[2] }}
													</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="dropdown sortbuttons mx-2">
									<div class="col-lg-4">
										<div class="btn-group my-2" role="group" data-filter-group="pag">
											<select class="btn btn-secondary custom-btn-secondary" name="search[pag]" role="menu">
												<option value="">Afiseaza</option>
												@foreach($pagination as $pag)
													<option value="{{ $pag }}"
														{{ isset($search['pag']) && $search['pag'] == $pag ? 'selected' : '' }}>
														{{ __(':pag produse / pag.', ['pag' => $pag]) }}
													</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div>
									<input id="checkbox-10" class="checkbox-style" name="search[discount]" type="checkbox" {{ isset($search['discount']) ? 'checked' : '' }}>
									<label for="checkbox-10" class="checkbox-style-3-label custom-checkbox-style-3-label">{{ __('Doar cu reducere') }}</label>
								</div>
							</div>

							{{-- @if(isset($categorie) && $categorie->descriere)
							<!-- About Category -->
							<div class="col-md-12">
								<div class="custom-read-more" data-readmore="true" data-readmore-trigger-open="Citeste mai mult <i class='icon-angle-down'></i>" data-readmore-trigger-close="Vezi mai putin <i class='icon-angle-up'></i>">

									<p>{!! $categorie->descriere !!}</p>

									<a href="#" class="btn btn-link text-primary read-more-trigger read-more-trigger-right"></a>

								</div>
							</div>
							<!-- End About Category -->
							@endif --}}
						</div>
						@if(isset($subcategorii) && count($subcategorii) > 0)
							<div class="subcategories-container px-0">
								<div class="row custom-sigla-container gutter-10 px-0 pb-0">
									@foreach($subcategorii as $subcategorie)
		                                <x-category class="col-xl-3 col-md-4 col-sm-6 col-12"
		                                	title="$subcategorie->nume"
		                                    :image="$subcategorie->imagine ? 'categorii/'.$subcategorie->imagine : null"
		                                    :title="$subcategorie->nume"
		                                    :url="route('category', $subcategorie->slug)"
		                                    />
		                            @endforeach
								</div>
							</div>
							<hr>
						@endif

						<!-- Shop
						============================================= -->
						<div id="shop" class="shop row gutter-10 mt-3">
							@forelse($produse as $produs)
                                <x-product class="col-xl-3 col-md-4 col-sm-6 col-12"
                                    :product="$produs" />
                            @empty
                            	<p>{{ __('Nici un produs gasit. Mariti aria de cautare.') }}</p>
                            @endforelse
                            <nav aria-label="Page navigation" class="mt-3">
								{{ $produse->links() }}
							</nav>
						</div><!-- #shop end -->

                        @isset($categorie)
                            <div class="col-auto">
                                <div class="description-box-styling mb-4">{!! $categorie->descriere_jos !!} </div>
                            </div>
                        @endisset
					</div>
				</div>
			</section><!-- #content end -->

		</div><!-- .postcontent end -->

		<x-sidebar-layout :min="$min" :max="$max" />
	</div>
</x-app-layout>
