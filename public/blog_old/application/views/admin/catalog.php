<?php if( isset( $succes ) && $succes!="" ): ?>

    <h4 class="alert_success validation_errors"><?=$succes?></h4>

<? endif ?>

    

<?php if( isset( $warning ) ): ?>

    <h4 class="alert_warning validation_errors"><?=$warning?></h4>

<? endif ?>

    

<?php if( isset( $error ) && $error!="" ): ?>

    <h4 class="alert_error validation_errors"><?=$error?></h4>

<? endif ?>



<?php if( validation_errors()!="" ): ?>

    <h4 class="alert_error validation_errors"><?=validation_errors()?></h4>

<? endif ?>    

    

<form action="<?=base_url()?>admin/catalog/upload" method="post" enctype="multipart/form-data"> 

    <article class="module width_full">

        <header><h3>Upload catalog produse</h3></header>
        
        <div class="module_content">

            <fieldset>
                
                <?php if( $catalog_curent == "" ): ?>
                    <p style="margin-left: 10px;"><strong>Nu exista un catalog curent.</strong></p>
                <? else: ?>
                    <p style="margin-left: 10px;"><a href="<?= $catalog_curent ?>" target="_blank"><strong>Descarca catalogul curent</strong></a></p>
                <? endif ?>
                
                <label>Selecteaza catalogul:</label>

                <div style="float: left; width: 100%; margin-left: 10px">

                    <input type="file" name="catalog" /><br />

                </div>

            </fieldset>

        </div>

        <footer>

            <div class="submit_link">

                <input type="submit" value="Salveaz&#259;" class="alt_btn">

            </div>

        </footer>

    </article><!-- end of post new article -->

</form>
