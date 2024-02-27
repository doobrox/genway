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
    
<form action="<?=base_url()?>admin/newsletter/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post" onsubmit="return confirm('Esti sigur ca vrei sa salvezi acest newsletter?')"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; newsletter" : "Adaug&#259; un nou newsletter"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Titlu</label>
                <input type="text" name="titlu" value="<?=isset( $_POST['titlu'] ) ? $_POST['titlu'] : (isset($item['titlu']) ? $item['titlu'] : "")?>" />
            </fieldset>
            <fieldset>
                <label>Con&#355;inut</label>
                <?=$editor?>
            </fieldset>
        </div>
        <footer>
            <div class="submit_link">
                <input type="submit" name="salveaza_trimite" value="Salveaz&#259; şi trimite" class="alt_btn">
                <input type="submit" name="salveaza" value="Salveaz&#259; f&#259;r&#259; trimitere">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>