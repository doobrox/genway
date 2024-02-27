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
					:label="__('Cod')"
					name="cod"
				    :value="old('cod', $item['cod'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Putere')"
					name="putere"
				    :value="old('putere', $item['putere'] ?? '')"
				    required
                    min="1"
				/>
				<x-ofertare.fields.edit.select
					:label="__('Marca')"
					name="marca"
				    :value="old('marca', $item['marca'] ?? '')"
				    :options="[
                        'Fronius snap' => 'Fronius snap',
                        'Fronius GEN24' => 'Fronius GEN24',
                        'Huawei' => 'Huawei',
                        'Sungrow' => 'Sungrow',
                    ]"
				    required
				/>
				<x-ofertare.fields.edit.select
					:label="__('Tip')"
					name="tip"
				    :value="old('tip', $item['tip'] ?? '')"
				    :options="[
                        'monofazat' => 'monofazat',
                        'trifazat' => 'trifazat',
                    ]"
				    required
				/>
				<x-ofertare.fields.edit.select
					:label="__('Grid')"
					name="grid"
				    :value="old('grid', $item['grid'] ?? '')"
				    :options="[
                        'on-grid' => 'on-grid',
                        'on-grid-hybrid' => 'on-grid-hybrid',
                    ]"
				    required
				/>
				<x-ofertare.fields.edit.text
					:label="__('Contor')"
					name="contor"
				    :value="old('contor', $item['contor'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.textarea
					:label="__('Descriere')"
					name="descriere"
				    :value="old('descriere', $item['descriere'] ?? '')"
                    rows="10"
				/>
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
	<script src="{{ asset('js/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
            // CKEDITOR.replace('ckeditor',{
            //     height: '800px',
            // });
            ClassicEditor.create( document.querySelector( '.ckeditor' ), {
                // Editor configuration.
                // 'height':200

            }).then( editor => {
                editor.ui.view.editable.element.style.height = '500px';
                window.editor = editor;
            });
		});
	</script>
@endsection
