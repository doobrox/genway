@extends('ofertare.layouts.app')
@section('styles')
<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css">
<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2-bootstrap.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('scripts')
<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		if ($('.date-picker').length) {
			if ($().datepicker) {

				$('.date-picker').datepicker({
					// format: "dd/mm/yyyy",
					format: "yyyy-mm-dd",
					dateFormat: "yyyy-mm-dd",
					language: 'ro',
					autoclose: true,
				});
			}
		}
		if (typeof jQuery === "function" && typeof $ === "function") { 
        let input = $('.select2-ajax');
        if(input.length) {
	        let dataset = input.data();
		        input.select2({
		            dropdownAutoWidth: true,
		            dropdownParent: input.closest('.select2-container'),
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
		    }
		}
		$('.add_item').click(function() {
			let container = $(this).closest('.items-container');
			let i = container ? container.data('items') : 0;
			let txt = '';
			txt += '<div class="row">';
				txt += '<div class="col-md-6">';
				txt += '<select name="echipa['+i+'][user_id]" class="form-control">';
					@foreach($tehnicieni as $tehnician)
						txt += '<option value="{{ $tehnician->id }}">{{ $tehnician->nume }} {{ $tehnician->prenume }}</option>';
					@endforeach
				txt += '</select>';
				txt += '</div>';
				txt += '<div class="col-md-6">';
					txt += '<input type="text" class="form-control" name="echipa['+i+'][procent]" value="" placeholder="{{ __('Procent') }}">';
				txt += '</div>';
				txt += '<div class="col-xs-12 mt-3"><label class="m-0">';
					txt += '<input type="radio" name="lider" value="'+i+'"> <span>{{ __('Lider de echipa') }}</span>';
				txt += '</label></div>';
				txt += '<div class="col-xs-12 mt-3">';
					txt += '<button type="button" class="btn btn-danger img-rounded delete_item" onclick="this.closest(\'.row\').remove()"><b style="padding-right: 5px">-</b> {{ __('Sterge') }}</button>';
				txt += '</div>';
				txt += '<hr class="col-xs-12">';
			txt += '</div>';
			$(txt).insertBefore(this);
			if(container) {container.data('items', ++i);}
		});
		$('#sabloane_echipa').on('change', function() {
			if(val = $(this).val()) {
				let dataset = $(this).data();
			    $.post({
			        url: dataset['url']+'/'+val,
			        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			        success: function(data) {
			        	let container = $('[data-echipa]');
			        	if(container.length && data.length) {
			        		data = $.parseJSON( data );
			        		container.find('div.row').remove();
			        		container.data('items', 0);
			        		data.forEach(function(item, index) {
			        			$('.add_item').trigger('click');
			        			let prev = $('.add_item').prev();
			        			prev.find('select').val(item.id_user);
			        			prev.find('input').val(item.procent);
			        		});
			        	}
			        }
			    });
			}
		});
	});
</script>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ isset($item) ? __('Editare echipa programare') : __('Adauga echipa programare') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ $save_route }}" class="form-horizontal" method="post" id="form-programare">
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

				@if($formular)
					<div class="container" style="max-width: 100%; padding: 0;">
						<table class="table table-advance">
							<thead>
								<tr>
									<th>#</th>
									<th>{{ __('Nume') }}</th>
									<th>{{ __('Prenume') }}</th>
									<th>{{ __('CNP') }}</th>
									<th>{{ __('Judet Imobil') }}</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{ $formular->id }}</td>
									<td>{{ $formular->nume }}</td>
									<td>{{ $formular->prenume }}</td>
									<td>{{ $formular->cnp }}</td>
									<td>{{ $formular->judetImobil->nume ?? '' }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				@else
					<div class="form-group">
						<label class="col-md-3 control-label">{{ __('Numar formular AFM') }}</label>
						<div class="col-md-7 select2-container">
							<select name="formular_id" class="form-control select2-ajax"
								data-url="{{ route('ofertare.programari.formulare', $item->an ?? $an) }}"
								data-placeholder="{{ __('Cauta numar formular') }}"
								>
								@if(old('formular_id', $item->formular_id ?? ''))
									<option value="{{ old('formular_id', $item->formular_id ?? '') }}" selected> {{ old('formular_id', $item->formular_id ?? '') }}
									</option>
								@endif
							</select>
						</div>
					</div>
				@endif
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('Data montare panouri') }}</label>
					<div class="col-md-7">
						<input type="text" class="form-control date-picker" 
							name="data_montare_panouri" 
							value="{{ \Carbon\Carbon::parse(old('data_montare_panouri', $formular->data_montare_panouri ?? ''))->format('Y-m-d') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('Data monare invertor + PIF') }}</label>
					<div class="col-md-7">
						<input type="text" class="form-control date-picker" 
							name="data_montare_invertor_pif" 
							value="{{ \Carbon\Carbon::parse(old('data_montare_invertor_pif', $formular->data_montare_invertor_pif ?? ''))->format('Y-m-d') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('Echipa tehnicieni') }}</label>
					<div class="col-md-7">
						<select id="sabloane_echipa" name="sabloane_echipa" class="form-control" data-url="{{ route('ofertare.programari.echipa') }}">
							<option value="">{{ __('Selecteaza sablon echipa') }}</option>
							@foreach($sabloane as $sablon)
								<option value="{{ $sablon->id }}" @selected($sablon->id == old('sabloane_echipa'))>{{ $sablon->nume }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					@php $team = old('echipa', $echipa ?? [1]); unset($team['lider']); @endphp
					<div class="col-md-7 items-container" data-items="{{ $team ? max(array_keys($team)) + 1 : 1 }}" data-echipa="1">
						@foreach($team as $i => $member)
							<div class="row">
								<div class="col-md-6">
									<select name="echipa[{{ $i }}][user_id]" class="form-control">
										@forelse($tehnicieni as $tehnician)
											<option value="{{ $tehnician->id }}" 
												@selected($tehnician->id == old('echipa.'.$i.'.user_id', $member['user_id'] ?? ''))
												> {{ $tehnician->nume }} {{ $tehnician->prenume }}
											</option>
										@empty
											<option value="">{{ __('Nu exista tehnicieni valabili') }}</option>
										@endforelse
									</select>
								</div>
								<div class="col-md-6">
									<input type="text" class="form-control" 
										name="echipa[{{ $i }}][procent]" 
										value="{{ old('echipa.'.$i.'.procent', $member['procent'] ?? '') }}" 
										placeholder="Procent">
								</div>
								<div class="col-md-12 mt-3">
									<label class="m-0">
										<input type="radio" 
											name="lider" 
											@checked(old('lider', $member['lider'] ?? '') == $i || isset($member['lider']))
											value="{{ $i }}">
										<span>{{ __('Lider de echipa') }}</span>
									</label>
								</div>
								<div class="col-md-12 mt-3">
									<button type="button" class="btn btn-danger img-rounded delete_item" onclick="this.closest('.row').remove()"><b style="padding-right: 5px">-</b> {{ __('Sterge') }}</button>
								</div>
								<hr class="col-xs-12">
							</div>
						@endforeach
						<button type="button" class="btn btn-success img-rounded add_item control-label"><b style="padding-right: 5px">+</b> {{ __('Adauga') }}</button>
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