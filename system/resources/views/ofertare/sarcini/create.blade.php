@extends('ofertare.layouts.app')

@section('pre-styles')
	<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('styles')
	@livewireStyles
	<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2-bootstrap.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ isset($item) ? __('Editare') : __('Adauga') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ $save_route }}" class="form-horizontal" method="post" id="form-afm" data-year="{{ $section }}" enctype="multipart/form-data">
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
				<x-ofertare.fields.edit.text
					:label="__('Titlu')"
					name="title"
				    :value="old('title', $item['title'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.select
					:label="__('Catre')"
					name="to_id"
				    :value="old('to_id', $item['to_id'] ?? '')"
				    :data_url="route('ofertare.sarcini.getUsersOfertare')"
				    class="select-db"
				    required
				/>
				<x-ofertare.fields.edit.textarea
					:label="__('Descriere')"
					name="description"
				    :value="old('description', $item['description'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.file
					:label="__('Atasari')"
					name="attachments[]"
				    multiple="true"
				/>
				<x-ofertare.fields.edit.select
					:label="__('Tip')"
					name="type"
				    :value="old('type', $item['type'] ?? '')"
				    :options="[0 => 'Cu finalizare', 1 => 'Cu intoarcere']"
				    required
				/>
				{{-- <x-ofertare.fields.edit.select
					:label="__('Intoarcere')"
					name="return_id"
				    :value="old('return_id', $item['return_id'] ?? '')"
				    :data_url="route('ofertare.sarcini.getUsersOfertare')"
				    class="select-db"
				    id="return_id"
				/> --}}
				<x-ofertare.fields.edit.select
					:label="__('Status')"
					name="status"
				    :value="old('status', $item['status'] ?? '')"
				    :options="[0 => 'In lucru', 1 => 'Finalizat']"
				    required
				/>
				<x-ofertare.fields.edit.select2
					:label="__('Formular AFM')"
					name="formular_id"
				    :selected_value="old('formular_id', $item['formular_id'] ?? '')"
				    :selected_display_value="old('formular_id', $item['formular_id'] ?? '')"
				    :data_url="route('ofertare.sarcini.searchAfm', $section)"
				/>
				<div class="form-group">
			        <div class="col-md-offset-3 col-md-7">
                        <label for="client_manual" class="control-label">
				            <input type="checkbox" name="client_manual" value="1" id="client_manual"
                                onclick="$('#client_manual_div').toggle()"
                                {{ old('client_manual', $item['client_manual'] ?? 0) == 1 ? 'checked' : '' }}
                            >
                            Client negasit in formulare/cu plata
                        </label>
				    </div>
				</div>
                <div id="client_manual_div" class="{{ old('client_manual', $item['client_manual'] ?? 0) != 1 ? 'dn' : '' }}">
                    <x-ofertare.fields.edit.text
                        :label="__('Nume')"
                        name="client_nume"
                        :value="old('client_nume', $item->info_client->nume ?? '')"
                    />
                    <x-ofertare.fields.edit.text
                        :label="__('Prenume')"
                        name="client_prenume"
                        :value="old('client_prenume', $item->info_client->prenume ?? '')"
                    />
                    <x-ofertare.fields.edit.text
                        :label="__('Telefon')"
                        name="client_telefon"
                        :value="old('client_telefon', $item->info_client->telefon ?? '')"
                    />
                    <x-ofertare.fields.edit.select
                        :label="__('Judet imobil')"
                        name="client_judet_imobil"
                        :data_url="route('ofertare.afm.get.column.db.options', 'judet_imobil')" {{-- Trebuie testat --}}
                        :value="old('client_judet_imobil', $item->info_client->judet_imobil ?? '')"
                        class="select-db"
                    />
                    <x-ofertare.fields.edit.select
                        :label="__('localitate imobil')"
                        name="client_localitate_imobil"
                        :options="['' => 'Alegeti judetul intai']"
                        :value="old('client_localitate_imobil', $item->info_client->localitate_imobil ?? '')"
                    />
                    <x-ofertare.fields.edit.text
                        :label="__('Strada imobil')"
                        name="client_strada_imobil"
                        :value="old('client_strada_imobil', $item->info_client->strada_imobil ?? '')"
                    />
                    <x-ofertare.fields.edit.text
                        :label="__('Numar imobil')"
                        name="client_numar_imobil"
                        :value="old('client_numar_imobil', $item->info_client->numar_imobil ?? '')"
                    />
                    <x-ofertare.fields.edit.select
                        :label="__('Marca invertor')"
                        name="client_marca_invertor"
                        :options="[
                            '' => 'Alegeti marca invertor',
                            'Huawei' => 'Huawei',
                            'Fronius snap' => 'Fronius snap',
                            'Fronius GEN24' => 'Fronius GEN24',
                            'Sungrow' => 'Sungrow',
                        ]"
                        :value="old('client_marca_invertor', $item->info_client->marca_invertor ?? '')"
                    />
                </div>
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
						format: "dd/mm/yyyy",
						dateFormat: "yy-mm-dd",
						language: 'ro',
						autoclose: true,
					});
				}
			}

			if (typeof jQuery === "function" && typeof $ === "function") {

                $('select[name*="judet_imobil"]').on( "change", function() {
					if($('select[name*="localitate_imobil"]').length > 0) {
						let jud = $(this).val();
						$.ajax({
					        url: '{{ route('localitati.all') }}/'+jud,
		                    method: 'POST',
		                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					        success: function(msg){
                                let val = $('select[name*="localitate_imobil"]').data('value');
					            finalTxt = "<option value=''>- Selecteaza -</option>";
					            $.each(msg, function(i, item){
					            	let selected = '';
					            	if(val == item.id){
					            		selected = 'selected';
					            	}
					                finalTxt += "<option value='"+ item.id +"' "+ selected +">"+ item.nume +"</option>";
					            });
					            $('select[name*="localitate_imobil"]').html( finalTxt );
					        }
					    });
					}
				});

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
                                $(input).trigger("change");
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
