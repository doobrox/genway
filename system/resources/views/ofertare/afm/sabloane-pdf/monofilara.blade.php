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
                margin: 0.5cm 1cm;
            }
            span {
                color: #404040;
            }
            table {
                border-collapse: collapse;
            }
            table td, table th {
                font-size: 9px;
                border: 0.5px solid rgba(0, 0, 0, 0.5);
            }
            .border {
                position: fixed;
                width: 100%;
                height: 93%;
                border: 1px solid rgba(0, 0, 0, 0.8); 
                margin: 0.67cm 0 0 0;
            }
            .normal {
                font-size: 9px;
            }
            .small-normal {
                font-size: 7.5px;
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
            .bg-white {
                background-color: white;
            }
            .text-componente {
                font-size: 8px;
                background-color: white;
                line-height: 7px;
            }
            .text-componente-medium {
                font-size: 7px;
                background-color: white;
                line-height: 7px;
            }
            .text-componente-coeficient {
                font-size: 5px;
                line-height: 7px;
            }
            .text-componente-small {
                font-size: 6px;
                line-height: 7px;
                background-color: white;
            }
            .text-componente-small-2 {
                font-size: 6px;
                line-height: 5px;
                background-color: white;
            }
        </style>
    </head>
    <body class="relative max-full">
        <img class="fixed full" src="{{ $base64_img }}">
        <div class="border"></div>
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
        <span class="fixed text-componente" style="top: 32%; left: {{ $marca_invertor == 'Huawei' ? 48.5 : 48 }}%;">{{ $marca_invertor }}</span>
        <span class="fixed text-componente" style="top: 33%; left: 49.5%;">{{ $putere_invertor }}kW</span>
        <span class="fixed normal" style="top: 77.7%; left: 78%;">{{ $nume }} {{ $prenume }}</span>
        <span class="fixed normal" style="top: 83.5%; left: 78.5%;">{{ $nume_judet_imobil }}, {{ $nume_localitate_imobil }}, {{ $strada_imobil }}, {{ $numar_imobil }},</span>
        <span class="fixed normal" style="top: 84.75%; left: 77.15%;">
            {{ $bloc_imobil ? $bloc_imobil : '' }} {{ $sc_imobil ? $sc_imobil.',' : '' }}, {{ $et_imobil ? $et_imobil.',' : '' }} {{ $ap_imobil ? $ap_imobil : '' }}
        </span>
        
        <img src="{{ $base64_stamp_img }}" width="125px" class="fixed" style="top: 76.25%; left: 27%;">
        <img src="{{ $base64_logo_img }}" width="100px" class="fixed" style="top: 76.5%; left: 42.5%;">
        
        <span class="fixed normal" style="top: 78%; left: {{ $fi ? (strlen($fi->nume_firma) > 35 ? 62 : 63) - (strlen($fi->nume_firma)/2 * 0.4) : '63' }}%;">
            {{ $fi ? $fi->nume_firma : '' }}
        </span>
        <span class="fixed normal bg-white" style="top: 88.25%; left: 54.4%; padding-right: 10px;">
            Popa Alexandru
        </span>
        <span class="fixed normal bg-white" style="top: 93%; left: 54.4%; padding-right: 10px;">
            Popa Alexandru
        </span>
        <span class="fixed small-normal" style="top: 94%; left: 68.5%;">
            {{ date('d/m/Y') }}
        </span>
        <span class="fixed text-componente" style="top: 27%; left: 19.2%;">Cablu 4mm<sup class="small">2</sup></span>
        <span class="fixed text-componente" style="top: 28.5%; left: 19.1%;">RV-K 026/1kV</span>
        <span class="fixed text-componente" style="top: 26.6%; left: 27.2%;">F15A</span>
        <span class="fixed text-componente text-center" style="top: 27.7%; left: 31.3%;">SPD40kA<br>1000VDC</span>
        <span class="fixed text-componente text-center" style="top: 25.8%; left: 35%;">{{ $setari['siguranta'] }}A<br>1000VDC</span>
        <span class="fixed text-componente" style="top: 26.6%; left: 27.2%;">F15A</span>

        <span class="fixed text-componente-medium" style="top: 27%; left: 39.5%;">Cablu 4mm<sup class="small">2</sup></span>
        <span class="fixed text-componente-medium" style="top: 28.5%; left: 39.4%;">RV-K 026/1kV</span>
        @if($putere_invertor >= 10)
            <span class="fixed text-componente" style="top: 7%; left: 19.2%;">Cablu 4mm<sup class="small">2</sup></span>
            <span class="fixed text-componente" style="top: 8.5%; left: 19.1%;">RV-K 026/1kV</span>
            <span class="fixed text-componente" style="top: 6.6%; left: 27.2%;">F15A</span>
            <span class="fixed text-componente text-center" style="top: 7.7%; left: 31.3%;">SPD40kA<br>1000VDC</span>
            <span class="fixed text-componente text-center" style="top: 5.8%; left: 35%;">{{ $setari['siguranta'] }}A<br>1000VDC</span>   

            <span class="fixed text-componente-medium" style="top: 7%; left: 39.5%;">Cablu 4mm<sup class="small">2</sup></span>
            <span class="fixed text-componente-medium" style="top: 8.5%; left: 39.4%;">RV-K 026/1kV</span> 
        @endif
        
        <span class="fixed text-componente" style="top: 30.6%; left: 47.6%;">Invertor AC/DC</span>
        @if($tipul_bransamentului == 'monofazat')
            <span class="fixed text-componente" style="top: 19.1%; left: 56.5%;">Cablu MYYM 3 x {{ $setari['sectiune_cablu'] }}mm<sup class="small">2</sup></span>
            <span class="fixed text-componente text-center" style="top: 16.2%; left: 66.5%;">PdeC 6kA <br>Curba {{ $tip_curba }}</span>
            <span class="fixed text-componente text-center" style="top: 15.3%; left: 70.5%;">{{ $setari['siguranta'] }}A 2P</span>
            <span class="fixed text-componente-small text-center" style="top: 18%; left: 77.2%;">Cablu MYYM<br>3 x {{ $setari['sectiune_cablu'] }}mm<sup class="extra-small">2</sup></span>
            <span class="fixed text-componente-small-2 text-center" style="top: 15.8%; left: 81.4%;">{{ $val_inferioara_siguranta }}A 2P<br>PdeC 6kA<br>Curba {{ $tip_curba }}</span>
            <span class="fixed text-componente-small text-center" style="top: 22.6%; left: 85.2%;">Smart Meter<br>Fronius TS 100A-1</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 17.4%; left: 88.7%;">PdeC 6kA<br>Curba {{ $tip_curba }}</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 16.9%; left: 91.5%;">{{ $val_inferioara_siguranta }}A 2P</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 39.3%; left: 94.5%;">PdeC 6kA<br>Curba C</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 38.5%; left: 97%;">{{ $capacitate_disjunctor }}A 2P</span>

        @else
            <span class="fixed text-componente-medium" style="top: 19.1%; left: 56.7%;">Cablu MYYM 5 x {{ $setari['sectiune_cablu'] }}mm</span>
            <span class="fixed text-componente-coeficient" style="top: 18.6%; left: 64%;">2</span>
            <span class="fixed text-componente" style="top: 7.4%; left: 64.3%;">Cablu UTP 5e</span>
            <span class="fixed text-componente text-center" style="top: 16.3%; left: 65.9%;">PdeC 4.5kA <br>Curba {{ $tip_curba }}</span>
            <span class="fixed text-componente text-center" style="top: 15.3%; left: 70%;">{{ $setari['siguranta'] }}A 4P</span>
            <span class="fixed text-componente-small text-center" style="top: 18%; left: 77.2%;">Cablu MYYM<br>5 x {{ $setari['sectiune_cablu'] }}mm</span>
            <span class="fixed text-componente-coeficient" style="top: 18.6%; left: 80.2%;">2</span>
            <span class="fixed text-componente text-center" style="top: 13%; left: 88.8%;">Tablou<br>General</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 15.8%; left: 80.9%;">{{ $val_inferioara_siguranta }}A 4P<br>PdeC 4.5kA<br>Curba {{ $tip_curba }}</span>
            <span class="fixed text-componente-small text-center" style="top: 22.7%; left: 85.2%;">Smart Meter<br>Huawei DTSU666-H</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 17.8%; left: 88.6%;">PdeC 4.5kA<br>Curba {{ $tip_curba }}</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 17%; left: 90.4%;">{{ $val_inferioara_siguranta }}A 4P</span>
            <span class="fixed text-componente" style="top: 36.4%; left: 91.3%;">BMPT</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 39.2%; left: 94.5%;">PdeC 4.5kA<br>Curba C</span>
            <span class="fixed text-componente-small-2 text-center" style="top: 38.4%; left: 96.5%;">{{ $capacitate_disjunctor }}A 4P</span>
        @endif
        <span class="fixed text-componente text-center" style="top: 23.4%; left: 67.9%;">Contor din instalatia<br>de utilizare</span>
        <span class="fixed text-componente text-center" style="top: 11.9%; left: 79.2%;">Retea<br>Consumator</span>
        <span class="fixed text-componente" style="top: 47%; left: 94.7%;">RETEA</span>
        <table class="fixed extra-small bg-white text-center" style="width: 39.5%; top: 47.5%; left: 1.5%;">
            <thead>
                <tr>
                    <th>PROTECTIE</th>
                    <th colspan="2" style="width: 100px;">Valori Setate</th>
                    <th style="width: 100px;">Timp<br>(sec/cicluri)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Functia protectie  tensiune maximala treapta I</td>
                    <td>1,15 Un</td>
                    <td>264,5 V</td>
                    <td>0,5 = 25 cicluri</td>
                </tr>
                <tr>
                    <td>Functia protectie tensiune maximala treapta II</td>
                    <td>0,85 Un</td>
                    <td>195,5 V</td>
                    <td>3,2 = 160 cicluri</td>
                </tr>
                <tr>
                    <td>Functia protectie tensiune maximala treapta I</td>
                    <td>52 Hz</td>
                    <td>52 Hz</td>
                    <td>0,5 = 25 cicluri</td>
                </tr>
                <tr>
                    <td>Functia protectie tensiune maximala treapta II</td>
                    <td>47,5 Hz</td>
                    <td>47,5 Hz</td>
                    <td>0,5 = 25 cicluri</td>
                </tr>
                <tr>
                    <td>Functia de protective maxima de tensiune (valoarea mediate la 10 minute)*</td>
                    <td>1,1 Un</td>
                    <td>253 V</td>
                    <td>603</td>
                </tr>
                <tr>
                    <td>Pornire initiala si Repornire invertor dupa intrerupere tensiune RED</td>
                    <td colspan="3">900 S</td>
                </tr>
                <tr>
                    <td>Functie HVRT 264,5 V</td>
                    <td colspan="3">Activata</td>
                </tr>
                <tr>
                    <td>Functie HVRT 184,5 V</td>
                    <td colspan="3">Activata</td>
                </tr>
                <tr>
                    <td>Statism la variatia frecventei</td>
                    <td colspan="3">-5%</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>