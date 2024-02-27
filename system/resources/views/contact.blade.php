<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/bs-rating.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/ion.rangeslider.css') }}" type="text/css" />
    @endpush

    @push('scripts')
        <!-- Price range -->
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
    <div class="row gutter-10 col-mb-30 flex-col-reverse-mobile">
        <x-sidebar-layout />
        <!-- Post Content
        ============================================= -->
        <div class="postcontent col-lg-10 order-lg-last">
            <!-- Contact Info
            ============================================= -->
            <div class="row col-mb-50 mb-2">
                <div class="d-flex justify-content-center">
                    <img class="mt-4 mb-4 contact-main-img" src="{{ asset('images/imagine-casa-genway.jpg') }}" alt="">
                </div>
                <h3 class="text-center"><span data-animate="svg-underline-animated" class="svg-underline nocolor"><span class="mb-2">Program De Lucru</span></span></h3>
                <div class="col-md-4">
                    <div class="feature-box fbox-center fbox-bg fbox-plain ps-1 pe-1 pb-2 custom-card-h-program h-border-primary">
                        <div class="fbox-icon mb-0">
                            <a href="#"><i class="icon-clock2"></i></a>
                        </div>
                        <div class="fbox-content">
                            <h3 class="mb-4 mt-3">BUCURESTI</h3>
                            <div>
                                <div class="custom-card-text">
                                    <p class="mt-2 mb-2"><strong>Luni-Vineri: 9:00-17:00</strong></p>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2"><strong>1.Mihai Bravu</strong><br></p>
                                    <address class="mt-2 mb-2">Adresa : Intrarea Tarcau nr. 3, Sector 3</address>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2"><strong>2.Giulesti</strong></p>
                                    <address class="mt-2 mb-2">Adresa : Str. 9 Mai nr. 45A, Sector 6</address>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2">Consultanta / vanzare / instalare / tehnic :<br><strong>021.627.00.34</strong><br><strong>0726/785.385</strong></p>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2">Proiecte, contracte, comenzi speciale : <br><strong>0724/27.38.62</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box fbox-center fbox-bg fbox-plain ps-1 pe-1 pb-2 custom-card-h-program h-border-primary">
                        <div class="fbox-icon mb-0">
                            <a href="#"><i class="icon-clock2"></i></a>
                        </div>
                        <div class="fbox-content">
                            <h3 class="mb-4 mt-3">RM. VALCEA</h3>
                            <div>
                                <div class="custom-card-text">
                                    <p class="mb-2 mt-2"><strong>Luni-Vineri: 8:00-19:00<br></strong></p>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mb-2 mt-2"><strong>1.Sediu</strong></p>
                                    <address class="mb-2 mt-0">Adresa : Str. G-ral Magheru Bl. V6 PARTER</address>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mb-2 mt-2"><strong>2.Punct de lucru</strong></p>
                                    <address class="mb-2 mt-2">Str. Matei Basarab Bl. 132 Sc. B PARTER</address>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mb-2 mt-2">Consultanta / vanzare / instalare / tehnic :<br><strong>0250/73.87.46</strong><br><strong>0724/273.523</strong></p>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mb-2 mt-2">Proiecte, contracte, comenzi speciale : <br><strong>0724/27.38.62</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box fbox-center fbox-bg fbox-plain ps-1 pe-1 pb-2 custom-card-h-program h-border-primary">
                        <div class="fbox-icon mb-0">
                            <a href="#"><i class="icon-clock2"></i></a>
                        </div>
                        <div class="fbox-content">
                            <h3 class="mb-4 mt-3">BRAILA</h3>
                            <div>
                                <div class="custom-card-text">
                                    <p class="mt-2 mb-2"><strong>Luni-Vineri: 9:00-17:00</strong></p>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2"><strong>1.Adresa</strong><br></p>
                                    <address class="mt-2 mb-2">Adresa : Str. Dorobanti Bl. B14 PARTER</address>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2">Consultanta / instalare :<br><strong>0239/69.33.50</strong><br><strong>0724/273.608</strong></p>
                                    <span class="flex flex-center"><div class="line-card-program-lucru"></div></span>
                                    <p class="mt-2 mb-2">Proiecte, contracte, comenzi speciale : <br><strong>0724/27.38.62</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="text-center"><span data-animate="svg-underline-animated" class="svg-underline nocolor"><span class="mb-2">Persoanele de contact pentru programul Casa Verde:</span></span></h3>
                <div class="mb-4">
                    <p class="mb-2 mt-2">1. Regiunea București-Ilfov. Persoana de contact: Chiroabă Gabriel, mobil: 0786.719.400, email: <a href="mailto:gabriel.chiroaba@genway.ro" class="text-decoration-underline">gabriel.chiroaba@genway.ro</a>;</p>
                    <p class="mb-2 mt-2">2. Regiunea Sud-Muntenia - județele Argeș, Călărași, Dâmbovița, Giurgiu, Ialomița, Prahova și Teleorman. Persoana de contact: Radu Paun, mobil: 0744.671.170, email: <a href="mailto:radu.paun@genway.ro" class="text-decoration-underline">radu.paun@genway.ro</a>;</p>
                    <p class="mb-2 mt-2">3. Regiunea Sud-Vest - județele Dolj, Gorj, Mehedinţi, Olt și Vâlcea. Persoana de contact: Popa Magdalena, mobil: 0746.888.838, email: <a href="mailto:magdalena.popa@genway.ro" class="text-decoration-underline">magdalena.popa@genway.ro</a>;</p>
                    <p class="mb-2 mt-2">4.Regiunea Sud- Est - județele Brăila, Buzău, Constanța, Galați, Vrancea și Tulcea. Persoana de contact Dănăila Alexandra, mobil: 0742.014.954, emal: <a href="mailto:alexandra.danaila@genway.ro" class="text-decoration-underline">alexandra.danaila@genway.ro</a>;</p>
                    <p class="mb-2 mt-2">5. Regiunea Nord Est - județele Bacău, Botoșani, Iași, Neamț, Suceava și Vaslui. Persoana de contact: Mihaela Dumitriu, mobil: 0742.014.956, email: <a href="mailto:mihaela.dumitriu@genway.ro;" class="text-decoration-underline">mihaela.dumitriu@genway.ro;</a>;</p>
                    <p class="mb-2 mt-2">6. Regiunea Vest - județele Arad, Caraș-Severin, Hunedoara și Timiș. Persoana de contact: Andreea Lungu, mobil: 0770.355.149, email: <a href="mailto:andreea.lungu@genway.ro" class="text-decoration-underline">andreea.lungu@genway.ro</a>;</p>
                    <p class="mb-2 mt-2">7. Regiunea Nord Vest - județele Bihor, Bistrița-Năsăud, Cluj, Sălaj, Satu Mare și Maramureș. Persoana de contact: Mihaela Dumitriu, mobil: 0742.014.956, email: <a href="mailto:mihaela.dumitriu@genway.ro" class="text-decoration-underline">mihaela.dumitriu@genway.ro</a>;</p>
                    <p class="mb-2 mt-2">8. Regiunea Centru - județele Alba, Brașov, Covasna, Harghita, Mureș și Sibiu. Persoana de contact: Mihai Radu, mobil: 0786.173.341, email: <a href="mailto:mihai.radu@genway.ro" class="text-decoration-underline">mihai.radu@genway.ro</a>;</p>

                </div>
                <div class="col-md-6">
                    <div class="feature-box fbox-center fbox-bg fbox-plain ps-1 pe-1 pb-2 custom-h-23rem h-border-primary">
                        <div class="fbox-icon">
                            <a href="#"><i class="icon-map-marker2"></i></a>
                        </div>
                        <div class="fbox-content">
                            <h3 class="mb-2">{{ $nume_firma }}</h3>
                            <div class="text-center custom-card-text">
                                <address>
                                    <p class="custom-card-text">Intrarea Tarcau<br>Numarul 3<br>Sector 3<br>Bucuresti<br></p>
                                    <strong>Cod Fiscal:</strong> {{ $cod_fiscal }}<br>
                                    <strong>Capital social: </strong> {{ $capital_social }}<br>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-box fbox-center fbox-bg fbox-plain custom-h-23rem h-border-primary">
                        <div class="fbox-icon">
                            <a href="#"><i class="icon-email2"></i></a>
                        </div>
                        <div class="fbox-content">
                            <h3 class="mb-2">Telefon</h3>
                            <div class="text-center">
                                {{ $tel_contact }}<br>{{ $tel2_contact }}<br>
                                <strong>Email:<br></strong>
                                @foreach($contact_mails as $mail)
                                    <a href="mailto:{{ $mail }}">{{ $mail }}</a><br>
                                @endforeach
                                <strong>Site web:<br></strong><a href="http://www.genway.ro">www.genway.ro</a><br>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- Contact Info End -->

            <div class="row align-items-stretch col-mb-50 mb-0">
                <!-- Contact Form
                ============================================= -->
                <div class="col-lg-6">
                    <div class="fancy-title title-border">
                        <h3><span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>Trimite-ne</span></span> un mesaj</h3>
                    </div>

                    <div>
                        <div class="form-result">
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4" :errors="$errors" :bag="'login'" />
                        </div>
                        <form class="mb-0" action="{{ route('contact.send') }}" method="post">
                            <div class="form-process">
                                <div class="css3-spinner">
                                    <div class="css3-spinner-scaler"></div>
                                </div>
                            </div>
                            <div class="row">
                                @csrf
                                <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6 form-group">
                                    <label for="contactform-name">Nume <small>*</small></label>
                                    <input type="text" id="contactform-name" name="nume" value="" class="sm-form-control custom-padding-form-control required" />
                                </div>
                                <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6 form-group">
                                    <label for="contactform-email">Email <small>*</small></label>
                                    <input type="email" id="contactform-email" name="email" value="" class="required email sm-form-control custom-padding-form-control" />
                                </div>
                                <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6 form-group">
                                    <label for="contactform-phone">Telefon</label>
                                    <input type="text" id="contactform-phone" name="telefon" value="" class="sm-form-control custom-padding-form-control" />
                                </div>
                                <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6 form-group">
                                    <label for="contactform-subject">Subiect <small>*</small></label>
                                    <input type="text" id="contactform-subject" name="subiect" value="" class="required sm-form-control custom-padding-form-control" />
                                </div>
                                <div class="w-100"></div>
                                <div class="col-12 form-group">
                                    <label for="contactform-message">Mesaj <small>*</small></label>
                                    <textarea class="required sm-form-control" id="contactform-message" name="mesaj" rows="6" cols="30" placeholder="{{ __('Maxim 1000 de caractere') }}"></textarea>
                                </div>

                                <div class="col-12 form-group d-none">
                                    <input type="text" id="contactform-botcheck" name="botcheck" class="sm-form-control" />
                                </div>

                                <div class="col-12 form-group">
                                    <input id="checkbox-22" class="checkbox-style" name="termenii_de_prelucrare" type="checkbox" required>
                                    <label for="checkbox-22" class="checkbox-style-3-label custom-checkbox-style-3-label ms-0 custom-personal-policy">
                                        <span>{!! __('Sunt de acord cu <a href=":href">prelucrarea datelor cu caracter personal</a>', [
                                            'href' => route('page', 'termeni-si-conditii'),
                                        ]) !!}</span>
                                    </label>
                                </div>

                                <div class="col-12 form-group">
                                    <button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d m-0">{{ __('Trimite') }}</button>
                                </div>
                            </div>
                            <input type="hidden" name="prefix" value="contactform-">
                        </form>
                    </div>
                </div><!-- Contact Form End -->
                <!-- Google Map
                ============================================= -->
                <div class="col-lg-6 min-vh-60 flex-center align-items-center">
                    <iframe class="border-0 custom-carousel-shadow pb-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2850.064857758004!2d26.120169215741075!3d44.41131481109443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b1fefa4c42ced1%3A0x2d6e3a02b27bd75b!2sIntrarea%20Tarc%C4%83u%203%2C%20Bucure%C8%99ti!5e0!3m2!1sen!2sro!4v1658137830920!5m2!1sen!2sro&language=ro" height="550" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div><!-- Google Map End -->
            </div>

        </div><!-- .postcontent end -->
    </div>
    
</x-app-layout>