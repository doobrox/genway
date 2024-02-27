@props(['id' => 'content'])

<section id="{{ $id }}">
    <div class="content-wrap custom-content-wrap">
        <div class="container clearfix">
            {{ $slot }}
        </div>
    </div>
</section>