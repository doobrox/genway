{{--<!-- Top Bar
============================================= -->
<div id="top-bar">
    <div class="container clearfix">
        <div class="row justify-content-between">
            <div class="col-12 col-md-auto">
                <!-- Top Social
                ============================================= -->
                <ul id="top-social">
                    <li><a href="#" class="si-facebook"><span class="ts-icon"><i class="icon-facebook"></i></span><span class="ts-text">Facebook</span></a></li>
                    <li><a href="#" class="si-twitter"><span class="ts-icon"><i class="icon-twitter"></i></span><span class="ts-text">Twitter</span></a></li>
                    <li><a href="#" class="si-instagram"><span class="ts-icon"><i class="icon-instagram2"></i></span><span class="ts-text">Instagram</span></a></li>
                </ul><!-- #top-social end -->
            </div>
            <div class="col-12 col-md-auto">
                <!-- Top Links
                ============================================= -->
                <div class="top-links">
                    <ul class="top-links-container f-semibold">
                        @auth
                        <form method="post" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <li class="top-links-item">
                                <a class="fw-semibold" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    this.closest('form').submit();">{{ __('Logout') }}
                                </a>
                            </li>
                        </form>
                        @else
                        <li class="top-links-item"><a class="fw-semibold" href="{{ route('login') }}">Intra in cont</a></li>
                        <li class="top-links-item"><a class="fw-semibold" href="{{ route('login') }}">Cont nou</a></li>
                        @endauth
                    </ul>
                </div><!-- .top-links end -->
            </div>
        </div>
    </div>
</div><!-- #top-bar end --> --}}
<!-- Header
============================================= -->
<style>
    .icon-resize {
        font-size: 20px;
    }
    .color-inherit {
        color: inherit;
    }
    .move-left {
        left: 5%;
    }
</style>
<header id="header" class="header-size-sm" data-sticky-shrink="false">
    <div class="container">
        <div class="header-row">
            <!-- Logo
            ============================================= -->
            <div id="logo" class="my-2 ms-auto ms-lg-0 me-lg-auto">
                <a href="{{ route('home') }}" class="standard-logo"><img src="{{ asset('images/logo.png') }}" alt="Genway Logo" style="height: 60px;"></a>
                <a href="{{ route('home') }}" class="retina-logo"><img src="{{ asset('images/logo.png') }}" alt="Genway Logo" style="height: 60px;"></a>
            </div><!-- #logo end -->
            <div class="header-misc d-none d-lg-flex">
                <ul class="header-extras">
                    <li>
                        <i class="i-plain icon-call m-0"></i>
                        <div class="he-text">
                            Contact
                            <span><a href="tel:{{ $tel_contact }}">{{ $tel_contact }}</a></span>
                        </div>
                    </li>
                    <li>
                        <i class="i-plain icon-line2-envelope m-0"></i>
                        <div class="he-text">
                            Email
                            <span><a href="mailto:{{ $mail_contact }}">{{ $mail_contact }}</a></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="header-wrap">
        <div class="container">
            <div class="header-row justify-content-between flex-row-reverse flex-lg-row justify-content-lg-center">
                <div class="header-misc">
                    <x-cart-layout />

                    <!-- Auth Dropdown
                    ============================================= -->
                    <div id="auth-dropdown" class="dropdown mx-3 me-lg-0 header-misc-icon d-sm-block">
                        <a href="javascript:void(0)" id="auth-meniu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="icon-user"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end auth-content sub-menu-container p-0" aria-labelledby="auth-meniu">
                            @auth
                                <li class="menu-item">
                                    <a class="dropdown-item text-start menu-link" href="{{ route('profile') }}">Profil</a>
                                </li>
                                <li class="menu-item">
                                    <a class="dropdown-item text-start menu-link" href="{{ route('profile.password') }}">Schimba parola</a>
                                </li>
                                <li class="menu-item">
                                    <a class="dropdown-item text-start menu-link" href="{{ route('profile.orders') }}">Istoric comenzi</a>
                                </li>
                                <div class="dropdown-divider d-none d-lg-block"></div>
                                <li class="menu-item">
                                    <form method="post" action="{{ route('logout') }}" class="m-0 menu-link">
                                        @csrf
                                        <a class="text-start"
                                            onclick="event.preventDefault();
                                            this.closest('form').submit();">{{ __('Logout') }} <i class="icon-signout"></i></a>
                                    </form>
                                </li>
                            @else
                                <li class="menu-item">
                                    <a class="dropdown-item text-start menu-link" href="{{ route('login') }}">Intra in cont <i class="icon-signin"></i></a>
                                </li>
                                <div class="dropdown-divider d-none d-lg-block"></div>
                                <li class="menu-item">
                                    <a class="dropdown-item text-start menu-link" href="{{ route('login') }}">Cont nou <i class="icon-plus-sign"></i></a>
                                </li>
                            @endauth
                        </ul>
                    </div>

                    {{-- <!-- Top Search
                    ============================================= -->
                    <div id="top-search" class="header-misc-icon">
                        <a href="#" id="top-search-trigger"><i class="icon-line-search"></i><i class="icon-line-cross"></i></a>
                    </div><!-- #top-search end --> --}}
                </div>
                <a href="tel:021.627.00.34" class="d-lg-none d-flex align-items-center color-inherit position-relative move-left"><i class="i-plain icon-call m-0 icon-resize"></i><span>{{ $tel_contact }}</span></a>

                <div id="primary-menu-trigger">
                    <svg class="svg-trigger" viewBox="0 0 100 100">
                        <path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path>
                        <path d="m 30,50 h 40"></path>
                        <path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path>
                    </svg>
                </div>
                <!-- Primary Navigation
                ============================================= -->
                <nav class="primary-menu with-arrows">
                    <ul class="menu-container">
                        @foreach($meniu as $sectiune)
                            <li class="menu-item {{ url()->current() == $sectiune->link ? 'current' : '' }}"><a class="menu-link" href="{{ $sectiune->link }}"><div>{{ $sectiune->nume }}</div></a>
                                @if(count($pagini = $sectiune->pagini) > 0 && $sectiune->id != 12)
                                    <ul class="sub-menu-container">
                                        @foreach($pagini as $pagina)
                                            <li class="menu-item">
                                                <a class="menu-link" target="{{ $pagina->link_extern ? '_blank' : '_self' }}" href="{{ $pagina->link_extern ? $pagina->link_extern : route('page', $pagina->slug) }}">
                                                    <div>{{ $pagina->titlu }}</div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="header-wrap-clone"></div>
</header><!-- #header end -->
