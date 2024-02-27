@extends('ofertare.layouts.app')

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
				<x-ofertare.fields.edit.select
					:label="__('Sectiune')"
					name="an"
				    :value="old('an', $item['an'] ?? '')"
				    :options="$sections"
				    required
				/>
				<x-ofertare.fields.edit.text
					:label="__('Titlu')"
					name="titlu"
				    :value="old('titlu', $item['titlu'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.select
					:label="__('Marca invertor')"
					name="marca_invertor"
				    :value="old('marca_invertor', $item['marca_invertor'] ?? '')"
				    :options="$marci_invertor"
				    required
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Putere invertor')"
					name="putere_invertor"
				    :value="old('putere_invertor', $item['putere_invertor'] ?? '')"
				    required
                    min="0" step="0.01"
				/>
				<x-ofertare.fields.edit.select
					:label="__('Marca panouri')"
					name="marca_panouri"
				    :value="old('marca_panouri', $item['marca_panouri'] ?? '')"
				    :options="$marci_panouri"
				    required
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Numar panouri')"
					name="numar_panouri"
				    :value="old('numar_panouri', $item['numar_panouri'] ?? '')"
                    min="0" step="1"
				    required
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Putere panouri')"
					name="putere_panouri"
				    :value="old('putere_panouri', $item['putere_panouri'] ?? '')"
				    required
                    min="0" step="0.01"
				/>
				<x-ofertare.fields.edit.text
					:label="__('Tip acumulatori')"
					name="tip_acumulatori"
				    :value="old('tip_acumulatori', $item['tip_acumulatori'] ?? '')"
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Capacitate acumulatori')"
					name="capacitate_acumulatori"
				    :value="old('capacitate_acumulatori', $item['capacitate_acumulatori'] ?? '')"
                    min="0" step="0.01"
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Aport propriu')"
					name="aport_propriu"
				    :value="old('aport_propriu', $item['aport_propriu'] ?? '')"
				    required
                    min="0" step="0.01"
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Contributie afm')"
					name="contributie_afm"
				    :value="old('contributie_afm', $item['contributie_afm'] ?? '')"
				    required
                    min="0" step="0.01"
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
	{{-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
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
	</script> --}}
@endsection
