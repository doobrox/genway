<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Functions {

    public $pConfig;

    

    public function __construct() {

        $this->pConfig['per_page'] = 8;

       

        $this->pConfig['full_tag_open'] = '<div class="paginare-linkuri">';

        $this->pConfig['full_tag_close'] = '</div>';

        

        $this->pConfig['last_link'] = 'Ultima &rsaquo;';

        $this->pConfig['first_link'] = '&lsaquo; Prima';

        

        $this->pConfig['next_link'] = 'Urmatoarea &rsaquo;';

        $this->pConfig['prev_link'] = 'Precedenta &lsaquo;';

        

        $this->pConfig['cur_tag_open'] = '<b>';

        $this->pConfig['cur_tag_close'] = '</b>';

    }

    

    public function make_furl( $tip, $titlu, $id ) {

        $segments = array(

            $tip,

            url_title($titlu, 'dash', TRUE)."-".$id

        );



        return site_url( $segments );

    }

    

    public function make_furl_simplu( $tip, $nume ) {

        return base_url()."{$tip}-". url_title($nume, 'dash', TRUE) ."/";

    }

    

    public function make_furl_categorie( $id_categorie, $add_base_url = true ) {

        $CI =& get_instance();

        

        $CI->db->from('categorii');

        $CI->db->where('id', $id_categorie);

        $categorie = $CI->db->get()->row_array();

        $furl = "";

        if( !empty( $categorie ) ) {

            do {

                $furl = "{$categorie['slug']}/{$furl}";

                

                $CI->db->from('categorii');

                $CI->db->where('id', $categorie['id_parinte']);

                $categorie = $CI->db->get()->row_array();

                

                if( isset($categorie['id_parinte']) && $categorie['id_parinte']==0 ) {

                    $furl = "{$categorie['slug']}/{$furl}";

                }

            } while( isset( $categorie['id_parinte'] ) && $categorie['id_parinte']!=0 );

        }

        

        return ($add_base_url ? base_url() : "") . $furl;

    }

    

    public function make_furl_produs( $nume_produs, $id_produs, $fnc = "" ) {

        $CI =& get_instance();

        

        $CI->db->from('produse_categorii');

        $CI->db->where('id_produs', $id_produs);

        $CI->db->limit(1);

        $categorie = $CI->db->get()->row_array();

        

        if( !empty( $categorie ) ) {

            $segments = array(

                rtrim( $this->make_furl_categorie($categorie['id_categorie'], false), "/" ),

                url_title($nume_produs, 'dash', TRUE)."-{$id_produs}",

                ($fnc!="" ? $fnc : "")

            );



            return site_url( $segments );

        }

        

        return NULL;

    }

    

    public function get_id_parinte_start( $id_categorie ) {

        $CI =& get_instance();

        

        $CI->db->from('categorii');

        $CI->db->where('id', $id_categorie);

        $categorie = $CI->db->get()->row_array();

 

        $id_parinte = 0;

        if( !empty( $categorie ) ) {

            do {

                $CI->db->from('categorii');

                $CI->db->where('id', $categorie['id_parinte']);

                $categorie = $CI->db->get()->row_array();

                

                if( isset($categorie['id_parinte']) && $categorie['id_parinte']==0 ) {

                    $id_parinte = $categorie['id'];

                }

            } while( isset( $categorie['id_parinte'] ) && $categorie['id_parinte']!=0 );

        }

        

        return $id_parinte;

    }

    

    

    public function make_breadcrumbs_categorie( $id_categorie ) {

        $CI =& get_instance();

        

        $CI->db->from('categorii');

        $CI->db->where('id', $id_categorie);

        $categorie = $CI->db->get()->row_array();

        $breadcrumbs = array();

        if( !empty( $categorie ) ) {

            do {

                $array_categorie = array( array(

                    "link" => $this->make_furl_categorie($categorie['id']),

                    "titlu" => $categorie['nume'],

                ) );

                $breadcrumbs = array_merge($array_categorie, $breadcrumbs);

                

                $CI->db->from('categorii');

                $CI->db->where('id', $categorie['id_parinte']);

                $categorie = $CI->db->get()->row_array();

                

                if( isset($categorie['id_parinte']) && $categorie['id_parinte']==0 ) {

                    $array_categorie = array( array(

                        "link" => $this->make_furl_categorie($categorie['id']),

                        "titlu" => $categorie['nume'],

                    ) );

                    $breadcrumbs = array_merge($array_categorie, $breadcrumbs);

                }

            } while( isset( $categorie['id_parinte'] ) && $categorie['id_parinte']!=0 );

        }

        $breadcrumbs = array_merge(array(array(

                                        "link" => base_url(),

                                        "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',

                                        "class" => "acasa"

                                    )), $breadcrumbs);

        return $breadcrumbs;

    }

    

    public function build_form_dropdown( $stack, $key = "id", $val = "nume", $first_val = "------------" ) {

        $options = array();

        

        if( $first_val!=FALSE ) {

            $first_val = $first_val===TRUE ? "-------------" : $first_val;

            $options[""] = $first_val; 

        }    

        

        foreach( $stack as $item ) {

            $options[$item[$key]] = $item[$val]; 

        }

        

        return $options;

    }

    

    public function generare_nr_factura() {

        $CI =& get_instance();

        $CI->db->select_max('nr_factura');

        $nr_factura = $CI->db->get('comenzi')->row()->nr_factura;



        if( $nr_factura!="" ) {

            $nr_factura = (int)$nr_factura;

            $nr_factura++;

            $nr_factura = str_repeat("0", 6-strlen($nr_factura)) . $nr_factura;

        } else {

            $nr_factura = "001000";

        }

        

        return $nr_factura;

    }

    

    public function generare_factura( $id_comanda ) {

        $CI =& get_instance();

        $CI->load->model('index_page_model', 'indexmodel');

        $CI->load->model('istoric_comenzi_model', 'istoric_comenzi_m');

        $CI->load->model('produs_model', 'produsm');

        $CI->load->model('user_model', 'userm');

        $CI->load->helper('setari');

        $CI->load->helper('dompdf');

        $CI->load->helper('file');

        

        $data['comanda'] = $CI->istoric_comenzi_m->get_comanda( $id_comanda ); 

        $data['comanda']['mesaj'] = nl2br( $data['comanda']['mesaj'] );



        $data['user'] = $CI->userm->get_user( $data['comanda']['id_user'] );

        if( $data['user']['livrare_adresa_1']==1 ) {

            $data['user'] = $CI->userm->get_user_livrare( $data['comanda']['id_user'] );

        }



        $items = $CI->istoric_comenzi_m->get_produse_comanda( $data['comanda']['id'] );

        $data['items'] = array();

        $k = 0;

        foreach ($items as $item) {

            $data['items'][$k] = $item;

            $data['items'][$k]['furl'] = $this->make_furl_produs( $item['nume'], $item['id_produs'] );



            $ids_filtre = explode(",", $item['filtre']);

            $data['items'][$k]['filtre'] = array();

            $y = 0;

            foreach ($ids_filtre as $id_filtru) {

                $filtru =  $CI->produsm->get_filtru( $id_filtru );

                if( !empty( $filtru ) ) {

                    $data['items'][$k]['filtre'][$y]['nume_parinte'] = $filtru['nume_parinte'];

                    $data['items'][$k]['filtre'][$y]['nume_filtru'] = $filtru['nume'];



                    $y++;

                }

            }





            $k++;

        }

        

        switch ($data['comanda']['id_tip_plata']) {

            case "1":

                $data['comanda']['text_plata'] = "prin ramburs";

                break;

            

            case "2":

                $data['comanda']['text_plata'] = "online";

                break;

            

            case "3":

                $data['comanda']['text_plata'] = "prin transfer bancar";

                break;

        }



        $content = $CI->load->view( "factura_proforma", $data, true );

        

        $nume_fisier = "factura{$data['comanda']['nr_factura']}.pdf";

        

        $data = pdf_create($content, '', false);

        write_file(realpath("")."/application/controllers/facturi/{$nume_fisier}", $data);



        return $nume_fisier;

        

    }



    function categoriesToTree(&$categories) {



        $map = array(

            0 => array('subcategories' => array())

        );



        foreach ($categories as &$category) {

            $category['subcategories'] = array();

            $map[$category['id']] = &$category;

        }



        foreach ($categories as &$category) {

            $map[$category['id_parinte']]['subcategories'][] = &$category;

        }



        return $map[0]['subcategories'];

    }

    

    function getCategoriesDepth( $id ) {

        $CI =& get_instance();

        $depth = 0;

        

        $query = $CI->db->select('id_parinte')

                ->from('categorii')

                ->where('id', $id)

                ->get()

                ->row_array();

        $current_parent_id = isset( $query['id_parinte'] ) ? $query['id_parinte'] : 0;

        while ($current_parent_id != 0) {

            $query = $CI->db->select('id_parinte')

                    ->from('categorii')

                    ->where('id', $current_parent_id)

                    ->get()

                    ->row_array();

            

            $current_parent_id = isset( $query['id_parinte'] ) ? $query['id_parinte'] : 0;

            $depth = $depth + 1;

        }

        

        return $depth;

    }

    

    function build_slug( $slug ) {

        $slug = preg_replace('/[^\da-z ]/i', '', $slug);

        

        return url_title( strtolower( trim( $slug ) ) );

    }

    

    function build_pret_formatat($pret) {

        @list( $intreg, $subunitar ) = explode(".", $pret);

        return "{$intreg}.<sup>{$subunitar}</sup>";

    }

    

    public function pret_format( $pret ) {

        return number_format($pret, 2, ',', '.');

    }

    

    function calculare_total_cos($id_curier = "", $cod_voucher = "", $tip_plata = "") {

        $CI =& get_instance();

        

        $CI->load->helper('setari');

        $CI->load->library('cart');

        
        $cota_tva = (int)setare('COTA_TVA');


        $subtotal = $CI->cart->total();

        $tva = $cota_tva>0 ? $subtotal*$cota_tva/100 : 0;

        $taxa_expediere = $this->calculare_taxa_expediere( $id_curier );

        $valoare_voucher = $this->calculare_valoare_voucher( $cod_voucher );
        
        $discount_fidelitate = $this->calculare_discount_fidelitate();
        
        $discount_plata_op = $this->calculare_discount_plata_op( $tip_plata );

        $total = $subtotal + $tva + $taxa_expediere + $valoare_voucher + $discount_fidelitate + $discount_plata_op;
        

        return $total;

    }

    

    public function calculare_taxa_expediere($id_curier) {

        $CI =& get_instance();

        

        $CI->load->model('index_page_model', 'indexm');
        $CI->load->model('produs_model', 'produsm');
        $CI->load->model('user_model', 'userm');

        $CI->load->helper('setari');

        $CI->load->library('cart');

        

        $cota_tva = (int)setare('COTA_TVA');

        $subtotal = $CI->cart->total();

        $tva = $cota_tva>0 ? $subtotal*$cota_tva/100 : 0;

        $total = $subtotal + $tva;


        $curier = $CI->indexm->get_curier( $id_curier );

        $taxa = 0;

        if( !empty( $curier ) ) {

                $greutate = 0;

                foreach( $CI->cart->contents() as $item ) {
                    $produs = $CI->produsm->get_produs( $item['id'] );

                    if( !empty( $produs ) ) {

                        $greutate += ($produs['greutate'] * $item['qty']);

                    }
                }

                $greutate = $greutate>0 ? $greutate : 1;
                
                
                $km_exteriori = 0;
                if( $CI->simpleloginsecure->is_logat() ) {
                    $user = $CI->userm->get_user( $CI->session->userdata("id") );
                    if( !empty( $user ) ) {
                        $id_localitate = $user['id_localitate'];
                        if( $user['livrare_id_localitate']==1 ) {
                            $id_localitate = $user['livrare_id_localitate'];
                        }
                        
                        $km_exteriori = $CI->indexm->get_km_exteriori( $id_localitate );
                    }
                }

                //(pret_prim_kg + kg_aditionali*pret_kg_aditional + taxa_ramburs + procent ramburs din valoare comanda + taxa km ext * km ext) * 1.24 (TVA)
                //ex. colet 10kg, 500ron (valoare comanda), 10km exteriori: (1*12+9*0.8+5+0.005*500+1*10)*1.24 -----/// 1.24 = TVA
                //echo "({$curier['pret_primul_kg']} + {$curier['pret_kg_aditional']}*($greutate-1) + {$curier['procent_ramburs']} + $subtotal*{$curier['procent_ramburs']}/100 + {$curier['taxa_km_exteriori']} * $km_exteriori)*1.24<br />";
                
                $taxa = round( ( $curier['pret_primul_kg'] + $curier['pret_kg_aditional']*($greutate-1) + $curier['procent_ramburs'] + $subtotal*$curier['procent_ramburs']/100 + $curier['taxa_km_exteriori'] * $km_exteriori )*1.20, 2 );

        }
        
        return $taxa;

    }

    

    function calculare_valoare_voucher_vechi( $cod_voucher ) {

        if( $cod_voucher=="" ) return 0; 

        

        $CI =& get_instance();

        

        $CI->load->model('index_page_model', 'indexm');

        $CI->load->helper('setari');

        $CI->load->library('cart');

        $subtotal = $CI->cart->total();     

        $voucher = $CI->indexm->get_voucher($cod_voucher);

        if( empty( $voucher ) ) {

            return 0;

        }

        switch ($voucher['tip']) {

            case "1":

                return $voucher['valoare'] * -1;

            

            case "2":

                return ( $voucher['valoare']*$subtotal/100 ) * -1;

        }

        

        return 0;

    }

    function calculare_valoare_voucher( $cod_voucher ) {

        if( $cod_voucher=="" ) return 0; 

        

        $CI =& get_instance();

        

        $CI->load->model('index_page_model', 'indexm');

        $CI->load->helper('setari');

        $CI->load->library('cart');

        foreach ($CI->cart->_cart_contents as $produs){
            $voucher = $CI->indexm->get_voucher_produs($cod_voucher, $produs['id']);
            $subtotal = $produs['price'];
            if (empty($voucher) === false){
                break;    
            }
        }
        
        $vau = $CI->indexm->get_voucher_produs_exista($cod_voucher);

        if( empty( $voucher ) and empty($vau)) {
            $subtotal = $CI->cart->total(); 
            $voucher = $CI->indexm->get_voucher($cod_voucher);

        }      

        if( empty( $voucher ) ) {

            return 0;

        }

        

        switch ($voucher['tip']) {

            case "1":

                return $voucher['valoare'] * -1;

            

            case "2":

                return ( $voucher['valoare']*$subtotal/100 ) * -1;

        }

        return 0;

    }
    
    function calculare_discount_fidelitate() {
        $CI =& get_instance();
        $CI->load->model('index_page_model', 'indexm');
        $CI->load->library('cart');

        $subtotal = $CI->cart->total();
        
        $id_user = $CI->session->userdata("id");
        $discount_fidelitate = $CI->indexm->get_discount_fidelitate( $id_user ); 
        
        if( $discount_fidelitate>0 && $subtotal>0 ) {
            return $discount_fidelitate/100 * $subtotal * -1;
        }
        
        return 0;
    }
    
    function calculare_discount_plata_op($tip_plata) {
        $CI =& get_instance();
        $CI->load->model('index_page_model', 'indexm');
        $CI->load->library('cart');
        $CI->load->helper('setari');
        
        $subtotal = $CI->cart->total();
        if( $tip_plata==3 ) {
            $reseller = $CI->session->userdata("reseller");
            if( $reseller==1 ) {
                $discount_plata_op = setare( "REDUCERE_PLATA_OP_RESELLER" ); 
            } else {
                $discount_plata_op = setare( "REDUCERE_PLATA_OP_PF" ); 
            }

            if( $discount_plata_op>0 && $subtotal>0 ) {
                return ($discount_plata_op/100 * $subtotal * -1);
            }
        }
        
        return 0;
    }
    
    function get_valoare_discount_plata_op() {
        $CI =& get_instance();
        $CI->load->helper('setari');
        
        $discount_plata_op = 0;
        $id_tip_plata = $CI->session->userdata("id_tip_plata");
        if( $id_tip_plata==3 ) {
            $reseller = $CI->session->userdata("reseller");
            if( $reseller==1 ) {
                $discount_plata_op = setare( "REDUCERE_PLATA_OP_RESELLER" ); 
            } else {
                $discount_plata_op = setare( "REDUCERE_PLATA_OP_PF" ); 
            }
        }
        
        return $discount_plata_op;
    }

    

    function google_adwords_conversions_code() {

        return '<!-- Google Code for Comanda Conversion Page -->

                <script type="text/javascript">

                /* <![CDATA[ */

                var google_conversion_id = 1000677025;

                var google_conversion_language = "ro";

                var google_conversion_format = "3";

                var google_conversion_color = "ffffff";

                var google_conversion_label = "jmRdCK-S0gMQob2U3QM";

                var google_conversion_value = 0;

                var google_remarketing_only = false;

                /* ]]> */

                </script>

                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">

                </script>

                <noscript>

                <div style="display:inline;">

                <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1000677025/?value=0&amp;label=jmRdCK-S0gMQob2U3QM&amp;guid=ON&amp;script=0"/>

                </div>

                </noscript>

                ';

    }

    

    public function db_sync_request( $cod_produse = array() ) {

        $CI =& get_instance();

        $sync_url = "http://www.lenjeriidepatmoderne.ro/index_page/db_sync_produse";

        

        if( !empty( $cod_produse ) ) {

            $fields_count = 0;

            $fields_string = "sync=1&";

            foreach( $cod_produse as $cod ) {

                $produs = $CI->db->select("stoc")->where("cod_ean13", $cod)->get("produse")->row_array();

                if( !empty( $produs ) ) {

                    $fields_string .= "cod_produs[]={$cod}&stoc[]={$produs['stoc']}&";



                    $fields_count += 2;

                }

            }

            $fields_string = rtrim($fields_string, "&");



            $ch = curl_init();



            curl_setopt($ch,CURLOPT_URL, $sync_url);

            curl_setopt($ch,CURLOPT_POST, $fields_count);

            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



            $result = curl_exec($ch);



            curl_close($ch);

        }

    }

}



/* End of file functions.php */

/* Location: ./application/libraries/functions.php */