<?php
if(isset($_POST['submit'])):
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
		//your site secret key
        $secret = '6LfYJ0AUAAAAABVQUX3GIVrFw_wTuKB-eJdBoKPt';
		//get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
		
		$name = !empty($_POST['name'])?$_POST['name']:'';
		$emailaddress = !empty($_POST['emailaddress'])?$_POST['emailaddress']:'';
		$phone = !empty($_POST['phone'])?$_POST['phone']:'';
		$subiectt = !empty($_POST['subiectt'])?$_POST['subiectt']:'';
		$message = !empty($_POST['message'])?$_POST['message']:'';
        if($responseData->success):
			//contact form submission code
			//$to == setare('EMAIL_CONTACT');
			$to ='notificari@genway.ro,adytzul89@gmail.com';
			$subject = 'Mesaj nou de pe Genway Romania';
			$htmlContent = "
				<div style='border:1px solid #ccc;padding:5px'>
				<strong>Salut,</strong><br/><br/>
				Acest mesaj este trimis de la adresa: www.genway.ro/contact<br/><br/>
					<p><b>Subiect: </b>".$subiectt."</p>
					<p><b>Nume: </b>".$name."</p>
					<p><b>Email: </b>".$emailaddress."</p>
					<p><b>Telefon: </b>".$phone."</p>
					<p><b>Mesaj: </b>".$message."</p><br/>
				<strong>O zi buna!</strong>
				<hr/>
				Ati primit acest email deoarece aceasta adresa de email a fost folosita la inscrierea pe genway.ro.
				Daca acest mesaj a ajuns din greseala va rugam sa ne <a href='http://www.genway.ro/contact'>contactati</a>.
				</div>
			";
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			// More headers
			$headers .= 'From:'.$name.' <'.$emailaddress.'>' . "\r\n";
			//send email
			@mail($to,$subject,$htmlContent,$headers);
			
            $succMsg = '<p style="border:1px solid green;padding:5px;">Mesajul dvs. a fost trimis cu success!</p>';
			$name = '';
			$emailaddress = '';
			$phone = '';
			$subiectt = '';
			$message = '';
        else:
            $errMsg = '<p style="border:1px solid #FF0000;padding:5px;">Eroare! Verificati si retrimiteti din nou..</p>';
        endif;
    else:
        $errMsg = '<p style="border:1px solid #FF0000;padding:5px;">Corectati reCaptcha pentru antispam</p>';
    endif;
else:
    $errMsg = '';
    $succMsg = '';
	$name = '';
	$emailaddress = '';
	$phone = '';
	$subiectt = '';
	$message = '';
endif;
?>

<?php if( isset( $breadcrumbs ) ): ?>
    <div class="pam-postcontent pam-postcontent-0 clearfix">
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
    </div>
<? endif ?>

<div class="pam-postcontent pam-postcontent-0 clearfix">
    <div class="pam-content-layout-wrapper layout-item-0">
        <div class="pam-content-layout layout-item-1">
            <div class="pam-content-layout-row">
                <div class="pam-layout-cell layout-item-2" style="width: 100%" >

                    <div class="cos">

                        <h2 class="page-head">Contact</h2>

                        <div class="contact-wrap">

                            <div class="contact-info">

                                <div class="contact-adresa">

                                    <h3><strong><?= setare('FACTURARE_NUME_FIRMA') ?></strong></h3>
                                    <p><?= setare('FACTURARE_ADRESA') ?>, <?= setare('FACTURARE_LOCALITATE') ?>, jud. <?= setare('FACTURARE_JUDET') ?></p>
                                    
                                    <?php if( setare('AFISARE_DATE_FIRMA')=="DA" ): ?>
                                        <p><strong>Cod Fiscal:</strong> <?= setare('FACTURARE_CUI') ?></p>
                                        <p><strong>Nr. reg. com.:</strong> <?= setare('FACTURARE_NR_REG_COMERT') ?></p>
                                        <p><strong>Capital social:</strong> <?= setare('FACTURARE_CAPITAL_SOCIAL') ?></p>
                                    <? endif ?>

                                </div>


                                <div class="orar">

                                    <h3><?= $program_lucru['titlu'] ?></h3>
                                    <?= $program_lucru['continut'] ?>
                                </div>

                                <div class="informatii-contact">
                                    <p><strong>Telefon:</strong> <?= setare('TELEFON_CONTACT') ?>, <?= setare('TELEFON_CONTACT_2') ?></p>
                                    <p>
                                        <strong>E-mail:</strong> 
                                        <?php foreach( $email_contact as $email ): ?>
                                            <a href="mailto:<?= $email['email'] ?>"><?= $email['email'] ?></a><?= $email['separator'] ?> 
                                        <? endforeach ?>
                                    </p>
                                    <p><strong>Site web:</strong> <a href="<?= base_url() ?>">www.genway.ro</a></p>

                                </div>


                            </div>
                            <div class="contact-form">

                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2841054.4667385486!2d25.020079599999995!3d45.94194665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sro!2sro!4v1403878762432" width="290" height="290" frameborder="0" style="border:0"></iframe>

                                <h3 style="margin-bottom:15px">Trimite-ne un mesaj</h3>
                                
                                								
								<style>
								.inputcss {    
									height: 25px!important;
									padding: 5px 3%!important;
									width: 94%!important;
									margin-bottom: 7px!important;
								}
								.inputcss2 {    
									height: 80px!important;
									padding: 5px 3%!important;
									width: 94%!important;
								}
								.submittcss {
									background: #358bd6;
									color: #FFF;
									padding: 10px;
									border: 0;
									width: 100%;
									margin-top: 15px;
								}
								</style>
								
								<?php if(!empty($errMsg)): ?><div class="errMsg"><?php echo $errMsg; ?></div><?php endif; ?>
								<?php if(!empty($succMsg)): ?><div class="succMsg"><?php echo $succMsg; ?></div><?php endif; ?>
								<div class="form-info ">
									<form action="" method="POST">
										<div>
										
											<input type="text" class="text inputcss" style="border:1px solid #eee" value="<?php echo !empty($name)?$name:''; ?>" placeholder="Nume" name="name" required>
											<input type="email" class="text inputcss" style="border:1px solid #eee" value="<?php echo !empty($emailaddress)?$emailaddress:''; ?>" placeholder="Email" name="emailaddress" required">
											<input type="phone" class="text inputcss" style="border:1px solid #eee;background: #F9FAFB;" value="<?php echo !empty($phone)?$phone:''; ?>" placeholder="Telefon" name="phone" pattern=".{6,}" required>
											<input type="text" class="text inputcss" style="border:1px solid #eee" value="<?php echo !empty($subiectt)?$subiectt:''; ?>" placeholder="Subiect" name="subiectt" required>
											<textarea type="text" class="inputcss2" style="border:1px solid #eee" placeholder="Mesaj" name="message" style="height:80px" pattern=".{6,}" required><?php echo !empty($message)?$message:''; ?></textarea>
										</div>
										<br/>
										<div style="">
											<div class="g-recaptcha" data-sitekey="6LfYJ0AUAAAAADcqGqWdKOOcrQmGTpQwKJXdTQE6"></div>
										</div>
										<div>
											<input type="submit" name="submit" class="submittcss" value="Trimite">
										</div>
									</form>
								</div>
								
                            </div>

                        </div>
	
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>