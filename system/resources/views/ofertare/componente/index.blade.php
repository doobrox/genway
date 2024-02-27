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
    <div class="table-responsive table-scrollable" style="overflow: auto;max-height: 72vh;">
		<table class="table table-bordered table-advance table-hover">
			<thead>
				<tr style="position: sticky; top:0; z-index:9">
					<th>{{ __('#') }}</th>
					@foreach($columns as $column => $name)
						<th>{{ $name }}</th>
					@endforeach
					<th>{{ __('Optiuni') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr id="item_{{ $item->id }}">
						<td>{{ $item->id }}</td>
						@foreach($columns as $column => $name)
							<td>
								<x-dynamic-component :component="'ofertare.fields.view.textarea'"
	                                :item="$item"
	                                :column="(object)['nume' => $column]"
	                                :count="40"
	                            />
							</td>
						@endforeach
						<td>
							@if($can_edit)
								<a href="{{ route($edit_route_text, $item->id) }}" class="btn btn-sm blue">
									<i class="fa fa-edit"></i>
								</a>
							@endif
							@if($can_delete)
								<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route($delete_route_text, $item->id) }}" class="btn btn-sm red">
									<i class="fa fa-trash-o"></i>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	{{ $items->links() }}
@endsection
