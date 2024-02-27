
@extends('ofertare.layouts.app')

@section('styles')
	@livewireStyles
	{{-- <link rel="stylesheet" href="{{ asset('css/tailwind/app.css') }}" type="text/css"> --}}
@endsection

@section('content')
	<div class="table-responsive" style="overflow: auto;max-height: 86vh;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					@foreach($coloane as $coloana)
						<th>{{ $coloana->nume }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
                <tr id="item_{{ $item->id }}">
                    @foreach($coloane as $coloana)
                        <td
                            data-item-id="{{ $item->id }}"
                            data-column-name="{{ $coloana->nume }}"
                            data-edit="{{ $coloana->editare }}"
                            data-type="{{ $coloana->tip }}"
                            data-edit-route="{{ route('ofertare.afm.get.column', [2023, $item->id, $coloana->nume]) }}"
                            data-update-route="{{ route('ofertare.afm.update.column', [2023, $item->id, $coloana->nume]) }}"
                        >
                            @switch($coloana->afisare)
                                @case(1)
                                    <x-dynamic-component :component="'ofertare.columns.view.'.$coloana->nume"
                                        :item="$item"
                                        :column="$coloana"
                                    />
                                    @break
                                @case(null)
                                @default
                                    <x-dynamic-component :component="'ofertare.fields.view.'.$types[$coloana->tip]"
                                        :item="$item"
                                        :column="$coloana"
                                    />
                                    @break
                            @endswitch
                        </td>
                    @endforeach
                </tr>
            </tbody>
            {{-- @dd($coloane) --}}
		</table>
	</div>
    <input type="hidden" class="btn-link p-0 m-0" data-toggle="modal" data-target="#afm-modal"></input>
    <div class="modal fade" id="afm-modal" tabindex="-1" role="dialog" aria-labelledby="afm-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal-label">Modal</h4>
                </div>
                <div class="alert alert-danger hidden text-center" id='error' role="alert"></div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-btn">{{ __('Salveaza') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Inchide') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script type="text/javascript" src="https://www.old.genway.ro/application/views/ofertare/assets/pages/scripts/afm.js?v=20231122"></script> --}}
    <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/js/select2.full.min.js" defer></script>

	@livewireScripts

    <script>

        $('td[data-edit]').on('dblclick', function () {
            const currentTd = $(this);
            const openModal = $('#afm-modal');

            $.ajax({
                url: currentTd.data('edit-route'),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (currentTd.data('edit') == '1') {
                        currentTd.html(data.html);
                        initAll();
                        onBlurHandler(currentTd);
                       
                    } else if (currentTd.data('edit') == '2') {
                        $(openModal).find('.modal-body').html(data.html);
                        $(openModal).modal('show');

                        //saveModalHandler(openModal, currentTd);
                       
                    }
                },
                error: function (xhr, status, error) {
                    // console.error('Erori primite', xhr.responseText);
                }
            });

            
        });

       /* function saveModalHandler(openModal,currentTd) {
            $(openModal).find('.save-btn').on('click', () => {
                const updatedRouteUrl = currentTd.data('update-route');
                const allInputsFromModal = $(openModal).find(':input');
                const formInModal = $(openModal).find('form');
                const formAction = formInModal.attr('action') || updatedRouteUrl;

                const formData = new FormData();
        
                allInputsFromModal.each(function () {
                    const inputName = $(this).attr('name'); 
                    const inputValue = $(this).val();                                
                    formData.append(inputName, inputValue);
                });
                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    dataType: 'json',
                    success: function (data) {     
                        if(data.reload == true) {
                                // pentru Andrei
                        } else {
                            if(data.label == null) {
                                currentTd.html(data.value);
                            } else {
                                currentTd.html(data.label);
                            }
                        }
                        allInputsFromModal.val('');
                        $('.fields-group').remove();
                        $(openModal).modal('hide');
                        
                    },
                    error: function (xhr, status, error) {
                        
                        let errorMessage = 'A aparut o eroare. Va rugam sa incercati din nou mai tarziu.';
                        if (xhr.status === 401) {
                            errorMessage = 'Nu aveti permisiunea necesara pentru aceasta actiune.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Resursa nu a fost gasita.';
                        }

                        $('#error').text(errorMessage).removeClass('hidden').addClass('show');

                        setTimeout(function () {
                            $('#error').text(errorMessage).addClass('hidden');
                        }, 3000); 
                    }
                });                             
            })
        }*/

        function onBlurHandler(currentTd) {
            let input = currentTd.find(':input');
            //let action = input[0].classList.contains('date-picker') || input[0].type === 'file' ? 'change' : 'blur';

            //let modal = input[0].classList.contains('modal-body') ? 'click' : 'blur';


            let action;

            if (input[0].classList.contains('date-picker')) {
                action = 'change';
            } else if (input[0].type === 'file') {
                action = 'change';
            } else if ($(input[0]).closest('#afm-modal').find('.save-btn').length > 0 && $(input[0]).data('edit') == '2') {
                action = 'click';
                alert(action);

            } else {
                action = 'blur';
            }






            input[0].style.minWidth = '100px';
            let updateRoute = currentTd.data('update-route');
            let formInModal = $('#afm-modal').find('form');
            let formAction = formInModal.attr('action') || updateRoute;


            input.on(action, function () {
                const form = new FormData();
                

                if($(this).attr('type') === 'file') {
                    let files = this.files;
                    form.append(this.name, files[0]); 
                } else {
                    form.append(this.name, this.value); 
                }

                $.ajax({
                    url: updateRoute,
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        if(data.reload == true) {
                            // reload
                            location.reload();
                        } else {
                            currentTd.html(data.label || data.value);
                        }

                        if (data.errors) {
                            for (let error in data.errors) {
                                if (data.errors.hasOwnProperty(error)) {
                                    let displayError = data.errors[error];
                                    //console.log("err => ", error, ":", displayError);
                                    currentTd.html(displayError);
                                }
                            }
                        }
                    },
                    error: function (xhr, status, error) {

                        let errorMessage = 'A aparut o eroare. Va rugam sa incercati din nou mai tarziu.';
                        if (xhr.status === 401) {
                            errorMessage = 'Nu aveti permisiunea necesara pentru aceasta actiune.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Resursa nu a fost gasita.';
                        }

                        $('#error').text(errorMessage).removeClass('hidden').addClass('show');

                        setTimeout(function () {
                            $('#error').text(errorMessage).addClass('hidden');
                        }, 3000); 
                    }
                });

            }
        )}

        /*function onBlurHandler(currentTd) {
            let input = currentTd.find(':input');
            let action = input[0].classList.contains('date-picker') ? 'change' : 'blur';
            input[0].style.minWidth = '100px';
            input.on(action, function () {
                const form = new FormData();

                if($(this).attr('type') === 'file') {
                    let files = this.files;
                    form.append(this.name, files[0]); 
                } else {
                    form.append(this.name, this.value); 
                }

                $.ajax({
                    url: currentTd.data('update-route'),
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        if(data.reload == true) {
                            // reload
                            location.reload();
                        } else {
                            currentTd.html(data.label || data.value);
                        }
                    },
                    error: function (xhr, status, error) {
                        // console.error('Error: ', xhr.responseText);
                    }
                });

            // $('td[data-edit="1"] .form-control').blur(function () {
                // if ($(this).attr('type') === 'file') {
                
                //     const fileInput = this;

                //     var fd = new FormData();
                //     var files = fileInput.files;

                //     if (files.length > 0) {
                //         fd.append(columnName, files[0]); 

                //         $.ajax({
                //             url: updatedRouteUrl,
                //             type: 'POST',
                //             data: fd,
                //             contentType: false,
                //             processData: false,
                //             dataType: 'json',
                //             success: function (data) {
                //                 if(data.reload == true) {
                //                     // reload
                //                     location.reload();
                //                 } else {
                //                     if(data.label == null) {
                //                         currentTd.html( data.value);
                //                     }else {
                //                         currentTd.html( data.label);
                                        
                //                     }
                //                 }
                //             },
                //             error: function (xhr, status, error) {
                //                 // console.error('Error: ', xhr.responseText);
                //             }
                //         });
                //     }
                // } else {
                //     const newValue = $(this).val();

                //     $.ajax({
                //         url: updatedRouteUrl,
                //         type: 'POST',
                //         data: {
                //             [columnName]: newValue,
                //         },
                //         dataType: 'json',
                //         success: function (data) {
                //             if(data.reload == true) {
                //                     // reload
                //                 location.reload();
                //             }else {
                //                     if(data.label == null) {
                //                         currentTd.html( data.value);
                //                     }else {
                //                         currentTd.html( data.label);  
                //                      }
                //             }
                //         },
                //         error: function (xhr, status, error) {
                //             // console.error('Error: ', xhr.responseText);
                //         }
                //     });
                // }
            });
        }*/

        function addSirPanouri(parent) {
            let sir = '<div class="form-group">';
                    sir += '<select class="form-control" name="siruri_panouri[]">';
                        for(let j = 1; j <= 30; j++) {
                            sir += '<option value="1x'+j+'">1x'+j+'</option>';
                        }
                    sir += '</select>';
                    sir += '<button class="btn btn-danger img-rounded delete-item mt-3" onclick="this.closest(\'.form-group\').remove()">';
                        sir += '<b style="padding-right: 5px">-</b> Sterge';
                    sir += '</button>';
                    sir += '<hr class="mt-8" style="margin-bottom: 2rem">';
                sir += '</div>';
            parent.insertAdjacentHTML('beforeend', sir);
        }
    
        function initAll() {
            initDate();
            initSelect();
            initSelect2();
        }


        function initSelect2() {
            $('.select2[multiple]').select2();

            $('.select2').on('select2:close', function() {
               console.log('select2')
            });
        }


        function initDate() {
            $('.date-picker').datepicker({
                format: "yyyy-mm-dd",
                dateFormat: "yyyy-mm-dd",
                language: 'ro',
                autoclose: true,
            });
        }

        function initSelect() {
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
        } 
    </script>
@endsection
