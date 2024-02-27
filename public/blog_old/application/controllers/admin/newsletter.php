<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Newsletter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->simpleloginsecure->is_logat("administratori")) {
            redirect('admin/login');
        }
        //$this->output->enable_profiler(TRUE);
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('setari');
        $this->load->helper('breadcrumbs');
        $this->load->helper('text');
        $this->load->model('index_page_model', 'indexmodel');
        $this->load->model('admin/newsletter_model', 'newsletterm');
        $this->load->library('admin/fckeditor');
        $this->config->load('table');
    }

    public function index($data = array()) {
        $data["page"] = "newsletter";
        
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_newsletter( $data );
        } elseif( isset( $p['salveaza'] ) ) {
            $data = $this->update_newsletter( $data );
        }
        
        $data["page"] = "newsletter";
        $newsletter = $this->newsletterm->get_newsletters();

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Titlu', 'Continut', 'Data adaugare', 'Optiuni');
        $k = 1;
        foreach ($newsletter as $item) {
            $js_titlu = str_replace("'", "`", $item['titlu']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi newsletterul: {$js_titlu}?')"
            );

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                array(
                    'data' => anchor("admin/newsletter/editeaza/{$item['id']}", $item['titlu']),
                    'width' => '150',
                ),
                word_limiter(strip_tags($item['continut']), 100),
                $item['data_adaugare'],
                array(
                    'data' =>
                        anchor("admin/newsletter/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) .
                        anchor("admin/newsletter/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 
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
                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi newsletterle selectate?\')" />
                    <input type="submit" name="salveaza" value="Trimite newsletter" class="alt_btn" onclick="return confirm(\'Esti sigur ca vrei sa trimiti newsletterle selectate catre clientii abonati?\')" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici un newsletter gasit. " . anchor('admin/newsletter/adauga', 'Adaug&#259; unul');
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Newsletter",
                    "class" => "current",
                ),
            );

        $data["page_view"] = "newsletter";

        $this->load->library('admin/display', $data);
    }
    
    public function sterge_newsletter( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $this->db->delete('newsletters', array('id' => $id));
                $this->db->delete('newsletters_queue', array('id_newsletter' => $id));
            }
            $data['succes'] = "Newsletterele selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici un newsletter.";
        }
        
        return $data;
    }
    
    public function update_newsletter( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            $k = 0;
            
            foreach ($p['id'] as $id) {
                $useri = $this->newsletterm->get_useri_abonati();
                foreach ($useri as $user) {
                    $this->db->where("id_user", $user['id']);
                    $this->db->where("id_newsletter", $id);
                    $this->db->from("newsletters_queue");
                    
                    if( $this->db->get()->num_rows()==0 ) {
                        $this->db->set("id_user", $user['id']);
                        $this->db->set("id_newsletter", $id);
                        $this->db->insert("newsletters_queue");
                    }
                }
                
                $k++;
            }
            
            $data['succes'] = "Newsletterele selectate vor fi trimise cu succes catre clientii abonati.";
        }
        
        return $data;
    }
    
    function adauga( $data = array() ) {

        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = 800;
        $this->fckeditor->Height = 400;
        $this->fckeditor->Value = isset( $_POST['FCKeditor'] ) ? htmlspecialchars_decode( $_POST['FCKeditor'] ) : "";
        $data['editor'] = $this->fckeditor->CreateHtml();
        
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/newsletter"),
                    "titlu" => "Newsletter"
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; newsletter",
                    "class" => "current",
                ),
            );
        $data["page"] = "newsletter_edit";
        $data["page_view"] = "newsletter_edit";
        $this->load->library('admin/display', $data);
        
    }
    
    function editeaza( $id, $data = array() ) {
        $data['item'] = $this->newsletterm->get_newsletter( $id );
        
        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = 800;
        $this->fckeditor->Height = 400;
        $this->fckeditor->Value = isset( $_POST['FCKeditor'] ) ? htmlspecialchars_decode( $_POST['FCKeditor'] ) : htmlspecialchars_decode( $data['item']['continut'] );
        $data['editor'] = $this->fckeditor->CreateHtml();
        
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/newsletter"),
                    "titlu" => "Newsletter"
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; newsletter",
                    "class" => "current",
                ),
            );
        $data["page"] = "newsletter_edit";
        $data["page_view"] = "newsletter_edit";
        $this->load->library('admin/display', $data);
        
    }

    public function salveaza( $id = 0 ) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('titlu', 'Titlu', 'trim|required|xss_clean');
        $this->form_validation->set_rules('FCKeditor', 'Continut', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();

            if( $id == 0 ) {
                $this->db->set('titlu', $p['titlu']);
                $this->db->set('continut', $p['FCKeditor']);
                $this->db->set('data_adaugare', 'NOW()', FALSE);
                if( $this->db->insert( 'newsletters' ) ) {
                    $id_newsletter  = $this->db->insert_id();
                    
                    if( isset( $p['salveaza_trimite'] ) ) {
                        $useri = $this->newsletterm->get_useri_abonati();
                        $k = 0;
                        foreach ($useri as $user) {
                            $this->db->where("id_user", $user['id']);
                            $this->db->where("id_newsletter", $id_newsletter);
                            $this->db->from("newsletters_queue");
                            if( $this->db->get()->num_rows()==0 ) {
                                $this->db->set("id_user", $user['id']);
                                $this->db->set("id_newsletter", $id_newsletter);
                                $this->db->insert("newsletters_queue");
                            }
                            
                            $k++;
                        }
                        
                        $data['succes'] = "Newsletterul a fost salvat si va fi trimis catre cei {$k} clienti abonati.";
                    }
                    
                    if( !isset( $data['succes'] ) ) {
                        $data['succes'] = "Newsletterul a fost salvat cu succes.";
                    }
                    $this->form_validation->_field_data = array();
                }
            } else {
                $this->db->set('titlu', $p['titlu']);
                $this->db->set('continut', $p['FCKeditor']);
                $this->db->where('id', $id);
                if( $this->db->update( 'newsletters' ) ) {
                    if( isset( $p['salveaza_trimite'] ) ) {
                        $useri = $this->newsletterm->get_useri_abonati();
                        $k = 0;
                        foreach ($useri as $user) {
                            $this->db->where("id_user", $user['id']);
                            $this->db->where("id_newsletter", $id);
                            $this->db->from("newsletters_queue");
                            if( $this->db->get()->num_rows()==0 ) {
                                $this->db->set("id_user", $user['id']);
                                $this->db->set("id_newsletter", $id);
                                $this->db->insert("newsletters_queue");
                            }
                            
                            $k++;
                        }
                        
                        $data['succes'] = "Newsletterul a fost salvat si va fi trimis catre cei {$k} clienti abonati.";
                    }
                    
                    if( !isset( $data['succes'] ) ) {
                        $data['succes'] = "Newsletterul a fost salvat cu succes.";
                    }
                    $this->form_validation->_field_data = array();
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
        $this->db->delete( 'newsletters', array('id'=>$id) );
        $this->db->delete( 'newsletters_queue', array('id_newsletter'=>$id) );

        $data["succes"] = "Newsletterul a fost sters cu succes.";

        $this->index( $data );
    }
 
}