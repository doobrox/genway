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
    
<form action="<?=base_url()?>admin/pagini/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; pagina" : "Adaug&#259; o nou&#259; pagin&#259;"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Titlu</label>
                <input type="text" name="titlu" value="<?=isset( $_POST['titlu'] ) ? set_value('titlu') : (isset($item['titlu']) ? $item['titlu'] : "")?>" id="build_slug" />
            </fieldset>
            <fieldset>
                <label>Link extern</label>
                <input type="text" name="link_extern" value="<?=isset( $_POST['link_extern'] ) ? set_value('link_extern') : (isset($item['link_extern']) ? $item['link_extern'] : "")?>" /><br />
                <span style="margin-left: 10px">NOTA: daca acest camp este completat campurile de mai jos nu vor putea fi afisate pe site.</span>
            </fieldset>
            <fieldset>
                <label>Slug</label>
                <input type="text" name="slug" value="<?=isset( $_POST['slug'] ) ? set_value('slug') : (isset($item['slug']) ? $item['slug'] : "")?>" />
            </fieldset>
            <fieldset>
                <label>Con&#355;inut</label>
                <?=$editor?>
            </fieldset>
        </div>
        <footer>
            <div class="submit_link">
                Meniu principal: 
                <?=form_dropdown('in_meniu_principal', array_reverse( $options_activ ), (isset( $_POST['in_meniu_principal'] ) ? $_POST['in_meniu_principal'] : (isset($item['in_meniu_principal']) ? $item['in_meniu_principal'] : "")))?>
                Meniu secundar: 
                <?=form_dropdown('in_meniu', $options_activ, (isset( $_POST['in_meniu'] ) ? $_POST['in_meniu'] : (isset($item['in_meniu']) ? $item['in_meniu'] : "")))?>
                Activ: 
                <?=form_dropdown('activ', $options_activ, (isset( $_POST['activ'] ) ? $_POST['activ'] : (isset($item['activ']) ? $item['activ'] : "")))?>
                <input type="submit" value="Salveaz&#259;" class="alt_btn">
                <input type="reset" value="Reseteaz&#259;">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>