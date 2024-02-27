<x-app-layout>
	@push('styles')
        <link rel="stylesheet" href="{{ asset('css/bs-rating.css') }}" type="text/css" />
	@endpush
	<div class="row gutter-10 col-mb-80">
		<!-- Post Content
		============================================= -->
		<div class="postcontent col-lg-12 order-lg-last">

				<!-- Page Title
				============================================= -->
				<section id="custom-breadcrumbs">

					<div class="container clearfix">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">Dispozitive telecomunicatii</a></li>
							<li class="breadcrumb-item"><a href="#">Aparate audio</a></li>
							<li class="breadcrumb-item active" aria-current="page">Interfoane audio</li>
						</ol>
					</div>

				</section><!-- #page-title end -->
			
				<div class="single-product custom-single-product-container">
					<div class="product">
						<div class="row gutter-40">

							<div class="col-md-6">

								<!-- Product Single - Gallery
								============================================= -->
								<div class="fslider custom-single-product-arrows" data-arrows="true" data-animation="fade" data-thumbs="true" data-slideshow="false">
									<div class="flexslider">
										<div class="slider-wrap custom-image-slide-container" data-lightbox="gallery">
											<div class="slide" data-thumb="images/image_1.png"><a href="images/image_1.png" title="Pink Printed Dress - Front View" data-lightbox="gallery-item"><img src="images/image_1.png" alt="Pink Printed Dress"></a></div>
											<div class="slide" data-thumb="images/image_2.png"><a href="images/image_2.png" title="Pink Printed Dress - Front View" data-lightbox="gallery-item"><img src="images/image_2.png" alt="Pink Printed Dress"></a></div>
											<div class="slide" data-thumb="images/image_3.png"><a href="images/image_3.png" title="Pink Printed Dress - Front View" data-lightbox="gallery-item"><img src="images/image_3.png" alt="Pink Printed Dress"></a></div>
										</div>
									</div>
								</div>
								<!-- Product Single - Gallery End -->
							</div>

							<div class="col-md-6 product-desc">
								
								<div class="text-center">
									<h3 class="fw-medium h1">Interfoane <span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>Audio</span></span></h3>
								</div>
								<div class="d-flex align-items-center justify-content-between">

									
									<!-- Product Single - Price
									============================================= -->
									<div class="product-price"><del>$39.99</del> <ins>$24.99</ins></div><!-- Product Single - Price End -->

									<!-- Product Single - Rating
									============================================= -->
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

									<!-- Product Single - Rating End -->

								</div>

								<div class="line"></div>

								<!-- Product Single - Quantity & Cart Button
								============================================= -->
								<form class="cart mb-0 d-flex justify-content-between align-items-center" method="post" enctype='multipart/form-data'>
									<div class="quantity clearfix">
										<input type="button" value="-" class="minus custom-minus-one-product">
										<input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="qty custom-qty-one-product" />
										<input type="button" value="+" class="plus custom-plus-one-product">
									</div>
									<button type="submit" class="add-to-cart button m-0">Add to cart</button>
								</form><!-- Product Single - Quantity & Cart Button End -->

								<div class="line"></div>

								<!-- Product Single - Short Description
								============================================= -->
								
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero velit id eaque ex quae laboriosam nulla optio doloribus! Perspiciatis, libero, neque, perferendis at nisi optio dolor!</p>
								<p>Perspiciatis ad eveniet ea quasi debitis quos laborum eum reprehenderit eaque explicabo assumenda rem modi.</p>

								
								
								<ul class="iconlist">
									<li><i class="icon-caret-right"></i><a href="">Ajutor</a></li>
									<li><i class="icon-caret-right"></i><a href="">Fisiere tehnice</a></li>
									<li><i class="icon-caret-right"></i><a href="">Altele</a></li>
								</ul><!-- Product Single - Short Description End -->
								
								<!-- Modal Reviews
								============================================= -->
								<div class="flex-center"><a href="#" data-bs-toggle="modal" data-bs-target="#reviewFormModal" class="button button-3d m-4">Adauga Comentariu</a></div>

								<div class="modal fade" id="reviewFormModal" tabindex="-1" role="dialog" aria-labelledby="reviewFormModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="reviewFormModalLabel">Posteaza Recenzia</h4>
												<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-hidden="true"></button>
											</div>
											<div class="modal-body">
												<form class="row mb-0" id="template-reviewform" name="template-reviewform" action="#" method="post">

													<div class="col-12 mt-3">
														<label for="template-reviewform-rating m-0">Evaluare <small>*</small></label>
														
														<div class="white-section">
															<input id="input-1" type="number" class="rating" max="5" data-step="0.1" data-size="sm">
														</div>
													</div>

													<div class="w-100"></div>

													<div class="col-12 mb-3">
														<label for="template-reviewform-name">Nume <small>*</small></label>
														<div class="input-group">
															<div class="input-group-text"><i class="icon-user"></i></div>
															<input type="text" id="template-reviewform-name" name="template-reviewform-name" value="" class="form-control required" />
														</div>
													</div>
													<div class="col-12 mb-3">
														<label for="template-reviewform-email">Email <small>*</small></label>
														<div class="input-group">
															<div class="input-group-text">@</div>
															<input type="email" id="template-reviewform-email" name="template-reviewform-email" value="" class="required email form-control" />
														</div>
													</div>


													<div class="w-100"></div>

													<div class="col-12 mb-3">
														<label for="template-reviewform-comment">Comentariu <small>*</small></label>
														<textarea class="required form-control" id="template-reviewform-comment" name="template-reviewform-comment" rows="6" cols="30"></textarea>
													</div>

													<div class="col-12">
														<button class="button button-3d m-0" type="submit" id="template-reviewform-submit" name="template-reviewform-submit" value="submit">Postează</button>
													</div>

												</form>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
								<!-- Modal Reviews End -->

								<!-- Product Single - Meta
								============================================= -->
								<div class="card product-meta">
									<div class="card-body">
										<span class="posted_in">Producator: <a href="#" rel="tag">Genway</a>.</span>
										<span itemprop="productID" class="sku_wrapper">Cod Produs: <span class="sku">8465415</span></span>
										<span class="posted_in">Stoc: <a href="#" rel="tag">+999 Mare</a>.</span>
									</div>
								</div><!-- Product Single - Meta End -->

								<ul class="list-group list-group-flush custom-list-group-flush">
									<li class="list-group-item d-flex justify-content-between align-items-center px-0">
										<span class="text-muted">Producator:</span><span class="text-dark fw-semibold">Genway</span>
									</li>
									<li class="list-group-item d-flex justify-content-between align-items-center px-0">
										<span class="text-muted">Cod produs:</span><span class="text-dark fw-semibold">#Cod-Produs</span>
									</li>
									<li class="list-group-item d-flex justify-content-between align-items-center px-0">
										<span class="text-muted">Stoc:</span><span class="text-dark fw-semibold">+999 Mare</span>
									</li>
								</ul>	

								<!-- Product Single - Share
								============================================= -->
								<div class="si-share border-0 d-flex justify-content-between align-items-center mt-4">
									<span>Share:</span>
									<div>
										<a href="#" class="social-icon si-borderless si-facebook">
											<i class="icon-facebook"></i>
											<i class="icon-facebook"></i>
										</a>
										<a href="#" class="social-icon si-borderless si-twitter">
											<i class="icon-twitter"></i>
											<i class="icon-twitter"></i>
										</a>
										<a href="#" class="social-icon si-borderless si-pinterest">
											<i class="icon-pinterest"></i>
											<i class="icon-pinterest"></i>
										</a>
										<a href="#" class="social-icon si-borderless si-gplus">
											<i class="icon-gplus"></i>
											<i class="icon-gplus"></i>
										</a>
										<a href="#" class="social-icon si-borderless si-rss">
											<i class="icon-rss"></i>
											<i class="icon-rss"></i>
										</a>
										<a href="#" class="social-icon si-borderless si-email3">
											<i class="icon-email3"></i>
											<i class="icon-email3"></i>
										</a>
									</div>
								</div><!-- Product Single - Share End -->

							</div>

							<div class="w-100"></div>

							<div class="col-12 mt-5">

								<div class="tabs clearfix mb-0" id="tab-1">

									<ul class="tab-nav clearfix">
										<li><a href="#tabs-1"><i class="icon-align-justify2"></i><span class="d-none d-md-inline-block"> Description</span></a></li>
										<li><a href="#tabs-2"><i class="icon-info-sign"></i><span class="d-none d-md-inline-block"> Additional Information</span></a></li>
										<li><a href="#tabs-3"><i class="icon-star3"></i><span class="d-none d-md-inline-block"> Reviews (2)</span></a></li>
									</ul>

									<div class="tab-container">

										
										<div class="tab-content clearfix" id="tabs-1">
											<p>Pink printed dress,  woven, round neck with a keyhole and buttoned closure at the back, sleeveless, concealed zip up at left side seam, belt loops along waist with slight gathers beneath, brand appliqu?? above left front hem, has an attached lining.</p>
											Comes with a white, slim synthetic belt that has a tang clasp.
										</div>
										<div class="tab-content clearfix" id="tabs-2">

											<table class="table table-striped table-bordered">
												<tbody>
													<tr>
														<td>Size</td>
														<td>Small, Medium &amp; Large</td>
													</tr>
													<tr>
														<td>Color</td>
														<td>Pink &amp; White</td>
													</tr>
													<tr>
														<td>Waist</td>
														<td>26 cm</td>
													</tr>
													<tr>
														<td>Length</td>
														<td>40 cm</td>
													</tr>
													<tr>
														<td>Chest</td>
														<td>33 inches</td>
													</tr>
													<tr>
														<td>Fabric</td>
														<td>Cotton, Silk &amp; Synthetic</td>
													</tr>
													<tr>
														<td>Warranty</td>
														<td>3 Months</td>
													</tr>
												</tbody>
											</table>

										</div>
										<div class="tab-content clearfix" id="tabs-3">

											<div id="reviews" class="clearfix">

												<div class="clear"></div>

												<ol class="commentlist clearfix">

													<li class="comment even thread-even depth-1" id="li-comment-1">
														<div id="comment-1" class="comment-wrap clearfix">

															<div class="comment-meta">
																<div class="comment-author vcard">
																	<span class="comment-avatar clearfix">
																	<img alt='Image' src='https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=60' height='60' width='60' /></span>
																</div>
															</div>
															
															<div class="comment-content clearfix">
																<div class="comment-author">John Doe<span><a href="#" title="Permalink to this comment">April 24, 2021 at 10:46AM</a></span></div>
																<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo perferendis aliquid tenetur. Aliquid, tempora, sit aliquam officiis nihil autem eum at repellendus facilis quaerat consequatur commodi laborum saepe non nemo nam maxime quis error tempore possimus est quasi reprehenderit fuga!</p>
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

															</div>

															<div class="clear"></div>

														</div>
													</li>

													<li class="comment even thread-even depth-1" id="li-comment-2">
														<div id="comment-2" class="comment-wrap clearfix">

															<div class="comment-meta">
																<div class="comment-author vcard">
																	<span class="comment-avatar clearfix">
																	<img alt='Image' src='https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=60' height='60' width='60' /></span>
																</div>
															</div>

															<div class="comment-content clearfix">
																<div class="comment-author">Mary Jane<span><a href="#" title="Permalink to this comment">June 16, 2021 at 6:00PM</a></span></div>
																<p>Quasi, blanditiis, neque ipsum numquam odit asperiores hic dolor necessitatibus libero sequi amet voluptatibus ipsam velit qui harum temporibus cum nemo iste aperiam explicabo fuga odio ratione sint fugiat consequuntur vitae adipisci delectus eum incidunt possimus tenetur excepturi at accusantium quod doloremque reprehenderit aut expedita labore error atque?</p>
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
															</div>

															<div class="clear"></div>

														</div>
													</li>

												</ol>	

											</div>

										</div>

									</div>

								</div>

								<div class="line mb-0"></div>

								<div class="modal-header ">
									<h4 class="modal-title" id="reviewFormModalLabel">Adauga un comentariu</h4>
								</div>
								<div class="modal-body">
									<form class="row mb-0" id="template-reviewform" name="template-reviewform" action="#" method="post">

										<div class="col-12 mt-3">
											<label for="template-reviewform-rating m-0">Evaluare <small>*</small></label>
											
											<div class="white-section">
												<input id="input-1" type="number" class="rating" max="5" data-step="0.1" data-size="sm">
											</div>
										</div>

										<div class="w-100"></div>

										<div class="col-12 mb-3">
											<label for="template-reviewform-name">Nume <small>*</small></label>
											<div class="input-group">
												<div class="input-group-text"><i class="icon-user"></i></div>
												<input type="text" id="template-reviewform-name" name="template-reviewform-name" value="" class="form-control required" />
											</div>
										</div>
										<div class="col-12 mb-3">
											<label for="template-reviewform-email">Email <small>*</small></label>
											<div class="input-group">
												<div class="input-group-text">@</div>
												<input type="email" id="template-reviewform-email" name="template-reviewform-email" value="" class="required email form-control" />
											</div>
										</div>


										<div class="w-100"></div>

										<div class="col-12 mb-3">
											<label for="template-reviewform-comment">Comentariu <small>*</small></label>
											<textarea class="required form-control" id="template-reviewform-comment" name="template-reviewform-comment" rows="6" cols="30"></textarea>
										</div>

										<div class="col-12">
											<button class="button button-3d m-0" type="submit" id="template-reviewform-submit" name="template-reviewform-submit" value="submit">Postează</button>
										</div>

									</form>
								</div>

								<div class="line"></div>

								<div class="row custom-row-align-center">
									<div class="col-md-4 d-none d-md-block">
										<a href="#" title="Brand Logo"><img src="images/logo.png" alt="Brand Logo"></a>
									</div>

									<div class="col-md-8">

										<div class="row gutter-30">

											<div class="col-lg-6">
												<div class="feature-box fbox-plain fbox-dark fbox-sm">
													<div class="fbox-icon">
														<i class="icon-thumbs-up2"></i>
													</div>
													<div class="fbox-content">
														<h3>100% Original Brands</h3>
														<p class="mt-0">We guarantee you the sale of Original Brands with warranties.</p>
													</div>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="feature-box fbox-plain fbox-dark fbox-sm">
													<div class="fbox-icon">
														<i class="icon-credit-cards"></i>
													</div>
													<div class="fbox-content">
														<h3>Lots of Payment Options</h3>
														<p class="mt-0">We accept Visa, MasterCard and American Express &amp; of-course PayPal.</p>
													</div>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="feature-box fbox-plain fbox-dark fbox-sm">
													<div class="fbox-icon">
														<i class="icon-truck2"></i>
													</div>
													<div class="fbox-content">
														<h3>Free Express Shipping</h3>
														<p class="mt-0">Free Delivery to 100+ Locations Worldwide on orders above $40.</p>
													</div>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="feature-box fbox-plain fbox-dark fbox-sm">
													<div class="fbox-icon">
														<i class="icon-undo"></i>
													</div>
													<div class="fbox-content">
														<h3>30-Days Returns Policy</h3>
														<p class="mt-0">Return or exchange items purchased within 30 days for Free.</p>
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

				<div class="line"></div>

				<div class="w-100">

					<h4>Related Products</h4>

					<div class="owl-carousel product-carousel carousel-widget" data-margin="30" data-pagi="false" data-autoplay="5000" data-items-xs="1" data-items-md="2" data-items-lg="3" data-items-xl="4">

						<div class="oc-item">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="images/image_1.png" alt="Checked Short Dress"></a>
									<a href="#"><img src="images/image_2.png" alt="Checked Short Dress"></a>
									<div class="sale-flash badge bg-success p-2">50% Off*</div>
									<div class="bg-overlay">
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc center">
									<div class="product-title"><h3><a href="#">Checked Short Dress</a></h3></div>
									<div class="product-price"><del>$24.99</del> <ins>$12.49</ins></div>
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
												<span class="star custom-star" title="Five Stars">
													<span class="icon-star-half-full"></span>
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="images/image_1.png" alt="Slim Fit Chinos"></a>
									<a href="#"><img src="images/image_2.png" alt="Slim Fit Chinos"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc center">
									<div class="product-title"><h3><a href="#">Slim Fit Chinos</a></h3></div>
									<div class="product-price">$39.99</div>
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
												<span class="star custom-star" title="Five Stars">
													<span class="icon-star-half-full"></span>
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="images/image_1.png" alt="Dark Brown Boots"></a>
									<a href="#"><img src="images/image_2.png" alt="Dark Brown Boots"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc center">
									<div class="product-title"><h3><a href="#">Dark Brown Boots</a></h3></div>
									<div class="product-price">$49</div>
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
												<span class="star custom-star" title="Five Stars">
													<span class="icon-star-half-full"></span>
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="images/image_1.png" alt="Light Blue Denim Dress"></a>
									<a href="#"><img src="images/image_2.png" alt="Light Blue Denim Dress"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc center">
									<div class="product-title"><h3><a href="#">Light Blue Denim Dress</a></h3></div>
									<div class="product-price">$19.95</div>
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
												<span class="star custom-star" title="Five Stars">
													<span class="icon-star-half-full"></span>
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="images/image_1.png" alt="Unisex Sunglasses"></a>
									<a href="#"><img src="images/image_2.png" alt="Unisex Sunglasses"></a>
									<div class="sale-flash badge bg-success p-2">Sale!</div>
									<div class="bg-overlay">
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc center">
									<div class="product-title"><h3><a href="#">Unisex Sunglasses</a></h3></div>
									<div class="product-price"><del>$19.99</del> <ins>$11.99</ins></div>
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
												<span class="star custom-star" title="Five Stars">
													<span class="icon-star-half-full"></span>
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div><!-- .postcontent end -->
	</div>
	@push('scripts')
        <script src="{{ asset('js/star-rating.js') }}"></script>
	@endpush
</x-app-layout>