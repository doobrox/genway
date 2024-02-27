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

                    <div class="istoric-comenzi">

                        <h2>Istoric comenzi</h2>
                        <?php if( !empty( $comenzi ) ): ?>
                            <?php foreach( $comenzi as $item ): ?>
                                <div class="item-istoric">
                                    <div class="numar-comanda"><b>Numar comanda</b> <a href="<?= site_url("istoric_comenzi/comanda/{$item['id']}") ?>">#<?= $item['nr_factura'] ?></a></div>
                                    <div class="status-comanda"><b>Stare:</b> <?= $item['text_stare'] ?></div>
                                    <div class="continut-comanda">
                                        <div>
                                            <b>Data:</b> <?= $item['data_adaugare_f'] ?><br>
                                            <b>Valoare:</b> <?= $item['valoare'] ?> lei
                                        </div>
                                        <div>
                                            <b>Nr. produse:</b> <?= $item['nr_produse'] ?><br />
                                            <b>Stare plata:</b> <?= $item['stare_plata_text'] ?>
                                        </div>
                                        <div class="info-comanda">
                                            <a href="<?= site_url("istoric_comenzi/comanda/{$item['id']}") ?>">
                                                <img title="Vezi comanda" alt="Vezi comanda" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/informatii-comanda.png">
                                            </a>
                                            <a href="<?=base_url()?>istoric_comenzi/factura/<?=$item['nr_factura']?>" target="_blank">
                                                <img title="Descarca factura" alt="Descarca factura" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/factura-proforma.png">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <? endforeach ?>
                        <? else: ?>
                            <p class="alert alert-warning">
                                Nu exista nici o comanda.
                            </p>
                        <? endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
