<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class categorii extends CI_Controller {
    private $categorii = "";
    private $id_parinte = 0;
    private $id_categorie = 0;
    private $ids = array();
    
    public function __construct() {
        parent::__construct();
        
//        $this->output->enable_profiler(true);
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->helper('text');
        $this->load->helper('html');
        $this->load->helper('breadcrumbs');
        $this->load->driver('cache');
        $this->load->model('admin/index_page_model', 'indexm');
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_categorii( $data );
        } elseif( isset( $p['update_categorii'] ) ) {
            $data = $this->update_categorii( $data );
        }
        
        $data["page"] = "categorii";
        
        $categorii = $this->indexm->get_categorii();
        
        if( !empty( $categorii ) ) {
            $categorii = $this->functions->categoriesToTree( $categorii );
            $this->build_tree( $categorii );
            $data['categorii_tree'] = $this->categorii;
        } else {
            //TODO: daca nu gaseste nici o categorie sa fie default .. categorie principala
            $data['error'] = "Nici o categorie gasita.";
        }
        
        $data['title'] = 
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Categorii",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "categorii";

        $this->load->library('admin/display', $data);
    }
    
    
    public function sterge_categorii( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $count_produse = $this->indexm->count_produse_by_id_categorie( $id );
                if( $count_produse==0 ) {
                    $item = $this->indexm->get_categorie( $id );
                    if( !empty( $item ) ) {
                        $this->db->delete('categorii', array('id' => $id));
                        @unlink( MAINSITE_STYLE_PATH . "images/categorii/" . $item[0]['imagine'] );
                    }
                } else {
                    $data['error'] = "Una din categoriile selectate nu a fost stearsa deoarece contine in ea produse sau alte subcategorii cu produse in ele.";
                }
            }
            
            $this->clear_cache();
            
            $data['succes'] = "Categoriile selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici o categorie.";
        }
        
        return $data;
    }
    
    public function update_categorii( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id_edit'] ) ) {
            $k = 0;
            
            foreach ($p['id_edit'] as $id) {
                $this->db->set('ordonare', $p['ordonare'][$k]);
                $this->db->set('activ', $p['activ'][$k]);
                $this->db->where('id', $id);
                $this->db->update('categorii');
                
                if( $p['pret_multiplicator'][$k]!=0 ) {
                    $this->ids = array( $id );
                    $this->get_id_subcategorii_by_id_parinte( $id );
                    foreach ($this->ids as $id_categorie) {
                        
                        $this->db->select("id_produs");
                        $this->db->where("id_categorie", $id_categorie);
                        $produse = $this->db->get("produse_categorii")->result_array();
                        
                        foreach ($produse as $produs) {
                            $this->db->set("pret_multiplicator", $p['pret_multiplicator'][$k]);
                            $this->db->set("pret_user", "pret*{$p['pret_multiplicator'][$k]}", FALSE);
                            $this->db->where("id", $produs['id_produs']);
                            $this->db->update("produse");
                        }
                    }
                }
                
                $k++;
            }
            
            $this->clear_cache();
            
            $data['succes'] = "Categoriile au fost salvate cu succes.";
        }
        
        return $data;
    }
    
    function get_id_subcategorii_by_id_parinte( $id ) {
        $idc = $id;
        do {
            $this->db->select("id")->where("id_parinte", $idc);
            $result = $this->db->get("categorii")->result_array();
            foreach ($result as $cat) {
                $this->ids[] = $cat['id'];
                $this->get_id_subcategorii_by_id_parinte($cat['id']);
            }
            
        } while( !empty( $ids ) );
    }

    public function adauga( $data = array() ) {
        $data["page"] = "categorii";
        $data["page_view"] = "categorii_edit";
        
        $this->load->library('admin/fckeditor');

        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = "99%";
        $this->fckeditor->Height = 400;
        $data['editor'] = $this->fckeditor->CreateHtml() ;
        
        $data['options_activ'] = array(
                "1" => "DA",
                "0" => "NU",
            );
        
        $categorii = $this->indexm->get_categorii();
        $categorii = $this->functions->categoriesToTree( $categorii );
        $this->build_tree_select($categorii);
        $data['categorii_tree'] = $this->categorii;

        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/categorii"),
                    "titlu" => "Categorii",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; categorie",
                    "class" => "current",
                ),
            );
        
        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "categorii";

        $item = $this->indexm->get_categorie( $id );
        $data['item'] = $item[0];
        $this->id_parinte = $item[0]['id_parinte'];
        $this->id_categorie = $item[0]['id'];

        $this->load->library('admin/fckeditor');

        $this->fckeditor->BasePath = base_url() . '/plugins/fckeditor/';
        $this->fckeditor->Width = "99%";
        $this->fckeditor->Height = 400;
        $this->fckeditor->Value = $item[0]['descriere'];
        $data['editor'] = $this->fckeditor->CreateHtml() ;
        
        $categorii = $this->indexm->get_categorii();
        $categorii = $this->functions->categoriesToTree( $categorii );
        $this->build_tree_select($categorii);
        $data['categorii_tree'] = $this->categorii;

        $data['options_activ'] = array(
                "1" => "DA",
                "0" => "NU",
            );
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/categorii"),
                    "titlu" => "Categorii",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; categorie",
                    "class" => "current",
                ),
            );
        
        $data["page_view"] = "categorii_edit";

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id = 0 ) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|xss_clean');
        $this->form_validation->set_rules('FCKeditor', 'Descriere', 'trim|xss_clean');
        $this->form_validation->set_rules('seo_title', 'SEO Title', 'trim|xss_clean');
        $this->form_validation->set_rules('meta_description', 'META Description', 'trim|xss_clean');
        $this->form_validation->set_rules('meta_keywords', 'META Keywords', 'trim|xss_clean');
        $this->form_validation->set_rules('id_parinte', 'Parinte', 'trim|required|numeric|callback_check_id_parinte['. $id .']');
        $this->form_validation->set_rules('activ', 'Activ', 'trim|required|numeric');
        
        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            
            $config['upload_path'] = MAINSITE_STYLE_PATH . 'images/categorii/temp/';
            $config['allowed_types'] = 'gif|jpg|png';
            $this->load->library('upload', $config);
            $imagine_galerie = "";
            if ($this->upload->do_upload( 'imagine' )) {
                $imagine = $this->upload->data();
                
                $this->load->library('classupload');
                $this->classupload->upload($config['upload_path'] . $imagine['file_name']);
                if ($this->classupload->uploaded) {
                    $this->classupload->image_resize = true;
                    $this->classupload->image_ratio_fill = true;
                    $this->classupload->image_y = 70;
                    $this->classupload->image_x = 70;

                    $this->classupload->Process($config['upload_path'] . "../");

                    if ($this->classupload->processed) {
                        @unlink($config['upload_path'] . $imagine_galerie);
                        $imagine_galerie = $this->classupload->file_dst_name;
                        $this->classupload->clean();
                        
                        if( $id != 0 ) {
                            $this->sterge_imagine($id);
                        }
                    }
                }
            }
            
            if( $id == 0 ) {
                $this->db->set('id_parinte', $p['id_parinte']);
                $this->db->set('nume', $p['nume']);
                $this->db->set('slug', $p['slug']);
                $this->db->set('descriere', $p['FCKeditor']);
                $this->db->set('seo_title', $p['seo_title']);
                $this->db->set('meta_description', $p['meta_description']);
                $this->db->set('meta_keywords', $p['meta_keywords']);
                $this->db->set('imagine', $imagine_galerie);
                $this->db->set('ordonare', $this->indexm->get_max_ordonare( 'categorii' ));
                $this->db->set('data_adaugare', 'NOW()', false);
                $this->db->set('activ', $p['activ']);

                if( $this->db->insert('categorii') ) {
                    $data['succes'] = "Categoria a fost salvata cu succes.";
                    $this->form_validation->_field_data = array();
                    $_POST = array();
                }
            } else {
                $this->db->set('id_parinte', $p['id_parinte']);
                $this->db->set('nume', $p['nume']);
                $this->db->set('slug', $p['slug']);
                $this->db->set('descriere', $p['FCKeditor']);
                $this->db->set('seo_title', $p['seo_title']);
                $this->db->set('meta_description', $p['meta_description']);
                $this->db->set('meta_keywords', $p['meta_keywords']);
                isset($imagine_galerie) && $imagine_galerie!="" ? $this->db->set('imagine', $imagine_galerie) : "";
                $this->db->set('activ', $p['activ']);
                $this->db->where('id', $id);

                if( $this->db->update('categorii') ) {
                    $data['succes'] = "Categoria a fost salvata cu succes.";
                }
            }
            
            $this->clear_cache();
            
        }
        $data = isset( $data ) ? $data : array();
        if( $id==0 ) {
            $this->adauga( $data );
        } else {
            $this->editeaza( $id, $data );
        }
    }

    public function sterge( $id ) {
        $data = array();
        $count_produse = $this->indexm->count_produse_by_id_categorie( $id );
        if( $count_produse==0 ) {
            $item = $this->indexm->get_categorie( $id );
            if( !empty( $item ) ) {
                $this->db->delete('categorii', array('id' => $id));
                @unlink( MAINSITE_STYLE_PATH . "images/categorii/" . $item[0]['imagine'] );
                
                $this->clear_cache();
                
                $data["succes"] = "Categoria a fost stearsa cu succes.";
            }
        } else {
            $data['error'] = "Categoria nu a fost stearsa deoarece contine in ea produse sau alte subcategorii cu produse in ele.";
        }


        $this->index( $data );
    }
    
    public function sterge_imagine( $id ) {
        $data = array();
        
        $item = $this->indexm->get_categorie( $id );
        @unlink( MAINSITE_STYLE_PATH . "images/categorii/" . $item[0]['imagine'] );
        
        $this->db->set( 'imagine', "" );
        $this->db->where( 'id', $id );
        $this->db->update( 'categorii' );
        
        $data["succes"] = "Imaginea a fost stearsa cu succes.";

        $this->editeaza( $id, $data );
    }
    
    public function build_tree( $categorii ) {
        if( !empty( $categorii ) ) {
            foreach ( $categorii as $categorie ) {
                $link_editare = site_url("admin/categorii/editeaza/{$categorie['id']}");
                
                $this->categorii .= "<tr>";
                
                $this->categorii .= '<td><input type="checkbox" name="id[]" value='. $categorie['id'] .' /></td>';
                
                $this->categorii .= "<td>";
                $this->categorii .= $categorie['id'];
                $this->categorii .= "</td>";
                
                $this->categorii .= "<td>";
                $this->categorii .= ( $categorie['id_parinte']==0 ? "<strong>". anchor( $link_editare, $categorie['nume'] ) ."</strong>" : "<span style='margin-left: ". $this->functions->getCategoriesDepth( $categorie['id'] ) * 15 ."px'>&rarr; ". anchor( $link_editare, $categorie['nume'] ) ) ."</span>";
                $this->categorii .= "</td>";
                
                $this->categorii .= "<td>";
                $this->categorii .= form_dropdown(
                                "pret_multiplicator[]",
                                array(
                                    "0" => "--",
                                    "1" => "1.0",
                                    "1.2" => "1.2",
                                    "1.4" => "1.4",
                                    "1.6" => "1.6",
                                ) );
                $this->categorii .= "</td>";
                
                $this->categorii .= "<td>";
                $this->categorii .= form_input( 
                                array(
                                    "name" => "ordonare[]",
                                    "value" => $categorie['ordonare'],
                                    "class" => "width50"
                                ) );
                $this->categorii .= "</td>";
                
                $this->categorii .= "<td>";
                $this->categorii .= form_dropdown( 
                                        'activ[]',
                                        array(
                                            "1" => "DA",
                                            "0" => "NU",
                                        ),
                                        $categorie['activ']
                                    );
                $this->categorii .= "</td>";
                
                $this->categorii .= "<td>";
                $this->categorii .= form_hidden('id_edit[]', $categorie['id']);
                $this->categorii .= anchor("admin/categorii/sterge/{$categorie['id']}", img(ADMIN_STYLE_PATH."images/icn_trash.png"), array( "onclick" => "return confirm('Esti sigur ca vrei sa  stergi categoria: {$categorie['nume']}?')") );
                $this->categorii .= anchor("admin/categorii/editeaza/{$categorie['id']}", img(ADMIN_STYLE_PATH."images/icn_edit.png"));
                $this->categorii .= "</td>";
                
                $this->categorii .= "</tr>";

                $this->build_tree( $categorie['subcategories'] );
            }
        }
    }
    
    public function build_tree_select( $categorii ) {
        if( !empty( $categorii ) ) {
            $this->categorii .= "<ul>";
            
            if( $this->categorii=="<ul>" ) {
                $this->categorii .= "<li style='list-style:none'><input type='radio' name='id_parinte' value='0' checked /><strong>CATEGORIE PRINCIPALA</strong></li>";
            }
            
            foreach ( $categorii as $categorie ) {
                $this->categorii .=  "<li style='list-style:none;'><input type='radio' name='id_parinte' value='{$categorie['id']}' ". ( $this->id_parinte==$categorie['id'] ? "CHECKED" : "" ) ." />" . ( $categorie['id_parinte']==0 ? "<strong>". $categorie['nume'] ."</strong>" : $categorie['nume'] ) 
                        . "</li>";

                $this->build_tree_select( $categorie['subcategories'] );
            }
            $this->categorii .= "</ul>";
        }
    }
    
    public function check_id_parinte($id_parinte, $id_categorie) {
        if( $id_categorie!=0 && $id_parinte==$id_categorie ) {
            $this->form_validation->set_message('check_id_parinte', 'Categoria parinte nu poate fi aceeasi cu categoria editata.');
            return false;
        }

        return true;
    }
    
    private function clear_cache() {
        $this->cache->file->delete('categorii');
        $this->cache->file->delete_regexp('/categorii_[0-9+].*/');
    }

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */