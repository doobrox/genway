@props(['title','image','url','subcategories'])

<div {{ $attributes->merge(['class' => 'product custom-products-cards-padding card-container-tooltip']) }}>
    <div class="product text-center h-100">
        <div class="grid-inner card h-translatey-sm all-ts custom-card-sigla-height @isset($subcategories) custom-border-sigla @else border @endisset border-color h-100">
            @if(isset($image) && $image)
                <div class="p-2">
                    <a href="{{ $url }}">
                        <img src="{{ route('images', $image) }}" 
                            @isset($subcategories) style="max-height: 140px;" @else style="max-height: 100px;" @endisset 
                            alt="{{ $title ?? __('Imagine produs') }}">
                    </a>
                </div>
            @endif
            @isset($title)
            <div class="p-3 @if(!isset($subcategories)) bg-color @endif">
                <div class="product-title mb-0">
                    @isset($subcategories)
                        <h3><a class="" href="{{ $url }}">{{ $title }}</a></h3>
                    @else
                        <h5 class="m-0"><a class="text-white" href="{{ $url }}">{{ $title }}</a></h5>
                    @endisset
                </div>
            </div>
            @endisset
        </div>
    </div>
    @isset($subcategories)
    <div class="content-card-container-tooltip border-color masonry">
        <div class="masonry">
            @foreach($subcategories as $subcategory)
                <div class="masonry-item">
                    <div class="masonry-content">
                        @if($subcategory->imagine)
                            <a class="d-inline-flex w-100 align-items-center" style="height: 100px;" href="{{ route('category', $subcategory->slug) }}"><img class="d-block mx-auto mh-100" src="{{ route('images', 'categorii/'.$subcategory->imagine) }}"></a>
                        @endif
                        <div class="flex ps-3">
                            <h4 class="m-0 masonry-title"><a href="{{ route('category', $subcategory->slug) }}">{{ $subcategory->nume }}</a></h4>
                            <div class="line my-2"></div>
                            <ul class="no-style-list m-0 pb-3">
                                @foreach($subcategory->subcategorii as $item)
                                    <li><a href="{{ route('category', $item->slug) }}">{{ $item->nume }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endisset
</div>