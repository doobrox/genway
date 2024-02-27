<x-app-layout>
	@push('styles')
		<style type="text/css">
			#notfound {
				min-height: 60vh;
			    width: 100%;
				position: relative;
			}
			.notfound {
			    max-width: 767px;
			    width: 100%;
			    line-height: 1.4;
			    padding: 0 15px;
			    position: absolute;
			    left: 50%;
			    top: 50%;
			    -webkit-transform: translate(-50%,-50%);
			    -ms-transform: translate(-50%,-50%);
			    transform: translate(-50%,-50%);
			}
			.notfound .notfound-404 {
			    position: relative;
			    height: 150px;
			    line-height: 150px;
			    margin-bottom: 25px;
			}
			.notfound .notfound-404 h1 {
			    font-size: 150px;
			    font-weight: 900;
			    margin: 0;
			    text-transform: uppercase;
			    background: url("{{ asset('images/Fotovoltaice in scoli-Budești-Postare Facebook4.png') }}");
			    -webkit-background-clip: text;
			    -webkit-text-fill-color: transparent;
			    background-size: cover;
			    background-position: center;
			}
			.notfound h2 {
			    font-size: 26px;
			    font-weight: 700;
			    margin: 0;
			}
			.notfound p {
			    font-size: 14px;
			    font-weight: 500;
			    margin-bottom: 0;
			    text-transform: uppercase;
			}
		</style>
	@endpush
	<div class="row gutter-10">
		<!-- Post Content
		============================================= -->
		<div class="postcontent">
			<!-- Content
			============================================= -->
			<div id="notfound">
				<div class="notfound">
					<div class="notfound-404"><h1>404</h1></div>
					<h2>{{ __('Oops! Pagina nu a putut fi gasita') }}</h2>
					<p>{{ __('Ne pare rau dar pagina accesata nu exista, a fost stearsa, numele a fost schimbat sau nu este valabila momentan.') }}</p>
					<a class="button mx-0 button-fill fill-from-bottom button-color-default" href="{{ route('home') }}"><i class="icon-home"></i> {{ __('Pagina principala') }}</a>
				</div>
			</div>
		</div><!-- .postcontent end -->
	</div>
	@push('scripts')
	@endpush
</x-app-layout>
