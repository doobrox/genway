@props(['gallery','folder' => '','route' => 'images','column_name' => 'fisier','column_title' => 'titlu','popup' => false,'static' => true])

@if($popup)
    <a href="#gallery-{{ $popup }}" class="btn btn-outline-info mx-2" data-lightbox="inline"><i class="icon-eye-open"></i></a>
@endif

<!-- Gallery Slider
============================================= -->
<div class="fslider {{ $static ? '' : 'responsive' }} {{ $popup ? 'mfp-hide mx-auto popup-slider' : '' }}" @if($popup) id="gallery-{{ $popup }}" @endif data-arrows="true" data-animation="fade" data-thumbs="true" data-slideshow="true">
    <div class="flexslider">
        <div class="slider-wrap mx-auto"  @if($popup) style="max-width: 80vh; max-height: 80vh;" @endif>
            @foreach($gallery as $index => $image)
                {{-- <a href={{ $image->url }}>Test</a> --}}
                <div class="slide d-flex h-0 justify-content-center align-items-center img-area" data-thumb="{{ route($route, $folder.$image->$column_name) }}">
                    @if($image->url)
                        <a id="imagine#{{ $image->id }}" class="button-gallery-position" href={{ $image->url }}>
                            <button>Detalii Proiect</button>
                        </a>
                    @endif
                    <img src="{{ route($route, $folder.$image->$column_name) }}"
                        alt="{{ $image->$column_title ?? __('Imagine :nr', ['nr' => $index + 1]) }}"
                        class="w-auto position-absolute">
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Gallery Slider End -->
