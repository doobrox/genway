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
    
<form action="<?=base_url()?>admin/newsletter_abonati/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; abonat" : "Adaug&#259; un nou abonat"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Nume</label>
                <input type="text" name="nume" value="<?=isset( $_POST['nume'] ) ? $_POST['nume'] : (isset($item['nume']) ? $item['nume'] : "")?>" />
            </fieldset>
            <fieldset>
                <label>Adresa de email</label>
                <input type="text" name="email" value="<?=isset( $_POST['email'] ) ? $_POST['email'] : (isset($item['email']) ? $item['email'] : "")?>" />
            </fieldset>
        </div>
        <footer>
            <div class="submit_link">
                Activ: 
                <?=form_dropdown('activ', $options_activ, (isset( $_POST['activ'] ) ? $_POST['activ'] : (isset($item['activ']) ? $item['activ'] : "")))?>
                <input type="submit" name="salveaza" value="Salveaz&#259;" class="alt_btn">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>