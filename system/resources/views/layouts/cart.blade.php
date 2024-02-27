<!-- Top Cart
============================================= -->
<div id="top-cart" class="header-misc-icon d-sm-block">
    <a href="javascript:void(0)" id="top-cart-trigger">
        <i class="icon-line-shopping-cart"></i>
        @if(isset($cart['items']) && count($cart['items']) > 0)
            <span class="top-cart-number">{{ count($cart['items']) }}</span>
        @endif
    </a>
    <div class="top-cart-content">
        <div class="top-cart-title">
            <h4>{{ __('Cosul meu') }} <i id="top-cart-close" class="icon-line-cross"></i></h4>
        </div>
        <div class="top-cart-items">
            @forelse($cart['items'] ?? [] as $item)
                <!-- item -->
                <div class="top-cart-item">
                    <div class="top-cart-item-image"><a href="{{ $item['route'] }}"><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"></a></div>
                    <div class="top-cart-item-desc align-items-stretch">
                        <div class="top-cart-item-desc-title">
                            <a href="{{ $item['route'] }}" title="{{ $item['name'] }}">{{ mb_strimwidth($item['name'], 0, 50, '...') }}</a>
                            <span class="top-cart-item-price d-block">{{ $item['total_with_tva'] }} lei</span>
                        </div>
                        <div class="top-cart-info">
                            <div class="top-cart-item-quantity text-center">x {{ $item['qty'] }}</div>
                            <div class="top-cart-item-action feature-box fbox-center fbox-dark fbox-effect w-100 m-0">
                                <a href="javascript:void(0)" class="fbox-icon w-100 m-0" data-cart-route="{{ route('cart.remove', $item['id']) }}">
                                    <i class="icon-line-cross"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div>{{ __('Nu ai niciun produs in cos') }}</div>
            @endif
        </div>
        <div class="top-cart-action">
            <span class="top-checkout-price">{{ $cart['total_with_tva'] ?? '0' }} lei</span>
            <a href="{{ route('cart') }}" class="button custom-white-space-button button-small text-center m-0">{{ __('Vezi detalii cos') }}</a>
        </div>
    </div>
</div><!-- #top-cart end -->