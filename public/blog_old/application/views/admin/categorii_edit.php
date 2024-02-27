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
    
<form action="<?=base_url()?>admin/categorii/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post" enctype="multipart/form-data"> 
    <article class="module width_full">
        <header><h3><?=isset($item['id']) ? "Editeaz&#259; categorie" : "Adaug&#259; o nou&#259; categorie"?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Nume</label>
                <input type="text" name="nume" value="<?=isset( $_POST['nume'] ) ? set_value('nume') : (isset($item['nume']) ? $item['nume'] : "")?>" id="build_slug" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Slug</label>
                <input type="text" name="slug" value="<?=isset( $_POST['slug'] ) ? set_value('slug') : (isset($item['slug']) ? $item['slug'] : "")?>" />                    
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>Descriere</label>
                <?=$editor?>
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>SEO Title</label>
                <input type="text" name="seo_title" value="<?=isset( $_POST['seo_title'] ) ? set_value('seo_title') : (isset($item['seo_title']) ? $item['seo_title'] : "")?>" />
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>SEO Meta description</label>
                <input type="text" name="meta_description" value="<?=isset( $_POST['meta_description'] ) ? set_value('meta_description') : (isset($item['meta_description']) ? $item['meta_description'] : "")?>" />                    
            </fieldset>
        </div>
        <div class="module_content">
            <fieldset>
                <label>SEO Meta keywords</label>
                <input type="text" name="meta_keywords" value="<?=isset( $_POST['meta_keywords'] ) ? set_value('meta_keywords') : (isset($item['meta_keywords']) ? $item['meta_keywords'] : "")?>" />                    
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
                        <a href="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorii/<?= $item['imagine'] ?>" rel="prettyPhoto">
                            <img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorii/<?= $item['imagine'] ?>" width="160"/> <br />
                        </a>
                        <a href="<?= base_url() ?>admin/categorii/sterge_imagine/<?=$item['id']?>" onclick="return confirm('Esti sigur ca vrei sa stergi imaginea?')">Sterge imaginea</a>
                    <? else: ?>
                        <em>Nu exista imagine curenta.</em>
                    <? endif ?>
                </div>
            </fieldset>
        </div><div class="clear"></div>
        <div class="module_content">
            <fieldset>
                <label>Categorie parinte</label>
                <div class="list-categorii">
                    <?=isset( $categorii_tree ) ? $categorii_tree : ""?>
                </div>
            </fieldset>
        </div>
        <footer>
            <div class="submit_link">
                Publicat: 
                <?=form_dropdown('activ', $options_activ, (isset( $_POST['activ'] ) ? $_POST['activ'] : (isset($item['activ']) ? $item['activ'] : "")))?>
                <input type="submit" value="Salveaz&#259;" class="alt_btn">
                <input type="reset" value="Reseteaz&#259;">
            </div>
        </footer>
    </article><!-- end of post new article -->
</form>