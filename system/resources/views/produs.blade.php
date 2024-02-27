<x-app-layout
    :title="$meta_title ?? null"
	:keywords="$meta_keywords ?? null"
	:description="$meta_description ?? null"
>
	@push('styles')
        <link rel="stylesheet" href="{{ asset('css/bs-rating.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}" type="text/css" />
	@endpush
	@push('scripts')
        <script src="{{ asset('js/star-rating.js') }}"></script>
	@endpush
	<div class="row gutter-10">
		<!-- Post Content
		============================================= -->
		<div class="postcontent col-lg-12 order-lg-last">
			<x-breadcrumbs :breadcrumbs="$breadcrumbs" />
			<div class="single-product custom-single-product-container">
				<div class="product">
					<div class="row gutter-40">
						@if(isset($galerie) && count($galerie) > 0)
							<div class="col-md-6">
								<x-gallery :gallery="$galerie" :folder="'produse/'" />
							</div>
						@endif
						<div class="col-md-{{ isset($galerie) && count($galerie) > 0 ? '6' : '12' }} product-desc">
							<div class="text-center">
								<h3 class="fw-medium h1"><span data-animate="svg-underline-animated" class="svg-underline nocolor lower"><span>{{ $produs->nume }}</span></span></h3>
							</div>
							<div class="d-flex align-items-center justify-content-between">
								<!-- Product Single - Price
								============================================= -->
								<div class="product-price">
									<ins>{{ $produs->pret_cu_tva }} lei</ins>
									@if($produs->discount)
										<br>
										<del>{{ $produs->pret_intreg_cu_tva }} lei</del>
									@endif
								</div><!-- Product Single - Price End -->
								<div>
									<x-rating :rating="$produs->rating" />
									@if($produs->discount)
										<br>
										<h4 class="m-0"><div class="sale-flash badge bg-success p-2 position-static">
				                            {{ __('Reducere de :value:unit', $produs->discount_valoare_cu_tva) }}
					                    </div></h4>
									@endif
								</div>
							</div>
							<div class="line"></div>
							@if($produs->stoc > 0)
								<!-- Product Single - Quantity & Cart Button
								============================================= -->
								<!-- Session Status -->
							    <x-auth-session-status class="mb-4" :status="session('cart_status')" />
							    <!-- Validation Errors -->
							    <x-auth-validation-errors class="mb-4" :errors="$errors" :bag="'cart'" />
								<form class="cart mb-0 d-flex justify-content-between align-items-center"
									action="{{ route('cart.add', $produs->id) }}"
									method="post" enctype='multipart/form-data'>
									@csrf
									<div class="quantity clearfix">
										<button type="button" class="minus">
											<i class="icon-line-minus"></i>
										</button>
										<input type="number" step="1" min="1" name="cantitate" value="1" title="Qty" class="qty custom-qty-one-product" />
										<button type="button" class="plus">
											<i class="icon-line-plus"></i>
										</button>
									</div>
									<button type="submit" class="add-to-cart button m-0"><i class="icon-line-shopping-cart"></i> {{ __('Adauga in cos') }}</button>
								</form><!-- Product Single - Quantity & Cart Button End -->
							@else
								<div class="style-msg2 errormsg">
							        <div class="msgtitle">
							            <i class="icon-remove-sign"></i>
							            {{ __('Acest produs nu mai este in stoc.') }}
							        </div>
							    </div>
							@endif
							<div class="line"></div>
							<!-- Product Single - Short Description
							============================================= -->
							<div style="list-style: inside;">{!! $produs->meta_description !!}</div>
							@if($fisiere->isNotEmpty())
							<ul class="iconlist">
								@foreach($fisiere as $fisier)
									<li><i class="icon-caret-right"></i><a href="{{ route('files.tech', $fisier->fisier) }}">{{  $fisier->titlu ?: __('Fisiere tehnice') }}<i class="icon-download m-1"></i></a></li>
								@endforeach
							</ul>
							@endif
							<!-- Product Single - Short Description End -->
							@auth
							<!-- Modal Reviews
							============================================= -->
							<div class="flex-center"><a href="#" data-bs-toggle="modal" data-bs-target="#reviewFormModal" class="button button-3d m-4">{{ __('Adauga un review') }}</a></div>
							<div class="modal fade" id="reviewFormModal" tabindex="-1" role="dialog" aria-labelledby="reviewFormModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="reviewFormModalLabel">{{ __('Posteaza review') }}</h4>
											<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-hidden="true"></button>
										</div>
										<div class="modal-body">
											<x-review-form :product="$produs" />
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
							<!-- Modal Reviews End -->
							@endauth
							<!-- Product Single - Meta
							============================================= -->
							<ul class="list-group list-group-flush custom-list-group-flush">
								<li class="list-group-item d-flex justify-content-between align-items-center px-0">
									{{-- <span class="text-muted">Producator:</span><span class="text-dark fw-semibold"><a href="{{ route('products', ['search[provider]' => [$producator->id]]) }}">{{ $producator->nume }}</a></span> --}}
									<span class="text-muted">Producator:</span><span class="text-dark fw-semibold">
                                        <a href="{{ route('producer', ['producator' => $producator->slug]) }}">{{ $producator->nume }}</a>
                                    </span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0">
									<span class="text-muted">Cod produs:</span><span class="text-dark fw-semibold">{{ $produs->cod_ean13 }}</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0">
									<span class="text-muted">Stoc:</span>
									@if($produs->stoc > '10')
										<span class="text-success fw-semibold">{{ __('In stoc') }}</span>
									@elseif($produs->stoc > '0' && $produs->stoc <= '10')
										<span class="text-warning fw-semibold">{{ __('Stoc limitat') }}</span>
									@else
										<span class="text-danger fw-semibold">{{ __('Stoc epuizat') }}</span>
									@endif
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0">
									<span class="text-muted">TVA ({{ $produs->produsCotaTva() }}%):</span><span class="text-dark fw-semibold">{{ __('Inclus in pret') }}</span>
								</li>
							</ul><!-- Product Single - Meta End -->
						</div>
						<div class="w-100"></div>
						<div class="col-12 mt-5">
							<div class="tabs clearfix mb-0" id="tab-1">
								<ul class="tab-nav clearfix">
									<li><a href="#tab-description"><i class="icon-align-justify2"></i><span class="d-none d-md-inline-block"> {{ __('Descriere') }}</span></a></li>
									@if($comentarii->isNotEmpty())
									<li><a href="#tab-reviews"><i class="icon-star3"></i><span class="d-none d-md-inline-block"> {{ __('Review-uri') }} ({{ count($comentarii) }})</span></a></li>
									@endif
								</ul>
								<div class="tab-container">
									<div class="tab-content clearfix" id="tab-description">
										{!! $produs->descriere !!}
									</div>
									@if($comentarii->isNotEmpty())
									<div class="tab-content clearfix" id="tab-reviews">
										<div id="reviews" class="clearfix">
											<div class="clear"></div>
											<ol class="commentlist clearfix">
												@foreach($comentarii as $index => $comentariu)
													<li class="comment even thread-even depth-1" id="li-comment-{{ $index }}">
														<x-review
															:name="$comentariu->user ? $comentariu->user->nume : false"
															:rating="$comentariu->nota"
															:text="$comentariu->comentarii"
															:time="$comentariu->transformDate('created_at', 'F d, Y H:s')"
															/>
													</li>
												@endforeach
											</ol>
										</div>
									</div>
									@endif
								</div>
							</div>
							@auth
								<div class="line mb-0"></div>
								<div class="modal-header ">
									<h4 class="modal-title">{{ __('Adauga un review') }}</h4>
								</div>
								<x-review-form :product="$produs" />
							@endauth
							<div class="line"></div>
							<div class="row custom-row-align-center">
								<div class="col-md-4 d-none d-md-block">
									<a href="#" title="Brand Logo"><img src="{{ asset('images/logo.png') }}" alt="Brand Logo"></a>
								</div>
								<div class="col-md-8">
									<div class="row gutter-30">
										<div class="col-lg-6">
											<div class="feature-box fbox-plain fbox-dark fbox-sm">
												<div class="fbox-icon">
													<i class="icon-thumbs-up2"></i>
												</div>
												<div class="fbox-content">
													<h3>100% Produse Premium</h3>
													{{-- <p class="mt-0">We guarantee you the sale of Original Brands with warranties.</p> --}}
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="feature-box fbox-plain fbox-dark fbox-sm">
												<div class="fbox-icon">
													<i class="icon-credit-cards"></i>
												</div>
												<div class="fbox-content">
													<h3>Plata in Rate</h3>
													{{-- <p class="mt-0">We accept Visa, MasterCard and American Express &amp; of-course PayPal.</p> --}}
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="feature-box fbox-plain fbox-dark fbox-sm">
												<div class="fbox-icon">
													<i class="icon-line-phone-call"></i>
												</div>
												<div class="fbox-content">
													<h3>Consultanta Gratuita</h3>
													{{-- <p class="mt-0">Free Delivery to 100+ Locations Worldwide on orders above $40.</p> --}}
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="feature-box fbox-plain fbox-dark fbox-sm">
												<div class="fbox-icon">
													<i class="bi-patch-check-fill"></i>
												</div>
												<div class="fbox-content">
													<h3>Garantie 7-25 de ani</h3>
													{{-- <p class="mt-0">Return or exchange items purchased within 30 days for Free.</p> --}}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@if($recomandate->isNotEmpty())
				<div class="line"></div>
				<div class="w-100">
					<h4>{{ __('Produse recomandate') }}</h4>
					<div class="owl-carousel product-carousel carousel-widget" data-margin="30" data-pagi="false" data-autoplay="5000" data-items-xs="1" data-items-md="2" data-items-lg="3" data-items-xl="4">
						@foreach($recomandate as $produs)
							<x-new-product
                                :image="'produse/'.$produs->imagine"
                                :title="$produs->nume"
                                :url="$produs->route"
                                :price="$produs->pret_cu_tva"
                                :fullPrice="$produs->pret_intreg_cu_tva"
                                :tva_inclus="true"
                                :new="false"
                                :rating="$produs->rating"
                                 />
						@endforeach
					</div>
				</div>
			@endif
			@if($asemanatoare->isNotEmpty())
				<div class="line"></div>
				<div class="w-100">
					<h4>{{ __('Produse din aceasi categorie') }}</h4>
					<div class="owl-carousel product-carousel carousel-widget" data-margin="30" data-pagi="false" data-autoplay="5000" data-items-xs="1" data-items-md="2" data-items-lg="3" data-items-xl="4">
						@foreach($asemanatoare as $produs)
							<x-new-product
                                :image="'produse/'.$produs->imagine"
                                :title="$produs->nume"
                                :url="$produs->route"
                                :price="$produs->pret_cu_tva"
                                :fullPrice="$produs->pret_intreg_cu_tva"
                                :tva_inclus="true"
                                :new="false"
                                :rating="$produs->rating"
                                />
						@endforeach
					</div>
				</div>
			@endif
			<div class="line"></div>
		</div><!-- .postcontent end -->
	</div>
</x-app-layout>
