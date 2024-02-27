@props([
    'item',
    'column'
])

<a href="{{ route('ofertare.afm.generate.monofilara', [$item->getModelSection(), $item->id]) }}" 
    class="btn btn-sm yellow-mint" title="Generare monofilara" target="__blank" 
>
    <i class="fa fa-map"></i>
</a>

