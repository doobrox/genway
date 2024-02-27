<div>
    @if($this->title)
        <h3 class="text-center">{{ $this->title }}</h3>
    @endif

    <div class="text-center pt-3 pb-3" id="{{ $this->component_id }}-search">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form form-inline" wire:submit="search">
            <div class="inline-block">{{ __('Cauta raporte intre datele') }}</div>
            <x-ofertare.fields.edit.date
                wire:model.live="data_inceput"
                name="data_inceput"
                autocomplete="off"
                onchange="this.dispatchEvent(new InputEvent('input'))" {{-- necesary for livewire to update the value --}}
                :placeholder="__('Data inceput')"
                :offset="false"
                :row="false"
            />

            <x-ofertare.fields.edit.date
                wire:model.live="data_sfarsit"
                name="data_sfarsit"
                autocomplete="off"
                onchange="this.dispatchEvent(new InputEvent('input'))" {{-- necesary for livewire to update the value --}}
                :placeholder="__('Data sfarsit')"
                :offset="false"
                :row="false"
            />

            <x-ofertare.fields.edit.checkbox
                wire:model.live="per_zile"
                :value="1"
                :text=" __('Per zile')"
                :offset="false"
                :row="false"
            />

            <button class="btn btn-circle btn-info" type="submit">{{ __('Cauta') }}</button>
            <button class="btn btn-circle green-jungle" type="button" wire:click="export">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                <span>{{ __('Export') }}</span>
            </button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('Data:') }}</th>
                <th>{{ __('Tip operatie') }}</th>
                @foreach($users as $user)
                    <th>{{ $user->nume }} {{ $user->prenume }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($period ?? ['*'] as $date)
                @if($date == '*' || in_array($date->timestamp, $dates))
                    @foreach($evenimente as $eveniment)
                        <tr>
                            @if ($loop->first)
                                <td style="text-align: center; vertical-align: middle;" rowspan="{{ count($evenimente) }}">{{ $date == '*' ? __('Toate') : $date->format('d.m.Y') }}</td>
                            @endif
                            <td>{{ $eveniment }}</td>
                            @foreach($users as $user)
                                <td>{{ $date == '*' ? ($items[$user->id][$eveniment] ?? '') : ($items[$user->id][$eveniment][$date->format('Y-m-d')] ?? '') }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    @if(method_exists($items, 'links'))
        {{ $items->withQueryString()->links() }}
    @endif
</div>
