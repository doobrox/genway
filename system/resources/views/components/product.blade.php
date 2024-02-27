@props(['product','id', 'title','image','description','url','price','fullPrice',
    'discount' => false,'discountValue','rating','new' => false,'tva_inclus' => true
])

<!-- Product -->
<div {{ $attributes->merge(['class' => 'product custom-products-cards-padding']) }}>
    <div class="grid-inner card-border h-100 d-flex flex-column">
        @if(isset($image) || isset($product))
            <div class="img-area img-area-carti-principale mx-auto">
                <img class="p-2" 
                    src="{{ route('images', $image ?? 'produse/'.$product->imagine) }}" 
                    alt="{{ $title ?? __('Imagine Produs') }}">
                @if($discount || $new || isset($product) && $product->discount)
                    <div class="sale-flash badge bg-success p-2">
                        @if($discount || $product->discount)
                            {{ __('Reducere de :value:unit', $discountValue 
                                ?? ($tva_inclus ? $product->discount_valoare_cu_tva : $product->discount_valoare) 
                                ?? []) }}
                        @elseif($new)
                            {{ __('Stoc nou') }}
                        @endif
                    </div>
                @endif
            </div>
        @endif
        <div class="product-desc text-center d-flex flex-column flex-grow-1 justify-content-between">
            <div class="product-title mb-0">
                <h4 class="mb-0 fs-5"><a class="fw-bold" title="{{ $title ?? $product->nume }}" href="{{ $url ?? $product->route }}">{{ mb_strimwidth($title ?? $product->nume, 0, 70, '...') }}</a></h4>
                @if(isset($description) || isset($product))
                    <p class="fs-6 margin-low custom-main-product-description">{!! strip_tags(mb_strimwidth(preg_replace('/(&nbsp;)+|[\s]+/', ' ', $description ?? $product->descriere), 0, 150, '...')) !!}</p>
                @endif
            </div>
            <div class="m-2 mt-0">
                <span class="text-dark fw-semibold custom-main-product-description">{{ $product->cod_ean13 }}</span>
            </div>
            <div>
                <h5 class="product-price fw-semibold">
                    @if(isset($fullPrice) || isset($product) && $product->pret_intreg)
                        <del>{{ $fullPrice ?? ($tva_inclus ? $product->pret_intreg_cu_tva : $product->pret_intreg) }} lei</del>
                    @endif
                    <ins>{{ $price ?? ($tva_inclus ? $product->pret_cu_tva : $product->pret_normal) }} lei</ins>
                    @if($tva_inclus)
                        <br>
                        <small>({{ __('TVA inclus') }})</small>
                    @endif
                </h5>
                <x-rating :rating="$rating ?? $product->rating" />
                @if(isset($id) || isset($product))
                    <a href="javascript:void(0)" data-cart-route="{{ route('cart.add', $id ?? $product->id) }}" class="button custom-white-space-button button-fill fill-from-bottom button-color-default"><span><i class="icon-line-shopping-cart"></i>{{ __('Adauga in cos') }}</span></a>
                @endif
            </div>
        </div>
    </div>
</div>