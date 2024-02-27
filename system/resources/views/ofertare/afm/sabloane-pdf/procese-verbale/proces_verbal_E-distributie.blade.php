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
            header {
            	position:fixed;
            	top: -30px;
            	width: 100%;
            }
            header * {
				font-size: 12px;
            }
            header ~ * {
/*            	margin-top: 15px;*/
            }
            body {
                margin-top: 60px;
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
            .section-title {
                font-size: 13px;
                font-weight: 700;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
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
        	<div class="text-center" style="color: gray;">INTERNAL</div><br>
        	<div class="text-right" style="color: gray;">
	        	<strong>UZ PUBLIC, devine UZ CONFIDENTIAL dupa completare</strong><br>
	        	<em style="background-color: lightyellow; font-size: 10px;">*toate campurile sunt obligatorii in vederea completarii - necompletarea tuturor campurilor conduce la anularea valabilitatii documentului*</em>
			</div>
		</header>

		<div>
			<div class="left" style="position: fixed; top: -25px;">
	        	<img width="150px" src="{{ route('images', 'e-distributie.png') }}">
			</div>
			<div class="clear"></div>
			
			<p class="text-center">
				<strong>Proces verbal de punere in functiune finala</strong> a sistemului de producere a energiei electrice din<br>
				Centrala fotovoltaica CEF {{ $putere_invertor }} kW <em>(capacitatea sistemului fotovoltaic)</em>
			</p>

			<br>

			<p>Denumire instalator <strong>{{ $fi ? $fi->nume_firma : '...................' }}, 0747773031, office@genway.ro</strong></p>
			<p>Denumire beneficiar {{ $nume }} {{ $prenume }}</p>

			<p>Loc de consum unde a fost instalata centrala fotovoltaica <strong>{{ $nume_judet_imobil }}, <br>municipiul/oraşul/comuna/satul/sectorul {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong></p>

			<p>POD <strong>{{ $pod ?? '........' }}</strong></p>

			<p>Tip cerere - AFM / NON AFM / Electric UP <strong>{{ $nr_cerere_pif ?? '........' }}/{{ date('d.m.Y') }}</strong></p>

			<p>Echipamente centrala fotovoltaica:</p>

			<ul class="list">
				<li>
					<strong>Model invertor utilizat si acreditat de catre E-Distributie</strong> - <a href="https://www.e-distributie.com/ro/clientii-nostri/prosumatori/cum-devii-prosumator.html">https://www.e-distributie.com/ro/clientii-nostri/prosumatori/cum-devii-prosumator.html</a> &rarr; Lista invertoarelor declarate conforme. <em style="font-size:12px">Utilizare unui invertor neacreditat in afara listei de invertoare acreditate de catre Operatorul de Distributie conduce la anularea procesului verbal de punere sub tensiune finala pana la acreditarea acestuia de catre Operatorul de Distributie.</em> <strong>{{ $marca_invertor }}, {{ $putere_invertor }} kW</strong>
				</li>
				<li>
					Nr. panouri fotovoltaice: <strong>{{ $numar_panouri }} x {{ $putere_panouri }}</strong> W
				</li>
				<li>
					Sistem de stocare NU / DA ( se va mentiona capacitatea sistemului de stocare  Ah si numarul bateriilor / modulelor utilizate ) ( in caz contrar, se va mentiona ca nu s-au montat module de stocare a energiei electrice din CEF )
					<hr>
				</li>
				<li>
					Existenta blocului de masura si utilizare sigilabil si dimensiont conform puterii instalate in centrala fotovoltaica &ndash; <strong>dintre invertor si tabloul electric al clientului</strong> in conformitate cu ORD ANRE 15/2022; ORD ANRE 19/2022 actualizat prin ORD ANRE 133/2022 ( masura directa / semidirecta cu TC uri) &ndash; se va mentiona tipul BMP-ului utilizat si protectia aferente acestatuia (<em style="font-size:12px">ex: BMPT 32A intre invertor si TEG client cu intrerupator de sarcina 32A</em>)
					<hr>
				</li>
				<li>
					Existenta protectiei suplimentare privind montarea releului de antiinsularizare in cadrul centralei fotovoltaice ( DA – se va mentiona modelul de releul si tipul acestuia / NU )
					<hr>
				</li>
			</ul>

			<ol style="list-style-type: upper-roman;">
				<li>
					<p class="section-title">Date generale </p>

					Comisia de receptie convocata la data de <strong>{{ date('d/m/Y') }}</strong>

				<li><p class="section-title">Constatari: <em style="font-weight: 400;">Atestam ca aceasta lucrare corespunde din punct de vedere tehnic si electric, cu respectarea cerintelor din Ordinul ANRE 132/2020, 228/2018,19/2022 conditiilor tehnice in vigoare care permit punerea sub tensine, inclusiv a celor de protectie a muncii si PSI, normelor, prescriptiilor si normativelor invigoare, respectiv a normativului I7 ANRE in vigoare cu actualizarile aferente si se poate pune sub tensiune in perioada de proba finala.</em></p>

					<ol class="sublist">
						<li>
							In urma examinarii documentatiei prezentate, a rezultatelor probelor tehnologice si a cercetarii pe teren a lucrarilor executate s-a constatat: respectarea ORD ANRE 15/2022 // ORD ANRE 19/2022 //  ORD ANRE 133/2022  // ORD ANRE 132/2020 . INSTALATIA FOTOVOLTAICA POATE FI PUSA SUB TENSIUNE DE CATRE OPERATORUL DE DISTRIBUTIE / INSTALATORUL SISTEMULUI FOTOVOLTAIC SI RESPECTA NORMATIVUL I7 ( inclusiv pozarea conductoarelor aferente centralei Fotovoltaice in copex cu rezistenta la UV / cu manta metalica si respectarea distantei normate dintre utilizati (gaze sau apa) )
						</li>

						<li>
							In perioada <strong>{{ date('d/m/Y') }}</strong> au fost efectuate probele si s-au verificat conditiile tehnice de racordare la rețelele electrice de interes public pentru prosumatorul cu injecție de putere activă în rețea, mentionat mai sus, in conformitate cu ordinul Autorității Naționale de Reglementare în Domeniul Energiei nr. 228/2018 cu modificarile si completarile ulterioare si s-au constatat urmatoarele:

							<ul class="list">
								<li><em>reconectarea instalațiilor de producere a energiei electrice aparținând prosumatorului, la rețeaua electrică s-a realizat după un interval de 15 minute de la reapariția tensiunii în rețea.</em></li>
								<li><em>s-a verificat nefuncționarea centralei în regim insularizat, aceasta realizandu-se prin dotarea cu protecții care să întrerupă injecția puterii active în rețea a prosumatorului la apariția unui asemenea regim;</em></li>
								<li><em>s-a realizat protecția instalației de producere a energiei electrice, a invertoarelor componente și a instalațiilor auxiliare, împotriva defectelor din instalațiile proprii sau împotriva impactului rețelei electrice asupra acestora, la acționarea protecțiilor de declanșare a prosumatorului ori la incidente în rețea (supratensiuni tranzitorii, acționări ale protecțiilor în rețea, scurtcircuite cu și fără punere la pământ), cât și în cazul apariției unor condiții tehnice excepționale/anormale de funcționare; </em></li>
								<li><em>prosumatorul cu injecție de putere activă în rețea asigura în punctul de racordare/delimitare, după caz, calitatea energiei electrice în conformitate cu standardele în vigoare;</em></li>
								<li><em>se respecta Standardul de Performanta privind evacuarea surplusului de energie electrica in reteaua electrica de JT /MT aferenta Operatorului de Distributie.</em></li>
								<li><em>se respecta ORD ANRE 132/2020 privind  implementare reglajelor de interfata asupra grupului generator (fotovoltaic)</em></li>
								<li><em>se respecta ORD ANRE 15/2022 actualizat prin ORD ANRE 19/2022 privind montarea unui bloc de masura si protectie sigilabil in instalatia de utilizare dimensionat conform puterii instalate in centrala fotovoltaica <strong>racordat</strong> intre invertorul fotovoltaic si tabloul electric general al beneficiarului.</em></li>
								<li><em>se respecta ORD ANRE 132/2020 privind implementarea reglajelor de interfata asupra invertorului fotovoltaic, astfel incat, la debitarea surplusului de energie electrica in reteaua electrica a Operatorului de Distributie, nivelul tensiunii in punctul de delimitare se incadreaza in Standardul de Performanta.</em></li>
							</ul>


							<p>
								<strong>In conformitate cu cele mentionate anterior, declaram pe propria raspundere faptul ca Operatorul de Distributie poate monta in cadrul blocului de masura si protectie  montat in instalatia de utilizare, contorul de energie electrica ce va inregistra productia din centrala fotovoltaica.</strong>
							</p>
						</li>

						<li>
							<strong>Alte constatari: Consumatorul are obligatia sa exploateze si sa intretina instalatiile proprii in conformitate cu prescriptiile in vigoare, fiind răspunzător de orice modificare (neautorizată) ulteriora adusa instalatiei.</strong>
						</li>
					</ol>
				</li>

				<li><p class="section-title">Concluzii</p>

					<ol>
						<li>
							Pe baza constatarilor si concluziilor consemnate mai sus, comisia de receptie in unanimitate/cu majoritate de pareri hotaraste: ADMITEREA RECEPTIEI PUNERII IN FUNCTIUNE A CENTRALEI FOTOVOLTAICE: __________________________________________________________________<br>
							Centrala electrica fotovoltaica &ndash; de {{ $putere_invertor }} kW ( + sistem de stocare, daca exista montat ).
						</li>

						<li>
							Comisia de receptie stabileste ca, pentru o cat mai buna exploatare a capacitatii puse in functiune, mai sunt necesare urmatoarele masuri:
							<ul class="list">
								<li><em>Consumatorul are obligatia sa exploateze si sa intretina instalatiile proprii in conformitate cu prescriptiile in vigoare, fiind răspunzător de orice modificare (neautorizată) ulteriora adusa instalatiei;</em></li>
								<li><em>Aproba efectuarea punerii in functiune si rămânerea in exploatare a centralei Fotovoltaice  si admite ca, pentru buna functionare a instalatiei, mai sunt necesare urmatoarele masuri / modificari: intretinerea periodica a instalatiei prin curățarea panourilor fotovoltaice (beneficiar) si vizite periodice pentru inspectarea vizuală a instalației si efectuarea măsurătorilor tehnoloJice (executant);</em></li>
							</ul>
						</li>

						<li>Prezentul proces-verbal, contine 2 file care fac parte integranta din cuprinsul acestuia, a fost incheiat azi {{ date('d/m/Y') }} in doua exemplare originale.</li>

						<li>Se regasesc mai jos fotografiile de ansamblu aferente panourilor fotovoltaica cat si cele aferente de ansamblu invertorului din care reiese montarea blocului de masura in instalatia de utilizare dintre invertor si TEG client in care OD va monta contorul de energie electrica in vederea inregistrarii productiei din centrala fotovoltaica </li>

						<li>Schema electrica monofilara prezenta in cadrul documentatiei de prosumator releva situatia din teren in conformitate cu ORD ANRE 19 (art 19) pe intreg traseul de utilizare al beneficiarului ( intre BMPT OD – coloana de utilizare pana la panourile fotovoltaice – traseul DC).</li>
					</ol>
				</li>
			</ol>

			<p><strong>Comisia de analiza documente si receptie</strong>:</p>

			<p>
				Reprezentanti din partea Instalatorului/Executantului: {{ $fi ? $fi->nume_firma : '...................' }}
			</p>

			<img src="{{ $base64_stamp_img }}" style="width:150px; height:auto;">

			<p>&nbsp;</p>

			<p>Reprezentanti din partea beneficiar: {{ $nume }} {{ $prenume }}</p>


			<p>&nbsp;</p>

			{{-- <hr style="border: 1px solid black;"> --}}

			<p><strong>Observatii OD:</strong></p>
			<p><strong>Pentru a putea analiza conformitatea unui invertor sunt necesare urmatoarele documente:</strong></p>
			<ul class="list">
				<li>Certificatul de conformitate al invertorului (conform standard EN-50549-1);</li>
				<li>Lista modelelor de invertoare pentru care se doreste verificarea conformitatii;</li>
				<li>Un document tehnic (Test report - EN50549-1) din care sa reiasa testele ce au fost efectuate pe acest invertor si daca contine:
					<ul class="list" style="list-style: none outside disc!important;">
						<li>Detalii de parametrizare si setare protectii invertor - documentatia tehnica a invertorului din care sa putem identifica reglajele protectiilor ce pot fi setate pe invertor,  pentru a verifica daca corespunde cu legislatia in vigoare.</li>
						<li>Protectie antiinsularizare (prin protectii de tensiune/frecventa)</li>
						<li>Protectie antiinsularizare (prin viteza de variatie a frecventei - RoCoF/voltage shift)</li>
					</ul>
				</li>
			</ul>
		</div>
	</body>
</html>