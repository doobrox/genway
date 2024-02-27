@props([
    'label' => null, 
    'type' => 'text', 
    'value' => '',
    'required' => null,
    'offset' => true,
    'row' => true,
    'error' => false,
    'form' => true,
])

@if($form)
    <div class="form-group {{ $error || ($attributes->get('name') && $errors->has(input_name_to_dot($attributes->get('name')))) ? 'has-error' : '' }}">
        @isset ($label)
            <label for="{{ $attributes->get('name') }}" class="col-md-3 control-label">{{ $label }}</label>
        @endisset  
        <div class="@if(!$label && $offset) col-md-offset-3 @endif @if($label || $row) col-md-7 @endif">
@endif 
       
    <input type="{{ $type }}" autofocus autocomplete="off" 
        {{ $attributes->merge(['class' => 'form-control date-picker']) }} 
        value="{{ $value ? \Carbon\Carbon::parse($value ?? '')->format('Y-m-d') : null }}" 
        @if($required) required @endif style="min-width: 85px;">

@if($form)        
        </div>
    </div>
@endif  