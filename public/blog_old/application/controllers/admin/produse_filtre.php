<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produse_filtre extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        //$this->output->enable_profiler(true);
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('text');
        $this->load->helper('html');
        $this->load->helper('breadcrumbs');
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->model('admin/produse_model', 'produsem');
        $this->load->model('admin/filtre_model', 'filtrem');
        $this->config->load('table');
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
    }

    public function index( $id_produs, $data = array() ) {
        $data["page"] = "produse_filtre";
        
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_produse_filtre( $data );
        } elseif( isset( $p['salveaza'] ) ) {
            $data = $this->update_produse_filtre( $data );
        }
        
        $data['produs'] = $this->produsem->get_produs( $id_produs );
        $data['produs'] = $data['produs'][0];
        
        $filtre = $this->produsem->get_filtre_produs( $id_produs );

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Filtru', 'Cod EAN13', 'Pret', 'Greutate', 'Stoc', 'Optiuni');
        $k = 1;
        foreach ($filtre as $item) {
            $item_filtre = json_decode($item['id_filtre']);
            $nume_filtru = array();
            foreach ($item_filtre as $item_filtru) {
                $item_filtru_full = $this->filtrem->get_filtru( $item_filtru );
                $nume_parinte = $this->produsem->get_nume_filtru( $item_filtru_full[0]['id_parinte'] );
                
                $nume_filtru[] = "<strong>{$nume_parinte}</strong> &rarr; {$item_filtru_full[0]['nume']}";
            }
            $nume_filtru = implode( " / ", $nume_filtru );
            
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi filtrul: {$nume_filtru}?')"
            );

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                anchor("admin/produse_filtre/editeaza/{$id_produs}/{$item['id']}", $nume_filtru ) ,
                array(
                    'data' =>
                        form_input( 
                                array(
                                    "name" => "cod_ean13[]",
                                    "value" => $item['cod_ean13'],
                                )
                        ),
                    'width' => '100',
                ),
                array(
                    'data' =>
                        form_input( 
                                array(
                                    "name" => "pret[]",
                                    "value" => $item['pret'],
                                    "class" => "width50"
                                )
                        ),
                    'width' => '50',
                    'align' => 'right',
                ),
                array(
                    'data' =>
                        form_input( 
                                array(
                                    "name" => "greutate[]",
                                    "value" => $item['greutate'],
                                    "class" => "width50"
                                )
                        ),
                    'width' => '50',
                    'align' => 'right',
                ),
                array(
                    'data' =>
                        form_input( 
                                array(
                                    "name" => "stoc[]",
                                    "value" => $item['stoc'],
                                    "class" => "width50"
                                )
                        ),
                    'width' => '50',
                    'align' => 'right',
                ),
                array(
                    'data' =>
                        anchor("admin/produse_filtre/sterge/{$id_produs}/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) . 
                        anchor("admin/produse_filtre/editeaza/{$id_produs}/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) . 
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
                <td colspan="6" align="right">
                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici filtru gasit pentru produsul: {$data['produs']['nume']}. " . anchor("admin/produse_filtre/adauga/{$id_produs}", "Adauga un nou filtru");
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/produse/editeaza/{$id_produs}"),
                    "titlu" => $data['produs']['nume'],
                    "class" => "current",
                ),
                array(
                    "link" => "",
                    "titlu" => "Filtre produs",
                    "class" => "current",
                ),
            );
        
        $data["pag"] = "produse_filtre";
        $data["page_view"] = "produse_filtre";

        $this->load->library('admin/display', $data);
    }
    
    
    public function sterge_produse_filtre( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $this->db->delete('produse_filtre', array('id' => $id));
            }
            $data['succes'] = "Filtrele selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici un filtru.";
        }
        
        return $data;
    }
    
    public function update_produse_filtre( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id_edit'] ) ) {
            $k = 0;
            
            foreach ($p['id_edit'] as $id) {
                $filtru = $this->produsem->get_filtru_produs( $id );
                if( !empty( $filtru ) ) {
                    $produs =  $this->produsem->get_produs( $filtru['id_produs'] );
                    if( !empty( $produs ) ) {
                        $this->db->set('cod_ean13', $p['cod_ean13'][$k]);
                        
                        if( $p['pret'][$k]!="" && $produs[0]['pret']!= round( $p['pret'][$k], 2 ) ) {
                            $this->db->set('pret', $p['pret'][$k]);
                        } else {
                            $this->db->set('pret', "NULL", FALSE);                            
                        }
                        
                        if( $p['greutate'][$k]!="" && $produs[0]['greutate']!= round( $p['greutate'][$k], 2 ) ) {
                            $this->db->set('greutate', $p['greutate'][$k]);
                        } else {
                            $this->db->set('greutate', "NULL", FALSE);                            
                        }
                        
                        $this->db->set('stoc', $p['stoc'][$k]);
                        $this->db->where('id', $id);
                        $this->db->update('produse_filtre');
                    }
                }
                
                $k++;
            }
            
            $data['succes'] = "Filtrele au fost salvate cu succes.";
        }
        
        return $data;
    }

    public function adauga( $id_produs, $data = array() ) {
        $data["page"] = "produse_filtre";
        $data["page_view"] = "produse_filtre_edit";
        $data['produs'] = $this->produsem->get_produs( $id_produs );
        $data['produs'] = $data['produs'][0];
        
        $items = $this->indexm->get_filtre( 0 );
        $data['filtre'] = array();
        $k = 0;
        foreach( $items as $item ) {
            $data['filtre'][$k]['nume'] = $item['nume'];
            $data['filtre'][$k]['subfiltre'] = array( 0 => 
                array( 
                    "id" => "", 
                    "nume" => "ALEGE", 
                    "selected" => "" 
                ) );
            $items2 = $this->indexm->get_filtre( $item['id'] );
            $y = 1;
            foreach( $items2 as $item2 ) {
                $data['filtre'][$k]['subfiltre'][$y]['id'] = $item2['id']; 
                $data['filtre'][$k]['subfiltre'][$y]['nume'] = $item2['nume']; 
                $data['filtre'][$k]['subfiltre'][$y]['selected'] = isset( $_POST['id_filtre'] ) && in_array($item2['id'], $_POST['id_filtre']) ? "SELECTED" : "";
                
                $y++;
            }
            
            $k++;
        }

        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/produse/editeaza/{$id_produs}"),
                    "titlu" => $data['produs']['nume'],
                    "class" => "current",
                ),
                array(
                    "link" => site_url("admin/produse_filtre/index/{$id_produs}"),
                    "titlu" => "Filtre produs",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; filtru",
                    "class" => "current",
                ),
            );
        
        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id_produs, $id, $data = array() ) {
        $data["page"] = "produse_filtre";
        $data["page_view"] = "produse_filtre_edit";
        
        $data['produs'] = $this->produsem->get_produs( $id_produs );
        $data['produs'] = $data['produs'][0];
        
        $data['item'] = $this->produsem->get_filtru_produs( $id );
        $id_filtre = json_decode( $data['item']['id_filtre'] );
        $items = $this->indexm->get_filtre( 0 );
        $data['filtre'] = array();
        $k = 0;
        foreach( $items as $item ) {
            $data['filtre'][$k]['nume'] = $item['nume'];
            $data['filtre'][$k]['subfiltre'] = array( 0 => 
                array( 
                    "id" => "", 
                    "nume" => "ALEGE", 
                    "selected" => "" 
                ) );
            $items2 = $this->indexm->get_filtre( $item['id'] );
            $y = 1;
            foreach( $items2 as $item2 ) {
                $data['filtre'][$k]['subfiltre'][$y]['id'] = $item2['id']; 
                $data['filtre'][$k]['subfiltre'][$y]['nume'] = $item2['nume']; 
                $data['filtre'][$k]['subfiltre'][$y]['selected'] = isset( $_POST['id_filtre'] ) && in_array($item2['id'], $_POST['id_filtre']) ? "SELECTED" : ( is_array($id_filtre) && in_array($item2['id'], $id_filtre) ? "SELECTED" : "" );
                
                $y++;
            }
            
            $k++;
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/produse/editeaza/{$id_produs}"),
                    "titlu" => $data['produs']['nume'],
                    "class" => "current",
                ),
                array(
                    "link" => site_url("admin/produse_filtre/index/{$id_produs}"),
                    "titlu" => "Filtre produs",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; filtru",
                    "class" => "current",
                ),
            );
        
        $data["page_view"] = "produse_filtre_edit";

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id_produs, $id = 0 ) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_filtre', 'Filtre', 'required|callback_check_filtru_produs['.$id_produs.','. $id .']');
        $this->form_validation->set_rules('cod_ean13', 'Cod EAN13', 'trim|required|xss_clean');
        $this->form_validation->set_rules('greutate', 'Greutate', 'trim|numeric');
        $this->form_validation->set_rules('pret', 'Pret', 'trim|numeric');
        $this->form_validation->set_rules('stoc', 'Stoc', 'trim|required|numeric');
        
        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            $p['id_filtre'] = array_filter($p['id_filtre']);
            sort($p['id_filtre']);
            $p['id_filtre'] = json_encode($p['id_filtre']);
            
            $produs =  $this->produsem->get_produs( $id_produs );
            if( !empty( $produs ) ) {
                if( $p['pret']!="" && $produs[0]['pret']!= round( $p['pret'], 2 ) ) {
                    $this->db->set('pret', $p['pret']);
                } else {
                    $this->db->set('pret', "NULL", FALSE);                            
                }

                if( $p['greutate']!="" && $produs[0]['greutate']!= round( $p['greutate'], 2 ) ) {
                    $this->db->set('greutate', $p['greutate']);
                } else {
                    $this->db->set('greutate', "NULL", FALSE);                            
                }
            }
            
            if( $id == 0 ) {
                $this->db->set('id_produs', $id_produs);
                $this->db->set('id_filtre', $p['id_filtre']);
                $this->db->set('greutate', $p['greutate']);
                $this->db->set('cod_ean13', $p['cod_ean13']);
                $this->db->set('stoc', $p['stoc']);

                if( $this->db->insert('produse_filtre') ) {
                    $data['succes'] = "Filtrul a fost salvat cu succes.";
                    $this->form_validation->_field_data = array();
                    $_POST = array();
                }
            } else {
                $this->db->set('id_produs', $id_produs);
                $this->db->set('id_filtre', $p['id_filtre']);
                $this->db->set('greutate', $p['greutate']);
                $this->db->set('cod_ean13', $p['cod_ean13']);
                $this->db->set('stoc', $p['stoc']);
                $this->db->where('id', $id);
                if( $this->db->update('produse_filtre') ) {
                    $data['succes'] = "Filtrul a fost salvat cu succes.";
                }
            }
        }
        $data = isset( $data ) ? $data : array();
        if( $id==0 ) {
            $this->adauga( $id_produs, $data );
        } else {
            $this->editeaza( $id_produs, $id, $data );
        }
    }

    public function sterge( $id_filtru, $id ) {
        $this->db->delete('produse_filtre', array('id' => $id));

        $data["succes"] = "Filtrul a fost sters cu succes.";

        $this->index( $id_filtru, $data );
    }
    
    public function check_filtru_produs($id_filtre, $attr) {
        $filtre = array_filter( $id_filtre );
        sort( $filtre );
        $filtre = json_encode($filtre);
        
        list( $id_produs, $id_curent ) = explode(",", $attr);
        
        $this->db->where("id_filtre", $filtre);
        $this->db->where("id_produs", $id_produs);
        $this->db->where("id <>", $id_curent);
        
        if( $this->db->get("produse_filtre")->num_rows()>0 ) {
            $this->form_validation->set_message('check_filtru_produs', 'Acest tip de filtru exista deja pentru acest produs.');
            return false;
        }

        return true;
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */