@extends('ofertare.layouts.app')
@section('styles')
<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css">
<link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/css/select2-bootstrap.min.css"  rel="stylesheet" type="text/css">
@endsection

@section('scripts')
<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.add_item').click(function() {
			let container = $(this).closest('.items-container');
			let i = container ? container.data('items') : 0;
			let txt = '';
			txt += '<div class="row">';
				txt += '<div class="col-xs-8 col-md-9">';
					txt += '<input type="text" class="form-control" name="siruri_panouri[]" value="1x8" placeholder="{{ __('1x8') }}">';
				txt += '</div>';
				txt += '<div class="col-xs-4 col-md-3">';
					txt += '<button type="button" class="btn btn-danger img-rounded delete_item" onclick="this.closest(\'.row\').remove()"><b style="padding-right: 5px">-</b> {{ __('Sterge') }}</button>';
				txt += '</div>';
				txt += '<hr class="col-xs-12">';
			txt += '</div>';
			$(txt).insertBefore(this);
			if(container) {container.data('items', ++i);}
		});
	});
 
</script>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ isset($item) ? __('Corectare montaj') : __('Completare montaj') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ isset($item) ? route('ofertare.montaje.update', $item) : route('ofertare.montaje.store', $programare) }}" 
			class="form-horizontal" 
			method="post" id="form-role"
			enctype="multipart/form-data">
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
				
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('siruri_panouri') }} </label>
					@php $siruri = old('siruri_panouri', $item['siruri_panouri'] ?? ['1x8']); @endphp
					<div class="col-md-7 items-container" data-items="{{ max(array_keys($siruri)) + 1 }}">
						@foreach($siruri as $i => $sir)
							<div class="row">
								<div class="col-xs-8 col-md-9">
									<input type="text" class="form-control" 
										name="siruri_panouri[]" 
										value="{{ old('siruri_panouri.'.$i, $sir ?? '1x8') }}" 
										placeholder="1x8">
								</div>
								<div class="col-xs-4 col-md-3">
									<button type="button" class="btn btn-danger img-rounded delete_item" onclick="this.closest('.row').remove()"><b style="padding-right: 5px">-</b> {{ __('Sterge') }}</button>
								</div>
								<hr class="col-xs-12">
							</div>
						@endforeach
						<button type="button" class="btn btn-success img-rounded add_item control-label"><b style="padding-right: 5px">+</b> {{ __('Adauga') }}</button>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poze_panouri') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poze_panouri[]" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poze_panouri'][0]))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poze_panouri'][0]]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
						<input type="file" class="form-control mt-2" name="poze_panouri[]" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poze_panouri'][1]))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poze_panouri'][1]]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
						<input type="file" class="form-control mt-2" name="poze_panouri[]" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poze_panouri'][2]))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poze_panouri'][2]]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_invertor') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_invertor" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_invertor']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_invertor']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_smartmeter') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_smartmeter" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_smartmeter']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_smartmeter']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_dc_box') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_dc_box" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_dc_box']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_dc_box']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_siguranta_ac') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_siguranta_ac" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_siguranta_ac']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_siguranta_ac']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_legare_structuri') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_legare_structuri" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_legare_structuri']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_legare_structuri']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_cablu_casa') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_cablu_casa" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_cablu_casa']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_cablu_casa']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('poza_valoare_masurata_priza') }}</label>
					<div class="col-md-7">
						<input type="file" class="form-control" name="poza_valoare_masurata_priza" {{ !isset($item) ? 'required' : '' }}>
						@if(isset($item) && isset($item['poza_valoare_masurata_priza']))
							<img src="{{ route('ofertare.montaje.file.get', ['executie' => $item->id, 'fisier' => $item['poza_valoare_masurata_priza']]) }}"
								class="img-responsive img-thumbnail" style="max-height: 150px; max-width: 100%;">
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">{{ __('link_sistem') }}</label>
					<div class="col-md-7">
						<input type="text" class="form-control" name="link_sistem" 
							value="{{ old('link_sistem', $item['link_sistem'] ?? '') }}" 
							placeholder="{{ __('Link-ul de accesare al invertorului') }}" 
							required>
					</div>
				</div>
				<div class="margiv-top-10">
					<button type="submit" class="btn green">{{ isset($item) ? __('Corecteaza') : __('Trimite') }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection