<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class produse_recomandate extends CI_Controller {
    private $pConfig;
    public function __construct() {
        parent::__construct();
       
        //$this->output->enable_profiler(true);
        $this->load->library('functions');
        $this->load->library('table');
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

    }

    public function index( $data = array() ) {
        $data["page"] = "produse";
        
        $p = $this->input->post();
        if( isset( $p['salveaza'] ) ) {
            $data = $this->update_produse( $data );
        }
        
        if( empty( $p ) ) {
             $p = $this->input->get();
        }

        $items = $this->produsem->get_produse_promovate_index();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('ID', 'Nume', 'Cod EAN13', 'Categoria', 'Pret(RON)', 'Stoc', 'Ordonare', 'Optiuni');
        $k = 1;
        foreach ($items as $item) {
            $row = array(
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
                    'data' => $item['pret'],
                    'width' => '50'
                ),
                array(
                    'data' => $item['stoc'],
                    'width' => '50'
                ),
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
                <td colspan="4">
                </td>
                <td colspan="5" align="right">
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici un produs gasit.";
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Ordoneaza produsele recomandate",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "produse_recomandate";

        $this->load->library('admin/display', $data);
    }
    
    public function update_produse( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id_edit'] ) ) {
            $k = 0;
            
            foreach ($p['id_edit'] as $id) {
                $this->db->set('ordonare', $p['ordonare'][$k]);
                $this->db->where('id', $id);
                $this->db->update('produse');
                
                $k++;
            }
            
            $data['succes'] = "Produsele au fost salvate cu succes.";
        }
        
        return $data;
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */