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
					name="section"
				    :value="old('section', $item['section'] ?? '')"
				    :options="$sections"
				    required
				/>
				<div class="form-group" style="margin-top: -13px;">
					<div class="col-md-offset-3 col-md-7">
						<small>{{ __('Sectiunea nu este luata in considerare la adaugarea in componenta sau formular, este informativa pentru moment.') }}</small>
					</div>
				</div>
				<x-ofertare.fields.edit.text
					:label="__('Model')"
					name="model"
				    :value="old('model', $item['model'] ?? '')"
				    required
				/>
				<x-ofertare.fields.edit.numeric
					:label="__('Putere')"
					name="putere"
				    :value="old('putere', $item['putere'] ?? '')"
				    required
                    min="0" step="0.01"
				/>
				<x-ofertare.fields.edit.select
					:label="__('Marca')"
					name="marca"
				    :value="old('marca', $item['marca'] ?? '')"
				    :options="$marci"
				    required
				/>
				<x-ofertare.fields.edit.textarea
					:label="__('Descriere')"
					name="descriere"
				    :value="old('descriere', $item['descriere'] ?? '')"
                    rows="18"
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
