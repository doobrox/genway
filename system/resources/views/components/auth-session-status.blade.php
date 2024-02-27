@props(['status','message' => null])

@if ($status)
    <div {{ $attributes->merge(['class' => 'style-msg successmsg px-0']) }}>
        <div class="sb-msg">
            <i class="icon-check-circle"></i>
            {{ $message ?? $status }}
        </div>
    </div>
@endif
