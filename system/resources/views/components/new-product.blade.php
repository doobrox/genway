@props(['title','image','url','cod_ean13','price','fullPrice','discount' => false,'rating' => false,'new' => true,'tva_inclus' => false])

<div {{ $attributes->merge(['class' => 'product text-center w-100']) }}>
    <div class="grid-inner card border-0 custom-h-shadow-noi h-translatey-sm all-ts custom-card-h-100 justify-content-between">
        @isset($image)
        <div class="product-image img-area img-area-carti-noi m-0">
            <img class="p-2" src="{{ route('images', $image) }}" alt="{{ $title ?? __('Imagine Produs') }}">
            @if($discount || $new)
                <div class="sale-flash badge bg-success p-2">
                    @if($discount)
                        {{ __('Reducere') }}
                    @elseif($new)
                        {{ __('Stoc nou') }}
                    @endif
                </div>
            @endif
        </div>
        @endisset
        <div class="product-desc p-2">
            <div class="product-title"><h3><a href="{{ $url ?? 'javascript:void(0)' }}">{{ $title }}</a></h3></div>
            <div class="product-price">
                @isset($fullPrice)
                    <del>{{ $fullPrice }} lei</del>
                @endisset
                <div class="m-2 mt-0">
                    <span class="text-dark fw-semibold custom-main-product-description">{{ $cod_ean13 ?? '' }}</span>
                </div>
                <ins>{{ $price }} lei</ins>
                @if($tva_inclus)
                    <br>
                    <small>({{ __('TVA inclus') }})</small>
                @endif
            </div>
            <x-rating :rating="$rating" />
        </div>
    </div>
</div>
