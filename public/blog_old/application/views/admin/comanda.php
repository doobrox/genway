<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>

<?php if( validation_errors()!="" ): ?>
    <h4 class="alert_error validation_errors"><?=validation_errors()?></h4>
<? endif ?>    

<?php if( !empty( $comanda ) ): ?>
    <article class="module width_full">
        <header><h3>Comanda #<?=$comanda['nr_factura']?> din <?=$comanda['data_adaugare']?></h3></header>
        <div class="module_content">
            <strong>
                <?=img(ADMIN_STYLE_PATH . "images/icn_invoice.png")?>
                <a href="<?=base_url()?>application/controllers/facturi/factura<?=$comanda['nr_factura']?>.pdf">Vezi factura proforma</a>
            </strong>
            <div style="float: right">
                Tip plata: <strong><?=$comanda['tip_plata']?></strong>
               <form action="<?=base_url()?>admin/comenzi/salveaza_plata/<?=$comanda['id']?>" method="post" onsubmit="return confirm('Esti sigur ca vrei sa schimbi starea platii?')">
                    Stare plata: <?=form_dropdown('stare_plata', $options_stare_plata, $comanda['stare_plata'])?>
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </form>
            </div>
        </div><br clear="all" />
        <div class="module_content">
            <table width="100%">
                <tr valign="top">
                    <td>
                        <div style="border: 1px solid #757575; padding: 10px">
                            <strong>Beneficiar: </strong> <br />
                            <?php if( $user['tip']=="2" ): ?>
                                <?= $user['nume_firma'] ?><br />
                                CUI: <?= $user['cui'] ?><br />
                                Nr. reg. comert.: <?= $user['nr_reg_comert'] ?><br />
                            <? endif ?>
                            <br />

                            <?= $user['nume'] ?> <?= $user['prenume'] ?><br />
                            Email: <?= $user['user_email'] ?> <br />
                            Telefon: <?= $user['telefon'] ?> <br /><br />

                            <?php if( $user['livrare_adresa_1']==1 ): ?>
                                <?= $user['livrare_adresa'] ?><br />
                                Loc. <?= $user['nume_localitate'] ?><br />
                                Jud. <?= $user['nume_judet'] ?><br />
                            <? else: ?>
                                <?= $user['adresa'] ?><br />
                                Loc. <?= $user['nume_localitate'] ?><br />
                                Jud. <?= $user['nume_judet'] ?><br />
                            <? endif ?>
                        </div>
                    </td>
                    <td>
                        <div style="border: 1px solid #757575; margin-left: 10px; padding: 10px">
                            <strong>Furnizor:</strong><br />
                            <?=setare('FACTURARE_NUME_FIRMA')?><br />
                            <?=setare('FACTURARE_ADRESA')?><br />
                            Loc. <?=setare('FACTURARE_LOCALITATE')?>, Jud. <?=setare('FACTURARE_JUDET')?><br />
                            CUI: <?=setare('FACTURARE_COD_FISCAL')?> - Nr.reg.com. <?=setare('FACTURARE_NR_REG_COMERT')?><br />
                            Cont: <?=setare('FACTURARE_COD_FISCAL')?> - <?=setare('FACTURARE_BANCA')?>
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" cellpadding="5" rules="all" border="1" style="margin: 10px 0">
                <thead>
                    <tr>
                        <td><strong>Nume produs</strong></td>
                        <td><strong>Cod</strong></td>
                        <td><strong>Pret</strong></td>
                        <td><strong>Cantitate</strong></td>
                        <td><strong>Total</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $items as $item ): ?>
                        <tr class="div-row-cos-cumparaturi">
                            <td>
                                <a href="<?= $item['furl'] ?>" title="<?= $item['nume'] ?>"><?= $item['nume'] ?></a>
                                <?php if( isset( $item['filtre'] ) ): ?>
                                    <br />
                                    <?php foreach( $item['filtre'] as $filtru ): ?>
                                    <span style="padding-left: 20px">    
                                        <strong><?=$filtru['nume_parinte']?></strong>: <?=$filtru['nume_filtru']?>
                                    </span><br />
                                    <? endforeach; ?>
                                <? endif ?>
                            </td>
                            <td><?= $item['cod_ean13'] ?></td>
                            <td><?= $item['pret'] ?> lei</td>
                            <td>x <?= $item['cantitate'] ?></td>
                            <td>= <?= $item['valoare'] ?></td>
                        </tr>
                    <? endforeach ?>   
                        
                    <?php if( $comanda['valoare_discount_fidelitate']!=0 ): ?>
                        <tr>
                            <td colspan="3">Discount fidelitate <strong><?= $comanda['discount_fidelitate'] ?>%</strong></td>
                            <td></td>
                            <td><strong><?= $comanda['discount_fidelitate'] ?> lei</strong></td>
                        </tr>
                    <? endif ?>    
                        
                    <?php if( $comanda['valoare_discount_plata_op']!=0 ): ?>
                        <tr>
                            <td colspan="3">Discount plata in avans <?=$comanda['discount_plata_op']?>%</strong></td>
                            <td></td>
                            <td><strong><?= $comanda['valoare_discount_plata_op'] ?> lei</strong></td>
                        </tr>
                    <? endif ?>    
                        
                    <?php if( $comanda['cod_voucher']!="" ): ?>
                        <tr>
                            <td colspan="3">Cod cupon / discount <strong><?= $comanda['cod_voucher'] ?></strong></td>
                            <td></td>
                            <td><strong><?= $comanda['valoare_voucher'] ?> lei</strong></td>
                        </tr>
                    <? endif ?>    
                    
                    <?php if( $comanda['tva']>0 ): ?>
                        <tr>
                            <td colspan="3"> </td>
                            <td>TVA <?=$comanda['tva']?>%</td>
                            <td><strong>+ <?=$comanda['valoare_tva']?> lei</strong></td>
                        </div>
                    <? endif ?>

                    <tr>
                        <td colspan="3">Modalitate expediere: <strong><?= $comanda['nume_curier'] ?></strong></td>
                        <td></td>
                        <td><strong><?= $comanda['taxa_livrare'] > 0 ? "+{$comanda['taxa_livrare']}" : 0 ?> lei</strong></td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td><strong>TOTAL</strong></td>
                        <td><strong><?= $comanda['valoare'] ?> lei</strong></td>
                    </tr>   
                </tbody>
            </table>

            <?php if( $comanda['mesaj']!="" ): ?>  
                <b>Alte detalii despre comanda:</b><br />
                <?=$comanda['mesaj']?>
            <? endif ?>

            <hr />
            <form action="<?=base_url()?>admin/comenzi/salveaza/<?=$comanda['id']?>" method="post" onsubmit="return confirm('Esti sigur ca vrei sa schimbi starea comenzii?')">
                <div class="module_content" style="float: left; width: 47%; margin-right: 1%">
                    <fieldset>
                        <label>Stare</label>
                        <?=form_dropdown('stare', $options_stare, $comanda['stare'])?><br />
                        <span style="font-size:10px; margin-left: 10px">NOTA: Daca starea comenzii este setata ca "EXPEDIATA" atunci starea platii se modifica automat in "PLATA CONFIRMATA"!</span>
                    </fieldset>
                    <fieldset>
                        <label>Mesaj optional</label>
                        <textarea name="mesaj_admin" rows="5" style="width: 90%"></textarea>
                    </fieldset>
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </div>
            </form>
            <form action="<?=base_url()?>admin/comenzi/salveaza_nota_interna/<?=$comanda['id']?>" method="post">
                <div class="module_content" style="float: right; width: 45%;">
                    <fieldset>
                        <label>Nota interna</label>
                        <textarea name="nota_interna" rows="5" style="width: 90%"><?= $comanda['nota_interna'] ?></textarea><br />
                        <span style="font-size:10px; margin-left: 10px">Aceasta nota este o nota interna, vizibila doar de catre admin.</span>
                    </fieldset>
                    <input type="submit" name="salveaza" value="Salveaza nota" class="btn" />
                </div>
            </form>
            <div class="clear"></div>
        </div>

        <br clear="all" />

    </article>
<? endif ?>
