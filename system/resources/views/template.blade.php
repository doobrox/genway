<x-app-layout
    :title="$pagina->seo_title"
    :description="$pagina->meta_description"
    :keywords="$pagina->meta_keywords"
>
	@push('styles')
		@if($pagina->id === 65 || $pagina->id === 70)
        	<link rel="stylesheet" href="{{ asset('css/pages/oferta-casa-verde-2023.css') }}" type="text/css" />
		@endif
        <style type="text/css">
            {!! $pagina->css !!}
        </style>
	@endpush
	<div class="row gutter-10">
		<!-- Post Content
		============================================= -->
		<div class="postcontent col-lg-10 order-lg-last">
			<!-- Content
			============================================= -->
			@if($pagina->pagebuilder === 1)
				{!! replace_shortcodes($pagina->html) !!}
			@else
				<section id="content">
					<div class="content-wrap custom-content-wrap">
						<div class="container page-info">
							<h1 class="mt-2">{{ $pagina->titlu }}</h1>
							{!! replace_shortcodes(remove_empty_p_tags($pagina->continut)) !!}
						</div>
					</div>
				</section><!-- #content end -->
			@endif
		</div><!-- .postcontent end -->

		<x-sidebar-layout />
	</div>
	@push('scripts')
	@endpush
</x-app-layout>
