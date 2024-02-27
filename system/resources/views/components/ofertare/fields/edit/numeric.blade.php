@props([
    'label' => '',
    'name',
    'type' => 'number',
    'value' => '',
    'required' => null,
    'step' => 'any',
    'min' => '',
    'max' => '',
    'form' => true,
])

@if($form)
    <div class="form-group">
        @isset ($label)
            <label for="{{ $name }}" class="col-md-3 control-label">{{ $label }}</label>
            <div class="col-md-7">
        @else
        <div class="col-md-offset-3 col-md-7">
        @endisset
@endif  

    <input
        type="{{ $type }}"
        @isset($name) name="{{ $name }}" @endisset
        class="form-control"
        value="{{ $value ?? '' }}"
        @if($required) required @endif
        min="{{ $min }}"
        max="{{ $max }}"
        step="{{ $step }}"
        autofocus
    >
    
@if($form)        
        </div>
    </div>
@endif 
