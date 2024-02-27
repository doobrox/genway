<table border="1" cellspacing="0" class="generatoare">
	<tbody>
		<tr>
			<td>Nr. crt.</td>
			<td>Nr. UG</td>
			<td>Tip UG (As, S)</td>
			<td>Tip UG (T,H,E)</td>
			<td>U (V)</td>
			<td>Un UG (V)</td>
			<td>Pn UG (kW)</td>
			<td>Sn UG (kW)</td>
			<td>Pi total (kW)</td>
			<td>Pmax produsă de UG (kW)</td>
			<td>Pmin produsă de UG (kW)</td>
			<td>Qmax (kVAr)</td>
			<td>Qmin (kVAr)</td>
			<td>Sevac (kVA)</td>
			<td>Observații</td>
		</tr>
		@for($i = 1 ; $i <= 4 ; $i++)
			<tr>
				@for($j = 1 ; $j <= 15 ; $j++)
					<td>{!! $i == 1 ? $j : '__SPATIU__' !!}</td>
				@endfor
			</tr>
		@endfor
		<tr>
			<td colspan="8">TOTAL:</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>