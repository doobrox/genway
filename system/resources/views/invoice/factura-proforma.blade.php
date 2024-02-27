<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Factura #{{ $comanda->nr_factura }}</title>
        <style type="text/css">
            body {
                font-family:arial;
            }
            .diacritics {
                font-family:"DejaVu Sans", sans-serif;
            }
            .table_main {
                border:1px solid black;
            }
            .border-bottom-right {
                border-bottom:  1px solid black;
                border-right: 1px solid black;
            }
            .border-bottom {
                border-bottom:  1px solid black;
            }
            .border-right {
                border-right: 1px solid black;
            }
            .border-top {
                border-top: 1px solid black;
            }
        </style>
    </head>
    <body>
        <table width="100%" cellspacing="0" cellpadding="0" class="table_main">
            <tr>
                <td width="70%"  class="border-bottom"><h1 style="padding:0; margin:0">FACTURA PROFORMA #{{ $comanda->nr_factura }}</h1>
                    <p style="font-size:12px; padding-left:5px;">
                       <strong>Beneficiar: </strong> <br />
                        @if( $info['client_tip'] == "2")
                            {{ $info['client_nume_firma'] }}<br />
                            CUI: {{ $info['client_cui'] }}<br />
                            Nr. reg. comert.: {{ $info['client_nr_reg_comert'] }}<br />
                        @endif

                        {{ $info['client_nume'] }} {{ $info['client_prenume'] }}<br />
                        Email: {{ $info['client_user_email'] }} <br />
                        Telefon: {{ $info['client_telefon'] }} <br />
                        
                        @if( isset($info['client_livrare_adresa_1']) && $info['client_livrare_adresa_1'] == '1')
                            {{ $info['client_livrare_adresa'] }}<br />
                            Loc. {{ $info['client_livrare_localitate'] }}<br />
                            Jud. {{ $info['client_livrare_judet'] }}<br />
                        @else
                            {{ $info['client_adresa'] }}<br />
                            Loc. {{ $info['client_localitate'] }}<br />
                            Jud. {{ $info['client_judet'] }}<br />
                        @endif
                    </p>
                </td>
                <td width="30%"  class="border-bottom" valign="top">
                    <p style="font-size:12px; padding-right:5px;">
                        Nr Factura: {{ $comanda->nr_factura }}<br />
                        Data Comanda: {{ $comanda->data_adaugare }}<br /><br />

                        <strong>Furnizor:</strong><br />
                        {{ $info['furnizor_nume_firma'] }}<br />
                        {{ $info['furnizor_adresa'] }}<br />
                        Loc. {{ $info['furnizor_localitate'] }}, Jud. {{ $info['furnizor_judet'] }}<br />
                        CUI: {{ $info['furnizor_cui'] }} - Nr.reg.com. {{ $info['furnizor_nr_reg_comert'] }}<br />
                        Cont: {{ $info['furnizor_cod_fiscal'] ? $info['furnizor_cod_fiscal'].' - ' : '' }}{{ $info['furnizor_banca'] }}
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                   <table width="100%" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <td class="border-bottom-right"><strong>Nume produs</strong></td>
                            <td class="border-bottom-right"><strong>Cod</strong></td>
                            <td class="border-bottom-right"><strong>Pret</strong></td>
                            <td class="border-bottom-right"><strong>Cantitate</strong></td>
                            <td class="border-bottom"><strong>Total</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produse as $produs)
                            <tr class="div-row-cos-cumparaturi">
                                <td class="border-bottom-right">
                                    {{ $produs->nume }}
                                    {{-- @if(!empty( $produs->filtre ))
                                        <br />
                                        @foreach($produs->filtre as $filtru)
                                            <span style="padding-left: 20px">    
                                                <strong>{{ $filtru['nume_parinte'] }}</strong>: {{ $filtru['nume_filtru'] }}
                                            </span><br />
                                        @endforeach
                                    @endif --}}
                                </td>
                                <td class="border-bottom-right">{{ $produs->cod_ean13 }}</td>
                                <td class="border-bottom-right">{{ $produs->pret }}</td>
                                <td class="border-bottom-right">x {{ $produs->cantitate }}</td>
                                <td class="border-bottom">= {{ round($produs->pret * $produs->cantitate, 2) }}</td>
                            </tr>
                        @endforeach

                        @if($comanda->tva > 0)
                            <tr>
                                <td class="border-bottom-right"> </td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right">TVA {{ $comanda->tva }}%</td>
                                <td class="border-bottom"><strong>+ {{ $comanda->valoare_tva }}</strong></td>
                            </tr>
                        @endif

                        @if($comanda->valoare_discount_fidelitate != 0)
                            <tr>
                                <td class="border-bottom-right">Discount fidelitate {{ $comanda->discount_fidelitate }}%</td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom"><strong>{{ $comanda->valoare_discount_fidelitate }} lei</strong></td>
                            </tr>
                        @endif
                            
                        @if($comanda->valoare_discount_plata_op != 0)
                            <tr>
                                <td class="border-bottom-right">Discount plata in avans {{ $comanda->discount_plata_op }}%</td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom"><strong>{{ $comanda->valoare_discount_plata_op }} lei</strong></td>
                            </tr>
                        @endif
                            
                        @if($comanda->cod_voucher != "" && $comanda->cod_voucher != null)
                            <tr>
                                <td class="border-bottom-right">Cod cupon / discount {{ $comanda->cod_voucher }}</td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom"><strong>{{ $comanda->valoare_voucher }} lei</strong></td>
                            </tr>
                        @endif

                        <tr>
                            <td class="border-right">Modalitate expediere: <strong>{{ $info['comanda_curier'] }}</strong></td>
                            <td class="border-right"></td>
                            <td class="border-right"></td>
                            <td class="border-right"></td>
                            <td class="border-right"><strong>{{ $comanda->taxa_livrare > 0 ? "+ ".$comanda->taxa_livrare : 0 }}</strong></td>
                        </tr>
                        <tr>
                            <td class="border-top" colspan="3"></td>
                            <td class="border-top"><strong>Total</strong></td>
                            <td class="border-top"><strong>{{ $comanda->valoare }} lei</strong></td>
                        </tr>   
                    </tbody>
                </table>
                </td>
            </tr>
        </table>
        Plata se va face {{ $comanda->text_factura_tip_plata }} catre {{ $info['furnizor_nume_firma'] }}.
    </body>
</html>