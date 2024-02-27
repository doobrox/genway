@props([
    'item',
    'column'
])

@if($item[$column->nume])
    @php
        $file = \App\Models\Fisier::find($item[$column->nume]);
    @endphp
    @if($file)
        <a class="dfile_tabel" href="{{ route('ofertare.aws.get', $file->path.$file->name) }}" download><i class="fa fa-download"></i></a>
    @endif
@endif

