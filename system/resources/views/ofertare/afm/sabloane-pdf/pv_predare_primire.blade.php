<!DOCTYPE html>
<html>
    <head>
        <title>PROCES VERBAL</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            *, html, body {
                /*font-family: "Times New Roman";
                 font-family: Arial, sans-serif;*/
                font-size: 12px;
                /*font-family: 'Open Sans', sans-serif, Arial, Helvetica, Sans-Serif;*/
                font-family: "DejaVu Sans", sans-serif;
            }
            .titlu {
                font-size: 16px;
                text-align: center;
            }
            .text-center {
                text-align: center;
            }
            .left {
                float: left;
            }
            .right {
                float: right;
            }
            .componente > p {
                line-height: 14px;
                margin: 0;
                padding-top: 3px;
            }
            .tab {
                margin-left: 30px;
            }
            table.echipament th, table.echipament td {
                padding-top: 10px;
                padding-bottom: 10px;
                vertical-align: middle;
                border: 1px solid #000;
            }
            table.echipament {
                border: 1px solid #000;
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <img width="250px" style="opacity: .5;" src="{{ route('images', 'logo_genway.png') }}" style="margin-bottom: 20px;">
        <h2 class="titlu">PROCES VERBAL</h2>
        <p class="text-center">de recepție cantitativă si calitativă conform contractului <br>NR. {{ $numar_contract_instalare; }} / {{ $data_contract_instalare ? date("d.m.Y", strtotime($data_contract_instalare)) : '' }}</p>
        <br>
        <p>Prezentul contract se incheie intre:</p>
        <p><b>PRESTATOR</b></p>
        <p>{{ $fi['nume_firma'] ?? '.....' }} cu sediul in {{ $fi['judet_firma'] ?? '......' }}, {{ $fi['localitate_firma'] ?? '......' }}, {{ $fi['adresa_firma'] ?? '......' }}, cod fiscal {{ $fi['cod_fiscal_firma'] ?? '......' }}, Registrul Comertului {{ $fi['reg_com_firma'] ?? '......' }}, cont nr. {{ $fi['cont_firma'] ?? '......' }}, deschis la {{ $fi['banca_firma'] ?? '......' }}, reprezentata legal prin {{ $fi['reprezentant_firma'] ?? '......' }}</p>
        <p>ȘI</p>
        <p><b>BENEFICIAR</b></p>
        <p>{{ $nume }} {{ $prenume }} cu domiciliul in jud. {{ $nume_judet_domiciliu }}, localitatea {{ $nume_localitate_domiciliu }}, str. {{ $strada_domiciliu }}, nr. {{ $numar_domiciliu }}, {{  isset($bloc_domiciliu) && !empty($bloc_domiciliu) ? "bloc ".$bloc_domiciliu.", " : "" }} {{  isset($scara_domiciliu) && !empty($scara_domiciliu) ? "scara ".$scara_domiciliu.", " : "" }} {{  isset($et_domiciliu) && !empty($et_domiciliu) ? "et. ".$et_domiciliu.", " : "" }} {{  isset($ap_domiciliu) && !empty($ap_domiciliu) ? "ap. ".$ap_domiciliu.", " : "" }}, act de identitate tip C.I., seria {{ $serie_ci }}, numar {{ $numar_ci }}, cod numeric personal {{ $cnp }}, cont nr. - deschis la -, în calitate de beneficiar final, denumit/denumită în continuare beneficiar.</p>
        <br>

        <p>
            Adresa de implementare este: jud. {{ $nume_judet_imobil }}, loc. {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}, inscris in Cartea Funciara nr. {{ $nr_carte }}, numar cadastral {{ $nr_cadastral }}
        </p>

        <p>
            Comisia de recepție a studiat contractul de achiziție și consideră că actele prezentate sunt suficiente pentru aprecierea cantității și calității materialelor, conform documentelor contractuale, și a dispus &icirc;nceperea recepției.
        </p>

        <p class="tab">
            &Icirc;ncheiat azi &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.., cu ocazia predării de Instalalor către Beneficiar a următoarelor echipamente:
        </p>

        <p>
            Verificări cantitative:
        </p>

        <p>
            S-au identificat și preluat:
        </p>
        <table class="echipament">
            <thead>
                <tr>
                    <th> Nr. Crt.</th>
                    <th> Tipul de echipament</th>
                    <th> UM</th>
                    <th> Număr bucăți</th>
                    <th> Serii echipamente</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> 1.</td>
                    <td> Invertor solar {{ $invertor['putere'] ?? 0 + 0 }}kw, {{ $invertor['tip'] ?? '' }},<br>{{ $invertor['cod'] ?? '' }} </td>
                    <td> Buc</td>
                    <td> 1</td>
                    <td></td>
                </tr>
                <tr>
                    <td> 2.</td>
                    <td> Contor {{ $invertor['tip'] ?? '' }}, <br>{{ $invertor['contor'] ?? '' }}</td>
                    <td> Buc</td>
                    <td> 1</td>
                    <td></td>
                </tr>
                <tr>
                    <td> 3.</td>
                    <td> Tablou de protectie curent<br>continuu complet echipat</td>
                    <td> Buc</td>
                    <td> {{ $numar_sp_uri }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td> 4.</td>
                    <td> Siguranta automata</td>
                    <td> Buc</td>
                    <td> 1</td>
                    <td></td>
                </tr>
                @for($i = 5; $i < $numar_panouri+5; $i++)
                    <tr>
                        <td> {{ $i }}.</td>
                        <td> Panou fotovoltaic<br>monocristalin {{ $marca_panouri }} {{ $putere_panouri ?? '385' }}W</td>
                        <td> Buc</td>
                        <td> 1</td>
                        <td></td>
                    </tr>
                @endfor
                @foreach($total as $nume_echipament => $cant)
                    @if($cant > 0)
                        <tr>
                            <td>{{ $i }}.</td>
                            <td>{{ $nume_echipament }}</td>
                            <td>Buc</td>
                            <td>{{ $cant }}</td>
                            <td></td>
                        </tr>
                        @php $i++; @endphp
                    @endif
                @endforeach
            </tbody>
        </table>

        <p>
            Verificari calitative:
        </p>

        <p>
            Ambalajele &icirc;n care se regăsesc produsele sunt/nu sunt deformate și prezintă/nu prezintă urme de desfacere.
        </p>

        <p class="tab">
            Beneficiarul este răspunzător de modul depozitării p&acirc;nă la &icirc;nceperea montajului de către Instalator. Acestea trebuie depozitate la interior, &icirc;ntr-un loc ferit de ploaie și ninsoare.
        </p>

        <p>
            &nbsp;
        </p>

        <br>
        <br>
        <table width="100%">
            <tr>
                <td align="left" style="width:200px;">
                    <p><strong>Instalator,</strong></p>
                    <p>{{ $fi['nume_firma'] ?? '.................' }}</p>
                    <p>{{ $fi['reprezentant_firma'] ?? '.................' }}</p>
                    <p>....................................</p>
                </td>
                <td align="left">
                    <img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;">
                </td>
                <td></td>
                <td align="right" style="width:200px;">
                    <p><strong>Beneficiar,</strong></p>
                    <p>{{ $nume }} {{ $prenume }}</p>
                    <p>....................................</p>
                </td>
            </tr>
        </table>
    </body>
</html>
