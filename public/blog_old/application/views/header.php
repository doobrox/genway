	<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head>
        <meta charset="utf-8">
        <title><?= $title ?></title>
        <meta name="description" content="<?= $meta_description ?>" />
        <meta name="keywords" content="<?= $meta_keywords ?>" />
        
        <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="stylesheet" href="<?= base_url() . MAINSITE_STYLE_PATH ?>css/style.css" media="screen">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

        <!--[if lte IE 7]><link rel="stylesheet" href="<?= base_url() . MAINSITE_STYLE_PATH ?>css/style.ie7.css" media="screen" /><![endif]-->
        <link rel="stylesheet" href="<?= base_url() . MAINSITE_STYLE_PATH ?>css/style.responsive.css" media="all">
        
        <link rel="stylesheet" href="<?= base_url() . MAINSITE_STYLE_PATH ?>css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="<?= base_url() . MAINSITE_STYLE_PATH ?>css/flexslider.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
		
		<link rel="shortcut icon" href="<?= base_url() . MAINSITE_STYLE_PATH ?>favicon.ico" />
        
        <script src="<?= base_url() . MAINSITE_STYLE_PATH ?>js/jquery.js"></script>
        <script src="<?= base_url() . MAINSITE_STYLE_PATH ?>js/script.js"></script>
        <script src="<?= base_url() . MAINSITE_STYLE_PATH ?>js/script.responsive.js"></script>
        <script src="<?= base_url() . MAINSITE_STYLE_PATH ?>js/jquery.prettyPhoto.js"></script>
        <script src="<?= base_url() . MAINSITE_STYLE_PATH ?>js/main.js"></script>

