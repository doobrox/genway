<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filtre extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        //$this->output->enable_profiler(true);
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('text');
        $this->load->helper('html');
        $this->load->helper('breadcrumbs');
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->model('admin/filtre_model', 'filtrem');
        $this->config->load('table');
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        //TODO: eroare la stergere filtre
        
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_filtre( $data );
        } elseif( isset( $p['salveaza'] ) ) {
            $data = $this->update_filtre( $data );
        }
        
        $data["page"] = "filtre";
        $filtre = $this->filtrem->get_filtre();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Nume', 'Ordonare', 'Optiuni');
        $k = 1;
        foreach ($filtre as $item) {
            $js_titlu = str_replace("'", "`", $item['nume']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi filtrul: {$js_titlu}?')"
            );

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                ( $item['id_parinte']!=0 ? "&rarr; " : "" ) . anchor("admin/filtre/editeaza/{$item['id']}", $item['nume'], ($item['id_parinte']==0 ? "style='font-weight: bold'" : "")), 
                array(
                    'data' =>
                        form_input( 
                                array(
                                    "name" => "ordonare[]",
                                    "value" => $item['ordonare'],
                                    "class" => "width50"
                                )
                        ),
                    'width' => '80'
                ),
                array(
                    'data' =>
                        anchor("admin/filtre/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) . 
                        anchor("admin/filtre/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 
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
                <td>
                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi filtrele selectate?\')" />
                </td>
                <td colspan="5"align="right">
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici un filtru gasit. " . anchor("admin/filtre/adauga", "Adauga un nou filtru");
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Filtre",
                    "class" => "current",
                ),
            );
        
        $data["pag"] = "filtre";
        $data["page_view"] = "filtre";

        $this->load->library('admin/display', $data);
    }
    
    
    public function sterge_filtre( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $this->db->delete('produse_filtre', array('id_filtru' => $id));
                $this->db->delete('filtre', array('id_parinte' => $id));
                $this->db->delete('filtre', array('id' => $id));
            }
            $data['succes'] = "Filtrele selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici un filtru.";
        }
        
        return $data;
    }
    
    public function update_filtre( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id_edit'] ) ) {
            $k = 0;
            
            foreach ($p['id_edit'] as $id) {
                $this->db->set('ordonare', $p['ordonare'][$k]);
                $this->db->where('id', $id);
                $this->db->update('filtre');
                
                $k++;
            }
            
            $data['succes'] = "Filtrele au fost salvate cu succes.";
        }
        
        return $data;
    }

    public function adauga( $data = array() ) {
        $data["page"] = "filtre";
        $data["page_view"] = "filtre_edit";
        
        $items = $this->filtrem->get_filtre( 0 );
        $data['options_filtre'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "CATEGORIE PRINCIPALA" ); 

        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/filtre"),
                    "titlu" => "Filtre",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; filtru",
                    "class" => "current",
                ),
            );
        
        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "filtre";
        
        $data['item'] = $this->filtrem->get_filtru( $id );
        $data['item'] = $data['item'][0];
        
        $items = $this->filtrem->get_filtre( 0 );
        $data['options_filtre'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "CATEGORIE PRINCIPALA" ); 
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/filtre"),
                    "titlu" => "Filtre",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; filtru",
                    "class" => "current",
                ),
            );
        
        $data["page_view"] = "filtre_edit";

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id = 0 ) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_parinte', 'Parinte', 'trim');
        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            $p['id_parinte'] = $p['id_parinte']!="" ? $p['id_parinte'] : 0;
            
            if( $id == 0 ) {
                $this->db->set('id_parinte', $p['id_parinte']);
                $this->db->set('nume', $p['nume']);
                $this->db->set('ordonare', $this->indexm->get_max_ordonare( 'filtre' ));

                if( $this->db->insert('filtre') ) {
                    $data['succes'] = "Filtrul a fost salvat cu succes.";
                    $this->form_validation->_field_data = array();
                    $_POST = array();
                }
            } else {
                $this->db->set('id_parinte', $p['id_parinte']);
                $this->db->set('nume', $p['nume']);
                $this->db->where('id', $id);
                if( $this->db->update('filtre') ) {
                    $data['succes'] = "Filtrul a fost salvat cu succes.";
                }
            }
        }
        $data = isset( $data ) ? $data : array();
        if( $id==0 ) {
            $this->adauga( $data );
        } else {
            $this->editeaza( $id, $data );
        }
    }

    public function sterge( $id ) {
        $this->db->delete('produse_filtre', array('id_filtru' => $id));
        $this->db->delete('filtre', array('id_parinte' => $id));
        $this->db->delete('filtre', array('id' => $id));

        $data["succes"] = "Filtrul a fost sters cu succes.";

        $this->index( $data );
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */