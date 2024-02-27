@props([
    'column', 
    'values' => null,
    'conditions' => null,
])

<select {{ $attributes->merge(['class' => 'form-control select2']) }} id="search_judet_imobil" name="judet_imobil"
    data-url="{{ $column->data_url }}" data-trigger-url="{{ route('localitati.html') }}" 
    onchange="getLocalitatiInOptionsWithEmpty(this, '#search_localitate_imobil')" data-value="{{ $values }}">
        <option value="{{ $values }}" @selected($values)>{{ $values }}</option>
</select>
