<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>
        
<article class="module width_full">
    <header>
        <h3 class="tabs_involved">Selecteaza intervalul raportului</h3>
    </header>
    
    <div class="tab_container">
        <form action="<?= base_url() ?>admin/comenzi_raport/genereaza">
            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            <fieldset style="padding: 1.8%">
                                <label>Data start</label>
                                <input type="text" name="data_start" value="<?= $this->input->get("data_start") ?>" id="data_start" gldp-id="data_start" style="width: 92%" />
                                <div gldp-el="data_start"
                                     style="width:400px; height:300px; position:absolute; top:70px; left:100px;">
                                </div>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding: 1.8%">
                                <label>Data sfarsit</label>
                                <input type="text" name="data_sfarsit" value="<?= $this->input->get("data_sfarsit") ?>" id="data_sfarsit" gldp-id="data_sfarsit" style="width: 92%" />
                                <div gldp-el="data_sfarsit"
                                     style="width:400px; height:300px; position:absolute; top:70px; left:100px;">
                                </div>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding: 1.8%">
                                <label>Client</label>
                                <?=form_dropdown('id_user', $options_useri, $this->input->get('id_user'))?>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right">
                            <input type="submit" value="Genereaza raport" class="alt_btn" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        
    </div><!-- end of .tab_container -->
</article>
<?php if( isset( $tabelDate ) ): ?>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Comenzi</h3>
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
