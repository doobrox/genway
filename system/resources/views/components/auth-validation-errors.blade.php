@props(['errors', 'bag' => null])

@if ($bag != null && $errors->getBag($bag) && $errors->$bag->any())
    <div {{ $attributes->merge(['class' => 'style-msg2 errormsg px-0']) }}>
        <div class="msgtitle">
            <i class="icon-remove-sign"></i>
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <div class="sb-msg">
            <ul>
                @foreach ($errors->$bag->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'style-msg2 errormsg px-0']) }}>
        <div class="msgtitle">
            <i class="icon-remove-sign"></i>
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <div class="sb-msg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
