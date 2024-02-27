<!DOCTYPE html>
<html>
    <head>
        <title>Proces verbal PIF {{ $distribuitor_energie }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            *, html, body {
                font-size: 12px;
                font-family: "DejaVu Sans", sans-serif;
            }
            @page { margin-top: 60px; }
            header{
            	position:fixed;
            	top: -30px;
            	width: 100%;
            }
            p {
            	max-width: 100%;
            }
            hr {
            	width: 96%;
            	border: none;
            	border-bottom: 1px solid black;
            	margin: 16px 0 1em 0;
            }
            ol {
            	margin: 0;
            	padding-left: 2em;
            	list-style-position: outside;
            }
            ol li {
            	margin-bottom: 1em;
            }
            .title {
                font-size: 14px;
                font-weight: 700;
            }
            .section-title {
                font-size: 13px;
                font-weight: 700;
            }
            .text-center {
                text-align: center;
            }
            .text-left {
                text-align: left;
            }
            .font-bold {
            	font-weight: 700;
            }
            .w-full {
            	width: 100%;
            }
            .block {
            	display: block;
            }
            .inline-block {
            	display: block;
            }
            .left {
            	float: left;
            }
            .right {
            	float: right;
            }
            .clear {
            	clear: both;
            }
            .pl-0 {
            	padding-left: 0!important;
            }
            ul.list {
            	margin-left: 0;
            	padding-left: 2em;
            	list-style: none outside url('data:image/gif;base64,R0lGODlhBQAKAIABAAAAAP///yH5BAEAAAEALAAAAAAFAAoAAAIIjI+ZwKwPUQEAOw==');
            }
            ul.list li {
            	margin-bottom: 0.5em;
            }
            ol.sublist {
            	counter-reset: list;
            }
            ol.sublist > li {
			  	list-style: none;
			  	position: relative;
			  	margin-bottom: 0.5em;
			}
			ol.sublist > li:before {
			  	content: counter(list, lower-alpha) ") ";
			  	counter-increment: list;
			  	position: absolute;
    			left: -1.4em;
			}
            table {
                border: 1px solid #000;
                width: 100%;
                max-width: 100%;
                border-collapse: collapse;
/*                text-align: center;*/
            }
            /*table th, table td {
                padding-left: 10px;
                padding-right: 10px;
            }*/
            table th, table td {
                padding: 5px;
            }
            table ul {
                padding: 0;
                margin: 0;
            }
            table ul li{
            	list-style: none inside url('data:image/gif;base64,R0lGODlhBQAKAIABAAAAAP///yH5BAEAAAEALAAAAAAFAAoAAAIIjI+ZwKwPUQEAOw==');
            }
            table.items {
                border: 1px solid #000;
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }
            table.items td, table.items th{
                border: 1px solid #000;
            }
            table.simple {
                border: none;
                border-width: 0;
                width: 100%;
                max-width: 100%;
                border-collapse: collapse;
            }
            table.simple td, table.simple th {
                padding: 0;
                padding-right: 7px;
                vertical-align: top;
            }
        </style>
    </head>
    <body>
		<div>
			<p class="text-center">
				<strong class="title">PROCES VERBAL DE PUNERE ÎN FUNCȚIUNE<br>
				nr. {{ $nr_cerere_pif ?? '....' }} din {{ date('d/m/Y') }}</strong>
			</p>

			<p><strong><u>PARTILE CONTRACTANTE</u></strong></p>
			<p>
				<strong>{{ $fi ? $fi->nume_firma : '...................' }}</strong>, cu sediul în BVD. CHISINAU, NR. 8, BL. M2, SC. C, AP. 84, loc. SECTOR 2, jud. SECTOR 2, telefon 0747773031, email: office@genway.ro, înregistrată la Oficiul National al Registrului Comerțului sub nr. {{ $fi ? $fi->reg_com_firma : 'J40/4862/23.04.2015' }}, cod unic de înregistrare {{ $fi ? $fi->cod_fiscal_firma : 'RO21241885' }}, cu atestat ANRE {{ $fi ? $fi->nr_autorizare : '13692/28.09.2018' }} reprezentată legal prin POPA ALEXANDRU. în calitate de <strong>Executant</strong>,


				<br>și<br>

				Subsemnatul/a {{ $nume }} {{ $prenume }}, domiciliat/a în <strong>județul {{ $nume_judet_imobil }}, municipiul/oraşul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong> în calitate de <strong>Beneficiar</strong>
				<br><br>

				S-a procedat la <strong>recepția</strong> și <strong>punerea în funcțiune a obiectivului în județul {{ $nume_judet_imobil }}, municipiul/oraşul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong> cu Centrală Fotovoltaică {{ $putere_invertor }} kW cod loc consum {{ $cod_loc_consum ?? '................' }}, alcatuită din urmatoarele echipamente:
			</p>

			<table class="items">
				<thead>
					<tr style="font-weight: 700;">
						<th style="width:60px;">Nr. crt.</th>
						<th class="text-left">Denumire articol</th>
						<th style="width:110px;">Serie echipamente </th>
						<th style="width:60px;">U.M.</th>
						<th style="width:60px;">Cant</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>0</td>
						<td>1</td>
						<td>2</td>
						<td>3</td>
						<td>4</td>
					</tr>
					<tr>
						<td>1</td>
						<td class="text-left">Panou fotovoltaic MONOCRISTALIN JA SOLAR</td>
						<td></td>
						<td></td>
						<td>{{ $numar_panouri}}</td>
					</tr>
					<tr>
						<td>2</td>
						<td class="text-left">Invertor <strong>{{ $marca_invertor }}, {{ $putere_invertor }} kW</strong></td>
						<td>{{ $serie_invertor ?? '' }}</td>
						<td></td>
						<td>1</td>
					</tr>
					<tr>
						<td>3</td>
						<td class="text-left">Contor</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>4</td>
						<td class="text-left">Materiale auxiliare (struct. acoperis, protectii, conectica, cabluri)</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>5</td>
						<td class="text-left">....</td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>

			<p>Partile au constatat la receptionarea lucrărilor îndeplinirea cerințelor: </p>

			<table class="simple" style="page-break-inside: avoid;">
				<tr>
					<td style="width: 25%;"></td>
					<td style="width: 5%;">Vizuale</td>
					<td>
						<ul class="list pl-0">
							<li>integritatea echipamentelor</li>
							<li>existenta tuturor dotarilor comandate </li>
						</ul>
					</td>
				</tr>
				<tr>
					<td style="width: 25%;"></td>
					<td style="width: 5%;">Tehnice</td>
					<td>
						<ul class="list pl-0">
							<li>masurat dimensiuni</li>
							<li>verificare caracteristici tehnice </li>
						</ul>
					</td>
				</tr>
				<tr>
					<td style="width: 25%;"></td>
					<td style="width: 5%;">Functionale</td>
					<td>
						<ul class="list pl-0">
							<li>comenzi</li>
							<li>instalatie electrica</li>
						</ul>
					</td>
				</tr>
			</table>

			<p>Teste: funcționarea tuturor echipamentelor și comenzilor pentru instalația de producere fotovoltaica <strong>{{ $putere_invertor }}</strong> kW monofazata.</p>

			<p>Încheiat astăzi <strong>{{ date('d/m/Y') }}</strong> în două exemplare originale cate unul pentru fiecare parte.</p>

			<table class="simple" style="page-break-inside: avoid;">
	            <tr>
	                <td style="width: 10%;">&nbsp;</td>
	                <td class="text-left" style="vertical-align:top;">
	                    <strong>Executant,</strong><br>
	                    <strong>Am predat,</strong><br><br>
	                    {{ $fi ? $fi->nume_firma : '...................' }}<br>
	                    Ing. POPA ALEXANDRU
	                </td>
	                <td align="left" style="width: 30%;"><img src="{{ $base64_stamp_img }}" style="width:125px; height:auto;"></td>
	                <td class="text-left" style="vertical-align:top;">
	                    <strong>Beneficiar,</strong><br>
	                    <strong>Am primit,</strong><br><br>
	                    {{ $nume }} {{ $prenume }}
	                </td>
	                <td style="width: 10%;">&nbsp;</td>
	            </tr>
	        </table>

			<div style="page-break-after:always;"></div>

			<p class="text-center">
				<strong class="title">DECLARAȚIA EXECUTANTULUI</strong><br>
				NR. {{ $nr_cerere_pif ?? '....' }} din {{ date('d/m/Y') }}<br>
				<u>Conform Ordinului ANRE 59/2013 art. 54 alin. (1)</u>
			</p>
			<br>
			<p>
				<strong>{{ $fi ? $fi->nume_firma : '...................' }}</strong> cu sediul în BVD. CHISINAU, NR. 8, BL. M2, SC. C, AP. 84, loc. SECTOR 2, jud. SECTOR 2, telefon 0747773031, email: office@genway.ro, înregistrată la Oficiul Național al Registrului Comerțului sub nr. {{ $fi ? $fi->reg_com_firma : 'J40/4862/23.04.2015' }}, cod unic de înregistrare {{ $fi ? $fi->cod_fiscal_firma : 'RO21241885' }}, cu atestat ANRE {{ $fi ? $fi->nr_autorizare : '13692/28.09.2018' }} reprezentată legal prin POPA ALEXANDRU, în calitate de executant, confirmăm prin prezenta declarație pentru obiectivul cu codul locului de consum {{ $cod_loc_consum ?? '................' }} situat în <strong>județul {{ $nume_judet_imobil }}, municipiul/oraşul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong> având ca beneficiar pe {{ $nume }} {{ $prenume }} domiciliat în <strong>județul {{ $nume_judet_domiciliu }}, municipiul/oraşul/comuna/satul/sectorul {{ $nume_localitate_domiciliu }}, str. {{ $strada_domiciliu }}, nr. {{ $numar_domiciliu }}</strong>.
			</p>
			<p>
				<ul class="list">
					<li>
						respectarea cerințelor din Avizul Tehnic de Racordare nr. .............................. / ........................... emis de DELGAZ-GRID S.A. ,........................................................... pentru obiectivul mai sus menționat, (daca este cazul);
					</li>
					<li>
						realizarea instalaţiei de utilizare în baza proiectului tehnic verificat în condiţiile legii, cu respectarea normelor tehnice în vigoare la data executării acesteia, inclusiv a prevederilor Normativului pentru proiectarea, execuţia şi exploatarea instalaţiilor electrice aferente clădirilor, indicativ I7-2011, aprobat prin Ordinul ministrului dezvoltării regionale şi turismului nr. 2.741/2011 şi cu îndeplinirea condiţiilor care permit punerea ei sub tensiune;
					</li>
				</ul>
			</p>
			<p>
				<strong>{{ $fi ? $fi->nume_firma : '...................' }}</strong><br>
	            Ing. POPA ALEXANDRU<br><br>
	            <img src="{{ $base64_stamp_img }}" style="width:125px; height:auto;">
			</p>

			<div style="page-break-after:always;"></div>

			<p class="text-center">
				<strong class="title">Declarația de Conformitate privind calitatea lucrărilor</strong><br>
				în conformitate cu art.5 din HGR 1022/2002<br>
				NR. {{ $nr_cerere_pif ?? '....' }} din {{ date('d/m/Y') }}
			</p>
			<br>
			<p>
				<strong>{{ $fi ? $fi->nume_firma : '...................' }} cu sediul în București, SECTOR 2, bd. Chisinau, nr. 8, Parter, bl. M2, sc. C, ap.84</strong>, telefon .................................., E-mail: andreea.lungu@genway.ro înregistrată la Oficiul National al Registrului Comerțului sub nr. {{ $fi ? $fi->reg_com_firma : 'J40/4862/23.04.2015' }}, cod unic de înregistrare {{ $fi ? $fi->cod_fiscal_firma : 'RO21241885' }}, atestat ANRE tip B NR. {{ $fi ? $fi->nr_autorizare : '13692/28.09.2018' }}, asigurăm, garantăm și declarăm pe propria răspundere, conform prevederilor art. 5 din Hotărârea Guvernului nr. 1022/2002 privind regimul produselor și serviciilor care pot pune în pericol viața, sănătatea, securitatea muncii și protecția mediului, ca produsul/serviciul: ”INSTALAȚIE DE UTILIZARE CENTALĂ FOTOVOLTAICĂ” montat la imobilul aparținând: <strong>județul {{ $nume_judet_imobil }}, municipiul/oraşul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong>, cod loc de consum {{ $cod_loc_consum ?? '................' }} la care se referă această declarație, nu pune în pericol viața, sănătatea, securitatea muncii, nu produce un impact negativ asupra mediului și este în conformitate cu:
				<ul class="list" style="list-style: none outside disc!important;">
					<li>LEGEA nr.319 din 14 iulie 2006 a securității și sănătății în muncă;</li>
					<li>FT 34/1983 "Executarea și repararea instalațiilor interioare din locuințe";</li>
					<li>Normativul I7/2011 și Ghidul GP 052/2000;</li>
					<li>Ordinul 163/2007, pentru aprobarea „Normelor generale de prevenire și stingere a incendiilor;</li>
					<li>HGR nr. 537/2007, privind stabilirea și sancționarea contravențiilor la normele de prevenire și stingere a incendiilor;</li>
					<li>Legea calității în construcții „Legea 10/1995” publicat în Monitorul Oficial nr.12/1995.</li>
					<li>HGR 1022/2002 privind certificarea de conformitate.</li>
				</ul>
			</p>
			<p style="margin-left: 2rem;">Aviz tehnic de racordare: ................................................ (daca este cazul).</p>
			<br>
			<p>AGENTUL ECONOMIC DECLARĂ ȘI GARANTEAZĂ că:</p>
			<ol>
				<li>
					Instalațiile au fost executate în baza proiectului tehnic verificat în condiţiile legii, cu respectarea normelor tehnice în vigoare la data executării acesteia, inclusiv a prevederilor Normativului pentru proiectarea, execuţia şi exploatarea instalaţiilor electrice aferente clădirilor, indicativ I7-2011, aprobat prin Ordinul ministrului dezvoltării regionale şi turismului nr. 2.741/2011 şi cu îndeplinirea condiţiilor care permit punerea ei sub tensiune;
				</li>
				<li>
					Lucrările au fost verificate de responsabilul tehnic cu execuția, atestat în condițiile legii.
				</li>
				<li>
					Sunt respectate condițiile de amplasare, distanțele și gabaritele prescrise față de alte instalații, construcții și sol.
				</li>
				<li>
					Sunt respectate condițiile de protecție împotriva electrocutării conform reglementărilor legale precum și cele indicate de producătorul aparatajului instalat.
				</li>
				<li>
					Echipamentele, aparatajul și materialele utilizate sunt noi, de bună calitate și procurate pe cale legală, actele și documentația tehnică însoțitoare emisă de furnizor fiind depuse la dosarul instalației.
				</li>
				<li>
					Societatea își asumă răspunderea pentru lucrările ascunse în pământ sau în construcție, acestea fiind realizate conform normativelor aplicabile, cu respectarea cantităților și calității materialelor încorporate.
				</li>
				<li>
					Societatea iși asumă răspunderea privind respectarea măsurilor de prevenire și stingere a incendiilor pentru instalația proiectată/executată. Instalația corespunde din punct de vedere al prevenirii și stingerii incendiilor și respectă prevederile Ord.nr.163/2007.
				</li>
				<li>
					Sunt îndeplinite toate condițiile tehnice și de instruire a beneficiarului, pentru punerea în funcțiune a instalației executate.
				</li>
				<li>
					Perioada de garanție a lucrărilor executate este de ................................ de ............... luni de la punerea in funcțiune, perioadă în care executantul se obligă să remedieze fără plată, orice defecțiune în care se datorează nerespectării clauzelor și a specificațiilor contractuale sau a prevederilor reglementărilor tehnice aplicabile.
				</li>
			</ol>
			<br>
			<p>
				Executant,<br>
				<strong>{{ $fi ? $fi->nume_firma : '...................' }}</strong><br>
	            Ing. POPA ALEXANDRU<br><br>
	            <img src="{{ $base64_stamp_img }}" style="width:125px; height:auto;">
			</p>
		</div>

		<script type="text/php">
			if ( isset($pdf) ) {
			    $pdf->page_script('
			        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
			        $size = 9;
			        $color = [0,0,0,0.4];

			        $x1 = 30;
			        $x2 = $pdf->get_width() - 30;
			        $y = $pdf->get_height() - 45;
			        $pdf->line($x1, $y, $x2, $y, $color, $width = 1);

			        $text = "Pagina " . $PAGE_NUM . "/" . $PAGE_COUNT." - rev.001";
		            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
			        $x = ($pdf->get_width() - $width * 2) - 30;
			        $y = $pdf->get_height() - 40;
		            $pdf->text($x, $y, $text, $font, $size, $color);
			    ');
			}
		</script>
	</body>
</html>
