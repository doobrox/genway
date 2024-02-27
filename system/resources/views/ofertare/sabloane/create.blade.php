@extends('ofertare.layouts.app')
@section('styles')
<style type="text/css">
	.order-input {
		width: 45px;
	    height: 28px;
	    padding: 0px 2px;
	    display: inline;
	}
</style>
@endsection

@section('scripts')
<script type="text/javascript">
	function display_order_input(input, display = true) {
		if(display) {
			input.style.display = 'none';
		} else {
			input.style.display = 'inline';
		}
	}

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
    $('[data-be-checked]').click(function(event) {   // check toate domeniile
    	display_order_input(document.querySelector('[name="ordine_coloane['+this.value+']"]'), !this.checked);
    });
</script>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ __('Editare sablon AFM') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ isset($item) ? route('ofertare.sabloane_afm.update', $item) : route('ofertare.sabloane_afm.store') }}" 
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
					<label class="col-md-3 control-label">Nume sablon</label>
					<div class="col-md-7">
						<input type="text" class="form-control" name="nume" value="{{ old('nume', $item['nume'] ?? '') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Implicit</label>
					<div class="col-md-7">
						<label class="control-label">
							<input type="checkbox" name="implicit" value="1"
								@checked(old('implicit', $item['implicit'] ?? null))> 
							<span>{{ __('Da') }}</span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Coloane</label>
					<div class="col-md-7">
						<div class="mb-2">
							<label class="control-label">
								<input type="checkbox" id="check-all" value=""
									onclick="$('[name=\'coloane[]\']').prop('checked', this.checked)"> 
								<span>{{ __('Toate coloanele') }}</span>
							</label>
						</div>
						@foreach($columns as $key => $column)
							<div class="col-md-3 col-xs-6">
								<label>
									<input type="checkbox" name="coloane[]" value="{{ $column }}"
										@checked(in_array($column, old('coloane', $item['coloane'] ?? [])))
										data-be-checked="coloane"> 
									<span>{{ ucfirst(__(str_replace('_', ' ', $column))) }}</span>
									<input type="number" name="ordine_coloane[{{$column}}]" 
										class="form-control order-input" 
										step="0.1" 
										style="display: {{ in_array($column, old('coloane', $item['coloane'] ?? [])) ? 'inline' : 'none' }};" 
										value="{{ old('ordine_coloane.'.$column, $item['ordine_coloane'][$column] ?? 999) }}">
								</label>
							</div>
						@endforeach
					</div>
				</div>
				<div class="margiv-top-10">
					<button type="submit" class="btn green">{{ isset($item) ? __('Actualizeaza') : __('Adauga') }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection