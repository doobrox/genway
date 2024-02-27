@props([
    'selected_value' => '', 
    'data_url', 
    'placeholder' => '', 
    'label' => '', 
    'required' => null,
    'form' => true,
])

@if($form)
    <div class="form-group">
        @isset($label)
            <label class="col-md-3 control-label">{{ $label }}</label>
            <div class="col-md-7 select2-container block">
        @else
            <div class="col-md-offset-3 col-md-7 select2-container block">
        @endisset
@endif 

    <select name="formular_id" class="form-control select2-ajax"
        data-url="{{ $data_url }}"
        @isset($placeholder) data-placeholder="{{ $placeholder }}" @endisset
        @if($required) required @endif
    >
        @if($selected_value)
            <option value="{{ $selected_value }}" selected>{{ $selected_value }}</option>
        @endif
    </select>

@if($form)
        </div>
    </div>
@endif 
