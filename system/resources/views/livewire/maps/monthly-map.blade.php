<div>
    @if($this->title)
        <h3 class="text-center">{{ $this->title }}</h3>
    @endif
    @if($this->map_id == 'monthly-map')
        <div class="text-center pt-3 pb-3">
            <span>{{ __('Raport pentru contractele semnate in urma cu') }}</span>
            <select wire:model.live="luna" wire:change="updateMonth">
                @for($i = 5 ; $i <= 12 ; $i++)
                    <option value="{{ $i }}">{{ $i }} luni</option>
                @endfor
            </select>
        </div>
    @endif
    <div id="{{ $this->map_id }}" style="max-width: 900px; padding: 10px; margin: 0 auto;"></div>
    <script type="text/javascript">
        var simplemaps_countrymap_mapdata = {!! $settings !!};
        let {{ \Str::camel($this->map_id) }} = simplemaps_countrymap.create();
    </script>
</div>