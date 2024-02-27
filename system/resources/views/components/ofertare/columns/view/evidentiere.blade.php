@props([
    'item',
    'column' 
])
 
<label class="mt-checkbox mt-checkbox-outline m-0">
    <input {{ $attributes->merge(['class' => 'form-control']) }} wire:model.live="formular" type="checkbox" value="{{ $item->id }}">
    <span></span>
</label>
