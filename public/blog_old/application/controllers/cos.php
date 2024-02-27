<?php



if (!defined('BASEPATH')) exit('No direct script access allowed');



class cos extends CI_Controller {

    

     public function __construct() {

        parent::__construct();

        //$this->output->enable_profiler(true);

        $this->load->library('cart');

        $this->load->driver('cache');

        $this->load->model('index_page_model', 'indexmodel');

        $this->load->model('produs_model', 'produsm');

        $this->load->model('istoric_comenzi_model', 'istoric_comenzi_m');

        $this->load->helper('setari');
        $this->load->helper('email_template');
        $this->load->helper('html');

    }

    

    public function index( $data = array() ) {

        if( $this->session->flashdata('succes')!="" ) {

            $data['succes'] = $this->session->flashdata('succes');

        }


        $order_id = $this->input->get('orderId');

        if( $order_id!="" ) {

            $data['comanda'] = $this->istoric_comenzi_m->get_comanda_by_order_id( $order_id );

            $data['stare'] = $this->istoric_comenzi_m->get_stare_comanda( $order_id );

        }

        

        $items = $this->indexmodel->get_curieri();

        $data['options_curieri'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "Alege modalitatea de expediere" ); 

        

        $data['options_plata'] = array(

            "" => "Alege modalitate de plata",

            "1" => "Ramburs", //selectat default

            "2" => "Plata cu cardul",

            "3" => "Transfer Bancar",

        );

        

        $data['cota_tva'] = setare('COTA_TVA');

        

        $data['id_curier'] = $this->session->userdata('id_curier') ? $this->session->userdata('id_curier') : $this->indexmodel->get_curier_default();

        $data['cod_voucher'] = $this->session->userdata('cod_voucher') ? $this->session->userdata('cod_voucher') : "";

        $data['id_tip_plata'] = $this->session->userdata('id_tip_plata');

        $data['mesaj'] = $this->session->userdata('mesaj');

        

        $data['voucher'] = $this->indexmodel->get_voucher( $data['cod_voucher'] );



        $data['taxa_expediere'] = $this->functions->calculare_taxa_expediere( $data['id_curier'] );

        $data['taxa_expediere'] = $data['taxa_expediere']>0 ? "+{$data['taxa_expediere']}" : 0;

        

        $data['valoare_voucher'] = $this->functions->calculare_valoare_voucher( $data['cod_voucher'] );

        $data['valoare_voucher'] = $data['valoare_voucher']<0 ? "-" . $this->cart->format_number( $data['valoare_voucher'] ) : 0;

        
        $data['discount_plata_op'] = $this->functions->get_valoare_discount_plata_op();
        $data['valoare_discount_plata_op'] = $this->functions->calculare_discount_plata_op( $data['id_tip_plata'] );
        $data['valoare_discount_plata_op'] = $data['valoare_discount_plata_op']<0 ? "-" . $this->cart->format_number( $data['valoare_discount_plata_op'] ) : 0;

        if( $this->simpleloginsecure->is_logat() ) {
            $id_user = $this->session->userdata('id');
            
            $data['discount_fidelitate'] = $this->indexmodel->get_discount_fidelitate( $id_user );
            
            $data['valoare_discount_fidelitate'] = $this->functions->calculare_discount_fidelitate( $id_user );
            $data['valoare_discount_fidelitate'] = $data['valoare_discount_fidelitate']<0 ? "-" . $this->cart->format_number( $data['valoare_discount_fidelitate'] ) : 0;
        }


        $cart = $this->cart->contents();

        $data['cart'] = array();

        $error_stoc = "";

        $k = 0;

        foreach( $cart as $item ) {

            $produs = $this->produsm->get_produs( $item['id'] );      

            $data['cart'][$k] = $item;

            $data['cart'][$k]['furl'] = $this->functions->make_furl_produs( $produs['nume'], $produs['id'] );

            $data['cart'][$k]['imagine'] = $produs['imagine'];

            $data['cart'][$k]['price'] = $this->cart->format_number($item['price']);

            $data['cart'][$k]['subtotal'] = $this->cart->format_number($item['subtotal']);

            $data['cart'][$k]['cod_ean13'] = $produs['cod_ean13'];

            

            if ($this->cart->has_options( $item['rowid'] ) == TRUE) {

                $options = $this->cart->product_options($item['rowid']);

                $data['cart'][$k]['options'] = array();

                $y = 0;

                foreach ($options as $id_filtru) {

                    $filtru = $this->produsm->get_filtru( $id_filtru );

                    $data['cart'][$k]['options'][$y]['nume_parinte'] = $filtru['nume_parinte'];

                    $data['cart'][$k]['options'][$y]['nume_filtru'] = $filtru['nume'];



                    $y++;

                }

            }

            

            if( $produs['stoc']<$item['qty'] ) {

                $error_stoc .= "Nu exista {$item['qty']} x " . anchor( $data['cart'][$k]['furl'], $produs['nume'] ) .". Stocul actual este de: {$produs['stoc']} produse. <br />";

            }

            

            $k++; 

        }



        if( $k > 0 ) {

            $data['cota_tva'] = setare('COTA_TVA');

            $data['cod_voucher'] = $this->session->userdata('cod_voucher') ? $this->session->userdata('cod_voucher') : "";

            $data['id_curier'] = $this->session->userdata('id_curier') ? $this->session->userdata('id_curier') : $this->indexmodel->get_curier_default();
            
            $data['id_tip_plata'] = $this->session->userdata('id_tip_plata') ? $this->session->userdata('id_tip_plata') : "";



            $data['subtotal'] = $this->cart->format_number( $this->cart->total() );

            $data['tva'] = $data['cota_tva']>0 ? $this->cart->format_number( $this->cart->total() * $data['cota_tva']/100 ) : 0;

            $data['total'] = $this->cart->format_number( $this->functions->calculare_total_cos( $data['id_curier'], $data['cod_voucher'], $data['id_tip_plata'] ) );

        }

        

        if( $error_stoc!="" ) {

            $data['error'] = $error_stoc;

        }

        

        $data['breadcrumbs'] = array(

            array(

                "link" => base_url(),

                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',

                "class" => "acasa"

            ),

            array(

                "link" => site_url("cos"),

                "titlu" => "Cosul de cumparaturi",

            ),

        );

        

        $data["page_view"] = "cos";

        $data["title"] = "Cos de cumparaturi | " . setare('TITLU_NUME_SITE');

        $data["meta_description"] =  "";

        

        $this->load->library('display', $data);

    }

    

