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
    
<form action="<?=base_url()?>admin/produse_filtre/salveaza/<?=$produs['id']?>/<?=isset($item['id']) ? $item['id'] : ""?>" method="post"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; filtru produs" : "Adaug&#259; un nou filtru pentru produs"?></h3></header>
        <div class="module_content">
            <?php foreach ($filtre as $filtru): ?>
                <fieldset>
                    <label><?=$filtru['nume']?></label>
                    <select name="id_filtre[]">
                        <?php foreach ($filtru['subfiltre'] as $subfiltru): ?>
                            <option value="<?=$subfiltru['id']?>" <?=$subfiltru['selected']?>><?=$subfiltru['nume']?></option>
                        <? endforeach; ?>
                    </select>
                </fieldset>
            <? endforeach ?>
        </div>
        <div class="module_content">
            <fieldset style="width:49%; float:left; margin-right: 1%;"> 
                <label>Cod EAN13</label>
                <input type="text" name="cod_ean13" value="<?=isset( $_POST['cod_ean13'] ) ? set_value('cod_ean13') : (isset($item['cod_ean13']) ? $item['cod_ean13'] : "")?>" style="width: 92%" />
            </fieldset>
            <fieldset style="width:48%; float:left; margin-right: 1%;"> 
                <label>Greutate (KG)</label>
                <input type="text" name="greutate" value="<?=isset( $_POST['greutate'] ) ? set_value('greutate') : (isset($item['greutate']) ? $item['greutate'] : "")?>" style="width: 92%" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:49%; float:left; margin-right: 1%;"> 
                <label>Pret</label>
                <input type="text" name="pret" value="<?=isset( $_POST['pret'] ) ? set_value('pret') : (isset($item['pret']) ? $item['pret'] : "")?>" style="width: 92%" />
            </fieldset>
            <fieldset style="width:48%; float:left;"> 
                <label>Stoc</label>
                <input type="text" name="stoc" value="<?=isset( $_POST['stoc'] ) ? set_value('stoc') : (isset($item['stoc']) ? $item['stoc'] : "")?>" style="width: 92%" /> <br clear="all" />
            </fieldset>
        </div><div class="clear"></div>
        <div class="module_content">
            <fieldset>
                <p style="font-weight: bold; padding-left: 10px; margin: 0;">NOTA: Daca nu va fi introdus pretul pentru acest filtru, atunci pretul filtrului va fi considerat acelasi cu pretul produsului. Acelasi lucru si pentru greutate.</p>
            </fieldset>
        </div>
        <footer>
            <div class="submit_link">
                <input type="submit" value="Salveaz&#259;" class="alt_btn">
                <input type="reset" value="Reseteaz&#259;">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>