@props([
    'value' => null,
    'model' => null,
    'key' => null,
    'required' => null, 
])
 
<label class="mt-checkbox mt-checkbox-outline m-0"> {{ __('Fisier incarcat') }}
    <input {{ $attributes->merge(['class' => 'form-control']) }} type="checkbox" 
        @if($model)
            wire:model="{{ $model }}.not_empty"
            wire:key="{{ $key ?? $model }}.not_empty"
        @endif
        value="1" @required($required) @checked(!empty($value['not_empty']))>
    <span></span>
</label>

<label class="mt-checkbox mt-checkbox-outline m-0"> {{ __('Necompletat') }}
    <input {{ $attributes->merge(['class' => 'form-control']) }} type="checkbox" 
        @if($model)
            wire:model="{{ $model }}.empty"
            wire:key="{{ $key ?? $model }}.empty"
        @endif
        value="1" @required($required) @checked(!empty($value['empty']))>
    <span></span>
</label>
