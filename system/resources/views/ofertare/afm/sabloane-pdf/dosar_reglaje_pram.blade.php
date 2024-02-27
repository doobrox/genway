<!DOCTYPE html>
<html>
    <head>
        <title>Dosar, reglaje, pram</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            *, html, body {
                font-size: 12px;
                font-family: "DejaVu Sans", sans-serif;
            }
            @page { margin-top: 100px; }
            header{
            	position:fixed;
            	top: -50px;
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
            table {
            	width: 100%;
            }
            table td {
            	padding: 5px 10px;
            }
            .page-break {
            	page-break-after: always;
            }
            .section-title {
                font-size: 17px;
                font-weight: 700;
            }
            .section-subtitle {
                font-size: 15px;
            }
            .section-title-decoration {
            	border-bottom: 2px solid gray;
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
        	<span class="left inline-block">
				{{ $fi ? $fi->nume_firma : '...................' }}<br>
				Tel: 0747773031
			</span>
        	<img width="100px" class="right" src="{{ $base64_logo_img }}">
		</header>
    	<div>

			<p>&nbsp;</p>
			<h1 class="text-center section-title section-title-decoration">DOSARUL DE UTILIZARE AL INSTALATIEI FOTOVOLTAICE</h1>
			<p class="text-center"><em class="section-subtitle">PROSUMATOR AFM</em></p>
			<p>&nbsp;</p>

			<p>
				<strong>BENEFICIAR: </strong>{{ $nume }} {{ $prenume }}
			</p>

			<p>
				<strong>Cod proiect &nbsp;NON AFM/AFM:{{ $numar_dosar_afm }}</strong>
			</p>

			<p>
				<strong>POD:</strong> {{ $pod }}
			</p>

			<p>
				<strong>Adresa:</strong> <strong>{{ $nume_judet_imobil }}</strong>, municipiul/oraşul/comuna/satul/sectorul <strong>{{ $nume_localitate_imobil }}</strong>, str. <strong>{{ $strada_imobil }}</strong>,nr. <strong>{{ $numar_imobil }}</strong>
			</p>

			<p>
				<strong>Numar cerere:</strong> {{ $nr_cerere_dosar_reglaje }}
			</p>

			<p>
				<strong>Executant:</strong> S.C. ELECTRO SERVICE DISTRIBUTIE S.R.L, Bvd. Chisinau, nr. 8, Bl. M2, Sc. C, Ap. 84, C.U.I. : RO21241885, Nr. inreg. J40/4862/23.04.2015
			</p>

			<ol class="page-break">
				<li>
					Copie CI
				</li>
				<li>
					Buletin reglaje
				</li>
				<li>
					Buletin de masurare prize de pamant
				</li>
				<li>
					Proces verbal de receptie la terminarea lucrarilor si punerea in functiune
				</li>
				<li>
					Schema electrică monofilară a instalaţiei de utilizare, inclusiv tabloul general
				</li>
				<li>
					NOTIFICARE privind racordarea la un loc de consum existent a unei instalaţii de producere a energiei&nbsp; electrice (Anexa nr. 1/Anexa 2)
				</li>
				<li>
					Fișa tehnică a invertorului utilizat la racodarea instalației fotovoltaice
				</li>
				<li>
					Declarația de conformitate a invertorului utilizat la racodarea instalației fotovoltaice
				</li>
				<li>
					Fișa tehnică a panourilor fotovoltaice utilizate la instalația fotovoltaică
				</li>
				<li>
					Declarația de conformitate a panourilor fotovoltaice utilizate la racodarea instalației fotovoltaice
				</li>
				<li>
					Capturi de ecran cu setările protecțiilor invertorului
				</li>
				<li>
					Fotografii montaj, contor E-distributie
				</li>
			</ol>

			<p>&nbsp;</p>
			<h1 class="text-center section-title section-title-decoration">2. BULETIN REGLAJE</h1>
			<p>&nbsp;</p>

			<p>
				{{ $fi ? $fi->nume_firma : '...................' }}, reprezentata prin Administrator Popa Alexandru, cu sediul in Bucuresti, - Bd. Chisinau, nr.8, Parter, Bl.M2, sc.C, ap.84, posesoare atestate ANRE tip B nr. {{ $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '...................' }} , declaram pe propria raspundere ca echipamentul invertorul montat in imobilul din judeţul <strong>{{ $nume_judet_imobil }}</strong>, municipiul/oraşul/comuna/satul/sectorul <strong>{{ $nume_localitate_imobil }}</strong>, str. <strong>{{ $strada_imobil }}</strong>,nr. <strong>{{ $numar_imobil }}</strong>, <strong>beneficiar</strong>&nbsp; {{ $nume }} {{ $prenume }}, are urmatoarele protectii setate:
			</p>

			<table border="1" cellspacing="0">
				<tbody>
					<tr>
						<td class="text-center" style="border-color:black; height:18.4pt; vertical-align:top;">
							INVERTOR {{ $marca_invertor }}<br>
							SERIE: {{ $serie_invertor }}
						</td>
					</tr>
				</tbody>
			</table>

			<p>&nbsp;</p>

			<table border="1" cellspacing="0">
				<tbody>
					<tr>
						<td style="width:257.75pt">
							<strong>PROTECTIE</strong>
						</td>
						<td style="width:102.3pt" class="text-center">
							<strong>Valoare Setata</strong>
						</td>
						<td style="width:102.05pt" class="text-center">
							<strong>Timp (sec)</strong>
						</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functia protectie tensiune maximala treapta I
						</td>
						<td style="width:102.3pt" class="text-center">264,5V</td>
						<td style="width:102.05pt" class="text-center">0,5</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functia protectie tensiune minimala treapta II
						</td>
						<td style="width:102.3pt" class="text-center">195,5V</td>
						<td style="width:102.05pt" class="text-center">3,2</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functia de protectie de frecventa treapta I
						</td>
						<td style="width:102.3pt" class="text-center">52 Hz</td>
						<td style="width:102.05pt" class="text-center">0,5</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functia de protectie de frecventa treaptra II
						</td>
						<td style="width:102.3pt" class="text-center">47,5 Hz</td>
						<td style="width:102.05pt" class="text-center">0,5</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functia de protectie maxima de tensiune <br>
							(valoarea mediate la 10 minute)* 
						</td>
						<td style="width:102.3pt" class="text-center">253V</td>
						<td style="width:102.05pt" class="text-center">603</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Pornire  initiala si Repornire invertor dupa intrerupere tensiune RED
						</td>
						<td colspan="2" style="width:204.35pt" class="text-center">900 s</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HVRT&nbsp;&nbsp; 264,5V
						</td>
						<td colspan="2" style="width:204.35pt" class="text-center">Activata</td>
					</tr>
					<tr>
						<td style="width:257.75pt">
							Functie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; LVRT&nbsp;&nbsp; 184,5V
						</td>
						<td colspan="2" style="width:204.35pt" class="text-center">Activata</td>
					</tr>
				</tbody>
			</table>

			<p>&nbsp;</p>

			<p class="page-break"><span>Beneficiar, autorizata/firma,</span> <img src="{{ $base64_stamp_img }}" style="width:150px; height:auto;"></p>



			<p>&nbsp;</p>
			<h1 class="text-center section-title section-title-decoration">3. BULETIN DE MASURARE PRIZE DE PAMANT</h1>
			<p>&nbsp;</p>

			<p>Nr. <strong>{{ $numar_contract_instalare }}/{{ date('d.m.Y') }}</strong> a rezistentei de dispersie a prizelor de pamant pentru:</p>

			<p>&nbsp;</p>

			<p>
				REALIZARE INSTALATIE ELECTRICA DE UTILIZARE <strong>{{ $tipul_bransamentului == 'monofazat' ? 230 : 380 }}</strong> V &ndash; SISTEM FOTOVOLTAIC ON GRID (P max. simultan absorbita = kW, Pmax. Evacuata = <strong>{{ $min_putere }}</strong> kW.&nbsp;
			</p>

			<p>
				<strong>Beneficiar:</strong> {{ $nume }} {{ $prenume }}
			</p>

			<p>
				<strong>Loc de consum/ producere</strong>: <strong>{{ $nume_judet_imobil }}</strong>, municipiul/oraşul/comuna/satul/sectorul <strong>{{ $nume_localitate_imobil }}</strong>, str. <strong>{{ $strada_imobil }}</strong>,nr. <strong>{{ $numar_imobil }}</strong>
			</p>

			<table align="center" border="1" cellspacing="0">
				<tbody>
					<tr>
						<td style="width:34.6pt">Nr. Crt.</td>
						<td style="width:269.75pt">Denumire</td>
						<td>Valoarea calculata a rezistentei<br>(Ω)</td>
						<td>Valoarea maxima admisa<br>(Ω)</td>
						<td>Concluzii</td>
					</tr>
					<tr>
						<td style="width:34.6pt">1.1</td>
						<td style="width:269.75pt">Rez. dispersie priza pamant de protectie invertoare</td>
						<td>{{ $valoare_masurata_rezistenta }}</td>
						<td>4</td>
						<td>ADMIS</td>
					</tr>
					<tr>
						<td style="width:34.6pt">1.2</td>
						<td colspan="4">
							CONTINUITATEA INSTALAȚIEI DE LEGARE LA PAMANT DE PROTECTIE AFERENTA TABLOURILOR MONTATE: <strong>ESTE ASIGURATA</strong>
						</td>
					</tr>
				</tbody>
			</table>

			<p>Concluzii: INSTALATIILE DE LEGARE LA PAMANT DE PROTECTIE MASURATE corespund PE116/1994</p>

			<p>Nota: In calculul valorii calculate a rezistentei s-a tinut cont de starea de umiditate a terenului din ziua masuratorii.</p>

			<p>Valabilitatea masuratorilor: 1 (unu) an. Urmatoarea verificare se poate face pana la: <strong>{{ date('d.m.Y', strtotime("+1 Year")) }}</strong></p>

			<p>Aparat folosit:</p>
			<p>APARATUL DE MASURAT MRU-120 SERIA AA3951, certificat in data de 7 februarie 2023, nr: 2023/AA3951/1</p>


			<p class="left">
				Executant (nume si prenume):<br>
				<strong>Popa Alexandru</strong><br>
				NR. ADEVERINTA: 201810646/20.04.2023<br>
				GRAD IIA,IIB
			</p>
			<p class="right">
				<img src="{{ $base64_stamp_img }}" style="width:150px; height:auto;"> 
			</p>

		</div>
	</body>
</html>