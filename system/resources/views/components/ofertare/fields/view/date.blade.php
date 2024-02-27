@props([
    'item',
    'column'
])

{{ $item[$column->nume] ? date("Y-m-d", strtotime($item[$column->nume])) : '' }}


