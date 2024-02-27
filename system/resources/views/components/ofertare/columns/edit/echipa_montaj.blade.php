@props(['section' => '2021', 'column', 'item', 'types' => \App\Models\Ofertare\ColoanaTabelAFM::types(), 'form' => true])

@php $programare = $item->programare; @endphp

@if($programare)
	<ul>
		@foreach($programare->echipa as $membru)
			@if($membru->pivot->lider)
				<li><b>{{ $membru->nume }} {{ $membru->prenume }} ({{ $membru->pivot->procent }}%) (Lider)</b></li>
			@else
				<li>{{ $membru->nume }} {{ $membru->prenume }} ({{ $membru->pivot->procent }}%)</li>
			@endif
		@endforeach
	</ul>
@endif
<div class="text-right">
	<a class="btn btn-success img-rounded add_item control-label" target="_blank"
	@if($programare)
		href="{{ route('ofertare.programari.edit', $programare->id) }}"
    	title="{{ __('Editeaza echipa programare') }}">
    	<i class="{{ 'fa fa-pencil' }}"></i> {{ __('Editeaza echipa programare') }}
	@else
		href="{{ route('ofertare.programari.create.form', [$section, $item->id]) }}"
    	title="{{ __('Adauga echipa programare') }}">
    	<i class="{{ 'fa fa-plus' }}"></i> {{ __('Adauga echipa programare') }}
	@endif
    </a>
</div>
