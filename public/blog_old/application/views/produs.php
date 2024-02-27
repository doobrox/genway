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
                    <h2><?= $item['nume'] ?></h2>
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
                    </p>
                    <p class="comentariu"><a href="<?=$furl_adauga_comentariu?>" title="Scrie o parere despre <?= $item['nume'] ?>">Comenteaza despre produs</a></p>
					
                    <p class="info"><a href="<?= site_url('info/cum-cumpar') ?>" title="Cum cumpar?">Cum cumpar?</a></p>
                    <p class="info"><a href="<?= site_url('info/livrarea-comenzilor') ?>" title="Livrarea comenzilor">Livrarea comenzilor</a></p>
                </div>

                <div class="pam-layout-cell layout-item-6" style="width: 60%" >
                    <div class="descriere-produs">
                        <h3><?= $item['nume'] ?></h3>
                        <div class="descriere-produs-col">
                            <p><strong>Producator:</strong> <a href="<?=base_url()?>producator/<?= $item['slug_producator'] ?>" title="<?= $item['nume_producator'] ?>"><?= $item['nume_producator'] ?></a></p>
                            <p><strong>Cod produs:</strong> <span class="cod"><?= $item['cod_ean13'] ?></span></p>
                            <p><strong>Disponibilitate:</strong> <span class="<?= $item['stoc_class'] ?>"><?= $item['stoc_text'] ?></span></p>
                        </div>
                        <div class="descriere-produs-col2">
                            <p class="pret-produs">
                                <?= $item['pret_cu_tva'] ?>lei 
                                <?php if( isset( $item['pret_intreg_format'] ) ): ?>
                                    <span><?= $item['pret_intreg_format'] ?>lei</span>
                                <? endif ?>
                            </p>
                            <div class="livrare">
                                 <p><strong>TVA:</strong> inclus in pret</p>
                                <p><?=setare('DETALII_LIVRARE')?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if( isset( $item['fisiere_tehnice'] ) && !empty( $item['fisiere_tehnice'] ) ): ?>
                        <div class="descriere-produs">
                            <h4>Fisiere tehnice</h4>
                            <?php foreach( $item['fisiere_tehnice'] as $fisier ): ?>
                                <p><a href="<?= base_url() . MAINSITE_STYLE_PATH ?>images/produse/fisiere_tehnice/<?= $fisier['fisier'] ?>" target="_blank"><?= $fisier['titlu'] ?></a></p>
                            <? endforeach ?>
                        </div>
                    <? endif ?>
                    
                    <?php if( $item['stoc']>0 ): ?>
                        <div class="adaugare-in-cos">
                            <form action="<?=  site_url("cos/adauga_produs") ?>" method="post" id="form-adauga-cos">
                                <label>Cant:</label>
                                
                                <input type="text" class="qty" name="cantitate" value="1" />
                                <input type="hidden" name="id_produs" value="<?= $item['id'] ?>"/>
                                
                                <input type="button" id="btn-form-adauga-cos" value="Adauga in cos" />
                            </form>
                        </div>
                    <? endif ?>
                    <div class="rating">
                        <div class="rating-stars">
                            <img src="<?=base_url() . MAINSITE_STYLE_PATH?>images/star/star-2<?=$item['nota_medie']?>.png" alt="Nota <?= $item['nota_medie'] ?>" title="Nota <?= $item['nota_medie'] ?>" />
                        </div>
                        <div class="rating-links"><a href="<?=$furl_adauga_comentariu?>" title="Adauga un comentariu">Adauga un comentariu</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pam-content-layout-wrapper layout-item-3">
        <div class="pam-content-layout layout-item-4">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell" style="width: 100%" >
                    <h4 class="descriere"><?= $item['nume'] ?></h4>
                    
                    <?= $item['descriere'] ?>
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

                    <p><a href="<?=$furl_adauga_comentariu?>" title="Adauga un comentariu" class="adauga-un-comentariu">Adauga un comentariu</a></p>

                </div>
            </div>
        </div>
    </div>
    
    <?php if( !empty( $produse_recomandate ) ): ?>
        <div class="pam-content-layout-wrapper layout-item-3">
            <div class="pam-content-layout layout-item-4">
                <div class="pam-content-layout-row">
                    <div class="pam-layout-cell" style="width: 100%" >
                        <h4>Produse recomandate</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="pam-content-layout layout-item-4">
            <?php foreach( $produse_recomandate as $prod ): ?>
                <div class="pam-layout-cell produs" style="width: 25%" >
                    <div class="produs-img">
                        <a href="<?= $prod['furl'] ?>" title="<?= $prod['nume'] ?>">
                            <img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/produse/85x85/<?= $prod['imagine'] ?>" alt="<?= $prod['nume'] ?>" />
                        </a>
                    </div>

                    <div class="produs-info">
                        <h3><a href="<?= $prod['furl'] ?>" title="<?= $prod['nume'] ?>"><?= $prod['nume'] ?></a></h3>
                        <p><?= $prod['descriere'] ?></p>
                        <p>
                            <span class="pret">
                                <?php if( isset( $prod['pret_intreg_format'] ) ): ?>
                                    <del><?= $prod['pret_intreg_format'] ?> lei</del>
                                    <br />   
                                <? endif ?>    
                                <?= $prod['pret'] ?> lei    
                            </span>
                        </p>
                        <p>
                            <form action="<?= site_url('cos/adauga_produs') ?>" method="post" id="produs-<?= $prod['id'] ?>">
                                <input type="hidden" name="id_produs" value="<?= $prod['id'] ?>" />
                                <input type="hidden" name="cantitate" value="1" />
                               <a href="#" onclick="document.getElementById('produs-<?= $prod['id'] ?>').submit(); return false;" class="adauga-in-cos">Adauga in cos</a>
                            </form>
                        </p>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    <? endif ?>
</div>
