<!DOCTYPE html>
<html>
    <head>
        <title>Monofilara {{ $putere_invertor ?? '' }}kW {{ $tipul_bransamentului }} </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            *, html, body {
                /*font-family: "Times New Roman";
                 font-family: Arial, sans-serif;*/
                font-size: 12px;
                /*font-family: 'Open Sans', sans-serif, Arial, Helvetica, Sans-Serif;*/
                font-family: "DejaVu Sans", sans-serif;
            }
            @page {
                margin: 0cm 0cm;
            }
            span {
                color: #404040;
            }
            .normal {
                font-size: 9px;
            }
            .small {
                font-size: 6px;
            }
            .extra-small {
                font-size: 4.75px;
            }
            .text-center {
                text-align: center;
            }
            .full {
                width:100%;
                height:100%;
            }
            .max-full {
                max-width:100%;
            }
            .fixed {
                position: fixed;
            }
            .relative {
                position: relative;
            }
            .absolute {
                position: relative;
            }
        </style>
    </head>
    <body class="relative max-full">
        <img class="fixed full" src="{{ $base64_img }}">
        @if($putere_invertor >= 10)
            <span class="fixed normal" style="top: 20.5%; left: 4.5%;">{{ ceil($numar_panouri/2) }} panouri</span>
            <span class="fixed normal" style="top: 20.5%; left: 13%;">{{ $putere_panouri }}</span>
        @endif
        @if($tipul_bransamentului == 'monofazat')
            <span class="absolute normal" style="top: 37.9%; left: 5.6%;">{{ $numar_panouri }} panouri</span>
            <span class="absolute normal" style="top: 39.3%; left: 2.5%;">{{ $putere_panouri }}</span>
        @else
            <span class="absolute normal" style="top: 40%; left: 4.5%;">{{ $putere_invertor >= 10 ? floor($numar_panouri/2) : $numar_panouri }} panouri</span>
            <span class="absolute normal" style="top: 40%; left: 8%;">{{ $putere_panouri }}</span>
        @endif
        <span class="fixed small" style="top: 32%; left: {{ $marca_invertor == 'Huawei' ? 49 : 48.5 }}%;">{{ $marca_invertor }}</span>
        <span class="fixed small" style="top: 33%; left: 49.5%;">{{ $putere_invertor }}kW</span>
        @if($tipul_bransamentului == 'monofazat')
            <span class="fixed extra-small" style="top: 16.05%; left: 68.2%;">{{ $capacitate_disjunctor }}</span>
            <span class="fixed extra-small" style="top: 16.7%; left: 91%;">{{ $capacitate_disjunctor }}</span>
            <span class="fixed extra-small" style="top: 38.35%; left: 96.9%;">{{ $capacitate_disjunctor }}</span>
        @else
            <span class="fixed extra-small" style="top: 16.1%; left: 67.8%;">{{ $capacitate_disjunctor }}</span>
            <span class="fixed extra-small" style="top: 17.1%; left: 90.8%;">{{ $capacitate_disjunctor }}</span>
            <span class="fixed extra-small" style="top: 38.1%; left: 96.8%;">{{ $capacitate_disjunctor }}</span>
        @endif
        <span class="fixed normal" style="top: 77.7%; left: 78%;">{{ $nume }} {{ $prenume }}</span>
        <span class="fixed normal" style="top: 83.5%; left: 78.5%;">{{ $nume_judet_imobil }}, {{ $nume_localitate_imobil }}, {{ $strada_imobil }}, {{ $numar_imobil }},</span>
        <span class="fixed normal" style="top: 84.75%; left: 77.15%;">
            {{ $bloc_imobil ? $bloc_imobil : '' }} {{ $sc_imobil ? $sc_imobil.',' : '' }}, {{ $et_imobil ? $et_imobil.',' : '' }} {{ $ap_imobil ? $ap_imobil : '' }}
        </span>
        <span class="fixed normal" style="top: 78%; left: 55.5%;">
            {{ $fi ? $fi->nume_firma : '' }}
        </span>
        <span class="fixed small" style="top: 94%; left: 69%;">
            {{ date('d/m/Y') }}
        </span>
    </body>
</html>