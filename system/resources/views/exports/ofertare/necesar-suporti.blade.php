<table>
	<thead>
		@php
			$style = 'background:#000000;color:#ffffff;font-weight:500;padding:5px;text-align:center;';
		@endphp
		<tr>
			<th style="{{ $style }}">{{ __('Sir') }}</th>
			<th style="{{ $style }}">{{ __('Cant.') }}</th>
			<th style="{{ $style }}">{{ __('Sina R1 - 2.2m') }}</th>
			<th style="{{ $style }}">{{ __('Sina R1 - 3.3m') }}</th>
			<th style="{{ $style }}">{{ __('RS') }}</th>
			{{-- <th style="{{ $style }}">{{ __('MRH-06A') }}</th> --}}
			{{-- <th style="{{ $style }}">{{ __('TRH-01A') }}</th> --}}
			<th style="{{ $style }}">{{ __('MRH') }}</th>
			<th style="{{ $style }}">{{ __('TRH') }}</th>
			<th style="{{ $style }}">{{ __('MRH-Sandwich') }}</th>
			<th style="{{ $style }}">{{ __('EC-01') }}</th>
			<th style="{{ $style }}">{{ __('MC-01') }}</th>
		</tr>
	</thead>
	<tbody>
		@php
			$total = [
				'cant' => 0,
				'sina_22' => 0,
				'sina_33' => 0,
				'RS' => 0,
				'MRH' => 0,
				'TRH' => 0,
				'MRH_SW' => 0,
				'EC' => 0,
				'MC' => 0
			];
		@endphp
		@forelse($siruri as $sir => $invelitori)
			@php
				$nr = (int)str_replace('1x', '', $sir);
				$count_tigla = count($invelitori['tigla'] ?? []);
				$count_tabla = count($invelitori['tabla'] ?? []);
				$count_sandwich = count($invelitori['sandwich'] ?? []);
				$cant = $count_tigla + $count_tabla + $count_sandwich;
				$total['cant'] += $cant;
			@endphp
			<tr>
				<td>{{ $sir }}</td>
				<td>{{ $cant }}</td>
				<td>{{ $var = $cant * ($nr % 3) }}</td> @php $total['sina_22'] += $var; @endphp
				<td>{{ $var = $cant * (intval($nr / 3) * 2) }}</td> @php $total['sina_33'] += $var; @endphp
				<td>{{ $var = $cant * (intval(($nr - 1) / 3) * 2) }}</td> @php $total['RS'] += $var; @endphp
				<td>{{ $var = $count_tabla * ($nr * 2 + 4) }}</td> @php $total['MRH'] += $var; @endphp
				<td>{{ $var = $count_tigla * ($nr * 2 + 4) }}</td> @php $total['TRH'] += $var; @endphp
				<td>{{ $var = $count_sandwich * (4 /* EC value */ + (($nr - 1) * 2) /* MC value */) }}</td> @php $total['MRH_SW'] += $var; @endphp
				<td>{{ $var = $cant * 4 }}</td> @php $total['EC'] += $var; @endphp
				<td>{{ $var = $cant * (($nr - 1) * 2) }}</td> @php $total['MC'] += $var; @endphp
			</tr>
		@empty
		@endforelse
	</tbody>
	<tfoot>
		@php
			$style = 'background:#4682B4;color:#ffffff;font-weight:500;padding:5px;';
		@endphp
		<tr>
			<th style="{{ $style }}" colspan="2">{{ __('TOTAL') }}</th>
			<th style="{{ $style }}">{{ $total['sina_22'] }}</th>
			<th style="{{ $style }}">{{ $total['sina_33'] }}</th>
			<th style="{{ $style }}">{{ $total['RS'] }}</th>
			<th style="{{ $style }}">{{ $total['MRH'] }}</th>
			<th style="{{ $style }}">{{ $total['TRH'] }}</th>
			<th style="{{ $style }}">{{ $total['MRH_SW'] }}</th>
			<th style="{{ $style }}">{{ $total['EC'] }}</th>
			<th style="{{ $style }}">{{ $total['MC'] }}</th>
		</tr>
	</tfoot>
</table>