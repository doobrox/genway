<!doctype html>

<html lang="en">



    <head>

        <meta charset="utf-8"/>
        
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />

        <title>Administrare</title>
        


        <link rel="stylesheet" href="<?= base_url() . ADMIN_STYLE_PATH ?>css/layout.css" type="text/css" media="screen" />

        <link rel="stylesheet" href="<?=base_url().ADMIN_STYLE_PATH?>css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />

        <link rel="stylesheet" href="<?=base_url().ADMIN_STYLE_PATH?>css/glDatePicker.flatwhite.css" type="text/css" media="screen" />

        <!--[if lt IE 9]>

        <link rel="stylesheet" href="<?= base_url() . ADMIN_STYLE_PATH ?>css/ie.css" type="text/css" media="screen" />

        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

        <![endif]-->

        <script src="<?= base_url() . ADMIN_STYLE_PATH ?>js/jquery-1.5.2.min.js" type="text/javascript"></script>

        <script src="<?= base_url() . ADMIN_STYLE_PATH ?>js/hideshow.js" type="text/javascript"></script>

        <script src="<?= base_url() . ADMIN_STYLE_PATH ?>js/jquery.tablesorter.min.js" type="text/javascript"></script>

        <script type="text/javascript" src="<?= base_url() . ADMIN_STYLE_PATH ?>js/jquery.equalHeight.js"></script>

        <script type="text/javascript" src="<?=base_url().ADMIN_STYLE_PATH?>js/jquery.prettyPhoto.js"></script>

        <script type="text/javascript" src="<?=base_url().ADMIN_STYLE_PATH?>js/glDatePicker.min.js"></script>

        <script type="text/javascript">

            $(document).ready(function() {

                // add parser through the tablesorter addParser method

                $.tablesorter.addParser({

                    id: 'custom_parser',

                    is: function(s) {

                        return false;

                    },

                    format: function(s, table, cell) {

                        return $('input', cell).val();

                    },

                    type: 'numeric'

                });

                

                <?php if( $page_view=="pagini" ): ?>

                    $(".tablesorter").tablesorter({

                        headers: {

                            3: {

                                sorter:'custom_parser'

                            }

                        }

                    }); 

                <? elseif( $page_view=="categorii" ): ?>

                    $(".tablesorter").tablesorter({

                        headers: {

                            2: {

                                sorter:'custom_parser'

                            }

                        }

                    }); 

                <? elseif( $page_view=="produse" ): ?>

                    $(".tablesorter").tablesorter({

                        headers: {

                            5: {

                                sorter:'custom_parser'

                            },

                            6: {

                                sorter:'custom_parser'

                            }

                        }

                    }); 

                <? else: ?>

                    $(".tablesorter").tablesorter(); 

                <? endif ?>

                //When page loads...

                $(".tab_content").hide(); //Hide all content

                $("ul.tabs li:first").addClass("active").show(); //Activate first tab

                $(".tab_content:first").show(); //Show first tab content



                //On Click Event

                $("ul.tabs li").click(function() {



                    $("ul.tabs li").removeClass("active"); //Remove any "active" class

                    $(this).addClass("active"); //Add "active" class to selected tab

                    $(".tab_content").hide(); //Hide all tab content



                    var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content

                    $(activeTab).fadeIn(); //Fade in the active ID content

                    return false;

                });

                

                $("select[name='tip']").change(function(){

                    var display = $(this).val()=="1" ? "none" : "block";

                    $("#inputs_firma").css("display", display);

                });

                

                $("a[rel^='prettyPhoto']").prettyPhoto({social_tools:false});

                

                $("#build_slug").keyup(function(){

                    $.get("<?=base_url()?>/admin/index_page/ajax_build_slug?q=" + encodeURI( $(this).val() ), function(data){

                        $("input[name='slug']").val( data );

                    });

                });

            });

            

            $(window).load(function() {

                $('#data_start, #data_sfarsit').glDatePicker({

                    cssName: 'flatwhite',

                    selectableYears: [<?=$selectableYears?>],

                    dowOffset: 1,

                    

                    onClick: function(target, cell, date, data) {

                        target.val( date.getFullYear() + '-' +

                                    ( date.getMonth()<9 ? "0" + (date.getMonth()+1) : (date.getMonth()+1)) + '-' +

                                    ( date.getDate()<10 ? "0" + date.getDate() : date.getDate())

                            );



                        if(data != null) {

                            alert(data.message + '\n' + date);

                        }

                    }

                });

            });

            

            function updateCheckAll() {

                var checked = $("#check_all").attr("checked");

                if( checked ) {

                    $("input[name='id[]']").attr("checked", true);

                } else {

                    $("input[name='id[]']:checked").attr("checked", false);

                }

            }

            

            function updateSelectSrc( val ) {

                $("#select_src").html("");

                $.ajax({

                    url: "<?=base_url()?>/admin/index_page/ajax_get_localitati/"+val,

                    success: function(msg){

                        var obj = jQuery.parseJSON( msg );

                        finalTxt = "";

                        

                        $.each(obj, function(i, tip){

                            finalTxt += "<option value='"+ tip.id +"'>"+ tip.nume +"</option>";

                        });

                        $("#select_src").html( finalTxt );

                    }

                });

            }

            

            function appendSelectProduseRecomandate() {

                $("#id_produse_recomandate").append( $("#select_prod_recomandate").html() );

                return false;

            }

        </script>

        <script type="text/javascript">

            $(function(){

                $('.column').equalHeight();

            });

        </script>



    </head>





    <body>



        <header id="header">

            <hgroup>

                <h1 class="site_title"><a href="<?= base_url() ?>admin">Panou de Administrare</a></h1>

                <h2 class="section_title"></h2><div class="btn_view_site"><a href="<?= base_url() ?>" target="_blank">Vezi site-ul</a></div>

            </hgroup>

        </header> <!-- end of header bar -->



        <section id="secondary_bar">

            <div class="user">

                <p>Administrator</p>

                <a class="logout_user" href="<?= base_url() ?>login/iesire" title="Iesire">Iesire</a>

            </div>

            <div class="breadcrumbs_container">

                <?php if( isset( $breadcrumbs ) ): ?>

                    <article class="breadcrumbs">

                        <a href="<?= base_url() ?>admin">Administrare</a> <div class="breadcrumb_divider"></div> 

                        <?=display_breadcrumbs($breadcrumbs)?>

                    </article>

                <? endif ?>

            </div>

        </section><!-- end of secondary bar -->



        <aside id="sidebar" class="column">

            <form action="<?=site_url('admin/produse')?>" class="quick_search">

                <input type="hidden" name="dupa" value="a.nume" />

                <input name="q" type="text" value="<?=isset( $_GET['q'] ) ? urldecode($_GET['q']) : "Caut&#259; un produs"?>" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">

            </form>

            <hr/>

            <h3>Produse</h3>

            <ul class="toggle">

                <li class="icn_new_article"><a href="<?=base_url()?>admin/produse/adauga">Adaug&#259; un nou produs</a></li>

                <li class="icn_edit_article"><a href="<?=base_url()?>admin/produse">Editeaz&#259; produse</a></li>

                <li class="icn_export"><a href="<?=base_url()?>admin/csv">Actualizeaz&#259; produse</a></li>

                <li class="icn_edit_article"><a href="<?=base_url()?>admin/produse_recomandate">Ordoneaz&#259; produse recomandate</a></li>

                <li class="icn_comentarii"><a href="<?=base_url()?>admin/produse_comentarii">Comentarii produse</a></li>

                <li class="icn_new_article"><a href="<?=base_url()?>admin/categorii/adauga">Adaug&#259; o categorie</a></li>

                <li class="icn_categories"><a href="<?=base_url()?>admin/categorii">Categorii</a></li>

                <li class="icn_filtre"><a href="<?=base_url()?>admin/filtre">Filtre</a></li>

                <li class="icn_add_filtru"><a href="<?=base_url()?>admin/filtre/adauga">Adaug&#259; un filtru</a></li>

            </ul>

            <h3>Producatori</h3>

            <ul class="toggle">

                <li class="icn_new_article"><a href="<?=base_url()?>admin/producatori/adauga">Adaug&#259; un nou produc&#259;tor</a></li>

                <li class="icn_edit_article"><a href="<?=base_url()?>admin/producatori">Editeaz&#259; produc&#259;tori</a></li>

            </ul>

            <h3>Pagini</h3>

            <ul class="toggle">

                <li class="icn_new_article"><a href="<?=base_url()?>admin/pagini/adauga">Adaug&#259; o nou&#259; pagin&#259;</a></li>

                <li class="icn_edit_article"><a href="<?=base_url()?>admin/pagini">Editeaz&#259; pagini</a></li>

            </ul>

            <h3>Bannere</h3>

            <ul class="toggle">

                <li class="icn_new_article"><a href="<?=base_url()?>admin/bannere/adauga">Adaug&#259; banner</a></li>

                <li class="icn_edit_article"><a href="<?=base_url()?>admin/bannere">Editeaz&#259; bannere</a></li>

            </ul>

            <h3>Comenzi / Clien&#355;i</h3>

            <ul class="toggle">

                <li class="icn_add_user"><a href="<?=base_url()?>admin/useri/adauga">Adaug&#259; un nou client</a></li>

                <!--<li class="icn_profile"><a href="#">Your Profile</a></li>-->

                <li class="icn_view_users"><a href="<?=base_url()?>admin/useri">Vezi clien&#355;i</a></li>

                <li class="icn_tags"><a href="<?=base_url()?>admin/comenzi">Comenzi</a></li>

                <li class="icn_report"><a href="<?=base_url()?>admin/comenzi_raport">Raport comenzi</a></li>

                <li class="icn_vouchere"><a href="<?=base_url()?>admin/vouchere">Vouchere</a></li>

                <li class="icn_voucher_add"><a href="<?=base_url()?>admin/vouchere/adauga">Adauga voucher</a></li>

            </ul>

            <h3>Newsletter & Abonati</h3>

            <ul class="toggle">

                <li class="icn_view_users"><a href="<?=base_url()?>admin/newsletter_abonati">Vezi abonati</a></li>

                <li class="icn_add_user"><a href="<?=base_url()?>admin/newsletter_abonati/adauga">Adaug&#259; un nou abonat</a></li>

                <li class="icn_send_newsletter"><a href="<?=base_url()?>admin/newsletter/adauga">Trimite un newsletter</a></li>

                <li class="icn_newsletters"><a href="<?=base_url()?>admin/newsletter">Vezi newslettere</a></li>

            </ul>

            <!--<h3>Media</h3>

            <ul class="toggle">

                    <li class="icn_folder"><a href="#">File Manager</a></li>

                    <li class="icn_photo"><a href="#">Gallery</a></li>

                    <li class="icn_audio"><a href="#">Audio</a></li>

                    <li class="icn_video"><a href="#">Video</a></li>

            </ul>-->

            <h3>Administrator</h3>

            <ul class="toggle">

                <li class="icn_curieri"><a href="<?=base_url()?>admin/curieri/adauga">Adaug&#259; un curier</a></li>

                <li class="icn_curieri"><a href="<?=base_url()?>admin/curieri">Curieri</a></li>
                
                <li class="icn_newsletters"><a href="<?=base_url()?>admin/email_templates">Template-uri email</a></li>
                
                <li class="icn_report"><a href="<?= base_url() ?>admin/catalog">Upload catalog produse</a></li>
                
                <li class="icn_settings"><a href="<?= base_url() ?>admin/setari">Set&#259;ri</a></li>

                <li class="icn_cache"><a href="<?= base_url() ?>admin/index_page/clear_cache" onclick="return confirm('Esti sigur ca vrei sa stergi cache-ul?')">Sterge cache-ul</a></li>

                <!--<li class="icn_security"><a href="#">Schimb&#259; parola</a></li>-->

                <li class="icn_jump_back"><a href="<?= base_url() ?>login/iesire">Ie&#351;ire</a></li>

            </ul>



            <footer>

                <hr />

                <p><strong>Copyright &copy; <?= date('Y') ?></strong></p>

                <p>Realizat de <a href="http://www.pamdesign.ro">PAM Design</a></p>

                <br />

                <br />

            </footer>

        </aside><!-- end of sidebar -->



        <section id="main" class="column">