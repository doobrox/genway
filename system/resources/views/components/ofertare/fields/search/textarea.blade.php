@props([
    'rows' => '1', 
    'value' => null, 
    'required' => null, 
    'model' => null,
    'key' => null,
])

<textarea {{ $attributes->merge(['class' => 'form-control']) }} rows="{{ $rows }}" 
    @if($model)
        wire:model="{{ $model }}"
        wire:key="{{ $key ?? $model }}"
    @endif
    @required($required) autofocus>{{ $value }}</textarea>
