<div>
    @if($this->title)
        <h3 class="text-center">
            {{ $this->title }}
        </h3>
    @endif
    @isset($this->export)
        <div class="text-center">
            <button class="btn btn-circle green-jungle" type="button" wire:click="exportExcel">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                <span>{{ __('Export') }}</span>
            </button>
        </div>
    @endisset
    @if($this->component_id == 'monthly-map')
        <div class="text-center pt-3 pb-3">
            <span>{{ __('Raport pentru contractele semnate in urma cu') }}</span>
            <select wire:model.live="luna" wire:change="updateMonth">
                @for($i = 5 ; $i <= 12 ; $i++)
                    <option value="{{ $i }}">{{ $i }} {{ __('luni') }}</option>
                @endfor
                <option value="">{{ __('toate') }}</option>
            </select>
        </div>
    @endif
    @if($this->component_id == 'dosare-depuse-map')
        <div class="text-center pt-3 pb-3">
            <span>{{ __('Raport dosare depuse in luna') }}</span>
            <select wire:model.live="luna" wire:change="updateMonth">
                @foreach($this->months as $month => $name)
                    <option value="{{ $month }}" @selected($month == $this->luna)>{{ $name }}</option>
                @endforeach
            </select>
            <span> anul </span>
            <select wire:model.live="an" wire:change="updateYear">
                @for($i = 2022 ; $i <= date('Y') ; $i++)
                    <option value="{{ $i }}" @selected($i == $this->an)>{{ $i }}</option>
                @endfor
            </select>
        </div>
    @endif
    <div wire:ignore id="{{ $this->component_id }}" style="max-width: 900px; padding: 10px; margin: 0 auto;"></div>
    @if($description)
        <div class="alert alert-info">
            {{ $description['list_start'] ?? '' }}
            <ul>
                @foreach($description['columns'] as $column => $text)
                    <li>{!! $text !!}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <script type="text/javascript">
        var simplemaps_countrymap_mapdata = {!! $settings !!};
        let {{ \Str::camel($this->component_id) }} = simplemaps_countrymap.create();
    </script>
</div>