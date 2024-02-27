<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');





class Produs extends CI_Controller {


    public function __construct() {


        parent::__construct();


//        $this->output->enable_profiler(true);





        $this->load->library('functions');


        $this->load->helper('breadcrumbs');


        $this->load->helper('captcha');


        $this->load->helper('setari');


        $this->load->helper('text');


        $this->load->driver('cache');


        $this->config->load('captcha');


        $this->load->model('index_page_model', 'indexm');


        $this->load->model('categorii_model', 'categoriim');


        $this->load->model('produs_model', 'produsm');


        $this->db->query( "SET lc_time_names = 'ro_RO'" );


    }





    public function index( $slug_categorie, $id_produs, $data = array() ) {


        


        $item = $this->produsm->get_produs( $id_produs );


        if( empty( $item ) ) {


            redirect( "_404" );


        }


        


        $categorie = $this->categoriim->get_categorie_by_slug( $slug_categorie );


        if( empty( $categorie ) ) {


            redirect( "_404" );


        }


        


        $data['categorie'] = $categorie;


        


        if( !$this->produsm->check_produs_categorie( $id_produs, $categorie['id'] ) ) {


            redirect( $this->functions->make_furl_produs( $item['nume'], $item['id'] )  );


        }


        


        $data['item'] = $item;


        $data['item']['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id'] );


        $data['item']['pret_cu_tva'] = $this->functions->build_pret_formatat( $item['pret_cu_tva'] );


        if( $item['pret_cu_tva']<$item['pret_intreg_cu_tva'] ) {


            $data['item']['pret_intreg_format'] =  $this->functions->build_pret_formatat( $item['pret_intreg_cu_tva'] );


        }





        if ( $item['stoc_la_comanda']==1 ) {


            $data['item']['stoc_class'] = "instoc";


            $data['item']['stoc_text'] = "Stoc la comanda.";


        } elseif( $item['stoc']==0 ) {


            $data['item']['stoc_class'] = "zerostoc";


            $data['item']['stoc_text'] = "Stoc epuizat";


        } elseif( $item['stoc']<10 ) {


            $data['item']['stoc_class'] = "instoc";


            $data['item']['stoc_text'] = "Stoc limitat < 10";


        } elseif( $item['stoc']>=10 ) {


            $data['item']['stoc_class'] = "instoc";


            $data['item']['stoc_text'] = "Stoc suficient";

        }


        


        if( $item['stoc']>0 ) {


            $filtre = $this->produsm->get_filtre_produs( $id_produs );


            $parinti = array();


            $subfiltre_princ = array();





            foreach ($filtre as $filtru) {


                $subfiltre = json_decode($filtru['id_filtre']);


                foreach ($subfiltre as $id_subfiltru) {


                    $subfiltre_princ[] = $id_subfiltru;


                    $subfiltru = $this->produsm->get_filtru( $id_subfiltru );


                    $parinti[] = $subfiltru['id_parinte'];


                }


            }


            $subfiltre_princ = array_unique($subfiltre_princ);


            $parinti = array_unique($parinti);





            $data['filtre'] = array();


            $k = 0;


            foreach ($parinti as $id_parinte) {


                $filtru_princ = $this->produsm->get_filtru_principal( $id_parinte );


                $data['filtre'][$k]['nume'] = $filtru_princ['nume'];





                $data['filtre'][$k]['options_filtre'] = array( "" => "ALEGE" );


                $subfiltre2 = $this->produsm->get_subfiltre_by_id_parinte( $id_parinte );


                foreach ($subfiltre2 as $subf) {


                    if(in_array($subf['id'], $subfiltre_princ) ) {


                        $data['filtre'][$k]['options_filtre'][ $subf['id'] ] = $subf['nume'];


                    }


                }





                $k++;


            }


        }


        


        $data['item']['galerie'] = $this->produsm->get_galerie_foto( $id_produs );
        
        
        $items = $this->produsm->get_fisiere_produs( $id_produs );
        $data['item']['fisiere_tehnice'] = array();
        $k = 0;
        foreach ($items as $ft) {
            $data['item']['fisiere_tehnice'][$k] = $ft;
            $data['item']['fisiere_tehnice'][$k]['titlu'] = $ft['titlu']=="" ? $ft['fisier'] : $ft['titlu'];
            
            $k++;
        }


        $data['furl_adauga_comentariu'] = $this->functions->make_furl_produs( $item['nume'], $item['id'], "adauga-comentariu" );


        $comentarii = $this->produsm->get_comentarii( $data['item']['id'] );


        $data['comentarii'] = array();


        $k = 0;


        foreach( $comentarii as $com ) {


            $data['comentarii'][$k] = $com;


            $data['comentarii'][$k]['comentarii'] = nl2br( $com['comentarii'] );





            $k++;


        }


        


        if ( !$data['produse_recomandate'] = $this->cache->file->get("produse_recomandate_{$id_produs}") ) {


            $produse_recomandate = $this->produsm->get_produse_recomandate( $id_produs );


            $k = 0;


            $data['produse_recomandate'] = array();


            foreach ($produse_recomandate as $recomandat) {


                $data['produse_recomandate'][$k] = $recomandat;


                $data['produse_recomandate'][$k]['descriere'] = character_limiter(strip_tags($recomandat['descriere']), 100);


                $data['produse_recomandate'][$k]['furl'] = $this->functions->make_furl_produs( $recomandat['nume'], $recomandat['id'] );





                if( $recomandat['pret']<$recomandat['pret_intreg'] ) {


                    $data['produse_recomandate'][$k]['pret_intreg_format'] =  $this->functions->build_pret_formatat( $recomandat['pret_intreg'] );


                }





                $k++;


            }


            


            if( CACHE ) {


                $this->cache->file->save("produse_recomandate_{$id_produs}", $data['produse_recomandate'], 86400);


            }


        }


        


