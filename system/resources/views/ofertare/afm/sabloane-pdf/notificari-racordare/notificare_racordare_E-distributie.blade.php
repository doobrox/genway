<!DOCTYPE html>
<html>
    <head>
        <title>Notificare racordare loc consum E-Distribuție</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            *, html, body {
                font-size: 12px;
                font-family: "DejaVu Sans", sans-serif;
            }
            @page { margin-top: 120px; }
            header{
            	position:fixed;
            	top: -90px;
            	width: 100%;
            }
            sup {
            	font-size: 7px;
            }
            .titlu {
                font-size: 16px;
                text-align: left;
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
    	
		<header>
			<p class="text-center">
        		<small>INTERNAL</small>
        	</p>
        	<p>
        		<span style="float: right; text-align: right;">
        			UZ PUBLIC, devine UZ CONFIDENTIAL dupa completare<br>
        			<em style="font-size: 9px;">Conform Politicii de Clasificare si Tratare a Informatiei nr. 59/31.03.2016</em>
        		</span>
        	</p>
		</header>

		<div>
			<h1 class="titlu">
				ANEXA nr. 2: NOTIFICARE privind racordarea la un loc de consum existent a unei instalaţii de producere a energiei electrice cu putere instalată de cel mult 100 kW pe loc de consum</strong>
			</p>
			<p>Utilizatorul {{ $nume }} {{ $prenume }} cu domiciliul/sediul in judetul {{ $judet_domiciliu }} municipiul/orasul/comuna/satul/sectorul {{ $localitate_domiciliu }}, str. {{ $strada_domiciliu }} nr. {{ $numar_domiciliu }}, bl. {{ $bloc_domiciliu }}, sc. {{ $scara_domiciliu }}, et. {{ $et_domiciliu }}, ap. {{ $ap_domiciliu }}, cod postal ......, telefon/telefon mobil/fax {{ $telefon }}, e-mail: {{ $email }}, cod de inregistrare fiscala ...., CNP {{ $cnp }}, inregistrat la oficiul registrului comertului cu nr. ...., reprezentat prin, în calitate de ........................................................., contul ..........................................................., deschis la banca..........................., sucursala................, reprezentat prin împuternicit/persoană fizică autorizată/reprezentant al operatorului economic atestat/furnizor de energie electrică {{ $fi ? $fi->nume_firma : '...................' }}, CNP ................................. , cu domiciliul/sediul în Mun. București municipiul/orașul/comuna/satul/sectorul SECTOR 2, codul poștal ...................., str. Bvd. Chisinau nr. 8, bl. M2, sc. C, ap. 84, telefon/telefon mobil/fax: 0747773031, e-mail: office@genway.ro, nr/data act autorizare {{ $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '13692/28.09.2018' }} emis de ANRE, notific prin prezenta racordarea la locul de consum existent situat în <strong>judetul {{ $judet_imobil }} municipiul/orasul/comuna/satul/sectorul {{ $localitate_imobil }}, str. {{ $strada_imobil }} nr. {{ $numar_imobil }}, bl. ......., sc. ......, et. ....., ap. ....., nr. CF .................., înregistrat în cartea funciară nr. {{ $nr_carte }}, având nr. cadastral {{ $nr_cadastral }}, cod loc consum {{ $cod_loc_consum }}</strong>, având codul unic de identificare {{ $pod }} (înscris pe factura de energie electrică) a unei instalații de producere a energiei electrice cu putere maximă simultană ce poate fi evacuată în rețeaua de distribuție de {{ $min_putere }} kW.
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
				(1) Date tehnice și energetice aferente instalației de producere a energiei electrice:
			</h1>

			<p>
				a) Generatoare asincrone si sincrone
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
					@for($i = 1 ; $i <= 4 ; $i++)
						<tr>
							@for($j = 1 ; $j <= 15 ; $j++)
								<td>{!! $i == 1 ? $j : '&nbsp;' !!}</td>
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
			<p>
				NOTĂ:<br>
				U.G. = unitate generatoare; panou = panou fotovoltaic; As - asincron; S - sincron; T - termo; H - hidro; E
				-	eolian; Qmax = putere reactivă maximă; Qmin = putere reactivă minimă;<br>
				Pn = putere activă nominală; Pi = putere activă instalată; Pmax = putere activă maximă; Pmin = putere activă minimă; Sn = putere aparentă nominală;<br>
				Un = tensiune nominală la borne;<br>
				U = tensiunea în punctul de racordare;<br>
				Sevac = puterea aparentă aprobată pentru evacuare în reţea.
			</p>
			<p>
				b) Mijloace de compensare a energiei reactive: <br><span>NU [x]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DA [ ]</span>
			</p>

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
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
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
				* Se completează dacă tipul de echipament de compensare utilizat are reglaj în trepte.<br>
				NOTĂ: <br>Qn - putere reactivă nominală; Qmax = putere reactivă maximă; Qmin = putere reactivă minimă;
			</p>

			<p>
				c) Module generatoare de tip fotovoltaic:
			</p>

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
						<td>{{ $putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td>{{ $total_putere_panouri }}</td>
						<td>&nbsp;</td>
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
						<td colspan="3"><strong>TOTAL:</strong></td>
						<td></td>
						<td>{{-- {{ $total_putere_panouri }} --}}</td>
						<td>{{-- {{ $total_putere_panouri }} --}}</td>
						<td>{{-- {{ $total_putere_panouri }} --}}</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>

			<p>
				NOTĂ: <br><span>Pi = putere activă instalată; Pmax = putere activă maximă;</span>
				<span>c.c. = curent continuu; c.a. = curent alternativ;</span>.
			</p>
		</div>

		<p> d) Invertoare </p>

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
					<td>{{ $invertor ? $invertor->cod : '' }}</td>
					<td>{{ $ca_invertor }}</td>
					<td>{{ $putere_invertor }}</td>
					<td>{{ $putere_invertor }}</td>
					<td>{{ $total_putere_panouri }}</td>
					<td>AFM dosar nr. {{ $numar_dosar_afm }}</td>
				</tr>
				{{-- <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr> --}}
				<tr>
					<td colspan="4"><strong>TOTAL: </strong></td>
					<td>{{-- {{ $putere_invertor }} --}}</td>
					<td>{{-- {{ $putere_invertor }} --}}</td>
					<td>{{-- {{ $total_putere_panouri }} --}}</td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

		<p>
			NOTĂ: <br><span>Un = tensiune nominală la borne; Pi = putere activă instalată; Pmax = putere activă maximă; c.c. = curent continuu; c.a. = curent alternativ;</span>.
		</p>

		<p>
			e) Serviciile interne ale instalației producere:
		</p>

		<table align="center" border="1" cellspacing="0" style="width: 80%;">
			<tbody>
				<tr>
					<td style="text-align: left">Pi servicii interne</td>
					<td>[0,001]</td>
					<td>kW</td>
				</tr>
				<tr>
					<td style="text-align: left">Puterea maximă simultan absorbită servicii interne</td>
					<td>[0,001]</td>
					<td>kW</td>
				</tr>
			</tbody>
		</table>
		<p><span>Pi = putere activă instalată;</span></p>

		<h1>(2) Anexez prezentei următoarele documente:</h1>

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

		<h1>
			(3) În sprijinul solicitării mele, transmit următoarele informații privind:
		</h1>

		<ol style="list-style-type:lower-alpha; margin-right:10%;">
			<li>
				deținerea de sisteme de stocare a energiei electrice produse din surse regenerabile

				<p>NU DETIN [x] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DETIN [ ]</p>

				<table align="center" border="1" cellspacing="0" style="width:80%">
					<tbody>
						<tr>
							<td>Detalii schemă alimentare</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td style="width:100%">Capacitate baterii de acumulatoare</td>
							<td style="width:100%">&nbsp;</td>
							<td>Ah</td>
						</tr>
					</tbody>
				</table>
			</li>

			<li>
				echipamentele de măsurare a energiei electrice montate în instalațiile de utilizare, altele decât cele aparținând operatorilor de distribuție, și caracteristicile acestora, respectiv: serie contor, tip contor, date tehnice;

				<p>NU [x]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DA [ ]</p>
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
			Tehnicienii E-Distribuție pot recomanda, pe parcursul procesului de obținere a avizului tehnic de racordare/ emitere a certificatului de racordare, folosirea serviciului Vizita virtuală (<a target="_blank" href="https://www.e-distributie.com/ro/servicii-online-si-informatii/racordare/Vizita.html">www.e-distributie.com/ro/servicii-online-si-informatii/racordare/Vizita.html</a>).<br>
			Aceasta este alternativa virtuală a unei vizite în teren și este realizată de către tehnicienii E-Distribuție printr-un apel video, pentru a stabili la fața locului, într-un timp cât mai scurt, cea mai bună soluție tehnică.
		</p>


		<p>Te putem contacta în cazul în care soluția de racordare poate fi stabilită printr-o vizită virtuală?</p>
		<p class="text-center"><span style="font-size:2rem;">□</span> DA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:2rem;">□</span> NU</p>


		<p><strong>Data: {{ date('d/m/Y') }}</strong></p>

		<table style="border:none;">
			<tr>
				<td align="left" style="width:35%;">
					Solicitant/Imputernicit,<br>
					<strong>
						POPA ALEXANDRU<br>
						{{ $fi ? $fi->nume_firma : '...................' }}<br>
					</strong>
					(numele, prenumele şi semnătura)
				</td>
				<td align="left">
                    <img src="{{ $base64_stamp_img }}" style="width:120px;height:auto;margin-right:10px;">
				</td>
			</tr>
		</table>
		{{-- PAGE BREAK --}}
		<div style="page-break-after: always;"></div>

		<div>
			<h1 style="text-align: center;">
				Notă de informare privind condițiile de prelucrare a datelor cu caracter personal
			</h1>

			<ul>
				<li>
					<strong>Operatorul de prelucrare a datelor cu caracter personal</strong>
				</li>
			</ul>

			<p>
				E- Distribuție Muntenia S.A., cu sediul &icirc;n Bucuresti, B-dul Mircea Voda nr.30, sectorul 3, &icirc;nregistrată la Registrul Comerțului sub nr. J40/1859/2002, cod unic de &icirc;nregistrare 14507322, (denumită &icirc;n continuare &ldquo;<strong>Operator</strong>&rdquo;), &icirc;n calitate de Operator va prelucra datele dumneavoastră cu caracter personal &icirc;n conformitate cu dispozițiile legale incidente &icirc;n domeniul protecției datelor cu caracter personal, precum și &icirc;n conformitate cu prevederile prezentei note de informare.
			</p>

			<ul>
				<li>
					<strong>Responsabilul cu protecția datelor cu caracter personal (RPD)</strong>
				</li>
			</ul>

			<p>
				Operatorul a numit un RPD care poate fi contactat &icirc;n cazul &icirc;n care considerați necesar la următoarea adresă de e-mail : <a href="mailto:dpo.e-distributie@enel.com.">dpo.e-distributie@enel.com.</a>
			</p>

			<ul>
				<li>
					<strong>Scopul și modalitatea de prelucrare a datelor cu caracter personal</strong>
				</li>
			</ul>

			<p>
				Operatorul va prelucra urmatoarele date personale: <em>nume, prenume, domiciliu, serie si numar act identitate, cod numeric personal, semnatura, adresa email personala sau profesionala, numar telefon personal sau profesional, date privind bunurile detinute (acte de proprietate, autorizatii de construire/ demolare si alte documente referitoare la proprietate), date bancare, datele privind sanatatea pentru clienții vulnerabili, date referitoare la consumul de energie electrică (respectiv consumul de energie, coordonatele contorului, seria si numarul de contor) imagini video, poze, date de geolocalizare), </em>(denumite &icirc;n cele ce urmează &ldquo;<strong>Date</strong><strong> </strong><strong>p</strong><strong>ersonale</strong>&rdquo;), Date personale obținute de către Operator &icirc;n mod legitim, conform indicațiilor de mai jos.
			</p>

			<p>
				Operatorul are acces la datele tale și din surse externe, astfel cum poate fi furnizorul de energie electrica.
			</p>

			<p>
				&Icirc;n sensul prezentei note, prelucrarea Datelor personale &icirc;nseamnă orice operațiune sau ansamblu de operațiuni efectuate cu sau fără utilizarea de procese automatizate și aplicate Datelor personale, cum ar fi: colectarea, &icirc;nregistrarea, organizarea, structurarea, stocarea, adaptarea sau modificarea, extragerea, consultarea, utilizarea, comunicarea &nbsp;prin &nbsp;transmitere, &nbsp;diseminarea &nbsp;sau &nbsp;punerea &nbsp;la &nbsp;dispoziție &nbsp;&icirc;n &nbsp;orice &nbsp;alt &nbsp;mod, &nbsp;compararea &nbsp;sau interconectarea, restricționarea, ștergerea sau distrugerea.
			</p>

			<p>
				Vă informăm că aceste Date Personale vor fi prelucrate at&acirc;t prin intermediul unor metode manuale, precum și/sau prin intermediul unor mijloace informatice sau telematice.
			</p>

			<ul>
				<li>
					<strong>Scopul și baza juridică a prelucrării</strong>
				</li>
			</ul>

			<p>
				Operatorul va prelucra datele dumneavoastră cu caracter personal &icirc;n scopul &icirc;ndeplinirii activităților de distribuție conform licenței deținute, &nbsp;dar și &icirc;n scopul executării contractului de distribuție a energiei electrice sau pentru efectuarea unor demersuri pre-contractuale la cerere (racordare la rețelele electrice, eliberarea avizelor de amplasament).
			</p>

			<p>
				Temeiul juridic al prelucrării Datelor personale este reprezentat de contractul de distribuție a energiei electrice, de protejarea intereselor legitime urmărite de Operator pentru realizarea scopurilor mentionate, de conformare fata de obligatiile legale ce revin Operatorului, dar si de daptul ca prelucrarea este necesară pentru &icirc;ndeplinirea unei sarcini care servește unui interes public. Prelucrarea datelor cu privire la starea de sanatate a clientilor vulnerabili este necesară din motive de interes public major, &icirc;n baza dreptului Uniunii sau a dreptului roman.
			</p>

			<p>
				Interesul legitim urmărit este de a intreprinde demersuri &icirc;n scopul &icirc;mbunătățirii constante a serviciilor prestate clienților; &nbsp;implementarea &nbsp;avertizorilor &nbsp;de &nbsp;integritate &nbsp;&icirc;n &nbsp;scopul &nbsp;prevenirii &nbsp;fraudelor &nbsp;economice &nbsp;şi &nbsp;informatice, respectiv &nbsp;operaționalizarea &nbsp;instrumentelor &nbsp;de &nbsp;comunicare &nbsp;puse &nbsp;la &nbsp;dispoziția &nbsp;Clienților &nbsp;și &nbsp;a &nbsp;terților &nbsp;&icirc;n &nbsp;vederea transmiterii sesizărilor referitoare la &icirc;ncălcarea de către salariații subscrisei a Codului Etic, a Programului Global de conformitate, a Planului de toleranță zero &icirc;mpotriva corupţiei, a liniilor Directoare 231, Regulamentului Intern, precum și a Programului Safety First, precum și interesul legitim const&acirc;nd &icirc;n activității de recuperare a creanțelor.
			</p>

			<p>
				Transmiterea anumitor Date personale ale dumneavoastră este necesară pentru ducerea la &icirc;ndeplinire a obiectului contractului de distribuție a energiei electrice sau a contractului de racordare la rețelele electrice de interes public sau pentru efectuarea unor demersuri pre-contractuale la cerere, prin urmare, un eventual refuz din partea dumneavoastră ar putea conduce imposibilitatea &icirc;ncheierii și executării de către Operator a contractelor menționate anterior.
			</p>
		</div>

		<div>
			<ul>
				<li>
					<strong>Destinatarii Datelor cu caracter personal</strong>
				</li>
			</ul>

			<p>
				Datele dumneavoastră personale vor putea fi dezvăluite, &icirc;n scopurile amintite mai sus, următorilor destinatari:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					angajaților și colaboratorilor Operatorului prezenți pe teritoriul Uniunii Europene;
				</li>
				<li>
					societăților terțe sau a altor destinatari, precum partenerii contractuali &icirc;n contractele aferente serviciilor aferente &nbsp;activității &nbsp;de &nbsp;distributie &nbsp;(activități &nbsp;de &nbsp;citire &nbsp;contori, &nbsp;proiectare, &nbsp;execuție &nbsp;lucrări &nbsp;&icirc;n &nbsp;sistemul &nbsp;de distribuție, remediere avarii) sau societățile de recuperare creanțe (&ldquo;Terțe Părți&rdquo;) care desfășoară activități &icirc;n favoarea Operatorului, &icirc;n calitatea lor de imputerniciti ai Operatorului.
				</li>
				<li>
					către autoritățile statului (ANRE, ANAF, ANAP, ITM, organele de urmărire penală etc.), spre exemplu, &icirc;n vederea &icirc;ndeplinirii obligatiilor prevazute de legislatia fiscala, legislatia muncii, luptă anticorupție, efectuarea operațiunilor contabile, derularea de proceduri de achiziții sau &icirc;n vederea punerii &icirc;n aplicare a hotăr&acirc;rilor judecătorești și/sau a altor sentințe/decizii emise de către instanțele judecătorești și/sau a altor organisme &icirc;nvestite de statul roman cu putere decizională (spre exemplu, dar fară a se limita la: transmiterea de informații către organele de urmarire penală, ANRE);
				</li>
			</ol>

			<ul>
				<li>
					<strong>Transferul Datelor cu caracter personal</strong>
				</li>
			</ul>

			<p>
				Datele dumneavoastră cu caracter personal vor fi prelucrate &icirc;n Uniunea Europeană și păstrate &icirc;n serverele din Uniunea Europeană.
			</p>

			<ul>
				<li>
					<strong>Perioada de păstrare a Datelor cu caracter personal</strong>
				</li>
			</ul>

			<p>
				Datele cu caracter personal vor fi păstrate conform principiilor de proporționalitate și necesitate, și &icirc;n orice caz p&acirc;nă la atingerea scopurilor &icirc;n care au fost prelucrate.
			</p>

			<p>
				Ulterior, respectiv fie la data &icirc;ncetării relației de colaborare, fie p&acirc;nă la expirarea obligației legale de arhivare ce revine Operatorului &icirc;n temeiul legislației aplicabile &icirc;n materie fiscală, contabilă sau potrivit reglementărilor ANRE, datele vor fi șterse.
			</p>

			<ul>
				<li>
					<strong>Drepturile persoanelor vizate</strong>
				</li>
			</ul>

			<p>
				Potrivit art. 15 - 21 din GDPR, referitor la Datele cu caracter personal comunicate, aveți dreptul:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					să aveți acces la acestea și să solicitați o copie;
				</li>
				<li>
					să solicitați rectificarea acestora;
				</li>
				<li>
					să solicitați ștergerea acestora;
				</li>
				<li>
					să obțineți restricționarea prelucrării datelor;
				</li>
				<li>
					să vă opuneți prelucrării datelor;
				</li>
				<li>
					să primiți datele &icirc;ntr-un format structurat, utilizat &icirc;n mod curent și care poate fi citit de pe un dispozitiv automat
				</li>
			</ol>

			<p>
				Mai multe informarții cu privire la drepturi și modul de exercitare al acestora puteți regăsi &icirc;n documentul &bdquo;<em>Informații </em><em>suplimentare referitoare la exercitarea drepturilor de care beneficiază persoanele vizate&rdquo;. </em>Exercitarea drepturilor o veți putea face oric&acirc;nd utilizand unul dintre canalele noastre de contact disponibile pe site-ul <a href="http://www.e-distributie.com/">www.e-</a><a href="http://www.e-distributie.com/">distribuție.com.</a>
			</p>

			<p>
				Pentru mai multe informații cu privire la Datele dumneavoastră cu caracter personal vă veți putea adresa Responsabilului cu protecția datelor cu caracter personal al companiei, ce poate fi contactat la adresa de e-mail: <a href="mailto:dpo.e-distributie@enel.com">dpo.e-distributie@enel.com.</a>
			</p>

			<p>
				Vă amintim că aveți dreptul de a depune o pl&acirc;ngere la autoritatea națională de supraveghere a prelucrării datelor cu caracter personal &icirc;n cazul &icirc;n care considerați că prelucrarea datelor cu caracter personal &icirc;ncalcă prevederile Regulamentului.
			</p>
		</div>

		<div>
			<h1 style="text-align: center;">
				INFORMATII SUPLIMENTARE REFERITOARE LA EXERCITAREA DREPTURILOR DE CARE BENEFICIAZA PERSOANELE VIZATE
			</h1>

			<p>
				In conformitate cu prevederile Regulamentului U.E. nr. 679/2016 privind protectia persoanelor fizice in ceea ce priveste prelucrarea datelor cu caracter personal si privind libera circulatie a acestor date si de abrogare a Directivei 95/46/CE (denumit in continuare &ldquo;<em>Regulamentul</em>&rdquo;), precizam ca orice persoana vizata are urmatoarele drepturi atunci cand vine vorba despre prelucrarea datelor cu caracter personal:
			</p>

			<ol style="list-style-type: upper-alpha;">
				<li>
					<strong>DREPTUL DE ACCES LA DATELE CU CARACTER PERSONAL</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a obţine, la cerere, de la E-Distributie Muntenia (denumita in continuare &bdquo;<em>Operator&rdquo;) </em>confirmarea faptului că datele &icirc;n legătura cu persoana acestora sunt sau nu prelucrate de către Operator si in caz afirmativ, acces la datele respective, precum si urmatoarele informatii:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					scopurile prelucrarii;
				</li>
				<li>
					categoriile de date cu caracter personal prelucrate;
				</li>
				<li>
					destinatarii sau categoriile de destinatari carora datele cu caracter personal le-au fost sau urmeaza sa le fie divulgate;
				</li>
				<li>
					acolo unde este posibil, perioada pentru care se preconizeaza ca vor fi stocate datele cu caracter personal sau, daca acest lucru nu este posibil, criteriile utilizate pentru a stabili această perioada,
				</li>
				<li>
					existenta dreptului de a solicita rectificarea sau stergerea datelor cu caracter personal ori restrictionarea prelucrarii datelor cu caracter personal referitoare la persoana vizata sau a dreptului de a se opune prelucrarii;
				</li>
				<li>
					dreptul de a depune o plangere in fața Autoritatii Nationale de Supraveghere a Prelucrarii datelor cu Caracter Personal in cazul in care considerati ca prelucrarea datelor cu caracter personal incalca prevederile Regulamentului;
				</li>
				<li>
					in cazul in care datele cu caracter personal nu sunt colectate de la persoana vizata, orice informații disponibile privind sursa acestora;
				</li>
				<li>
					daca este cazul, existenta unui proces decizional automatizat incluzand crearea de profiluri, precum si, cel putin in cazurile respective, informatii pertinente privind logica utilizata si privind importanta si consecintele preconizate ale unei astfel de prelucrari pentru persoana vizata
				</li>
				<li>
					in cazul in care datele cu caracter personal sunt transferate către o tara terta sau o organizatie internationala, informatiile cu privire la garantiile adecvate implementate pentru transferul datelor.<br>
					De asemenea, Operatorul are obligatia de a furniza o copie<sup>1</sup> a datelor cu caracter personal care fac obiectul prelucrarii.
				</li>
			</ol>

			<small style="font-size: 8px;"><sup>1</sup> Pentru orice alte copii solicitate suplimentar de catre Clienti, Operatorul are dreptul de a percepe o taxa rezonabila, bazata pe costurile administrative in conformitate cu prevederile art. 15 din Regulament.</small>

			<ol style="list-style-type: upper-alpha;" start="2">
				<li>
					<strong>DREPTUL DE RECTIFICARE A DATELOR CU CARACTER PERSONAL</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a obţine, <strong>la cerere</strong>, de la Operator rectificarea datelor cu caracter personal inexacte si/sau incomplete, inclusiv prin transmiterea de catre client a unei declaraţii suplimentare pentru rectificarea datelor cu caracter personal si/sau prin transmiterea unei asemenea declaratii suplimentare de catre client la solicitarea Opetorului, daca este cazul.
			</p>

			<p>
				Urmare a exercitarii acestui drept, Operatorul are obligatia de a comunica oricarui Destinatar orice rectificarea efectuata asupra datelor cu caracter personal, cu exceptia cazului in care acest lucru se dovedeste imposibil sau presupune eforturi disproportionate. De asemenea, Operatorul are obligatia de a informa solicitantul cu privire la destinatarii respective doar daca acesta din urma solicita acest lucru.
			</p>

			<ol style="list-style-type: upper-alpha;" start="3">
				<li>
					<strong>DREPTUL DE STERGERE A DATELOR CU CARACTER PERSONAL</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a obtine, la cerere, de la Operator stergerea datelor cu caracter personal referitoare la persoana acestora atunci cand, cel putin unul dintre urmatoarele motive, intervine:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					datele cu caracter personal nu mai sunt necesare pentru ducerea la indeplinire a scopurilor pentru care au fost colectate sau prelucrate;
				</li>
				<li>
					Clientul isi retrage consimtamantul pe baza caruia are loc prelucrarea si nu exista niciun alt temei juridic pentru desfasurarea operatiunilor de prelucrare a datelor cu caracter personal;
				</li>
				<li>
					Clientul se opune prelucrarii realizate in temeiul interesului legitim al operatorului sau pentru indeplinirea unei sarcini care serveste unui interes public sau care rezulta din exercitarea autoritatii publice cu care este investit operatorul, din motive legate de situatia sa particulara si nu exista alte motive legitime pentru desfasurarea operatiunilor de prelucrare a datelor cu caracter personal;<br>
				</li>
			</ol>
				
			<ol style="list-style-type: lower-alpha;" start="4">
				<li>
					Clientul se opune prelucrarii datelor cu caracter personal in scop de marketing direct;
				</li>
				<li>
					prelucrarea datelor cu caracter personal nu a fost efectuata in conformitate cu prevederile Regulamentului;
				</li>
				<li>
					datele cu caracter personal trebuie sterse pentru respectarea unei obligatii legale care revine operatorului de date cu caracter personal in temeiul dreptului U.E. sau al dreptului roman;
				</li>
				<li>
					datele cu caracter personal au fost colectate in legatura cu oferirea de servicii ale societatii informationale oferite in mod direct unui minor.
				</li>
			</ol>

			<p>
				De asemenea, in cazul in care Operatorul a transmis datele cu caracter personal catre alti destinatari, Operatorul este obligat sa-i informeze pe acestia asupra faptului ca, Clientii au solicitat stergerea datelor cu caracter personal, precum si sa solicite Destinatarilor stergerea oricaror trimiteri (linkuri) catre datele respective si/sau a oricaror copii sau reproduceri ale acestor date. In vederea transmiterii acestei solicitari catre ceilalti Destinatari, Operatorul va tine cont de tehnologia disponibila si de costul implementarii unor asemnea masuri de informare a destinatarilor.
			</p>

			<p>
				Totusi, cererea Clientilor referitoare la de stergerea datelor cu caracter personal nu poate fi pusa in aplicare de catre Operator atunci cand operatiunile de prelucrare a datelor cu caracter personal sunt necesare:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					pentru exercitarea dreptului la libera exprimare si la informare;
				</li>
				<li>
					pentru respectarea unei obligatii legale care prevede prelucrarea &icirc;n temeiul dreptului U.E. sau al dreptului roman;
				</li>
				<li>
					pentru indeplinirea unei sarcini executate in interes public de catre Operatorul de date cu caracter personal;
				</li>
				<li>
					din motive de interes public in domeniul sanatatii publice;
				</li>
				<li>
					in scopuri de arhivare in scop de interes public, de cercetare stiintifica sau istorica ori in scopuri statistice si doar in masura in care exercitarea dreptului de stergere este susceptibil sa faca imposibila sau sa afecteze in mod grav realizarea obiectivelor prelucrarii respective;;
				</li>
				<li>
					pentru constatarea, exercitarea sau apararea unui drept in instanta.
				</li>
			</ol>

			<p>
				Urmare a exercitarii acestui drept, Operatorul are obligatia de a comunica oricarui Destinatar orice stergere a datelor cu caracter personal, cu exceptia cazului in care acest lucru se dovedeste imposibil sau presupune eforturi disproportionate. De asemenea, Operatorul are obligatia de a informa solicitantul cu privire la destinatarii respective doar daca acesta din urma solicita acest lucru.
			</p>

			<ol style="list-style-type: upper-alpha;" start="4">
				<li>
					<strong>DREPTUL DE RESTRICTIONARE A DATELOR CU CARACTER PERSONAL</strong>
				</li>
			</ol>

			<p>
				Clientii &nbsp;au &nbsp;dreptul &nbsp;de &nbsp;a &nbsp;obţine, &nbsp;la &nbsp;cerere, &nbsp;restrictionarea &nbsp;prelucrarii &nbsp;datelor &nbsp;cu &nbsp;caracter &nbsp;personal &nbsp;atunci &nbsp;cand intervine cel putin una dintre urmatoarele situatii:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					Clientii contesta exactitatea datelor, masura de restrictionare a datelor cu caracter personal urmand sa fie aplicata pe toata durata de timp necesara care ii permite operatorului de date cu caracter personal sa verifice exactitatea datelor;
				</li>
				<li>
					prelucrarea datelor cu caracter personal nu a fost efectuata in conformitate cu prevederile Regulamentului, iar Clientii se opun stergerii datelor cu caracter personal, solicitand in schimb restrictionarea utilizarii acestora;
				</li>
				<li>
					Operatorul nu mai are nevoie de datele cu caracter personal ale Clientilor in scopul prelucrarii acestora, insa Clientii le solicita pentru constatarea, exercitarea sau apararea unui drept in instanta;
				</li>
				<li>
					Clientii s-au opus desfasurarii operatiunilor de prelucrare a datelor cu caracter personal realizate in temeiul interesului legitim al operatorului sau pentru indeplinirea unei sarcini care serveste unui interes public sau care rezulta din exercitarea autoritatii publice cu care este investit operatorul din motive legate de situatia lor particulara, masura de restrictionare a datelor cu caracter personal urmand sa fie aplicata pe toata durata de timp in care se verifica dacă drepturile legitime ale Operatorului prevaleaza asupra drepturilor Clientilor;
				</li>
			</ol>

			<p>
				De asemene, in cazul in care prelucrarea datelor cu caracter personal este restrictionata ca urmare a intervenirii uneia dintre situatiile mentionate anterior,
			</p>

			<ul>
				<li>
					datele cu caracter personal care fac obiectul restrictionarii pot fi prelucrate numai cu consimtamantul Clientilor sau pentru constatarea, exercitarea sau apararea unui drept in instanta sau pentru protectia drepturilor unei alte persoane fizice sau juridice sau din motive de interes public important al U.E. sau al unui stat membru. Restrictionarea prelucrarii datelor cu caracter personal nu are in vedere operatiunile de stocare a acestor date;
				</li>
				<li>
					orice Client care a obtinut restrictionarea prelucrarii datelor cu caracter personal va fi informat de catre Operator inainte de ridicarea restrictiei de prelucrare.
				</li>
			</ul>

			<p>
				Urmare a exercitarii acestui drept, Operatorul are obligatia de a comunica oricarui Destinatar orice restrictionare efectuata asupra datelor cu caracter personal, cu exceptia cazului in care acest lucru se dovedeste imposibil sau presupune eforturi disproportionate. De asemenea, Operatorul are obligatia de a informa solicitantul cu privire la destinatarii respective doar daca acesta din urma solicita acest lucru.
			</p>
		</div>

		<div>
			<ol style="list-style-type: upper-alpha;" start="5">
				<li>
					<strong>DREPTUL LA PORTABILITATEA DATELOR CU CARACTER PERSONAL</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a obţine, la cerere, de la Operator datele cu caracter personal care ii privesc si pe care acestia le-au furnizat catre Operator intr-un format structurat, utilizat in mod curent si care poate fi citit automat, acestia avand totodata dreptul de a solicita transmiterea acestor date altui operator de date cu caracter personal atunci cand:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					prelucrarea datelor cu caracter personal de catre Operator se bazează pe consimtamantul Clientului;
				</li>
				<li>
					prelucrarea datelor cu caracter personal de catre Operator este necesara pentru executarea unui contract in care Clientul este parte sau pentru a face niste demersuri la cererea Clientului inainte de incheierea unui contract;
				</li>
				<li>
					prelucrarea datelor cu caracter personal de catre Operator este efectuată prin mijloace automate.
				</li>
			</ol>

			<p>
				In cazul in care solicitarea Clientului indeplineste una dintre conditiile mentionate anterior si doar atunci cand acest lucru este fezabil din punct de vedere tehnic, Cientul are dreptul de a solicita transmiterea directa a datelor cu caracter personal de la Operator la un alt operator de date cu caracter personal.
			</p>

			<p>
				Potrivit Regulamentului, Clientii nu pot exercita acest drept atunci cand prelucrarea datelor cu caracter personal este necesara pentru indeplinirea unei sarcini executate de catre Operator in interes public.
			</p>

			<ol style="list-style-type: upper-alpha;" start="6">
				<li>
					<strong>DREPTUL LA OPOZIŢIE</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul
			</p>

			<ol style="list-style-type: upper-roman;">
				<li>
					<strong>de a se opune &icirc;n orice moment, din motive legate de situatia particulara in care se afla</strong>, operatiunilor de prelucrare a datelor cu caracter personal, inclusiv crearii de profiluri<sup>2</sup>, atunci cand prelucrarea este necesara:

					<ol>
						<li>
							pentru indeplinirea de catre Operator a unei sarcini care serveste unui interes public;
						</li>
						<li>
							in scopul protejarii intereselor legitime urmarite de Operator sau de o terta parte, cu exceptia cazului in care prevaleaza interesele sau drepturile si libertatile fundamentale ale persoanei vizate, care necesita protejarea datelor cu caracter personal, in special atunci cand persoana vizata este un copil;
						</li>
						<li>
							in scopuri de cercetare stiintifica sau istorica sau in scopuri statistice, cu execeptia situatiei in care prelucrarea este necesara pentru indeplinirea de catre Operator a unei sarcini din motive de interes public.
						</li>
					</ol>
				</li>
			</ol>

			<p>
				In oricare dintre aceste situatii, Operator are obligatia de a inceta prelucrarea datelor cu caracter personal, cu exceptia cazului in care Operator demonstreaza ca are motive legitime si imperioase care justifica prelucrarea si care prevaleaza asupra intereselor si libertatilor Clientilor sau ca scopul acestei prelucrari este constatarea, exercitarea sau apararea unui drept in instanta.
			</p>

			<ol style="list-style-type: upper-roman;" start="2">
				<li>
					<strong>de a se opune in orice moment, fara nicio justificare, operatiunilor de prelucrare a datelor cu caracter personal in scop de marketing direct<sup>3</sup></strong>, inclusiv in ceea ce priveste crearea de profiluri, in masura in care aceasta activitate este legata activitatea respectiva de marketing direct.
				</li>
			</ol>

			<ol style="list-style-type: upper-alpha;" start="7">
				<li>
					<strong>DREPTURI IN LEGATURA CU UN EVENTUAL PROCES DECIZIONAL AUTOMAT</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a nu face obiectul unei decizii bazate exclusiv pe operatiuni de prelucrare automata, inclusiv crearea de profiluri, care produc efecte juridice asupra acestora sau care ii pot afecta in mod similar in mod semnificativ.
			</p>

			<p>
				Totusi, aceasta regula nu este aplicabila atunci cand decizia:
			</p>

			<ol>
				<li>
					este necesara pentru incheierea sau executarea unui contract intre Clienti si Operator, sub conditia existentei unor masuri corespunzatoare pentru protejarea drepturilor, libertatilor si intereselor legitime ale Clientilor, cel putin a dreptului de a obtine interventie umana, de a-si exprima punctul de vedere si de a contesta decizia luata prin astfel de mijloace;
				</li>
				<li>
					are la baza consimtamantul explicit al Clientilor, sub conditia existentei unor masuri corespunzatoare pentru protejarea drepturilor, libertatilor si intereselor legitime ale Clientilor, cel putin a dreptului de a obtine interventie umana,de a-si exprima punctul de vedere si de a contesta decizia luata prin astfel de mijloace;
				</li>
				<li>
					este autorizata prin dreptul Uniunii Europene sau dreptul romanesc si doar daca oricare dintre aceste dispozitii legale prevad, de asemenea, masuri corespunzatoare pentru protejarea drepturilor, libertatilor si intereselor legitime ale Clientilor;
				</li>
			</ol>

			<p>
				Categoriile de date cu caracter special<sup>4</sup> nu pot face face obiectul unor asemenea decizii decat daca prelucrarea acestora se intemeiaza pe consimtamantul explicit al Clientilor sau prelucrarea este necesara din motive de interes public major si doar daca au fost instituite masuri corespunzatoare pentru protejarea drepturilor, libertatilor si intereselor legitime ale Clientilor.
			</p>

			<small style="font-size: 8px;"><sup>2</sup> Orice forma de prelucrare automata a datelor cu caracter personal care consta in utilizarea datelor cu caracter personal pentru a evalua anumite aspecte personale referitoare la o persoana fizica, in special pentru a analiza sau prevedea aspecte privind situatia economica, sanatatea, preferintele personale, interesele, fiabilitatea, comportamentul, performanata la locul de munca, locul in care se afla persoana fizica respective sau deplasarile acesteia.</small> <br>
			<small style="font-size: 8px;"><sup>3</sup> Spre exemplu, dar fara a se limita la activitatile de reclama, marketing si publicitate efectuate prin direct-mailing, prin telefon, prin sms, prin e-mail, prin posta. </small> <br>
			<small style="font-size: 8px;"><sup>4</sup> Date cu caracter personal care dezvaluie originea rasiala sau etnica, opiniile politice, confesiunea religioasa sau convingerile filozofice sau apartenenta la sindicate si prelucrarea de date genetice, de date biometrice pentru identificarea unica a unei persoane fizice, de date privind sanatatea sau de date privind viata sexuala sau orientarea sexuala ale unei persoane fizice.
			</small>
			<br>
			<br>
			<br>
			<br>

			<ol style="list-style-type: upper-alpha;" start="8">
				<li>
					<strong>DREPTUL DE A INAINTA O PLANGERE</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de inainta o plangere catre Autoritatea Nationala de Supraveghere a Prelucrarii Datelor cu Caracter Personal cu privire la modul de prelucrare a datelor personale de catre Operator, &icirc;n cazul &icirc;n care acestia considera ca prelucrarea datelor cu caracter personal care ii vizeaza nu respecta dispozitiile Regulamentului.
			</p>

			<ol style="list-style-type: upper-alpha;" start="9">
				<li>
					<strong>DREPTUL DE A SE ADRESA JUSTITIEI</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a se adresa justitiei pentru apararea oricaror drepturi garantate de Regulament care au fost incalcate.
			</p>

			<ol style="list-style-type: upper-alpha;" start="10">
				<li>
					<strong>DREPTUL DE A FI INFORMAT CU PRIVIRE LA INCALCAREA SECURITATII DATELOR CU CARACTER PERSONAL</strong>
				</li>
			</ol>

			<p>
				Clientii au dreptul de a fi informati, din oficiu si fara intarziere<strong>, </strong>ori de cate ori apar situatii/cazuri in care incalcarea securitatii datelor cu caracter personal este susceptibila sa genereze un risc ridicat pentru drepturile si libertatile persoanelor fizice.
			</p>

			<p>
				Totusi, informarea Clientilor nu este necesara in cazul in care cel putin una dintre conditiile de mai jos este indeplinita:
			</p>

			<ol style="list-style-type: lower-alpha;">
				<li>
					Operatorul a implementat masuri de protectie tehnice si organizatorice adecvate iar aceste masuri au fost aplicate in cazul datelor cu caracter personal afectate de incalcarea securitatii datelor cu caracter personal, in special masuri prin care se asigura ca datele cu caracter personal devin neinteligibile oricarei persoane care nu este autorizata sa le acceseze, cum ar fi criptarea;
				</li>
				<li>
					Operatorul a luat masuri ulterioare prin care se asigura ca riscul ridicat pentru drepturile si libertatile persoanelor fizice nu mai este susceptibil sa se materializeze;
				</li>
				<li>
					informarea Clientilor ar implica un efort disproportionat.
				</li>
			</ol>

			<p>
				Informarea Clientilor cu privire la incalcarea securitatii datelor cu caracter personal se poate face prin transmiterea de mesaje informative (spre exemplu, dar fara a se limita la: posta fizica, posta electronica, intranet, informare mass media) fie catre fiecare Client in parte, fie in mod colectiv, catre un grup mai mare de Clienti.
			</p>

			<p>
				Oricare dintre aceste drepturi poate fi exercitat prin transmiterea de catre fiecare persoana vizata in parte sau de catre reprezentantii acesteia a unei cereri scrise, datate si semnate catre operatorul de distributie. In cerere trebuie sa fie mentionata si adresa de corespondenta si/sau adresa de posta electronica catre care se doreste transmiterea informatiilor solicitate.
			</p>

			<p>
				In vederea exercitarii de catre persoana vizata a drepturilor mentionate anterior, precum si in scopul solutionarii cu celeritate a acestor solicitari de catreoperatorul de distributie, oricare dintre persoanele vizate poate utiliza:
			</p>

			<ul>
				<li>
					Formularul pentru exercitarea dreptului de acces la datele cu caracter personal;
				</li>
				<li>
					Formularul pentru exercitarea dreptului de rectificare a datelor cu caracter personal;
				</li>
				<li>
					Formularul pentru exercitarea dreptului de stergere a datelor cu caracter personal;
				</li>
				<li>
					Formularul pentru exercitarea dreptului de restrictionare a datelor cu caracter personal;
				</li>
				<li>
					Formularul pentru exercitarea dreptului la portabilitatea datelor cu caracter personal;
				</li>
				<li>
					Formularul pentru exercitarea dreptului la opozitie;
				</li>
				<li>
					Formularul pentru exercitarea drepturilor in legatura cu un eventual proces decizional individual automatizat.
				</li>
			</ul>

			<p>
				Formularele de exercitare a drepturilor mentionate anterior au caracter pur orientativ, utilizarea acestora ramanand la latitudinea fiecarei persoane vizate in parte. Formularele sunt disponibile (i) fie pe adresa <a href="http://www.e-distributie.com/">www.e-distributie.com </a>&ndash; sectiunea Clienti &ndash; sub-sectiunea Date Personale &ndash; Vreau sa imi exercit drepturile, (ii) fie la serviciul de registratura de la sediul operatorului de distributie.
			</p>
		</div>

		<p>
			In cazul in care doriti sa va exercitati aceste drepturi, puteti inainta o cerere scrisa, datata si semnata
		</p>

		<ul style="list-style-type:disc">
			<li>
				La urmatoare adresa de e-mail : <a href="mailto:dpo.e-distributie@enel.com">dpo.e-distributie@enel.com</a><strong>;</strong>
			</li>
			<li>
				Expediand-o prin posta la adresa de corespondenta: <strong>E-Distributie Muntenia SA </strong>(pentru activitatea desfasurata in Bucuresti si Judetele Ilfov si Giurgiu) - Bucuresti, Bv. Mircea Voda nr. 30, sector 3;
			</li>
			<li>
				Prin accesarea website-ului <a href="http://www.e-distributie.com/">www.e-distributie.com </a>&ndash; sectiunea Clienti, accesand contul de client creat, in mod facultativ/optional de dumneavoastra pe acest website. Mentionam ca activarea/crearea acestui cont de client ramane la latitudinea dumneavoastra.
			</li>
		</ul>

		<p>
			Totodata, in cazul in care aveti nevoie de informatii suplimentare referitoare la continutul prezentului document, puteti solicita (i) sprijinul Responsabilului cu protectia datelor cu caracter personal, respectiv la adresa de e-mail: <a href="mailto:dpo.e-distributie@enel.com">dpo.e-distributie@enel.com </a>sau (ii) accesand website-ul <a href="http://www.e-distributie.com/">www.e-distributie.com </a>&ndash; sectiunea Clienti &ndash; subsectiunea Date personale &ndash; Trebuie sa cunosti.
		</p>
		<script type="text/php">
			if ( isset($pdf) ) { 
			    $pdf->page_script('
			        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
			        $page_height = $pdf->get_height();
			        $page_width = $pdf->get_width();

			        $size = 7;
			        // footer
			        if ($PAGE_COUNT > 1) {
			            $text = "Pagina ".$PAGE_NUM. " din ".$PAGE_COUNT;
		            	$width = $fontMetrics->get_text_width($text, $font, $size);
			        	$x = $page_width - ($width + 30);
				        $y = $page_height - 35;
			            $pdf->text($x, $y, $text, $font, $size);
			        }
			        $size = 9;
		            $text = $PAGE_NUM >= 4 ? "UZ PUBLIC" : "UZ PUBLIC, devine UZ CONFIDENTIAL dupa completare";
		            $width = $fontMetrics->get_text_width($text, $font, $size);
				    $x = $page_width - ($width + 30);
			        $y = $page_height - 45;
		            $pdf->text($x, $y, $text, $font, $size);

		            if ($PAGE_NUM > 3) {
		            	// header
			            $image = "'.route('images', 'e-distributie.png').'";
		            	$width = 150;
	                    $height = 35;
	                    $x = 35;
	                    $y = 45;
	                    $pdf->image($image, $x, $y, $width, $height);

	                    // footer
	                    $size = 5;
	                    $text = "E-Distribuție Muntenia S.A. – București, sector 3, bd. Mircea Voda nr. 30";
				        $y = $page_height - 45;
			            $pdf->text($x, $y, $text, $font, $size);

			            $text = "Nr. de ordine in Registrul Comertului J40/1859/2002, Cod Unic de înregistrare 14507322";
				        $y = $page_height - 37.5;
			            $pdf->text($x, $y, $text, $font, $size);

			            $text = "Capital social subscris şi vărsat 271.365.250 lei";
			            $y = $page_height - 30;
			            $pdf->text($x, $y, $text, $font, $size);

			            $text = "www.e-distributie.com";
			            $y = $page_height - 22.5;
			            $pdf->text($x, $y, $text, $font, $size);
			        }
			    ');
			}
		</script>
	</body>
</html>