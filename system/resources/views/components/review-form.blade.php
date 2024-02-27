@props(['id' => false,'route' => null,'product'])

<div {{ $attributes->merge(['class' => 'p-2']) }}>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('review')" />
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" :bag="'review'" />
    <form class="row mb-0" @if($id) id="{{ $id }}" @endif method="post" name="template-reviewform" 
        action="{{ $route ?? route('product.review', [
            'slug' => $product->slug,
            'produs' => $product->id,
        ]) }}">
        @csrf
        <div class="col-12 mb-2">
            <label for="rating-input">{{ __('Evaluare') }} <small>*</small></label>
            
            <div class="white-section">
                <input id="rating-input" type="number" class="rating" name="evaluare" max="5" data-step="1" data-size="sm">
            </div>
        </div>

        <div class="w-100"></div>

        {{-- <div class="col-12 mb-3">
            <label for="template-reviewform-name">{{ __('Nume') }} <small>*</small></label>
            <div class="input-group">
                <div class="input-group-text"><i class="icon-user"></i></div>
                <input type="text" id="template-reviewform-name" name="template-reviewform-name" value="" class="form-control required" />
            </div>
        </div> --}}
        {{-- <div class="col-12 mb-3">
            <label for="template-reviewform-email">{{ __('Email') }} <small>*</small></label>
            <div class="input-group">
                <div class="input-group-text">@</div>
                <input type="email" id="template-reviewform-email" name="template-reviewform-email" value="" class="required email form-control" />
            </div>
        </div> --}}


        <div class="w-100"></div>

        <div class="col-12 mb-3">
            <label for="comentariu">{{ __('Comentariu') }} <small>*</small></label>
            <textarea class="required form-control" id="comentariu" name="comentariu" rows="6" cols="30"></textarea>
        </div>

        <div class="col-12">
            <button class="button button-3d m-0" type="submit" id="reviewform-submit" name="template-reviewform-submit">{{ __('Posteaza') }}</button>
        </div>

    </form>
</div>