    public function adauga_produs() {

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_produs', 'ID produs', 'trim|required|numeric');

        $this->form_validation->set_rules('cantitate', 'Cantitate', 'trim|required|numeric');

        

        if ($this->form_validation->run() == TRUE) {

            $p = $this->input->post();

            $produs = $this->produsm->get_produs( $p['id_produs'] );



            $item = array(

               'id'      => $p['id_produs'],

               'qty'     => $p['cantitate'],

               'price'   => $produs['pret'],

               'name'    => $produs['nume']

            );

            

            if( isset( $p['id_filtre'] ) ) {

                $id_filtre = array_filter( $p['id_filtre'] );

                sort( $id_filtre );

                $id_filtre = json_encode($id_filtre);

                

                $filtru = $this->produsm->get_filtru_produs( $p['id_produs'], $id_filtre );

                

                if( !empty( $filtru ) ) {

                    if( $filtru['pret']=="" ) {

                        $data['pret_intreg_format'] = round( $produs['pret_intreg'], 2);

                        $item['price'] = $produs['pret'];

                    } else {

                        $data['pret_intreg_format'] = round( $filtru['pret'], 2 );

                        switch ($produs['reducere_tip']) {

                            case '1':

                                $item['price'] = $filtru['pret'] - $filtru['reducere_valoare'];

                                break;



                            case '2':

                                $item['price'] = $filtru['pret'] - ( $produs['reducere_valoare']/100*$filtru['pret'] );

                                break;



                            default:

                                $item['price'] = $filtru['pret'];

                                break;

                        }

                    }

                }

                

                $id_filtre = array_filter($p['id_filtre']);

                $id_filtre = array_unique($id_filtre);

                sort($id_filtre);

                

                $item['options'] = array();

                foreach ($id_filtre as $id_filtru) {

                    $item['options'][] = $id_filtru;

                }

            }

            

            $cart = $this->cart->contents();

            foreach ($cart as $prod) {

                if( $prod['id']==$p['id_produs'] ) {

                    $item['qty'] = $prod['qty'] + $p['cantitate'];

                    break;

                } 

            }



            $this->cart->product_name_rules = '[:print:]';

            if( $this->cart->insert($item) ) {

                $this->session->set_flashdata('succes', "Produsul a fost adaugat cu succes in lista.");

                redirect( "cos" );

            }

        }

        

        $this->index();

    }

    

