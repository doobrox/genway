<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class catalog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);

        parent::__construct();
        $this->load->library('functions');
        $this->load->helper('breadcrumbs');
        $this->load->helper('setari');
        $this->load->model('admin/index_page_model', 'indexm');
        
        if (!$this->simpleloginsecure->is_admin()) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $data['catalog_curent'] = setare("LINK_CATALOG_PRODUSE");
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Upload Catalog",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "catalog";

        $this->load->library('admin/display', $data);
    }
    
    public function upload() {
        $data = array();

        $p = $this->input->post();
        
        $config['upload_path'] = dirname(__FILE__) . "/catalog/";
        $config['allowed_types'] = 'txt|pdf|doc|xls|xlsx|doc|docx';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload( 'catalog' )) {
            $uploaded_data = $this->upload->data();

            $this->db->set("valoare", base_url() . "application/controllers/admin/catalog/{$uploaded_data['file_name']}");
            $this->db->where("camp", "LINK_CATALOG_PRODUSE");
            $this->db->update("setari");
            
            $this->session->set_flashdata( "succes", "Catalogul a fost incarcat cu succes." );
            redirect("admin/catalog");
        } else {
            $data['error'] = $this->upload->display_errors();
        }
        
        $this->index( $data );
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */