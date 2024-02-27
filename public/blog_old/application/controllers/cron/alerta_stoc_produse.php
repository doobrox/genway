<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CRON o data / zi la ora 9:00 diminieata
 * trimitere email cu alerta ca produsul se afla in stoc
 * wget http://www.lenjeriidepatdeosebite.ro/cron/alerta_stoc_produse >/dev/null 2>&1
 */

class alerta_stoc_produse extends CI_Controller {

    public function index() {
//        $this->output->enable_profiler(true);
        $this->load->helper('setari');
        $this->load->library('email');
        $this->load->model('cron_model', 'cronm');
        
        $items = $this->cronm->get_alerta_produse();
        foreach ($items as $item) {
            $item['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id_produs'] );
            $this->email->from(setare('EMAIL_CONTACT'), setare('TITLU_NUME_SITE_SCURT'));
            $this->email->to($item['email']);
            $this->email->subject( "Produsul {$item['nume']} se afla in stoc! | " . setare('TITLU_NUME_SITE_SCURT') );
                
            $template['titlu'] = "Produsul {$item['nume']} se afla in stoc!";
            $template['continut'] = "Buna ziua,<br /><br/ >
Produsul <strong>". anchor( $item['furl'], $item['nume'] ) ."</strong> se afla in stoc. <br />
Cantitatea dorita de dvs este de <strong>{$item['cantitate']} bucati</strong>, iar stocul este de <strong>{$item['stoc']} bucati</strong>. Pentru a comanda produsul accesati: <br /><br />
". anchor( $item['furl'] ) ;
            $message = $this->load->view("template_email", $template, true);
            $this->email->message( $message );

            if( $this->email->send() ) {
                $this->db->set("email_trimis", 1);
                $this->db->where("id", $item['id']);
                $this->db->update("produse_alerte");
            }
        }
    }
}