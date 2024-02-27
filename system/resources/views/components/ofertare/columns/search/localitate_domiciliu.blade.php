@props([
    'column', 
    'values' => null,
    'conditions' => null,
])

@php
if(!empty($conditions['judet_domiciliu'])) {
    $values = \App\Models\Localitate::where('id_judet', $conditions['judet_domiciliu'])
        ->pluck('nume', 'id')
        ->toArray();
} elseif (!empty($conditions['localitate_domiciliu'])) {
    $values = \App\Models\Localitate::where('id_judet', 
        \App\Models\Localitate::select('id_judet')->where('id', $conditions['localitate_domiciliu'])->limit(1)
    )->pluck('nume', 'id')
    ->toArray();
} else {
    $values = [];
}
@endphp

<select {{ $attributes->merge(['class' => 'form-control select2']) }} id="search_localitate_domiciliu" name="localitate_domiciliu">
    <option value=""></option>
    @foreach(is_array($values) ? $values : [] as $id => $label)
        <option value="{{ $id }}" @selected($values == $id)>{{ $label }}</option>
    @endforeach
</select>
