@props([
    'item',
    'column'
])

<a target="__blank" href="{{ $item[$column->nume] }}" title="{{ $item[$column->nume] }}"><i class="fa fa-external-link" aria-hidden="true"></i></a>