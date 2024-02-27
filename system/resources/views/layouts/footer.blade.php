<!-- Footer
============================================= -->
<footer id="footer" class="mt-4">
    <div class="container">
        <!-- Footer Widgets
        ============================================= -->
        <div class="footer-widgets-wrap">
            <div class="row col-mb-50">
                <div class="col-lg-8">
                    <div class="row col-mb-50">
                        <div class="col-md-4">
                            <div class="widget clearfix">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo Genway" class="footer-logo">
                                <!-- <abbr title="Sediu"><strong>Sediu:</strong></abbr> Bucuresti  Braila  RM. Valcea -->
                                <abbr class="text-decoration-none"><strong>{{ __('Telefon') }}:</strong></abbr><br>
                                <p class="mb-0">
                                    <a href="tel:{{ $tel_contact }}">{{ $tel_contact }}</a>
                                </p><br>
                                <abbr class="text-decoration-none"><strong>{{ __('Email') }}:</strong></abbr>
                                <p class="mb-4">
                                    @foreach($contact_mails as $mail)
                                        <a href="mailto:{{ $mail }}">{{ $mail }}</a>
                                    @endforeach
                                </p>
                                <abbr class="text-decoration-none color-1"><strong>{{ __('Instalator Validat AFM') }}:</strong></abbr><br>
                                <a href="https://www.genway.ro/info/oferta-casa-verde-2023">
                                    <img src="https://www.old.genway.ro/application/views/admin/js/ckeditor/plugins/imageuploader/uploads/oferta-casa-verdepng.png" alt="" class="mt-2 w-100">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if(count($pagini) > 0)
                                <div class="widget widget_links clearfix">
                                    <h4>{{ __('Informatii utile') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul>
                                                @for($i = 0 ; $i < ceil(count($pagini)/2) ; $i++)
                                                    <li>
                                                        <a href="{{ $pagini[$i]->link_extern ? $pagini[$i]->link_extern : route('page', $pagini[$i]->slug) }}"
                                                            target="{{ $pagini[$i]->link_extern ? '_blank' : '_self' }}"
                                                            >{{ $pagini[$i]->titlu }}
                                                        </a>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul>
                                                @for($i = ceil(count($pagini)/2) ; $i < count($pagini) ; $i++)
                                                    <li>
                                                        <a href="{{ $pagini[$i]->link_extern ? $pagini[$i]->link_extern : route('page', $pagini[$i]->slug) }}"
                                                            target="{{ $pagini[$i]->link_extern ? '_blank' : '_self' }}"
                                                            >{{ $pagini[$i]->titlu }}
                                                        </a>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row col-mb-50">
                        <div class="col-md-4 col-lg-12 pb-4">
                            <div class="widget clearfix" style="margin-bottom: -20px;">
                                <div class="row">
                                    <div class="col-lg-6 bottommargin-sm">
                                        <div class="counter counter-small"><span data-from="50" data-to="{{ $capital_social }}" data-refresh-interval="80" data-speed="3000" data-comma="true"></span></div>
                                        <h5 class="mb-0">{{ __('Capital social') }}</h5>
                                    </div>
                                    <div class="col-lg-6 bottommargin-sm">
                                        <div class="counter counter-small"><span data-from="100" data-to="{{ $clienti }}" data-refresh-interval="50" data-speed="2000" data-comma="true"></span></div>
                                        <h5 class="mb-0">{{ __('Clienti') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 pb-4">
                            <x-newsletter-form />
                            <div class="d-flex justify-content-around">
                                <a href="https://www.facebook.com/electroservicero" class="social-icon si-large si-rounded topmargin-sm si-facebook" target="_blank">
									<i class="icon-facebook"></i>
									<i class="icon-facebook"></i>
								</a>
                                <a href="https://www.youtube.com/channel/UCqsPfHQOoZhzXMw9Q7EQ5fQ" class="social-icon si-large si-rounded topmargin-sm si-youtube" target="_blank">
									<i class="icon-youtube"></i>
									<i class="icon-youtube"></i>
								</a>
                                <a href="https://www.instagram.com/genway.romania/" class="social-icon si-large si-rounded topmargin-sm si-instagram" target="_blank">
									<i class="icon-instagram"></i>
									<i class="icon-instagram"></i>
								</a>
                                <a href="https://www.linkedin.com/company/genway-romania" class="social-icon si-large si-rounded topmargin-sm si-linkedin" target="_blank">
									<i class="icon-linkedin"></i>
									<i class="icon-linkedin"></i>
								</a>
                                <a href="https://www.google.com/maps/dir/44.4512052,26.133894/Genway,+Intrarea+Tarc%C4%83u+3,+Bucure%C8%99ti+031526/" class="social-icon si-large si-rounded topmargin-sm si-gplus" target="_blank">
									<i class="icon-map-marker1"></i>
									<i class="icon-map-marker1"></i>
								</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .footer-widgets-wrap end -->
    </div>
    <!-- Copyrights
    ============================================= -->
    <div id="copyrights">
        <div class="container">
            <div class="row col-mb-30">
                <div class="col-md-6 text-center text-md-start">
                    &copy; {{ date('Y') }} {{ __('Toate Drepturile Rezervate de Genway Romania') }}<br>
                    <div class="copyright-links"><a href="{{ route('page', 'termeni-si-conditii') ?? route('home') }}">{{ __('Termeni si conditii') }}</a> / <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') ?? route('home') }}">{{ __('Politica de confidentialitate') }}</a></div>
                </div>
                {{-- <div class="col-md-6 text-center d-flex justify-content-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end">
                        <a href="#" class="social-icon si-borderless si-facebook">
                            <i class="icon-facebook"></i>
                            <i class="icon-facebook"></i>
                        </a>
                        <a href="#" class="social-icon si-borderless si-twitter">
                            <i class="icon-twitter"></i>
                            <i class="icon-twitter"></i>
                        </a>

                        <a href="#" class="social-icon si-borderless si-instagram">
                            <i class="icon-instagram"></i>
                            <i class="icon-instagram"></i>
                        </a>

                        <a href="#" class="social-icon si-borderless si-yahoo">
                            <i class="icon-yahoo"></i>
                            <i class="icon-yahoo"></i>
                        </a>
                        <a href="#" class="social-icon si-borderless si-linkedin">
                            <i class="icon-linkedin"></i>
                            <i class="icon-linkedin"></i>
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>
    </div><!-- #copyrights end -->
</footer><!-- #footer end -->
