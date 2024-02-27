<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setari extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('breadcrumbs');
        $this->load->model('admin/setari_model', 'setarim');
        $this->config->load('table');
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }

    }

    public function index( $data = array() ) {

        $data["page"] = "setari";

        $setari = $this->setarim->get_setari();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Camp', 'Valoare', 'Optiuni');
        $k = 1;
        foreach( $setari as $item ) {
            $row = array(
                 array(
                    'data' => $k,
                    'width' => '20',
                ),
                $item['camp'],
                nl2br( $item['valoare'] ),
                array(
                    'data' =>
                        anchor("admin/setari/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH."images/icn_edit.png")),
                    'width' => '50'
                )
            );
            $this->table->add_row( $row );

            $k++;
        }
        $data['tabelDate'] = $this->table->generate();
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Set&#259;ri",
                    "class" => "current",
                ),
            );
        
        $data["page_view"] = "setari";

        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "setari";

        $item = $this->setarim->get_setare( $id );
        $data['item'] = $item[0];
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/setari"),
                    "titlu" => "Set&#259;ri",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; setare",
                    "class" => "current",
                ),
            );

        $data["page_view"] = "setari_edit";
        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id ) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('valoare', 'Valoare', 'trim|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            $this->db->set("valoare", $p['valoare']);
            $this->db->where("id", $id);
            if( $this->db->update( "setari" ) ) {
                $this->form_validation->_camp_data = array();
                $data['succes'] = "Setarea a fost salvata cu succes.";
            }
        }
        $this->editeaza( $id, $data );
    }

    public function sterge( $id ) {
        $this->db->delete( 'setari', array('id'=>$id) );

        $data["succes"] = "Setarea a fost sterasa cu succes";

        $this->index( $data );
    }

}

/* End of file setari.php */
/* Location: ./application/controllers/admin/setari.php */