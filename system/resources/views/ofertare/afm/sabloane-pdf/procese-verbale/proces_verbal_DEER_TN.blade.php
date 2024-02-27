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
            	width: 100%;
            	border: none;
            	border-bottom: 1px dotted black;
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
            .flex {
            	display: flex;
            }
            .inline-block {
            	display: inline-block;
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
		<div>
			<div>
				<span class="left inline-block">
					Nr. înregistrare OD .........................../...............<br>
					aferent cererii de punere sub tensiune nr. ...................................... {{ $pod }}
				</span>
	        	{{-- <img width="100px" class="right" src="{{ $base64_logo_img }}"> --}}
			</div>
			<div class="clear"></div>
			<br>
			
			<p class="text-center">
				<strong>
					PROCES VERBAL<br>
					de efectuare a probelor de punere în funcţiune a instalaţiei de utilizare şi producere energie <br>
					electrică, de tip FOTOVOLTAIC
				</strong>
			</p>

			<p class="text-center">
				<strong>
					prosumator {{ $nume }} {{ $prenume }} situată în {{ $nume_judet_imobil }}, {{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }} 
					conform prevederilor Ordinului ANRE nr. 19 din 02.03.2022
				</strong>
			</p>

			<br>

			<p>încheiat azi {{ date('d/m/Y') }} cu ocazia efectuării probelor de punere în funcţiune a instalatiei de utilizare/producere de tip <strong>FOTOVOLTAIC CEF {{ $nume }} {{ $prenume }}</strong></p>

			<ol>
				<strong><em>Datele tehnice ale instalaţiei de producere:</em></strong>
				<li>Seria invertorului este {{ $serie_invertor }} tipul invertorului este {{ $marca_invertor }}</li>
				<li>Montajul CEF {{ $nume }} {{ $prenume }} este în conformitate cu ATR .................................................</li>
				<li>Tipul/ seria contorului montat de prosumator pe partea de producere este {{ $invertor ? $invertor->contor : '...........' }} / {{ $serie_contor }}</li>
				<li>
					<span class="inline-block" style="width:10.5%;">Sistemul nu</span> <span class="inline-block">are □ sistem de stocare</span><br>
					<span class="inline-block" style="width:10.5%;">&nbsp;</span> <span class="inline-block">are □ şi anume: </span>
					<span class="inline-block">- puterea sistemului de stocare .......................................................</span>
					<span class="inline-block" style="width:25.5%;">&nbsp;</span> <span class="inline-block">- tipul acumulatorilor .......................................................................</span><br>
					<span class="inline-block" style="width:25.5%;">&nbsp;</span> <span class="inline-block">- seria contorului montat de OD pe sistemul de stocare este ............................</span>
				</li>
			</ol>

			<p><strong>In perioada ……………………………………………… au fost efectuate probele şi s-au verificat condiţiile tehnice de racordare la RED pentru prosumatorul cu injecţie de putere activă în reţea, în conformitate cu Ord. ANRE 228/2018 cu modificările şi completările ulterioare.</strong></p>
			<ol>
				S-au constatat următoarele:
				<li>Reconectarea instalaţiilor de producere a energiei electrice aparţinând prosumatorului, la reţeaua electrică s-a realizat după 15 minute de la reapariţia tensiunii în reţea;</li>
				<li>S-a verificat nefuncţionarea centralei în regim insularizat, aceasta realizându-se prin dotarea cu protecţii/protectii integrate a echipamentului care să întrerupă injecţia puterii active în reţea a prosumatorului la apariţia unui asemenea regim;</li>
				<li>S-a realizat protecţia instalaţiei de producere a energiei electrice, a invertorului/ invertoarelor componente şi a instalaţiilor auxiliare, a sistemului de stocare a energiei şi a instalaţiei electrice aferente locului de consum împotriva defectelor din instalaţiile proprii şi împotriva impactului reţelei electrice asupra acestora, la acţionarea protecţiilor de declanşare a prosumatorului ori la incidente în reţea (supratensiuni tranzitorii, acţionări ale protecţiilor în reţea, scurtcircuite cu şi fără punere la pământ), cât şi în cazul apariţiei unor condiţii tehnice excepţionale/ anormale de funcţionare;</li>
				<li>Prosumatorul cu injecţie de putere activă în reţea asigură în punctul de racordare / delimitare (după caz), calitatea energiei electrice în conformitate cu standardele în vigoare.</li>
			</ol>

			<p><strong>Subsemnatul POPA ALEXANDRU reprezentant al firmei executante {{ $fi ? $fi->nume_firma : 'S.C. ELECTRO SERVICE DISTRIBUTIE S.R.L.' }} (atestat ANRE tip Seria B, Nr {{ $fi && $fi->nr_autorizare ? $fi->nr_autorizare : '13692/28.09.2018' }} cu valabilitate pana la data de {{ $fi && $fi->data_valabilitate_autorizare ? $fi->data_valabilitate_autorizare : '28.09.2023' }}) declar că instalaţia de producere a fost realizată cu respectarea prevederilor Ord. ANRE 228/2018 şi 132/2020, cu modificările şi completările ulterioare.</strong></p>

			<p class="left">&nbsp;&nbsp;Prosumator,&nbsp;&nbsp;</p>
			<p class="right">Reprezentant firmă executantă care a efectuat probele,</p>
			<div class="clear"></div>

			<div style="page-break-after: always;"></div>

			<p><strong>Observaţii OD privind instalaţia de utilizare de la locul de consum şi producere</strong></p>
			<p>
				<span class="inline-block" style="width:10.5%;">OD a sigilat</span> <span class="inline-block">□ contorul pe partea de producere, din instalaţia prosumatorului</span><br>
				<span class="inline-block" style="width:10.5%;">&nbsp;</span> <span class="inline-block">□ contorul pe sistemul de stocare</span><br>
				<span class="inline-block" style="width:10.5%;">&nbsp;</span> <span class="inline-block">□ contorul in punctul de delimitare </span>
			</p>

			<p style="margin-right: 0;">
				Observaţii ( daca este cazul ) .........................................................................................................................................
				<hr>
				<hr>
				<hr>
				<hr>
				<hr>
				<hr>
			</p>

			<br>

			<div class="left">Data: {{ date('d/m/Y') }}</div>
			<div class="right text-center" style="width:30%;">
				Reprezentant OD,
				<hr class="inline">
			</div>
			<div class="clear"></div>

		</div>
	</body>
</html>