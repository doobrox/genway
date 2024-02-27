@props(['rating' => false])

@if($rating)
<!-- Product Single - Rating
============================================= -->
<div {{ $attributes->merge(['class' => 'rating-container theme-krajee-svg custom-rating-xs rating-animate rating-disabled']) }}>
    <div class="rating-stars custom-rating-stars-cursor-unset" tabindex="0">
        <span class="empty-stars">
            @for($i = 1 ; $i <= 5 ; $i++)
                <span class="star custom-star" title="{{ $i }} Star">
                    @if($i <= $rating)
                        <span class="icon-star3"></span>
                    @elseif($i > $rating && ($i - 1) < round($rating, 1))
                        <span class="icon-star-half-full"></span>
                    @else
                        <span class="icon-star-empty"></span>
                    @endif
                </span>
            @endfor
        </span>
        {{-- <span class="empty-stars">
                <span class="star custom-star" title="One Star">
                    <span class="icon-star-empty"></span>
                </span>
                <span class="star custom-star" title="Two Stars">
                    <span class="icon-star-empty"></span>
                </span>
                <span class="star custom-star" title="Three Stars">
                    <span class="icon-star-empty"></span>
                </span>
                <span class="star custom-star" title="Four Stars">
                    <span class="icon-star-empty"></span>
                </span>
                <span class="star custom-star" title="Five Stars">
                    <span class="icon-star-empty"></span>
                </span>
            </span>
            <span class="filled-stars">
                <span class="star custom-star" title="One Star">
                    <span class="icon-star3"></span>
                </span>
                <span class="star custom-star" title="Two Stars">
                    <span class="icon-star3"></span>
                </span>
                <span class="star custom-star" title="Three Stars">
                    <span class="icon-star3"></span>
                </span>
                <span class="star custom-star" title="Four Stars">
                    <span class="icon-star3"></span>
                </span>
                <span class="star custom-star" title="Five Stars">
                    <span class="icon-star-half-full"></span>
                </span>
            </span> --}}
    </div>
</div>
<!-- Product Single - Rating End -->
@endif