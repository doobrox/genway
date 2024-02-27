<link rel="stylesheet" type="text/css" href="<?=base_url().ADMIN_STYLE_PATH?>css/uploadify.css">

<script type="text/javascript" src="<?=base_url().ADMIN_STYLE_PATH?>js/jquery.uploadify-3.1.min.js"></script>

<script type="text/javascript">

$(function() {

    var i = 0;

    $('#file_upload').uploadify({

        'swf'      : '<?=base_url() . ADMIN_STYLE_PATH?>uploadify.swf',

        'uploader' : '<?=base_url()?>/admin/index_page/uploadify',

        'buttonImage'      : '<?=base_url() . ADMIN_STYLE_PATH?>images/adaugare-imagini.jpg',

        'width' : 180,

        'height' : 20,

        //'uploadLimit' : 6,

        'onUploadSuccess' : function(file, data, response) {

            $("#files").append( '<li class="error">' + 

                '<img src="<?=base_url().MAINSITE_STYLE_PATH?>images/produse/temp/' + file.name.toLowerCase() + '" /> <br />' + 

                file.name.toLowerCase() + '<br />' +

                '<label for="file-' + i + '"><input type="radio" name="principala" value="'+ i +'" id="file-' + i + '" /> principala</label>' + 

                '<input type="hidden" name="imagini[]" value="'+ file.name.toLowerCase() +'" />' + 

                '</li>' 

            );

            i++;

        } 

    });

    

    $("select[name='pret_multiplicator']").change(function(){

        if( $(this).val()!="0" ) {

            var multiplicator = parseFloat( $(this).val() );

            var pret_reseller = parseFloat( $("input[name='pret']").val() );

            var pret_user = pret_reseller * multiplicator;

            

            if( !isNaN( pret_user ) ) {

                $("input[name='pret_user']").val( pret_user.toFixed(2) );

            }

        } else {

            $("input[name='pret_user']").val("");

        }

    });

    

    $("input[name='pret_user']").keyup(function(){

        $("select[name='pret_multiplicator'] option[value='0']").attr("selected", true);

    });

});



function stergereImagineGalerie(id, imagine) {

    if( confirm('Esti sigur ca vrei sa stergi imaginea: ' + imagine) ) {

        $.get('<?=base_url()?>admin/index_page/stergere_imagine/' + id, function( data ){

            if( data==1 ) {

                $("#list-imagine-" + id).hide('slow', function(){ $("#list-imagine-" + id).remove(); });

            }

        });

    }

    

    return false;

}

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


<?php if( validation_errors()!="" ): ?>

    <h4 class="alert_error validation_errors"><?=validation_errors()?></h4>

