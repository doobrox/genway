<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagina extends CI_Controller {

    public function index( $slug_pagina, $data = array() ) {
        $this->load->driver('cache');
        
        $this->load->model('pagina_model', 'paginam');
        $this->load->helper('setari');
        $this->load->helper('text');
        $this->load->library('functions');


        $pagina = $this->paginam->get_pagina_by_slug( $slug_pagina );
        if( !$pagina ) {
            redirect('_404');
        }
        $data["pagina"] = $pagina;
        
        $data["breadcrumbs"] = array(
            array(
                "link" => base_url(),
                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',
                "class" => "acasa"
            ),
            array(
                "link" => site_url( "info/{$slug_pagina}" ),
                "titlu" => $pagina['titlu']
            ),
        );

        $data["page"] = "pagina";
        $data["page_view"] = "pagina";
        $data["title"] = $pagina['titlu'] . " | " . setare('TITLU_NUME_SITE');
        $data["meta_description"] = $pagina['titlu'] . " - " . character_limiter( strip_tags( $pagina['continut'] ) , 80) . " - " . setare('TITLU_NUME_SITE');
        
        
        $this->load->library('display', $data);
    }

}

/* End of file pagina.php */
/* Location: ./application/controllers/pagina.php */