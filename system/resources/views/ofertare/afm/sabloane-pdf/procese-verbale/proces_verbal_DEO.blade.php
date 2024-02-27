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
        	<img width="200px" style="max-width:60%;" src="{{ route('images', 'distributie_oltenia.png') }}">
        	<img width="100px" class="right" src="{{ $base64_logo_img }}">
        	<p style="color:red;margin-top:0;"><strong>Confidential (C3)</strong></p>
		</header>

		<div>

			<h1 class="titlu text-center">
				PROCES-VERBAL DE PUNERE IN FUNCTIUNE <br>
				a instalatiilor de producere a energiei electrice FOTOVOLTAICE<br>
				nr. {{ $nr_cerere_pif ?? '......' }} / {{ date('d.m.Y') }}
			</h1>

			<p><strong>Beneficiar {{ $nume }} {{ $prenume }} adresa implementare: {{ $nume_judet_imobil }}, <br>{{ $nume_localitate_imobil }}, str. {{ $strada_imobil }}, nr. {{ $numar_imobil }}</strong></p>

			<p><strong>LC {{ $pod }}</strong></p>

			<ol style="list-style-type: upper-roman;">
				<li>
					<p class="section-title">DATE GENERALE</p>
					<ol>
						<li>Comisia de receptie convocata la data de .......... si-a desfasurat activitatea in intervalul: ..........</li>
					</ol>
				</li>

				<li><p class="section-title">CONSTATARI: </p>

					<p>In urma examinarii documentatiei prezentate, a rezultatelor probelor tehnologice si a cercetarii pe teren a lucrarilor executate s-a constatat:</p>
					<ol>
						<li>
							Documentatia tehnico - economica prevazuta in conformitate cu Regulamentul de efectuare a obiectivelor de investitii a fost/nu a fost prezentata integral comisiei de receptie, lipsind: -<br>
							....................................................................................................................................
						</li>
						<li>
							Documentatia tehnico - economica prevazuta in conformitate cu Regulamentul de efectuare a obiectivelor de investitii a fost/nu a fost prezentata integral comisiei de receptie, lipsind:
						</li>

						<li>
							<strong>
								In perioada .......... – .......... au fost efectuate probele si s-au verificat conditiile tehnice de racordare la retelele electrice de interes public pentru prosumatorul cu injectie de putere activa in retea, mentionat mai sus, in conformitate cu ordinul Autoritatii Nationale de Reglementare in Domeniul Energiei nr. 228/2018 cu modificarile si completarile ulterioare si s-au constatat urmatoarele:

								<ul class="list">
									<li><em>reconectarea instalatiilor de producere a energiei electrice apartinând prosumatorului, la reteaua electrica s-a realizat dupa un interval de 15 minute de la reaparitia tensiunii in retea.</em></li>
									<li><em>s-a verificat nefunctionarea centralei in regim insularizat, aceasta realizandu-se prin dotarea cu protectii care sa intrerupa injectia puterii active in retea a prosumatorului la aparitia unui asemenea regim</em></li>
									<li><em>s-a realizat protectia instalatiei de producere a energiei electrice, a invertoarelor componente si a instalatiilor auxiliare, a sistemului de stocare a energiei si a instalatiei electrice aferente locului de consum impotriva defectelor din instalatiile proprii sau impotriva impactului retelei electrice asupra acestora, la actionarea protectiilor de declansare a prosumatorului ori la incidente in retea (supratensiuni tranzitorii, actionari ale protectiilor in retea, scurtcircuite cu si fara punere la pamânt), cât si in cazul aparitiei unor conditii tehnice exceptionale/anormale de functionare.</em></li>
									<li><em>prosumatorul cu injectie de putere activa in retea asigura in punctul de racordare/delimitare, dupa caz, calitatea energiei electrice in conformitate cu standardele in vigoare</em></li>
								</ul>
							</strong>
						</li>

						<li>
							La data receptiei, nivelul atins de indicatorii tehnico-economici aprobati este urmatorul: <strong>BINE</strong>
						</li>

						<li>
							Costul lucrarilor si al cheltuielilor pentru efectuarea probelor tehnologice, asa cum rezulta din documentele prezentate, este de 500 lei.
						</li>

						<li>
							Valoarea produselor rezultate in urma probelor tehnologice, care se pot valorifica, este de .......... lei.
						</li>

						<li>
							Valoarea de inregistrare a mijloacelor fixe ce se pun in functiune (sau se dau in folosinta) este la data receptiei de .......... lei.
						</li>

						<li>
							Alte constatari.
							<hr>
						</li>
					</ol>
				</li>

				<li><p class="section-title">CONCLUZII</p>

					<ol>
						<li>
							Pe baza constatarilor si concluziilor consemnate mai sus, comisia de receptie in unanimitate/cu majoritate de pareri hotaraste: ADMITEREA RECEPTIEI PUNERII IN FUNCTIUNE A CAPACITATII: <strong>{{ $max_putere }} kW</strong>.
						</li>

						<li>
							Comisia de receptie stabileste ca, pentru o cat mai buna exploatare a capacitatii puse in functiune, mai sunt necesare urmatoarele masuri: REVERIFICAREA PRIZEI DE PAMANT LA INTERVALUL DE UN AN; REVIZIA ECHIPAMENTELOR PENTRU MENTENANTA CEL PUTIN O DATA PE AN.
						</li>

						<li>Prezentul proces-verbal, care contine 1 file si 0 anexe, numerotate cu un total de 1 file, care fac parte integranta din cuprinsul acestuia, a fost incheiat azi {{ date('d/m/Y') }} in trei exemplare originale.</li>
					</ol>
				</li>
			</ol>

			<p><strong>Comisia de analiza documente si receptie</strong>:</p>

			<table style="border:none;">
				<tr>
					<td align="left" style="width:60%;">
						Reprezentanti din partea Instalatorului/Executantului<br>
						Nume/prenume, Functie POPA ALEXANDRU, ADMINISTRATOR<br>
						Nume/prenume, Functie .........................................<br>
						Nume/prenume, Functie .........................................
					</td>
					<td align="left">
	                    <img src="{{ $base64_stamp_img }}" style="width:150px; height: auto;">
					</td>
				</tr>
			</table>

			<p>
				Solicitant,<br>
				Nume/prenume {{ $nume }} {{ $prenume }}
			</p>
		</div>
		<script type="text/php">
			if ( isset($pdf) ) {
			    $pdf->page_script('
			        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
			        $size = 10;


			        if ($PAGE_COUNT > 1) {
			            $text = "Pagina ".$PAGE_NUM. "/".$PAGE_COUNT;
		            	$width = $fontMetrics->get_text_width($text, $font, $size) / 2;
			        	$x = ($pdf->get_width() - $width)/2;
				        $y = $pdf->get_height() - 55;
			            $pdf->text($x, $y, $text, $font, $size);
			        }
			        $size = 8;
		            $text = "01-03-01_P03-F23_PV PIF a instalatiei de producere a energiei electrice_prosumator_rev03";
				    $x = 30;
			        $y = $pdf->get_height() - 35;
		            $pdf->text($x, $y, $text, $font, $size);
			    ');
			}
		</script>
	</body>
</html>
