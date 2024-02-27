@props([
    'label' => '', 
    'name', 
    'rows' => '4', 
    'placeholder' => '',
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
    <textarea autofocus class="form-control" @isset($name) name="{{ $name }}" @endisset rows="{{ $rows }}" placeholder="{{ $placeholder }}" @if($required) required @endif>{{ $value }}</textarea>

@if($form)   
        </div>
    </div>
@endif 

