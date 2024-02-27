@props([
    'label' => '',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => null,
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

    <input type="{{ $type }}" @isset($name) name="{{ $name }}" @endisset class="form-control" value="{{ $value ?? '' }}" @if($required) required @endif>

@if($form)            
        </div>
    </div>
@endif