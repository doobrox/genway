@extends('ofertare.layouts.app')

@section('pre-styles')
	<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('styles')
	@livewireStyles
	<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2-bootstrap.min.css"  rel="stylesheet" type="text/css">
	<style>
		.vizualizare-sarcina p {
			margin: 10px 0;
		}
	</style>
@endsection

@section('content')
<div class="portlet light bordered vizualizare-sarcina">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ $item->title }}</span>
		</div>
	</div>
	<div class="portlet-body form">
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
			<p><b>{{ __('Descriere') }}:</b></p>
			{{ $item->description }}
			<br><br>
			<p><b>{{ __('An formular') }}:</b> {{ $item->year }}</p>
			<p><b>{{ __('De la') }}:</b> {{ $item->nume_from_complet }}</p>
			<p><b>{{ __('Catre') }}:</b> {{ $item->nume_to_complet }}</p>
            @if($item->formular_id)
			    <p><b>{{ __('Formular') }}:</b> <a href="https://www.old.genway.ro/ofertare/afm/index/{{ $item->year }}?id={{ $item->formular_id }}" target="_blank">#{{ $item->formular_id }}</a></p>
			@endif

            <p><b>{{ __('Nume') }}:</b> {{ $afm->nume }} {{ $afm->prenume }}</p>
			<p><b>{{ __('Telefon') }}:</b> {{ $afm->telefon }}</p>
            @if($item->formular_id)
                <p><b>{{ __('Judet imobil') }}:</b> {{ $afm->nume_judet_imobil }}</p>
                <p><b>{{ __('Localitate imobil') }}:</b> {{ $afm->nume_localitate_imobil }}</p>
			@else
                <p><b>{{ __('Judet imobil') }}:</b> {{ $item->nume_judet_imobil }}</p>
                <p><b>{{ __('Localitate imobil') }}:</b> {{ $item->nume_localitate_imobil }}</p>
			@endif
			<p><b>{{ __('Strada imobil') }}:</b> {{ $afm->strada_imobil }}</p>
			<p><b>{{ __('Numar imobil') }}:</b> {{ $afm->numar_imobil }}</p>
			<p><b>{{ __('Marca invertor') }}:</b> {{ $afm->marca_invertor }}</p>
			<p><b>{{ __('Tip sarcina') }}:</b> {{ $types[$item->type] }}</p>
			<p><b>{{ __('Status') }}:</b> {{ $stats[$item->status] }}</p>
			<p><b>{{ __('Data creare') }}:</b> {{ $item->created_at ?? '-' }}</p>
			<p><b>{{ __('Data finalizare') }}:</b> {{ $item->finish_date ?? '-' }}</p>
			<hr>
			@if($item->fisiere && $item->fisiere->isNotEmpty())
				<p><b>{{ __('Atasari') }}:</b></p>
				<ul>
					@foreach($item->fisiere as $file)
						<li><a href="{{ route('ofertare.aws.get', $file['path'].'/'.$file['name']) }}" download>{{ $file['name'] }}</a></li>
					@endforeach
				</ul>
				<hr>
			@endif
			@if(auth()->user()->can('sarcini.delete'))
				<a href="javascript:void(0)" onclick="this.querySelector('form').submit();" class="btn red">{{ __('Sterge sarcina') }}
            		<form method="post" action="{{ route('ofertare.sarcini.delete', $item->id) }}">
						@csrf
						@method('DELETE')
						<input type="hidden" name="redirect_url" value="{{ route('ofertare.sarcini.browse') }}">
					</form>
            	</a>
			@endif
		</div>
	</div>
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ __('Istoric / Chat') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		@foreach($item->mesaje as $mesaj)
			<div style="background: #dfdfdf;padding: 20px;border-radius: 5px!important;margin-bottom: 10px;">
				<p style="font-size: 12px;">{{ $mesaj->created_at }}</p>
				{!! $mesaj->description !!}
				@if($mesaj->fisiere && $mesaj->fisiere->isNotEmpty())
					<hr>
					<p><b>{{ __('Atasari') }}:</b></p>
					<ul>
						@foreach($mesaj->fisiere as $file)
							<li><a href="{{ route('ofertare.aws.get', $file['path'].'/'.$file['name']) }}" download>{{ $file['name'] }}</a></li>
						@endforeach
					</ul>
				@endif
				<p style="margin-top: 10px;"><b>- {{ $mesaj->nume_user_complet }} -</b></p>
			</div>
		@endforeach
		@if(auth()->user()->can('sarcini.edit'))
			<form action="{{ route('ofertare.sarcini.mesaj.store', $item) }}" class="form-horizontal" method="post" id="form-afm" data-year="{{ $section }}" enctype="multipart/form-data">
				@csrf
				<div class="form-body">
					<x-ofertare.fields.edit.textarea
						:label="__('Descriere')"
						name="description"
					    :value="old('description')"
					    rows="6"
					    required
					/>
					<x-ofertare.fields.edit.file
						:label="__('Atasari')"
						name="attachments[]"
					    multiple="true"
					/>
				</div>
				<div class="margiv-top-10">
					<button type="submit" class="btn green">{{ __('Adauga mesaj') }}</button>
				</div>
			</form>
		@endif
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
						format: "dd/mm/yyyy",
						dateFormat: "yy-mm-dd",
						language: 'ro',
						autoclose: true,
					});
				}
			}

			if (typeof jQuery === "function" && typeof $ === "function") {

    		    let selects_db = $('.select-db');
    		    if (selects_db.length) {
    		    	selects_db.each(function(index, input) {
    		    		$.ajax({
					        url: $(input).data('url'),
					        success: function(msg){
					        	let val = $(input).data('value');
					            let obj = jQuery.parseJSON( msg );
					            finalTxt = "<option value=''>- Selecteaza -</option>";
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

    		            // daca exista valoare in camp, o preluam pe cea completa din camp
    		            if(val = $input.val()) {
    		            	$.ajax({
						        url: $(input).data('url')+"/"+val,
						        method: 'POST',
    		                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    		                    dataType: 'json',
						        success: function(msg){
						            finalTxt = "<option value='"+ msg.id +"'selected'>"+ msg.text +"</option>";
						            $(input).html( finalTxt );
						        }
						    });
    		            }

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
    		                            obj.text = obj.text || obj.nume || obj.id;
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
		});
	</script>
@endsection