    public function salveaza() {

        $p = $this->input->post();
        

        if( isset( $p['sterge'] ) ) {

            foreach( $p['id'] as $rowid ) {

                $data = array(

                   'rowid' => $rowid,

                   'qty'   => 0

                );



                $this->cart->update($data); 

            }

            redirect( "cos" );

        } elseif( isset( $p['salveaza'] ) || isset( $p['salveaza_x'] ) || isset( $p['salveaza_y'] ) ) {

            $this->session->set_userdata('id_curier', $p['id_curier']);

            $this->session->set_userdata('id_tip_plata', $p['id_tip_plata']);

            $this->session->set_userdata('mesaj', (isset($p['mesaj']) ? $p['mesaj'] : ""));

            

            for( $i=0; $i<count($p['rowid']); $i++ ) {

                $data = array(

                   'rowid' => $p['rowid'][$i],

                   'qty'   => $p['qty'][$i]

                );



                $this->cart->update($data); 

            }

            redirect( "cos" );

        } elseif( isset( $p['goleste'] ) ) {

            $this->session->unset_userdata('id_curier');

            $this->session->unset_userdata('id_tip_plata');

            $this->session->unset_userdata('cod_voucher');

            $this->session->unset_userdata('mesaj');

            

            $this->cart->destroy();

            redirect('cos');

        } elseif( isset( $p['trimite'] ) ) {
            $this->trimite();
        }

    }

    

    public function sterge($rowid) {

        $data = array(

           'rowid' => $rowid,

           'qty'   => 0

        );



        $this->cart->update($data);

        

        redirect('cos');

    }
    

