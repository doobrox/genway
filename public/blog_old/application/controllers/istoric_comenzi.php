<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class istoric_comenzi extends CI_Controller {
    private $id_user;
    
    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);
       
        $this->load->helper('setari');
        $this->load->model('index_page_model', 'indexm');
        $this->load->model('istoric_comenzi_model', 'istoric_comenzi_m');
        $this->load->model('user_model', 'userm');
        
        if (!$this->simpleloginsecure->is_logat()) {
            redirect( base_url()."login?link=" . urlencode(site_url("istoric_comenzi") ) );
        }
        
        $this->id_user = $this->session->userdata("id");
    }

    public function index( $data = array() ) {
        $data["title"] = "Contul meu - Istoric comenzi | " . setare('TITLU_NUME_SITE');
        
        $items =  $this->istoric_comenzi_m->get_comenzi( $this->id_user );
        $data['comenzi'] = array();
        $k = 0;
        foreach ($items as $item) {
            $data['comenzi'][$k] = $item;
            
            switch ($item['stare']) {
                case "-1":
                    $data['comenzi'][$k]['text_stare'] = "Anulata";
                    break;

                case "0":
                    $data['comenzi'][$k]['text_stare'] = "Comanda noua";
                    break;

                case "1":
                    $data['comenzi'][$k]['text_stare'] = "Comanda preluata";
                    break;

                case "2":
                    $data['comenzi'][$k]['text_stare'] = "Comanda livrata";
                    break;

                case "3":
                    $data['comenzi'][$k]['text_stare'] = "Comanda finalizata";
                    break;

                default:
                    break;
            }
            
            switch( $item['stare_plata'] ) {
                case -2:
                    $data['comenzi'][$k]['stare_plata_text'] = "Respinsa";
                    break;
                case -1:
                    $data['comenzi'][$k]['stare_plata_text'] = "Anulata";
                    break;
                case 0:
                    $data['comenzi'][$k]['stare_plata_text'] = "In procesare";
                    break;
                case 1:
                    $data['comenzi'][$k]['stare_plata_text'] = "Confirmata";
                    break;
            }
            
            $k++;
        }
        
        $data['breadcrumbs'] = array(
            array(
                "link" => base_url(),
                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',
                "class" => "acasa"
            ),
            array(
                "link" => site_url("istoric_comenzi"),
                "titlu" => "Istoric comenzi",
            ),
        );
        
        $data["page_view"] = "istoric_comenzi";

        $this->load->library('display', $data);
    }
    
    public function comanda($id_comanda) {
        $comanda = $this->istoric_comenzi_m->get_comanda( $id_comanda );
        if( empty( $comanda ) || $comanda['id_user']!=$this->id_user ) {
            redirect('istoric_comenzi');
        }
        
        $this->load->model('produs_model', 'produsm');
        
        $data['comanda'] = $comanda;
        $data['comanda']['mesaj'] = nl2br( $comanda['mesaj'] );
        
        switch ($comanda['id_tip_plata']) {
            case "1":
                $data['comanda']['tip_plata'] = "Ramburs";
                break;
            
            case "2":
                $data['comanda']['tip_plata'] = "Online";
                break;

            case "3":
                $data['comanda']['tip_plata'] = "Transfer bancar";
                break;
        }
        
        switch ($comanda['stare']) {
            case "-1":
                $data['comanda']['text_stare'] = "Anulata";
                break;

            case "0":
                $data['comanda']['text_stare'] = "Comanda noua";
                break;

            case "1":
                $data['comanda']['text_stare'] = "Comanda preluata";
                break;

            case "2":
                $data['comanda']['text_stare'] = "Comanda livrata";
                break;

            case "3":
                $data['comanda']['text_stare'] = "Comanda finalizata";
                break;

            default:
                break;
        }
        
        switch( $comanda['stare_plata'] ) {
                case -2:
                    $data['comanda']['stare_plata_text'] = "Respinsa";
                    break;
                case -1:
                    $data['comanda']['stare_plata_text'] = "Anulata";
                    break;
                case 0:
                    $data['comanda']['stare_plata_text'] = "In procesare";
                    break;
                case 1:
                    $data['comanda']['stare_plata_text'] = "Confirmata";
                    break;
            }
        
        $data['user'] = $this->userm->get_user( $data['comanda']['id_user'] );
        if( $data['user']['livrare_adresa_1']==1 ) {
            $data['user'] = $this->userm->get_user_livrare( $data['comanda']['id_user'] );
        }
        
        $items = $this->istoric_comenzi_m->get_produse_comanda( $data['comanda']['id'] );
        $data['items'] = array();
        $k = 0;
        foreach ($items as $item) {
            $data['items'][$k] = $item;
            $data['items'][$k]['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id_produs'] );
            
            $ids_filtre = explode(",", $item['filtre']);
            $data['items'][$k]['filtre'] = array();
            $y = 0;
            foreach ($ids_filtre as $id_filtru) {
                $filtru =  $this->produsm->get_filtru( $id_filtru );
                if( !empty( $filtru ) ) {
                    $data['items'][$k]['filtre'][$y]['nume_parinte'] = $filtru['nume_parinte'];
                    $data['items'][$k]['filtre'][$y]['nume_filtru'] = $filtru['nume'];

                    $y++;
                }
            }
            
            $k++;
        }
        
        $data['breadcrumbs'] = array(
            array(
                "link" => base_url(),
                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',
                "class" => "acasa"
            ),
            array(
                "link" => site_url("istoric_comenzi"),
                "titlu" => "Istoric comenzi",
            ),
            array(
                "link" => site_url("istoric_comenzi/comanda/{$id_comanda}"),
                "titlu" => "Comanda #{$comanda['nr_factura']} din {$comanda['data_adaugare_f']}",
            ),
        );
        
        $data["title"] = "Comanda #{$comanda['nr_factura']} din {$comanda['data_adaugare_f']} | " . setare('TITLU_NUME_SITE');
        $data["page_view"] = "comanda";

        $this->load->library('display', $data);        
    }
    
    public function factura($nr_factura) {
        $comanda = $this->istoric_comenzi_m->get_comanda_by_nr_factura( $nr_factura );
        if( !empty( $comanda ) ) {
            if( $this->id_user == $comanda['id_user'] ) {
                $file = dirname(__FILE__) . "/facturi/factura{$comanda['nr_factura']}.pdf";
                $filename = "Factura proforma #{$comanda['nr_factura']} - {$comanda['data_adaugare']}.pdf"; /* Note: Always use .pdf at the end. */

                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($file));
                header('Accept-Ranges: bytes');

                @readfile($file);
            }
        }
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */