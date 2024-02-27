@props([
    'item',
    'column'
])

<div class="dropdown">
    <button class="dropdown-toggle btn btn-sm purple" type="button" data-toggle="dropdown" style="border: none">
        <i class="fa fa-file-pdf-o"></i> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-options">
        @foreach($column->default_values as $value => $title)
            @php
                switch ($value) {
                    case 'Imputernicire':
                        $color = 'yellow';
                        break;
                    default:
                        $color = 'purple';
                        $title = __('Notificare racordare :title', ['title' => $title]);
                        break;
                }
            @endphp
            <li>
                <a href="{{ route('ofertare.afm.generate.document', [$item->getModelSection(), $item->id, 'notificare-racordare-'.$value, $item->getModelSection()]) }}" 
                    class="btn btn-sm {{ $color }}" target="__blank" title="{{ $title }}">
                    <i class="fa fa-file-pdf-o" style="color: #fff"></i> {{ $title }}
                </a>
            </li>
        @endforeach
    </ul>
</div>