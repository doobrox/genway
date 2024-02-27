<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class producatori extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);

        parent::__construct();
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('text');
        $this->load->helper('breadcrumbs');
        $this->load->helper('form');
        $this->load->model('admin/producatori_model', 'producatorim');
        $this->config->load('table');
        if (!$this->simpleloginsecure->is_admin()) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_producatori( $data );
        }
        
        $data["page"] = "producatori";
        $producatori = $this->producatorim->get_producatori();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'ID', 'Nume', 'Slug', 'Optiuni');
        $k = 1;
        foreach ($producatori as $item) {
            $js_titlu = str_replace("'", "`", $item['nume']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi producatorul: {$js_titlu}?')"
            );

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                array(
                    'data' => $item['id'],
                    'width' => '20',
                ),
                anchor("admin/producatori/editeaza/{$item['id']}", $item['nume']),
                $item['slug'],
                array(
                    'data' =>
                        anchor("admin/producatori/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) .
                        anchor("admin/producatori/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 
                        form_hidden('id_edit[]', $item['id']),
                    'width' => '50'
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
                <td colspan="6">
                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi producatorii selectati?\')" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici un producator gasit. " . anchor("admin/producatori/adauga", "Adauga un nou producator");
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Producatori",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "producatori";

        $this->load->library('admin/display', $data);
    }
    
    public function sterge_producatori( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $this->db->where('id', $id);
                $this->db->delete('produse_producatori');
            }
            $data['succes'] = "Producatorii selectati au fost stersi cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici un producator.";
        }
        
        return $data;
    }

    public function adauga($data = array()) {
        $data["page"] = "producatori";

        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/producatori"),
                    "titlu" => "Producatori",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; producator",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "producatori_edit";

        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "producatori";

        $item = $this->producatorim->get_producator( $id );
        $data['item'] = $item[0];

        $data["page_view"] = "producatori_edit";
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/producatori"),
                    "titlu" => "Producatori",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; producator",
                    "class" => "current",
                ),
            );

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id = 0 ) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            
            if( $id == 0 ) {
                $this->db->set('nume', $p['nume']);
                $this->db->set('slug', $p['slug']);
                if( $this->db->insert( 'produse_producatori' ) ) {
                    $data['succes'] = "Producatorul a fost salvat cu succes.";
                    $this->form_validation->_field_data = array();
                }
            } else {
                $this->db->set('nume', $p['nume']);
                $this->db->set('slug', $p['slug']);
                $this->db->where('id', $id);
                if( $this->db->update( 'produse_producatori' ) ) {
                    $data['succes'] = "Producatorul a fost salvat cu succes.";
                }
            }
        }

        if( $id==0 ) {
            $this->adauga($data);
        } else {
            $this->editeaza( $id, $data );
        }
    }
    
    public function sterge( $id ) {
        $this->db->delete( 'produse_producatori', array('id'=>$id) );

        $data["succes"] = "Producatorul a fost sters cu succes";

        $this->index( $data );
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */