<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Categorii extends CI_Controller {

    

    private $pConfig;

    

    public function __construct() {

        parent::__construct();

//        $this->output->enable_profiler(true);



        $this->load->library('functions');

        $this->load->library('pagination');

        $this->load->helper('text');

        $this->load->helper('setari');

        $this->load->driver('cache');

        $this->load->model('index_page_model', 'indexm');

        $this->load->model('categorii_model', 'categoriim');

        

        $this->pConfig = $this->functions->pConfig;

    }



    public function index( $slug_categorie = "", $slug_producator = "", $promotie = 0, $sortare_dupa = -1, $data = array() ) {

        $data['sortare_dupa'] = $sortare_dupa;

        

        if( $slug_producator!="" ) {

            $producator = $this->categoriim->get_producator_by_slug( $slug_producator );

            if( !empty( $producator ) ) {

                $_GET['id_producator'] = $producator['id'];

            }

        }

        

        $p = $this->input->post();

        if( empty( $p ) ) {

             $p = $this->input->get();

        }

        

        $categorie = $this->categoriim->get_categorie_by_slug( $slug_categorie );

        

        //if( !isset( $categorie['id'] ) ) redirect('index_page');

        if ( isset( $p['cautare'] ) ) {

            $data['titlu_h1'] = "Cautare produse";

        } elseif ( !empty( $categorie ) ) {

            $data['categorie'] = $categorie;

            $data['titlu_h1'] = $categorie['nume'];

        } elseif ( $promotie==1 ) {

            $data['titlu_h1'] = "Produse la promotie";

        } elseif ( $sortare_dupa==2 ) {

            $data['titlu_h1'] = "Cele mai noi produse";

        }

        

        if( isset( $categorie['id'] ) ) {

            $items = $this->indexm->get_categorii_by_id_parinte( $categorie['id'] );

            $data['subcategorii'] = array();

            $k = 0;

            foreach ($items as $item) {

                $data['subcategorii'][$k] = $item;

                $data['subcategorii'][$k]['k'] = $k;

                $data['subcategorii'][$k]['furl'] = $this->functions->make_furl_categorie( $item['id'] );



                $k++;

            }

        }

        

        $data['options_sortare_dupa'] = array(

            -1 => "Popularitate",
            
            0 => "Denumire A -> Z",

            1 => "Denumire Z -> A",

            2 => "Cele mai noi",

            3 => "Cele mai vechi",

            4 => "Pret crescator",

            5 => "Pret Descrescator",

        );

        

        $data['options_afisare'] = array(

            100 => "100 produse / pag.",

            80 => "80 produse / pag.",

            40 => "40 produse / pag.",

            20 => "20 produse / pag.",

            8 => "8 produse / pag.",

        );

        

        $q = array(

            "q" => isset( $p['q'] ) ? $p['q'] : "",

            "id_producator" => isset( $p['id_producator'] ) ? $p['id_producator'] : "",

            "pret_de_la" => isset( $p['pret_de_la'] ) ? $p['pret_de_la'] : "",

            "pret_pana_la" => isset( $p['pret_pana_la'] ) ? $p['pret_pana_la'] : "",

            "id_categorie" => isset( $categorie['id'] ) ? $categorie['id'] : "",

            "sortare_dupa" => isset( $p['sortare_dupa'] ) ? $p['sortare_dupa'] : $sortare_dupa,

            "reducere" => isset( $p['reducere'] ) ? 1 : 0,

            "promotie" => $promotie,

        );

        

        if( isset( $categorie['id'] ) ) {

            $url_pagination = $this->functions->make_furl_categorie( $categorie['id'] ) . "?";

        } else {

            $url_pagination = base_url() . "produse?";

        }

        

        $offset = $this->input->get('per_page');

        $_GET['afisare'] = isset( $_GET['afisare'] ) && is_numeric( $_GET['afisare'] ) ? $this->input->get('afisare') : 100;

        $this->pConfig['per_page'] = $_GET['afisare'];

        $this->pConfig['page_query_string'] = TRUE;

        $this->pConfig['base_url'] = $url_pagination .  build_url_string();

        

        $q['count'] = true;

        $this->pConfig['total_rows'] = $this->categoriim->get_produse($q);

        $q['count'] = false;

        

        

        $this->pagination->initialize($this->pConfig);

        

        $nume_fisier_cache = "produse_{$q['id_categorie']}_{$q['id_producator']}_{$q['sortare_dupa']}_{$q['reducere']}_{$q['promotie']}_{$this->pConfig['per_page']}_{$offset}";

        if( !$data['items'] = $this->cache->file->get($nume_fisier_cache) ) {

            $items = $this->categoriim->get_produse($q, $this->pConfig['per_page'], $offset);

            $data['items'] = array();

            $k = 0;

            foreach ($items as $item) {

                $data['items'][$k] = $item;

                $data['items'][$k]['descriere'] = character_limiter(strip_tags($item['descriere']), 100);

                $data['items'][$k]['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id'] );

                $data['items'][$k]['pret_cu_tva'] = $this->functions->build_pret_formatat( $item['pret_cu_tva'] );

                $data['items'][$k]['k'] = $k;



                if( $item['pret_cu_tva']<$item['pret_intreg_cu_tva'] ) {

                    $data['items'][$k]['pret_intreg_format'] =  $this->functions->build_pret_formatat( $item['pret_intreg_cu_tva'] );

                }



                $k++;

            }

            

            if( $k>0 ) {

                $data['items'][$k-1]['ultim'] = true;

            }

            

            if( CACHE ) {

                $this->cache->file->save($nume_fisier_cache, $data['items'], 86400);

            }

        }

        

        if( !empty( $data['items'] ) ) {

            $data['pagination_text'] = "Afiseaza de la ". ( isset($_GET['per_page']) && $_GET['per_page']!="" ? ((int)$_GET['per_page'])+1 : 0 ) ." la ". ( isset($_GET['per_page']) && $_GET['per_page']!="" && $_GET['afisare']!="" ? ( ((int)$_GET['per_page'] + (int)$_GET['afisare'])<$this->pConfig['total_rows'] ? ((int)$_GET['per_page'] + (int)$_GET['afisare']) : $this->pConfig['total_rows'] ) : ( $this->pConfig['per_page']<$this->pConfig['total_rows'] ? $this->pConfig['per_page'] : $this->pConfig['total_rows'] ) ) ." din " . $this->pConfig['total_rows'] . " de produse.";

            $data["pagination"] = $this->pagination->create_links();

        }

        

        if ( isset( $p['cautare'] ) ) {

            $data['title'] = "Cautare | " . setare('TITLU_NUME_SITE');

            $data['meta_description'] = "Cautare produse - " . setare('TITLU_NUME_SITE');

        } elseif( isset( $producator ) && !empty( $producator ) ) {

            $data['title'] = "{$producator['nume']} | " . setare('TITLU_NUME_SITE');

            $data['meta_description'] = "{$producator['nume']} - " . setare('TITLU_NUME_SITE');

        } elseif( $promotie==1 ) {

            $data['title'] = "Produse la promotie | " . setare('TITLU_NUME_SITE');

        } elseif( $sortare_dupa==2 ) {

            $data['title'] = "Cele mai noi produse | " . setare('TITLU_NUME_SITE');

        }

        

        if( isset( $categorie['id'] ) ) {

            $data['breadcrumbs'] = $this->functions->make_breadcrumbs_categorie( $categorie['id'] );

            

            if( $categorie['seo_title']!="" ) {

                $data['title'] =  $categorie['seo_title'] . " | " . setare('TITLU_NUME_SITE');

            } else {

                $data['title'] = array();

                for( $i=count($data['breadcrumbs'])-1; $i>0; $i-- ) {

                    $data['title'][] = $data['breadcrumbs'][$i]['titlu'];

                }

                $data['title'] = implode(" &raquo; ", $data['title']) . " | " . setare('TITLU_NUME_SITE');

            }

            

            $data['meta_description'] = $data['meta_keywords'] = $categorie['meta_description']!="" ? $categorie['meta_description'] : $data['title'];

            $data['meta_keywords'] = $categorie['meta_keywords']!="" ? $categorie['meta_keywords'] : strtolower($data['meta_keywords']);

        }

        

        $data["page"] = "categorii";

        $data["page_view"] = "categorii";



        $this->load->library('display', $data);

    }

}



/* End of file index_page.php */

/* Location: ./application/controllers/admin/index_page.php */