    public function trimite() {

        $this->load->model('user_model', 'userm');

        $data = array();

        

        if (!$this->simpleloginsecure->is_logat()) {

            redirect( base_url()."login?link=" . urlencode(site_url("cos") ) );

        }
        

        $this->load->library('form_validation');

        

        $p = $this->input->post();

        $userdata = $this->userm->get_user($this->session->userdata('id'));

        if ($userdata['tip'] == 1){
            
            $this->form_validation->set_rules('id_curier', 'Tip expediere', 'trim|required|numeric|callback_check_cnp');
            
        } else {

            $this->form_validation->set_rules('id_curier', 'Tip expediere', 'trim|required|numeric');

        }
        

        $this->form_validation->set_rules('id_tip_plata', 'Tip plata', 'trim|required|numeric');

        $this->form_validation->set_rules('mesaj', 'Mesaj', 'trim|xss_clean');



        if ($this->form_validation->run() == TRUE) {

            $cart = $this->cart->contents();

            if( empty( $cart ) ) return false;

            $p = $this->input->post();

            

            $cod_voucher = $this->session->userdata("cod_voucher");

            

            //verificare daca toate produsele sunt in stoc

            $error_stoc = "";

            foreach( $cart as $item ) {

                $prod = $this->produsm->get_stoc_nume_produs( $item['id'] );

                

                if( $prod['stoc']<$item['qty'] ) {

                    $prod['furl'] = $this->functions->make_furl_produs($prod['id'], $prod['nume']);

                    $error_stoc .= "Nu exista {$item['qty']} x " . anchor( $prod['furl'], $prod['nume'] ) .". Stocul actual este de: {$prod['stoc']} produse.<br />";

                }

            }
            

            if( $error_stoc!="" ) {

                $data['error'] = $error_stoc;

            } else {
                $id_user = $this->session->userdata('id');
                
                $taxa_livrare = $this->functions->calculare_taxa_expediere( $p['id_curier'] );

                $valoare_voucher = $this->functions->calculare_valoare_voucher( $cod_voucher );
                
                $discount_fidelitate = $this->indexmodel->get_discount_fidelitate( $id_user );
                
                $valoare_discount_fidelitate = $this->functions->calculare_discount_fidelitate( $id_user );
                
                $discount_plata_op = $this->functions->get_valoare_discount_plata_op();
                
                $valoare_discount_plata_op = $this->functions->calculare_discount_plata_op( $p['id_tip_plata'] );


                $this->db->set("id_user", $id_user);

                $this->db->set("id_curier", $p['id_curier']);

                $valoare_voucher!=0 ? $this->db->set("cod_voucher", $cod_voucher) : "";

                $this->db->set("id_tip_plata", $p['id_tip_plata']);

                $this->db->set("nr_factura",  $this->functions->generare_nr_factura());

                $this->db->set("mesaj", $p['mesaj']);

                $this->db->set("taxa_livrare", $taxa_livrare);

                $this->db->set("valoare_voucher", $valoare_voucher);
                
                $this->db->set("discount_fidelitate", $discount_fidelitate);
                
                $this->db->set("valoare_discount_fidelitate", $valoare_discount_fidelitate);
                
                $this->db->set("discount_plata_op", $discount_plata_op);
                
                $this->db->set("valoare_discount_plata_op", $valoare_discount_plata_op);

                $this->db->set("tva", setare('COTA_TVA'));

                $this->db->set("data_adaugare", "NOW()", FALSE);

                $this->db->set("stare", "0");

                if( $this->db->insert("comenzi") ) {

                    $id_comanda = $this->db->insert_id();



                    foreach( $cart as $item ) {

                        $filtre = "";

                        if ($this->cart->has_options( $item['rowid'] ) == TRUE) {

                            $options = $this->cart->product_options($item['rowid']);

                            $filtre_produs = array();

                            foreach ($options as $id_filtru) {

                                $filtre_produs[] = $id_filtru;

                            }

                            $filtre = implode(",", $filtre_produs);

                        }



                        $this->db->set("id_comanda", $id_comanda);

                        $this->db->set("id_produs", $item['id']);

                        $this->db->set("cantitate", $item['qty']);

                        $this->db->set("pret", $item['price'] );

                        $this->db->set("filtre", $filtre );

                        $this->db->insert("comenzi_produse");

                    }



                    //update stoc

                    foreach( $cart as $item ) {

                        $this->db->set("stoc", "stoc-{$item['qty']}", FALSE);

                        $this->db->where("id", $item['id']);

                        $this->db->update("produse");

                    }



                    //TRIMITERE EMAILURI

                    $fisier = $this->functions->generare_factura( $id_comanda );

                    $this->load->library('email');

                    $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));

                    $this->email->attach( dirname(__FILE__) . "/facturi/{$fisier}" );



                    $data['comanda'] = $this->istoric_comenzi_m->get_comanda( $id_comanda ); 

                    $data['comanda']['mesaj'] = nl2br( $data['comanda']['mesaj'] );

                    

                    $data['comanda']['metoda_plata'] = "";

                    switch ($data['comanda']['id_tip_plata']) {

                        case "1":

                            $data['comanda']['metoda_plata'] = "Ramburs";

                            break;



                        case "2":

                            $data['comanda']['metoda_plata'] = "Online Mobilpay.ro";

                            break;



                        case "3":

                            $data['comanda']['metoda_plata'] = "Transfer bancar";

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



                    //TRIMITERE CATRE CLIENT
                    //preluare template email din baza de date
                    $info = array(
                        "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                        "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                        "__LINK_COMANDA__" => site_url("istoric_comenzi/comanda/{$id_comanda}"),
                    );

                    $data['titlu'] = email_template(3, "subiect", $info);
                    $data['continut'] = email_template(3, "continut", $info);
                    
                    $message = $this->load->view("template_comanda", $data, true);

                    

                    $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));

                    $this->email->to( $data['user']['user_email'] );

                    $this->email->subject( $data['titlu'] );

                    $this->email->message( $message );

                    $this->email->send();



                    //TRIMITERE CATRE ADMIN
                    //preluare template email din baza de date
                    $info = array(
                        "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                        "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                        "__LINK_SITE__" => base_url(),
                        "__LINK_COMANDA__" => site_url("admin/comenzi/comanda/{$id_comanda}"),
                    );

                    $data['titlu'] = email_template(4, "subiect", $info);
                    $data['continut'] = email_template(4, "continut", $info);
                    
                    $message = $this->load->view("template_comanda", $data, true);

                    $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));

                    $this->email->to( setare('EMAIL_CONTACT') );

                    $this->email->reply_to( $data['user']['user_email'], "{$data['user']['nume']} {$data['user']['prenume']}" );

                    $this->email->subject( $data['titlu'] );

                    $this->email->message( $message );

                    $this->email->send();



                    //update vouchere

                    $voucher = $this->indexmodel->get_voucher( $cod_voucher );

                    if( $voucher['caracter']=="2" ) { //caracter unic

                        $this->db->set("activ", 0);

                        $this->db->where("cod", $cod_voucher);

                        $this->db->update("vouchere");

                    }

                    

                    $this->session->unset_userdata('id_curier');

                    $this->session->unset_userdata('cod_voucher');

                    $this->session->unset_userdata('id_tip_plata');

                    $this->session->unset_userdata('mesaj');

                    $this->cart->destroy();



                    switch ($p['id_tip_plata']) {

                        case 1:

                            $this->session->set_flashdata('succes', 'Comanda a fost salvata cu succes. Modalitatea de plata aleasa este ramburs. ' . anchor("istoric_comenzi/comanda/{$id_comanda}", "Vezi datele comenzii")); 

                            break;



                        case 2:

                            $this->session->set_flashdata('succes', 'Comanda a fost salvata cu succes. Veti fi redirectionat spre pagina de plata... <br />' . img( MAINSITE_STYLE_PATH . "images/logo_mobilpay.png" ));

                            break;



                        case 3:

                            $this->session->set_flashdata('succes', 'Comanda a fost salvata cu succes. O factura proforma a fost generata si trimisa catre adresa dvs. de email. <br />' . anchor("istoric_comenzi/comanda/{$id_comanda}", "Vezi datele comenzii")); 

                            break;

                    }



                    //STERGERE CACHE

                    $cod_produse = array();

                    foreach ($cart as $item) {

                        $produs = $this->produsm->get_produs( $item['id'] );

                        

                        if( !empty( $produs ) ) {

                            $cod_produse[] = $produs['cod_ean13'];

                            

                            $produse_ce_recomanda_prod = $this->produsm->get_produse_ce_recomanda_prod( $item['id'] );

                            foreach ($produse_ce_recomanda_prod as $prod) {

                                $this->cache->file->delete("produse_recomandate_{$prod['id_produs']}");

                            }

                            

                            $produse_categorii = $this->produsm->get_categorii_produs( $item['id'] );

                            foreach ($produse_categorii as $prod) {

                                $this->cache->file->delete_regexp("/produse_{$prod['id_categorie']}_.*/");

                            }

                            

                            if( $produs['promovat_index']==1 ) {

                                $this->cache->file->delete("produse_promovate_index");

                            }

                            

                            $this->cache->file->delete("produse_recomandate_{$produs['id']}");

                            $this->cache->file->delete_regexp("/produse_[0-9]{0,}_[0-9]{0,}_[0-9]{0,}_" . ( $produs['reducere_tip']!="0" ? "1" : "0" ) . "_.*/");

                            $this->cache->file->delete_regexp("/produse_[0-9]{0,}_[0-9]{0,}_[0-9]{0,}_[0-9]{0,}_{$produs['promotie']}_.*/");

                            $this->cache->file->delete_regexp("/produse_[0-9]{0,}_{$produs['id_producator']}.*/");

                        }

                    }

                    

                    //sync stoc

                    $this->functions->db_sync_request( $cod_produse );



                    redirect( base_url()."cos/procesare_plata?id={$id_comanda}&id_tip_plata={$p['id_tip_plata']}" );

                }

            }

        }

        

        $this->index( $data );

    }

    public function check_cnp(){

        $this->load->model('user_model', 'userm');

        $userdata = $this->userm->get_user($this->session->userdata('id'));

    	if (!empty($userdata['cnp'])) {
    		return true;
    	} else {
            $this->form_validation->set_message('check_cnp', 'Pentru a plasa comanda trebuie adaugat CNP-ul in <a href="'.base_url().'profilul_meu">cont</a>');
    		return false;
    	}

    }

    public function plata( $order_id = "", $data = array()) {

        

        if (!$this->simpleloginsecure->is_logat()) {

            $this->load->helper('url');

            redirect("login/index/error");

        }

        

        if( $order_id!="" ) {

            $data['stare'] = $this->istoric_comenzi_m->get_stare_comanda( $order_id );

        }



        $data["title"] = "Procesare plata... | " . setare('TITLU_NUME_SITE');

        $data["page_view"] = "procesare_plata";

        $this->load->library('display', $data);

    }



    public function cancel( $order_id = "" ) {

        //TODO: cancel

        $this->db->set( 'stare_plata', -1 );

        $this->db->where( 'order_id', $order_id );

        $this->db->update( 'comenzi' );

        

        $this->plata( $order_id );

    }

    

    public function procesare_plata() {

        //TODO: verificari in plus dupa id_comanda ... ?????

        $data = array();

        $p = $this->input->get();

        if( !isset( $p['id'] ) || !is_numeric($p['id']) ) exit;

        if( !isset( $p['id_tip_plata'] ) || !is_numeric($p['id_tip_plata']) ) exit;

        

        switch ($p['id_tip_plata']) {

         case 2:

            $this->load->model('istoric_comenzi_model', 'istoric_comenzi_m');

             

            $comanda = $this->istoric_comenzi_m->get_comanda( $p['id'] );

             

            require_once dirname(__FILE__) . '/Mobilpay/Payment/Request/Abstract.php';

            require_once dirname(__FILE__) . '/Mobilpay/Payment/Request/Sms.php';

            require_once dirname(__FILE__) . '/Mobilpay/Payment/Request/Card.php';

            require_once dirname(__FILE__) . '/Mobilpay/Payment/Invoice.php';

            require_once dirname(__FILE__) . '/Mobilpay/Payment/Address.php';



            $data['paymentUrl'] = 'http://sandboxsecure.mobilpay.ro';

//            $data['paymentUrl'] = 'https://secure.mobilpay.ro';

            $x509FilePath = dirname(__FILE__) . '/Mobilpay/public.cer';



            srand((double) microtime() * 1000000);

            $order_id = md5(uniqid(rand()));



            try {

                $objPmReqCard = new Mobilpay_Payment_Request_Card();

                $objPmReqCard->signature = setare( 'MOBILPAY_CHEIE_UNICA' );

                $objPmReqCard->orderId = $order_id;

                $objPmReqCard->confirmUrl = base_url() . 'cos/card_confirm';

                $objPmReqCard->returnUrl = base_url() . 'cos';



                $objPmReqCard->invoice = new Mobilpay_Payment_Invoice();

                $objPmReqCard->invoice->currency = 'RON';

                $objPmReqCard->invoice->amount = $comanda['valoare'];

                $objPmReqCard->invoice->details = 'Plata cu cardul pentru suma';

                $objPmReqCard->encrypt($x509FilePath);



                $data['env_key'] = $objPmReqCard->getEnvKey();

                $data['env_data'] = $objPmReqCard->getEncData();

            }

            catch(Exception $e) {}



            $this->db->set( 'order_id', $order_id );

            $this->db->where( 'id', $p['id'] );

            $this->db->update( 'comenzi' );



             break;



         default:

             break;

        }

        

        $this->index( $data );

    }

    

    public function card_confirm() {

        require_once dirname(__FILE__) . '/Mobilpay/Payment/Request/Abstract.php';

        require_once dirname(__FILE__) . '/Mobilpay/Payment/Request/Card.php';

        require_once dirname(__FILE__) . '/Mobilpay/Payment/Request/Notify.php';

        require_once dirname(__FILE__) . '/Mobilpay/Payment/Invoice.php';

        

        $errorCode 		= 0;

        $errorType		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_NONE;

        $errorMessage	= '';



        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') == 0) {

            if(isset($_POST['env_key']) && isset($_POST['data'])) {

            #calea catre cheia privata

            #cheia privata este generata de mobilpay, accesibil in Admin -> Conturi de comerciant -> Detalii -> Setari securitate

                $privateKeyFilePath = dirname(__FILE__) . '/Mobilpay/private.key';

                try {

                    $objPmReq = Mobilpay_Payment_Request_Abstract::factoryFromEncrypted($_POST['env_key'], $_POST['data'], $privateKeyFilePath);



                    //echo "Action: ".$objPmReq->objPmNotify->action."\n";



                    switch($objPmReq->objPmNotify->action) {

                    #orice action este insotit de un cod de eroare si de un mesaj de eroare. Acestea pot fi citite folosind $cod_eroare = $objPmReq->objPmNotify->errorCode; respectiv $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                    #pentru a identifica ID-ul comenzii pentru care primim rezultatul platii folosim $id_comanda = $objPmReq->orderId;

                        case 'confirmed':

                        #cand action este confirmed avem certitudinea ca banii au plecat din contul posesorului de card si facem update al starii comenzii si livrarea produsului

                            $this->confirm_plata( $objPmReq->orderId );

                            

                            $this->db->set( 'stare_plata', 1 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );

                            

                            $errorMessage = $objPmReq->objPmNotify->getCrc();

                            $cod_eroare = $objPmReq->objPmNotify->errorCode;

                            $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                            break;

                        case 'confirmed_pending':

                        #cand action este confirmed_pending inseamna ca tranzactia este in curs de verificare antifrauda. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.



                            $this->db->set( 'stare_plata', 0 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );



                            $errorMessage = $objPmReq->objPmNotify->getCrc();

                            $cod_eroare = $objPmReq->objPmNotify->errorCode;

                            $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                            break;

                        case 'paid_pending':

                        #cand action este paid_pending inseamna ca tranzactia este in curs de verificare. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.



                            $this->db->set( 'stare_plata', 0 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );



                            $errorMessage = $objPmReq->objPmNotify->getCrc();

                            $cod_eroare = $objPmReq->objPmNotify->errorCode;

                            $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                            break;

                        case 'paid':

                        #cand action este paid inseamna ca tranzactia este in curs de procesare. Nu facem livrare/expediere. In urma trecerii de aceasta procesare se va primi o noua notificare pentru o actiune de confirmare sau anulare.

                            $this->db->set( 'stare_plata', 0 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );



                            $errorMessage = $objPmReq->objPmNotify->getCrc();

                            $cod_eroare = $objPmReq->objPmNotify->errorCode;

                            $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                            break;

                        case 'canceled':

                        #cand action este canceled inseamna ca tranzactia este anulata. Nu facem livrare/expediere.



                            $this->db->set( 'stare_plata', -1 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );



                            $errorMessage = $objPmReq->objPmNotify->getCrc();

                            $cod_eroare = $objPmReq->objPmNotify->errorCode;

                            $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                            break;

                        case 'credit':

                        #cand action este credit inseamna ca banii sunt returnati posesorului de card. Daca s-a facut deja livrare, aceasta trebuie oprita sau facut un reverse.



                            $this->db->set( 'stare_plata', -1 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );



                            $errorMessage = $objPmReq->objPmNotify->getCrc();

                            $cod_eroare = $objPmReq->objPmNotify->errorCode;

                            $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;

                            break;

                        default:

                            $this->db->set( 'stare_plata', -1 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );



                            $errorType		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;

                            $errorCode 		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_ACTION;

                            $errorMessage 	= 'mobilpay_refference_action paramaters is invalid';

                            break;

                    }



                    if( $cod_eroare!=0 ) {

                            $this->db->set( 'stare_plata', -1 );

                            $this->db->where( 'order_id', $objPmReq->orderId );

                            $this->db->update( 'comenzi' );

                    }

                }

                catch(Exception $e) {

                    $errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_TEMPORARY;

                    $errorCode		= $e->getCode();

                    $errorMessage 	= $e->getMessage();

                }

            }

            else {

                $errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;

                $errorCode		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_PARAMETERS;

                $errorMessage 	= 'mobilpay.ro posted invalid parameters';

            }

        }

        else {

            $errorType 		= Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;

            $errorCode		= Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_METHOD;

            $errorMessage 	= 'invalid request metod for payment confirmation';

        }



        header('Content-type: application/xml');

        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

        if($errorCode == 0) {

            echo "<crc>{$errorMessage}</crc>";

        }

        else {

            echo "<crc error_type=\"{$errorType}\" error_code=\"{$errorCode}\">{$errorMessage}</crc>";

        }

    }

    

    public function confirm_plata( $order_id ) {

        $this->load->model('user_model', 'userm');

        

        $comanda = $this->istoric_comenzi_m->get_comanda_by_order_id($order_id);

        if( !$comanda && !empty( $comanda ) ) {

            return false;

        }

        

        $user = $this->userm->get_user( $comanda['id_user'] );

        if( $user ) {

            $this->load->library('email');

            $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
            $this->email->to( $user['user_email'] );

            //preluare template email din baza de date
            $info = array(
                "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                "__NR_FACTURA__" => $comanda['nr_factura'],
                "__DATA_COMANDA__" => $comanda['data_adaugare_f'],
                "__LINK_COMANDA__" => site_url("istoric_comenzi/comanda/{$comanda['id']}"),
            );

            $template['titlu'] = email_template(5, "subiect", $info);
            $template['continut'] = email_template(5, "continut", $info);

            $message = $this->load->view("template_email", $template, true);

            $this->email->subject( $template['titlu'] );
            $this->email->message( $message );

            $this->email->send();

        }

            

        return true;

    }





}



/* End of file pagina.php */

/* Location: ./application/controllers/pagina.php */