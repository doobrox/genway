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

                    <div class="istoric-comenzi cos">

                        <h2>Comanda #<?=$comanda['nr_factura']?></h2>



                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">Informatii comanda</td>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <b>Numar comanda:</b> #<?=$comanda['nr_factura']?><br />
                                        <b>Data adaugarii:</b> <?=$comanda['data_adaugare_f']?>
                                    </td>
                                    <td>
                                        <b>Tip plata:</b> <?=$comanda['tip_plata']?> <br />
                                        <b>Modalitate expediere:</b> <?=$comanda['nume_curier']?>
                                    </td>

                                </tr>

                            </tbody>
                        </table>

                        <table>
                            <thead>
                                <tr>
                                    <td>Beneficiar</td>
                                    <td>Furnizor</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php if( $user['tip']=="2" ): ?>
                                            <?= $user['nume_firma'] ?><br />
                                            CUI: <?= $user['cui'] ?><br />
                                            Nr. reg. comert.: <?= $user['nr_reg_comert'] ?><br />
                                        <? endif ?>

                                        <?= $user['nume'] ?> <?= $user['prenume'] ?><br />
                                        Email: <?= $user['user_email'] ?> <br />
                                        Telefon: <?= $user['telefon'] ?> <br />
                                        <br />

                                        <?php if( $user['livrare_adresa_1']==1 ): ?>
                                            <?= $user['livrare_adresa'] ?><br />
                                            Loc. <?= $user['nume_localitate'] ?><br />
                                            Jud. <?= $user['nume_judet'] ?><br />
                                        <? else: ?>
                                            <?= $user['adresa'] ?><br />
                                            Loc. <?= $user['nume_localitate'] ?><br />
                                            Jud. <?= $user['nume_judet'] ?><br />
                                        <? endif ?>
                                    </td>
                                    <td>
                                            <?=setare('FACTURARE_NUME_FIRMA')?><br />
                                            <?=setare('FACTURARE_ADRESA')?><br />
                                            Loc. <?=setare('FACTURARE_LOCALITATE')?>, Jud. <?=setare('FACTURARE_JUDET')?><br />
                                            CUI: <?=setare('FACTURARE_COD_FISCAL')?> - Nr.reg.com. <?=setare('FACTURARE_NR_REG_COMERT')?><br />
                                            Cont: <?=setare('FACTURARE_COD_FISCAL')?> - <?=setare('FACTURARE_BANCA')?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>


                        <h2>Factura proforma</h2>


                        <table class="fp">
                            <thead>
                                <tr>
                                    <td>Produs</td>
                                    <td>Cod</td>
                                    <td>Cantitate</td>
                                    <td>Pret</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $items as $item ): ?>
                                <tr>

                                    <td>
                                        <a href="<?= $item['furl'] ?>" title="<?= $item['nume'] ?>"><?= $item['nume'] ?></a>
                                    </td>
                                    <td>
                                        <?= $item['cod_ean13'] ?>
                                    </td>

                                    <td>
                                        <?= $item['cantitate'] ?>
                                    </td>

                                    <td>
                                        <?= $item['pret'] ?>
                                    </td>

                                    <td>
                                        <?= $item['valoare'] ?>
                                    </td>

                                </tr>
                                <? endforeach; ?>

                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <b>Subtotal</b>
                                    </td>
                                    <td>
                                        <?=$comanda['subtotal']?> lei
                                    </td>
                                </tr>
                                
                                <?php if( $comanda['valoare_discount_fidelitate']!=0 ): ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            <b>Discount fidelitate <?=$comanda['discount_fidelitate']?>%</b>
                                        </td>
                                        <td>
                                            <?=$comanda['valoare_discount_fidelitate']?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <?php if( $comanda['valoare_discount_plata_op']!=0 ): ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            <b>Discount plata in avans <?=$comanda['discount_plata_op']?>%</b>
                                        </td>
                                        <td>
                                            <?= $comanda['valoare_discount_plata_op'] ?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <?php if( $comanda['cod_voucher']!="" ): ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            <b>Cod cupon / discount:  <?=$comanda['cod_voucher']?></b>
                                        </td>
                                        <td>
                                            <?=$comanda['subtotal']?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <?php if( $comanda['tva']>0 ): ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            <b>TVA <?=$comanda['tva']?>%</b>
                                        </td>
                                        <td>
                                            + <?=$comanda['valoare_tva']?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <b>Modalitate expediere: <?=$comanda['nume_curier']?></b>
                                    </td>
                                    <td>
                                        <?=$comanda['taxa_livrare']>0 ? "+{$comanda['taxa_livrare']}" : 0?> lei
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="3"></td>
                                    <td>
                                        <b>Total</b>
                                    </td>
                                    <td>
                                        <?=$comanda['valoare']?> lei
                                    </td>
                                </tr>
                                

                            </tbody>
                        </table>
						
						<table class="fp-respo">
                            <thead>
                                <tr>
                                    <td>Produs</td>
                                    <td>Cod</td>
                                    <td>Cantitate</td>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $items as $item ): ?>
                                <tr>

                                    <td>
                                        <a href="<?= $item['furl'] ?>" title="<?= $item['nume'] ?>"><?= $item['nume'] ?></a>
                                    </td>
                                    <td>
                                        <?= $item['cod_ean13'] ?>
                                    </td>

                                    <td>
                                        <?= $item['cantitate'] ?>
                                    </td>

                                </tr>
                                <? endforeach; ?>

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
                                <?php foreach( $items as $item ): ?>
                                <tr>

                                    

                                    <td>
                                        <?= $item['pret'] ?>
                                    </td>

                                    <td>
                                        <?= $item['valoare'] ?>
                                    </td>

                                </tr>
                                <? endforeach; ?>

                                <tr>
                                   
                                    <td>
                                        <b>Subtotal</b>
                                    </td>
                                    <td>
                                        <?=$comanda['subtotal']?> lei
                                    </td>
                                </tr>
                                
                                <?php if( $comanda['valoare_discount_fidelitate']!=0 ): ?>
                                    <tr>
                                        
                                        <td>
                                            <b>Discount fidelitate <?=$comanda['discount_fidelitate']?>%</b>
                                        </td>
                                        <td>
                                            <?=$comanda['valoare_discount_fidelitate']?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <?php if( $comanda['valoare_discount_plata_op']!=0 ): ?>
                                    <tr>
                                        
                                        <td>
                                            <b>Discount plata in avans <?=$comanda['discount_plata_op']?>%</b>
                                        </td>
                                        <td>
                                            <?= $comanda['valoare_discount_plata_op'] ?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <?php if( $comanda['cod_voucher']!="" ): ?>
                                    <tr>
                                        
                                        <td>
                                            <b>Cod cupon / discount:  <?=$comanda['cod_voucher']?></b>
                                        </td>
                                        <td>
                                            <?=$comanda['subtotal']?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <?php if( $comanda['tva']>0 ): ?>
                                    <tr>
                                        
                                        <td>
                                            <b>TVA <?=$comanda['tva']?>%</b>
                                        </td>
                                        <td>
                                            + <?=$comanda['valoare_tva']?> lei
                                        </td>
                                    </tr>
                                <? endif ?>
                                
                                <tr>
                                    
                                    <td>
                                        <b>Modalitate expediere: <?=$comanda['nume_curier']?></b>
                                    </td>
                                    <td>
                                        <?=$comanda['taxa_livrare']>0 ? "+{$comanda['taxa_livrare']}" : 0?> lei
                                    </td>
                                </tr>
                                
                                <tr>
                                    
                                    <td>
                                        <b>Total</b>
                                    </td>
                                    <td>
                                        <?=$comanda['valoare']?> lei
                                    </td>
                                </tr>
                                

                            </tbody>
                        </table>

                        <p align="right"><a href="<?=base_url()?>application/controllers/facturi/factura<?=$comanda['nr_factura']?>.pdf"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/descarca-factura.png"</a></a>

                        <h2>Detalii factura</h2>

                        <table>
                            <thead>
                                <tr>
                                    <td>Data adaugarii</td>
                                    <td>Status</td>
                                    <td>Observatii</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <b><?=$comanda['data_adaugare_f']?></b>
                                    </td>
                                    <td>
                                        <b><?= $comanda['text_stare'] ?></b>
                                    </td>
                                    <td>
                                        <b><?= $comanda['mesaj'] ?></b>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                        <p align="right"><a href="<?= site_url('istoric_comenzi') ?>"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/inapoi.png"</a></a>


                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
