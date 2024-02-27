@props([
    'invertor',
    'numar_sp_uri',
    'numar_panouri',
    'marca_panouri',
    'putere_panouri',
    'total',
])

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
