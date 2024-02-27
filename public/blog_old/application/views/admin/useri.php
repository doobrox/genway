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
            <h3 class="tabs_involved">Exporta clienti</h3>
        </header>
        <div class="tab_container" style="padding: 0 0 5px 5px;">
            <form action="<?= base_url() ?>admin/useri/export_xls/" method="post" onsubmit="document.getElementById('info_export').style.display='inline'">
                <input type="hidden" name="q" value="<?=isset($_GET['q']) ? $_GET['q'] : ""?>" />
                <input type="hidden" name="dupa" value="<?=isset($_GET['dupa']) ? $_GET['dupa'] : ""?>" />
                
                <input type="submit" name="export_toti" class="alt_btn" value="Exporta toti clientii">
                <input type="submit" name="export_doar_filtrati" value="Exporta doar clientii filtrati">
                <span style="display: none" id="info_export">Exportul clientilor poate dura cateva secunde. Va rugam asteptati...</span>
            </form>

        </div>
    </article>
    
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Cauta utilizatori</h3>
        </header>

        <div class="tab_container">
            <form action="<?= base_url() ?>admin/useri/">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <fieldset>
                                    <label>Cauta</label>
                                    <input type="text" name="q" value="<?=isset($_GET['q']) ? $_GET['q'] : ""?>" style="width: 94%" />
                                </fieldset>
                            </td>
                            <td>
                                <fieldset style="padding: 1.8%">
                                    <label>dup&#259;</label>
                                    <?=form_dropdown("dupa", $select_dupa, isset($_GET['dupa']) ? $_GET['dupa'] : "" )?>
                                </fieldset>
                            </td>
                            <td>
                                <input type="submit" value="Cauta" class="alt_btn" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div><!-- end of .tab_container -->

    </article>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Utilizatori</h3>
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