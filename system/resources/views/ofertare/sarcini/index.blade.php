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
				<th>{{ __('Titlu') }}</th>
				<th>{{ __('De la') }}</th>
				<th>{{ __('Catre') }}</th>
				<th>{{ __('Tip') }}</th>
				<th>{{ __('Status') }}</th>
				<th>{{ __('Judet imobil') }}</th>
				<th>{{ __('Data primire') }}</th>
				<th>{{ __('Data finalizare') }}</th>
				<th>{{ __('Optiuni') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr id="item_{{ $item->id }}">
					<td>{{ $item->id }}</td>
					<td>{{ $item->title }}</td>
					<td>{{ $item->nume_from_complet }}</td>
					<td>{{ $item->nume_to_complet }}</td>
					<td>{{ $types[$item->type] }}</td>
					<td>{{ $stats[$item->status] }}</td>
					<td>{{ $item->nume_judet_imobil }}</td>
					<td>{{ $item->created_at }}</td>
					<td>{{ $item->finished_at }}</td>
					<td>
						@if($item->status == 0 && auth()->user()->id == $item->to_id || $item->type == 1 && $item->status == 1 && auth()->user()->id == $item->from_id)
							<a href="{{ route('ofertare.sarcini.check', $item->id) }}" class="btn btn-sm green">
								<i class="fa fa-check"></i>
							</a>
						@endif
						<a href="{{ route('ofertare.sarcini.show', $item->id) }}" class="btn btn-sm purple">
							<i class="fa fa-eye"></i>
						</a>
						@if(auth()->user()->can('sarcini.edit'))
							<a href="{{ route('ofertare.sarcini.edit', $item->id) }}" class="btn btn-sm blue">
								<i class="fa fa-edit"></i>
							</a>
						@endif
						@if(auth()->user()->can('sarcini.delete'))
							<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.sarcini.delete', $item->id) }}" class="btn btn-sm red">
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
