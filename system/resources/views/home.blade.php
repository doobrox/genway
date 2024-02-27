<x-app-layout>
    @push('styles')
    @endpush

    @push('scripts')
        <!-- Masonry -->
        <script src="{{ asset('js/masonry.js') }}"></script>
    @endpush

    <div class="row gutter-10 col-mb-80">
        <x-sidebar-layout />
        <!-- Post Content
        ============================================= -->
        <div class="postcontent col-lg-10 order-lg-last">
            <!-- Content
            ============================================= -->
            <section id="content">
                <div class="content-wrap custom-content-wrap">
                    <div class="container">
                        <!-- Top Search
                        ============================================= -->
                        <div class="margin-low header-misc order-last m-0 my-4 my-lg-0 flex-grow-1 flex-lg-grow-0">
                            <form action="{{ route('products') }}" method="get" class="w-100">
                                <div class="input-group">
                                    <input type="text" name="search[c]" class="form-control" placeholder="Cauta un produs">
                                    <button class="input-group-text"><i class="icon-line-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <!-- #top-search end -->

                        <!-- Carousel
                        ============================================= -->
                        <div id="carouselExampleCaptions" class="carousel slide mb-4" data-bs-ride="carousel" data-interval="true">
                            {{-- <div class="carousel-indicators">
                                @foreach($banners as $index => $banner)
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}" class="{{ $index == '0' ? 'active' : '' }}" aria-current="true" aria-label="{{ $banner->titlu }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($banners as $index => $banner)
                                    <div class="carousel-item {{ $index == '0' ? 'active' : '' }}">
                                        <a href="{{ $banner->link }}" target="{{ $banner->target }}"><img class="d-block w-100" src="{{ route('images', 'bannere/'.$banner->imagine) }}" alt="{{ $banner->titlu }}" /></a>
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a> --}}
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/aBAy-R8tsJA" title="Sistem fotovoltaic plutitor" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <!-- #Carousel end -->

                        <!-- Sigle -->
                        <div class="shop row mt-3 custom-sigla-container gutter-10 px-0">
                            @foreach($categorii as $categorie)
                                <x-category class="col-xl-3 col-md-4 col-sm-6 col-12"
                                    :image="'categorii/'.$categorie->imagine"
                                    :title="$categorie->nume"
                                    :url="route('category', $categorie->slug)"
                                    :subcategories="$categorie->subcategorii"
                                    />
                            @endforeach
                        </div>
                        <!-- Sigle end -->

                        <!-- Produse Noi
                        ============================================= -->
                        <div class="row align-items-center flex-center-category-name">
                            <div class="col-auto">
                                <h3 class="fw-medium fs-2 mt-5 mb-2"><span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>{{ __('Produse Noi') }}</span></span></h3>
                            </div>
                        </div>

                        <div class="owl-carousel product-carousel carousel-widget py-3" data-margin="30" data-pagi="false" data-items-xs="1" data-items-md="2" data-items-lg="3" data-items-xl="3">
                            @foreach($produseNoi as $produs)
                                <x-new-product
                                    :image="'produse/'.$produs->imagine"
                                    :title="$produs->nume"
                                    :url="$produs->route"
                                    :discount="$produs->discount"
                                    :price="$produs->pret_cu_tva"
                                    :fullPrice="$produs->pret_intreg_cu_tva"
                                    :tva_inclus="true"
                                    :rating="$produs->rating"
                                    :cod_ean13="$produs->cod_ean13"
                                     />

                            @endforeach
                        </div>
                        <!-- Produse Noi end
                        ============================================= -->

                        @if(isset($produseRecomandate) && $produseRecomandate != null)
                            <div class="row align-items-center flex-center-category-name">
                                <div class="col-auto">
                                    <h3 class="fw-medium fs-2 mt-4 mb-0">{{ __('Produse') }} <span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>{{ __('Recomandate') }}</span></span></h3>
                                </div>
                            </div>

                            <!-- Shop
                            ============================================= -->
                            <div class="shop row gutter-10 col-mb-10 mt-3">
                                @foreach($produseRecomandate as $produs)
                                    <x-product class="col-xl-3 col-md-4 col-sm-6 col-12"
                                        :product="$produs"/>
                                @endforeach
                            </div><!-- #shop end -->
                        @endif

                        <!-- Descriere
                        ============================================= -->
                        <div>
                            <div class="col-auto mb-4 custom-pagination-margin">
                                <h1 class="fw-medium fs-1 h1"><span data-animate="svg-underline-animated" class="svg-underline nocolor"><span>Genway</span></span> Romania - Interfoane, videointerfoane, sisteme de supraveghere video</h1>
                            </div>
                            <div class="custom-read-more" data-readmore="true" data-readmore-trigger-open="Citeste mai mult <i class='icon-angle-down'></i>" data-readmore-trigger-close="Vezi mai putin <i class='icon-angle-up'></i>">
                                <p>Înființată în anul 1991, în Ramnicu Valcea, cu activitatea principală de reparații produse electronice, SC Electro-Service SRL și-a construit și promovat imaginea unei firme deschizătoare de drum. În acest sens, inovarea a fost calea alesă, fapt care trebuie privit de noi toţi ca o provocare. Inovarea este și va fi prezentă în toate domeniile şi toate activităţile pe care le desfăşurăm: tehnologie, produse, procese de muncă, metode de lucru, relaţii interpersonale, comunicare, atitudini, abordarea clienţilor.
                                <br><br>
                                Eficienţa înseamnă să aduci profit pentru angajații și clienţii tai, iar rezultatele ca valoare, se traduc în stabilirea de priorităţi şi luarea unor decizii corecte.
                                <br><br>
                                De accea în anul 2001 ne-am extins activiatatea în domeniul serviciilor de execuție instalații electrice , termice, sanitare și curenți slabi. Aprecierea clienților reprezintă cartea noastră de vizită, iar pentru a răspunde prompt tuturor solicitărilor, în anul 2004 am inaugurat primul punct de lucru în București. Din dorința de extindere, în anul 2005 am derulat primele activități de import și distribuție națională pentru sistemele de interfonie Genway. Calitatea superioară a produselor, în comparație cu cele autohtone, a dus la crearea unei rețele naționale de distribuție într-o continuă ascensiune.
                                <br><br>
                                Nu ne-am oprit aici, în următorii ani ne-am extins punctele de lucru astfel încât din anul 2008 suntem prezenți cu doua puncte de lucru în Râmnicu Vâlcea, trei puncte de lucru în București și unul în Brăila. Să satisfacem cele mai pretențioase cerințe ale clienților este primordial pentru noi, motiv pentru care am ales produse și sisteme care dețin cele mai înalte standarde de calitate și siguranță. Astfel, în anul 2008, am devenit unic importator și distribuitor al sistemelor de interfonie și videointerfonie ABB Genway, membră a grupului ABB, prezent la nivel mondial. Produsele ABB Genway se pot utiliza atat pentru aplicațiiile comerciale cât și casnice În urmatorii ani ne-am îndreptat atenția către extinderea portofoliului de produse comercializate și crearea de noi spații pentru depozitare. Astfel din anul 2014 suntem prezenți cu un spațiu total de prezentare a produselor în suprafață de 650mp, un spațiu de depozitare în suprafață de 3500mp și o gama completă de sisteme de securitate printre care putem enumera:
                                </p>
                                <ul class="iconlist">
                                    <li><i class="icon-ok-sign"></i> sisteme de interfonie audio video ABB Genway</li>
                                    <li><i class="icon-ok-sign"></i> sisteme de detecție la efracție Satel</li>
                                    <li><i class="icon-ok-sign"></i> echipamente pentru supraveghere video analogice si digitale Avtech, Gnv</li>
                                    <li><i class="icon-ok-sign"></i> echipamente de control acces și încuietori electrice Headen</li>
                                    <li><i class="icon-ok-sign"></i> automatizari pentru porți Powertech</li>
                                    <li><i class="icon-ok-sign"></i> instalații de iluminat</li>
                                </ul>
                                <a href="#" class="btn btn-link text-primary read-more-trigger read-more-trigger-right"></a>
                            </div>
                        </div>
                        <!-- #Descriere end --->
                    </div>
                </div>
            </section><!-- #content end -->

        </div><!-- .postcontent end -->
    </div>
</x-app-layout>
