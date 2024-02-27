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

                        <h2>Mi-am uitat parola </h2>
                        
                        <?php if (isset($succesRecuperarePass)): ?>
                            <div class="alert alert-success">
                                <?= $succesRecuperarePass ?>
                            </div>							<br clear="all" />
                        <? endif ?>

                        <?php if (isset($errRecuperarePass)): ?>
                            <div class="alert alert-error">
                                <?= $errRecuperarePass ?>
                            </div>							<br clear="all" />
                        <? endif ?>
                        
                        <p>Pentru a recupera parola introduceti adresa dvs. de email si veti primi un link prin care veti putea confirma recuperarea parolei, apoi parola dvs. va fi schimbata.</p>
                        <div class="login-form">
                            <form action="<?=  base_url()?>recuperare_parola/salveaza" method="POST">

                                <div class="one-col">

                                    <label>Adresa de e-mail:</label>
                                    <input type="text" name="email_recuperare" value="<?=isset( $_POST['email_recuperare'] ) ? $_POST['email_recuperare'] : ""?>" value="" />
                                </div>     

                                <div class="one-col">
                                    <input type="button" onclick="this.form.submit()" class="recuperare-parola" value="Recupereaza parola">
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>