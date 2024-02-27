<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Factura</title>
        <style type="text/css">
            body {
                font-family:arial;
            }
            .table_main {
                border:1px solid black;
            }
            .border-bottom-right {
                border-bottom:  1px solid black;
                border-right: 1px solid black;
            }
            .border-bottom {
                border-bottom:  1px solid black;
            }
            .border-right {
                border-right: 1px solid black;
            }
            .border-top {
                border-top: 1px solid black;
            }
        </style>
    </head>
    <body>
        <table width="100%" cellspacing="0" cellpadding="0" class="table_main">
            <tr>
                <td width="70%"  class="border-bottom"><h1 style="padding:0; margin:0">FACTURA PROFORMA #<?=$comanda['nr_factura']?></h1>
                    <p style="font-size:12px; padding-left:5px;">
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
                    </p>
                </td>
                <td width="30%"  class="border-bottom" valign="top">
                    <p style="font-size:12px; padding-right:5px;">
                        Nr Factura: <?=$comanda['nr_factura']?><br />
                        Data Comanda: <?=$comanda['data_adaugare']?><br /><br />

                        <strong>Furnizor:</strong><br />
                        <?=setare('FACTURARE_NUME_FIRMA')?><br />
                        <?=setare('FACTURARE_ADRESA')?><br />
                        Loc. <?=setare('FACTURARE_LOCALITATE')?>, Jud. <?=setare('FACTURARE_JUDET')?><br />
                        CUI: <?=setare('FACTURARE_CUI')?> - Nr.reg.com. <?=setare('FACTURARE_NR_REG_COMERT')?><br />
                        Cont: <?=setare('FACTURARE_CONT_BANCA')?> - <?=setare('FACTURARE_BANCA')?>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                   <table width="100%" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <td class="border-bottom-right"><strong>Nume produs</strong></td>
                            <td class="border-bottom-right"><strong>Cod</strong></td>
                            <td class="border-bottom-right"><strong>Pret</strong></td>
                            <td class="border-bottom-right"><strong>Cantitate</strong></td>
                            <td class="border-bottom"><strong>Total</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $items as $item ): ?>
                            <tr class="div-row-cos-cumparaturi">
                                <td class="border-bottom-right">
                                    <?= $item['nume'] ?>
                                        <?php if( !empty( $item['filtre'] ) ): ?>
                                            <br />
                                            <?php foreach( $item['filtre'] as $filtru ): ?>
                                                <span style="padding-left: 20px">    
                                                    <strong><?=$filtru['nume_parinte']?></strong>: <?=$filtru['nume_filtru']?>
                                                </span><br />
                                            <? endforeach; ?>
                                        <? endif ?>
                                </td>
                                <td class="border-bottom-right"><?= $item['cod_ean13'] ?></td>
                                <td class="border-bottom-right"><?= $item['pret'] ?></td>
                                <td class="border-bottom-right">x <?= $item['cantitate'] ?></td>
                                <td class="border-bottom">= <?= $item['valoare'] ?></td>
                            </tr>
                        <? endforeach ?>   

                        <?php if( $comanda['tva']>0 ): ?>
                            <tr>
                                <td class="border-bottom-right"> </td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right"></td>
                                <td class="border-bottom-right">TVA <?=$comanda['tva']?>%</td>
                                <td class="border-bottom"><strong>+ <?=$comanda['valoare_tva']?></strong></td>
                            </tr>
                        <? endif ?>

                        <tr>
                            <td class="border-right">Modalitate expediere: <strong><?= $comanda['nume_curier'] ?></strong></td>
                            <td class="border-right"></td>
                            <td class="border-right"></td>
                            <td class="border-right"></td>
                            <td class="border-right"><strong><?= $comanda['taxa_livrare'] > 0 ? "+{$comanda['taxa_livrare']}" : 0 ?></strong></td>
                        </tr>
                        <tr>
                            <td class="border-top" colspan="3"></td>
                            <td class="border-top"><strong>Total</strong></td>
                            <td class="border-top"><strong><?= $comanda['valoare'] ?> lei</strong></td>
                        </tr>   
                    </tbody>
                </table>
                </td>
            </tr>
        </table>
        Plata se va face <?=$comanda['text_plata']?> catre <?=setare('FACTURARE_NUME_FIRMA')?>.
    </body>
</html>