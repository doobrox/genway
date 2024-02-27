<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('setari');
        $this->load->helper('captcha');
        $this->load->model('pagina_model', 'paginam');
        $this->config->load('captcha');
    }

    public function index( $data = array() ) {

        $data["page_view"] = "contact";
        $data["header_simplu"] = true;
        
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $data['program_lucru'] = $this->paginam->get_pagina( 14 );

        $emails = explode(";", setare("EMAIL_CONTACT_PUBLIC"));
        $nr_emails = count( $emails );
        $data['email_contact'] = array();
        $k = 1;
        foreach( $emails as $email ) {
            $data['email_contact'][$k]['email'] = $email;
            $data['email_contact'][$k]['separator'] = $k<$nr_emails ? "; " : "";
            
            $k++;
        }
        
        $captcha = create_captcha( $this->config->item('config') );
        captcha_insert_db($captcha);
        $data['captcha'] = $captcha['image'];

        $data['breadcrumbs'] = array(
            array(
                "link" => base_url(),
                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',
                "class" => "acasa"
            ),
            array(
                "link" => site_url("contact"),
                "titlu" => "Contact",
            ),
        );
        
        $data["title"] = "Contact | " . setare('TITLU_NUME_SITE');
        $this->load->library('display', $data);
    }

    public function verificare() {
        if ($this->form_validation->run('trimite_mesaj_contact') == TRUE) {
            
            $p = $this->input->post();

            $this->load->library('email');
            $this->email->from(setare('EMAIL_CONTACT'), setare('TITLU_NUME_SITE'));
            //$this->email->to(setare('EMAIL_CONTACT') );
            $this->email->to( 'adytzul89@gmail.com' );

            $this->email->subject('Contact ' . setare('TITLU_NUME_SITE') . ' : ' . $p['subiect']);
            $template['titlu'] = 'Contact ' . setare('TITLU_NUME_SITE') . ' : ' . $p['subiect'];
            $template['continut'] = "
<h3>Salut admin,</h3>
<p>
Acest mesaj este trimis de la adresa: <a href='".  base_url()."/contact'>".  base_url()."/contact</a> <br />
</p>
<br />
<table border='0' cellpadding='5'>
    <tr>
        <td>Subiect:</td>
        <td>{$p['subiect']}</td>
    </tr>
    <tr>
        <td>Nume:</td>
        <td>{$p['nume']}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{$p['email']}</td>
    </tr>
    <tr>
        <td>Telefon:</td>
        <td>{$p['telefon']}</td>
    </tr>
    <tr valign='top'>
        <td>Mesaj:</td>
        <td>". nl2br( $p['mesaj'] ) ."</td>
    </tr>
</table>

<br />
<p>
O zi buna.
</p>";
            $message = $this->load->view("template_email", $template, true);
            
            $this->email->message( $message );
            $this->email->send();
            
            clear_captcha($p['captcha']);
            $this->session->set_flashdata('succes', "Mesajul a fost trimis cu succes.");
            redirect( "contact" );
        }

        $this->index();
    }
    
    /*function check_captcha( $captcha ) {
        if( !check_captcha( $captcha ) ) {
            $this->form_validation->set_message("check_captcha", "Campul %s nu este corect.");
            return false;
        }
        return  true;
    }*/
  
}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */