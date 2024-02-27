@extends('ofertare.layouts.app')

@section('styles')
	@livewireStyles
    <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css">
    <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2-bootstrap.min.css"  rel="stylesheet" type="text/css">
	{{-- <link rel="stylesheet" href="{{ asset('css/tailwind/app.css') }}" type="text/css"> --}}
@endsection

@section('content')
	<div>
		@livewire('tables.afm-table', ['section' => $section])
	</div>
@endsection

@section('scripts')
	@livewireScripts
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/js/select2.full.min.js"></script>
    
@endsection
