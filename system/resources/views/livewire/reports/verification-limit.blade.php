<div>
    @if($this->title)
        <h3 class="text-center mb-5">{{ $this->title }}</h3>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('Zile ramase pana la verificare') }}</th>
                @forelse($headers as $header => $nume)
                    <th>{{ $nume }}</th>
                @empty
                    <th>{{ __('Numar dosare (doar cele fara judet)') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($limits as $limita => $value)
                <tr>
                    <td>{{ $value ? __(':limit zile', ['limit' => str_replace('_', '-', $limita)]) : __(ucfirst($limita)) }}</td>
                    @forelse($headers as $header => $nume)
                        <td><a href="{{ $this->link($limita, $header) }}">{{ $items[$limita][$header] ?? 0 }}</a></td>
                    @empty
                        <td><a href="{{ $this->link($limita) }}">{{ $items[$limita] ?? 0 }}</a></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>