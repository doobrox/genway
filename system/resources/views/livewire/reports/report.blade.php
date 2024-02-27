<div>
    @if($this->title)
        <h3 class="text-center mb-5">{{ $this->title }}</h3>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ $this->statusHeader() }}</th>
                @forelse($headers as $header => $nume)
                    <th>{{ $nume }}</th>
                @empty
                    <th>{{ $this->generalHeader() }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($limits as $limita => $name)
                @if(!empty($limita))
                    <tr>
                        <td>{{ __($name) }}</td>
                        @forelse($headers as $header => $nume)
                            <td><a href="{{ $this->link($limita, $header) }}">{{ $items[$limita][$header] ?? 0 }}</a></td>
                        @empty
                            <td><a href="{{ $this->link($limita) }}">{{ $items[$limita] ?? 0 }}</a></td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>