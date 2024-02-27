
</article>
</div>
</div>
</div>
</div>
</div>
<footer class="pam-footer">
    <div class="pam-footer-inner">
        <div class="pam-content-layout">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell" style="width: 100%">
                    <p>
                        <a href="<?= base_url() ?>">Acasa</a> | 
                        
                        <?php foreach( $pagini_footer as $pag ): ?>
                            <a href="<?=$pag['furl']?>" title="<?=$pag['titlu']?>" <?=$pag['target']?>><?=$pag['titlu']?></a> &nbsp;| 
                        <? endforeach ?>
                            
                        <a href="<?= site_url('contact') ?>">Contact</a>
                    </p>
                    <p>Copyright &copy; <?= date('Y') ?> <?= setare("TITLU_NUME_SITE") ?>. Toate drepturile sunt rezervate.</p>
                </div>
            </div>
        </div>

        <p class="pam-page-footer">
            <span id="pam-footnote-links">Site creat de <a href="http://www.pamdesign.ro" target="_blank">PAM Design</a>.</span>
        </p>
    </div>
</footer>

</div>

 <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>')</script>

  <!-- FlexSlider -->
  <script defer src="<?= base_url() . MAINSITE_STYLE_PATH ?>js/jquery.flexslider.js"></script>

  <script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
		itemWidth: 780,
			itemMargin: 0,
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
	

  </script>


</body></html>