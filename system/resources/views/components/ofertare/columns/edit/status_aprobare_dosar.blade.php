@props(['section' => '2021', 'column', 'item', 'types' => \App\Models\Ofertare\ColoanaTabelAFM::types()])

<form class="form-temp form-horizontal" method="post" action="{{ route('ofertare.afm.update.column', ['section' => $section, 'formular' => $item->id, 'column' => $column->nume]) }}">
	@csrf
	<x-dynamic-component :component="'ofertare.fields.edit.'.$types[$column->tip]" required
		:label="$column->titlu"
		:name="$column->nume"
		:required="true"
		:options="$column->default_values"
	    :value="old($column->nume, $item[$column->nume] ?? '')"
	/>
	<x-dynamic-component :component="'ofertare.fields.edit.textarea'"
		label="Justificare status aprobare dosar"
		name="justificare_status_aprobare_dosar"
		:required="true"
		:options="[]"
	    :data_url="''"
	    :value="old('justificare_status_aprobare_dosar', $item['justificare_status_aprobare_dosar'] ?? '')"
	/>
	<x-dynamic-component :component="'ofertare.fields.edit.textarea'"
		label="Mesaj trimis in email"
		name="mail_body"
		:options="[]"
	    :value="old('mail_body')"
	/>
	<x-dynamic-component component="ofertare.fields.edit.hidden"
		name="utilizator_status_aprobare_dosar"
	    :value="auth()->id()"
	/>
	<x-dynamic-component component="ofertare.fields.edit.hidden"
		name="data_status_aprobare_dosar"
	    :value="now()"
	/>
    <p><b>* {{ __('Atentie! Daca nu este completat campul "Judet domiciliu", nu se poate alege automat un responsabil.') }}</b></p>
</form>
