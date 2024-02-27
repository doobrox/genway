<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class produse extends CI_Controller {

    private $pConfig;
    private $categorii;

    public function __construct() {

        parent::__construct();

       

        //$this->output->enable_profiler(true);

        $this->load->library('functions');

        $this->load->library('table');

        $this->load->library('pagination');

        $this->load->helper('html');

        $this->load->helper('text');

        $this->load->helper('breadcrumbs');

        $this->load->helper('form');

        $this->load->driver('cache');

        $this->load->model('admin/index_page_model', 'indexm');

        $this->load->model('admin/produse_model', 'produsem');

        $this->load->model('admin/producatori_model', 'producatorim');

        $this->config->load('table');

        if (!$this->simpleloginsecure->is_admin()) {

            redirect('admin/login');

        }

        

        $this->pConfig['next_link'] = '&rsaquo;';

        $this->pConfig['prev_link'] = '&lsaquo;';

        $this->pConfig['num_tag_open'] = $this->pConfig['next_tag_open'] = $this->pConfig['prev_tag_open'] = '<li>';

        $this->pConfig['num_tag_close'] = $this->pConfig['next_tag_close'] = $this->pConfig['prev_tag_close'] = '</li>';

        $this->pConfig['cur_tag_open'] = '<li class="active"><a href="#">';

        $this->pConfig['cur_tag_close'] = '</a></li>';

    }



    public function index( $data = array() ) {

        //TODO: la stergere produse de sters si din tbl produse_filtre

        $data["page"] = "produse";

        

        $p = $this->input->post();

        if( isset( $p['sterge'] ) ) {

            $data = $this->sterge_produse( $data );

        } elseif( isset( $p['salveaza'] ) ) {

            $data = $this->update_produse( $data );

        } elseif( isset( $p['retrage'] ) ) {

            $data = $this->update_produse_activ( $data, 0 );

        } elseif( isset( $p['publica'] ) ) {

            $data = $this->update_produse_activ( $data, 1 );

        }

        

        if( empty( $p ) || isset( $p['salveaza'] ) ) {

             $p = $this->input->get();

        }

        

        $categorii = $this->indexm->get_categorii();

        $categorii = $this->functions->categoriesToTree( $categorii );

        $this->build_tree_dropdown($categorii, (isset( $p['id_categorie'] ) ? $p['id_categorie'] : NULL));

        $data['categorii_tree'] = $this->categorii;

        

        $data['options_stoc'] = array(

            -1 => "TOATE",

            1 => "In stoc",

            2 => "Fara stoc",

        );

        

        $data['options_dupa'] = array(

            "a.nume" => "Nume produs",

            "a.cod_ean13" => "Cod EAN13",

        );

        

        $data['options_reducere'] = array(

            0 => "TOATE",

            1 => "Cu reducere",

            2 => "Fara reducere",

        );

        

        $q = array(

            "q" => isset( $p['q'] ) ? $p['q'] : "",

            "dupa" => isset( $p['dupa'] ) ? $p['dupa'] : "",

            "id_categorie" => isset( $p['id_categorie'] ) ? $p['id_categorie'] : "",

            "stoc" => isset( $p['stoc'] ) ? $p['stoc'] : "",

            "reducere" => isset( $p['reducere'] ) ? $p['reducere'] : "",

            "promovat" => isset( $p['promovat'] ) ? true : false,

        );

        

        $offset = $this->input->get('per_page');

        $this->pConfig = array();

        $this->pConfig['per_page'] = 50; //200

        $this->pConfig['page_query_string'] = TRUE;

        $this->pConfig['base_url'] = base_url() . "admin/produse?".  build_url_string();

        $data['total_locatii'] = $this->pConfig['total_rows'] = count($this->produsem->get_produse($q));

        $this->pagination->initialize($this->pConfig);



        $items = $this->produsem->get_produse($q, $this->pConfig['per_page'], $offset);



        $this->table->set_template( $this->config->item('table_config') );

        $this->table->set_heading('#', 'ID', 'Nume', 'Cod EAN13', 'Categoria', 'Pret', 'Multip.', 'Pret user(RON)', 'Stoc', 'Popularitate', 'Promovat&nbsp;index', 'Optiuni');

        $k = 1;

        foreach ($items as $item) {

            $js_titlu = str_replace("'", "`", $item['nume']);

            $attrDelete = array(

                "onclick" => "return confirm('Esti sigur ca vrei sa stergi produsul: {$js_titlu}?')"

            );



            $row = array(

                array(

                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',

                    'width' => '20',

                ),

                array(

                    'data' => $item['id'],

                    'width' => '30'

                ),

                anchor("admin/produse/editeaza/{$item['id']}", $item['nume']),

                array(

                    'data' => $item['cod_ean13'],

                    'width' => '100'

                ),

                anchor($this->functions->make_furl_categorie( $item['id'] ), $item['nume_categorie']),

                array(

                    'data' =>

                        form_input( 

                                array(

                                    "name" => "pret[]",

                                    "value" => $item['pret'],

                                    "class" => "width50",

                                    "id" => "pret_reseller_{$item['id']}"

                                )

                        ),

                    'width' => '50'

                ),

                array(

                    'data' =>

                    form_dropdown(

                                "pret_multiplicator[]",

                                array(

                                    "0" => "----",

                                    "1.2" => "1.2",

                                    "1.4" => "1.4",

                                    "1.6" => "1.6",

                                ),

                                $item['pret_multiplicator'],

                                "data-id-produs='{$item['id']}'"

                        ),

                    'width' => '50'

                ),

                array(

                    'data' =>

                        form_input( 

                                array(

                                    "name" => "pret_user[]",

                                    "value" => $item['pret_user'],

                                    "class" => "width50",

                                    "id" => "pret_user_{$item['id']}"

                                )

                        ),

                    'width' => '50'

                ),

                array(

                    'data' =>

                        form_input( 

                                array(

                                    "name" => "stoc[]",

                                    "value" => $item['stoc'],

                                    "class" => "width50"

                                )

                        ),

                    'width' => '50'

                ),

                array(

                    'data' =>

                        form_input( 

                                array(

                                    "name" => "ordonare_popular[]",

                                    "value" => $item['ordonare_popular'],

                                    "class" => "width50"

                                )

                        ),

                    'width' => '50'

                ),

                array(

                    'data' => $item['promovat_index']==1 ? "DA" : "NU",

                    'width' => '80'

                ),

                array(

                    'data' =>

                        anchor("admin/produse_comentarii/index/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_comentarii.png"), array("title"=>"Comentarii produs")) .

                        anchor("admin/produse_filtre/index/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_filtru.png"), array("title"=>"Filtre produs")) .

                        anchor("admin/produse/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) .

                        anchor("admin/produse/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 

                        form_hidden('id_edit[]', $item['id']),

                    'width' => '70'

                )

            );

            $this->table->add_row($row);



            $k++;

        }

        if ($k > 1) {

            $row = '<tfoot>

               <tr>

                <td>

                    <input type="checkbox" id="check_all" onclick="return updateCheckAll()" />

                </td>

                <td colspan="3">

                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi produsele selectate?\')" />

                    <input type="submit" name="publica" value="Publica" onclick="return confirm(\'Esti sigur ca vrei sa publici produsele selectate?\')" />

                    <input type="submit" name="retrage" value="Retrage" onclick="return confirm(\'Esti sigur ca vrei sa retragi produsele selectate?\')" />

                </td>

                <td colspan="8" align="right">

                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />

                </td>

            </tfoot>

            </table>';

            

            $data['tabelDate'] = $this->table->generate();

            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );

            $data["pagination"] = $this->pagination->create_links();

        } else {

            $data['warning'] = "Nici un produs gasit.";

        }

        

        $data['breadcrumbs'] = array(

                array(

                    "link" => "",

                    "titlu" => "Produse",

                    "class" => "current",

                ),

            );

        $data["page_view"] = "produse";



        $this->load->library('admin/display', $data);

    }



    public function sterge_produse( $data = array() ) {

        $p = $this->input->post();

        if( isset( $p['id'] ) ) {

            foreach ($p['id'] as $id) {

                $this->sterge($id, false);

            }

            

            $this->clear_cache( $p['id'] );

            

            $data['succes'] = "Produsele selectate au fost sterse cu succes.";

        } else {

            $data['warning'] = "Nu ati selectat nici un produs.";

        }

        

        return $data;

    }

    

    public function update_produse_activ( $data = array(), $activ = 1 ) {

        $p = $this->input->post();

        if( isset( $p['id'] ) ) {

            foreach ($p['id'] as $id) {

                $this->db->set('activ', $activ);

                $this->db->where('id', $id);

                $this->db->update('produse');

            }

            

            $this->clear_cache( $p['id'] );

            

            $data['succes'] = "Produsele selectate au fost ". ( $activ==1 ? "publicate" : "retrase" ) ." cu succes.";

        } else {

            $data['warning'] = "Nu ati selectat nici un produs.";

        }

        

        return $data;

    }

    

    public function update_produse( $data = array() ) {

        $p = $this->input->post();

        if( isset( $p['id_edit'] ) ) {

            $k = 0;

            

            foreach ($p['id_edit'] as $id) {

                $this->db->set('pret', $p['pret'][$k]);

                $this->db->set('pret_multiplicator', $p['pret_multiplicator'][$k]);

                $this->db->set('pret_user', $p['pret_user'][$k]);

                $this->db->set('stoc', $p['stoc'][$k]);
                
                $this->db->set('ordonare_popular', $p['ordonare_popular'][$k]);

                $this->db->where('id', $id);

                $this->db->update('produse');

                

                $k++;

            }

            

            $this->clear_cache( $p['id_edit'] );

            

            $data['succes'] = "Produsele au fost salvate cu succes.";

        }

        

        return $data;

    }

    

    public function adauga($data = array()) {

        $data["page"] = "produse";

        $this->load->library('admin/fckeditor');



        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';

        $this->fckeditor->Width = "99%";

        $this->fckeditor->Height = 400;

        $this->fckeditor->Value = isset( $_POST['FCKeditor'] ) ? htmlspecialchars_decode($_POST['FCKeditor']) : "";

        $data['editor'] = $this->fckeditor->CreateHtml() ;



        $items = $this->producatorim->get_producatori();

        $data['options_producatori'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "", FALSE ); 

        

        $categorii = $this->indexm->get_categorii();

        $categorii = $this->functions->categoriesToTree( $categorii );

        $this->build_tree_select($categorii);

        $data['categorii_tree'] = $this->categorii;



        $data['options_pret_multiplicator'] = array(

            "0" => "-- PRET LIBER --",

            "1.2" => "1.2",

            "1.4" => "1.4",

            "1.6" => "1.6",

        );



        $data['options_reducere_tip'] = array(

            0 => "FARA",

            1 => "Valorica",

            2 => "Procentuala",

        );



        $data['options_activ'] = array(

            1 => "DA",

            0 => "NU"

        );

        

        $data['options_promovat'] = array(

            0 => "NU",

            1 => "DA",

        );

        

        if( isset( $_POST['id_produs_recomandat'] ) && is_array( $_POST['id_produs_recomandat'] ) ) {

            $_POST['id_produs_recomandat'] = array_unique($_POST['id_produs_recomandat']);

            foreach ($_POST['id_produs_recomandat'] as $id_produs_recomandat) {

                if( $id_produs_recomandat!="" ) {

                    $data['id_produse_recomandate'][] = $id_produs_recomandat;

                }

            }

        }

        

        $items = $this->produsem->get_produse( array("order_by_nume" => true) );

        $data['options_produse_recomandate'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "SELECTEAZA PRODUS RECOMANDAT"); 



        $data['breadcrumbs'] = array(

                array(

                    "link" => site_url("admin/produse"),

                    "titlu" => "Produse",

                ),

                array(

                    "link" => "",

                    "titlu" => "Adaug&#259; produs",

                    "class" => "current",

                ),

            );

        $data["page_view"] = "produse_edit";



        $this->load->library('admin/display', $data);

    }



    public function editeaza( $id, $data = array() ) {

        $data["page"] = "produse";



        $item = $this->produsem->get_produs( $id );

        $data['item'] = $item[0];



        $this->load->library('admin/fckeditor');



        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';

        $this->fckeditor->Width = "99%";

        $this->fckeditor->Height = 400;

        $this->fckeditor->Value = $item[0]['descriere'];

        $data['editor'] = $this->fckeditor->CreateHtml() ;

        

        $items = $this->producatorim->get_producatori();

        $data['options_producatori'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "", FALSE ); 

        

        $items = $this->produsem->get_produse( array("order_by_nume" => true) );

        $data['options_produse_recomandate'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "SELECTEAZA PRODUS RECOMANDAT"); 

        

        if( isset( $_POST['id_produs_recomandat'] ) && is_array( $_POST['id_produs_recomandat'] ) ) {

            $_POST['id_produs_recomandat'] = array_unique($_POST['id_produs_recomandat']);

            foreach ($_POST['id_produs_recomandat'] as $id_produs_recomandat) {

                if( $id_produs_recomandat!="" && $id_produs_recomandat!=$id ) {

                    $data['id_produse_recomandate'][] = $id_produs_recomandat;

                }

            }

        } else {

            $id_produse_recomandate = $this->produsem->get_produse_recomandate( $data['item']['id'] );

            foreach ($id_produse_recomandate as $item) {

                $data['id_produse_recomandate'][] = $item['id_produs_recomandat'];

            }

        }

        

        $items = $this->produsem->get_categorii_produs( $id );

        $categorii_produs = array();

        foreach( $items as $item ) {

            $categorii_produs[] = $item['id_categorie'];

        }



        $categorii = $this->indexm->get_categorii();

        $categorii = $this->functions->categoriesToTree( $categorii );

        $this->build_tree_select($categorii, $categorii_produs);

        $data['categorii_tree'] = $this->categorii;

        

        $data['galerie'] = $this->produsem->get_galerie_imagini( $id );

        $data['options_pret_multiplicator'] = array(

            "0" => "-- PRET LIBER --",

            "1.2" => "1.2",

            "1.4" => "1.4",

            "1.6" => "1.6",

        );
        
        $data['fisiere_tehnice'] = $this->produsem->get_fisiere_tehnice( $id );

        $data['options_reducere_tip'] = array(

            0 => "FARA",

            1 => "Valorica",

            2 => "Procentuala",

        );

        

        $data['options_activ'] = array(

            1 => "DA",

            0 => "NU"

        );

        

        $data['options_promovat'] = array(

            0 => "NU",

            1 => "DA",

        );

        

        $data["page_view"] = "produse_edit";

        

        $data['breadcrumbs'] = array(

                array(

                    "link" => site_url("admin/produse"),

                    "titlu" => "Produse",

                ),

                array(

                    "link" => "",

                    "titlu" => "Editeaz&#259; produs",

                    "class" => "current",

                ),

            );



        $this->load->library('admin/display', $data);

    }



    public function salveaza( $id = 0 ) {

        $p = $this->input->post();

        $data = array();



        $this->load->library('form_validation');

        $this->form_validation->set_rules('cod_ean13', 'Cod EAN13', 'trim|required|xss_clean');

        $this->form_validation->set_rules('nume', 'Nume', 'trim|required');

        $this->form_validation->set_rules('id_producator', 'Producatori', 'trim|required|numeric');

        $this->form_validation->set_rules('FCKeditor', 'Descriere', 'trim|required');

        $this->form_validation->set_rules('id_categorie', 'Categorii', 'required');

        $this->form_validation->set_rules('greutate', 'Greutate', 'trim|numeric');

        $this->form_validation->set_rules('pret', 'Pret resellter', 'trim|required|numeric');

        $this->form_validation->set_rules('pret_multiplicator', 'Multiplicator pret', 'trim|required|numeric');

        $this->form_validation->set_rules('pret_user', 'Pret utilizator normal', 'trim|required|numeric');

        $this->form_validation->set_rules('reducere_tip', 'Tip reducere', 'trim|required|numeric');

        $this->form_validation->set_rules('reducere_valoare', 'Valoare reducere', 'trim|numeric' . ( $p['reducere_tip']==2 ? '|less_than[100]|greater_than[0]' : '' ));

        $this->form_validation->set_rules('stoc', 'Stoc', 'trim|numeric');

        $this->form_validation->set_rules('promovat_index', 'Promovat index', 'trim|required|numeric');

        $this->form_validation->set_rules('promotie', 'Promotie', 'trim|required|numeric');

        $this->form_validation->set_rules('seo_title', 'SEO Title', 'trim|xss_clean');

        $this->form_validation->set_rules('meta_description', 'SEO Meta description', 'trim|xss_clean');

        $this->form_validation->set_rules('meta_keywords', 'SEO Meta keywords', 'trim|xss_clean');

        $this->form_validation->set_rules('activ', 'Activ', 'trim|required|numeric');



        if ($this->form_validation->run() == TRUE) {

            $p['stoc_la_comanda'] = isset( $p['stoc_la_comanda'] ) ? 1 : 0;

            $p['greutate'] = $p['greutate']!="" ? $p['greutate'] : 0;

            

            if( $id == 0 ) {

                $this->db->set('id_producator', $p['id_producator']);

                $this->db->set('cod_ean13', $p['cod_ean13']);

                $this->db->set('nume', $p['nume']);

                $this->db->set('descriere', $p['FCKeditor']);

                $this->db->set('greutate', $p['greutate']);

                $this->db->set('pret', $p['pret']);

                $this->db->set('pret_multiplicator', $p['pret_multiplicator']);

                $this->db->set('pret_user', $p['pret_user']);

                $this->db->set('reducere_tip', $p['reducere_tip']);

                $this->db->set('reducere_valoare', $p['reducere_valoare']);

                $this->db->set('stoc', $p['stoc']);

                $this->db->set('stoc_la_comanda', $p['stoc_la_comanda']);

                $this->db->set('promovat_index', $p['promovat_index']);

                $this->db->set('promotie', $p['promotie']);

                $this->db->set('seo_title', $p['seo_title']);

                $this->db->set('meta_description', $p['meta_description']);

                $this->db->set('meta_keywords', $p['meta_keywords']);

                $this->db->set('data_adaugare', 'NOW()', FALSE);

                $this->db->set('activ', $p['activ']);

                if( $this->db->insert( 'produse' ) ) {

                    $id_produs = $this->db->insert_id();

                    $this->salveaza_imagini($id_produs);
                    
                    $this->salveaza_fisiere_tehnice($id);

                    if( isset( $_POST['id_categorie'] ) && !empty( $_POST['id_categorie'] ) ) {

                        foreach( $_POST['id_categorie'] as $id_categorie ) {

                            $this->db->set('id_produs', $id_produs);

                            $this->db->set('id_categorie', $id_categorie);

                            $this->db->insert('produse_categorii');

                        }

                    }

                    

                    if( isset( $_POST['id_produs_recomandat'] ) && is_array( $_POST['id_produs_recomandat'] ) ) {

                        $_POST['id_produs_recomandat'] = array_unique($_POST['id_produs_recomandat']);

                        foreach ($_POST['id_produs_recomandat'] as $id_produs_recomandat) {

                            if( $id_produs_recomandat!="" ) {

                                $this->db->set('id_produs', $id_produs);

                                $this->db->set('id_produs_recomandat', $id_produs_recomandat);

                                $this->db->insert('produse_recomandate');

                            }

                        }

                    }

                    

                    $this->clear_cache( $id_produs );

                    

                    $data['succes'] = "Produsul a fost salvat cu succes.";

                    $this->form_validation->_field_data = array();

                    $_POST = array();

                }

            } else {

                $this->db->set('id_producator', $p['id_producator']);

                $this->db->set('cod_ean13', $p['cod_ean13']);

                $this->db->set('nume', $p['nume']);

                $this->db->set('descriere', $p['FCKeditor']);

                $this->db->set('greutate', $p['greutate']);

                $this->db->set('pret', $p['pret']);

                $this->db->set('pret_multiplicator', $p['pret_multiplicator']);

                $this->db->set('pret_user', $p['pret_user']);

                $this->db->set('reducere_tip', $p['reducere_tip']);

                $this->db->set('reducere_valoare', $p['reducere_valoare']);

                $this->db->set('stoc', $p['stoc']);

                $this->db->set('stoc_la_comanda', $p['stoc_la_comanda']);

                $this->db->set('promovat_index', $p['promovat_index']);

                $this->db->set('promotie', $p['promotie']);

                $this->db->set('seo_title', $p['seo_title']);

                $this->db->set('meta_description', $p['meta_description']);

                $this->db->set('meta_keywords', $p['meta_keywords']);

                $this->db->set('activ', $p['activ']);

                $this->db->where('id', $id);

                if( $this->db->update( 'produse' ) ) {

                    $this->salveaza_imagini($id);
                    
                    $this->salveaza_fisiere_tehnice($id);
                    

                    if( isset( $p['principala'] ) && $p['principala']!="" ) {

                        $this->db->set('principala', 1);

                        $this->db->where('id', $p['principala']);

                        $this->db->update('general_galerie');

                    }

                    

                    if( isset( $_POST['id_categorie'] ) && !empty( $_POST['id_categorie'] ) ) {

                        $this->db->delete( 'produse_categorii', array('id_produs'=>$id) );

                        

                        foreach( $_POST['id_categorie'] as $id_categorie ) {

                            $this->db->set('id_produs', $id);

                            $this->db->set('id_categorie', $id_categorie);

                            $this->db->insert('produse_categorii');

                        }

                    }
                    
                    if( isset( $_POST['ft_id'] ) && is_array( $_POST['ft_id'] ) ) {
                        $i = 0;
                        foreach ($_POST['ft_id'] as $ft_id) {
                            $this->db->set("titlu", $_POST['ft_titlu'][$i]);
                            $this->db->set("reseller", $_POST['ft_reseller'][$i]);
                            $this->db->where("id", $_POST['ft_id'][$i]);
                            $this->db->update("produse_fisiere");
                            
                            $i++;
                        }
                    }

                    

                    if( isset( $_POST['id_produs_recomandat'] ) && is_array( $_POST['id_produs_recomandat'] ) ) {

                        $this->db->delete( 'produse_recomandate', array('id_produs'=>$id) );

                        $_POST['id_produs_recomandat'] = array_unique($_POST['id_produs_recomandat']);

                        

                        foreach ($_POST['id_produs_recomandat'] as $id_produs_recomandat) {

                            if( $id_produs_recomandat!="" && $id_produs_recomandat!=$id ) {

                                $this->db->set('id_produs', $id);

                                $this->db->set('id_produs_recomandat', $id_produs_recomandat);

                                $this->db->insert('produse_recomandate');

                            }

                        }

                    }

                    

                    $this->clear_cache( $id );

                    

                    $data['succes'] = "Produsul a fost salvat cu succes.";

                }

            }

        }



        if( $id==0 ) {

            $this->adauga($data);

        } else {

            $this->editeaza( $id, $data );

        }

    }

    

    public function sterge( $id, $index = true ) {

        $produs = $this->produsem->get_produs( $id );

        if( !empty( $produs ) ) {

            $galerie = $this->produsem->get_galerie_imagini( $id );

            foreach ($galerie as $imagine) {

                @unlink( MAINSITE_STYLE_PATH . "images/produse/" . $imagine['fisier'] );

                @unlink( MAINSITE_STYLE_PATH . "images/produse/85x85/" . $imagine['fisier'] );

                @unlink( MAINSITE_STYLE_PATH . "images/produse/temp/" . $imagine['fisier'] );

            }

            

            $this->db->delete( 'general_galerie', array('id_produs' => $id) );

            $this->db->delete( 'comenzi_produse', array('id_produs' => $id) );

            $this->db->delete( 'produse_categorii', array('id_produs' => $id) );

            $this->db->delete( 'produse_comentarii', array('id_produs' => $id) );

            $this->db->delete( 'produse_recomandate', array('id_produs' => $id) );

            $this->db->delete( 'produse_recomandate', array('id_produs_recomandat' => $id) );

            $this->db->delete( 'produse', array('id' => $id) );

            

            $this->clear_cache( $id );

        }

        

        if( $index==true ) {

            $data["succes"] = "Produsul a fost sters cu succes.";

            $this->index( $data );

        }

    }

    

    public function build_tree_select( $categorii, $categorii_actuale = array() ) {

        if( !empty( $categorii ) ) {

            $this->categorii .= "<ul>";

            foreach ( $categorii as $categorie ) {

                $checked = (isset( $_POST['id_categorie'] ) && is_array( $_POST['id_categorie'] ) && in_array($categorie['id'], $_POST['id_categorie']) ) || ( in_array($categorie['id'], $categorii_actuale) ) ? "CHECKED" : "";

                $this->categorii .=  "<li style='list-style:none;' ><label for='categorie{$categorie['id']}' style='font-size:12px; ". ( $categorie['id_parinte']!=0 ? "font-weight: lighter" : "" ) . "'><input type='checkbox' name='id_categorie[]' value='{$categorie['id']}' id='categorie{$categorie['id']}' ". $checked ." />" . $categorie['nume'] . "</label></li>";



                $this->build_tree_select( $categorie['subcategories'], $categorii_actuale );

            }

            $this->categorii .= "</ul>";

        }

    }

    

    public function build_tree_dropdown( $categorii, $id_categorie_actuala = NULL ) {

        if( !empty( $categorii ) ) {

            foreach ( $categorii as $categorie ) {

                $depth = $this->functions->getCategoriesDepth( $categorie['id'] );

                $selected = (isset( $_POST['id_categorie'] ) && $categorie['id']==$_POST['id_categorie'] ) || (isset( $_GET['id_categorie'] ) && $categorie['id']==$_GET['id_categorie'] ) || $categorie['id']==$id_categorie_actuala ? "SELECTED" : "";

                @$this->categorii .=  "<option value='{$categorie['id']}' style='padding-left:". ($depth*10) ."px' {$selected}>{$categorie['nume']}</option>";



                $this->build_tree_dropdown( $categorie['subcategories'], $id_categorie_actuala );

            }

        }

    }

    

    private function salveaza_imagini( $id_produs ) {

        $p = $this->input->post();

        

        if (isset($p['imagini']) && !empty($p['imagini'])) {

            $this->load->library('classupload');

            $upload_path = MAINSITE_STYLE_PATH . "images/produse/temp/";

            

            $k = 0;

            foreach ($p['imagini'] as $imagine_galerie) {

                $this->classupload->upload($upload_path . $imagine_galerie);



                if ($this->classupload->uploaded) {

                    list($w, $h) = getimagesize($upload_path . $imagine_galerie);

                    if ($w > $h && $w > 900) {

                        $this->classupload->image_resize = true;

                        $this->classupload->image_ratio_y = true;

                        $this->classupload->image_x = 900;

                    } elseif ($w < $h && $h > 800) {

                        $this->classupload->image_resize = true;

                        $this->classupload->image_ratio_x = true;

                        $this->classupload->image_y = 800;

                    }

                    $this->classupload->Process($upload_path . "../");



                    $this->classupload->image_resize = true;

                    $this->classupload->image_ratio_fill = true;

                    $this->classupload->image_y = 85;

                    $this->classupload->image_x = 85;



                    $this->classupload->Process($upload_path . "../85x85");



                    if ($this->classupload->processed) {

                        @unlink($upload_path . $imagine_galerie);



                        $imagine_galerie = $this->classupload->file_dst_name;



                        $principala = isset( $p['principala'] ) && $p['principala']==$k ? 1 : 0;

                        

                        if( $principala==1 ) {

                            $this->db->set('principala', 0);

                            $this->db->where('id_produs', $id_produs);

                            $this->db->update('general_galerie');

                        }

                        

                        $this->db->set('id_produs', $id_produs);

                        $this->db->set('fisier', $imagine_galerie);

                        $this->db->set('principala', $principala);

                        $this->db->set('data_adaugare', 'NOW()', FALSE);

                        $this->db->insert('general_galerie');



                        $this->classupload->clean();

                    }

                }

                

                $k++;

            }

        }



        $this->db->from('general_galerie');

        $this->db->where('id_produs', $id_produs);

        $this->db->where('principala', 1);



        if( $this->db->count_all_results()==0 ) {

            $this->db->set('principala', 1);

            $this->db->where('id_produs', $id_produs);

            $this->db->limit(1);

            $this->db->update('general_galerie');

        }

    }
    
    public function salveaza_fisiere_tehnice($id_produs) {
        if (isset($_FILES['fisiere_tehnice_fisier']['name'])) {
            $count_files = count($_FILES['fisiere_tehnice_fisier']['name']);
            
            $this->load->library('classupload');
            $upload_path = MAINSITE_STYLE_PATH . "images/produse/fisiere_tehnice/";
            
            $i = 0;
            for ($i = 0; $i < $count_files; $i++) {
                $_FILES['userfile']['name'] = $_FILES['fisiere_tehnice_fisier']['name'][$i];
                $_FILES['userfile']['type'] = $_FILES['fisiere_tehnice_fisier']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['fisiere_tehnice_fisier']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $_FILES['fisiere_tehnice_fisier']['error'][$i];
                $_FILES['userfile']['size'] = $_FILES['fisiere_tehnice_fisier']['size'][$i];
                
                $this->classupload->allowed = "pdf";
                $this->classupload->upload( $_FILES['userfile'] );    
                if ($this->classupload->uploaded) {
                    $this->classupload->Process($upload_path);

                    if ($this->classupload->processed) {
                        $this->db->set("id_produs", $id_produs);
                        $this->db->set("titlu", $_POST['fisiere_tehnice_titlu'][$i]);
                        $this->db->set("reseller", $_POST['fisiere_tehnice_reseller'][$i]);
                        $this->db->set("fisier", $this->classupload->file_dst_name);
                        $this->db->insert("produse_fisiere");
                    }
                }
            }
        }
    }
    
    public function sterge_fisier( $id_produs, $id_fisier ) {
        $data = array();
        
        $item = $this->produsem->get_fisier( $id_fisier );
        if( !empty( $item ) ) {
            @unlink( MAINSITE_STYLE_PATH . 'images/produse/fisiere_tehnice/' . $item['fisier'] );
            
            $this->db->delete("produse_fisiere", array("id" => $id_fisier));
            
            $data["succes"] = "Fisierul a fost sters cu succes.";
        }
        
        $this->editeaza( $id_produs, $data );
    }
    

    private function clear_cache($id_produse) {

        if( !is_array( $id_produse ) ) {

            $id_produse = array( $id_produse );

        }

        

        foreach ($id_produse as $idp) {

            $produs = $this->produsem->get_produs( $idp );

            $produs = $produs[0];

            

            if( !empty( $produs ) ) {

                $produse_ce_recomanda_prod = $this->produsem->get_produse_ce_recomanda_prod( $produs['id'] );

                foreach ($produse_ce_recomanda_prod as $prod) {

                    $this->cache->file->delete("produse_recomandate_{$prod['id_produs']}");

                }



                $produse_categorii = $this->produsem->get_categorii_produs( $produs['id'] );

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

    }



}



/* End of file index_page.php */

/* Location: ./application/controllers/admin/index_page.php */