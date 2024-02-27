<table>
	<thead>
		@php
			$style = 'background:#000000;color:#ffffff;font-weight:500;padding:5px;text-align:center;min-width:30px;';
			// $puteri = [3, 5, 6, 8.2, 10, 15, 20, 27];
		@endphp
		<tr>
			<th style="{{ $style }}" rowspan="2">{{ __('Marca') }}</th>
			<th style="{{ $style }}" rowspan="2">{{ __('Tip invertor') }}</th>
			<th style="{{ $style }}" colspan="{{ count($puteri) }}">{{ __('Putere invertor') }}</th>
		</tr>
		<tr>
			@foreach($puteri as $putere)
				<th style="{{ $style }}" colspan="">{{ ($putere + 0).'kW' }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@forelse($invertoare as $marca => $invertor)
			<tr>
				<td rowspan="2">{{ $marca }}</td>
				<td>{{ __('Monofazat') }}</td>
				@foreach($puteri as $putere)
					<td>{{ $invertor['monofazat'][$putere][0]['total'] ?? null }}</td>
				@endforeach
			</tr>
			<tr>
				<td>{{ __('Trifazat') }}</td>
				@foreach($puteri as $putere)
					<td>{{ $invertor['trifazat'][$putere][0]['total'] ?? null }}</td>
				@endforeach
			</tr>
		@empty
		@endforelse
	</tbody>
</table>