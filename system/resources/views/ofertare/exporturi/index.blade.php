@extends('ofertare.layouts.app')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>{{ __('Nume') }}</th>
				<th>{{ __('Status') }}</th>
				<th>{{ __('Data') }}</th>
				<th width="100"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr id="item_{{ $item->id }}">
					<td>{{ $items->total() - $loop->index - $items->perPage() * ($items->currentPage() - 1) }}</td>
					<td>{{ $item->nume }}</td>
					<td>{{ $item->status_text }}</td>
					<td>{{ $item->created_at }}</td>
					<td>
						@if($item->status == '2')
							<a href="{{ route('ofertare.exporturi.download', $item->nume) }}" class="btn btn-sm blue">
								<i class="fa fa-download"></i>
							</a>
						@endif
						@if($item->status != '1')
							<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.exporturi.delete', $item->nume) }}" class="btn btn-sm red">
								<i class="fa fa-trash-o"></i>
							</a>
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	{{ $items->links() }}
@endsection