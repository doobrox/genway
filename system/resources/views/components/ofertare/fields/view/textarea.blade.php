@props([
    'item',
    'column',
    'count' => 75 
])

@if(strlen($item[$column->nume]) > $count)
    {{ substr($item[$column->nume], 0, $count) }}...
    <button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#{{ $column->nume.'_'.$item['id'] }}_modal">+ Mai mult</button>
    <div class="modal fade" id="{{ $column->nume.'_'.$item['id'].'_modal' }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalLabel">{{ $column->titlu ?? '#'.$item->id. ' ' .$column->nume }}</h4>
                </div>
                <div class="modal-body">{!! nl2br($item['nume_'.$column->nume] ?? $item[$column->nume]) !!}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Inchide') }}</button>
                </div>
            </div>
        </div>
    </div>
@else 
    <div style="width: 125px">{!! nl2br($item[$column->nume]) !!}</div>
@endif