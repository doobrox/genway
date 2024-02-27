<div class="subscribe-widget clearfix" data-alert-type="inline">
	<h5>Completeaza campurile de mai jos pentru a te inscrie in lista noastra de abonati.</h5>
	<div class="widget-subscribe-form-result"></div>
	<form action="{{ route('newsletter') }}" method="post" class="my-0">
		@csrf
		<div class="form-group mx-auto mb-2">
			<input type="text" name="nume" class="form-control" placeholder="Nume" required="">
		</div>
		<div class="form-group mx-auto mb-2">
			<input type="text" name="email" class="form-control" placeholder="Email" required="">
		</div>
		<div class="form-group mx-auto mb-2">
			<label class="form-check-label fw-semibold">
	            <input type="checkbox" name="termenii_de_prelucrare" class="form-check-input" value="1" required>
	            <span>Sunt de acord cu <a href="{{ route('page', 'prelucrarea-datelor-cu-caracter-personal') }}">&nbsp;Prelucrarea datelor cu caracter personal</a></span>
	        </label>
		</div>
		<button class="btn btn-success" type="submit"><i class="icon-email2"></i> Abonare</button>
	</form>
</div>