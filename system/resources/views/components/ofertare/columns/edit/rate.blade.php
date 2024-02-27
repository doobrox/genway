@props(['section' => '2021', 'column', 'item', 'types' => \App\Models\Ofertare\ColoanaTabelAFM::types()])

{{-- <form class="form-temp form-horizontal" method="post" action="{{ route('ofertare.afm.update.column', ['section' => $section, 'formular' => $item->id, 'column' => $column->nume]) }}">
	@csrf --}}
	<table class="table table-advance table-responsive">
		<thead>
			<tr>
				<th>{{ __('#') }}</th>
				<th>{{ __('Contravaloare') }}</th>
				<th>{{ __('Data limita') }}</th>
				<th>{{ __('Data platii') }}</th>
				<th>{{ __('Explicatii') }}</th>
				<th>{{ __('Validare plata') }}</th>
			</tr>
		</thead>
		<tbody>
			@for($i = 0 ; $i < 4 ; $i++)
				@php $rata = $item->rateContract->isEmpty() ? '' : ($item->rateContract[$i] ?? '') @endphp
				<tr>
					<td>{{ $i + 1 }}</td>
					<td>
						<x-dynamic-component :component="'ofertare.fields.edit.numeric'"
							name="rate[{{ $i }}][contravaloare]"
							:required="true"
						    :value="old('rate.'.$i.'.contravaloare', $rata['contravaloare'] ?? '')"
						    :form="false"
						/>
					</td>
					<td>
						<x-dynamic-component :component="'ofertare.fields.edit.date'"
							name="rate[{{ $i }}][data_limita]"
							:required="true"
						    :value="old('rate.'.$i.'.data_limita', $rata['data_limita'] ?? '')"
						    :form="false"
						/>
					</td>
					<td>
						<x-dynamic-component :component="'ofertare.fields.edit.date'"
							name="rate[{{ $i }}][data_platii]"
							:required="true"
						    :value="old('rate.'.$i.'.data_platii', $rata['data_platii'] ?? '')"
						    :form="false"
						/>
					</td>
					<td>
						<x-dynamic-component :component="'ofertare.fields.edit.textarea'"
							name="rate[{{ $i }}][explicatii]"
							:rows="2"
							:required="true"
						    :value="old('rate.'.$i.'.explicatii', $rata['explicatii'] ?? '')"
						    :form="false"
						/>
					</td>
					<td>
						<x-dynamic-component component="ofertare.fields.edit.checkbox"
							name="rate[{{ $i }}][validare_plata]"
						    :checked="old('rate.'.$i.'.validare_plata', $rata['validare_plata'] ?? '') == 1"
						    :value="1"
						    :form="false"
						/>
					</td>
				</tr>
			@endfor
		</tbody>
	</table>
    {{-- <p><b>* {{ __('Atentie! Daca nu este completat campul "Judet domiciliu", nu se poate alege automat un responsabil.') }}</b></p> --}}
{{-- </form> --}}
