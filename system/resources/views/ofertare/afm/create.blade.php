@extends('ofertare.layouts.app')

@section('pre-styles')
	<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('styles')
	@livewireStyles
	{{-- <link rel="stylesheet" href="{{ asset('css/tailwind/app.css') }}" type="text/css"> --}}
	<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2-bootstrap.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ isset($item) ? __('Editare formular afm') : __('Adauga formular afm') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ $save_route }}" class="form-horizontal" method="post" id="form-afm" data-section="{{ $section }}" enctype="multipart/form-data">
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
				@forelse($columns as $column)
					@if(is_null($column) || $column->nume == "id")
						@continue
					@endif
					{{-- @if($column->tip == 5)
						<x-dynamic-component :component="'ofertare.fields.'.$types[$column->tip]"
							:label="$column->titlu"
							:name="$column->nume"
							:options="$column->default_values"
						    :data_url="$column->data_url"
						    :value="old($column->nume, $item[$column->nume] ?? '')"
						/>
					@else --}}
					@if($column->tip == 9 || $column->tip == 8)
						@continue
					@else
						<x-dynamic-component :component="'ofertare.fields.edit.'.$types[$column->tip]"
							:label="$column->titlu"
							:name="$column->nume"
							:options="$column->default_values"
						    :data_url="$column->data_url"
						    :value="old($column->nume, $item[$column->nume] ?? '')"
						    :multiple="$column->rules['multiple'] ?? null"
						/>
					@endif
				@empty
					{{ __('Nici o coloana selectata in sablonul implicit') }}
				@endforelse
				<div class="margiv-top-10">
					<button type="submit" class="btn green">{{ isset($item) ? __('Actualizeaza') : __('Adauga') }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('scripts')
	@livewireScripts
	<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			if ($('.date-picker').length) {
				if ($().datepicker) {
					$('.date-picker').datepicker({
						format: "yyyy-mm-dd",
						dateFormat: "yyyy-mm-dd",
						language: 'ro',
						autoclose: true,
					});
				}
			}
			if (typeof jQuery === "function" && typeof $ === "function") {

				$('select[name="judet_domiciliu"]').on( "change", function() {
					if($('select[name="localitate_domiciliu"]').length > 0) {
						let jud = $(this).val();
						$.ajax({
					        url: '{{ route('localitati.html') }}/'+jud,
		                    method: 'POST',
		                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					        success: function(msg){
					            $('select[name="localitate_domiciliu"]').html( msg );
					        }
					    });
					}
				});
				$('select[name="judet_imobil"]').on( "change", function() {
					if($('select[name="localitate_imobil"]').length > 0) {
						let jud = $(this).val();
						$.ajax({
					        url: '{{ route('localitati.html') }}/'+jud,
		                    method: 'POST',
		                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					        success: function(msg){
					            $('select[name="localitate_imobil"]').html( msg );
					        }
					    });
					}
				});

    		    let selects_db = $('select[data-url^="http"]');
    		    if (selects_db.length) {
    		    	selects_db.each(function(index, input) {
    		    		$.ajax({
					        url: $(input).data('url'),
					        success: function(msg){
					        	let val = $(input).data('value')
					            var obj = jQuery.parseJSON( msg );
					            finalTxt = "<option value=''></option>";
					            $.each(obj, function(i, item){
					            	let selected = '';
					            	if(val == i){
					            		selected = 'selected';
					            	}
					                finalTxt += "<option value='"+ i +"' "+ selected +">"+ item +"</option>";
					            });
					            $(input).html( finalTxt );
					        }
					    });
    		    	});
    		    }

    		    let select2Inputs = $('.select2-ajax');
    		    if (select2Inputs.length) {
    		        select2Inputs.each(function(index, input) {
    		            let $input = $(input);
    		            let dataset = $input.data();
    		            $input.select2({
    		                dropdownAutoWidth: true,
    		                dropdownParent: $input.closest('.select2-container'),
    		                width: '100%',
    		                allowClear: true,
    		                ajax: {
    		                    url: dataset['url'] ? dataset['url'] : 'javscript:void(0)',
    		                    method: 'POST',
    		                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    		                    dataType: 'json',
    		                    delay: 50,
    		                    data: function (params) {
    		                        return {
    		                            search: params.term, // search term
    		                            page: params.page
    		                        };
    		                    },
    		                    processResults: function (data, params) {
    		                        let result = $.map(data, function (obj) {
    		                            obj.text = obj.text || obj.nume || obj.id; // replace email with the property used for the text
    		                            return obj;
    		                        });
    		                        return {
    		                            results: result
    		                        };
    		                    },
    		                    cache: true
    		                },
    		                placeholder: dataset['placeholder'] ? dataset['placeholder'] : '',
    		                language: 'ro',
    		                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    		                minimumInputLength: dataset['minLength'] ? dataset['minLength'] : 1,
    		            });
    		        });
    		    }
    		}
			$(document).ready(function() {
			  	$(document).on('click', '.add_item', function() {
			  	  	var $fieldsGroup = $('<div class="fields-group"></div>');
			  	  	var $select = $('<select class="form-control" name="sir[]"></select>');
			  	  	for (var i = 1; i <= 30; i++) {
			  	  	  	var $option = $('<option></option>').attr('value', '1x' + i).text('1x' + i);
			  	  	  	if (i === 2) {
			  	  	  		$option.attr('selected', 'selected');
			  	  	  	}
			  	  	  	$select.append($option);
			  	  	}
			  	  	$fieldsGroup.append($select);
			  	  	var $deleteButton = $('<button class="btn btn-danger img-rounded delete_item mt-3"><b style="padding-right: 5px">-</b> Sterge</button>');
			  	  	$fieldsGroup.append($deleteButton);
			  	  	var $hr = $('<hr class="col-xs-12 mt-8" style="margin-bottom: 2rem">');
			  	  	$fieldsGroup.append($hr);
			  	  	$fieldsGroup.appendTo('#fields-container');
			  	});
			  	$(document).on('click', '.delete_item', function() {
			  	  	$(this).closest('.fields-group').remove();
			  	});
			});
		});
	</script>
@endsection
