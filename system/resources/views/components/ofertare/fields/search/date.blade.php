@props([
    'value' => null,
    'model' => null,
    'key' => null,
    'required' => null
])

@foreach(['start' => __('Inceput'), 'end' => __('Sfarsit')] as $input => $placeholder)
    <input type="text" {{ $attributes->merge(['class' => 'form-control date-picker'.(!$loop->first ? ' mt-1' : '')]) }} 
        @if($model)
            wire:model="{{ $model }}.{{ $input }}"
            wire:key="{{ $key ?? $model }}.{{ $input }}"
        @endif
        value="{{ $value[$input] ?? null }}" style="min-width: 100px;" 
        onchange="this.dispatchEvent(new InputEvent('input'))"
        placeholder="{{ $placeholder }}" @required($required) autofocus>
@endforeach
<label class="mt-checkbox mt-checkbox-outline m-0 mt-1"> {{ __('Necompletat') }}
    <input {{ $attributes->merge(['class' => 'form-control']) }} type="checkbox" 
        @if($model)
            wire:model="{{ $model }}.empty"
            wire:key="{{ $key ?? $model }}.empty"
        @endif
        value="1" @required($required) @checked(!empty($value['empty']))>
    <span></span>
</label>