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
    
<form action="<?=base_url()?>admin/csv/import" method="post" enctype="multipart/form-data"> 
    <article class="module width_full">
        <header><h3>Import Produse</h3></header>
        <div class="module_content">
            <fieldset>
                <label>Selecteaza fisierul CSV:</label>
                <div style="float: left; width: 100%; margin-left: 10px">
                    <input type="file" name="csvfile" /><br />
                    <input type="checkbox" name="add_produse" /> adauga produsele din fisierul CSV care nu exista in baza de date <br />
                    <span style="font-size: 10px;">NOTA: prima linie va fi ignorata!</span>
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

<article class="module width_full">
    <header><h3>Export Produse</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Export: <a href="<?=site_url("admin/csv/export")?>">Salveaza fisier CSV</a></label>
        </fieldset>
    </div>
</article><!-- end of post new article -->
