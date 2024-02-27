<?php //todo: de stilizat aici ?>

<style type="text/css">
    body {
        font-family: arial;
        font-size: 12px;
    }
</style>

<?php if( isset( $succes ) ): ?>
    <p class="succes"><?=$succes?></p>
<? else: ?>
    <strong>Ne pare rau, dar nu exista in stoc cantitatea de <u><?=$cantitate?> bucati</u> pentru produsul <u><?= $nume_produs ?></u>. </strong>
    <br /><br />
    Completati adresa de email, pentru a fi anuntati atunci cand stocul va fi mai mare sau egal cu <strong><?=$cantitate?> bucati</strong>.

    <hr />
    
    <?php if( validation_errors()!=""):?>
        <div class="error"><?=validation_errors()?></div>
    <? endif ?>

    <form action="<?=base_url()?>index_page/ajax_popup_stoc_indisponibil_submit" method="post">
        <input type="hidden" name="alerta_cantitate" value="<?= isset($_POST['alerta_cantitate']) ? $_POST['alerta_cantitate'] : $cantitate ?>" />
        <input type="hidden" name="alerta_id_produs" value="<?= isset($_POST['alerta_id_produs']) ? $_POST['alerta_id_produs'] : $id_produs ?>" />

        Email: <input name="alerta_email" value="<?= set_value('alerta_email') ?>" /> 
        <input type="submit" value="Trimite" />
    </form>
<? endif ?>
