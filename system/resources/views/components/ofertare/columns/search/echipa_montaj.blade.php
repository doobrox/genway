@props([
    'column', 
    'values' => null,
    'conditions' => null,
])

@php
    $values = \App\Models\User::whereIn('id', is_array($values) ? $values : [])
        ->pluck(\DB::raw('CONCAT('.implode(",' ',", $column->rules['db']['cols']).') as text'), 'id')
        ->toArray();
@endphp

<div class="select2-wrapper">
    <select {{ $attributes->merge(['class' => 'form-control select2']) }} 
        data-values="{{ json_encode(is_array($values) ? $values : '') }}" name="{{ $column->nume }}[]" 
        data-ajax--url="{{ $column->data_url }}" multiple>
        @forelse(is_array($values) ? $values : [] as $id => $label)
            <option value="{{ $id }}" selected>{{ $label }}</option>
        @empty
        @endforelse
    </select>
</div>