<style>
.ttitlu {
    color: #FFFFFF;
    font-size: 16px;
    font-family: 'Open Sans', sans-serif, Arial, Helvetica, Sans-Serif;
    font-weight: normal;
    font-style: normal;
    margin: 0 15px
}
.tt > h3:before{
	color:#FFF;
	margin: 0 10px;
    margin-right: 6px;
    bottom: 2px;
    position: relative;
    display: inline-block;
    vertical-align: middle;
    font-size: 0;
    line-height: 0;
}
</style>		
		
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		
    </head>
    <body>
        <div id="pam-main">
            <div class="pam-sheet clearfix">
                <header class="pam-header">

                    <div class="pam-shapes">

                        <div class="genway-logo">
                            <h1><a href="<?= base_url() ?>" title="<?= setare("TITLU_NUME_SITE") ?>"><?= setare("TITLU_NUME_SITE") ?></a></h1>
                        </div>

                       <!-- <div class="yahoo-status">
                            <a href="ymsgr:sendim?yahooid"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/online.gif" /></a>
                        </div>
						-->

                        <div class="numere-de-contact">
                            <div class="telefon"><a href="tel:+04<?= setare('TELEFON_CONTACT') ?>"><?= setare('TELEFON_CONTACT') ?></a></div>
                            <div class="telefon"><a href="tel:+04<?= setare('TELEFON_CONTACT_2') ?>"><?= setare('TELEFON_CONTACT_2') ?></a></div>
                            <div class="email"><a href="mailto:<?= setare('EMAIL_SUPORT_ONLINE') ?>"><?= setare('EMAIL_SUPORT_ONLINE') ?></a></div>
                            <!--<div class="yahoo-online"><a href="ymsgr:sendim?genway.romania">genway.romania (ON)</a></div>-->
							<br/>
							<div class="yahoo-online"><a href="ymsgr:sendim?genway.romania&m=Salut," border="0"><img src="http://opi.yahoo.com/online?u=genway.romania&t=2"><br/>genway.romania</a></div>
                        </div>
                        
                        <?php if( isset( $is_logat ) ): ?>
                            <div class="login-cont"> 
                                <div class="info-utilizator">Bine ai venit, <?= $nume_user ?>! <a href="<?= site_url('profilul_meu') ?>" class="edit">Editeaza cont</a><a href="<?=site_url('login/iesire')?>" class="logout" title="Logout">Logout</a></div>
                            </div>
                        <? else: ?>
                            <div class="login-cont"> 
                                <form action="<?= site_url('login/verificare') ?>" method="post">
                                    <input type="text" name="user_email_login" value="<?=isset( $_POST['user_email_login'] ) ? $_POST['user_email_login'] : ""?>" placeholder="E-mail">
                                    <input type="password" name="user_pass_login" value="<?=isset( $_POST['user_pass_login'] ) ? $_POST['user_pass_login'] : ""?>" placeholder="Parola">
                                    <input type="button" onClick="this.form.submit()">
                                </form>
                                <a href="<?= site_url('login/inregistrare') ?>" class="cont-nou">Inregistreaza un cont nou</a>
                            </div>
                        <? endif ?>

                    </div>

                </header>
                <nav class="pam-nav">
                    <ul class="pam-hmenu">
                        <li><a href="<?= base_url() ?>">Acasa</a>
                            <ul class="active">
                                <?php foreach( $pagini_footer as $pag ): ?>
                                    <li><a href="<?=$pag['furl']?>" title="<?=$pag['titlu']?>" <?=$pag['target']?>><?=$pag['titlu']?></a></li>
                                <? endforeach ?>
                            </ul>
                        </li>
                        <li><a href="<?= site_url('info/despre-noi') ?>">Despre noi</a></li>
                        <li><a href="<?= site_url('noutati') ?>">Noutati</a></li>
                        <li><a href="<?= site_url('promotii') ?>">Promotii</a></li>
						<li><a href="/info/instalari">Instalari</a>
							<ul class="linkulli active">
								<a class="linkli" href="/info/montare-interfoane">Montare interfoane</a>
								<!--<a class="linkli" href="/info/montare-camere-video">Montare camere video</a>-->
								<a class="linkli" href="/info/montare-alarme">Montare alarme</a>
								<a class="linkli" href="/info/montare-automatizari-porti">Montare automatizari porti</a>
								<a class="linkli" href="/info/montare-supraveghere-video">Montare supraveghere video</a>
							</ul>
						</li>
                        <li><a href="http://www.genway.ro/info/cariere">Cariere</a></li>
                        <li><a href="<?= site_url('contact') ?>">Contact</a></li>
                        <li style="float:right!important;">
                    </ul> 

                    <div class="cos-cumparaturi">
                        <?php if( $cart_nr_produse>0 ): ?>
                            <a href="<?= site_url('cos') ?>">Aveți <?=$cart_nr_produse?> <?=$cart_nr_produse==1 ? "produs" : "produse"?> în coș</a>
                        <? else: ?>
                            <a href="<?= site_url('cos') ?>">Nici un produs în coș</a>
                        <? endif ?>
                    </div>

                </nav>
                <div class="pam-layout-wrapper">
                    <div class="pam-content-layout">
                        <div class="pam-content-layout-row">
                            <div class="pam-layout-cell pam-sidebar1"><div class="pam-vmenublock clearfix">

<style>
.goog-te-gadget-simple { width:99%!important;height:25px }
.:2.container {display:none!important}
</style>							
<div class="pam-vmenublockheader">
	<h3 class="tt ttitlu"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;&nbsp;Alege limba</h3>
</div>
<div class="pam-vmenublockcontent" style="background:#FFF;margin-bottom:-18px">

<center><div id="google_translate_element"></div></center>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'ro', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<br/>
</div>

<div class="pam-vmenublockheader">
	<h3 class="tt ttitlu"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;Cauta</h3>
</div>
<div class="pam-vmenublockcontent" style="padding:3px;background:#FFF">

<form action="<?=site_url('produse')?>" class="quick_search">

	<input type="hidden" name="dupa" value="a.nume" />

	<input name="q" type="text" value="<?=isset( $_GET['q'] ) ? urldecode($_GET['q']) : "Caut&#259; un produs"?>" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;" style="width:76%;padding:3px;height:25px" placeholder="Caut&#259; un produs">

	<button style="background:linear-gradient(to right, #004C8E , #005FB4);border:0px;padding:7px;width:28px;"> <i class="fa fa-search" aria-hidden="true" style="color:#FFF"></i> </button>
