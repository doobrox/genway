<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>
    
<?php if( isset( $categorii_tree ) ): ?>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Categorii</h3>
        </header>

        <div class="tab_container">
                <div class="tab_content" id="tab1" >
                    <form action="" method="post">
                        <div class="special_list">
                            <table cellspacing="0" class="tablesorter">
                                <thead>
                                    <th class="header" width="30" align="center">#</th>
                                    <th class="header" width="30">ID</th>
                                    <th class="header">Nume</th>
                                    <th class="header" width="60">Multiplicator</th>
                                    <th class="header" width="60">Ordonare</th>
                                    <th class="header" width="60">Publicat</th>
                                    <th class="header" width="60">Optiuni</th>
                                </thead>
                                <?=$categorii_tree?>
                                <tfoot>
                                    <tr>
                                     <td>
                                         <input type="checkbox" id="check_all" onclick="return updateCheckAll()" />
                                     </td>
                                     <td colspan="2">
                                         <input type="submit" name="sterge" value="Sterge" onclick="return confirm('Esti sigur ca vrei sa stergi categoriile selectate?')" />
                                     </td>
                                     <td colspan="5" align="right">
                                         <input type="submit" name="update_categorii" value="Salveaza" onclick="return confirm('Esti sigur ca vrei sa salvezi categoriile? ATENTIE: Preturile vor fi actualizate daca ati selectat vreo optiune din lista de multiplicatori!')" class="alt_btn" />
                                     </td>
                                 </tfoot>
                            </table>
                        </div>
                    </form>
                </div><!-- end of #tab1 -->

        </div><!-- end of .tab_container -->

    </article>
<? endif ?>
