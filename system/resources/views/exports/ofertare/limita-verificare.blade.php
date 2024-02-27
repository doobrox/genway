<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('Judete') }}/{{ __('Zile') }}</th>
                <td>{{ __('0-4 zile') }}</td>
                <td>{{ __('5-10 zile') }}</td>
                <td>{{ __('11-20 zile') }}</td>
                <td>{{ __('21-25 zile') }}</td>
                <td>{{ __('Intarziate') }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('Fara judet') }}</td>
                <td>{{ $items['']['0_4'] ?? 0 }}</td>
                <td>{{ $items['']['5_10'] ?? 0 }}</td>
                <td>{{ $items['']['11_20'] ?? 0 }}</td>
                <td>{{ $items['']['21_25'] ?? 0 }}</td>
                <td>{{ $items['']['intarziate'] ?? 0 }}</td>
            </tr>
            @foreach($judete as $judet)
                <tr>
                    <td>{{ $judet['nume'] }}</td>
                    <td>{{ $items[$judet['id']]['0_4'] ?? 0 }}</td>
                    <td>{{ $items[$judet['id']]['5_10'] ?? 0 }}</td>
                    <td>{{ $items[$judet['id']]['11_20'] ?? 0 }}</td>
                    <td>{{ $items[$judet['id']]['21_25'] ?? 0 }}</td>
                    <td>{{ $items[$judet['id']]['intarziate'] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> 
</div>