<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index_Page extends CI_Controller {
    
    public function __construct() {
        
        parent::__construct();
//        $this->output->enable_profiler(true);
        
        $this->load->library('functions');
        $this->load->helper('text');
        $this->load->driver('cache');
        $this->load->model('index_page_model', 'indexm');
    }

    public function index( $data = array() ) {
        $data["page"] = "index_page";
        
        if ( !$data['produse_promovate'] = $this->cache->file->get('produse_promovate_index') ) {
            $items = $this->indexm->get_produse_promovate_index();
            $k = 0;
            $data['produse_promovate'] = array();
            foreach ($items as $item) {
                $data['produse_promovate'][$k] = $item;
                $data['produse_promovate'][$k]['descriere'] = character_limiter(strip_tags($item['descriere']), 150);
                $data['produse_promovate'][$k]['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id'] );
                $data['produse_promovate'][$k]['k'] = $k;

                if( $item['pret']<$item['pret_intreg'] ) {
                    $data['produse_promovate'][$k]['pret_intreg_format'] =  $this->functions->build_pret_formatat( $item['pret_intreg'] );
                }

                $data['produse_promovate'][$k]['pret'] = $this->functions->build_pret_formatat( $item['pret'] );

                $k++;
            }
            
            if( CACHE ) {
                $this->cache->file->save('produse_promovate_index', $data['produse_promovate'], 86400);
            }
        }
        
        $data['pagina'] = $this->indexm->get_pagina(1);
        $data['pagina_2'] = $this->indexm->get_pagina(2);
        
        $data["page_view"] = "index_page";

        $this->load->library('display', $data);
    }
    
    public function ajax_get_localitati( $id = "" ) {
        $data = array();
        if( $id!="" ) {
            $localitati = $this->indexm->get_localitati_by_id_judet( $id, NULL );
            $data['select_localitati'] = array();
            $k = 1;
            foreach( $localitati as $localitate ) {
                $data['select_localitati'][$k]['id'] = $localitate['id'];
                $data['select_localitati'][$k]['nume'] = $localitate['nume'];

                $k++;
            }
            echo json_encode( $data['select_localitati'] );
        }
    }
    
    public function ajax_get_taxa_expediere($id_curier) {
        echo $this->functions->calculare_taxa_expediere($id_curier);
    }
    
    public function ajax_get_discount_plata_op($tip_plata = "") {
        $this->session->set_userdata('id_tip_plata', $tip_plata);
        echo $this->functions->calculare_discount_plata_op($tip_plata);
    }
    
    public function ajax_get_valoare_discount_plata_op() {
        echo $this->functions->get_valoare_discount_plata_op();
    }
    
    public function ajax_get_total_cos($val = "") {
        $id_curier = $val!="" ? $val : ( $this->session->userdata('id_curier') ? $this->session->userdata('id_curier') : $this->indexm->get_curier_default() );
        $tip_plata = $this->session->userdata('id_tip_plata') ? $this->session->userdata('id_tip_plata') : "";
        $cod_voucher = $this->session->userdata('cod_voucher') ? $this->session->userdata('cod_voucher') : "";
        
        $total = $this->functions->calculare_total_cos( $id_curier, $cod_voucher, $tip_plata );
        echo $this->cart->format_number( $total );
    }
    
    public function ajax_get_produs_by_filtre() {
        $id_produs = $this->input->get('id_produs');
        $id_filtre = $this->input->get('id_filtre');
        
        if( !is_numeric($id_produs) ) return;
        //if( !is_array($id_filtre) ) return;
        $this->load->model('produs_model', 'produsm');
        $data = array();
        
        $produs = $this->produsm->get_produs( $id_produs );
        if(is_array($id_filtre) ) {
            $id_filtre = array_filter( $id_filtre );
            sort( $id_filtre );
            $id_filtre = json_encode($id_filtre);
            $filtru = $this->produsm->get_filtru_produs( $id_produs, $id_filtre );
        }
        
        if( isset( $filtru ) && !empty( $filtru ) ) {
            if( $filtru['pret']=="" ) {
                $data['pret_intreg_format'] = round( $produs['pret_intreg'], 2);
                $data['pret'] = $produs['pret'];
            } else {
                $data['pret_intreg_format'] = round( $filtru['pret'], 2 );
                switch ($produs['reducere_tip']) {
                    case '1':
                        $data['pret'] = $filtru['pret'] - $filtru['reducere_valoare'];
                        break;

                    case '2':
                        $data['pret'] = $filtru['pret'] - ( $produs['reducere_valoare']/100*$filtru['pret'] );
                        break;

                    default:
                        $data['pret'] = $filtru['pret'];
                        break;
                }
            }
            
            if( $filtru['stoc']==0 ) {
                $data['stoc_class'] = "stoc-zero";
                $data['stoc_text'] = "Stoc zero.";
            } elseif( $filtru['stoc']<10 ) {
                $data['stoc_class'] = "stoc-limitat";
                $data['stoc_text'] = "Stoc limitat < 10";
            } elseif( $filtru['stoc']>=10 ) {
                $data['stoc_class'] = "stoc-suficient";
                $data['stoc_text'] = "Stoc suficient.";
            }
        } else {
            $data['pret_intreg_format'] = round( $produs['pret_intreg'], 2 );
            $data['pret'] = $produs['pret'];
            
            if( $produs['stoc_la_comanda']==1 ) {
                $data['stoc_class'] = "stoc-la-comanda";
                $data['stoc_text'] = "Stoc doar la comanda.";
            } elseif( $produs['stoc']==0 ) {
                $data['stoc_class'] = "stoc-zero";
                $data['stoc_text'] = "Stoc zero.";
            } elseif( $produs['stoc']<10 ) {
                $data['stoc_class'] = "stoc-limitat";
                $data['stoc_text'] = "Stoc limitat < 10";
            } elseif( $produs['stoc']>=10 ) {
                $data['stoc_class'] = "stoc-suficient";
                $data['stoc_text'] = "Stoc suficient.";
            }
        }
        
        echo json_encode($data);
    }

    public function ajax_verifica_stoc($id_produs, $cantitate) {
        $this->db->from("produse")
                 ->where("id", $id_produs)
                 ->where("stoc >=", $cantitate);
        
        echo $this->db->count_all_results()>0 ? "1" : "0";
    }
    
    public function ajax_popup_stoc_indisponibil($id_produs, $cantitate) {
        $this->load->model('produs_model', 'produsm');
        $data = array();
        
        $item = $this->produsm->get_produs( $id_produs );
        if( empty( $item ) ) {
            exit;
        }
        
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $data['id_produs'] = $item['id'];
        $data['nume_produs'] = $item['nume'];
        $data['cantitate'] = $cantitate;
        
        $this->load->view('_popup_stoc_indisponibil', $data);
    }
    
    public function ajax_popup_stoc_indisponibil_submit() {
        $this->load->model('produs_model', 'produsm');
        $p = $this->input->post();
        
        if( !empty( $p ) ) {
            $item = $this->produsm->get_produs( $p['alerta_id_produs'] );
            if( empty( $item ) ) {
                exit;
            }
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('alerta_id_produs', 'ID Produs', 'trim|numeric|required');
            $this->form_validation->set_rules('alerta_cantitate', 'Cantitate dorita', 'trim|numeric|required');
            $this->form_validation->set_rules('alerta_email', 'Adresa de email', 'trim|required|valid_email');

            if ($this->form_validation->run() == TRUE) {
                $this->db->set("id_produs", $p['alerta_id_produs']);
                $this->db->set("cantitate", $p['alerta_cantitate']);
                $this->db->set("email", $p['alerta_email']);
                if( $this->db->insert("produse_alerte") ) {
                    $this->session->set_flashdata("succes", "Adresa de email a fost salvata cu succes.");
                    redirect("index_page/ajax_popup_stoc_indisponibil/{$p['alerta_id_produs']}/{$p['alerta_cantitate']}");
                }
            }

            $this->ajax_popup_stoc_indisponibil( $p['alerta_id_produs'], $p['alerta_cantitate'] );
        }
    }
    
    public function ajax_abonare_newsletter() {
        $p = $this->input->post();
        
        if( !empty( $p ) && !empty( $p['email_newsletter'] ) && !empty( $p['nume_newsletter'] ) ) {
            
            if( $p['nume_newsletter']=="" ) {
                echo "0|Campul nume este obligatoriu.";
            } elseif( !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $p['email_newsletter']) ) {
                echo "0|Adresa de email nu este valida.";
            } else {
                $this->db->from("newsletters_abonati");
                $this->db->where("email", $p['email_newsletter']);
                if( $this->db->count_all_results()>0 ) {
                    $abonat = $this->db->where("email", $p['email_newsletter'])->get("newsletters_abonati")->row_array();
                    if( !empty( $abonat ) ) {
                        if( $abonat['activ']==1 ) {
                            echo "0|Adresa de email este deja abonata la newsletter.";
                        } else {
                            $this->db->set("activ", 1);
                            $this->db->where("email", $p['email_newsletter']);
                            if( $this->db->update("newsletters_abonati") ) {
                                echo "1|Ati fost abonat cu succes la newsletter.";
                            }
                        }
                    }
                } else {
                    $this->db->set("nume", $p['nume_newsletter']);
                    $this->db->set("email", $p['email_newsletter']);
                    $this->db->set("activ", 1);
                    if( $this->db->insert("newsletters_abonati") ) {
                        $this->load->library('email');
                        $this->load->helper('setari');
                        
                        $data['titlu'] = "Un nou abonat la newsletter. " . setare('TITLU_NUME_SITE');

                        $data['continut'] = "<p>Buna ziua, <br /><br />" .
                                "O noua persoana s-a abonat la newsletter: {$p['nume_newsletter']} ( {$p['email_newsletter']} ). Pentru a vedea toti abonatii la newsletter accesati: <br /><Br />" .
                                anchor("admin/newsletter_abonati/") . "<br /><br />";

                        $message = $this->load->view("template_email", $data, true);

                        $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                        $this->email->to( setare('EMAIL_CONTACT') );
                        $this->email->subject( $data['titlu'] );
                        $this->email->message( $message );
                        $this->email->send();
//                        echo $this->email->print_debugger();
                        
                        echo "1|Ati fost abonat cu succes la newsletter.";
                    }
                }
            }
        } else {
            echo "0|Nu ati introdus nici o valoare in campuri.";
        }
    }
        
    public function ajax_check_voucher() {
        $this->load->library("form_validation");
        $this->form_validation->set_rules('cod_voucher', 'Cod cupon/discount', 'trim|required|alpha_numeric|callback_check_voucher');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            
            $this->session->set_userdata("cod_voucher", $p['cod_voucher']);
            $id_curier = $this->session->userdata('id_curier') ? $this->session->userdata('id_curier') : $this->indexm->get_curier_default();
            $tip_plata = $this->session->userdata('id_tip_plata') ? $this->session->userdata('id_tip_plata') : "";
            
            $valoare_voucher = $this->functions->calculare_valoare_voucher( $p['cod_voucher'] );
            $total_cos = $this->functions->calculare_total_cos( $id_curier, $p['cod_voucher'], $tip_plata );
            
            $msj = array(
                    "succes" => 1,
                    "cod_voucher" => $p['cod_voucher'],
                    "valoare_voucher" => $this->cart->format_number( $valoare_voucher ),
                    "total_cos" => $this->cart->format_number( $total_cos ),
                );
            
            echo json_encode($msj);
        } else {
            $msj = array(
                    "succes" => 0,
                    "mesaj" => strip_tags(validation_errors())
                );
            
            echo json_encode($msj);
        }
    }
    
    
    public function check_voucher($cod_voucher) {
        $this->load->library('cart');

        foreach ($this->cart->_cart_contents as $produs){
            $voucher = $this->indexm->get_voucher_produs($cod_voucher, $produs['id']);
            if (empty($voucher) === false){
                break;    
            }
        }

        $vou = $this->indexm->get_voucher_produs_exista($cod_voucher);

        if (empty($voucher) and empty($vou) === false){
            $this->form_validation->set_message('check_voucher', '%s este valabil pentru alt produs fata de cele din cos.');
            return false;
        }

        $voucher = $this->indexm->get_voucher($cod_voucher);
        
        if( empty( $voucher ) ) {
            $this->form_validation->set_message('check_voucher', '%s nu este valid.');
            return false;
        }      
        if( $voucher['expirat']==1 ) {
            $this->form_validation->set_message('check_voucher', '%s este expirat.');
            return false;
        }
        
        if( $voucher['activ']==0 ) {
            $this->form_validation->set_message('check_voucher', '%s a fost folosit deja.');
            return false;
        }
        
        return true;
    }
    
    public function ajax_remove_voucher() {
        $this->session->unset_userdata('cod_voucher');
        $id_curier = $this->session->userdata('id_curier') ? $this->session->userdata('id_curier') : $this->indexm->get_curier_default();

        $total_cos = $this->functions->calculare_total_cos( $id_curier );

        $msj = array(
                "total_cos" => $this->cart->format_number( $total_cos ),
            );

        echo json_encode($msj);
    }
    
    public function db_sync_produse() {
        $p = $this->input->post();
        
        if( isset( $p['sync'] ) && $p['sync']==1 ) {
            $k = 0;
            foreach( $p['cod_produs'] as $cod_produs ) {
                $this->db->set("stoc", $p['stoc'][$k]);
                $this->db->where("cod_ean13", $cod_produs);
                $this->db->update("produse");
                
                $k++;
            }
        }
    }
}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */