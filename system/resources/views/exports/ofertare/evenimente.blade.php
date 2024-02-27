<table>
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