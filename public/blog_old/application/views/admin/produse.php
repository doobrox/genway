<script type="text/javascript">
    $(function() {
        $("select[name='pret_multiplicator[]']").change(function(){
           var id_produs = $(this).attr("data-id-produs");
           if( $(this).val()!="0" ) {
                var multiplicator = parseFloat( $(this).val() );
                var pret_reseller = parseFloat( $("#pret_reseller_" + id_produs).val() );
                var pret_user = pret_reseller * multiplicator;
                
                if( !isNaN( pret_user ) ) {
                    $("#pret_user_" + id_produs ).val( pret_user.toFixed(2) );
                }
            } else {
                $("#pret_user_" + id_produs ).val( "" );
            }
        });
    });
</script>

<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>

<article class="module width_full">
    <header>
        <h3 class="tabs_involved">Cauta produse</h3>
    </header>

    <div class="tab_container">
        <form action="<?= base_url() ?>admin/produse/">
            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            <fieldset>
                                <label>Cauta</label>
                                <input type="text" name="q" value="<?=isset($_GET['q']) ? $_GET['q'] : ""?>" style="width: 87%" />
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding: 1.8%">
                                <label>dupa</label>
                                <?=form_dropdown('dupa', $options_dupa, (isset( $_POST['dupa'] ) ? $_POST['dupa'] : (isset($item['dupa']) ? $item['dupa'] : "")))?>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding: 1.8%">
                                <label>din categoria</label>
                                <select name="id_categorie">
                                    <option value="0">TOATE</option>
                                    <?=$categorii_tree?>
                                </select>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <fieldset style="padding: 1.8%" class="select_short">
                                <label>stoc</label>
                                <?=form_dropdown("stoc", $options_stoc, isset($_GET['stoc']) ? $_GET['stoc'] : "" )?>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding: 1.8%" class="select_short">
                                <label>reducere</label>
                                <?=form_dropdown("reducere", $options_reducere, isset($_GET['reducere']) ? $_GET['reducere'] : "" )?>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset style="padding: 1.8%" class="select_short_short">
                                <label>promovat</label> <br /><br />
                                <input type="checkbox" name="promovat" value="1" <?=isset($_GET['promovat']) ? "checked" : ""?> /> DA
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right">
                            <input type="submit" value="Cauta" class="alt_btn" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div><!-- end of .tab_container -->
</article>
        
<?php if( isset( $tabelDate ) ): ?>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Produse</h3>
        </header>

        <div class="tab_container">
                <div class="tab_content" id="tab1" >
                    <form action="" method="post">
                        <?=$tabelDate?>
                    </form>
                    <?php if( isset( $pagination ) ): ?>
                        <div class="pagination">
                                <?=$pagination?>
                        </div>
                    <? endif ?>
                </div><!-- end of #tab1 -->

        </div><!-- end of .tab_container -->

    </article>
<? endif ?>
