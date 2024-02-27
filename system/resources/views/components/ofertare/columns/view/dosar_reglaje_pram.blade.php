@props([
    'item',
    'column'
])

<a href="{{ route('ofertare.afm.generate.dosar.reglaje.pram', [$item->getModelSection(), $item->id]) }}" 
    class="btn btn-sm grey-mint" title="Generare dosar, reglaje, pram" target="__blank">
    <i class="fa fa-file-pdf-o"></i>
</a>

