<table>
	<thead>
		<tr>
			<th>
				{{ __('Echipa') }}.............................<br>
				{{ __('Data') }}.............................
			</th>
			<th>{{ __('TOTAL MATERIALE') }}</th>
			@foreach($formulare as $formular)
				<th>{{ $formular['nume'] }} {{ '&' }} <br>{{ $formular['prenume'] }} <br>{{ __('NECESAR') }}</th>
				<th>{{ $formular['nume'] }} {{ '&' }} <br>{{ $formular['prenume'] }} <br>{{ __('UTILIZAT') }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($produse as $produs)
			@php $row = $loop->iteration + 1; @endphp
			<tr>
				<td>{{ $produs }}</td>
				<td>{{ '=SUM(C'.$row.':'.$highestColumn.$row.')' }}</td>
				@foreach($formulare as $formular)
					<td>
						@if(
							$produs == $formular['cod']
							|| $produs == 'SM-'.$formular['cod']
						) 1
						@elseif($produs == 'Panouri fotovoltaice '.($formular['putere_panouri'] + 0).'W')
							{{ $formular['numar_panouri'] }}
						@elseif($produs == 'SP.01')
							{{ $formular['numar_sp-uri'] }}
						@elseif($produs == 'Sina R1 2.1')
							{{ $formular['numar_panouri'] % 3 }}
						@elseif($produs == 'Sina R1 3.1')
							{{ intval($formular['numar_panouri'] / 3) * 2 }}
						@elseif($produs == 'Clema capat EC-01')
							4 
						@elseif($produs == 'Clema de mijloc MC-01')
							{{ ($formular['numar_panouri'] - 1) * 2 }}
						@elseif($produs == 'Sina de imbinare RS')
							{{ intval(($formular['numar_panouri'] - 1) / 3) * 2 }}
						@elseif(strtoupper($produs) == strtoupper($formular['tipul_invelitorii']) && in_array($formular['baza_invelitoare'], ['tigla', 'tabla']))
							{{ $formular['numar_panouri'] * 2 + 4 }}
						@elseif(strtoupper($produs) == strtoupper($formular['tipul_invelitorii']) && $formular['baza_invelitoare'] === 'sandwich')
							{{ 4 /* EC value */ + (($formular['numar_panouri'] - 1) * 2) /* MC value */ }}
						@endif
					</td>
					<td></td>
				@endforeach
			</tr>
		@endforeach
	</tbody>
</table>