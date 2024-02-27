<html>
    <body style="text-rendering: optimizelegibility;
          font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
        <div style="border: 1px solid #cccccc; width:90%; height: auto; margin: 10px auto; padding: 10px;">	
            <div style="color: #000;"><h1 style="margin: 0px; color: #2B57C7;
                                          font-size: 16px;
                                          font-weight: normal;">{{ $details['subject'] }}</h1>
            </div>
            <div style="clear: both; margin-top: 10px; border-top: 1px dashed #777777; height: 10px;"></div>
            <div style="padding-bottom: 10px; font-size: 13px;">
                {!! $details['body'] !!}
                <table width="100%">
                    <tr valign="top">
                        <td valign="top">
                            <div style="border: 1px solid #757575; padding: 10px">
                                <strong>Beneficiar: </strong> <br />
                                @if( $user->tip == "2")
                                    {{ $user->nume_firma }}<br />
                                    CUI: {{ $user->cui }}<br />
                                    Nr. reg. comert.: {{ $user->nr_reg_comert }}<br />
                                @endif
                                <br />
                                {{ $user->nume }} {{ $user->prenume }}<br />
                                Email: {{ $user->user_email }} <br />
                                Telefon: {{ $user->telefon }} <br /><br />
                                @if( $user->livrare_adresa_1 == '1')
                                    {{ $user->livrare_adresa }}<br />
                                    Loc. {{ $localitate->nume }}<br />
                                    Jud. {{ $localitate->judet->nume }}<br />
                                @else
                                    {{ $user->adresa }}<br />
                                    Loc. {{ $localitate->nume }}<br />
                                    Jud. {{ $localitate->judet->nume }}<br />
                                @endif
                            </div>
                        </td>
                        <td valign="top">
                            <div style="border: 1px solid #757575; margin-left: 10px; padding: 10px">
                                <strong>Furnizor:</strong><br />
                                {{ $setari['FACTURARE_NUME_FIRMA'] }}<br />
                                {{ $setari['FACTURARE_ADRESA'] }}<br />
                                Loc. {{ $setari['FACTURARE_LOCALITATE'] }}, Jud. {{ $setari['FACTURARE_JUDET'] }}<br />
                                CUI: {{ $setari['FACTURARE_CUI'] }} - Nr.reg.com. {{ $setari['FACTURARE_NR_REG_COMERT'] }}<br />
                                Cont: {{ $setari['FACTURARE_COD_FISCAL'] }} - {{ $setari['FACTURARE_BANCA'] }}
                            </div>
                        </td>
                    </tr>
                </table>
                <table width="100%" cellpadding="5" border="1" rules="all" style="margin: 10px 0">
                    <thead>
                        <tr>
                            <td><strong>Nume produs</strong></td>
                            <td><strong>Cod</strong></td>
                            <td><strong>Pret</strong></td>
                            <td><strong>Cantitate</strong></td>
                            <td><strong>Total</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produse as $produs)
                            <tr class="div-row-cos-cumparaturi">
                                <td>
                                    <a href="{{ $produs->route }}" title="{{ $produs->nume }}">{{ $produs->nume }}</a>
                                    {{-- @if(!empty( $produs->filtre ))
                                        <br />
                                        @foreach($produs->filtre as $filtru)
                                            <span style="padding-left: 20px">    
                                                <strong>{{ $filtru['nume_parinte'] }}</strong>: {{ $filtru['nume_filtru'] }}
                                            </span><br />
                                        @endforeach
                                    @endif --}}
                                </td>
                                <td>{{ $produs->cod_ean13 }}</td>
                                <td>{{ $produs->pret_cos }} lei</td>
                                <td>x {{ $produs->cantitate }}</td>
                                <td>= {{ round($produs->pret_cos * $produs->cantitate, 2) }}</td>
                            </tr>
                        @endforeach
                        @if($comanda->tva > 0)
                            <tr>
                                <td> </td>
                                <td></td>
                                <td></td>
                                <td>TVA {{ $comanda->tva }}%</td>
                                <td><strong>+ {{ $comanda->valoare_tva }} lei</strong></td>
                            </tr>
                        @endif 
                        @if($comanda->valoare_discount_fidelitate != 0)
                            <tr>
                                <td>Discount fidelitate {{ $comanda->discount_fidelitate }}%</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>{{ $comanda->valoare_discount_fidelitate }} lei</strong></td>
                            </tr>
                        @endif
                        @if($comanda->valoare_discount_plata_op != 0)
                            <tr>
                                <td>Discount plata in avans {{ $comanda->discount_plata_op }}%</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>{{ $comanda->valoare_discount_plata_op }} lei</strong></td>
                            </tr>
                        @endif
                        @if($comanda->cod_voucher != "" && $comanda->cod_voucher != null)
                            <tr>
                                <td>Cod cupon / discount {{ $comanda->cod_voucher }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>{{ $comanda->valoare_voucher }} lei</strong></td>
                            </tr>
                        @endif
                        <tr>
                            <td>Modalitate expediere: {{ $comanda->nume_curier }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>{{ $comanda->taxa_livrare > 0 ? "+ ".$comanda->taxa_livrare : 0 }} lei</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3">Metoda plata: {{ $comanda->text_tip_plata }}</td>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $comanda->valoare }} lei</strong></td>
                        </tr>   
                    </tbody>
                </table>
                @if($comanda->mesaj != "" && $comanda->mesaj != null)
                    <b>Alte detalii despre comanda:</b><br />
                    {{ nl2br($comanda->mesaj) }}
                @endif
            </div>
            <div style="border-bottom: 1px dashed #777777; border-top: 1px dashed #777777; padding: 10px 0; font-size: 13px; color: #777777;">
                Ati primit acest email deoarece aceasta adresa de email a fost folosita la inscrierea pe www.genway.ro. Daca acest mesaj a ajuns din greseala va rugam sa ne <a href="{{ route('contact') }}" style="color: #2B57C7; text-decoration: none !important;">contactati</a>
            </div>
        </div>
    </body>
</html>