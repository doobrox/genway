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
            .section-title {
                font-size: 13px;
                font-weight: 700;
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
        	<div class="text-center" style="color: gray;"><b>INTERNAL</b></div>
		</header>

		<div>
			<div>
				<span class="left inline-block">
					{{ $fi ? $fi->nume_firma : '...................' }}<br>
					Tel: 0747773031
				</span>
	        	<img width="100px" class="right" src="{{ $base64_logo_img }}">
			</div>
			<div class="clear"></div>
			<br>
			
			<p class="text-center">
				Proces verbal de punere in functiune finala a sistemului de producere a energiei electrice din<br>
				Centrala fotovoltaica CEF {{ $putere_invertor }} KW
			</p>

			<br>

			<p>Denumire beneficiar {{ $nume }} {{ $prenume }}</p>

			<p>Loc de consum unde a fost instalata centrala fotovoltaica <strong>{{ $nume_judet_imobil }}</strong>, <br>municipiul/oraşul/comuna/satul/sectorul <strong>{{ $nume_localitate_imobil }}</strong>, str. <strong>{{ $strada_imobil }}</strong>, nr. <strong>{{ $numar_imobil }}</strong></p>

			<p>POD <strong>{{ $pod }}</strong></p>

			<p>Tip cerere - AFM, <strong>{{ $nr_cerere_pif }}</strong></p>

			<p>Echipamente centrala fotovoltaica:</p>

			<ul class="list">
				<li>
					Model invertor utilizat {{ $marca_invertor }}
				</li>
				<li>
					Nr panouri fotovoltaice : {{ $numar_panouri }} x putere panouri {{ $putere_panouri }}W
				</li>
				<li>
					Sistem de stocare NU / DA ( se va mentiona capacitatea sistemului de stocare Ah si numarul bateriilor / modulelor utilizate ) ( in caz contrar, se va mentiona ca nu s-au montat module de stocare a energiei electrice din CEF ) 
					<hr>
				</li>
				<li>
					Existenta blocului de masura si utilizare sigilabil si dimensiont conform puterii instalate in centrala fotovoltaica &ndash; dintre invertor si tabloul electric al clientului ( masura directa / semidirecta cu TC uri) &ndash; se va mentionat tipul BMP ului utilizat si protectia aferente acestatuia (ex : BMPT 32A intre invertor si TEG client cu intrerupator de sarcina 32A) 
					<hr>
				</li>
			</ul>

			<ol style="list-style-type: upper-roman;">
				<li>
					<p class="section-title">Date generale </p>

					Comisia de receptie convocata la data de _______________________________________________
				</li>

				<li><p class="section-title">Constatari: </p>

					<ol class="sublist">
						<li>
							In urma examinarii documentatiei prezentate, a rezultatelor probelor tehnologice si a cercetarii pe teren a lucrarilor executate s-a constatat :&nbsp; respectarea ORD ANRE 15/2022 // ORD ANRE 19/2022 //&nbsp; ORD ANRE 133/2022&nbsp; // ORD ANRE 132/2020 . INSTALATIA FOTOVOLTAICA POATE FI PUSA SUB TENSIUNE DE CATRE OPERATORUL DE DISTRIBUTIE / INSTALATORUL SISTEMULUI FOTOVOLTAIC SI RESPECTA NORMATIVUL I7 ( inclusiv pozarea conductoarelor aferente centralei Fotovoltaice in copex cu manta metalica si respectarea distantei normate dintre utilizati ( gaze sau apa))
						</li>
						<li>
							Documentatia tehnico - economica prevazuta in conformitate cu Regulamentul de efectuare a obiectivelor de investitii a fost/nu a fost prezentata integral comisiei de receptie, lipsind:
						</li>

						<li>
							In perioada ___________________ au fost efectuate probele si s-au verificat conditiile tehnice de racordare la rețelele electrice de interes public pentru prosumatorul cu injecție de putere activă &icirc;n rețea, mentionat mai sus, in conformitate cu ordinul Autorității Naționale de Reglementare &icirc;n Domeniul Energiei nr. 228/2018 cu modificarile si completarile ulterioare si s-au constatat urmatoarele:

							<ul class="list">
								<li><em>reconectarea instalațiilor de producere a energiei electrice aparțin&acirc;nd prosumatorului, la rețeaua electrică s-a realizat după un interval de 15 minute de la reapariția tensiunii &icirc;n rețea. </em></li>
								<li><em>s-a verificat nefuncționarea centralei &icirc;n regim insularizat, aceasta realizandu-se prin dotarea cu protecții care să &icirc;ntrerupă injecția puterii active &icirc;n rețea a prosumatorului la apariția unui asemenea regim;</em></li>
								<li><em>s-a realizat protecția instalației de producere a energiei electrice, a invertoarelor componente și a instalațiilor auxiliare, &icirc;mpotriva defectelor din instalațiile proprii sau &icirc;mpotriva impactului rețelei electrice asupra acestora, la acționarea protecțiilor de declanșare a prosumatorului ori la incidente &icirc;n rețea (supratensiuni tranzitorii, acționări ale protecțiilor &icirc;n rețea, scurtcircuite cu și fără punere la păm&acirc;nt), c&acirc;t și &icirc;n cazul apariției unor condiții tehnice excepționale/anormale de funcționare;</em></li>
								<li><em>prosumatorul cu injecție de putere activă &icirc;n rețea asigura &icirc;n punctul de racordare/delimitare, după caz, calitatea energiei electrice &icirc;n conformitate cu standardele &icirc;n vigoare;</em></li>
								<li><em>se respecta Standardul de Performanta privind evacuarea surplusului de energie electrica in reteaua electrica de JT /MT aferenta Operatorului de Distributie . </em></li>
								<li><em>se respecta ORD ANRE 132/2020 privind&nbsp; implementare reglajelor de interfata asupra grupului generator ( fotovoltaic) </em></li>
								<li><em>se respecta ORD ANRE 15/2022 actualizat prin ORD ANRE 19/2022 privind montarea unui bloc de masura si protectie sigilabil in instalatia de utilizare dimensionat conform puterii instalate in centrala fotovoltaica <strong>racordat</strong> intre invertorul fotovoltaic si tabloul electric general al beneficiarului .</em></li>
							</ul>


							<p>
								In conformitate cu cele mentionate anterior, declaram pe propria raspundere faptul ca ca Operatorul de Distributie poate monta in cadrul blocului de masura si protectie din instalatia de utilizare, contorul de energie electrica ce va inregistra productia din centrala fotovoltaica.
							</p>
						</li>

						<li>
							La data receptiei, nivelul atins de indicatorii tehnico-economici aprobati este urmatorul: <strong>parametri nominali in furnizarea energiei electrice in punctul de delimitare ;</strong>
						</li>

						<li>
							Costul lucrarilor si al cheltuielilor pentru efectuarea probelor tehnologice, asa cum rezulta din documentele prezentate, este de ______________________________ lei.
						</li>

						<li>
							Valoarea produselor rezultate in urma probelor tehnologice, care se pot valorifica, este de ______________________________ lei.
						</li>

						<li>
							Valoarea de inregistrare a mijloacelor fixe ce se pun in functiune (sau se dau in folosinta) este la data receptiei de ______________________________ lei.
						</li>

						<li>
							Alte constatari 
							<hr>
							<hr>
							<hr>
						</li>
					</ol>
				</li>

				<li><p class="section-title">Concluzii</p>

					<ol>
						<li>
							Pe baza constatarilor si concluziilor consemnate mai sus, comisia de receptie in unanimitate/cu majoritate de pareri hotaraste: ADMITEREA RECEPTIEI PUNERII IN FUNCTIUNE A CENTRALEI FOTOVOLTAICE : __________________________________________________________________<br>
							Centrala electrica fotovoltaica &ndash; de {{ $putere_invertor }} kW.
						</li>

						<li>
							Comisia de receptie stabileste ca, pentru o cat mai buna exploatare a capacitatii puse in functiune, mai sunt necesare urmatoarele masuri: __________________________________________________________________________
							<hr>
							<hr>
						</li>

						<li>Prezentul proces-verbal, contine 2 file care fac parte integranta din cuprinsul acestuia, a fost incheiat azi _____________________________in trei exemplare originale.</li>
					</ol>
				</li>
			</ol>

			<p><strong>Comisia de analiza documente si receptie</strong>:</p>

			<p>
				Reprezentanti din partea Instalatorului/Executantului<br>
				{{ $fi ? $fi->nume_firma : '...................' }}<br>
				POPA ALEXANDRU
			</p>

			<img src="{{ $base64_stamp_img }}" style="width:150px; height:auto;">

			<p>&nbsp;</p>

			<p>Reprezentanti din partea beneficiar: {{ $nume }} {{ $prenume }}</p>

			<p>&nbsp;</p>

			<hr>

			<p>&nbsp;</p>

			<p>Reprezentanti din partea Operatorului de Distributie &ndash; E Distributie Muntenia</p>

			<p>&nbsp;</p>

			<hr style="border: 1px solid black;">

			<p>&nbsp;</p>

			<p>Observatii OD :</p>

		</div>
	</body>
</html>