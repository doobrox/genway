@props([
    'label' => '', 
    'data_url' => null, 
    'value' => null,
    'options' => [],
    'name' => null, 
    'required' => null, 
    'multiple' => null, 
    'form' => true,
])

@if($form)
    <div class="form-group">
        @isset ($label)
            <label @if(!empty($name)) for="{{ input_name_to_dot($name, '-') }}" @endif class="col-md-3 control-label">{{ $label }}</label>
            <div class="col-md-7">
        @else
        <div class="col-md-offset-3 col-md-7">
        @endisset
@endif

    <select 
        {{ $attributes->merge(['class' => 'form-control select2']) }}
        data-value="{{ is_array($value) ? json_encode($value) : $value }}"
        @if(!empty($name)) 
            @isset ($label) id="{{ input_name_to_dot($name, '-') }}" @endisset
            name="{{ $name }}{{ !empty($multiple) ? '[]' : '' }}" 
        @endif
        @if($data_url) data-url="{{ $data_url }}" @endisset
        @if($required) required @endif
        @if($multiple) multiple @endif
    >
        @forelse ($options as $option_value => $option_label)
            @if(!$required || (isset($option_value) && $option_value !== ''))
                <option value="{{ $option_value }}" @selected((is_array($value) && in_array($option_value, $value)) || $option_value == $value)>{{ $option_label }}</option>
            @endif
        @empty
            @foreach(\Arr::wrap($value) as $result)
                @if(!$required || (isset($result) && $result !== ''))
                    <option value="{{ $result }}" selected>{{ $result }}</option>
                @endif
            @endforeach    
        @endforelse
    </select>

@if($form)
        </div>
    </div>
@endif