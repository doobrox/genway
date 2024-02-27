<table>
	<thead>
		@php
			$style = 'background:#000000;color:#ffffff;font-weight:500;padding:5px;text-align:center;';
		@endphp
		<tr>
			<th style="{{ $style }}">{{ __('Putere panouri (W)') }}</th>
			<th style="{{ $style }}">{{ __('Nr. Panouri') }}</th>
		</tr>
	</thead>
	<tbody>
		@forelse($panouri as $putere => $valoare)
			<tr>
				<td>{{ $putere + 0 }}</td>
				<td>{{ $valoare }}</td>
			</tr>
		@empty
		@endforelse
	</tbody>
</table>