@props([
    'item',
    'column',
    'all' => false
])

@php
    if($item->oferta) {
        $item['model_panouri'] = $item->oferta->model_panouri;
        $item['limita_cablare_ac'] = $item['limita_cablare_ac'] ?? $item->oferta->limita_cablare_ac;
        $item['limita_cablare_dc'] = $item['limita_cablare_dc'] ?? $item->oferta->limita_cablare_dc;
    }
    $result = $item->componenta ? nl2br($item->componenta->descriere) : nl2br(
        'Invertor '.($item['putere_invertor'] + 0 ?? '').'kW, monofazat sau trifazat ('.($item['marca_invertor'] ?? 'Huawei sau Fronius').')
        Modul de comunicație pentru vizualizarea datelor de la distanață
        '.($item['numar_panouri'] ?? '').' panouri monocristaline '.($item['marca_panouri'] ?? '').' '.($item['model_panouri'] ?? '').' -  '.($item['putere_panouri'] + 0 ?? '').'Wp/buc (total '.($item['putere_panouri'] * $item['numar_panouri'] / 1000).'kWp) '.''.'
        Suport de prindere pe plan înclinat peste învelitoare din țiglă ceramică sau tablă
        Contor inteligent '.($item['marca_invertor'] ?? 'Huawei sau Fronius').'
        Cofret protecții DC (curent continuu): descărcător(SPD), siguranțe fuzibile, întrerupător-separator
        Cofret protecții AC(curent alternativ): siguranță automată (MCB)
        Panou de informare, accesorii, cablare (în limita a '.($item['limita_cablare_dc'] ?? '30').'m de traseu DC și '.($item['limita_cablare_ac'] ?? '10').'m traseu AC) și manoperă
        Verificarea prizei de pământ și întocmirea documentației tehnice pentru obținerea calității de
        prosumator
    ');
@endphp

@if(strlen(strip_tags($result)) > 50 && $all === false)
    {{ substr(strip_tags($result), 0, 50) }}...
    <button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#{{ $column->nume.'_'.$item['id'] }}_modal">+ Mai mult</button>
    <div class="modal fade" id="{{ $column->nume.'_'.$item['id'].'_modal' }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalLabel">{{ $item[$column->titlu] ?? '#'.$item->id. ' ' .$column->nume }}</h4>
                </div>
                <div class="modal-body">{!! $result !!}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                </div>
            </div>
        </div>
    </div>
@else
    {!! $result !!}
@endif