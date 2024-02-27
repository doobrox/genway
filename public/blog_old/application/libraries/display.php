<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display {
    
    public function display( $data ) {

        $CI =& get_instance();
        $CI->load->helper('text');
        $CI->load->helper('setari');
        $CI->load->helper('bannere');
        $CI->load->helper('breadcrumbs');
        $CI->load->library('functions');
        $CI->load->library('cart');
        $CI->load->driver('cache');
        $CI->load->model('index_page_model', 'indexmodel');
        
        $data['title'] = isset( $data['title'] ) ? $data['title'] : setare('TITLU_NUME_SITE');
        $data['meta_keywords'] = isset( $data['meta_keywords'] ) ? $data['meta_keywords'] : setare('DEFAULT_META_KEYWORDS');
        $data['meta_description'] = isset( $data['meta_description'] ) ? $data['meta_description'] : setare('DEFAULT_META_DESCRIPTION');
        
        $items = $CI->indexmodel->get_bannere('HEADER');
        $k = 0;
        $data['bannere'] = array();
        foreach ($items as $item) {
            $data['bannere'][$k] = $item;
            $data['bannere'][$k]['k'] = $k;
            $k++;
        }
        
        $items = $CI->indexmodel->get_bannere('HEADER_STANGA');
        if( !empty( $items ) ) {
            $data['banner_stanga'] = $items[0];
        }
        
        if( $CI->simpleloginsecure->is_logat() ) {
            $CI->load->model('user_model', 'userm');
            
            $data['is_logat'] = true;
            $data['nume_user'] = $CI->session->userdata('nume') . " " . $CI->session->userdata('prenume');
        }

        $data['cod_voucher'] = $CI->session->userdata('cod_voucher') ? $CI->session->userdata('cod_voucher') : "";
        $data['id_curier'] = $CI->session->userdata('id_curier') ? $CI->session->userdata('id_curier') : $CI->indexmodel->get_curier_default();
        
        $data['cart_nr_produse'] = $CI->cart->total_items();
        $data['cart_total'] = $CI->cart->format_number( $CI->functions->calculare_total_cos( $data['id_curier'], $data['cod_voucher'] ) );
        $data['cart_total'] = $data['cart_total']!=false ? $data['cart_total'] : 0;
        
        $nume_fisier_cache = isset( $data['categorie']['id'] ) && $data['categorie']['id']!="" ? "categorii_{$data['categorie']['id']}" : "categorii";
        if ( !$data['categorii'] = $CI->cache->file->get($nume_fisier_cache) ) {
            $items = $CI->indexmodel->get_categorii_by_id_parinte();
            $k = 0;
            $data['categorii'] = array();
            foreach ($items as $item) {
                $data['categorii'][$k] = $item;
                $data['categorii'][$k]['furl'] = $CI->functions->make_furl_categorie( $item['id'] ); 
                $data['categorii'][$k]['class'] = isset( $data['categorie']['id'] ) && $item['id']==$data['categorie']['id'] ? "active" : ""; 

                if( isset($data['categorie']['id']) && ( $CI->functions->get_id_parinte_start( $data['categorie']['id'] )==$item['id'] || $data['categorie']['id']==$item['id'] ) ) {
                    $depth = $CI->functions->getCategoriesDepth( $data['categorie']['id'] );

                    $data['categorii'][$k]['subcategorii'] = "<ul class='active'>";

                    $items2 = $CI->indexmodel->get_categorii_by_id_parinte( $item['id'] );
                    foreach ($items2 as $item2) {
                        $furl = $CI->functions->make_furl_categorie( $item2['id'] ); 
                        $a_class = $item2['id']==$data['categorie']['id'] ? "active" : ""; 
                        $depth2 = $CI->functions->getCategoriesDepth( $item2['id'] );

                        $data['categorii'][$k]['subcategorii'] .= "<li style='padding-left: ". ( 15*$depth2 ) ."px'>" . anchor( $furl, $item2['nume'], array( "title" => $item2['nume'], "class" => $a_class ) ) ."</li>";

                        if( ( $depth>=1 && $item2['id']==$data['categorie']['id'] ) || ($depth>1 && ($CI->indexm->get_id_parinte( $data['categorie']['id'] )==$item2['id'] || in_array( $CI->indexm->get_id_parinte( $data['categorie']['id'] ), $CI->indexm->get_ids_categorii( $item2['id'] ) ) ) ) ) {
                            $items3 = $CI->indexmodel->get_categorii_by_id_parinte( $item2['id'] );
                            foreach ($items3 as $item3) {
                                $furl = $CI->functions->make_furl_categorie( $item3['id'] ); 
                                $a_class = $item3['id']==$data['categorie']['id'] ? "active" : ""; 
                                $depth2 = $CI->functions->getCategoriesDepth( $item3['id'] );

                                $data['categorii'][$k]['subcategorii'] .= "<li style='padding-left: ". ( 15*$depth2 ) ."px'>" . anchor( $furl, $item3['nume'], array( "title" => $item3['nume'], "class" => $a_class ) ) ."</li>";

                                if( ($depth>=2 && $item3['id']==$data['categorie']['id'])  || ($depth>2 && $CI->indexm->get_id_parinte( $data['categorie']['id'] )==$item3['id'] ) ) {
                                    $items4 = $CI->indexmodel->get_categorii_by_id_parinte( $item3['id'] );
                                    foreach ($items4 as $item4) {
                                        $furl = $CI->functions->make_furl_categorie( $item4['id'] ); 
                                        $a_class = $item4['id']==$data['categorie']['id'] ? "active" : ""; 
                                        $depth2 = $CI->functions->getCategoriesDepth( $item4['id'] );

                                        $data['categorii'][$k]['subcategorii'] .= "<li style='padding-left: ". ( 15*$depth2 ) ."px'>" . anchor( $furl, $item4['nume'], array( "title" => $item4['nume'], "class" => $a_class ) ) ."</li>";
                                    }
                                }
                            }
                        }
                    }

                    $data['categorii'][$k]['subcategorii'] .= "</ul>";
                }

                $k++;
            }
            
            if( CACHE ) {
                $CI->cache->file->save($nume_fisier_cache, $data['categorii'], 86400);
            }
        }
        
        $items = $CI->indexmodel->get_producatori();
        $data['options_producatori'] = $CI->functions->build_form_dropdown( $items, 'id', 'nume', 'ORICARE' ); 
        
        $data['options_pret_de_la'] = array( "" => "ORICARE" );
        for( $i=0; $i<=100; $i+=10 ) {
            $data['options_pret_de_la'][$i] = "{$i} lei";
        }
        
        $data['options_pret_pana_la'] = array( "" => "ORICARE" );
        for( $i=10; $i<=500; $i+=( $i>90 ? 100 : 10) ) {
            $data['options_pret_pana_la'][$i] = "{$i} lei";
        }

        $pagini = $CI->indexmodel->get_header_pages();
        $k = 0;
        $data['pagini_header'] = array();
        foreach( $pagini as $pagina ) {
            $data['pagini_header'][$k]['titlu'] = $pagina['titlu'];
            $data['pagini_header'][$k]['furl'] = $pagina['link_extern']!="" ? $pagina['link_extern'] : site_url( "info/{$pagina['slug']}" );
            $data['pagini_header'][$k]['target'] = $pagina['link_extern']!="" ? 'target="_blank"' : "";
        
            $k++;
        }

        $pagini = $CI->indexmodel->get_footer_pages();
        $k = 0;
        $data['pagini_footer'] = array();
        foreach( $pagini as $pagina ) {
            $data['pagini_footer'][$k]['titlu'] = $pagina['titlu'];
            $data['pagini_footer'][$k]['furl'] = $pagina['link_extern']!="" ? $pagina['link_extern'] : site_url( "info/{$pagina['slug']}" );
            $data['pagini_footer'][$k]['target'] = $pagina['link_extern']!="" ? 'target="_blank"' : "";
            
            $k++;
        }
        
        if( setare("FOOTER_LINK")!="" ) {
            $footer_link = explode("\n", setare("FOOTER_LINK"));
            $footer_text = explode("\n", setare("FOOTER_TEXT"));

            $k = 0;
            $data['footer_custom_links'] = array();
            foreach( $footer_link as $flink ) {
               $data['footer_custom_links'][$k]['link'] = $flink; 
               $data['footer_custom_links'][$k]['text'] = isset( $footer_text[$k] ) && $footer_text[$k]!="" ? $footer_text[$k] : ""; 
               $data['footer_custom_links'][$k]['separator'] = $k<count( $footer_link )-1 ? " | " : "";

               $k++;
            }
        }
       
        $CI->load->view('header', $data);
        $CI->load->view($data['page_view'], $data);
        $CI->load->view('footer', $data);
    }
    
}

/* End of file display.php */
/* Location: ./application/libraries/display.php */