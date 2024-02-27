<?php if( isset( $succes ) ): ?>
    <h4 class="alert_success"><?=$succes?></h4>
<? endif ?>
    
<?php if( isset( $warning ) ): ?>
    <h4 class="alert_warning"><?=$warning?></h4>
<? endif ?>
    
<?php if( isset( $error ) ): ?>
    <h4 class="alert_error"><?=$error?></h4>
<? endif ?>
    
<?php if( isset( $tabelDate ) ): ?>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved">Curieri</h3>
        </header>

        <div class="tab_container">
                <div class="tab_content" id="tab1" >
                    <form action="" method="post">
                        <?=$tabelDate?>
                    </form>
                </div><!-- end of #tab1 -->

        </div><!-- end of .tab_container -->

    </article>
<? endif ?>
