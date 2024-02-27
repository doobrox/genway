@props([
    'label' => '', 
    'name', 
    'type' => 'file',
    'required' => null, 
    'multiple' => false, 
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
        @if($required) required @endif
        @if($multiple) multiple @endif
    >

@if($form)                
        </div>
    </div>
@endif
