@props([
    'label' => null,
    'text' => null,
    'required' => null, 
    'checked' => null, 
    'offset' => true,
    'row' => true,
    'form' => true,
])

@if($form)
    <div class="form-group">
        @isset ($label)
            <label for="{{ $name }}" class="col-md-3 control-label">{{ $label }}</label>
        @endisset
        <div class="mt-checkbox-inline @if(!$label && $offset) col-md-offset-3 @endif @if($label || $row) col-md-7 @endif">
@endif 

    <label class="mt-checkbox m-0 margin-right-10">
        <input type="checkbox" {{ $attributes }} @checked($checked) @required($required)> {{ $text }}
        <span></span>
    </label>

@if($form)
        </div>
    </div>
@endif 
