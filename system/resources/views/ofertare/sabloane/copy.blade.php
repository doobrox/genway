@extends('ofertare.layouts.app')

@section('styles')
@endsection

@section('scripts')
<script type="text/javascript">
	function getSabloaneAsOptions(input, target) {
        input = $(input);
        $.post({
            url: input.data('url') + ( input.val() ?  '/' + input.val() : ''),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: (data) => {
                $(target).html(data);
                $(target).trigger('change');
            }
        });
    }
    $('#user').trigger('change');
</script>
@endsection

@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject bold uppercase">{{ __('Copiere sablon AFM') }}</span>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="{{ route('ofertare.sabloane_afm.duplicate') }}" 
			class="form-horizontal" 
			method="post" id="form-role">
			@csrf
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
					<label class="col-md-3 control-label">Utilizator</label>
					<div class="col-md-7">
						<select name="user" id="user" class="form-control select2"
							onchange="getSabloaneAsOptions(this, '#sablon')" 
							data-url="{{ route('ofertare.sabloane_afm.user.get') }}">
							@foreach($users as $user)
								<option value="{{ $user->id }}" @selected(old('user') == $user->id)>{{ $user->nume }} {{ $user->prenume }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Sablon</label>
					<div class="col-md-7">
						<select name="sablon" id="sablon" class="form-control select2">
							@foreach($sabloane ?? [] as $sablon)
								<option value="{{ $sablon->id }}" @selected(old('sablon') == $sablon->id)>{{ $sablon->nume }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="margiv-top-10">
					<button type="submit" class="btn green">{{ __('Copiaza') }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection