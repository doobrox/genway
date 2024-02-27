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
    
<form action="<?=base_url()?>admin/curieri/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; curier" : "Adaug&#259; un nou curier"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Nume</label>
                <input type="text" name="nume" value="<?=isset( $_POST['nume'] ) ? set_value('nume') : (isset($item['nume']) ? $item['nume'] : "")?>" id="build_slug" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:49%; float:left; margin-right: 1%;"> 
                <label>Pret primul kg (RON)</label>
                <input type="text" name="pret_primul_kg" value="<?=isset( $_POST['pret_primul_kg'] ) ? set_value('pret_primul_kg') : (isset($item['pret_primul_kg']) ? $item['pret_primul_kg'] : "")?>" style="width: 90%" />
            </fieldset>
            <fieldset style="width:49%; float:left;"> 
                <label>Pret kg aditional (RON)</label>
                <input type="text" name="pret_kg_aditional" value="<?=isset( $_POST['pret_kg_aditional'] ) ? set_value('pret_kg_aditional') : (isset($item['pret_kg_aditional']) ? $item['pret_kg_aditional'] : "")?>" style="width: 90%" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:32%; float:left; margin-right: 1%;"> 
                <label>Taxa ramburs (RON)</label>
                <input type="text" name="taxa_ramburs" value="<?=isset( $_POST['taxa_ramburs'] ) ? set_value('taxa_ramburs') : (isset($item['taxa_ramburs']) ? $item['taxa_ramburs'] : "")?>" style="width: 90%" />
            </fieldset>
            <fieldset style="width:32%; float:left; margin-right: 1%;"> 
                <label>Procent din val. comanda (%)</label>
                <input type="text" name="procent_ramburs" value="<?=isset( $_POST['procent_ramburs'] ) ? set_value('procent_ramburs') : (isset($item['procent_ramburs']) ? $item['procent_ramburs'] : "")?>" style="width: 90%" />
            </fieldset>
            <fieldset style="width:33%; float:left;"> 
                <label>Taxa km exteriori (RON/km)</label>
                <input type="text" name="taxa_km_exteriori" value="<?=isset( $_POST['taxa_km_exteriori'] ) ? set_value('taxa_km_exteriori') : (isset($item['taxa_km_exteriori']) ? $item['taxa_km_exteriori'] : "")?>" style="width: 90%" />
            </fieldset>
        </div>
        <div class="clear"></div>
        <footer>
            <div class="submit_link">
                Activ:  
                <?=form_dropdown('activ', $options_activ, (isset( $_POST['activ'] ) ? $_POST['activ'] : (isset($item['activ']) ? $item['activ'] : "")))?>
                <input type="submit" value="Salveaz&#259;" class="alt_btn">
                <input type="reset" value="Reseteaz&#259;">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>