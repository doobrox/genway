<?php if( isset( $breadcrumbs ) ): ?>
    <div class="pam-postcontent pam-postcontent-0 clearfix">
        <div class="pam-content-layout-wrapper layout-item-0">
            <div class="pam-content-layout layout-item-1">
                <div class="pam-content-layout-row">
                    <div class="pam-layout-cell layout-item-2" style="width: 100%" >
                        <div class="paginare">
                            <?= display_breadcrumbs($breadcrumbs) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endif ?>

<div class="pam-postcontent pam-postcontent-0 clearfix">
    <div class="pam-content-layout-wrapper layout-item-0">
        <div class="pam-content-layout layout-item-1">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell layout-item-2" style="width: 100%" >

                    <div class="login-signup">

                        <div class="login-user">

                            <h2>Intra in cont</h2>
                            
                            <?php if( isset( $succesLogin ) ): ?>
                                <div class="alert alert-success">
                                    <?=$succesLogin?>
                                </div>
                            <? endif ?>

                            <?php if( isset( $errorLogin ) ): ?>
                                <div class="alert alert-error">
                                    <?=$errorLogin?>
                                </div>
                            <? endif ?>
                            
                            <p>Pentru a intra in cont introduceti informatiile pe care le-ati folosit la inregistrare</p>
                            <div class="login-form">
                                <form action="<?= site_url('login/verificare') ?>?link=<?=isset( $link ) ? $link : ""?>" method="post">
                                    <label>Adresa de email:</label>
                                    <input type="text" name="user_email_login" value="<?=isset( $_POST['user_email_login'] ) ? $_POST['user_email_login'] : ""?>" />
                                    <label>Parola:</label>
                                    <input type="password" name="user_pass_login" value="<?=isset( $_POST['user_pass_login'] ) ? $_POST['user_pass_login'] : ""?>" />
                                    <label><span>Tine-ma minte</span> <input type="checkbox"></label>
                                    
                                    <input type="button" onclick="this.form.submit()" class="autentificare" value="Intra in cont">
                                    <input type="button" onclick="window.location='<?= site_url('recuperare_parola') ?>'; return false;" class="recupereaza-parola" value="Am uitat parola">
                                </form>
                            </div>

                        </div>

                        <div class="login-cont-nou">

                            <h2>Nu am cont</h2>
                            <p>Daca iti vei creea un cont vei putea ulterior cumpara produse fara completarea tuturor datele de livrare si de contact. Va multumim!</p>
                            <p><a href="<?= site_url('login/inregistrare') ?>" class="inregistrare-cont">Vreau un cont nou</a></p>
                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

