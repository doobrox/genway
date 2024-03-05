<div class="relative" wire:ignore.self>
    <x-ofertare.table-loader wire:loading.flex 
        wire:target="conditions, current_columns, all_columns_checked, gotoPage, nextPage, previousPage, changeSablon, orderBy, exportTabelSiConditii, exportNecesarEchipa" />
    @if(session()->has('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif
    @if(session()->has('warning'))
        <div class="alert alert-warning" role="alert">{{ session('warning') }}</div>
    @endif
    @can('afm.2021.buton_selectare_coloane')
        <div class="dropdown inline-block mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="coloane"
                data-toggle="dropdown"
                aria-expanded="false"
            >
                <i class="fa fa-gears"></i> {{ __('Coloane tabel') }}
            </button>
            <div class="dropdown-menu" id="select_coloane" style="padding: 10px;overflow: auto;max-height: 500px; width: 200px;max-width: 100vw;" wire:key="column_list" wire:poll.visible="current_columns">
                <x-ofertare.fields.edit.checkbox wire:model.live="all_columns_checked" wire:loading.attr="disabled" :text="__('Toate')" :form="false" /> <br>
                @foreach ($coloane_permise as $nume => $titlu)
                    <x-ofertare.fields.edit.checkbox wire:model.live.lazy="current_columns" wire:loading.attr="disabled" :value="$nume" :text="$titlu" :form="false" /> <br>
                @endforeach
            </div>
        </div>
    @endcan
    @if(count(auth()->user()->sabloaneAFM))
        <div class="dropdown inline-block mr-2">
            <button class="btn btn-secondary dropdown-toggle"type="button" id="coloane"
                data-toggle="dropdown"
                aria-expanded="false"
            >
                <i class="fa fa-list"></i> {{ __('Sabloane tabel') }}
            </button>
            <ul class="dropdown-menu" id="select_coloane" style="padding: 10px;overflow: auto;max-height: 500px;">
                @foreach (auth()->user()->sabloaneAFM as $sablon)
                    <li>
                        <a href="javascript:void(0)" class="btn btn-sm @if($sablon->implicit) blue-hoki cursor-default @endif" title="{{ $sablon->nume }}"
                            @if(!$sablon->implicit) wire:click.prevent="changeSablon({{ $sablon->id }})" @endif>
                            {{ $sablon->nume }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcan
    <div class="dropdown inline-block mr-2">
        <button class="btn green-jungle dropdown-toggle"type="button"id="coloane"
            data-toggle="dropdown"
            aria-expanded="false"
        >
            <i class="fa fa-file-excel-o"></i> {{ __('Export') }} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" style="overflow: auto; max-height: 500px;">
            @foreach($this->getExportOptions() as $export)
                @if($export['condition'])
                    <li>
                        <a href="javascript:void(0)" class="btn btn-sm {{ $export['color'] }}" title="{{ $export['title'] }}"
                            wire:click.prevent="{{ $export['func'] }}">
                            <i class="{{ $export['icon'] }}" style="color: #fff"></i> {{ $export['title'] }}
                        </a>
                    </li>
                @endif
            @endforeach
            {{-- <li>
                <a href="javascript:void(0)" class="btn btn-sm green-jungle" title="{{ __('Export necesar echipa') }}"
                    wire:click.prevent="exportNecesarEchipa">
                    <i class="fa fa-file-excel-o" style="color: #fff"></i> {{ __('Export necesar echipa') }}
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="btn btn-sm green-jungle" title="{{ __('Export tabel si conditii') }}"
                    wire:click.prevent="exportTabelSiConditii">
                    <i class="fa fa-file-excel-o" style="color: #fff"></i> {{ __('Export tabel si conditii') }}
                </a>
            </li> --}}
        </ul>
    </div>

    <div class="dropdown inline-block mr-2">
        <button class="btn blue-sharp dropdown-toggle" type="button" id="explicatii" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-info-circle"></i> {{ __('Explicatii') }} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" style="overflow: auto; max-height: 500px;">
            @foreach($this->getExportDescriptions() as $index => $item)
                @if(!isset($item['condition']) || (isset($item['condition']) && $item['condition']))
                    <li>
                        <a href="#" data-toggle="modal" data-target="#explicatii-modal-{{ $index }}" class="btn btn-sm {{ $item['color'] ?? 'blue-sharp' }}" title="{{ $item['title'] }}">
                            @isset($item['icon'])
                                <i class="{{ $item['icon'] }}" style="color: #fff"></i>
                            @endisset
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>

    @foreach($this->getExportDescriptions() as $index => $item)
        @if(!isset($item['condition']) || (isset($item['condition']) && $item['condition']))
            <div class="modal fade" id="explicatii-modal-{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="explicatii-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="explicatii-title">{{ $item['title'] }}</h4>
                        </div>
                        <div class="alert alert-danger hidden text-center" id='error' role="alert"></div>
                        <div class="modal-body">
                            {{ $item['description']['list_start'] ?? '' }}
                            <ul>
                                @foreach($item['description']['columns'] as $column => $text)
                                    <li>{!! $text !!}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Inchide') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <div wire:loading wire:target="formular">{{ __('Actualizare selectie...') }}</div>

    <div class="table-responsive table-scrollable" style="overflow: auto;max-height: 72vh;">
        <table class="table table-bordered table-advance table-hover" {{-- wire:loading.class="bg-default" --}}>
            <thead>
                <tr style="position: sticky; top:0; z-index:9">
                    @foreach($coloane as $coloana)
                        <th @class(['relative'])
                            @if($coloana->ordonare)
                            wire:click="orderBy('{{ $coloana->nume }}', {{
                                $this->order_by_column === $coloana->nume && $this->order_by_sort === 1 ? 2 : 1
                            }})"
                            @endif>
                            @if($coloana->ordonare)
                                <a href="javascript:void(0)" class="flex justify-between items-center" style="text-decoration: none;"
                                    wire:loading.attr="disabled"
                                    wire:click.prevent="orderBy('{{ $coloana->nume }}', {{
                                        $this->order_by_column == $coloana->nume ? 2 : 1
                                    }})"
                                >
                                    <span>{{ $coloana->titlu }}</span>
                                    @if($this->order_by_column !== $coloana->nume)
                                        <i class="fa fa-sort ml-2" aria-hidden="true"></i>
                                    @elseif($this->order_by_sort === 1)
                                        <i class="fa fa-sort-asc ml-2" aria-hidden="true"></i>
                                    @else
                                        <i class="fa fa-sort-desc ml-2" aria-hidden="true"></i>
                                    @endif
                                </a>
                            @else
                                {{ $coloana->titlu }}
                            @endif
                        </th>
                    @endforeach
                    <th  style="position: sticky; top:0; z-index:9">{{ __('Optiuni') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($coloane as $coloana)
                        <td>
                            @switch($coloana->cautare)
                                @case(1)
                                    <x-dynamic-component :component="'ofertare.fields.search.'.$types[$coloana->tip]"
                                        model="conditions.{{ $coloana->nume }}"
                                        key="{{ $coloana->nume }}"
                                        wire:keydown.enter="$refresh"
                                        :value="$this->conditions[$coloana->nume] ?? null"
                                        :options="$coloana->default_values"
                                        :data_url="$coloana->getAdvancedDataUrl($section)"
                                        :multiple="$coloana->rules['multiple'] ?? null"
                                    />
                                    @break
                                @case(2)
                                    <x-dynamic-component :component="'ofertare.columns.search.'.$coloana->nume"
                                        wire:model="conditions.{{ $coloana->nume }}"
                                        wire:key="{{ $coloana->nume }}"
                                        :values="$this->conditions[$coloana->nume] ?? null"
                                        :conditions="$this->conditions"
                                        :column="$coloana"
                                    />
                                    @break
                            @endswitch
                        </td>
                    @endforeach
                    <td>
                        <button class="btn btn-success img-rounded" wire:click.prevent="$refresh"><i class="fa fa-search mr-2"></i>{{ __('Cauta') }}</button>
                    </td>
                </tr>
                @foreach($items as $item)
                    <tr wire:key="{{ $item->id }}" class="@if(in_array($item->id, $this->formular)) active @endif">
                        @foreach($coloane as $coloana)
                            <td
                                @if($coloana->canBeEdited($item))
                                    data-edit="{{ $coloana->editare }}"
                                    data-type="{{ $coloana->tip }}"
                                    data-edit-route="{{ route('ofertare.afm.get.column', [$section, $item->id, $coloana->nume]) }}"
                                    data-update-route="{{ route('ofertare.afm.update.column', [$section, $item->id, $coloana->nume]) }}"
                                @endif
                                >

                                @if($coloana->nume === 'test_de_test_test')

                                    <a href="#" data-toggle="modal" data-target="#downloads-modal-{{ $item->id }}" class="btn btn-sm blue-sharp" title="Download Modal">
                                        Open Modal
                                    </a>

                                    <div class="modal fade" id="downloads-modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="downloads-title" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="explicatii-title">Titlu</h4>
                                                </div>
                                                <div class="alert alert-danger hidden text-center" id='error' role="alert"></div>
                                                <div class="modal-body">
                                                    <span class="downloads-modal-row-id">{{ $item->id }}</span>
                                                    <br>
                                                    <br>
                                                    <input type="file" id="fileInput{{ $item->id }}" name="fileInput{{ $item->id }}">
                                                    <br>
                                                    <br>
                                                    <button id="generateQRButton_{{ $item->id }}">Generate QR Code</button>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('#generateQRButton{{ $item->id }}').click(function() {
                                                                var fileInput{{ $item->id }} = $('#fileInput{{ $item->id }}')[0].files[0];
                                                                var formData{{ $item->id }} = new FormData();
                                                                formData{{ $item->id }}.append('file', fileInput{{ $item->id }});

                                                                $.ajax({
                                                                    url: '{{ route('generate.qr.invoice', ['id' => $item->id]) }}',
                                                                    method: 'POST',
                                                                    // For Livewire (if not working, comment this line temporarily)
                                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                                    dataType: 'json',
                                                                    processData: false,
                                                                    contentType: false,
                                                                    data: formData{{ $item->id }},
                                                                    success: function(data){
                                                                        console.log(data);
                                                                    },
                                                                    error: function(xhr, status, error) {
                                                                        console.error(xhr.responseText);
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Inchide') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--<button wire:click="openModalForDownload('{{ $item->id }}')" class="btn btn-primary">Livewire</button>--}}

                                @else

                                    @switch($coloana->afisare)
                                        @case(1)
                                            <x-dynamic-component :component="'ofertare.columns.view.'.$coloana->nume"
                                                :item="$item"
                                                :column="$coloana"
                                            />
                                            @break
                                        @default
                                            <x-dynamic-component :component="'ofertare.fields.view.'.$types[$coloana->tip]"
                                                :item="$item"
                                                :column="$coloana"
                                            />
                                            @break
                                    @endswitch

                                @endif
                            </td>
                        @endforeach
                        <td class="options">
                            <div class="dropdown inline-block">
                                <button class="dropdown-toggle btn btn-sm" type="button" data-toggle="dropdown" style="background: #f0f0f0; border: none">
                                    <i class="fa fa-list fa-lg"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-options pull-right">
                                    @foreach($this->getOptions($item) as $option)
                                        @if($option['condition'])
                                            <li>
                                                <a @isset($option['route']) href="{{ $option['route'] }}" @else href="javascript:void(0)" wire:click.prevent="{{ $option['func'] ?? '' }}" @endif target="{{ isset($option['target']) ? $option['target'] : '_blank' }}" class="btn btn-sm {{ $option['color'] }}" title="{{ $option['title'] }}">
                                                    <i class="{{ $option['icon'] }}" style="color: #fff"></i> {{ $option['title'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $items->links() }}

    {{-- @foreach($this->getExportDescriptions() as $item)
        <div class="box blue portlet mt-5 mb-3">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-info-circle fa-2x"></i>{{ $item['title'] }}</div>
                <div class="tools"><a href="javascript:void(0)" class="expand"></a></div>
            </div>
            <div class="portlet-body" style="display: none;">
                {{ $item['description']['list_start'] ?? '' }}
                <ul>
                    @foreach($item['description']['columns'] as $column => $text)
                        <li>{!! $text !!}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach --}}

    @teleport('body')
        <div class="modal fade" id="afm-modal" tabindex="-1" role="dialog" aria-labelledby="afm-modal-title" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="afm-modal-title">{{ __('Editare') }}</h4>
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
    @endteleport
</div>

@assets

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    ['livewire:initialized', 'reinit'].forEach( function(e) {
        window.addEventListener(e, function (event) {
            // for livewire to finish rendering
            setTimeout(function(){
                initAll();

                $('td[data-edit]').on('dblclick', function () {
                    if(this.dataset.edit) {
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
                                    initAll();
                                    $(openModal).modal('show');

                                    saveModalHandler(openModal, currentTd);

                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Erori primite', xhr.responseText);
                            }
                        });
                    }
                });
            }, 1);

        });
    });
</script>
@endassets

@push('scripts')
    <script>
        function saveModalHandler(openModal,currentTd) {
            $(openModal).find('.save-btn').off('click').on('click', () => {
                const updatedRouteUrl = currentTd.data('update-route');
                const allInputsFromModal = $(openModal).find("input[type=\"text\"], input[type=\"hidden\"], input[type=\"number\"], input[type=\"email\"], input[type=\"radio\"]:checked, input[type=\"checkbox\"]:checked, select, textarea");
                const formInModal = $(openModal).find('form');
                const formAction = formInModal.attr('action') || updatedRouteUrl;

                const formData = new FormData();

                allInputsFromModal.each(function () {
                    formData.append($(this).attr('name'), $(this).val());
                });

                $.ajax({
                    url: formAction,
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        if(data.reload == true) {
                            // reload
                            @this.$refresh();
                            currentTd.removeData('edit');
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
        }

        function onBlurHandler(currentTd) {
            let input = currentTd.find(':input');
            let action = input[0].classList.contains('date-picker') || input[0].type === 'file'
                ? 'change' : 'blur';
            input[0].style.minWidth = '100px';
            input.focus();
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
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: form,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        if(data.reload == true) {
                            // reload
                            @this.$refresh();
                            currentTd.removeData('edit');
                        } else {
                            currentTd.html(data.label || data.value);
                        }
                    },
                    error: function (xhr, status, error) {
                        // console.error('Error: ', xhr.responseText);
                    }
                });
            });
        }

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

        // was necesary to modify  the min.js file
        // from $(this).scrollTop(position.y); to $(self).scrollTop(position.y);
        // to prevent scrollTop event that is not stopped because of the re-render
        function initSelect2() {
            $('.select2[multiple]').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    dropdownParent: $('.select2-wrapper'),
                    minimumInputLength: 1,
                    // allowClear: true,
                    scrollAfterSelect: false,
                    dropdownAutoWidth: true,
                    placeholder: null,
                    ajax: {
                        url: $(this).data('url'),
                        dataType: 'json',
                        processResults: function (data, params) {
                            return {
                                results: $.map(data, function (item, key) {
                                    return {id: key, text: item}
                                })
                            };
                        }
                    }
                }).on('change', function (e) {
                    let data = $(this).select2('val');
                    let key = $(this).attr('wire:model');
                    // Livewire.dispatch('update-select2', {item: {[key]:data}});
                    @this.set($(this).attr('wire:model'), data);
                });
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
                            let val = $(input).val() || $(input).data('value');
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

        function getLocalitatiInOptionsWithEmpty(input, location) {
            input = $(input);
            if(input.val()) {
                $.post({
                    url: input.data('trigger-url') + ( input.val() ?  '/' + input.val() : ''),
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: (data) => {
                        $(location).html('<option value=""></option>'+data);
                        $(location).trigger('change');
                    }
                });
            } else {
                $(location).html('<option value=""></option>');
            }
        }

    </script>
@endpush
