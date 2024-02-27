<x-app-layout
    :title="$seo_title ?? null"
    :description="$meta_description ?? null"
    :keywords="$meta_keywords ?? null"
>
	<div class="row gutter-10">
		<div class="postcontent col-lg-10 order-lg-last">
			<section id="content">
				<div class="container clearfix">
					<div class="row">
						<div class="postcontent col-lg-12 order-lg-last">
							<div id="posts" class="row grid-container gutter-30">
								<div class="entry col-12">
									 @foreach($items ?? [] as $pagina)
										<x-article 
											:title="$pagina->titlu"
											:link="route('page', $pagina->slug)"
											:date="$pagina->created_at"
											:first="$loop->first">

										    {!! \Str::limit(replace_shortcodes(remove_empty_p_tags(strip_tags($pagina->continut))), 300) !!}

										</x-article>
									@endforeach
								</div>
                                {{ $items->links() }}
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<x-sidebar-layout />
	</div>
</x-app-layout>
