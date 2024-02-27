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
                    <?php if (isset($stare)): ?>
                        <?php if ($stare == 1): ?>
                            <h2 class="pam-postheader">Felicitari!</h1>
                            <p class="alert alert-success"><strong>Plata a fost efectuata cu succes. Va multumim.</strong></p>
                            <p>Pentru a vedea detaliile comenzii accesati: <?=anchor("istoric_comenzi/comanda/{$comanda['id']}")?></p>
                        <? elseif ($stare == 0): ?>
                            <h2 class="pam-postheader">In curs de verificare...</h1>
                            <p class="alert alert-warning"><strong>Plata este in curs de verificare a datelor. </h3>
                                    <p><a style="opacity: 1;" href="<?= base_url() ?>istoric_comenzi" class="ka_button small_button small_coolblue"><span>vezi istoric comenzi</span></a></p>
                        <? elseif ($stare == -1): ?>
                            <h2 class="pam-postheader">Plata a fost anulata...</h1>
                            <p class="alert alert-error"><strong>Plata comenzii efectuate a fost anulata.</strong></p>
                            <p><a style="opacity: 1;" href="<?= base_url() ?>istoric_comenzi" class="ka_button small_button small_coolblue"><span>vezi istoric comenzi</span></a></p>
                        <? elseif ($stare == -2): ?>
                            <h2 class="pam-postheader">Plata a fost respinsa...</h1>
                            <p class="alert alert-error"><strong>Datele introduse de catre dvs. nu sunt corecte sau nu aveti fonduri suficiente pentru a achita comanda. <br /> Verificati datele, respectiv soldul curent si incercati din nou.</strong></p>
                            <p><a style="opacity: 1;" href="<?= base_url() ?>istoric_comenzi" class="ka_button small_button small_coolblue"><span>vezi istoric comenzi</span></a></p>
                        <? endif ?>

                        <?php if( $stare == 1 || $stare == 0 ): ?>
                            <?= $this->functions->google_adwords_conversions_code() ?>
                        <? endif ?>
                    <? elseif (isset($paymentUrl)): ?>
                        <h2 class="pam-postheader">Procesare...</h1>
                        <?php if( isset( $succes ) ): ?>
                            <?=$succes?>
                            <?= $this->functions->google_adwords_conversions_code() ?>
                        <? else: ?>
                            <p class="message_yellow">
                                In cateva secunde veti fi redirectionat spre pagina de plata a Mobilpay.ro<br />
                                Va rugam asteptati. <br /><br />
                                <?=img( base_url() . MAINSITE_STYLE_PATH . "images/logo_mobilpay.png" )?>
                            </p>
                        <? endif ?>
                        <form name="frmPaymentRedirect" method="post" action="<?= $paymentUrl ?>">
                            <input type="hidden" name="env_key" value="<?= $env_key ?>"/>
                            <input type="hidden" name="data" value="<?= $env_data ?>"/>
                        </form>        

                        <script type="text/javascript" language="javascript">
                            window.setTimeout(document.frmPaymentRedirect.submit(), 5000);
                        </script>
                    <? else: ?>
                        
                        <?php if( isset( $succes ) ): ?>
                            <p class="alert alert-success">
                                <?=$succes?>
                            </p>
                        <? endif ?>

                        <?php if( isset( $error ) ): ?>
                            <p class="alert alert-error">
                                <?=$error?>
                            </p>
                        <? endif ?>

                        <?php if( validation_errors()!=""):?>
                            <div class="alert alert-error"><?=validation_errors()?></div><br />
                        <? endif ?>
                        
                        <?php if( !empty( $cart ) && !isset( $_GET['id_tip_plata'] ) ): ?>
                            <form action="<?=site_url("cos/salveaza")?>" method="post" id="form-cos">
                                <div class="cos">
                                    <table class="fp">
                                        <thead>
                                            <tr>
                                                <td>Imagine</td>
                                                <td>Produs</td>
                                                <td>Cantitate</td>
                                                <td>Pret</td>
                                                <td>Total</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach( $cart as $item ): ?>
                                                <tr>
                                                    <td class="imagine-cos">              
                                                        <input type="hidden" name="rowid[]" value="<?=$item['rowid']?>" />
                                                        
                                                        <?php if( $item['imagine']!="default.png" ): ?>  
                                                            <a href="<?=base_url() . MAINSITE_STYLE_PATH?>images/produse/<?= $item['imagine'] ?>" title="<?= $item['name'] ?>" rel="prettyPhoto[galerie]">
                                                        <? endif ?>

                                                            <img src="<?=base_url() . MAINSITE_STYLE_PATH?>images/produse/85x85/<?= $item['imagine'] ?>" alt="<?= $item['name'] ?>" />

                                                        <?php if( $item['imagine']!="default.png" ): ?>  
                                                            </a>
                                                        <? endif ?>
                                                    <td>
                                                        <a href="<?= $item['furl'] ?>" title="<?= $item['name'] ?>"><?= $item['name'] ?></a>
                                                    </td>

                                                    <td class="cantitate">
                                                        <input type="text" name="qty[]" value="<?= $item['qty'] ?>" id="qty_<?=$item['rowid']?>" class="qty_input_cos" size="1" value="1"> &nbsp; <input name="salveaza" value="update" type="image" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/update.png"> &nbsp; <a href="<?=site_url("cos/sterge/{$item['rowid']}")?>"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/remove.png"></a>
                                                    </td>

                                                    <td><?= $item['price'] ?></td>

                                                    <td><?= $item['subtotal'] ?></td>
                                                </tr>
                                            <? endforeach ?>
                                        </tbody>
                                    </table>
									
									
									
									<table class="fp-respo">
                                        <thead>
                                            <tr>
                                                <td>Imagine</td>
                                                <td>Produs</td>
                                                <td>Cant.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach( $cart as $item ): ?>
                                                <tr>
                                                    <td class="imagine-cos">              
                                                        <input type="hidden" name="rowid[]" value="<?=$item['rowid']?>" />
                                                        
                                                        <?php if( $item['imagine']!="default.png" ): ?>  
                                                            <a href="<?=base_url() . MAINSITE_STYLE_PATH?>images/produse/<?= $item['imagine'] ?>" title="<?= $item['name'] ?>" rel="prettyPhoto[galerie]">
                                                        <? endif ?>

                                                            <img src="<?=base_url() . MAINSITE_STYLE_PATH?>images/produse/85x85/<?= $item['imagine'] ?>" alt="<?= $item['name'] ?>" />

                                                        <?php if( $item['imagine']!="default.png" ): ?>  
                                                            </a>
                                                        <? endif ?>
                                                    <td>
                                                        <a href="<?= $item['furl'] ?>" title="<?= $item['name'] ?>"><?= $item['name'] ?></a>
                                                    </td>

                                                    <td class="cantitate">
                                                        <input type="text" name="qty[]" value="<?= $item['qty'] ?>" id="qty_<?=$item['rowid']?>" class="qty_input_cos" size="1" value="1"> <br /> <input name="salveaza" value="update" type="image" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/update.png"> &nbsp; <a href="<?=site_url("cos/sterge/{$item['rowid']}")?>"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/remove.png"></a>
                                                    </td>

                                                    
                                                </tr>
                                            <? endforeach ?>
                                        </tbody>
                                    </table>
									
									
									
									<table class="fp-respo">
                                        <thead>
                                            <tr>
                                                
                                                <td>Pret</td>
                                                <td>Total</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach( $cart as $item ): ?>
                                                <tr>
                                                    
                                                    <td><?= $item['price'] ?></td>

                                                    <td><?= $item['subtotal'] ?></td>
                                                </tr>
                                            <? endforeach ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="cos-optiuni">

                                    <div class="three-col">

                                        <div>
                                            <h3>Cupon/cod reducere</h3>
                                            <p>Ai un cupon de reduceri?</p>
                                            <div class="cos-form">
                                                <div id="frm_add_voucher" style="display: <?= !isset( $voucher ) || empty( $voucher ) ? "block" : "none"?>">
                                                    <input type="text" name="cod_voucher" />
                                                    <input type="button" class="adauga-voucher" name="salveaza_voucher" class="adauga-cupon" value="Adauga cupon">
                                                </div>
                                                <div id="frm_delete_voucher" style="display: <?= isset( $voucher ) && !empty( $voucher ) ? "block" : "none" ?>">
                                                    <input type="button" class="sterge-voucher" name="sterge_voucher" value="Sterge voucherul">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="three-col">
                                        <div>

                                            <div class="cos-form">
                                                    <label>Modalitate de expediere</label>
                                                    <?=form_dropdown('id_curier', $options_curieri, (isset($id_curier) && $id_curier!="" ? $id_curier : ( isset( $_POST['id_curier'] ) ? $_POST['id_curier'] : "" )), "id='expediere'")?>

                                                    <label>Metoda de plata</label>
                                                    <?=form_dropdown('id_tip_plata', $options_plata, (isset($id_tip_plata) && $id_tip_plata!="" ? $id_tip_plata : ( isset( $_POST['id_tip_plata'] ) ? $_POST['id_tip_plata'] : 1 )), "id='expediere'")?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="three-col">
                                        <div>

                                            <div class="cos-form">
                                                    <label>Detalii comanda</label>
                                                    <textarea name="mesaj"><?=$mesaj?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="cos-optiuni">

                                    <div class="finalizare-comanda">
                                        <p class="subtotal">Pret produse: <span class="cos-pret"><?= $subtotal ?> lei</span></p>
                                        <?php if( $cota_tva>0 ): ?> 
                                            <p class="subtotal">TVA <?=$cota_tva?>%: <span class="cos-pret">+ <?=$tva?> lei</span></p>
                                         <? endif ?>
                                            
                                        <?php if( isset( $valoare_discount_fidelitate ) && $valoare_discount_fidelitate!=0 ): ?> 
                                            <p class="subtotal">Discount fidelitate <?=$discount_fidelitate?>%: <span class="cos-pret"><?=$valoare_discount_fidelitate?> lei</span></p>
                                         <? endif ?>
                                        
                                        <div id="discount_plata_op">
                                            <?php if( isset( $valoare_discount_plata_op ) && $valoare_discount_plata_op!=0 ): ?> 
                                                <p class="subtotal">Discount discount plata in avans <?= $discount_plata_op ?>%: <span class="discount"><?=$valoare_discount_plata_op?> lei</span></p>
                                             <? endif ?>
                                        </div>
                                            
                                        <div id="voucher_cos">
                                            <?php if( isset( $voucher ) && !empty( $voucher ) ): ?>
                                                <p class="subtotal">Cod cupon/discount <?= $voucher['cod'] ?>: <span class="discount"><?= $valoare_voucher ?> lei</span></p>
                                            <? endif ?>
                                        </div>
                                        
                                        <p class="subtotal">Transport: <span class="cos-pret" id="taxa_expediere"><?=$taxa_expediere?> lei</span></p>
                                        <p class="total">Total: <span class="cos-pret" id="total_cos"><?= $total ?> lei</span></p>
                                        <p>
                                            <button name="trimite" class="buton-cos" onclick="return confirm('Esti sigur ca vrei sa finalizezi comanda?')">Finalizeaza comanda</button>
                                            <button value="update" name="salveaza" class="buton-cos">Salveaza</button>
                                            <button name="goleste" class="buton-cos">Goleste</button>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        <? elseif( !isset( $succes ) ): ?>
                            <p class="alert alert-warning">Nu exista nici un produs in cos.</p>
                        <? endif ?>
                    <? endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
