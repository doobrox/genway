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
    
<form action="<?=base_url()?>admin/vouchere/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" enctype="multipart/form-data" method="post">
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; voucher" : "Adaug&#259; un nou voucher"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>COD</label>
                <input type="text" name="cod" value="<?=isset( $_POST['cod'] ) ? set_value('cod') : (isset($item['cod']) ? $item['cod'] : "")?>" /><BR />
                <span style="font-size: 10px; margin-left: 10px;">Codul voucherului trebuie sa contina doar litere si cifre si sa fie unic in baza de date.</span>
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Nume</label>
                <input type="text" name="nume" value="<?=isset( $_POST['nume'] ) ? set_value('nume') : (isset($item['nume']) ? $item['nume'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:49%; float:left; margin-right: 1%;"> 
                <label>Tip</label>
                <?=form_dropdown('tip', $options_tip, (isset( $_POST['tip'] ) ? $_POST['tip'] : (isset($item['tip']) ? $item['tip'] : "")))?>
            </fieldset>
            <fieldset style="width:49%; float:left;"> 
                <label>Valoare</label>
                <input type="text" name="valoare" value="<?=isset( $_POST['valoare'] ) ? set_value('valoare') : (isset($item['valoare']) ? $item['valoare'] : "")?>"  style="width: 92%" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:49%; float:left; margin-right: 1%;"> 
                <label>Data expirare</label>
                <input type="text" name="data_expirare" value="<?=isset( $_POST['data_expirare'] ) ? set_value('data_expirare') : (isset($item['data_expirare']) ? $item['data_expirare'] : "")?>" id="data_start" style="width: 92%" />
            </fieldset>
            <fieldset style="width:49%; float:left;"> 
                <label>Caracter</label>
                <?=form_dropdown('caracter', $options_caracter, (isset( $_POST['caracter'] ) ? $_POST['caracter'] : (isset($item['caracter']) ? $item['caracter'] : "")))?>
            </fieldset>
            <fieldset style="width:100%;">
                <label>ID produs</label>
                <input type="text" name="id_produs" value="<?=isset( $_POST['id_produs'] ) ? set_value('id_produs') : (isset($item['id_produs']) ? $item['id_produs'] : "")?>" />
            </fieldset>
        </div><div class="clear"></div>
        <div class="module_content">
            <fieldset>
                <label>Activ</label>
                <?=form_dropdown('activ', $options_activ, (isset( $_POST['activ'] ) ? $_POST['activ'] : (isset($item['activ']) ? $item['activ'] : "")))?>
            </fieldset>
        </div>
            <div class="submit_link">
                <input type="submit" value="Salveaz&#259;" class="alt_btn">
                <input type="reset" value="Reseteaz&#259;">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>
