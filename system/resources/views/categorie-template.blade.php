<x-app-layout>
	@push('styles')
        <link rel="stylesheet" href="{{ asset('css/bs-rating.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/ion.rangeslider.css') }}" type="text/css" />
	@endpush
	<div class="row gutter-10 col-mb-80">
		<!-- Post Content
		============================================= -->
		<div class="postcontent col-lg-10 order-lg-last">
			<!-- Content
								============================================= -->
			<section id="content">
				<div class="content-wrap custom-content-wrap">
					<div class="container">
						<div class="margin-low header-misc order-last m-0 my-4 my-lg-0 flex-grow-1 flex-lg-grow-0">
							<!-- Top Search
												============================================= -->
							<form action="#" method="get" class="w-100">
								<div class="input-group">
									<input type="text" name="q" class="form-control" value="" placeholder="Cauta un produs">
									<button class="input-group-text"><i class="icon-line-search"></i></button>
								</div>
							</form>
							<!-- #top-search end -->
						</div>

						<div class="row justify-content-between align-items-center flex-center-category-name">
							<div class="col-auto mb-4">
								<h3 class="fw-medium h1">Interfoane <span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>Audio</span></span></h3>
							</div>

							<div class="col-auto mb-4 d-flex custom-space-between-d-flex custom-dropdown-sort-filters">

								<div class="dropdown sortbuttons">
									<div class="col-lg-4">
										<div class="btn-group" role="group" data-filter-group="color">
											<button type="button" class="btn btn-secondary custom-btn-secondary dropdown-toggle custom-dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="filter-text">Sorteaza dupa</span></button>
											<ul class="dropdown-menu" role="menu">
												<a class="dropdown-item" href="#" data-filter="">Popularitate</a>
												<a class="dropdown-item" href="#" data-filter="">Denumire A->Z</a>
												<a class="dropdown-item" href="#" data-filter="">Denumire Z->A</a>
												<a class="dropdown-item" href="#" data-filter="">Cele mai noi</a>
												<a class="dropdown-item" href="#" data-filter="">Cele mai vechi</a>
												<a class="dropdown-item" href="#" data-filter="">Pret Crescator</a>
												<a class="dropdown-item" href="#" data-filter="">Pret Descrescator</a>
											</ul>
										</div>
									</div>
								</div>

								<div class="dropdown sortbuttons ms-2">
									<div class="col-lg-4">
										<div class="btn-group" role="group" data-filter-group="color">
											<button type="button" class="btn btn-secondary custom-btn-secondary dropdown-toggle custom-dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="filter-text">Afiseaza</span></button>
											<ul class="dropdown-menu" role="menu">
												<a class="dropdown-item" href="#" data-filter="">100 produse / pag.</a>
												<a class="dropdown-item" href="#" data-filter="">80 produse / pag.</a>
												<a class="dropdown-item" href="#" data-filter="">40 produse / pag.</a>
												<a class="dropdown-item" href="#" data-filter="">20 produse / pag.</a>
												<a class="dropdown-item" href="#" data-filter="">8 produse / pag.</a>
											</ul>
										</div>
									</div>
								</div>
								<div>
									<input id="checkbox-10" class="checkbox-style" name="checkbox-10" type="checkbox" checked>
									<label for="checkbox-10" class="checkbox-style-3-label custom-checkbox-style-3-label">Doar cu reducere</label>
								</div>
							</div>

							<!-- About Category -->
							<div class="col-md-12">
								<div class="custom-read-more" data-readmore="true" data-readmore-trigger-open="Citeste mai mult <i class='icon-angle-down'></i>" data-readmore-trigger-close="Vezi mai putin <i class='icon-angle-up'></i>">

									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem ullam aliquid quos, tempore, quibusdam saepe numquam fugiat ab perspiciatis minima itaque in repellendus ad, modi voluptates quas distinctio iusto nesciunt. Esse, maxime dolorem distinctio quibusdam doloribus reiciendis sint obcaecati ex impedit ea non nam eos voluptate quod recusandae provident, unde libero, vel laudantium hic a. Ea amet, maxime voluptatibus distinctio temporibus libero cupiditate deserunt, facere rem, alias similique mollitia possimus asperiores cum harum at doloribus sed voluptas ipsam. Accusamus adipisci at sint voluptas unde deserunt incidunt excepturi perferendis quae similique laudantium voluptatibus quo sed perspiciatis autem quis hic, aut pariatur iste aliquid, veniam suscipit doloribus aliquam praesentium.</p>

									<p class="mb-0">Maxime quos, ex eligendi possimus earum amet, voluptatibus tempore asperiores officia, blanditiis non adipisci labore deleniti. Aliquam perferendis cumque maxime necessitatibus ut consequuntur, alias, error, inventore pariatur, quisquam eius impedit dolores recusandae enim harum! Saepe vel optio nemo id magni tempore repudiandae quod ullam, quia reprehenderit incidunt ipsum natus cum quidem? Provident, a ut.</p>

									<a href="#" class="btn btn-link text-primary read-more-trigger read-more-trigger-right"></a>

								</div>
							</div>
							<!-- End About Category -->
						</div>

						<div class="subcategories-container">
							<div class="row grid--1x4">
								<!-- Shop Item 1
													============================================= -->
								<div class="custom-col-lg-4 col-md-6 mb-4 sf-shoes sf-sportswear sf-new text-center subcategories-card-border">
									<div class="product">
										<div class="product-image position-relative">
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_3.png" alt="Black Shoe"></a>
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_2.png" alt="Black Shoe"></a>
										</div>
										<div class="product-desc">
											<div class="product-title">
												<h3><a href="#">Circuit interfon</a></h3>
											</div>
										</div>
									</div>
								</div>
								<!-- Shop Item 2
								============================================= -->
								<div class="custom-col-lg-4 col-md-6 mb-4 sf-shoes sf-sportswear sf-new text-center subcategories-card-border">
									<div class="product">
										<div class="product-image position-relative">
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_3.png" alt="Black Shoe"></a>
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_2.png" alt="Black Shoe"></a>
										</div>
										<div class="product-desc">
											<div class="product-title">
												<h3><a href="#">Circuit interfon</a></h3>
											</div>
										</div>
									</div>
								</div>
								<!-- Shop Item 3
								============================================= -->
								<div class="custom-col-lg-4 col-md-6 mb-4 sf-shoes sf-sportswear sf-new text-center subcategories-card-border">
									<div class="product">
										<div class="product-image position-relative">
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_3.png" alt="Black Shoe"></a>
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_2.png" alt="Black Shoe"></a>
										</div>
										<div class="product-desc">
											<div class="product-title">
												<h3><a href="#">Circuit interfon</a></h3>
											</div>
										</div>
									</div>
								</div>
								<!-- Shop Item 4
								============================================= -->
								<div class="custom-col-lg-4 col-md-6 mb-4 sf-shoes sf-sportswear sf-new text-center subcategories-card-border">
									<div class="product">
										<div class="product-image position-relative">
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_3.png" alt="Black Shoe"></a>
											<a class="custom-img-overlay" href="#"><img class="custom-img-overlay" src="./images/image_2.png" alt="Black Shoe"></a>
										</div>
										<div class="product-desc">
											<div class="product-title">
												<h3><a href="#">Circuit interfon</a></h3>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Shop
						============================================= -->
						<div class="shop row gutter-10 col-mb-10 mt-3">

							<!-- 4 produse -->
							<!-- Product 1 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div><!-- Product 2 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div><!-- Product 3 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div><!-- Product 4 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div>
							<!-- 4 produse -->
							<!-- Product 1-->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div><!-- Product 2 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div><!-- Product 3 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="#"><img src="./images/image_1.png" alt=""></a>
										<a href="#"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs rating-animate rating-disabled">
															<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
																<span class="empty-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-empty"></span>
																	</span>
																</span>
																<span class="filled-stars">
																	<span class="star custom-star" title="One Star">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Two Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Three Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Four Stars">
																		<span class="icon-star3"></span>
																	</span>
																	<span class="star custom-star" title="Five Stars">
																		<span class="icon-star-half-full"></span>
																	</span>
																</span>
															</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div><!-- Product 4 -->
							<div class="product col-xl-3 col-md-4 col-sm-6 col-12 custom-products-cards-padding">
								<div class="grid-inner card-border">
									<div class="product-image">
										<a href="img-back"><img src="./images/image_1.png" alt=""></a>
										<a href="img-front"><img src="./images/image_2.png" alt=""></a>
									</div>
									<div class="product-desc text-center">
										<div class="product-title mb-0">
											<h4 class="mb-0 fs-5"><a class="fw-bold" href="#">Set interfon audio pentru vila</a></h4>
										</div>
										<p class="fs-6 margin-low custom-main-product-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Odit, minima!</p>
										<h5 class="product-price fw-semibold">$799.99</h5>
										<div class="rating-container theme-krajee-svg rating-xs custom-rating-xs rating-animate rating-disabled">
											<div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
												<span class="empty-stars">
													<span class="star custom-star" title="One Star">
														<span class="icon-star-empty"></span>
													</span>
													<span class="star custom-star" title="Two Stars">
														<span class="icon-star-empty"></span>
													</span>
													<span class="star custom-star" title="Three Stars">
														<span class="icon-star-empty"></span>
													</span>
													<span class="star custom-star" title="Four Stars">
														<span class="icon-star-empty"></span>
													</span>
													<span class="star custom-star" title="Five Stars">
														<span class="icon-star-empty"></span>
													</span>
												</span>
												<span class="filled-stars">
													<span class="star custom-star" title="One Star">
														<span class="icon-star3"></span>
													</span>
													<span class="star custom-star" title="Two Stars">
														<span class="icon-star3"></span>
													</span>
													<span class="star custom-star" title="Three Stars">
														<span class="icon-star3"></span>
													</span>
													<span class="star custom-star" title="Four Stars">
														<span class="icon-star3"></span>
													</span>
													<span class="star custom-star" title="Five Stars">
														<span class="icon-star-half-full"></span>
													</span>
												</span>
											</div>
										</div>
										<a href="#" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"></i>Adauga in cos</span></a>
									</div>
								</div>
							</div>

						</div><!-- #shop end -->


						<nav aria-label="Page navigation" class="custom-pagination custom-pagination-margin">
							<ul class="custom-pagination justify-content-center mt-4">
								<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
								<li class="page-item"><a class="page-link" href="#">2</a></li>
								<li class="page-item"><a class="page-link" href="#">3</a></li>
								<li class="page-item"><a class="page-link" href="#">Next</a></li>
							</ul>
						</nav>

					</div>
				</div>
			</section><!-- #content end -->

		</div><!-- .postcontent end -->

		<x-sidebar-layout />
	</div>
	@push('scripts')
        <script src="{{ asset('js/star-rating.js') }}"></script>
        <!-- Price range (sidebar (sidebar.php ))-->
        <script src="{{ asset('js/rangeslider.min.js') }}"></script>
        <!-- Range Slider Example -->
        <script>
            var priceRangefrom = 0,
                priceRangeto = 0,
                $container = null;

            jQuery(window).on( 'load', function(){

                $container = $('#shop');
                $container.isotope({ transitionDuration: '0.65s' });

                $('.grid-filter a').click(function(){
                    $('.grid-filter li').removeClass('activeFilter');
                    $(this).parent('li').addClass('activeFilter');
                    var selector = $(this).attr('data-filter');
                    $container.isotope({ filter: selector });
                    return false;
                });

                $(window).resize(function() {
                    $container.isotope('layout');
                });

            });

            jQuery(document).ready( function($){
                $(".range_23").ionRangeSlider({
                    type: "double",
                    min: 9.99,
                    max: 129.99,
                    from: 9.99,
                    to: 129.99,
                    prefix: '$',
                    hide_min_max: true,
                    hide_from_to: false,
                    grid: false,
                    onStart: function (data) {
                        priceRangefrom = data.from;
                        priceRangeto = data.to;
                    },
                    onFinish: function (data) {
                        priceRangefrom = data.from;
                        priceRangeto = data.to;

                        $container.isotope({
                            filter: function() {

                                if( $(this).find('.product-price').find('ins').length > 0 ) {
                                    var price = $(this).find('.product-price ins').text();
                                } else {
                                    var price = $(this).find('.product-price').text();
                                }

                                priceNum = price.split("$");

                                return ( priceRangefrom <= priceNum[1] && priceRangeto >= priceNum[1] );
                            }
                        });

                    }
                });

            });
        </script>
	@endpush
</x-app-layout>