</form>

</div>
							
                                    <?php if( isset( $is_logat ) ): ?>
                                        <div class="pam-vmenublockheader">
                                            <h3 class="tt ttitlu"><i class="fa fa-user-o" aria-hidden="true"></i>&nbsp;&nbsp;Contul meu</h3>
                                        </div>
                                        <div class="pam-vmenublockcontent">
                                            <ul class="pam-vmenu">
                                                <li><a href="<?=site_url('istoric_comenzi')?>" title="Istoric comenzi">Istoric comenzi</a></li>
                                                <li><a href="<?=site_url('profilul_meu')?>" title="Profilul meu">Profilul meu</a></li>
                                                <li><a href="<?=site_url('profilul_meu/schimba_parola')?>" title="Schimba parola">Schimba parola</a></li>
                                                <li><a href="<?=site_url('login/iesire')?>" title="Logout">Logout</a></li>
                                            </ul>
                                        </div>
                                    <? endif ?>
                                    
                                    <div class="pam-vmenublockheader">
                                        <h3 class="t">Produse</h3>
                                    </div>
                                    <div class="pam-vmenublockcontent">
                                        <ul class="pam-vmenu">
                                            <?php foreach( $categorii as $categorie ): ?>
                                                <li>
                                                    <a href="<?=$categorie['furl']?>" title="<?=$categorie['nume']?>" class="<?=$categorie['class']?>"><?=$categorie['nume']?></a>
                                                    <?=isset( $categorie['subcategorii'] ) ? $categorie['subcategorii'] : ""?>
                                                </li>
                                            <? endforeach ?>
                                        </ul>

                                    </div>


                                </div><div class="pam-block clearfix">

                                    <div class="pam-blockcontent"><h2 style="text-align: center;"><span style="font-size: 18px;">Descarca catalogul nostru de produse</span></h2><p style="text-align: center;"><br><a href="<?= setare('LINK_CATALOG_PRODUSE') ?>" target="_blank"><img width="128" height="128" alt="" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/catalogpdf-2.png"></a></div>

                                    <div class="pam-blockcontent">
                                        <div class="abonare-newsletter">
                                            <h2>Newsletter</h2>
                                            <p>Completeaza campurile de mai jos pentru a te inscrie in lista noastra de abonati.</p>
                                            <form method="post">
                                                <input name="nume_newsletter" type="text" value="Nume" onFocus="if (this.value == 'Nume') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Nume';}">
                                                <input name="email_newsletter" type="text" value="E-mail" onFocus="if (this.value == 'E-mail') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'E-mail';}">
                                                <input type="button" value="Trimite" id="abonare_newsletter">
                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <!--<div class="pam-block clearfix">
                                    <div class="pam-blockheader">
                                        <h2>Ultimele evenimente</h2>
                                    </div>
                                    <div class="pam-blockcontent">
                                        <div style="width:100%; float:left;">
                                            <div class="eveniment">
                                                <div class="img-eveniment">
                                                    <p align="center"><a href="#"><img width="105" height="105" alt="" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/catalog.jpg"></a></p>
                                                </div>
                                                <div class="eveniment-info">
                                                    <h2><a href="#">Lorem ipsum dolor</a></h2>
                                                    <p>Lorem ipsum dolor sit amet cons Lorem ipsum dolor sit amet cons ...</p>
                                                    <p><span>14 Martie 2014</span></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="width:100%; float:left;">
                                            <div class="eveniment">
                                                <div class="img-eveniment">
                                                    <p align="center"><a href="#"><img width="105" height="105" alt="" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/catalog.jpg"></a></p>
                                                </div>
                                                <div class="eveniment-info">
                                                    <h2><a href="#">Lorem ipsum dolor</a></h2>
                                                    <p>Lorem ipsum dolor sit amet cons Lorem ipsum dolor sit amet cons ...</p>
                                                    <p><span>14 Martie 2014</span></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>-->
                            </div>
                            <div class="pam-layout-cell pam-content">
                                <article class="pam-post pam-article">