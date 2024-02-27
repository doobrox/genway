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

                        <h2>Schimba parola</h2>
                        
                        <?php if( isset( $succesSalvareParola ) ): ?>
                            <p class="alert alert-success">
                                <?=$succesSalvareParola?>
                            </p><br clear="all" />
                        <? endif ?>

                        <?php if( isset( $errorSalvareParola ) ): ?>
                            <div class="alert alert-error">
                                <?=$errorSalvareParola?>
                            </div><br clear="all" />
                        <? endif ?>
                        
                        <p>Pentru a putea schimba este necesar sa introduceti parola actuala a contului.</p>
                        <div class="login-form">

                        <form action="<?=  base_url()?>profilul_meu/salveaza_parola" method="POST" id="form-login">
                                <div class="two-col">
                                    <label>Parola veche</label>
                                    <input name="user_pass_old" value="<?=isset( $_POST['user_pass_old'] ) ? $_POST['user_pass_old'] : ""?>" type="password">
                                </div>
                                <br clear="all" />
                                
                                <div class="two-col">
                                    <label>Parola noua</label>
                                    <input name="user_pass" value="<?=isset( $_POST['user_pass'] ) ? $_POST['user_pass'] : ""?>" value="" type="password">
                                </div>
                                
                                <div class="two-col">
                                    <label>Confirma parola noua</label>
                                    <input name="user_pass_conf" value="<?=isset( $_POST['user_pass_conf'] ) ? $_POST['user_pass_conf'] : ""?>" value="" type="password">
                                </div>

                                <div class="one-col">
                                    <input type="button" onclick="this.form.submit()" class="modifica-parola" value="Modifica parola">
                                </div>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