<? endif ?>    



    <form action="<?=base_url()?>admin/produse/salveaza/<?=isset($item['id']) ? $item['id'] : ""?>" enctype="multipart/form-data" method="post"> 

    <article class="module width_full">

        <header><h3><?=isset($item['id']) ? "Editeaz&#259; produs" : "Adaug&#259; un nou produs"?></h3></header>

        <div class="module_content">

            <fieldset>

                <label>COD EAN13</label>

                <input type="text" name="cod_ean13" value="<?=isset( $_POST['cod_ean13'] ) ? set_value('cod_ean13') : (isset($item['cod_ean13']) ? $item['cod_ean13'] : "")?>" />

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset>

                <label>Nume</label>

                <input type="text" name="nume" value="<?=isset( $_POST['nume'] ) ? set_value('nume') : (isset($item['nume']) ? $item['nume'] : "")?>" />

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset>

                <label>Producator</label>

                <?=form_dropdown('id_producator', $options_producatori, (isset( $_POST['id_producator'] ) ? $_POST['id_producator'] : (isset($item['id_producator']) ? $item['id_producator'] : "")), "style='width: 90%'")?>

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset>

                <label>Descriere</label>

                <?=$editor?>

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset>

                <label>Categorii</label>

                <div class="list-categorii">

                    <?=isset( $categorii_tree ) ? $categorii_tree : ""?>

                </div>

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset> 

                <label>Greutate (kg)</label>

                <input type="text" name="greutate" value="<?=isset( $_POST['greutate'] ) ? set_value('greutate') : (isset($item['greutate']) ? $item['greutate'] : "")?>" style="width: 95%" /> <br clear="all" />

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset style="width:32%; float:left; margin-right: 1%;"> 

                <label>Pret reseller</label>

                <input type="text" name="pret" value="<?=isset( $_POST['pret'] ) ? set_value('pret') : (isset($item['pret']) ? $item['pret'] : "")?>" style="width: 85%" />

            </fieldset>

            <fieldset style="width:32%; float:left; margin-right: 1%;"> 

                <label>Multiplicator</label>

                <?=form_dropdown('pret_multiplicator', $options_pret_multiplicator, (isset( $_POST['pret_multiplicator'] ) ? $_POST['pret_multiplicator'] : (isset($item['pret_multiplicator']) ? $item['pret_multiplicator'] : "")), "style='width: 90%'")?>

            </fieldset>

            <fieldset style="width:32%; float:left;"> 

                <label>Pret utilizator normal</label>

                <input type="text" name="pret_user" value="<?=isset( $_POST['pret_user'] ) ? set_value('pret_user') : (isset($item['pret_user']) ? $item['pret_user'] : "")?>" style="width: 85%" />

            </fieldset>

        </div><div class="clear"></div>

        <div class="module_content">

            <fieldset style="width:32%; float:left; margin-right: 1%;"> 

                <label>Tip reducere</label>

                <?=form_dropdown('reducere_tip', $options_reducere_tip, (isset( $_POST['reducere_tip'] ) ? $_POST['reducere_tip'] : (isset($item['reducere_tip']) ? $item['reducere_tip'] : "")), "style='width: 90%'")?>

            </fieldset>

            <fieldset style="width:32%; float:left; margin-right: 2%;"> 

                <label>Valoare reducere</label>

                <input type="text" name="reducere_valoare" value="<?=isset( $_POST['reducere_valoare'] ) ? set_value('reducere_valoare') : (isset($item['reducere_valoare']) ? $item['reducere_valoare'] : "")?>" style="width: 85%" />

            </fieldset>

            <fieldset style="width:30%; float:left;"> 

                <label>Stoc</label>

                <input type="text" name="stoc" value="<?=isset( $_POST['stoc'] ) ? set_value('stoc') : (isset($item['stoc']) ? $item['stoc'] : "")?>" style="width: 85%" /> <br clear="all" />

                <div style="padding: 10px 0 0 10px; ">

                    <input type="checkbox" name="stoc_la_comanda" value="1" <?=isset( $_POST['stoc_la_comanda'] ) ? "CHECKED" : (isset($item['stoc_la_comanda']) && $item['stoc_la_comanda']==1 ? "CHECKED" : "")?>/><em>Stoc doar la comanda</em>

                </div>

            </fieldset>

        </div><div class="clear"></div>

        <div class="module_content">

            <fieldset>

                <label>Imagini</label>

                <br clear="all" />

                <div style="padding-left: 10px">

                    <input type="file" name="file_upload" id="file_upload" />

                    <ul id="files"></ul>

                    <div class="clear"></div>

                    <?php if( isset( $galerie ) ): ?>

                        <ul id="files">

                            <?php foreach( $galerie as $imagine ): ?>

                                <li id="list-imagine-<?=$imagine['id']?>">

                                    <img src="<?=base_url().MAINSITE_STYLE_PATH?>images/produse/<?=$imagine['fisier']?>" /> <br />

                                    <label for="imagine-<?=$imagine['id']?>">

                                        <input type="radio" name="principala" value="<?=$imagine['id']?>" id="imagine-<?=$imagine['id']?>" <?=$imagine['principala']==1 ? "CHECKED" : ""?>/> Principala

                                    </label><br />

                                    <a href="#" onclick="return stergereImagineGalerie( <?=$imagine['id']?>, '<?=$imagine['fisier']?>' )">[x] Sterge imaginea</a>

                                </li>

                            <? endforeach ?>

                        </ul>

                    <? endif ?>

                </div>

            </fieldset>

        </div>
        
        <div class="module_content">

            <fieldset>

                <label>Fisiere tehnice</label>

                <br clear="all" />

                <div style="padding-left: 10px">
                    <table style="font-size: 12px;">
                        <?php if( isset( $fisiere_tehnice ) && !empty( $fisiere_tehnice ) ): ?>
                            <?php foreach ( $fisiere_tehnice as $fisier ): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="ft_titlu[]" value="<?= $fisier['titlu'] ?>" />
                                    </td>
                                    <td>
                                        <a href="<?= base_url() . MAINSITE_STYLE_PATH ?>images/produse/fisiere_tehnice/<?= $fisier['fisier'] ?>" target="_blank"><?= $fisier['fisier'] ?></a>
                                    </td>
                                    <td>
                                        <input type="hidden" name="ft_id[]" value="<?= $fisier['id'] ?>" /> 
                                        <select name="ft_reseller[]">
                                            <option value="0">PUBLIC</option>
                                            <option value="1" <?= $fisier['reseller']==1 ? "SELECTED" : "" ?>>PRIVAT (DOAR RESELLERI)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="<?= site_url("admin/produse/sterge_fisier/{$item['id']}/{$fisier['id']}") ?>" ttile="Sterge fisier" style="color: red" onclick="return confirm('Esti sigur ca doresti sa stergi fisierul: <?= $fisier['fisier'] ?>?')">[x]</a>
                                    </td>
                                </tr>
                            <? endforeach ?>
                        <? endif ?>
                        <tr>
                            <td>
                                <input type="text" name="fisiere_tehnice_titlu[]" placeholder="Titlu fisier" />
                            </td>
                            <td>
                                <input type="file" name="fisiere_tehnice_fisier[]" />
                            </td>
                            <td>
                                <select name="fisiere_tehnice_reseller[]">
                                    <option value="0">PUBLIC</option>
                                    <option value="1">PRIVAT (DOAR RESELLERI)</option>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="fisiere_tehnice_titlu[]" placeholder="Titlu fisier" />
                            </td>
                            <td>
                                <input type="file" name="fisiere_tehnice_fisier[]" />
                            </td>
                            <td>
                                <select name="fisiere_tehnice_reseller[]">
                                    <option value="0">PUBLIC</option>
                                    <option value="1">PRIVAT (DOAR RESELLERI)</option>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="fisiere_tehnice_titlu[]" placeholder="Titlu fisier" />
                            </td>
                            <td>
                                <input type="file" name="fisiere_tehnice_fisier[]" />
                            </td>
                            <td>
                                <select name="fisiere_tehnice_reseller[]">
                                    <option value="0">PUBLIC</option>
                                    <option value="1">PRIVAT (DOAR RESELLERI)</option>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>

            </fieldset>

        </div>

        <div class="module_content prod_recomandate">

            <fieldset>

                <label>Produse recomandate</label>

                <div id="select_prod_recomandate"><?=form_dropdown('id_produs_recomandat[]', $options_produse_recomandate, "")?></div>

                <div id="id_produse_recomandate">

                    <?php if( isset( $id_produse_recomandate ) ): ?> 

                        <?php foreach( $id_produse_recomandate as $id_produs_recomandat ): ?>

                            <?=form_dropdown('id_produs_recomandat[]', $options_produse_recomandate, $id_produs_recomandat)?><br />

                        <? endforeach ?>

                    <? endif ?>

                            

                    <?=form_dropdown('id_produs_recomandat[]', $options_produse_recomandate, "")?><br />

                </div>

                <span style="padding-left: 10px">

                    <a href="#" onclick="return appendSelectProduseRecomandate()">+ Adauga alt produs recomandat</a>

                </span>

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset style="width:48%; float:left; margin-right: 3%;"> 

                <label>Promovat index</label>

                <?=form_dropdown('promovat_index', $options_promovat, (isset( $_POST['promovat_index'] ) ? $_POST['promovat_index'] : (isset($item['promovat_index']) ? $item['promovat_index'] : "")))?>

            </fieldset>

            <fieldset style="width:48%; float:left;"> 

                <label>Promotie</label>

                <?=form_dropdown('promotie', $options_promovat, (isset( $_POST['promotie'] ) ? $_POST['promotie'] : (isset($item['promotie']) ? $item['promotie'] : "")))?>

            </fieldset>

        </div><div class="clear"></div>

        <div class="module_content">

            <fieldset>

                <label>SEO Title</label>

                <input type="text" name="seo_title" value="<?=isset( $_POST['seo_title'] ) ? set_value('seo_title') : (isset($item['seo_title']) ? $item['seo_title'] : "")?>" />

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset>

                <label>SEO Meta description</label>

                <input type="text" name="meta_description" value="<?=isset( $_POST['meta_description'] ) ? set_value('meta_description') : (isset($item['meta_description']) ? $item['meta_description'] : "")?>" />

            </fieldset>

        </div>

        <div class="module_content">

            <fieldset>

                <label>SEO Meta keywords</label>

                <input type="text" name="meta_keywords" value="<?=isset( $_POST['meta_keywords'] ) ? set_value('meta_keywords') : (isset($item['meta_keywords']) ? $item['meta_keywords'] : "")?>" />

            </fieldset>

        </div>

        <footer>

            <div class="submit_link">

                Publicat: 

                <?=form_dropdown('activ', $options_activ, (isset( $_POST['activ'] ) ? $_POST['activ'] : (isset($item['activ']) ? $item['activ'] : "")))?>

                <input type="submit" value="Salveaz&#259;" class="alt_btn">

                <input type="reset" value="Reseteaz&#259;">

            </div>

        </footer>

    </article><!-- end of post new article -->

</form>