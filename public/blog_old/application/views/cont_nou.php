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

                        <h2>Inregistreaza un cont nou</h2>
                        <p>Pentru a intra in cont introduceti informatiile pe care le-ati folosit la inregistrare</p>
                        
                        
                        <?php if( isset( $succes ) ): ?>
                            <p class="alert alert-success">
                                <?=$succes?>
                            </p>
                        <? endif ?>

                        <?php if( validation_errors()!=""):?>
                            <div class="alert alert-error"><?=validation_errors()?></div>
                        <? endif ?>
                        
                        <div class="login-form">
                            <form method="post" action="<?= base_url()?>login/cont_nou?link=<?=isset( $link ) ? $link : ""?>">

                                <div class="one-col">
                                    <label> <label>Tip cont:</label></label>
                                    <?=form_dropdown('tip', $options_tip, (isset( $_POST['tip'] ) ? $_POST['tip'] : ""))?>
                                </div>     
                                
                                <div id="inputs_firma" style="display: <?=isset( $_POST['tip'] ) && $_POST['tip']==2 ? "block" : "none"?>">
                                    <div class="two-col">
                                        <label>Firma</label>
                                        <input type="text" name="nume_firma" value="<?=set_value('nume_firma')?>" />
                                    </div>

                                    <div class="two-col">
                                        <label>CUI</label> 
                                        <input type="text" name="cui" value="<?=set_value('cui')?>" />
                                    </div>

                                    <div class="two-col">
                                        <label>Nr.reg.com.</label> 
                                        <input type="text" name="nr_reg_comert" value="<?=set_value('nr_reg_comert')?>"  />
                                    </div>

                                    <div class="two-col">
                                        <label>Autorizatie IGPR (optional)</label> 
                                        <input type="text" name="autorizatie_igpr" value="<?=set_value('autorizatie_igpr')?>" />
                                    </div>
                                    
                                </div>

                                <div class="two-col">
                                    <label>Nume</label>
                                    <input name="nume" value="<?=set_value('nume')?>" type="text">
                                </div>

                                <div class="two-col">
                                    <label>Prenume</label>
                                    <input name="prenume" value="<?=set_value('prenume')?>" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="two-col">
                                    <label>CNP</label>
                                    <input name="cnp" value="<?=set_value('cnp')?>" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="two-col">
                                    <label>Telefon</label>
                                    <input name="telefon" value="<?=set_value('telefon')?>" type="text">
                                </div>
                                
                                <div class="two-col">
                                    <label>Email</label>
                                    <input type="text" name="user_email" value="<?=set_value('user_email')?>" id="inputEmail" class="input-medium">
                                </div>


                                <div class="two-col">
                                    <label>Parola</label>
                                    <input name="user_pass" value="<?=set_value('user_pass')?>" type="password" id="inputPassword" class="input-medium">
                                </div>

                                <div class="two-col">
                                    <label>Repeta parola</label>
                                    <input name="user_pass_check" value="<?=set_value('user_pass_check')?>" type="password" id="inputPassword" class="input-medium">
                                </div>

                                <div class="two-col">
                                    <label>Judet</label>
                                    <?=form_dropdown('id_judet', $options_judete, (isset( $_POST['id_judet'] ) ? $_POST['id_judet'] : ""), "onchange='return updateSelectSrc(this.value)'")?>
                                </div>

                                <div class="two-col">
                                    <label>Oras</label>
                                    <?=form_dropdown('id_localitate', $options_localitati, (isset( $_POST['id_localitate'] ) ? $_POST['id_localitate'] : ""), "id='select_src'")?> 
                                </div>

                                <div class="two-col">
                                    <label>Adresa</label>
                                    <input name="adresa" value="<?=set_value('adresa')?>" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="one-col">
                                    <label> <input type="checkbox" value="1" name="newsletter" <?= ( !isset( $_POST['newsletter'] ) || ( isset( $_POST['newsletter'] ) && $_POST['newsletter']=="1" ) ) ? "CHECKED" : ""?> > Ma abonez la newsletter.</label>
                                    <label> <input type="checkbox" value="1" name="reseller_cerere" <?= isset( $_POST['reseller_cerere'] ) && $_POST['reseller_cerere']=="1" ? "CHECKED" : ""?> >Doresc sa devin reseller. <a href="<?=site_url('info/avantaje-reseller')?>" target="_blank">Afla avantajele</a> devenirii reseller.</label>
                                    <label> <input type="checkbox" value="1" name="termeni" <?= isset( $_POST['termeni'] ) && $_POST['termeni']=="1" ? "CHECKED" : ""?> >Sunt de acord cu <a href="<?=site_url('info/termeni-si-conditii')?>" target="_blank">Termenii si conditiile</a> siteului.</label>
                                </div>

                                <div class="one-col sep">
                                    <h4>Adresa de livrare</h4>
                                </div>

                                <div class="one-col">
                                    <label> 
                                        <input type="checkbox" value="1" name="livrare_adresa_1" <?=isset( $_POST['livrare_adresa_1'] ) ? "CHECKED" : ""?> />
                                        Adresa de livrarea difera de adresa de contact
                                    </label>
                                </div>
                                
                                <div class="adresa_2" style="display: <?= isset($_POST['livrare_adresa_1']) ? "block" : "none" ?>">
                                    <div class="two-col">
                                        <label>Judet</label>
                                        <?=form_dropdown('livrare_id_judet', $options_judete, (isset( $_POST['livrare_id_judet'] ) ? $_POST['livrare_id_judet'] : ""), "onchange='return updateSelectSrc(this.value, \"select_src_2\")'")?>
                                    </div>

                                    <div class="two-col">
                                        <label>Oras</label>
                                        <?=form_dropdown('livrare_id_localitate', $options_localitati_livrare, (isset( $_POST['livrare_id_localitate'] ) ? $_POST['livrare_id_localitate'] : ""), "id='select_src_2'")?>  
                                    </div>


                                    <div class="two-col">
                                        <label>Adresa</label>
                                        <input name="livrare_adresa" value="<?=set_value('livrare_adresa')?>" type="text" id="inputEmail" class="input-medium">
                                    </div>
                                </div>


                                <div class="two-col codsecuritate">
                                    <label>Cod securitate</label>
                                    <?=$captcha?>
                                    <input name="captcha" value="<?=set_value('captcha')?>" style="width:25%!important;" value="" type="text" id="inputEmail" class="input-medium">
                                </div>

                                <div class="one-col">
                                    <input type="button" onclick="this.form.submit()" class="creeaza-cont" value="Creeaza cont">
                                </div>


                            </form>


                        </div>




                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

