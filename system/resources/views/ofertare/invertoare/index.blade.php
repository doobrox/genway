@extends('ofertare.layouts.app')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')

	@if(session()->has('status'))
        <div class="alert alert-success">
			<ul>
				<li>{{ session('status') }}</li>
			</ul>
		</div>
    @endif
	@if($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{ __('#') }}</th>
				<th>{{ __('Cod') }}</th>
				<th>{{ __('Putere') }}</th>
				<th>{{ __('Marca') }}</th>
				<th>{{ __('Tip') }}</th>
				<th>{{ __('Grid') }}</th>
				<th>{{ __('Contor') }}</th>
				<th>{{ __('Optiuni') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr id="item_{{ $item->id }}">
					<td>{{ $item->id }}</td>
					<td>{{ $item->cod }}</td>
					<td>{{ $item->putere }}</td>
					<td>{{ $item->marca }}</td>
					<td>{{ $item->tip }}</td>
					<td>{{ $item->grid }}</td>
					<td>{{ $item->contor }}</td>
					<td>
						@if(auth()->user()->can('invertoare.edit'))
							<a href="{{ route('ofertare.invertoare.edit', $item->id) }}" class="btn btn-sm blue">
								<i class="fa fa-edit"></i>
							</a>
						@endif
						@if(auth()->user()->can('invertoare.delete'))
							<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.invertoare.delete', $item->id) }}" class="btn btn-sm red">
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