        $data['breadcrumbs'] = $this->functions->make_breadcrumbs_categorie( $categorie['id'] );


        $data['breadcrumbs'][] = array(


            "link" => $data['item']['furl'],


            "titlu" => $data['item']['nume'],


        );


        


        if( $data['item']['seo_title']!="" ) {


            $data['title'] =  $data['item']['seo_title'] . " | " . setare('TITLU_NUME_SITE');


        } else {


            $data['title'] = "{$data['item']['nume']} - {$categorie['nume']} | " . setare('TITLU_NUME_SITE');


        }


        $data['meta_description'] = $data['item']['meta_description'];


        $data['meta_keywords'] = $data['item']['meta_keywords'];


        


        $data["page"] = "produs";


        $data["page_view"] = "produs";





        $this->load->library('display', $data);


    }


    


    public function adauga_comentariu($slug_categorie, $id_produs) {


        $data = array();


        $p = $this->input->post();


        


        if( isset( $p['salveaza'] ) ) {


            $data = $this->salveaza_comentariu( $id_produs );


        } 


        


        if( $this->session->flashdata('succes')!="" ) {


            $data['succes'] = $this->session->flashdata('succes');


        }


        


        $categorie = $this->categoriim->get_categorie_by_slug( $slug_categorie );


        $data['categorie'] = $categorie;


        


        $item = $this->produsm->get_produs( $id_produs );


        if( empty( $item ) ) {


            redirect( "_404" );


        }


        


        if( empty( $data['categorie'] ) ) {


            redirect( "_404" );


        }


        


        if( !$this->produsm->check_produs_categorie( $id_produs, $categorie['id'] ) ) {


            redirect( $this->functions->make_furl_produs( $item['nume'], $item['id'] )  );


        }


        


        $data['item'] = $item;


        $data['item']['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id'] );





        $data['options_note'] = array(


            5 => "5 stele",


            4 => "4 stele",


            3 => "3 stele",


            2 => "2 stele",


            1 => "1 stea",


        );





        $captcha = create_captcha( $this->config->item('config') );


        captcha_insert_db($captcha);


        $data['captcha'] = $captcha['image'];





        $data['item']['nota_medie'] = $this->produsm->get_nota_medie( $data['item']['id'] );


        $data['item']['nota_round'] = round( $this->produsm->get_nota_medie( $data['item']['id'] ) );





        $comentarii = $this->produsm->get_comentarii( $data['item']['id'] );


        $data['nr_comentarii'] = count( $comentarii );


        $data['comentarii'] = array();


        $k = 0;


        foreach( $comentarii as $com ) {


            $data['comentarii'][$k] = $com;


            $data['comentarii'][$k]['comentarii'] = nl2br( $com['comentarii'] );





            $k++;


        }


        


        $data['breadcrumbs'] = $this->functions->make_breadcrumbs_categorie( $categorie['id'] );


        $data['breadcrumbs'][] = array(


            "link" => $this->functions->make_furl_produs( $item['nume'], $item['id'], "adauga-comentariu" ),


            "titlu" => "Recenzii {$data['item']['nume']}",


        );


        


        $data['title'] = "Recenzii {$data['item']['nume']} | " . setare('TITLU_NUME_SITE');


        $data['meta_description'] = "Recenzii {$data['item']['nume']} - " . $data['item']['meta_description'];


        $data['meta_keywords'] = "Recenzii {$data['item']['nume']} - " . $data['item']['meta_keywords'];            





        $data['page_view'] = "produs_adauga_comentariu";


        $this->load->library('display', $data);


    }


    


    public function redirect_produs_vechi($id_produs_vechi) {


        echo $id_produs_vechi;


    }


    


    protected function salveaza_comentariu( $id_produs ) {


        $data = array();


        $this->load->library('form_validation');


        $this->config->load('form_validation');


        


        if ($this->form_validation->run('adauga_comentariu_nota') == TRUE) {


            $p = $this->input->post();


            $ip = $this->input->ip_address();


            if( !$this->simpleloginsecure->is_logat() ) {

                $data['error'] = "Trebuie sa fiti logat pentru a putea adauga o recenzie.";

            } elseif( !$this->produsm->check_ip_address( $id_produs, $ip ) ) {


                $data['error'] = "Puteti adauga doar un comentariu / produs o data la 24 de ore.";


            } else {


                $this->session->userdata("id")!=false ? $this->db->set('id_user', $this->session->userdata("id")) : ""; 


                $this->db->set('id_produs', $id_produs);


                $this->db->set('comentarii', $p['comentarii']);


                $this->db->set('nota', $p['nota']);


                $this->db->set('ip', $ip );


                $this->db->set('data_adaugare', 'NOW()', FALSE);





                if( $this->db->insert( 'produse_comentarii' ) ) {


                    clear_captcha($p['captcha']);


                    $this->session->set_flashdata( "succes", "Comentariul a fost adaugat cu succes." );





                    $item = $this->produsm->get_produs( $id_produs );


                    redirect( $this->functions->make_furl_produs( $item['nume'], $item['id'], "adauga-comentariu" ) );


                }


            }


        }


        


        return $data;


    }


    


    function check_captcha( $captcha ) {


        if( !check_captcha( $captcha ) ) {


            $this->form_validation->set_message("check_captcha", "Campul %s nu este corect.");


            return false;


        }


        return  true;


    }


    


}





/* End of file index_page.php */


/* Location: ./application/controllers/admin/index_page.php */