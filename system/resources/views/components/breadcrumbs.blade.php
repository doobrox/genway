@props(['breadcrumbs'])

@isset($breadcrumbs)
<!-- Page Breadcrumbs
============================================= -->
<section id="custom-breadcrumbs">
    <div class="container clearfix">
        <ol class="breadcrumb">
                @foreach($breadcrumbs as $title => $route)
                    @if($route == 'active')
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $route }}">{{ $title }}</a></li>
                    @endif
                @endforeach
        </ol>
    </div>
</section><!-- #custom-breadcrumbs end -->
@endisset