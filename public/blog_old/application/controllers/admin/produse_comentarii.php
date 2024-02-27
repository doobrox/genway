<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class produse_comentarii extends CI_Controller {

    private $pConfig;

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



    public function index( $id_produs = "", $data = array() ) {

        $data["page"] = "produse";

        

        $p = $this->input->post();

        if( isset( $p['sterge'] ) ) {

            if( !empty( $p['id'] ) && is_array( $p['id'] ) ) {
            	foreach( $p['id'] as $id_sterge ) {
            		$this->db->delete( 'produse_comentarii', array('id' => $id_sterge) );
            	}
            	
            	$data['succes'] = 'Comentariile au fost sterse cu succes!';
            } else {
            	$data['error'] = 'Nu ai selectat nici un comentariu.';            
            }  

        } 

        

        if( empty( $p ) ) {

             $p = $this->input->get();

        }

        

        $q = array(

            "id_produs" => $id_produs!="" ? $id_produs : (isset( $p['id_produs'] ) ? $p['id_produs'] : ""),

        );



        $offset = $this->input->get('per_page');

        $this->pConfig = array();

        $this->pConfig['per_page'] = 50; //200

        $this->pConfig['page_query_string'] = TRUE;

        $this->pConfig['base_url'] = base_url() . "admin/produse_comentarii?".  build_url_string();

        $data['total_locatii'] = $this->pConfig['total_rows'] = count($this->produsem->get_comentarii_produse($q));

        $this->pagination->initialize($this->pConfig);



        $items = $this->produsem->get_comentarii_produse($q, $this->pConfig['per_page'], $offset);



        $this->table->set_template( $this->config->item('table_config') );

        $this->table->set_heading('&nbsp;', 'Produs', 'COD EAN13', 'Comentarii', 'Nota', 'Data adaugare', 'Optiuni');

        $k = 1;

        foreach ($items as $item) {

            $attrDelete = array(

                "onclick" => "return confirm('Esti sigur ca vrei sa stergi comentariul?')"

            );

            $row = array(

                array(

                    'data' => '<input type="checkbox" name="id[]" value="' . $item['id'] . '" />',

                    'width' => '20'
                ),
	
                anchor("admin/produse/editeaza/{$item['id_produs']}", $item['nume_produs']),

                array(

                    'data' => $item['cod_ean13'],

                    'width' => '100'

                ),

                nl2br( $item['comentarii'] ),

                $item['nota'],

                $item['data_adaugare'],

                array(

                    'data' =>

                        anchor("admin/produse_comentarii/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete),

                    'width' => '70'

                )

            );

            $this->table->add_row($row);



            $k++;

        }

        if ($k > 1) {     
        
            $row = array(
            	'<input type="checkbox" name="check_all" id="check_all" onclick="return updateCheckAll();" />',
            	array(
	            	'data' => '<input type="submit" name="sterge" value="Sterge comentariile selectate" onclick="return confirm(\'Esti sigur?\')" />',
	
	                'colspan' => '6'
		)
            );      
            
            $this->table->add_row($row);

            $data['tabelDate'] = $this->table->generate();

            $data["pagination"] = $this->pagination->create_links();

        } else {

            $data['warning'] = "Nici un comentariu gasit.";

        }

        

        $data['breadcrumbs'] = array(

                array(

                    "link" => "",

                    "titlu" => "Comentarii produse",

                    "class" => "current",

                ),

            );

        $data["page_view"] = "produse_comentarii";



        $this->load->library('admin/display', $data);

    }

    

    public function sterge( $id ) {

        $this->db->delete( 'produse_comentarii', array('id' => $id) );

        $data["succes"] = "Comentariul a fost sters cu succes.";

        $this->index( "", $data );

    }

}



/* End of file index_page.php */

/* Location: ./application/controllers/admin/index_page.php */