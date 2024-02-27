@extends('ofertare.layouts.app')

@section('styles')
<style type="text/css">
	.table {
		position: relative;
	}
	.table .first-row th {
		position: sticky;
		top: -1px;
		background: white;
	}
</style>
@endsection

@section('scripts')
<script src="{{ asset('js/utility.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		if ($('.date-picker').length) {
			if ($().datepicker) {

				$('.date-picker').datepicker({
					format: "dd/mm/yyyy",
					dateFormat: "yy-mm-dd",
					language: 'ro',
					autoclose: true,
				});
			}
		}
	});
 
</script>
@endsection

@section('content')
<div class="table-responsive" style="overflow:auto;max-height:72vh;">
	<form id="search_programari"></form>
	<table class="table table-bordered table-hover" style="margin:0">
		<thead>
			<tr class="first-row">
				<th>{{ __('Nume') }}</th>
				<th>{{ __('Prenume') }}</th>
				<th>{{ __('CNP') }}</th>
				<th>{{ __('Judet Imobil') }}</th>
				<th>{{ __('Localitate Imobil') }}</th>
				<th>{{ __('Adresa Imobil') }}</th>
				<th>{{ __('Telefon') }}</th>
				<th>{{ __('Fisier vizita') }}</th>
				<th>{{ __('Schita amplasare panouri') }}</th>
				<th>{{ __('Data Montare Panouri') }}</th>
				<th>{{ __('Data Montare Invertoare + PIF') }}</th>
				{{-- <th>{{ __('Echipa') }}</th> --}}
				<th width="100"></th>
			</tr>
			<tr>
				<th><input type="text" class="form-control" form="search_programari" name="nume" value="{{ old('nume', $search['nume'] ?? '') }}"></th>
				<th><input type="text" class="form-control" form="search_programari" name="prenume" value="{{ old('prenume', $search['prenume'] ?? '') }}"></th>
				<th><input type="text" class="form-control" form="search_programari" name="cnp" value="{{ old('cnp', $search['cnp'] ?? '') }}"></th>
				<th>
					<select name="judet_imobil" class="form-control" form="search_programari" data-url="{{ route('localitati.html') }}" 
						onchange="getLocalitatiInOptionsWithEmpty(this, '#search_localitate_imobil')">
						<option value=""></option>
						@foreach($judete as $judet)
							<option value="{{ $judet->id }}" @selected(old('judet_imobil', $search['judet_imobil'] ?? '') == $judet->id)>{{ $judet->nume }}</option>
						@endforeach
					</select>
				</th>
				<th>
					<select name="localitate_imobil" form="search_programari" id="search_localitate_imobil" class="form-control">
						<option value=""></option>
						@foreach($localitati as $localitate)
							<option value="{{ $localitate->id }}" @selected(old('localitate_imobil', $search['localitate_imobil'] ?? '') == $localitate->id)>{{ $localitate->nume }}</option>
						@endforeach
					</select>
				</th>
				<th><input type="text" class="form-control" form="search_programari" name="adresa_imobil" value="{{ old('adresa_imobil', $search['adresa_imobil'] ?? '') }}"></th>
				<th><input type="text" class="form-control" form="search_programari" name="telefon" value="{{ old('telefon', $search['telefon'] ?? '') }}"></th>
				<th></th>
				<th></th>
				<th><input type="text" class="form-control date-picker" form="search_programari" name="data_montare_panouri" value="{{ old('data_montare_panouri', $search['data_montare_panouri'] ?? '') }}"></th>
				<th><input type="text" class="form-control date-picker" form="search_programari" name="data_montare_invertor_pif" value="{{ old('data_montare_invertor_pif', $search['data_montare_invertor_pif'] ?? '') }}"></th>
				{{-- <th></th> --}}
				<th>
					<button type="submit" form="search_programari" class="btn btn-sm green btn-outline filter-submit margin-bottom">
                        <i class="fa fa-search"></i> Cauta
                    </button>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr id="item_{{ $item->id }}">
					<td>{{ $item->formular->nume }}</td>
					<td>{{ $item->formular->prenume }}</td>
					<td>{{ $item->formular->cnp }}</td>
					<td>{{ $item->formular->judetImobil ? $item->formular->judetImobil->nume : '' }}</td>
					<td>{{ $item->formular->localitateImobil ? $item->formular->localitateImobil->nume : '' }}</td>
					<td>{{ $item->formular->adresa_imobil }}</td>
					<td>{{ $item->formular->telefon }}</td>
					<td>
						@if($item->formular->fisier_vizita)
							<a download href="{{ route('ofertare.programari.file.download', [
								'programare' => $item->id,
								'slug' => 'fisier-vizita',
								'fisier' => $item->formular->fisier_vizita,
								]) }}" title="{{ __('Fisier vizita') }}"><i class="fa fa-download"></i></a>
						@endif
					</td>
					<td>
						@if($item->formular->schita_amplasare_panouri)
							<a download href="{{ route('ofertare.programari.file.download', [
								'programare' => $item->id,
								'slug' => 'schita-amplasare-panouri',
								'fisier' => $item->formular->schita_amplasare_panouri,
								]) }}" title="{{ __('Schita amplasare panouri') }}"><i class="fa fa-download"></i></a>
						@endif
					</td>
					<td>{{ $item->formular->data_montare_panouri }}</td>
					<td>{{ $item->formular->data_montare_invertor_pif }}</td>
					{{-- <td>{!! $item->listaEchipa() !!}</td> --}}
					<td>
						<a href="{{ route('ofertare.programari.edit', $item->id) }}" class="btn btn-sm blue">
							<i class="fa fa-edit"></i>
						</a>
						<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.programari.delete', $item->id) }}" class="btn btn-sm red">
							<i class="fa fa-trash-o"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
{{ $items->links('pagination::bootstrap-4') }}
@endsection