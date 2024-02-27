<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>

<?php if( validation_errors()!="" ): ?>
    <h4 class="alert_error validation_errors"><?=validation_errors()?></h4>
<? endif ?>

<form action="<?=base_url()?>admin/useri/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259;" : "Adaug&#259;"?> utilizator</h3></header>
        <div class="module_content">
            <fieldset>
                <label>Email</label>
                <input type="text" name="user_email" value="<?=isset( $_POST['user_email'] ) ? set_value('user_email') : (isset($item['user_email']) ? $item['user_email'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Telefon</label>
                <input type="text" name="telefon" value="<?=isset( $_POST['telefon'] ) ? set_value('telefon') : (isset($item['telefon']) ? $item['telefon'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:48%; float:left; margin-right: 3%;"> 
                <label>Nume</label>
                <input type="text" name="nume" value="<?=isset( $_POST['nume'] ) ? set_value('nume') : (isset($item['nume']) ? $item['nume'] : "")?>" style="width: 93%" />
            </fieldset>
            <fieldset style="width:48%; float:left;"> 
                <label>Prenume</label>
                <input type="text" name="prenume" value="<?=isset( $_POST['prenume'] ) ? set_value('prenume') : (isset($item['prenume']) ? $item['prenume'] : "")?>" style="width: 93%" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:48%; float:left; margin-right: 3%;"> 
                    <label>Judet</label>
                    <?=form_dropdown('id_judet', $options_judete, (isset( $_POST['id_judet'] ) ? $_POST['id_judet'] : (isset($item['id_judet']) ? $item['id_judet'] : "")), "onchange='return updateSelectSrc(this.value)'")?>
            </fieldset>
            <fieldset style="width:48%; float:left;"> 
                    <label>Localitate</label>
                    <?=form_dropdown('id_localitate', $options_localitati, (isset( $_POST['id_localitate'] ) ? $_POST['id_localitate'] : (isset($item['id_localitate']) ? $item['id_localitate'] : "")), "id='select_src'")?>
            </fieldset>
        </div>
        <div class="clear"></div>
        <div class="module_content">
            <fieldset>
                <label>Adresa</label>
                <input type="text" name="adresa" value="<?=isset( $_POST['adresa'] ) ? set_value('adresa') : (isset($item['adresa']) ? $item['adresa'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Tip utilizator</label>
                <?=form_dropdown('tip', $options_tip, (isset( $_POST['tip'] ) ? $_POST['tip'] : (isset($item['tip']) ? $item['tip'] : "")))?>
            </fieldset>
        </div>
        <div id="inputs_firma" style="display: <?=isset( $_POST['tip'] ) && $_POST['tip']==2 ? "block" : (isset($item['tip']) && $item['tip']==2 ? "block" : "none" )?>">
            <div class="module_content">
                <fieldset>
                    <label>Nume firma</label>
                    <input type="text" name="nume_firma" value="<?=isset( $_POST['nume_firma'] ) ? set_value('nume_firma') : (isset($item['nume_firma']) ? $item['nume_firma'] : "")?>" />
                </fieldset>
            </div>
            <div class="module_content">
                <fieldset style="width:30%; float:left; margin-right: 3%;"> 
                    <label>CUI</label>
                    <input type="text" name="cui" value="<?=isset( $_POST['cui'] ) ? set_value('cui') : (isset($item['cui']) ? $item['cui'] : "")?>" style="width: 88%" />
                </fieldset>
                <fieldset style="width:30%; float:left; margin-right: 3%;"> 
                    <label>Nr. reg. comert.</label>
                    <input type="text" name="nr_reg_comert" value="<?=isset( $_POST['nr_reg_comert'] ) ? set_value('nr_reg_comert') : (isset($item['nr_reg_comert']) ? $item['nr_reg_comert'] : "")?>" style="width: 88%" />
                </fieldset>
                <fieldset style="width:33%; float:left;"> 
                    <label>Autorizatie IGPR</label>
                    <input type="text" name="autorizatie_igpr" value="<?=isset( $_POST['autorizatie_igpr'] ) ? set_value('autorizatie_igpr') : (isset($item['autorizatie_igpr']) ? $item['autorizatie_igpr'] : "")?>" style="width: 88%" />
                </fieldset>
            </div>
        </div>
        <div class="clear"></div>
        <div class="module_content">
            <fieldset style="width:48%; float:left; margin-right: 3%;"> 
                <label>Discount fidelitate (%)</label>
                <input type="text" name="discount_fidelitate" value="<?=isset( $_POST['discount_fidelitate'] ) ? set_value('discount_fidelitate') : (isset($item['discount_fidelitate']) ? $item['discount_fidelitate'] : "")?>" style="width: 93%" />
            </fieldset>
            <fieldset style="width:48%; float:left;"> 
                <label>Reseller</label>
                <?=form_dropdown('reseller', $options_admin, (isset( $_POST['reseller'] ) ? $_POST['reseller'] : (isset($item['reseller']) ? $item['reseller'] : "")))?>            
            </fieldset>
        </div>
        <div class="clear"></div>
        <footer>
            <div class="submit_link">
                Admin:
                <?=form_dropdown('admin', $options_admin, (isset( $_POST['admin'] ) ? $_POST['admin'] : (isset($item['admin']) ? $item['admin'] : "")))?>            
                Activ: 
                <?=form_dropdown('valid', $options_valid, (isset( $_POST['valid'] ) ? $_POST['valid'] : (isset($item['valid']) ? $item['valid'] : "")))?>
                <input type="submit" value="Salveaz&#259;" class="alt_btn">
                <input type="reset" value="Reseteaz&#259;">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>
    
<?php if( isset( $item['id'] ) ): ?>
        <form action="<?=base_url()?>admin/useri/salveaza_parola/<?=$item['id']?>" enctype="multipart/form-data" method="post" onsubmit="return confirm('Esti sigur ca vrei sa schimbi parola?')"> 
            <article class="module width_full">
                <header><h3>Schimb&#259; parola</h3></header>
                <div class="module_content">
                    <fieldset style="width:48%; float:left; margin-right: 3%;"> 
                        <label>Parola</label>
                        <input type="password" name="user_pass" style="width: 93%" />
                    </fieldset>
                    <fieldset style="width:48%; float:left;"> 
                        <label>Confirm&#259; parola</label>
                        <input type="password" name="user_pass_conf"style="width: 93%" />
                    </fieldset>
                </div>
                <div class="clear"></div>
                <footer>
                    <div class="submit_link">
                        <input type="submit" value="Salveaz&#259;" class="alt_btn">
                    </div>
                </footer>
            </article><!-- end of post new article -->
        </form>
    <? endif ?>