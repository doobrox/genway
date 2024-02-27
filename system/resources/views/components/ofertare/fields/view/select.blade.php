@props([
    'item',
    'column'
])

@if($column->default_values && isset($item[$column->nume]))
    {{ $column->default_values[$item[$column->nume]] ?? '' }}
@else
    {!! nl2br($item['nume_'.$column->nume] ?? $item[$column->nume] ?? '') !!}
@endif
