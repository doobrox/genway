@props([
    'label' => '',
    'name',
    'value' => '',
    'required' => null,
    'class' => 'ckeditor',
    'id' => 'ckeditor',
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

    <textarea id="{{ $id }}" class="{{ $class }}" @isset($name) name="{{ $name }}" @endisset @if($required) required @endif>{{ $value }}</textarea>

@if($form)
        </div>
    </div>
@endif 

