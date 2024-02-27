<?php if( isset( $breadcrumbs ) ): ?>
    <div class="pam-postcontent pam-postcontent-0 clearfix">
        <div class="pam-content-layout-wrapper layout-item-0">
            <div class="pam-content-layout layout-item-1">
                <div class="pam-content-layout-row">
                    <div class="pam-layout-cell layout-item-2" style="width: 100%" >
                        <div class="paginare">
                            <?= display_breadcrumbs($breadcrumbs) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endif ?>

<div class="pam-postcontent pam-postcontent-0 clearfix">
    <div class="pam-content-layout-wrapper layout-item-0">
        <div class="pam-content-layout layout-item-1">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell layout-item-2" style="width: 100%" >

                    <div class="login-signup">

                        <h2>Profilul meu</h2>
                        
                        <?php if( isset( $succes ) ): ?>
                            <p class="alert alert-success">
                                <?=$succes?>
                            </p><br clear="all" />
                        <? endif ?>

                        <?php if( isset( $error ) ):?>
                            <div class="alert alert-error"><?=$error?></div><br clear="all" />
                        <? endif ?>
                        
                        <p>Editare informatii de profil</p>
                        <div class="login-form">
                            <form method="post" action="<?=  base_url()?>profilul_meu/salveaza">

                                <div class="one-col">
                                    <label> <label>Tip cont:</label></label>
                                    <?=form_dropdown('tip', $options_tip, (isset( $_POST['tip'] ) ? $_POST['tip'] : (isset( $item['tip'] ) ? $item['tip'] : "")))?>
                                </div>     
                                
                                <div id="inputs_firma" style="display: <?=(isset( $_POST['tip'] ) && $_POST['tip']==2) || (isset( $item['tip'] ) && $item['tip']=="2" ) ? "block" : "none"?>">
                                    <div class="two-col">
                                        <label>Firma</label>
                                        <input type="text" name="nume_firma" value="<?=isset( $_POST['nume_firma'] ) ? set_value('nume_firma') : (isset( $item['nume_firma'] ) ? $item['nume_firma'] : "")?>" />
                                    </div>

                                    <div class="two-col">
                                        <label>CUI</label> 
                                        <input type="text" name="cui" value="<?=isset( $_POST['cui'] ) ? set_value('cui') : (isset( $item['cui'] ) ? $item['cui'] : "")?>" />
                                    </div>

                                    <div class="two-col">
                                        <label>Nr.reg.com.</label> 
                                        <input type="text" name="nr_reg_comert" value="<?=isset( $_POST['nr_reg_comert'] ) ? set_value('nr_reg_comert') : (isset( $item['nr_reg_comert'] ) ? $item['nr_reg_comert'] : "")?>"  />
                                    </div>
                                    
                                    <div class="two-col">
                                        <label>Autorizatie IGPR (optional)</label> 
                                        <input type="text" name="autorizatie_igpr" value="<?=isset( $_POST['autorizatie_igpr'] ) ? set_value('autorizatie_igpr') : (isset( $item['autorizatie_igpr'] ) ? $item['autorizatie_igpr'] : "")?>" />
                                    </div>
                                </div>

                                <div class="two-col">
                                    <label>Nume</label>
                                    <input name="nume" value="<?=isset( $_POST['nume'] ) ? set_value('nume') : (isset( $item['nume'] ) ? $item['nume'] : "")?>" type="text">
                                </div>

                                <div class="two-col">
                                    <label>Prenume</label>
                                    <input name="prenume" value="<?=isset( $_POST['prenume'] ) ? set_value('prenume') : (isset( $item['prenume'] ) ? $item['prenume'] : "")?>" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="two-col">
                                    <label>CNP</label>
                                    <input name="cnp" value="<?=isset( $_POST['cnp'] ) ? set_value('cnp') : (isset( $item['cnp'] ) ? $item['cnp'] : "")?>" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="two-col">
                                    <label>Telefon</label>
                                    <input name="telefon" value="<?=isset( $_POST['telefon'] ) ? set_value('telefon') : (isset( $item['telefon'] ) ? $item['telefon'] : "")?>" type="text">
                                </div>
                                
                                <br clear="all" />

                                <div class="two-col">
                                    <label>Judet</label>
                                    <?=form_dropdown('id_judet', $options_judete, (isset( $_POST['id_judet'] ) ? $_POST['id_judet'] : (isset( $item['id_judet'] ) ? $item['id_judet'] : "")), "onchange='return updateSelectSrc(this.value)'")?>
                                </div>

                                <div class="two-col">
                                    <label>Oras</label>
                                    <?=form_dropdown('id_localitate', $options_localitati, (isset( $_POST['id_localitate'] ) ? $_POST['id_localitate'] : (isset( $item['id_localitate'] ) ? $item['id_localitate'] : "")), "id='select_src'")?> 
                                </div>

                                <div class="two-col">
                                    <label>Adresa</label>
                                    <input name="adresa" value="<?=isset( $_POST['adresa'] ) ? set_value('adresa') : (isset( $item['adresa'] ) ? $item['adresa'] : "")?>" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="one-col">
                                    <label> <input type="checkbox" value="1" name="newsletter" <?=isset( $_POST['newsletter'] ) ? "CHECKED" : ( $item['newsletter']==1 ? "CHECKED" : "" )?> > Ma abonez la newsletter.</label>
                                </div>

                                <div class="one-col sep">
                                    <h4>Adresa de livrare</h4>
                                </div>

                                <div class="one-col">
                                    <label> 
                                        <input type="checkbox" value="1" name="livrare_adresa_1"  <?=isset( $_POST['livrare_adresa_1'] ) ? "CHECKED" : (!isset( $_POST ) || empty( $_POST ) && $item['livrare_adresa_1']==1 ? "CHECKED" : "")?> />
                                        Adresa de livrarea difera de adresa de contact
                                    </label>
                                </div>
                                
                                <div class="adresa_2" style="display: <?= isset($_POST['livrare_adresa_1']) ? "block" : ( isset( $item['livrare_adresa_1'] ) && $item['livrare_adresa_1']==1 ? "block" : "none" ) ?>">
                                    <div class="two-col">
                                        <label>Judet</label>
                                        <?=form_dropdown('livrare_id_judet', $options_judete, (isset( $_POST['livrare_id_judet'] ) ? $_POST['livrare_id_judet'] : (isset( $item['livrare_id_judet'] ) ? $item['livrare_id_judet'] : "")), "onchange='return updateSelectSrc(this.value, \"select_src_2\")'")?>
                                    </div>

                                    <div class="two-col">
                                        <label>Oras</label>
                                        <?=form_dropdown('livrare_id_localitate', $options_localitati_livrare, (isset( $_POST['livrare_id_localitate'] ) ? $_POST['livrare_id_localitate'] :  (isset( $item['livrare_id_localitate'] ) ? $item['livrare_id_localitate'] : "")), "id='select_src_2'")?>  
                                    </div>


                                    <div class="two-col">
                                        <label>Adresa</label>
                                        <input name="livrare_adresa" value="<?=isset( $_POST['livrare_adresa'] ) ? set_value('livrare_adresa') : (isset( $item['livrare_adresa'] ) ? $item['livrare_adresa'] : "")?>" type="text" id="inputEmail" class="input-medium">
                                    </div>
                                </div>


                                <div class="one-col">
                                    <input type="button" onclick="this.form.submit()" class="salveaza-informatii" value="Salveaza informatiile">
                                </div>

                            </form>

                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
