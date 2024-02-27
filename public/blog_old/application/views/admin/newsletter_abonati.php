<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>
    
<?php if( isset( $tabelDate ) ): ?>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Export & import</h3>
        </header>

        <div class="tab_container" style="padding: 0 0 5px 5px;">
            <p>NOTA: Pentru import selectati un fisier TXT ce contine adresele de email pe fiecare rand, in formatul: "NUME|ADRESA DE EMAIL"</p>
            
            <form action="<?= base_url() ?>admin/newsletter_abonati/export_txt/" method="post" onsubmit="document.getElementById('info_export').style.display='block'" style="display: inline">
                <input type="submit" class="alt_btn" value="Exporta abonatii">
            </form>
            <form action="<?= base_url() ?>admin/newsletter_abonati/import_txt/" enctype="multipart/form-data" method="post" style="float: right; margin-right: 5px;">
                <input type="file" name="fisier_txt" />
                <input type="submit" value="Import abonati">
            </form>

            <div style="display: none" id="info_export">Exportul clientilor poate dura cateva secunde. Va rugam asteptati...</div>
        </div>

    </article>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Newsletter</h3>
        </header>

        <div class="tab_container">
                <div class="tab_content" id="tab1" >
                    <form action="" method="post">
                        <?=$tabelDate?>
                    </form>
                </div><!-- end of #tab1 -->
        </div><!-- end of .tab_container -->

    </article>
<? endif ?>
