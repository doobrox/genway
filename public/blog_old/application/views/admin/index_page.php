<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>

<article class="module width_half">
    <header><h3 class="tabs_involved">Ultimele 5 comenzi</h3></header>
    <?php if( !empty( $comenzi ) ): ?>
        <table class="tablesorter" cellspacing="0"> 
            <thead> 
                <tr> 
                    <th>Nr.factura</th> 
                    <th>Data comada</th> 
                    <th>Nume client</th> 
                    <th>Valoare</th> 
                    <th>Stare</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php foreach( $comenzi as $item ): ?>
                    <tr> 
                        <td>#<?= $item['nr_factura'] ?></td> 
                        <td><?= anchor($item['furl'], $item['data_adaugare']) ?></td> 
                        <td><?= $item['nume_client'] ?></td> 
                        <td><?= $item['valoare'] ?></td> 
                        <td><?= $item['text_stare'] ?></td> 
                    </tr> 
                <? endforeach ?>
            </tbody> 
        </table>
    <? else: ?>
        <h4 class="alert_warning" style="margin: 0 0 5px 5px">
            Nu exista nici o comanda :(
        </h4>
    <? endif ?>

</article><!-- end of content manager article -->

<article class="module width_half">
    <header><h3 class="tabs_involved">Stoc mic (<5)</h3></header>
    
    <?php if( !empty( $stoc_limitat ) ): ?>
        <table class="tablesorter" cellspacing="0"> 
            <thead> 
                <tr> 
                    <th>ID</th> 
                    <th>Produs</th> 
                    <th>Stoc</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php foreach( $stoc_limitat as $item ): ?>
                    <tr> 
                        <td><?= $item['id'] ?></td> 
                        <td><?= anchor("admin/produse/editeaza/{$item['id']}", $item['nume']) ?></td> 
                        <td><?= $item['stoc'] ?></td> 
                    </tr> 
                <? endforeach ?> 
            </tbody> 
        </table>
    <? else: ?>
        <h4 class="alert_warning" style="margin: 0 0 5px 5px">
            Nu exista nici un produs cu stoc mic.
        </h4>
    <? endif ?>

</article><!-- end of content manager article -->
