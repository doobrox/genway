<div class="pam-postcontent pam-postcontent-0 clearfix">

    <?php if (isset($breadcrumbs)): ?>

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



    <?php if (isset($titlu_h1)): ?>

        <div class="pam-content-layout layout-item-0">

            <div class="pam-content-layout-row">

                <div class="pam-layout-cell layout-item-1" style="width: 100%" >

                    <h2><?= $titlu_h1 ?></h2>

                </div>

            </div>

        </div>

    <? endif ?>



    <?php if (isset($categorie) && $categorie['descriere'] != ""): ?>

        <p><?= $categorie['descriere'] ?></p>

    <? endif ?>



    <?php if (isset($categorie)): ?>

        <?php if (isset($subcategorii) && !empty($subcategorii)): ?>

            <?php foreach ($subcategorii as $subcat): ?>

                <?php if ($subcat['k'] % 5 == 0): ?>

                    <?php if ($subcat['k'] > 0): ?>

                    </div>

                    </div>

                <? endif ?>

                <?php if (!isset($subcat['ultim']) || $subcat['k'] == 0): ?>

                    <div class="pam-content-layout layout-item-0">

                        <div class="pam-content-layout-row">

                        <? endif ?>

                    <? endif ?>

                    <div class="pam-layout-cell layout-item-4" style="width: 20%" >

                        <div class="categorie">

                            <div class="categorie-img"> 

                                <p>

                                    <a href="<?= $subcat['furl'] ?>" title="<?= $subcat['nume'] ?>">

                                        <img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorii/<?= $subcat['imagine'] ?>" alt="<?= $subcat['nume'] ?>" width="70" height="70" />

                                    </a>

                                </p>

                            </div>

                            <div class="categorie-info"><h4><a href="<?= $subcat['furl'] ?>" title="<?= $subcat['nume'] ?>"><?= $subcat['nume'] ?></a></h4></div>

                        </div>

                    </div>

                <? endforeach; ?>

            </div>

        </div>

    <? endif ?>

<? endif ?>



<div class="pam-content-layout-wrapper layout-item-0">

    <div class="pam-content-layout layout-item-4">

        <div class="pam-content-layout-row">

            <form action="" id="form-sortare">

                <div class="pam-layout-cell" style="width: 33%" >

                    <div class="filtrare">

                        <label>Sorteaza dupa</label>

                        <?= form_dropdown('sortare_dupa', $options_sortare_dupa, (isset($_REQUEST['sortare_dupa']) ? $_REQUEST['sortare_dupa'] : (isset($sortare_dupa) ? $sortare_dupa : "-1")), "onchange='getElementById(\"form-sortare\").submit()'") ?>

                    </div>

                </div>



                <div class="pam-layout-cell" style="width: 34%" >

                    <div class="filtrare">

                        <label>Doar cu reducere?</label>

                        <input type="checkbox" value="1" name="reducere" <?= isset($_REQUEST['reducere']) ? "CHECKED" : "" ?> onchange="getElementById('form-sortare').submit()" /> 

                    </div>

                </div>



                <div class="pam-layout-cell" style="width: 33%" >

                    <div class="filtrare">

                        <label>Afiseaza</label>

                        <?= form_dropdown('afisare', $options_afisare, (isset($_REQUEST['afisare']) ? $_REQUEST['afisare'] : ""), "onchange='getElementById(\"form-sortare\").submit()'") ?>

                    </div>

                </div>

            </form>

        </div>

    </div>



    <?php if (isset($items) && !empty($items)): ?>

        <div class="pam-content-layout layout-item-2">

            <?php foreach ($items as $item): ?>

                <?php if ($item['k'] % 4 == 0): ?>

                    <?php if ($item['k'] > 0): ?>

                    </div>

                <? endif ?>

                <?php if (!isset($item['ultim']) || $item['k'] == 0): ?>

                    <div class="pam-content-layout-row">

                    <? endif ?>

                <? endif ?>



                <div class="pam-layout-cell produs" style="width: 25%" >

                    <div class="produs-img">

                        <a href="<?= $item['furl'] ?>" title="<?= $item['nume'] ?>">

                            <img width="85" height="85" alt="<?= $item['nume'] ?>" src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/produse/85x85/<?= $item['imagine'] ?>" />

                        </a>

                    </div>



                    <div class="produs-info">

                        <div class="prod-info-titlu"><h3><a href="<?= $item['furl'] ?>" title="<?= $item['nume'] ?>"><?= $item['nume'] ?></a></h3></div>

                        <div class="prod-info-descriere">
                            <p>
                                <u>Cod produs: <?= $item['cod_ean13'] ?></u><br />
                                <?= $item['descriere'] ?>
                            </p>
                        </div>
                        <p>
                            <span class="pret">

                                <?php if (isset($item['pret_intreg_format'])): ?>

                                    <del><?= $item['pret_intreg_format'] ?> lei</del>

                                    <br />   

                                <? endif ?>    

                                <?= $item['pret_cu_tva'] ?> lei

                            </span>
                            <br />(TVA inclus)

                        </p>

                        <p>

                        <form action="<?= site_url('cos/adauga_produs') ?>" method="post" id="produs-<?= $item['id'] ?>">

                            <input type="hidden" name="id_produs" value="<?= $item['id'] ?>" />

                            <input type="hidden" name="cantitate" value="1" />

                            <a href="#" onclick="document.getElementById('produs-<?= $item['id'] ?>').submit();
                                                   return false;" class="adauga-in-cos">Adauga in cos</a>

                        </form>

                        </p>

                    </div>

                </div>       

            <? endforeach ?>

        </div>

    </div>

    <?php if (isset($pagination)): ?>

        <div class="pam-content-layout layout-item-6">

            <div class="pam-content-layout-row">

                <div class="pam-layout-cell layout-item-8" style="width: 100%" >

                    <div class="paginare-doi">

                        <?= $pagination ?>

                        <div class="paginare-rezultate"><?= $pagination_text ?></div>

                    </div>

                </div>

            </div>

        </div>

    <? endif ?>

<? else: ?>

    <strong><em>Nici un produs gasit. Mariti aria de cautare.</em></strong>

<? endif ?>



</article>

</div>