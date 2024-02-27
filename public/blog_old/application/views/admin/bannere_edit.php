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
    
<form action="<?=base_url()?>admin/bannere/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" enctype="multipart/form-data" method="post">
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; banner" : "Adaug&#259; un nou banner"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Zona</label>
                <?=form_dropdown('zona', $options_zona, (isset( $_POST['zona'] ) ? $_POST['zona'] : (isset($item['zona']) ? $item['zona'] : "")))?>
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Titlu alternativ</label>
                <input type="text" name="titlu" value="<?=isset( $_POST['titlu'] ) ? set_value('titlu') : (isset($item['titlu']) ? $item['titlu'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Link</label>
                <input type="text" name="link" value="<?=isset( $_POST['link'] ) ? set_value('link') : (isset($item['link']) ? $item['link'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Target</label>
                <?=form_dropdown('target', $options_target, (isset( $_POST['target'] ) ? $_POST['target'] : (isset($item['target']) ? $item['target'] : "")))?>
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset style="width:48%; float:left; margin-right: 3%;"> 
                <label>Imagine</label>
                <input type="file" name="imagine" value="" style="width: 97%; margin: 0 0 0 10px" /><br />
            </fieldset>
            <fieldset style="width:48%; float:left;"> 
                <label>Imagine curenta:</label><br clear="all" />
                <div style="width: 97%; padding: 0 10px">
                    <?php if (isset($item['imagine']) && $item['imagine']!=""): ?>
                        <a href="<?= base_url() . MAINSITE_STYLE_PATH ?>images/bannere/<?= $item['imagine'] ?>" rel="prettyPhoto">
                            <img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/bannere/<?= $item['imagine'] ?>" width="300"/> <br />
                        </a>
                        <a href="<?= base_url() ?>admin/bannere/sterge_imagine/<?=$item['id']?>" onclick="return confirm('Esti sigur ca vrei sa stergi imaginea?')">Sterge imaginea</a>
                    <? else: ?>
                        <em>Nu exista imagine curenta.</em>
                    <? endif ?>
                </div>
            </fieldset>
        </div><div class="clear"></div>
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
