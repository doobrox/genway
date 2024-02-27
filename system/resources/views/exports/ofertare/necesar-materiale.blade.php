<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td><img width="150px" src="{{ $logo }}"></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="width: 350px;font-size: 25px;"><strong>Necesar materiale</strong></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Data:  </td>
        <td>{{ date('d.m.Y') }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Nume client:  </td>
        <td style="text-align: left;">{{ $formular->nume ?? '' }} {{ $formular->prenume ?? '' }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Adresa:  </td>
        <td style="text-align: left;">{{ $formular->adresa_imobil_scurt }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Numar contract instalare:  </td>
        <td style="text-align: left;">{{ $formular->numar_contract_instalare }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Numar contract AFM:</td>
        <td style="text-align: left;">{{ $formular->numar_dosar_afm }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Echipament #</td>
        <td>Descriere</td>
        <td>Cantitate</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">Sina R1 - 2.4m</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $total['sina_24'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">Sina R1 - 3.6m</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $total['sina_36'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">RS</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $total['RS'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">{{ $formular->nume_invelitoare }}</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $total['MRH'] ?: $total['TRH'] ?: $total['MRH_SW'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">EC-01</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $total['EC'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">MC-01</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $total['MC'] }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">Invertor</td>
        <td style="border: 1px solid #000;">{{ $invertor->cod ?? '' }}, {{ $invertor->putere ?? 0 + 0 }}kW, {{ $formular->tipul_bransamentului }}</td>
        <td style="border: 1px solid #000;">1</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">Panouri</td>
        <td style="border: 1px solid #000;">{{ $formular->putere_panouri + 0 }}W</td>
        <td style="border: 1px solid #000;">{{ $formular->numar_panouri }}</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000;">SP-uri</td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;">{{ $formular['numar_sp-uri'] ?? '' }}</td>
        <td></td>
    </tr>
</table>