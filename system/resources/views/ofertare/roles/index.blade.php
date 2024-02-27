@extends('ofertare.layouts.app')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
	<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Nume</th>
			<th width="100"></th>
		</tr>
	</thead>
	<tbody>
		@foreach($roles as $role)
			<tr id="role_{{ $role->id }}">
				<td>{{ $role->name }}</td>
				<td>
					<a href="{{ route('ofertare.roles.edit', $role->id) }}" class="btn btn-sm blue">
						<i class="fa fa-edit"></i>
					</a>
					<a href="javascript:void(0)" onclick="confirmDelete(this)" data-url="{{ route('ofertare.roles.delete', $role->id) }}" class="btn btn-sm red">
						<i class="fa fa-trash-o"></i>
					</a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection