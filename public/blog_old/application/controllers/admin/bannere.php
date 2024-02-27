<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class bannere extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);

        parent::__construct();
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('text');
        $this->load->helper('form');
        $this->load->helper('breadcrumbs');
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->model('admin/bannere_model', 'bannerem');
        $this->config->load('table');
        if (!$this->simpleloginsecure->is_admin()) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_bannere( $data );
        } elseif( isset( $p['salveaza'] ) ) {
            $data = $this->update_bannere( $data );
        }
        
        $data["page"] = "bannere";
        $bannere = $this->bannerem->get_bannere();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Zona', 'Alt/Title', 'Link', 'Imagine', 'Activ', 'Optiuni');
        $k = 1;
        foreach ($bannere as $item) {
            $js_titlu = str_replace("'", "`", $item['titlu']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi bannerul: {$js_titlu}?')"
            );

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                array(
                    'data' => $item['zona'],
                    'width' => '150',
                ),
                array(
                    'data' => anchor("admin/bannere/editeaza/{$item['id']}", $item['titlu']),
                    'width' => '150',
                ),
                anchor($item['link'], $item['link'], "target='_blank'"),
                anchor(base_url() . MAINSITE_STYLE_PATH . "images/bannere/{$item['imagine']}", $item['imagine'], "rel='prettyPhoto'"),
                form_dropdown( 
                        'activ[]',
                        array(
                            "1" => "DA",
                            "0" => "NU",
                        ),
                        $item['activ']
                ),
                array(
                    'data' =>
                        anchor("admin/bannere/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) .
                        anchor("admin/bannere/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 
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
                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi bannerele selectate?\')" />
                </td>
                <td colspan="5"align="right">
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici un banner gasit. " . anchor('admin/bannere/adauga', 'Adaug&#259; unul');
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Bannere",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "bannere";

        $this->load->library('admin/display', $data);
    }
    
    public function sterge_bannere( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $banner = $this->bannerem->get_banner( $id );
                if( !empty( $banner ) ) {
                    @unlink( MAINSITE_STYLE_PATH . "images/bannere/" . $banner['imagine'] );
                    $this->db->delete( 'bannere', array('id' => $id) );
                }
            }
            $data['succes'] = "Bannerele selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici un banner.";
        }
        
        return $data;
    }
    
    public function update_bannere( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id_edit'] ) ) {
            $k = 0;
            
            foreach ($p['id_edit'] as $id) {
                $this->db->set('activ', $p['activ'][$k]);
                $this->db->where('id', $id);
                $this->db->update('bannere');
                
                $k++;
            }
            
            $data['succes'] = "Bannerele au fost salvate cu succes.";
        }
        
        return $data;
    }
    
    public function adauga($data = array()) {
        $data["page"] = "bannere";

        $data['options_zona'] = array(
            "HEADER" => "HEADER",
        );

        $data['options_target'] = array(
            "_parent" => "_parent",
            "_blank" => "_blank"
        );
        
        $data['options_activ'] = array(
            1 => "DA",
            0 => "NU"
        );
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/bannere"),
                    "titlu" => "Bannere",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; banner",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "bannere_edit";

        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "bannere";

        $item = $this->bannerem->get_banner( $id );
        $data['item'] = $item[0];

        $data["page_view"] = "bannere_edit";

        $data['options_zona'] = array(
            "HEADER" => "HEADER",
        );

        $data['options_target'] = array(
            "_parent" => "_parent",
            "_blank" => "_blank"
        );

        $data['options_activ'] = array(
            1 => "DA",
            0 => "NU"
        );
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/bannere"),
                    "titlu" => "Bannere",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; banner",
                    "class" => "current",
                ),
            );

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id = 0 ) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('zona', 'Zona', 'trim|required|xss_clean');
        $this->form_validation->set_rules('titlu', 'Titlu alternativ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('link', 'Link', 'trim|required|prep_url');
        $this->form_validation->set_rules('activ', 'Activ', 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();

            $config['upload_path'] = MAINSITE_STYLE_PATH . 'images/bannere/';
            $config['allowed_types'] = 'gif|jpg|png';
            $this->load->library('upload', $config);

            if ($this->upload->do_upload( 'imagine' )) {
                $imagine = $this->upload->data();
            }

            if( $id == 0 ) {
                $this->db->set('zona', $p['zona']);
                $this->db->set('titlu', $p['titlu']);
                isset($imagine['file_name']) ? $this->db->set('imagine', $imagine['file_name']) : "";
                $this->db->set('link', $p['link']);
                $this->db->set('data_adaugare', 'NOW()', false);
                $this->db->set('activ', $p['activ']);
                if( $this->db->insert( 'bannere' ) ) {
                    $data['succes'] = "Bannerul a fost salvat cu succes.";
                    $this->form_validation->_field_data = array();
                }
            } else {
                $this->db->set('zona', $p['zona']);
                $this->db->set('titlu', $p['titlu']);
                isset($imagine['file_name']) ? $this->db->set('imagine', $imagine['file_name']) : "";
                $this->db->set('link', $p['link']);
                $this->db->set('activ', $p['activ']);
                $this->db->where('id', $id);
                if( $this->db->update( 'bannere' ) ) {
                    $data['succes'] = "Bannerul a fost salvat cu succes.";
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
        $data = array();
        
        $banner = $this->bannerem->get_banner( $id );
        if( !empty( $banner ) ) {
            @unlink( MAINSITE_STYLE_PATH . "images/bannere/" . $banner['imagine'] );
            
            $this->db->delete( 'bannere', array('id' => $id) );
            $data["succes"] = "Bannerul a fost sters cu succes";
        }

        $this->index( $data );
    }
    
    public function sterge_imagine( $id ) {
        $data = array();
        
        $item = $this->bannerem->get_banner( $id );
        @unlink( MAINSITE_STYLE_PATH . "images/bannere/" . $item['imagine'] );
        
        $this->db->set( 'imagine', "" );
        $this->db->where( 'id', $id );
        $this->db->update( 'bannere' );
        
        $data["succes"] = "Imaginea a fost stearsa cu succes.";

        $this->editeaza( $id, $data );
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */