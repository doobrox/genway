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
                font-size: 10px;
            }
            table ul {
                font-size: 10px;
                padding: 0;
                margin: 0;
            }
            table ul li{
                font-size: 10px;
            	list-style: none inside url('data:image/gif;base64,R0lGODlhBQAKAIABAAAAAP///yH5BAEAAAEALAAAAAAFAAoAAAIIjI+ZwKwPUQEAOw==');
            }
            table.items {
                border: 1px solid #000;
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            } 
        </style>
    </head>
    <body>
		{{-- <header>
        	<div class="text-center" style="color: gray;"><strong>INTERNAL</strong></div>
		</header> --}}

		<div>
			<div>
	        	<img width="100px" class="left" src="{{ $base64_logo_img }}">
				<span class="right inline-block text-center">
					<strong>
						VIZAT,<br>
						ȘEF SUPORT TEHNIC REGIONAL
					</strong><br>
					Ionel NICOLAE
				</span>
			</div>
			<div class="clear"></div>
			<br>
			
			<p class="text-center">
				<strong class="title">PROCES VERBAL DE PUNERE ÎN FUNCȚIUNE</strong><br>
				încheiat azi {{ date('d/m/Y') }} între
			</p>

			<br>

			<p>
				{{ $fi ? $fi->nume_firma : '...................' }}, reprezentanta prin ALEXANDRU POPA, in calitate de ADMINISTRATOR, CIF RO21241885, cu sediul în municipiul Bucuresti Sector 2, str. Bvd. Chisinau nr. 8 bl. M2 sc. C, ap. 84, telefon: 0747773031, e-mail office@genway.ro,  nr./data act autorizare {{ $fi ? $fi->nr_autorizare : '...................' }} emis de ANRE,
			</p>

			<p>si</p>

			<p><strong>{{ $nume }} {{ $prenume }}</strong> în calitate de beneficiar, cu domiciliul în <strong>{{ $nume_judet_imobil }}</strong>, <br>localitatea <strong>{{ $nume_localitate_imobil }}</strong>, <strong>{{ $adresa_domiciliu }}</strong></p>


			<p>certifică efectuarea probelor și punerea în funcțiune a echipamentelor/sistemului fotovoltaic: <em>Instalație fotovoltaică cu <strong>o putere maxima de evacuare {{ $max_putere }} kW</strong></em>, amplasată în <strong>{{ $nume_judet_imobil }}</strong>, <br>localitatea <strong>{{ $nume_localitate_imobil }}</strong>, str. <strong>{{ $strada_imobil }}</strong>, nr. <strong>{{ $numar_imobil }}</strong></p>

			<p>În urma probelor efectuate, conform anexei, se ADMITE punerea în funcțiune a centralei electrice fotovoltaice.</p>
			<table style="border:none;">
				<tr>
					<td align="left" style="width:60%;font-size:12px;">
						Executant: (nume, semnatura) <br>
						{{ $fi ? $fi->nume_firma : '...................' }}, prin Alexandru Popa
					</td>
					<td align="left">
	                    <img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;">
					</td>
				</tr>
			</table>
			<p>Beneficiar: (nume, semnatura) <strong>{{ $nume }} {{ $prenume }}</strong></p>
			<p>Specialist COR MT/JT: (nume, semnatura) ................... </p>

			<div style="page-break-after:always;"></div>

			<p class="right">Anexa</p>
			<p>CEF: (nume, adresa) <strong>{{ $nume }} {{ $prenume }}, {{ $nume_judet_imobil }}, localitatea {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong></p>

			<p class="text-center"><strong class="title">PROBE DE PUNERE ȊN FUNCŢIUNE PENTRU CENTRALELE PROSUMATORILOR ≤ 400 kW</strong></p>

			<table border="1" cellspacing="0">
				<tbody>
					<tr class="font-bold text-center">
						<td>Nr.<br> probă</td>
						<td>Denumirea/ Descrierea probei</td>
						<td>Condiţii de<br> funcţionare</td>
						<td style="width:110px;">Simulare</td>
						<td style="width:110px;">Mărimi măsurate</td>
						<td>Durata<br> probei</td>
						<td style="width:110px;">Cerinţe/Evaluare</td>
						<td style="width:60px;">Rezultat</td>
					</tr>
					<tr>
						<td class="text-center">1</td>
						<td>Stabilitatea de frecvenţă, Tabelul 1P</td>
						<td>Funcţionare normală, minim 60 %Pi</td>
						<td>Grup generator cu frecvenţă variabilă continuu sau în trepte</td>
						<td>
							Verificare stabilitate: Nelimitat în domeniul 49-51 Hz;<br>
							Timp funcţ. ≥30 min pentru 47,5-48,5 Hz;<br>
							48,5-49 Hz; 51-51,5 Hz
						</td>
						<td class="text-center">2,5h</td>
						<td>
							<ul>
								<li>Se va înregistra timpul de funcţionare / frecvenţa reglată;</li>
								<li>Efectuarea probei este opţională in situ dacă există buletin de test de tip sau individual pentru invertor, emis de fabricant sau laborator acreditat;</li>
							</ul>
						</td>
						<td>
							□ Admis<br>
							□ Respins<br>
							□ Buletin atașat
						</td>
					</tr>
					<tr>
						<td class="text-center">2</td>
						<td>Funcţionare la variaţia frecvenţei</td>
						<td>Funcţionare normală</td>
						<td>Generator de frecvenţă variabilă, etalonat în domeniul de reglaj</td>
						<td>
							<ul>
								Funcţionare pentru:
								<li>2 Hz/s, 500 ms</li>
								<li>1,5 Hz/s, 1000 ms</li>
								<li>1,25 Hz/s, 2000 ms</li>
							</ul>
						</td>
						<td class="text-center">-</td>
						<td>
							Evaluare numai pe bază de buletin de test tip/individual emis de fabricant sau laborator autorizat<br>
							Se admite pentru una din valori
						</td>
						<td>
							□ Buletin atașat
						</td>
					</tr>
					<tr>
						<td class="text-center">3</td>
						<td>Capabilitate de răspuns la abaterile de frecvenţă</td>
						<td>Funcţionare normală</td>
						<td class="text-center">-</td>
						<td>Diagrame stabilite prin norme</td>
						<td class="text-center">-</td>
						<td>Buletine de tip</td>
						<td>
							□ Buletin atașat
						</td>
					</tr>
					<tr>
						<td class="text-center">4</td>
						<td>Proba de reconectare automată la reţea</td>
						<td>Funcţionare normală, în sarcină minim 10 % Pi</td>
						<td>
							<ul>Testarea reconectării la reţea în cazul a două tipuri de solicitări:
								<li>gol de tensiune ≈0,2 s;</li>
								<li>RAR două cicluri (2 şi 10 s, pentru alim. din LEA a PT};</li>
								Sau
								<li>intrerupere scurta de tensiune < 3s pentru alimentare din LES</li>
							</ul>
						</td>
						<td>
							Intreruperea tensiunii se realizeaza in punctul de delimitre, din întreruptor<br>
							Reconectarea automată trebuie sa se realizeze după minim 15 min. 

						</td>
						<td class="text-center">1h</td>
						<td>Se consideră cerinţa îndeplinită dacă invertorul se conectează automat şi ia sarcină după 10-15 min, la fiecare revenire a tensiunii în domeniul acceptat (0,9-1,1U<sub>n</sub>)</td>
						<td>
							□ Admis<br>
							□ Respins
						</td>
					</tr>
					<tr>
						<td class="text-center">5</td>
						<td>Monitorizarea calităţii energiei electrice</td>
						<td>Funcţionare normală</td>
						<td class="text-center">-</td>
						<td>Parametri de calitate ai energiei electrice cu analizor de clasă A, conform SR EN 50160:2011</td>
						<td class="text-center">Cf. standard</td>
						<td>
							Cerinţa se consideră îndeplinită dacă raportul parametrilor de calitate ai e.e. are valori de monitorizare în limita standardului.<br>
							Raportul de monitorizare se va  realiza în perioada de probe sau minim o dată pe an.
						</td>
						<td>
							□ Admis<br>
							□ Respins
						</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Protecţiile electrice, inclusiv cele cu rol de antiinsularizare</td>
						<td>Funcţionare normală</td>
						<td class="text-center">-</td>
						<td>
							Verificarea protecţiilor din documentaţii şi a setărilor protecţiilor din instalaţia de racordare şi din instalaţia de utilizare;<br>
							Protecţiile invertorului se verifică prin accesarea meniului de pe display sau în aplicaţia informatică de fabrică
						</td>
						<td class="text-center">Cf. ATR, proiecte tehnice şi documentaţii de fabrică 2h</td>
						<td>
							Se verifică setările protecţiilor şi se emit buletine de verificare distincte pentru instalaţia de racord şi instalaţia de utilizare. <br>
							Protecţiile din invertor, inclusiv protecţii de antiinsularizare se verifică conform documentaţiei de fabrică, prin accesarea setarilor in meniu. 
						</td>
						<td>
							□ Admis<br>
							□ Respins
						</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Montarea şi verificarea  funcţionării contorului inteligent cu dublu sens în punctul de . Integrarea în SMI</td>
						<td>Funcţionare normală</td>
						<td class="text-center">-</td>
						<td>Verificarea înregistrării energiei în ambele sensuri, consum şi injecţie de energie în reţea la scăderea consumului în instalaţia de utilizare</td>
						<td class="text-center">Cf. ATR şi proiect tehnic al instalaţiei 4h</td>
						<td>Cerinţa se consideră îndeplinită dacă se constată înregistrarea corectă a consumului şi a injecţiei de energie electrică în reţea, în aplicaţia SMI a OD</td>
						<td>
							□ Admis<br>
							□ Respins
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>