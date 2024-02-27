@extends('ofertare.layouts.app')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
	<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>{{ __('Formular') }}</th>
			<th>{{ __('Data Montare Panouri') }}</th>
			<th>{{ __('Data Montare Invertoare + PIF') }}</th>
			<th>{{ __('Echipa') }}</th>
			<th>{{ __('Status') }}</th>
			<th width="100"></th>
		</tr>
	</thead>
	<tbody>
		@foreach($items as $item)
			<tr id="item_{{ $item->id }}">
				<td>{{ $item->formular_id }}</td>
				<td>{{ $item->data_montare_panouri }}</td>
				<td>{{ $item->data_montare_invertor_pif }}</td>
				<td>{!! $item->listaEchipa() !!}</td>
				<td>{{ $item->status_text }}</td>
				<td>
					@if($item->montaj_id)
						<a href="{{ route('ofertare.montaje.edit', $item->montaj_id) }}" class="btn btn-sm blue">
							<i class="fa fa-edit"></i>
						</a>
					@else
						<a href="{{ route('ofertare.montaje.create', $item->id) }}" class="btn btn-sm blue">
							<i class="fa fa-plus"></i>
						</a>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection