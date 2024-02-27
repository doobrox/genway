@component('mail::message')

{!! $details['body'] !!}


@isset($details['url'])
@isset($details['button_text'])
{{ $details['button_text'] }}
@endisset

@component('mail::button', ['url' => $details['url']])
{{ __('Login') }}
@endcomponent

@endisset

<div style="border-bottom: 1px dashed #777777; border-top: 1px dashed #777777; padding: 10px 0; font-size: 13px; color: #777777;">
    Ati primit acest email deoarece aceasta adresa de email a fost folosita la inscrierea pe www.genway.ro. Daca acest mesaj a ajuns din greseala va rugam sa ne <a href="{{ route('contact') }}" style="color: #2B57C7; text-decoration: none !important;">contactati</a>
</div>
@endcomponent
