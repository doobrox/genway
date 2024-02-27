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

                    <div class="cos">

                        <h2 class="page-head">Contact</h2>

                        <div class="contact-wrap">

                            <div class="contact-info">

                                <div class="contact-adresa">

                                    <h3><strong><?= setare('FACTURARE_NUME_FIRMA') ?></strong></h3>
                                    <p><?= setare('FACTURARE_ADRESA') ?>, <?= setare('FACTURARE_LOCALITATE') ?>, jud. <?= setare('FACTURARE_JUDET') ?></p>
                                    
                                    <?php if( setare('AFISARE_DATE_FIRMA')=="DA" ): ?>
                                        <p><strong>Cod Fiscal:</strong> <?= setare('FACTURARE_CUI') ?></p>
                                        <p><strong>Nr. reg. com.:</strong> <?= setare('FACTURARE_NR_REG_COMERT') ?></p>
                                        <p><strong>Capital social:</strong> <?= setare('FACTURARE_CAPITAL_SOCIAL') ?></p>
                                    <? endif ?>

                                </div>


                                <div class="orar">

                                    <h3><?= $program_lucru['titlu'] ?></h3>
                                    <?= $program_lucru['continut'] ?>
                                </div>

                                <div class="informatii-contact">
                                    <p><strong>Telefon:</strong> <?= setare('TELEFON_CONTACT') ?>, <?= setare('TELEFON_CONTACT_2') ?></p>
                                    <p>
                                        <strong>E-mail:</strong> 
                                        <?php foreach( $email_contact as $email ): ?>
                                            <a href="mailto:<?= $email['email'] ?>"><?= $email['email'] ?></a><?= $email['separator'] ?> 
                                        <? endforeach ?>
                                    </p>
                                    <p><strong>Site web:</strong> <a href="<?= base_url() ?>">www.genway.ro</a></p>

                                </div>


                            </div>
                            <div class="contact-form">

                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2841054.4667385486!2d25.020079599999995!3d45.94194665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sro!2sro!4v1403878762432" width="290" height="290" frameborder="0" style="border:0"></iframe>

                                <h3>Trimite-ne un mesaj</h3>
                                
                                <?php if( isset( $succes ) ): ?>
                                    <p class="alert alert-success">
                                        <?=$succes?>
                                    </p>
                                <? endif ?>

                                <?php if( validation_errors()!=""):?>
                                    <div class="alert alert-error"><?=validation_errors()?></div>
                                <? endif ?>
                                
                                <form action="<?= site_url('contact/verificare') ?>" method="post">
                                    <div class="formwrap">

                                        <input name="nume" value="<?= set_value('nume')!="" ? set_value('nume')  : "Nume" ?>" onFocus="if (this.value == 'Nume') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Nume';}" type="text">

                                        <input name="email" value="<?= set_value('email')!="" ? set_value('email')  : "E-mail" ?>" onFocus="if (this.value == 'E-mail') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'E-mail';}"  type="text">

                                        <input name="telefon" value="<?= set_value('telefon')!="" ? set_value('telefon')  : "Telefon" ?>" onFocus="if (this.value == 'Telefon') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Telefon';}"  type="text">

                                        <input name="subiect" value="<?= set_value('subiect')!="" ? set_value('subiect')  : "Subiect" ?>" onFocus="if (this.value == 'Subiect') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Subiect';}"  type="text">

                                        <textarea name="mesaj" onFocus="if (this.value == 'Mesaj') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Mesaj';}"><?= set_value('mesaj')!="" ? set_value('mesaj')  : "Mesaj" ?></textarea>
                                        
                                       

                                    </div>
									<input name="captcha" class="input-captcha" size="16" type="text"  onFocus="if (this.value == 'Cod securitate') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Cod securitate';}"/> 
                                        <?=$captcha?>
                                    <input type="button" onclick="this.form.submit()" value="Trimite">

                                </form>
                            </div>

                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
