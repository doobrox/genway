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
            <li>
                <a href="{{ route('ofertare.afm.generate.proces.verbal.pif', [$item->getModelSection(), $item->id, $value]) }}"
                    class="btn btn-sm purple" title="{{ __('Proces verbal PIF :title', ['title' => $title]) }}" target="__blank">
                    <i class="fa fa-file-pdf-o" style="color: #fff"></i> {{ __('Proces verbal PIF :title', ['title' => $title]) }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
