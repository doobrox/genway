@props([
    'label' => '', 
    'data_url' => null, 
    'value' => null,
    'options' => [],
    'name' => null, 
    'required' => null, 
    'multiple' => null, 
    'model' => null,
    'key' => null,
])

<select 
    {{ $attributes->merge(['class' => 'form-control select2']) }}
    data-value="{{ is_array($value) ? json_encode($value) : $value }}"
    @if(!empty($name)) 
        @isset ($label) id="{{ input_name_to_dot($name, '-') }}" @endisset
        name="{{ $name }}{{ !empty($multiple) ? '[]' : '' }}" 
    @endif
    @if($model)
        wire:model="{{ $model }}"
        wire:key="{{ $key ?? $model }}"
    @endif
    @if($data_url) data-url="{{ $data_url }}" @endisset
    @required($required)
    @if($multiple) multiple @endif
>
    @forelse ($options as $option_value => $option_label)
        <option value="{{ $option_value }}" @selected((is_array($value) && in_array($option_value, $value)) || $option_value == $value)>{{ $option_label }}</option>
    @empty
        @foreach(\Arr::wrap($value) as $result)
            <option value="{{ $result }}" selected>{{ $result }}</option>
        @endforeach    
    @endforelse
</select>
