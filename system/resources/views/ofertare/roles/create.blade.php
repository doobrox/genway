@extends('ofertare.layouts.app')
@push('styles')
@endpush

@section('scripts')
<script type="text/javascript">
	function all_checked(section) {
		let inputs = $('[data-be-checked="'+section+'"]');
		if(inputs.length) {
	        let check = true;
	        inputs.each(function() {
	            if(this.checked != true) { check = false; }
	        });
	        $('[data-check-all="'+section+'"]').prop('checked', check);
	    }
    }
    $('[data-check-all]').each(function() {
        all_checked($(this).data('check-all'));
    });
    
    $('[data-check-all]').click(function(event) {   // check toate domeniile
    	let section = $(this).data('check-all');
        if(this.checked) {
            // Iterate each checkbox
            $('[data-be-checked="'+section+'"]').each(function() {
                this.checked = true;                        
            });
        } else {
            $('[data-be-checked="'+section+'"]').each(function() {
                this.checked = false;                       
            });
        }
    }); 
    $('[data-be-checked]').click(function(event) {   // check toate domeniile
    	let section = $(this).data('be-checked');
        if(!this.checked) { 
            $('[data-check-all="'+section+'"]').prop('checked', false); 
            $('#check-all').prop('checked', false);
        } else {
            all_checked(section);
        }
    }); 
</script>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ __('Editare rol') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ isset($item) ? route('ofertare.roles.update', $item) : route('ofertare.roles.store') }}" 
			class="form-horizontal" 
			method="post" id="form-role">
			@csrf
			@isset($item)
				@method('PATCH')
			@endisset
			<div class="form-body">
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
				<div class="form-group">
					<label class="col-md-3 control-label">Nume rol</label>
					<div class="col-md-7">
						<input type="text" class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Permisiuni</label>
					<div class="col-md-7">
						<div>
							<label>
								<input type="checkbox" id="check-all" value=""
									onclick="$('[name=\'permissions[]\']').prop('checked', this.checked)"> 
								<span>{{ __('Toate permisiunile') }}</span>
							</label>
						</div>
						@foreach($sections as $section => $permissions)
							<div class="clearfix"></div>
							<h5><b>{{ __('Sectiunea') }} {{ strtolower(str_replace('_', ' ', $section)) }}</b></h5>
							@foreach($permissions as $name => $id)
    							<x-ofertare.permission-section 
    								:permissions="$itemPermission[$section] ?? []" 
    								:section="$section" 
    								:name="$name" 
    								:id="$id" />
							@endforeach
						@endforeach
					</div>
				</div>
				<div class="margiv-top-10">
					<button type="submit" class="btn green">{{ isset($item) ? __('Actualizeaza') : __('Adauga') }}</button>
				</div>
			</div>
		</form>

		{{-- <form class="form-horizontal" action="{{ route('ofertare.aws.store') }}" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<label class="col-md-3 control-label">Fisier</label>
				<div class="col-md-7">
					<input type="file" class="form-control" name="file">
				</div>
			</div>
			<div class="margiv-top-10">
				<button type="submit" class="btn green">{{ __('Incarca') }}</button>
			</div>
		</form> --}}
	</div>
</div>
@endsection