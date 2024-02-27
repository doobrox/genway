@extends('ofertare.layouts.app')

@section('styles')
	@livewireStyles
    {{-- <style type="text/css">
        *, html, body {
            /*font-family: "Times New Roman";
             font-family: Arial, sans-serif;*/
            font-size: 12px;
            /*font-family: 'Open Sans', sans-serif, Arial, Helvetica, Sans-Serif;*/
            font-family: "DejaVu Sans", sans-serif;
        }
        p {
            padding-bottom: 2px;
        }
        .titlu {
            font-size: 16px;
            text-align: center;
        }
        .componente > p {
            line-height: 14px;
            margin: 0;
            padding-top: 3px;
        }
        .tab {
            margin-left: 30px;
        }
        table td, table th {
            padding: 2.5px;
        }
    </style> --}}
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ isset($item) ? __('Editare') : __('Adauga') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ $save_route }}" class="form-horizontal" method="post" enctype="multipart/form-data">
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
					:label="__('Nume')"
					name="nume"
				    :value="old('nume', $item['nume'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.text
					:label="__('Subiect')"
					name="subiect"
				    :value="old('subiect', $item['subiect'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.ckeditor
					:label="__('Continut')"
					name="continut"
                    class="ckeditor"
                    id="ckeditor2"
				    :value="old('continut', $item['continut'] ?? '')"
				/>
                @if(auth()->user()->can('sabloane_documente.detalii'))
                    <x-ofertare.fields.edit.textarea
                        :label="__('Detalii')"
                        name="detalii"
                        {{-- class="ckeditor" --}}
                        {{-- id="ckeditor2" --}}
                        :value="old('detalii', $item['detalii'] ?? '')"
                        rows="10"
                    />
                @endif
                @if(auth()->user()->can('sabloane_documente.styles'))
                    <x-ofertare.fields.edit.textarea
                        :label="__('Stilizari')"
                        name="styles"
                        :value="old('styles', $item['stilizari'] ?? '')"
                        rows="10"
                    />
                @endif
                @if(auth()->user()->can('sabloane_documente.scripts'))
                    <x-ofertare.fields.edit.textarea
                        :label="__('Script-uri')"
                        name="scripts"
                        :value="old('scripts', $item['scripts'] ?? '')"
                        rows="10"
                    />
                @endif
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
	{{-- <script src="https://www.old.genway.ro/application/views/admin/js/ckeditor/ckeditor.js" type="text/javascript"></script> --}}
	{{-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}" type="text/javascript"></script> --}}
	<script src="{{ asset('js/ofertare/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
            // CKEDITOR.replace('ckeditor',{
            //     height: '800px',
            // });
            // ClassicEditor.create( document.querySelector( '.ckeditor' ), {
            //     // Editor configuration.
            //     // 'height':200

            // }).then( editor => {
            //     editor.ui.view.editable.element.style.height = '500px';
            //     window.editor = editor;
            // });


			const watchdog = new CKSource.EditorWatchdog();
			window.watchdog = watchdog;
			watchdog.setCreator( ( element, config ) => {
				return CKSource.Editor
					.create(element, config)
					.then(editor => {return editor;})
			} );

			watchdog.setDestructor(editor => {return editor.destroy();});
			watchdog.on( 'error', handleError );
			watchdog
				.create( document.querySelector('.ckeditor'), {licenseKey: '',})
				.catch( handleError );

			function handleError( error ) {
				console.error( 'Oops, something went wrong!' );
				console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
				console.warn( 'Build id: 8c7n21mv3uiq-apy0zvqexovn' );
				console.error( error );
			}


		});
	</script>
@endsection
