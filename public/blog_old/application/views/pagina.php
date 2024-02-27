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

    <h2 class="pam-postheader"><a href="<?= site_url( "info/{$pagina['slug']}" ) ?>"><?= $pagina['titlu'] ?></a>
    </h2>

    <div class="pam-postcontent pam-postcontent-0 clearfix">
        <div class="pam-content-layout">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell layout-item-0" style="width: 100%">
                    <?= $pagina['continut'] ?>
                </div>
            </div>
        </div>
    </div>