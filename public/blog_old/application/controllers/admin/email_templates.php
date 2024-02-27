<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class email_templates extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);

        parent::__construct();
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('breadcrumbs');
        $this->load->helper('setari');
        $this->load->helper('text');
        $this->load->helper('html');
        $this->config->load('table');
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->model('admin/email_templates_model', 'email_templates_m');
        
        if (!$this->simpleloginsecure->is_admin()) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $templates = $this->email_templates_m->get_templates();
        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('ID', 'Nume', 'Subiect', 'Continut', 'Optiuni');
        $k = 1;
        foreach ($templates as $item) {
            $js_titlu = str_replace("'", "`", $item['nume']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi curierul: {$js_titlu}?')"
            );

            $row = array(
                array(
                    'data' => $item['id'],
                    'width' => '20',
                ),
                anchor("admin/email_templates/editeaza/{$item['id']}", $item['nume']),
                array(
                    'data' => $item['subiect'],
                ),
                array(
                    'data' => character_limiter(strip_tags( $item['continut'] ), 100),
                ),
                array(
                    'data' =>
                        anchor("admin/email_templates/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")),
                    'width' => '50'                )
            );

            $this->table->add_row($row);

            $k++;
        }

        if ($k > 1) {
            $data['tabelDate'] = $this->table->generate();
        } else {
            $data['warning'] = "Nici un template gasit. " . anchor("admin/email_templates/adauga", "Adauga un nou template");
        }
                
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Template-uri email",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "email_templates";

        $this->load->library('admin/display', $data);
    }
    
    public function editeaza($id, $data = array()) {
        $data["page"] = "email_templates";
        $this->load->library('admin/fckeditor');

        $data['item'] = $this->email_templates_m->get_template( $id );
        
        
        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = "99%";
        $this->fckeditor->Height = 400;
        $this->fckeditor->Value = isset( $_POST['FCKeditor'] ) ? htmlspecialchars_decode($_POST['FCKeditor']) : $data['item']['continut'];

        $data['editor'] = $this->fckeditor->CreateHtml() ;


        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/email_templates"),
                    "titlu" => "Template-uri email",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; template",
                    "class" => "current",
                ),
            );


        $data["page_view"] = "email_templates_edit";
        $this->load->library('admin/display', $data);
    }
    
    public function salveaza($id) {
        $data = array();
        $p = $this->input->post();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');
        $this->form_validation->set_rules('subiect', 'Subiect', 'trim|required|xss_clean');
        $this->form_validation->set_rules('FCKeditor', 'Continut', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $this->db->set('nume', $p['nume']);
            $this->db->set('subiect', $p['subiect']);
            $this->db->set('continut', $p['FCKeditor']);
            $this->db->where('id', $id);
            if( $this->db->update( 'email_template' ) ) {
                $data['succes'] = "Template-ul a fost salvat cu succes.";
            }
        }
        
        $this->editeaza( $id, $data );
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */