@props(['gallery','folder' => '','route' => 'images','column_name' => 'fisier','column_title' => 'titlu'])

<!-- Product Single - Gallery
============================================= -->
<div class="fslider" data-arrows="true" data-animation="fade" data-thumbs="true" data-slideshow="true">
    <div class="flexslider">
        <div class="slider-wrap custom-image-slide-container" data-lightbox="gallery">
            @foreach($gallery as $index => $image)
                <div class="slide" data-thumb="{{ route($route, $folder.$image->$column_name) }}">
                    <a href="{{ route($route, $folder.$image->$column_name) }}" 
                        class="d-flex h-0 justify-content-center align-items-center img-area" 
                        title="{{ $image->$column_title ?? __('Imagine :nr', ['nr' => $index + 1]) }}" data-lightbox="gallery-item">
                        <img src="{{ route($route, $folder.$image->$column_name) }}" 
                            alt="{{ $image->$column_title ?? __('Imagine :nr', ['nr' => $index + 1]) }}" 
                            class="w-auto position-absolute">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Product Single - Gallery End -->