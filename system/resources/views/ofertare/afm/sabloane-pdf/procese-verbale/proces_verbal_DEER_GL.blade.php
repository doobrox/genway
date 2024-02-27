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
            @page { margin-top: 120px; }
            header{
            	position:fixed;
            	top: -80px;
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
                text-align: center;
            }
            .section-title {
                font-size: 13px;
                font-weight: 700;
            }
            .subsection-title {
                font-size: 13px;
            }
            .subsection-body {
                font-size: 14px;
                text-align: justify;
            }
            .text-center {
                text-align: center;
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
            .space {
            	margin-left: 5em;
            	display: inline-block;
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
            
        </style>
    </head>
    <body>
		<header>
        	<div class="" style="color: gray; font-size: 13px;">
        		{{ $fi ? $fi->nume_firma : '...........................' }}<br>
				Bucuresti - Pantelimon - Bd. Chisinau, nr.8, Parter, Bl.M2, sc.C, ap.84, Tel.: 0216270034<br>
				Email: office@genway.ro
        	</div>
		</header>

		<div>
			<h1 class="title">
				PROCES-VERBAL DE RECEPŢIE SI PROBE SI PUNERE SUB TENSIUNE-SISTEM<br> 
				FOTOVOLTAIC 
			</h1>

			<h3 class="section-title text-center">
				Nr. {{ $numar_contract_instalare }} din data {{ date('d.m.Y') }}<br>
				privind lucrarea de probe si receptie de PIF, instalare sistem fotovoltaic<br>
				(Locuinta) pentru obiectivul situat in judetul {{ $nume_judet_imobil }}<br>
				municipiul/orasul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }} nr. {{ $numar_imobil }},
			</h3>

			<p>
				Incheiat intre beneficiar {{ $nume }} {{ $prenume }} intre executant (instalator) {{ $fi ? $fi->nume_firma : '...........................' }}
			</p>

			<ol style="list-style: upper-roman;">
				<li>Comisia de recepţie convocată si si-a desfasurat activitatea in intervalul: 
					<table>
						<tr>
							<td style="width: 30px;"></td>
							<td>Comisia de receptie:</td>
							<td style="width: 100px;"></td>
							<td>Calitate</td>
							<td style="width: 30px;"></td>
						</tr>
						<tr>
							<td style="width: 30px;"></td>
							<td>Beneficiar: {{ $nume }} {{ $prenume }}</td>
							<td style="width: 100px;"></td>
							<td>PROSUMATOR EXECUTANT {{ $fi ? $fi->nume_firma : '...........................' }}</td>
							<td style="width: 30px;"></td>
						</tr>
					</table>
				</li>

				<li>Constatări:
					<p>În urma examinării documentaţiei- Dosarul de Utilizare, a rezultatelor probelor tehnologice şi a cercetării pe teren a lucrărilor executate s-a constatat:</p> 
					<ol>
						<li>Documentaţia tehnico-economică prevăzută în Regulamentul de efectuare a recepţiei punerii în funcţiune a obiectivelor de investiţii <strong>a fost (nu a fost)</strong> prezentată integral comisiei de recepţie, lipsind: - <em>NU ESTE CAZUL</em></li>
						<li>Sistemul fotovoltaic corespunde Fiselor Tehnice de componente si schemei electrice depuse la Dosarul de utilizare</li>
						<li>Proba de antiinsularizare (prin lipsa tensiunii in retea) a fost executata</li>
						<li>Sistemul fotovoltaic va fi luat in primire de beneficiar dupa punerea in functiune in prezenta reprezentantilor operatorului de distributie.</li>
					</ol>
				</li>

				<li>Concluzii:
					<p>Pe baza constatărilor şi a concluziilor consemnate mai sus, comisia de recepţie, în unanimitate cu majoritate de păreri, hotărăşte:</p>
					<strong>
						<ol>
							<li>Punerea in functiune impreuna cu reprezentantul retelei de distributie a energiei electrice</li>
							<li>Urmarirea, in timp, a echipamentelor instalate</li>
							<li>Efectuarea lucrarilor de mentenanta</li>
						</ol>
					</strong>
				</li>

			</ol>

			<p>Prezentul proces-verbal care conţine 2 (doua) file şi 0 (zero) anexe numerotate, cu un total de 0 (zero) file care fac parte integrantă din cuprinsul lui, a fost încheiat astăzi, 08.05.2023, în 2 exemplare originale.</p>


			<table width="100%">
				<tr>
					<td style="width: 30px;"></td>
					<td><strong>Comisia de receptie</strong></td>
					<td style="width: 50px;">&nbsp;</td>
					<td><strong>Semnaturi</strong></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td><strong>Reprezentanti DEER- Sucursala Galati</strong></td>
					<td style="width: 50px;"></td>
					<td></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>Presedinte: ................................</td>
					<td style="width: 50px;"></td>
					<td>................................</td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>Membri: .....................................</td>
					<td style="width: 50px;"></td>
					<td>................................</td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>Membri: .....................................</td>
					<td style="width: 50px;"></td>
					<td>................................</td>
					<td style="width: 30px;"></td>
				</tr>
			</table>

			<table width="100%" style="page-break-inside: avoid;">
				<tr>
					<td style="width: 30px;"></td>
					<td><strong>Alti participanti:</strong></td>
					<td style="width: 50px;">&nbsp;</td>
					<td></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>
						Executant: <strong>{{ $fi ? $fi->nume_firma : '...........................' }}</strong><br>
						Ing. POPA ALEXANDRU
					</td>
					<td style="width: 50px;"></td>
					<td rowspan="2"><img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;"></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td></td>
					<td style="width: 50px;"></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>Beneficiar (prosumator) {{ $nume }} {{ $prenume }}</td>
					<td style="width: 50px;"></td>
					<td></td>
					<td style="width: 30px;"></td>
				</tr>
			</table>

			{{-- PAGE BREAK --}}
			<div style="page-break-after: always;"></div>

			<h1 class="title">BULETIN DE PROBE</h1>

			<h3 class="section-title text-center">
				Nr. <strong>{{ $numar_contract_instalare }}</strong> din data <strong>{{ date('d.m.Y') }}</strong>
			</h3>

			<p class="subsection-title text-center">
				Privind lucrarea de probe, instalatie sistem fotovoltaic (locuinta) pentru obiectivul situat<br>
				<strong>judetul {{ $nume_judet_imobil }} municipiul/orasul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }} nr. {{ $numar_imobil }},</strong><br>
				Incheiat intre beneficiar {{ $nume }} {{ $prenume }} si executant (instalator) <strong>{{ $fi ? $fi->nume_firma : '...........................' }}</strong>
			</p>

			<ol>Probele de punere in functie pentru  centrala prosumatorului de {{ $putere_invertor }}  KW sunt :
				<li>Stabilitatea de frecventa cu o durata a probei de <strong>2.5 h</strong>, functionare normala, cu buletin de test pentru invertor, emis de fabricant, conform <strong>art. 4</strong>, lit. <strong>a</strong> din Ordinul ANRE nr. 228/2018.</li>
				<li>Functionare la varaiatia frecventei, functionare normala, evaluare pe baza de buletin de test emis de fabricant sau laborator autorizat, conform <strong>art. 4</strong>, lit. <strong>b</strong> din Ordinul ANRE 228/2018.</li>
				<li>Capabilitate de raspuns la abaterile de frecventa, functionare normala, conform art. 5-9 din ordinul  ANRE nr. 228/2018, modificat <strong>art. 9, alin. 2</strong> prin Ordinul ANRE nr. 132/2020.</li>
				<li>Proba de reconectare automata la retea, functionare normala, cu o durata a probei de  1h, invertorul se conecteaza automat si ia sarcina dupa 10-15 min, la fiecare revenire a tensiunii in domeniul acceptat (0.9-1.1, Un), conform <strong>art. 10</strong> din Ordinul ANRE 228/2018.</li>
				<li>Monitorizarea calitatii energiei electrice, functionare normala, cu valori  in limita standardului conform <strong>art. 12, 13</strong> din Ordinul ANRE 228/2018, modificat <strong>art. 12</strong> prin Ordinul ANRE nr. 132/2020.</li>
				<li>Protectiile electrice, inclusiv cele cu rol de antiinsularizare, functionare normala, cu o durata a probei de <strong>2 h</strong>, conform documentatiei de fabrica, prin accesarea setarilor in mediu, conform art. 14, 18 din ordinul  ANRE nr. 228/2018, modificat <strong>art. 14 si 18</strong> prin Ordinul ANRE nr. 132/2020.</li>
				<li>Montarea si verificarea functionarii contorului inteligent cu dblu sens, inegrarea SMI, functionare normala si inregistrarea corecta a consumului si a injectiei de energie electrica in retea conform <strong>art. 19, 21</strong> din ordinul  ANRE nr. 228/2018, modificat art. 19 prin Ordinul ANRE nr. 132/2020.</li>
			</ol>

			<table width="100%" style="page-break-inside: avoid;">
				<tr>
					<td style="width: 30px;"></td>
					<td><strong>Alti participanti:</strong></td>
					<td style="width: 50px;">&nbsp;</td>
					<td></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>
						Executant: POPA ALEXANDRU<br>
						<strong>{{ $fi ? $fi->nume_firma : '...........................' }}</strong>
					</td>
					<td style="width: 50px;"></td>
					<td rowspan="2"><img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;"></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td></td>
					<td style="width: 50px;"></td>
					<td style="width: 30px;"></td>
				</tr>
				<tr>
					<td style="width: 30px;"></td>
					<td>Beneficiar (prosumator) {{ $nume }} {{ $prenume }}</td>
					<td style="width: 50px;"></td>
					<td></td>
					<td style="width: 30px;"></td>
				</tr>
			</table>

			{{-- PAGE BREAK --}}
			<div style="page-break-after: always;"></div>


			<table class="subsection-body">
				<tr>
					<td colspan="2">Nr. <strong>{{ $numar_contract_instalare }}</strong> din data <strong>{{ date('d.m.Y') }}</strong></td>
				</tr>
				<tr>
					<td>Catre:</td>
					<td><span style="margin-left: -4em;">DEER S.A. zona MN, SDEE - Galati</span></td>
				</tr>
				<tr>
					<td></td>
					<td><span style="margin-left: -4em;">str. N. Balcescu, nr. 35A, 800001, Galati, jud. Galati</span></td>
				</tr>
				<tr>
					<td colspan="2">Spre stiinta: prosumator</td>
				</tr>
			</table>
			<br>
			<br>

			<h1 class="title">CERERE DE PUNERE SUB TENSIUNE SI VERIFICARI DE PIF</h1>

			<br>
			<p class="subsection-body">
				<span class="space"></span>Prin prezenta <strong>{{ $fi ? $fi->nume_firma : '...........................' }}</strong><br>
				<span class="space"></span>va solicita sa asistati la efectuarea verificarilor si punerea in fuctiune a instalatiei la CEF 3 kw, conform Ordinului ANRE 69/2020 la utilizatorul {{ $nume }} {{ $prenume }} situat in <strong>judetul {{ $nume_judet_imobil }} municipiul/orasul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }} nr. {{ $numar_imobil }}.</strong><br>
			</p>
			<p class="subsection-body">
				<span class="space"></span>Probele de verificare se vor efectua in intervalul:  
			</p>
			<p class="subsection-body">
				<span class="space"></span>In ziua: <strong>{{ date('d.m.Y') }}</strong> se va face verificarea si probe de PIF impreuna cu reprezentatii SD COR-MTJT, in prezenta prosumatorului.
			</p>
			<p class="subsection-body" style="margin-left: 5em;">
				TEL 0747773031<br>
				<strong>{{ $fi ? $fi->nume_firma : '...........................' }}</strong>
			</p>
		</div>
	</body>
</html>