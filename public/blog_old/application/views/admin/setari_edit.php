<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>

<form action="<?=base_url()?>admin/setari/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" method="post"> 
    <article class="module width_full">
        <header><h3>Editeaz&#259; setare: <?=$item['camp']?></h3></header>
        <div class="module_content">
            <fieldset>
                <label>Valoare</label>
                <textarea name="valoare" cols="40" rows="5"><?=isset( $_POST['valoare'] ) ? set_value('valoare') : (isset($item['valoare']) ? $item['valoare'] : "")?></textarea>
                <?php if( $item['descriere']!="" ): ?>
                    <br />
                    <span style="margin-left: 10px"><?= $item['descriere'] ?></span>
                <? endif ?>
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
