<!DOCTYPE html>
<html>
    <head>
        <title>Notificare racordare loc consum DEER</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            *, html, body {
                /*font-family: "Times New Roman";
                 font-family: Arial, sans-serif;*/
                font-size: 12px;
                /*font-family: 'Open Sans', sans-serif, Arial, Helvetica, Sans-Serif;*/
                font-family: "DejaVu Sans", sans-serif;
            }
            .titlu {
                font-size: 16px;
                text-align: center;
            }
            .text-center {
                text-align: center;
            }
            .componente > p {
                line-height: 14px;
                margin: 0;
                padding-top: 3px;
            }
            .tab {
                margin-left: 30px;
            }
            p {
            	max-width: 100%;
            }
            table {
                border: 1px solid #000;
                width: 100%;
                max-width: 100%;
                border-collapse: collapse;
                text-align: center;
            } 
            /*table th, table td {
                padding-left: 10px;
                padding-right: 10px;
            }*/
            table.items th, table.items td {
                padding-top: 10px;
                padding-bottom: 10px;
                vertical-align: middle;
                border: 1px solid #000;
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
		<div>
			<p>
				Nr. Utilizator ......................................................................<br>
				Partener afaceri ................................................................<br>
				Cont contract ....................................................................<br>
				Ob. Conectare ..................................................................<br>
				Loc consum ......................................................................<br>
				Chitanta nr. .........................................din ........................<br>
			</p>

			<h1 class="titlu">
				NOTIFICARE
			</h1>

			<p class="text-center">
				<strong>privind racordarea la un loc de consum existent a unei instalații de producere a energiei electrice</strong>
			</p>

			<p>
				Utilizatorul {{ $nume }} {{ $prenume }} cu domiciliul/sediul in judetul {{ $judet_domiciliu }} municipiul/orasul/comuna/satul/sectorul {{ $localitate_domiciliu }}, str. {{ $strada_domiciliu }} nr. {{ $numar_domiciliu }}, bl. {{ $bloc_domiciliu }}, sc. {{ $scara_domiciliu }}, et. {{ $et_domiciliu }}, ap. {{ $ap_domiciliu }}, cod postal ......, telefon/telefon mobil/fax {{ $telefon }}, e-mail: {{ $email }}, cod de inregistrare fiscala ...., CNP {{ $cnp }}, inregistrat la oficiul registrului comertului cu nr. ...., reprezentat prin, în calitate de ........................................................., contul ..........................................................., deschis la banca..........................., sucursala................,
			</p>

			<p>
				reprezentat prin împuternicit/persoană fizică autorizată/reprezentant al operatorului economic atestat/furnizor de energie electrică {{ $fi ? $fi->nume_firma : '...................' }}, cu domiciliul/sediul în județul/ Mun. Bucuresti municipiul/orașul/comuna/satul/sectorul SECTOR 2, codul poștal ...................., str. Bvd. Chisinau nr. 8, bl. M2, sc. C, et. ......, ap. 84 telefon/telefon mobil/fax: 0747773031, e-mail: office@genway.ro, nr/data act autorizare {{ $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '13692/28.09.2018' }} emis de ANRE,
			</p>

			<p>
				Notific prin prezenta racordarea la locul de consum existent situat în <strong>judetul {{ $judet_imobil }} municipiul/orasul/comuna/satul/sectorul {{ $localitate_imobil }}, str. {{ $strada_imobil }} nr. {{ $numar_imobil }}, înregistrat în cartea funciară nr. {{ $nr_carte }}, având nr. cadastral {{ $nr_cadastral }}, cod loc consum {{ $cod_loc_consum }}</strong>, având codul unic de identificare {{ $pod }} (înscris pe factura de energie electrică) a unei instalații de producere a energiei electrice cu putere maximă simultană ce poate fi evacuată în rețeaua de distribuție de {{ $min_putere }} kW {{ $min_putere }} kVA.
			</p>

			<p>
				Prin prezenta notificare, solicit:
			</p>

			<ul>
				<li>
					înlocuirea contorului existent la locul de consum cu un contor de măsurare a energiei electrice în ambele sensuri;
				</li>
				<li>
					punerea sub tensiune pentru perioada de probe a instalației de producere a energiei electrice.
				</li>
			</ul>

			<h1>
				Date tehnice și energetice aferente instalației de producere a energiei electrice:
			</h1>

			<p>
				<strong>Generatoare asincrone si sincrone</strong>
			</p>

			<table border="1" cellspacing="0">
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
					<tr>
						<td>1</td>
						<td>2</td>
						<td>3</td>
						<td>4</td>
						<td>5</td>
						<td>6</td>
						<td>7</td>
						<td>8</td>
						<td>9</td>
						<td>10</td>
						<td>11</td>
						<td>12</td>
						<td>13</td>
						<td>14</td>
						<td>15</td>
					</tr>
					<tr>
						<td colspan="8">TOTAL</td>
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

			<h1>
				Mijloace de compensare a energiei reactive:
			</h1>

			<p>□ NU [x]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; □ DA [ ]</p>

			<table border="1" cellspacing="0">
				<tbody>
					<tr>
						<td>Nr. crt.</td>
						<td>Tip echipament de compensare</td>
						<td>Qn (kVAr)</td>
						<td>Qmin (kVAr)</td>
						<td>Qmax (kVAr)</td>
						<td>Nr. trepte*</td>
						<td>Observații</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>3</td>
						<td>4</td>
						<td>5</td>
						<td>6</td>
						<td>7</td>
					</tr>
					<tr>
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

			<p>
				* Se completează dacă tipul de echipament de compensare utilizat are reglaj în trepte
			</p>

			<br>

			<h1>
				Module generatoare de tip fotovoltaic:
			</h1>

			<table border="1" cellspacing="0">
				<tbody>
					<tr>
						<td>Nr. crt.</td>
						<td>Nr. panouri</td>
						<td>Tip panou</td>
						<td>Pi panou (c.c.) (kW)</td>
						<td>Pi total panouri (c.c.) (kW)</td>
						<td>Pmax debitat de panouri (c.c.) (kW)</td>
						<td>Pi total panouri pe 1 invertor (c.c.) (kW)</td>
						<td>Observaţii</td>
					</tr>
					<tr>
						<td>1</td>
						<td>2</td>
						<td>3</td>
						<td>4</td>
						<td>5</td>
						<td>6</td>
						<td>7</td>
						<td>8</td>
					</tr>
					<tr>
						<td>1</td>
						<td>{{ $numar_panouri }}</td>
						<td>Monocristaline Ja Solar</td>
						<td>{{ $putere_panouri / 1000 }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td></td>
					</tr>
					<tr>
						<td>2</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><strong>TOTAL:</strong></td>
						<td>{{ $numar_panouri }}</td>
						<td><strong>-</strong></td>
						<td><strong>-</strong></td>
						<td>{{ $total_putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>

		<p><strong>Invertoare</strong></p>

		<table align="center" border="1" cellspacing="0" style="width:716px">
			<tbody>
				<tr>
					<td>Nr. crt.</td>
					<td>Nr. invertoare</td>
					<td>Tipul invertoarelor</td>
					<td>Un invertor (c.a.)(V)</td>
					<td>Pi invertor (c.a.) (kW)</td>
					<td>Pmax invertor evacuata in retea (c.a.) (kW)</td>
					<td>Pmax centrala formata din module generatoare (c.a.) (kW)</td>
					<td>Observatii</td>
				</tr>
				<tr>
					<td>1</td>
					<td>2</td>
					<td>3</td>
					<td>4</td>
					<td>5</td>
					<td>6</td>
					<td>7</td>
					<td>8</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>1</td>
					<td>{{ $marca_invertor }}</td>
					<td>{{ $ca_invertor }}</td>
					<td>{{ $putere_invertor }}</td>
					<td>{{ $putere_invertor }}</td>
					<td>{{ $total_putere_panouri }}</td>
					<td>AFM dosar nr. {{ $numar_dosar_afm }}</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4"><strong>TOTAL: </strong></td>
					<td>{{ $putere_invertor }}</td>
					<td>{{ $putere_invertor }}</td>
					<td>{{ $total_putere_panouri }}</td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

		<p>
			Notă: <em>U.G. = unitate generatoare; Panou = panou fotovoltaic; As &ndash; asincron; S &ndash; sincron; T &ndash; termo; H &ndash; hidro; E &ndash; eolian; Qn &ndash; Putere reactivă nominal; Qmax = Putere reactivă maximă; Qmin = Putere reactivă minimă;</em>
			<em>c.c. = curent continuu; c.a. = curent alternativ;</em>
			<em>Pn = putere activa nominal; Pi = putere activă instalată; Pmax = putere activă maximă; Pmin = putere activă minimă; Sn = putere aparentă nominală;</em>
			<em>Un = tensiune nominală la borne; U = tensiunea în punctul de racordare; Sevac = puterea aparentă aprobată pentru evacuare în rețea</em>.
		</p>

		<h1>
			Serviciile interne ale instalației producere:
		</h1>

		<table align="center" border="1" cellspacing="0" style="width: 80%;">
			<tbody>
				<tr>
					<td style="text-align: left">Pi servicii interne</td>
					<td>[0,0055]</td>
					<td>kW</td>
				</tr>
				<tr>
					<td style="text-align: left">Puterea maximă simultan absorbită servicii interne</td>
					<td>[0,0055]</td>
					<td>kW</td>
				</tr>
			</tbody>
		</table>

		<p>Anexez prezentei următoarele documente:</p>

		<ol style="list-style-type: lower-alpha;">
			<li>
				copia actului de identitate, a certificatului de înregistrare la Registrul Comerţului sau a altor autorizaţii legale de funcţionare emise de autorităţile competente, după caz;
			</li>
			<li>
				certificatul constatator (doar în cazul prosumatorului persoană juridică), în copie, emis de oficiul registrului comerţului, cu informaţii complete care să reflecte situaţia la zi a solicitantului, prin care se dovedeşte că persoana juridică nu desfăşoară ca activitate principală producerea de energie electrică;
			</li>
			<li>
				procesul-verbal care confirmă recepţia la terminarea lucrărilor aferente instalației de producere a energiei electrice și a instalației de stocare, după caz, întocmit de executantul lucrării;
			</li>
			<li>
				buletinul de încercare a prizei a pământ;
			</li>
			<li>
				certificatele de conformitate și fișele tehnice ale invertoarelor și unităților generatoare cu datele și funcțiile corespunzătoare, emise de fabricant, în copie, și, după caz, fișele tehnice emise de fabricant, în copie, ale instalației de stocare;
			</li>
			<li>
				schema electrică monofilară a instalației de producere a energiei electrice și modul de racordare a acesteia în instalația de utilizare existentă, cu precizarea protecțiilor prevăzute și reglajele acestora.
			</li>
		</ol>

		<p>
			În sprijinul solicitării mele, transmit următoarele informații privind:
		</p>

		<ol style="list-style-type:lower-alpha; margin-right:10%;">
			<li>
				deținerea de sisteme de stocare a energiei electrice produse din surse regenerabile

				<p>□ NU DETIN [x] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; □ DETIN [ ]</p>

				<table align="center" border="1" cellspacing="0" style="width:80%;page-break-inside:avoid;">
					<tbody>
						<tr>
							<td>Detalii schemă alimentare</td>
							<td colspan="2">{{ $tip_acumulatori }}</td>
						</tr>
						<tr>
							<td style="width:100%">Capacitate baterii de acumulatoare</td>
							<td style="width:100%">{{ $capacitate_acumulatori }}</td>
							<td>Ah</td>
						</tr>
					</tbody>
				</table>
			</li>

			<li>
				echipamentele de măsurare a energiei electrice montate în instalațiile de utilizare, altele decât cele aparținând operatorilor de distribuție, și caracteristicile acestora, respectiv: serie contor, tip contor, date tehnice;

				<p>□ NU DETIN [x]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; □ DETIN [ ]</p>
			</li>
		</ol>

		<table border="1" cellspacing="0">
			<tbody>
				<tr>
					<td>Nr. crt.</td>
					<td>Serie contor</td>
					<td>Tip contor</td>
					<td>Date tehnice</td>
				</tr>
				<tr>
					<td>1</td>
					<td>2</td>
					<td>3</td>
					<td>4</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

		<p>
			Declar pe propria răspundere că datele și informațiile cuprinse în prezenta cerere sunt autentice şi că documentele anexate, în copie, sunt conforme cu originalul.
		</p>

		<p>
			Data {{ date('d/m/Y') }}
		</p>

		<table style="border:none;">
			<tr>
				<td align="right">
                    <img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;">
				</td>
				<td align="right" style="width:250px;">
					Solicitant/Imputernicit,<br>
					POPA ALEXANDRU<br>
					{{ $fi ? $fi->nume_firma : '...................' }}<br>
					(numele, prenumele şi semnătura)
				</td>
			</tr>
		</table>

		<p>
			Prin semnarea prezentei cereri confirm primirea unui exemplar al Notei de informare cu privire la prelucrarea datelor cu caracter personal, ce conţine printre altele, informaţii referitoare la scopul şi temeiul prelucrăriii, durata prelucrării, drepturile mele, măsuri de securitate, reclamaţii etc.
		</p>

		<p>
			Data {{ date('d/m/Y') }}
		</p>

		<table style="border:none;">
			<tr>
				<td align="right">
                    <img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;">
				</td>
				<td align="right" style="width:250px;">
					Solicitant/Imputernicit,<br>
					POPA ALEXANDRU<br>
					{{ $fi ? $fi->nume_firma : '...................' }}<br>
					(numele, prenumele şi semnătura)
				</td>
			</tr>
		</table>

		<script type="text/php">
			if ( isset($pdf) ) { 
			    $pdf->page_script('
			        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
			        $size = 9;
			        if ($PAGE_COUNT > 1) {
			            $text = "Pagina " . $PAGE_NUM . " din " . $PAGE_COUNT;
				        $x = 30;
				        $y = $pdf->get_height() - 35;
			            $pdf->text($x, $y, $text, $font, $size);
			        }
		            $text = "DEGR E P46-F11 Ed.1";
		            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
			        $x = ($pdf->get_width() - $width * 2) - 30;
			        $y = $pdf->get_height() - 35;
		            $pdf->text($x, $y, $text, $font, $size);
			    ');
			}
		</script>
	</body>
</html>