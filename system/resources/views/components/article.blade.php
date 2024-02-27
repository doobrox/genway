@props(['title', 'link', 'date', 'first' => true])

<div {{ $attributes->merge(['class' => 'grid-inner border border-color-article mb-2 p-4'.($first ? ' mt-3' : ' mt-5')]) }}>
	<div class="entry-title">
		<h2><a href="{{ $link }}">{{ $title }}</a></h2>
	</div>
	<div class="entry-meta">
		<ul>
			@if(isset($date))
		        <li><i class="icon-calendar3"></i> {{ $date }}</li>
		    @endif				
		</ul>
	</div>
	<div class="entry-content mt-2">
		<p>{{ $slot }}</p>
		<a href="{{ $link }}" class="more-link mb-2">{{ __('Citeste mai mult') }}</a>
	</div>
</div>
						