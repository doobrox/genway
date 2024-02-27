@props([
    'type' => 'text',
    'value' => null,
    'required' => null,
    'model' => null,
    'key' => null,
])

<input type="{{ $type }}" {{ $attributes->merge(['class' => 'form-control']) }} 
    @if($model)
        wire:model="{{ $model }}"
        wire:key="{{ $key ?? $model }}"
    @endif
    value="{{ $value }}" @required($required) autofocus>
