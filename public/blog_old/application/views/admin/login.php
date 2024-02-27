<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Administrator</title>
	<link rel="stylesheet" href="<?=base_url().ADMIN_STYLE_PATH?>css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?=base_url().ADMIN_STYLE_PATH?>css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>


<body>

	<section id="main" class="column login-form">
		
		<?php if( isset( $errLogin ) ): ?>
                    <h4 class="alert_error"><?=$errLogin?></h4>
                <? endif ?>
		
                <form action="<?=base_url()?>admin/login/verificare" method="post">
                    <article class="module width_full">
                            <header><h3>Admin Login</h3></header>
                                    <div class="module_content">
                                                    <fieldset>
                                                            <label>Utilizator:</label>
                                                            <input type="text" name="user_email" style="width: 90%">
                                                    </fieldset>
                                                    <fieldset>
                                                            <label>Parola:</label>
                                                            <input type="password" name="user_pass" style="width: 90%">
                                                    </fieldset>
                                    </div>
                            <footer>
                                    <div class="submit_link">
                                            <input type="submit" value="Login" class="alt_btn">
                                    </div>
                            </footer>
                    </article><!-- end of post new article -->
                </form>
                    
		<div class="spacer"></div>
	</section>


</body>

</html>