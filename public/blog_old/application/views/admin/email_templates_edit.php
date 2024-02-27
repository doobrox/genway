<?php if (isset($succes)): ?>
    <h4 class="alert_success"><?= $succes ?></h4>
<? endif ?>

<?php if (isset($warning)): ?>
    <h4 class="alert_warning"><?= $warning ?></h4>
<? endif ?>

<?php if (isset($error)): ?>
    <h4 class="alert_error"><?= $error ?></h4>
<? endif ?>

<?php if (validation_errors() != ""): ?>
    <h4 class="alert_error validation_errors"><?= validation_errors() ?></h4>
<? endif ?>    

<form action="<?= base_url() ?>admin/email_templates/salveaza/<?= isset($item['id']) ? $item['id'] : "" ?>" method="post"> 

    <article class="module width_full">

        <header><h3><?= isset($item['id']) ? "Editeaz&#259; template" : "Adaug&#259; un nou template" ?></h3></header>

        <div class="module_content">

            <fieldset>

                <label>Nume</label>

                <input type="text" name="nume" value="<?= isset($_POST['nume']) ? set_value('nume') : (isset($item['nume']) ? $item['nume'] : "") ?>" id="build_slug" />

            </fieldset>

            <fieldset>

                <label>Subiect</label>

                <input type="text" name="subiect" value="<?= isset($_POST['subiect']) ? set_value('subiect') : (isset($item['subiect']) ? $item['subiect'] : "") ?>" id="build_slug" />

            </fieldset>

            <fieldset>

                <label>Con&#355;inut</label>

                <?= $editor ?>

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