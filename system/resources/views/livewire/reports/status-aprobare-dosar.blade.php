<div>
    @if($this->title)
        <h3 class="text-center mb-5">{{ $this->title }}</h3>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('Status dosare') }}</th>
                @forelse($regiuni as $regiune => $nume)
                    <th>{{ $nume }}</th>
                @empty
                    <th>{{ __('Numar dosare (doar cele fara judet)') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($limite as $limita => $name)
                @if(!empty($limita))
                    <tr>
                        <td>{{ __($name) }}</td>
                        @forelse($regiuni as $regiune => $nume)
                            <td><a href="{{ $this->link($limita, $regiune) }}">{{ $items[$limita][$regiune] ?? 0 }}</a></td>
                        @empty
                            <td><a href="{{ $this->link($limita) }}">{{ $items[$limita] ?? 0 }}</a></td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>