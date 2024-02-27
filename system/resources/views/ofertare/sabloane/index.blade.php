@extends('ofertare.layouts.app')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('buttons-section')
	<a href="{{ route('ofertare.sabloane_afm.copy') }}" class="btn grey-mint">
		<i class="fa fa-clone"></i> {{  __('Copiaza') }}
	</a>
@endsection

@section('content')
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{ __('Nume') }}</th>
				<th>{{ __('Implicit') }}</th>
				<th width="100"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr id="item_{{ $item->id }}">
					<td>{{ $item->nume }}</td>
					<td>
						@if($item->implicit)
							<i class="fa fa-check-square-o"></i>
						@else
							<a href="{{ route('ofertare.sabloane_afm.check', $item->id) }}"><i class="fa fa-square-o"></i></a>
						@endif
					</td>
					<td>
						<a href="{{ route('ofertare.sabloane_afm.edit', $item->id) }}" class="btn btn-sm blue">
							<i class="fa fa-edit"></i>
						</a>
						<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.sabloane_afm.delete', $item->id) }}" @if($item->implicit) data-refresh="1" @endif class="btn btn-sm red">
							<i class="fa fa-trash-o"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection