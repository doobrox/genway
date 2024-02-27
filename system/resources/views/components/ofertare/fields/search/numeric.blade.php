@props([
    'value' => null,
    'type' => 'number',
    'required' => null,
    'step' => 'any',
    'min' => '',
    'max' => '',
    'model' => null,
    'key' => null,
])

<input {{ $attributes->merge(['class' => 'form-control']) }} 
    type="{{ $type }}"
    style="min-width: 75px;" 
    value="{{ $value }}" 
    @if($model)
        wire:model="{{ $model }}"
        wire:key="{{ $key ?? $model }}"
    @endif
    @if($required) required @endif
    min="{{ $min }}"
    max="{{ $max }}"
    step="{{ $step }}"
    autofocus
>