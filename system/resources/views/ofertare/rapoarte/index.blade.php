@extends('ofertare.layouts.app')

@section('styles')
	@livewireStyles
	<style type="text/css">
		.pill-red {
			fill: #fc0003;
		}
		.pill-orange {
			fill: #ff6f00;
		}
		.pill-green {
			fill: #00af50;
		}
		.pill-yellow {
			fill: #f8d609;
		}
		.pill-brown {
			fill: #c55911;
		}
		.pill-pink {
			fill: #ffc0cb;
		}
		.pill-black {
			fill: #000;
		}
		.sm_location {
			opacity: 0!important;
		}
	</style>
	<script src="{{ asset('js/ofertare/countrymap.js') }}" type="text/javascript"></script>
@endsection

@section('content')
		{{-- <div class="panel with-nav-tabs panel-info">
            <ul class="nav nav-tabs panel-heading p-0">
                <li class="active"><a href="#general-map-tab" data-toggle="tab">{{ __('Raport general per judet') }}</a></li>
                <li><a href="#monthly-map-tab" data-toggle="tab">{{ __('Raport sisteme nemontate per judet') }}</a></li>
                <li><a href="#cer-neobtinute-map-tab" data-toggle="tab">{{ __('Raport CER-uri neobtinute per judet') }}</a></li>
                <li><a href="#dosare-nedepuse-map-tab" data-toggle="tab">{{ __('Raport dosare nedepuse per judet') }}</a></li>
            </ul>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="general-map-tab">
	                </div>
                    <div class="tab-pane fade" id="monthly-map-tab">
	                </div>
                    <div class="tab-pane fade" id="cer-neobtinute-map-tab">
                    </div>
                    <div class="tab-pane fade" id="dosare-nedepuse-map-tab">
                    </div>
                    <div class="tab-pane fade" id="tab5default">Default 5</div>
                </div>
            </div>
        </div> --}}
        {{-- <a href="{{ route('ofertare.rapoarte.export', ['section' => $section, 'filter' => $filter]) }}" class="btn btn-circle green-jungle mb-4">
			<i class="fa fa-file-excel-o" aria-hidden="true"></i>
			<span>{{ __('Export raport data alegere instalator') }}</span>
		</a> --}}
    @if($filter == 'stare-montaj')
		@livewire('maps.general-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('maps.monthly-afm-map', ['judete' => $judete, 'section' => $section])
    @elseif($filter == 'situatie-dosar')
		@livewire('maps.cer-neobtinute-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('maps.dosare-nedepuse-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('maps.dosare-depuse-afm-map', ['judete' => $judete, 'section' => $section])
    @elseif($filter == 'evenimente')
		@livewire('reports.events-afm-report', ['title' => __('Raport evenimente'), 'section' => $section, 'id' => 'events-report'])
    @elseif($filter == 'data-alegere-instalator')
		@livewire('maps.verification-limit-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.verification-limit-afm-report', ['title' => __('Raport data alegere instalator fara judet'), 'section' => $section, 'id' => 'verification-limit-report'])
		@livewire('reports.verification-limit-afm-report', [
			'title' => __('Raport data alegere instalator per regiune'),
			'section' => $section,
			'id' => 'verification-limit-per-region-report',
			'regiuni' => 1
		])
	@elseif($filter == 'status-aprobare-dosar')
		@livewire('maps.status-aprobare-dosar-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.status-aprobare-dosar-afm-report', [
			'title' => __('Raport status aprobare dosar per regiune'),
			'section' => $section,
			'id' => 'status-aprobare-dosar-per-region-report',
			'regiuni' => 1
		])
	@elseif($filter == 'contract-instalare')
		@livewire('maps.contract-instalare-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.contract-instalare-afm-report', [
			'title' => __('Raport contracte de instalare per regiune'),
			'section' => $section,
			'id' => 'contract-instalare-report',
			'regiuni' => 1
		])
	@elseif($filter == 'inginer-vizita')
		@livewire('maps.inginer-vizita-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.inginer-vizita-afm-report', [
			'title' => __('Raport vizite per regiune'),
			'section' => $section,
			'id' => 'inginer-vizita-report',
			'regiuni' => 1
		])
	@elseif($filter == 'schita-amplasare-panouri')
		@livewire('maps.schita-amplasare-panouri-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.schita-amplasare-panouri-afm-report', ['section' => $section, 'regiuni' => 1])
	@elseif($filter == 'verificare-schita-panouri')
		@livewire('maps.verificare-schita-panouri-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.verificare-schita-panouri-afm-report', ['section' => $section, 'regiuni' => 1])
	@elseif($filter == 'programare-montaj')
		@livewire('maps.programare-montaj-afm-map', ['judete' => $judete, 'section' => $section])
		@livewire('reports.programare-montaj-afm-report', ['section' => $section, 'regiuni' => 1])
    @endif
@endsection

@section('scripts')
 	@livewireScripts
 	{{-- @livewireScriptConfig --}}
 	<script type="text/javascript">
 		isset = function(obj) {
		  	var i, max_i;
		  	if(obj === undefined) return false;
		  	for (i = 1 , max_i = arguments.length; i < max_i; i++) {
			    if (obj[arguments[i]] === undefined) {
			        return false;
			    }
			    obj = obj[arguments[i]];
		  	}
		  	return true;
		};
        ['updatedMonth', 'updatedYear'].forEach( function(e) {
        	window.addEventListener(e, event => {
	            simplemaps_countrymap_mapdata = JSON.parse(event.detail.settings);
	            let dinamic_map = simplemaps_countrymap.create();
	            dinamic_map.load();
	        });
		});

        ['livewire:init', 'reloadInputs'].forEach( function(e) {
	        window.addEventListener(e, function (event) {
	        	if (typeof jQuery === 'function' && typeof $ === 'function') {
	                jQuery(document).ready(function($) {
	                	let wrapper = isset(event, 'detail', 'wrapper') ? event.detail.wrapper : '';
	                    if ($(wrapper + ' .date-picker').length) {
	                        if ($().datepicker) {
	                            $(wrapper + ' .date-picker').datepicker({
	                                format: 'yyyy-mm-dd',
	                                dateFormat: 'yyyy-mm-dd',
	                                language: 'ro',
	                                autoclose: true,
	                            });
	                        };
	                    }
	                    if($(wrapper + ' .select2').length && $().select2) {
	                        $(wrapper + ' .select2').select2();
	                    }
	                });
	            }
	        });
	    });
 	</script>
@endsection
