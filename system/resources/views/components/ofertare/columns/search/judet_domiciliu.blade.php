@props([
    'column', 
    'values' => null,
    'conditions' => null,
])

<select {{ $attributes->merge(['class' => 'form-control select2']) }} id="search_judet_domiciliu" name="judet_domiciliu"
    data-url="{{ $column->data_url }}" data-trigger-url="{{ route('localitati.html') }}" 
    onchange="getLocalitatiInOptionsWithEmpty(this, '#search_localitate_domiciliu')" data-value="{{ $values }}">
        <option value="{{ $values }}" @selected($values)>{{ $values }}</option>
</select>
