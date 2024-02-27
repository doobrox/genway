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
				<th>{{ __('Nume') }}</th>
				<th>{{ __('Subiect') }}</th>
				<th style="width:600px;">{{ __('Continut') }}</th>
				<th>{{ __('Optiuni') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr id="item_{{ $item->id }}">
					<td>{{ $item->id }}</td>
					<td>{{ $item->nume }}</td>
					<td>{{ $item->subiect }}</td>
					<td>{{ Illuminate\Support\Str::limit(str_replace("&nbsp;", " ", strip_tags($item->continut)), 200) }}</td>
					<td>
						@if(auth()->user()->can('sabloane_documente.view'))
							<a href="{{ route('ofertare.sabloane_documente.show', $item->id) }}" class="btn btn-sm purple" target="_blank" style="min-width: 34px; margin-top: 5px;">
								<i class="fa fa-eye"></i>
							</a>
						@endif
						@if(auth()->user()->can('sabloane_documente.edit'))
							<a href="{{ route('ofertare.sabloane_documente.edit', $item->id) }}" class="btn btn-sm blue" style="min-width: 34px; margin-top: 5px;">
								<i class="fa fa-edit"></i>
							</a>
							@if($item->pagebuilder)
								<a href="{{ route('ofertare.sabloane_documente.editor', $item->id) }}" class="btn btn-sm blue" style="min-width: 34px; margin-top: 5px;">
									<i class="fa fa-paint-brush"></i>
								</a>
							@endif
						@endif
						@if(auth()->user()->can('sabloane_documente.delete'))
							<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.sabloane_documente.delete', $item->id) }}" class="btn btn-sm red" style="min-width: 34px; margin-top: 5px;">
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
