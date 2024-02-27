<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="Zaomedia" />
        <meta name="keywords" content="{{ $meta_keywords }}" />
        <meta name="description" content="{{ $meta_description }}" />
        <meta property="og:title" content="{{ $page_title }}">
        <meta property="og:description" content="{{ $meta_description }}">
        <meta property="og:image" content="{{ asset('images/og_image.png') }}">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="theme-color" content="#ffffff">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        @isset($canonical)
            <link rel="canonical" href="{{ $canonical }}" />
        @endisset
        <title>{{ $page_title }}</title>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5R9MKZQ');</script>
        <!-- End Google Tag Manager -->

        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
              {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
              n.callMethod.apply(n,arguments):n.queue.push(arguments)};
              if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
              n.queue=[];t=b.createElement(e);t.async=!0;
              t.src=v;s=b.getElementsByTagName(e)[0];
              s.parentNode.insertBefore(t,s)}(window, document,'script',
              'https://connect.facebook.net/en_US/fbevents.js');
              fbq('init', '987350926045245');
              fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
          src="https://www.facebook.com/tr?id=987350926045245&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->

        <!-- Stylesheets ============================================= -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&amp;display=swap" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/swiper.css') }}" type="text/css" />
        <!-- Construction Demo Specific Stylesheet -->
        <link rel="stylesheet" href="{{ asset('css/construction.css') }}" type="text/css" />
        <!-- / -->
        <link rel="stylesheet" href="{{ asset('css/dark.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/font-icons.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css" />
        {{-- <link rel="stylesheet" href="{{ asset('css/fonts.css') }}" type="text/css" /> --}}
        <link rel="stylesheet" href="{{ asset('css/utility.css?v=20231123') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/colors.php?color=0087cb') }}" type="text/css" />
        @stack('styles')
        <!-- Scripts -->

       {{--  <meta name="facebook-domain-verification" content="h04lzs7z1kcsr4294y2xe0jsea67ht" />

        <!-- Global site tag (gtag.js) - Google Ads: 1045099197 -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-1045099197"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'AW-1045099197');
        </script> --}}
        {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    </head>
    <body class="stretched">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5R9MKZQ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <!-- Document Wrapper
        ============================================= -->
        <div id="wrapper" class="clearfix">
            <x-navigation-layout></x-navigation-layout>
            <x-section>
                {{ $slot }}
            </x-section>
            <x-footer-layout></x-footer-layout>
        </div>
        <!-- Go To Top
        ============================================= -->
        <div id="gotoTop" class="icon-angle-up"></div>
        <!-- Whatsapp button
        ============================================= -->
        <div id="whatsapp">
            <a href="https://wa.me/+40774490150" target="_blank"><i class="icon-whatsapp"></i></a>
        </div>
        <!-- JavaScripts
        ============================================= -->
        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('js/plugins.min.js') }}"></script>
        <!-- Footer Scripts
        ============================================= -->
        <script src="{{ asset('js/functions.js') }}"></script>
        <!-- Utility Footer Scripts
        ============================================= -->
        @stack('scripts')
        <script src="{{ asset('js/utility.js') }}"></script>
    </body>
</html>
