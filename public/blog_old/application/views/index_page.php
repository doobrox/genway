                                                

<div class="pam-postcontent pam-postcontent-0 clearfix">

    <div class="pam-content-layout layout-item-0">

        <div class="pam-content-layout-row">

            <div class="pam-layout-cell layout-item-1" style="width: 100%" >

                

                <?php if( count($bannere)==1 ): ?>

                    <a href="<?= $bannere[0]['link'] ?>" title="<?= $bannere[0]['titlu'] ?>" target="<?= $bannere[0]['target'] ?>">

                        <img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/bannere/<?= $bannere[0]['imagine'] ?>" alt="<?= $bannere[0]['titlu'] ?>" title="<?= $bannere[0]['titlu'] ?>" />

                    </a>

                 <? else: ?>

                    <?php if (!empty($bannere)): ?>

                        <div class="flexslider">

                            <ul class="slides">

                                <?php foreach ($bannere as $item): ?>

                                    <li>

                                        <a href="<?= $item['link'] ?>" title="<?= $item['titlu'] ?>" target="<?= $item['target'] ?>">

                                            <img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/bannere/<?= $item['imagine'] ?>" alt="<?= $item['titlu'] ?>" title="<?= $item['titlu'] ?>" />

                                        </a>

                                    </li>

                                <? endforeach ?>

                            </ul>

                        </div>

                    <? endif ?>

                <? endif ?>

            </div>

        </div>

    </div>

    <div class="pam-content-layout layout-item-2">

        <div class="pam-content-layout-row">



           <div style="width: 25%" class="pam-layout-cell layout-item-3-home">



                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/interfoane-audio.jpg"></p></div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>interfoane-audio/">Interfoane <br />audio</a></h3>

                    </div>



                </div>



            </div>
			
			 <div class="pam-layout-cell layout-item-3-home" style="width: 25%" >



                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/interfoane-video.jpg"></p></div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>interfoane-video/">Interfoane <br />video</a></h3>

                    </div>



                </div>



            </div>



            <div class="pam-layout-cell layout-item-3-home" style="width: 25%" >

                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/incuietori-si-accesorii.jpg"></p> </div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>accesorii/">Incuietori si <br />accesorii</a></h3>

                    </div>



                </div>

            </div>



            <div class="pam-layout-cell layout-item-3-home" style="width: 25%" >

                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/sisteme-de-alarma.jpg"></p></div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>efracti/">Sisteme de <br />alarma</a></h3>


                    </div>



                </div>

            </div>



        </div>
		
		

    </div>
	
	<div class="pam-content-layout layout-item-2">

        <div class="pam-content-layout-row">



           <div style="width: 25%" class="pam-layout-cell layout-item-3-home">



                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/supraveghere-video.jpg"></p></div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>cctv/">Supraveghere <br />video</a></h3>


                    </div>



                </div>



            </div>
			
			 <div class="pam-layout-cell layout-item-3-home" style="width: 25%" >



                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/automatizari-porti.jpg"></p></div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>automatizari-porti/">Automatizari <br />porti</a></h3>


                    </div>



                </div>



            </div>



            <div class="pam-layout-cell layout-item-3-home" style="width: 25%" >

                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/sisteme-solare.jpg"></p> </div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>sisteme-solare/">Sisteme <br />solare</a></h3>


                    </div>



                </div>

            </div>



            <div class="pam-layout-cell layout-item-3-home" style="width: 25%" >

                <div class="featured">

                    <div class="featured-img"> <p align="center"><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/sisteme-de-iluminat.jpg"></p></div>

                    <div class="featured-info fi-blue">

                        <h3><a href="<?= base_url() ?>iluminat/">Sisteme de <br />iluminat</a></h3>

                    </div>



                </div>

            </div>



        </div>
		
		

    </div>

   <!-- <div class="pam-content-layout layout-item-0">

        <div class="pam-content-layout-row">



            <div class="pam-layout-cell layout-item-4" style="width: 20%" >

                <div class="categorie">

                    <div class="categorie-img"> 

                        <p><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorie-img.png"></p>

                    </div>

                    <div class="categorie-info"><h4><a href="#">Titlu categorie</a></h4></div>

                </div>

            </div>



            <div class="pam-layout-cell layout-item-4" style="width: 20%" >

                <div class="categorie">

                    <div class="categorie-img"> 

                        <p><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorie-img.png"></p>

                    </div>

                    <div class="categorie-info"><h4><a href="#">Titlu categorie</a></h4></div>

                </div>

            </div>



            <div class="pam-layout-cell layout-item-4" style="width: 20%" >

                <div class="categorie">

                    <div class="categorie-img"> 

                        <p><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorie-img.png"></p>

                    </div>

                    <div class="categorie-info"><h4><a href="#">Titlu categorie</a></h4></div>

                </div>

            </div>



            <div class="pam-layout-cell layout-item-4" style="width: 20%" >

                <div class="categorie">

                    <div class="categorie-img"> 

                        <p><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorie-img.png"></p>

                    </div>

                    <div class="categorie-info"><h4><a href="#">Titlu categorie</a></h4></div>

                </div>

            </div>



            <div class="pam-layout-cell layout-item-4" style="width: 20%" >

                <div class="categorie">

                    <div class="categorie-img"> 

                        <p><img src="<?= base_url() . MAINSITE_STYLE_PATH ?>images/categorie-img.png"></p>

                    </div>

                    <div class="categorie-info"><h4><a href="#">Titlu categorie</a></h4></div>

                </div>

            </div>



        </div>

    </div> -->



    <?php if (isset($produse_promovate) && !empty($produse_promovate)): ?>

        <div class="pam-content-layout-wrapper layout-item-5">



            <div class="pam-content-layout layout-item-2">

                <div class="pam-content-layout-row">

                    <div class="pam-layout-cell" style="width: 100%" >

                        <h2>Produse recomandate</h2>

                    </div>

                </div>

            </div>



        </div>



        <div class="pam-content-layout layout-item-2">

            <?php foreach ($produse_promovate as $item): ?>

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

                        <div class="prod-info-descriere"><p><?= $item['descriere'] ?></p></div>

                        <p>

                            <span class="pret">

                                <?php if (isset($item['pret_intreg_format'])): ?>

                                    <del><?= $item['pret_intreg_format'] ?> lei</del>

                                    <br />   

                                <? endif ?>    

                                <?= $item['pret'] ?> lei

                            </span>

                        </p>

                        <p>

                        <form action="<?= site_url('cos/adauga_produs') ?>" method="post" id="produs-<?= $item['id'] ?>">

                            <input type="hidden" name="id_produs" value="<?= $item['id'] ?>" />

                            <input type="hidden" name="cantitate" value="1" />

                            <a href="#" onclick="document.getElementById('produs-<?= $item['id'] ?>').submit(); return false;" class="adauga-in-cos">Adauga in cos</a>

                        </form>

                        </p>

                    </div>

                </div>       

            <? endforeach ?>

        </div>

    </div>

<? endif ?>



<div class="pam-content-layout-wrapper layout-item-5">



    <div class="pam-content-layout layout-item-2">

        <div class="pam-content-layout-row">

            <div class="pam-layout-cell" style="width: 100%" >

                <h2><?= $pagina['titlu'] ?></h2>

            </div>

        </div>

    </div>



</div>



<div class="pam-content-layout layout-item-2">



    <div class="pam-content-layout-row">

        <?= $pagina['continut'] ?>

    </div>

</div>



</div>







