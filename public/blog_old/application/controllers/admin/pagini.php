<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pagini extends CI_Controller {

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
        $this->load->model('admin/pagini_model', 'paginim');
        $this->load->model('admin/index_page_model', 'indexm');
        $this->config->load('table');
        if (!$this->simpleloginsecure->is_admin()) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_pagini( $data );
        } elseif( isset( $p['salveaza'] ) ) {
            $data = $this->update_pagini( $data );
        }
        
        $data["page"] = "pagini";
        $pagini = $this->paginim->get_pagini();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Titlu', 'Continut', 'Ordonare', 'Activ', 'Optiuni');
        $k = 1;
        foreach ($pagini as $item) {
            $js_titlu = str_replace("'", "`", $item['titlu']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi pagina: {$js_titlu}?')"
            );

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                array(
                    'data' => anchor("admin/pagini/editeaza/{$item['id']}", $item['titlu']),
                    'width' => '150',
                ),
                ($item['link_extern']!="" ? anchor($item['link_extern']) . "<br />" : "") . 
                word_limiter(strip_tags($item['continut']), 100),
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
                form_dropdown( 
                        'activ[]',
                        array(
                            "0" => "NU",
                            "1" => "DA",
                        ),
                        $item['activ']
                ),
                array(
                    'data' =>
                        ($item['posibilitate_stergere'] == 1 ? anchor("admin/pagini/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) : "") .
                        anchor("admin/pagini/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 
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
                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi paginile selectate?\')" />
                </td>
                <td colspan="5"align="right">
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici o pagina gasita. " . anchor("admin/pagini/adauga", "Adauga o noua pagina");
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Pagini",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "pagini";

        $this->load->library('admin/display', $data);
    }
    
    public function sterge_pagini( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $item = $this->paginim->get_pagina( $id );
                if( $item[0]['posibilitate_stergere']==1 ) {
                    $this->db->delete('pagini', array('id' => $id));
                }
            }
            $data['succes'] = "Paginile selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici o pagina.";
        }
        
        return $data;
    }
    
    public function update_pagini( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id_edit'] ) ) {
            $k = 0;
            
            foreach ($p['id_edit'] as $id) {
                $this->db->set('ordonare', $p['ordonare'][$k]);
                $this->db->set('activ', $p['activ'][$k]);
                $this->db->where('id', $id);
                $this->db->update('pagini');
                
                $k++;
            }
            
            $data['succes'] = "Paginile au fost salvate cu succes.";
        }
        
        return $data;
    }
    
    public function adauga($data = array()) {
        $data["page"] = "pagini";

        $this->load->library('admin/fckeditor');

        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = "99%";
        $this->fckeditor->Height = 400;
        $this->fckeditor->Value = isset( $_POST['FCKeditor'] ) ? htmlspecialchars_decode($_POST['FCKeditor']) : "";
        $data['editor'] = $this->fckeditor->CreateHtml() ;

        $data['options_activ'] = array(
            1 => "DA",
            0 => "NU"
        );

        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/pagini"),
                    "titlu" => "Pagini",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; pagin&#259;",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "pagini_edit";

        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "pagini";

        $item = $this->paginim->get_pagina( $id );
        $data['item'] = $item[0];

        $this->load->library('admin/fckeditor');

        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = "99%";
        $this->fckeditor->Height = 400;
        $this->fckeditor->Value = $item[0]['continut'];
        $data['editor'] = $this->fckeditor->CreateHtml() ;

        $data["page_view"] = "pagini_edit";
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/pagini"),
                    "titlu" => "Pagini",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; pagin&#259;",
                    "class" => "current",
                ),
            );
        
        $data['options_activ'] = array(
            1 => "DA",
            0 => "NU"
        );

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id = 0 ) {
        $data = array();
        $p = $this->input->post();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('titlu', 'Titlu', 'trim|required|xss_clean');
        $this->form_validation->set_rules('link_extern', 'Link extern', 'trim|prep_url');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|xss_clean' . ($p['link_extern']=="" ? '|required' : ''));
        $this->form_validation->set_rules('FCKeditor', 'Continut', 'trim' . ($p['link_extern']=="" ? '|required' : ''));
        $this->form_validation->set_rules('in_meniu_principal', 'Meniu principal', 'trim');
        $this->form_validation->set_rules('in_meniu', 'Meniu secundar', 'trim');
        $this->form_validation->set_rules('activ', 'Activ', 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            if( $id == 0 ) {
                $this->db->set('titlu', $p['titlu']);
                $this->db->set('slug', $p['slug']);
                $this->db->set('continut', $p['FCKeditor']);
                $this->db->set('link_extern', $p['link_extern']);
                $this->db->set('ordonare', $this->indexm->get_max_ordonare( 'pagini' ));
                $this->db->set('in_meniu', $p['in_meniu']);
                $this->db->set('in_meniu_principal', $p['in_meniu_principal']);
                $this->db->set('activ', $p['activ']);
                if( $this->db->insert( 'pagini' ) ) {
                    $data['succes'] = "Pagina a fost salvata cu succes.";
                    $this->form_validation->_field_data = array();
                }
            } else {
                $this->db->set('titlu', $p['titlu']);
                $this->db->set('slug', $p['slug']);
                $this->db->set('continut', $p['FCKeditor']);
                $this->db->set('link_extern', $p['link_extern']);
                $this->db->set('in_meniu', $p['in_meniu']);
                $this->db->set('in_meniu_principal', $p['in_meniu_principal']);
                $this->db->set('activ', $p['activ']);
                $this->db->where('id', $id);
                if( $this->db->update( 'pagini' ) ) {
                    $data['succes'] = "Pagina a fost salvata cu succes.";
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
        $this->db->delete( 'pagini', array('id'=>$id) );

        $data["succes"] = "Pagina a fost stearsa cu succes";

        $this->index( $data );
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */