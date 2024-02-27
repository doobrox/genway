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
		label="Justificare actiune dosar"
		name="justificare_preverificare_dosar"
		:required="true"
		:options="[]"
	    :data_url="''"
	    :value="old('justificare_preverificare_dosar', $item['justificare_preverificare_dosar'] ?? '')"
	/>
	<x-dynamic-component :component="'ofertare.fields.edit.textarea'"
		label="Mesaj trimis in email"
		name="mail_body"
		:options="[]"
	    :value="old('mail_body')"
	/>
	<x-dynamic-component component="ofertare.fields.edit.hidden"
		name="utilizator_preverificare_dosar"
	    :value="auth()->id()"
	/>
	<x-dynamic-component component="ofertare.fields.edit.hidden"
		name="data_preverificare_dosar"
	    :value="now()"
	/>
</form>
