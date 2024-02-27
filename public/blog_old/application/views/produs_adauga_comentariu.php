<div class="pam-postcontent pam-postcontent-0 clearfix">
    <?php if( isset( $breadcrumbs ) ): ?>
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
    <? endif ?>
    
    <div class="pam-content-layout-wrapper layout-item-3">
        <div class="pam-content-layout layout-item-4">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell" style="width: 100%" >
                    <h2>Recenzii <?= $item['nume'] ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="pam-content-layout-wrapper">
        <div class="pam-content-layout layout-item-4">

            <div class="pam-content-layout-row">

                <div class="pam-layout-cell layout-item-5 imagine-produs" >
                    <p>
                        <?php if( $item['imagine']!="default.png" ): ?>  
                            <a href="<?=base_url() . MAINSITE_STYLE_PATH?>images/produse/<?= $item['imagine'] ?>" title="<?= $item['nume'] ?>" rel="prettyPhoto[galerie]">
                        <? endif ?>

                            <img width="271" src="<?=base_url() . MAINSITE_STYLE_PATH?>images/produse/<?= $item['imagine'] ?>" alt="<?= $item['nume'] ?>" title="<?= $item['nume'] ?>" />

                        <?php if( $item['imagine']!="default.png" ): ?>  
                            </a>
                        <? endif ?>
                        <br>
                        
                        <center>
                            <?php if( $item['nota_round']!=0 ): ?>
                                <img src="<?=base_url() . MAINSITE_STYLE_PATH?>images/star/star-2<?= $item['nota_round'] ?>.png"><br />
                            <? endif ?>

                            nota <?= $item['nota_medie'] ?> din <?=$nr_comentarii?> ratinguri
                        </center>
                    </p>

                </div>

                <div class="pam-layout-cell layout-item-6" style="width: 60%" >
                    <div>
                        <h3>Adauga recenzie</h3>
                        
                        <?php if( $this->simpleloginsecure->is_logat() ): ?>
	                        
	                        <?php if( validation_errors()!=""):?>
	                            <div class="alert alert-error"><?=validation_errors()?></div>
	                        <? endif ?>
	
	                        <?php if( isset( $error ) ): ?>
	                            <div style="padding: 15px 0">
	                                <p><span class="alert alert-error"><?=$error?></span></p>
	                            </div>
	                        <? endif ?>
	
	                        <?php if( isset( $succes ) ): ?>
	                            <div style="padding: 15px 0">
	                                <p><span class="alert alert-success"><?=$succes?></span></p>
	                            </div>
	                        <? endif ?>
	                        
	                        <div class="adauga-comentariu">
	                            <form action="" method="post" id="form-comenteaza">
	                                <label>Nota ta:</label>
	                                <?=form_dropdown('nota', $options_note, (isset( $_POST['nota'] ) ? $_POST['nota'] : ""))?><br />
	
	        
	                                <label>Parerea ta</label>
	                                <textarea rows="5" cols="60" style="width: 100%;" name="comentarii"><?=isset( $_POST['comentarii'] ) ? $_POST['comentarii'] : ""?></textarea>
	                                
	                                <label>Cod securitate</label>
	                                <input name="captcha" style="width:25%!important;" value="" type="text" >
	                                <?=$captcha?>
	                                
	                                <br />
	                                <input type="hidden" name="salveaza" value="1" />
	                                <input type="button" onclick="this.form.submit()"class="posteaza-comentariu"  value="Adauga recenzie" />
	                            </form>
	                        </div>
                        
                        <?php else: ?>
                        
                        	<div class="alert alert-error">
                        		<p>Trebuie sa fiti logat pentru a putea adauga o recenzie. <a href="<?= site_url('login') ?>" title="Autentificare" style="color: white; text-decoration: underline">Autentificare</a> </p>
                        	</div>
                        
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pam-content-layout-wrapper layout-item-3">
    <div class="pam-content-layout layout-item-4">
        <div class="pam-content-layout-row">
            <div class="pam-layout-cell" style="width: 100%" >

                <h4 class="descriere">Comentarii</h4>

                <?php if( !empty( $comentarii ) ): ?>
                        <?php foreach( $comentarii as $comentariu ): ?>
                            <div class="comentariu-produs">
                                <div class="comentariu-rating">
                                    <p>
                                        <img src="<?=base_url() . MAINSITE_STYLE_PATH?>images/star/star-2<?=$comentariu['nota']?>.png" alt="Nota <?= $comentariu['nota'] ?>" title="Nota <?= $comentariu['nota'] ?>" />
                                    </p>
                                    <p><span><?=$comentariu['data_adaugare_f']?></span></p>
                                </div>
                                <div class="comentariu-continut">
                                    <p><?=$comentariu['comentarii']?></p>
                                </div>
                            </div>
                        <? endforeach ?>
                    <? else: ?>
                        <p>
                            <em>Nu exista nici un comentariu.</em>
                        </p>
                    <? endif ?>
            </div>
        </div>
    </div>
</div>