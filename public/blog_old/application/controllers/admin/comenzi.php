<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class comenzi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);

        parent::__construct();
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->library('pagination');
        $this->load->helper('html');
        $this->load->helper('text');
        $this->load->helper('form');
        $this->load->helper('breadcrumbs');
        $this->load->helper('setari');
        $this->load->helper('email_template');
        $this->load->driver('cache');
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->model('admin/comenzi_model', 'comenzim');
        $this->load->model('admin/produse_model', 'produsem');
        $this->load->model('user_model', 'userm');
        $this->config->load('table');
        if (!$this->simpleloginsecure->is_admin()) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        
        $p = $this->input->post();
        if( isset( $p['sterge'] ) ) {
            $data = $this->sterge_comenzi( $data );
        } 
        
        $data["page"] = "comenzi";
        
        $data["options_cauta_dupa"] = array(
            ""   => "ORICARE",
            "nr_factura" => "Nr. factura",
            "nume_client" => "Nume client",
        );
        
        $data["options_stare"] = array(
            ""   => "ORICARE",
            "-1" => "Anulata",
            "0"  => "Noua",
            "1"  => "In procesare",
            "2"  => "Expediata",
            "3"  => "Finalizata",
        );
        
        $data["options_metoda_plata"] = array(
            ""   => "ORICARE",
            "1" => "Ramburs",
            "2"  => "Online Mobilpay.ro",
            "3"  => "Transfer Bancar",
        );
            
        $q = array(
            "q" => trim( $this->input->get("q") ),
            "dupa" => trim( $this->input->get("dupa") ),
            "stare" => trim( $this->input->get("stare") ),
            "id_tip_plata" => trim( $this->input->get("id_tip_plata") ),
            "data_start" => $this->input->get("data_start"),
            "data_sfarsit" => $this->input->get("data_sfarsit"),
        );
        
        $offset = $this->input->get('per_page');
        $this->pConfig = array();
        $this->pConfig['per_page'] = 50; //200
        $this->pConfig['page_query_string'] = TRUE;
        $this->pConfig['base_url'] = base_url() . "admin/comenzi?".  build_url_string();
        $data['total_locatii'] = $this->pConfig['total_rows'] = count($this->comenzim->get_comenzi($q));
        $this->pagination->initialize($this->pConfig);

        $items = $this->comenzim->get_comenzi($q, $this->pConfig['per_page'], $offset);

        $this->table->set_template( $this->config->item('table_config') );
        $this->table->set_heading('#', 'Nr. factura', 'Data adaugare', 'Nume client', 'Valoare(lei)', 'Stare', 'Plata', 'Optiuni');
        $k = 1;
        foreach ($items as $item) {
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi comanda cu id: #{$item['nr_factura']} din data de: {$item['data_adaugare']}?')"
            );
                
            switch ($item['stare']) {
                case "-1":
                    $item['stare'] = "Anulata";
                    break;

                case "0":
                    $item['stare'] = "Comanda noua";
                    break;

                case "1":
                    $item['stare'] = "Comanda preluata";
                    break;

                case "2":
                    $item['stare'] = "Comanda livrata";
                    break;

                case "3":
                    $item['stare'] = "Comanda finalizata";
                    break;

                default:
                    break;
            }
            
            switch( $item['stare_plata'] ) {
                case -2:
                    $item['stare_plata'] = "Respinsa";
                    break;
                case -1:
                    $item['stare_plata'] = "Anulata";
                    break;
                case 0:
                    $item['stare_plata'] = "In procesare";
                    break;
                case 1:
                    $item['stare_plata'] = "Confirmata";
                    break;
            }
            
            switch( $item['id_tip_plata'] ) {
                case "1":
                    $item['metoda_plata'] = "Ramburs";
                    $item['metoda_plata_class'] = "payment-ramburs";
                    break;
                case "2":
                    $item['metoda_plata'] = "Online Mobilpay.ro";
                    $item['metoda_plata_class'] = "payment-mobilpay";
                    break;
                case "3":
                    $item['metoda_plata'] = "Transfer bancar";
                    $item['metoda_plata_class'] = "payment-tb";
                    break;
            }

            $row = array(
                array(
                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',
                    'width' => '20',
                ),
                array(
                    'data' => "#".$item['nr_factura'],
                    'width' => '30'
                ),
                anchor("admin/comenzi/comanda/{$item['id']}", $item['data_adaugare'], "title='{$item['nota_interna']}'"),
                $item['nume_client'],
                array(
                    'data'  => $item['valoare'], 
                    'width' => '50'
                ),
                $item['stare'],
                "{$item['stare_plata']}<br /><span class='{$item['metoda_plata_class']}'>{$item['metoda_plata']}</span>",
                array(
                    'data' => anchor("admin/comenzi/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete), 
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
                <td colspan="2">
                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi comenzile selectate?\')" />
                </td>
                <td colspan="4" align="right">
                    <div class="pagination">'. $this->pagination->create_links() .'</div>
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['warning'] = "Nici o comanda gasita.";
        }
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Comenzi",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "comenzi";

        $this->load->library('admin/display', $data);
    }
    
    public function comanda($id_comanda, $data = array()) {
        
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $data['options_stare'] = array(
            "-1" => "Anulata",
            "0"  => "Noua",
            "1"  => "In procesare",
            "2"  => "Expediata",
            "3"  => "Finalizata",
        );
        
        $data['options_stare_plata'] = array(
            "-2" => "Anulata",
            "-1"  => "Respinsa",
            "0"  => "In procesare",
            "1"  => "Confirmata",
        );
        
        $comanda = $this->comenzim->get_comanda( $id_comanda );
        
        if( empty( $comanda ) ) {
            $data['error'] = "Aceasta comanda nu exista sau a fost stearsa.";
        } else {
            $data['comanda'] = $comanda;
            $data['comanda']['mesaj'] = nl2br( $comanda['mesaj'] );

            switch ($comanda['id_tip_plata']) {
                case "1":
                    $data['comanda']['tip_plata'] = "Ramburs";
                    break;

                case "2":
                    $data['comanda']['tip_plata'] = "Online Mobilpay.ro";
                    break;

                case "3":
                    $data['comanda']['tip_plata'] = "Transfer bancar";
                    break;
            }

            $data['user'] = $this->userm->get_user( $data['comanda']['id_user'] );
            if( $data['user']['livrare_adresa_1']==1 ) {
                $data['user'] = $this->userm->get_user_livrare( $data['comanda']['id_user'] );
            }

            $items = $this->comenzim->get_produse_comanda( $data['comanda']['id'] );
            $data['items'] = array();
            $k = 0;
            foreach ($items as $item) {
                $data['items'][$k] = $item;
                $data['items'][$k]['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id_produs'] );

                $ids_filtre = explode(",", $item['filtre']);
                $data['items'][$k]['filtre'] = array();
                $y = 0;
                foreach ($ids_filtre as $id_filtru) {
                    $filtru =  $this->comenzim->get_filtru( $id_filtru );
                    if( !empty( $filtru ) ) {
                        $data['items'][$k]['filtre'][$y]['nume_parinte'] = $filtru['nume_parinte'];
                        $data['items'][$k]['filtre'][$y]['nume_filtru'] = $filtru['nume'];

                        $y++;
                    }
                }

                $k++;
            }

            $data['breadcrumbs'] = array(
                    array(
                        "link" => site_url("admin/comenzi"),
                        "titlu" => "Comenzi",
                    ),
                    array(
                        "link" => "",
                        "titlu" => "Comanda #{$comanda['nr_factura']} din {$comanda['data_adaugare']}",
                        "class" => "current",
                    ),
                );
        }
        $data["page"] = "comanda";
        $data["page_view"] = "comanda";

        $this->load->library('admin/display', $data);        
    }
    
    public function salveaza($id_comanda) {
        $data = array();
        
        $data['comanda'] = $this->comenzim->get_comanda( $id_comanda );
        $data['comanda']['mesaj'] = nl2br( $data['comanda']['mesaj'] );
        
        $data['user'] = $this->userm->get_user( $data['comanda']['id_user'] );
        if( $data['user']['livrare_adresa_1']==1 ) {
            $data['user'] = $this->userm->get_user_livrare( $data['comanda']['id_user'] );
        }
        
        $items = $this->comenzim->get_produse_comanda( $data['comanda']['id'] );
        $data['items'] = array();
        $k = 0;
        foreach ($items as $item) {
            $data['items'][$k] = $item;
            $data['items'][$k]['furl'] = $this->functions->make_furl_produs( $item['nume'], $item['id_produs'] );

            $ids_filtre = explode(",", $item['filtre']);
            $data['items'][$k]['filtre'] = array();
            $y = 0;
            foreach ($ids_filtre as $id_filtru) {
                $filtru =  $this->comenzim->get_filtru( $id_filtru );
                if( !empty( $filtru ) ) {
                    $data['items'][$k]['filtre'][$y]['nume_parinte'] = $filtru['nume_parinte'];
                    $data['items'][$k]['filtre'][$y]['nume_filtru'] = $filtru['nume'];

                    $y++;
                }
            }
            
            $k++;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('stare', 'Stare', 'trim|required|numeric');
        $this->form_validation->set_rules('mesaj_admin', 'Mesaj optional', 'trim|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();

            $this->db->set('stare', $p['stare']);
            $this->db->where('id', $id_comanda);
            if( $this->db->update( 'comenzi' ) ) {
                if( $this->db->affected_rows()>0 ) {
                    
                    switch ($p['stare']) {
                        case "-1":
                            //comanda anulata
                            $info = array(
                                "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                                "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                                "__DATA_COMANDA__" => $data['comanda']['data_adaugare_f'],
                                "__MESAJ_ADMIN__" => nl2br($p['mesaj_admin']),
                            );

                            $data['titlu'] = email_template(6, "subiect", $info);
                            $data['continut'] = email_template(6, "continut", $info);
                            
                            break;
                        case "0":
                            //comanda vizualizata
                            $info = array(
                                "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                                "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                                "__DATA_COMANDA__" => $data['comanda']['data_adaugare_f'],
                                "__MESAJ_ADMIN__" => nl2br($p['mesaj_admin']),
                            );

                            $data['titlu'] = email_template(7, "subiect", $info);
                            $data['continut'] = email_template(7, "continut", $info);
                            
                            break;

                        case "1":
                            //comanda preluata
                            $info = array(
                                "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                                "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                                "__DATA_COMANDA__" => $data['comanda']['data_adaugare_f'],
                                "__MESAJ_ADMIN__" => nl2br($p['mesaj_admin']),
                            );

                            $data['titlu'] = email_template(8, "subiect", $info);
                            $data['continut'] = email_template(8, "continut", $info);
                            
                            break;

                        case "2":
                            //comanda expediata
                            //in momentul in care comanda a fost expediata atunci se considera ca produsele au fost platite deja
                            $this->db->set('stare_plata', 1);
                            $this->db->where('id', $id_comanda);
                            $this->db->update( 'comenzi' );
                            
                            
                            $info = array(
                                "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                                "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                                "__DATA_COMANDA__" => $data['comanda']['data_adaugare_f'],
                                "__MESAJ_ADMIN__" => nl2br($p['mesaj_admin']),
                            );

                            $data['titlu'] = email_template(9, "subiect", $info);
                            $data['continut'] = email_template(9, "continut", $info);
                            
                            break;

                        case "3":
                            $info = array(
                                "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                                "__NR_FACTURA__" => $data['comanda']['nr_factura'],
                                "__DATA_COMANDA__" => $data['comanda']['data_adaugare_f'],
                                "__MESAJ_ADMIN__" => nl2br($p['mesaj_admin']),
                            );

                            $data['titlu'] = email_template(10, "subiect", $info);
                            $data['continut'] = email_template(10, "continut", $info);
                            
                            break;
                    }
                    
                    $this->load->library('email');

                    $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                    $this->email->to( $data['user']['user_email'] );
                    $this->email->subject( $data['titlu'] );
                    $message = $this->load->view("template_comanda", $data, true);

                    $this->email->message( $message );
                    $this->email->send();
                    
                    $this->session->set_flashdata('succes', "Comanda a fost salvata cu succes.");
                    redirect( "admin/comenzi/comanda/{$id_comanda}" );
                } else {
                    $data['warning'] = "Comanda nu a fost modificata, astfel nu a fost trimis nici un email catre client.";
                }
            }
        }

        $this->comanda( $id_comanda, $data );
    }
    
    public function salveaza_plata($id_comanda) {
        $p = $this->input->post();

        $data = array();
        $this->db->set('stare_plata', $p['stare_plata']);
        $this->db->where('id', $id_comanda);
        if( $this->db->update( 'comenzi' ) ) {
            if( $p['stare_plata']==-2 && $this->db->affected_rows()>0 ) {
                $this->db->select("a.id_produs, a.cantitate");
                $this->db->from("comenzi_produse a");
                $this->db->where("a.id_comanda", $id_comanda);
                $comanda_produse = $this->db->get()->result_array();
                
                $cod_produse = array();
                foreach ($comanda_produse as $produs) {
                    
                    $this->db->set("stoc", "stoc+{$produs['cantitate']}", FALSE);
                    $this->db->where("id", $produs['id_produs']);
                    $this->db->update("produse");
                    
                    $cod_produs = $this->db->select("cod_ean13")->where("id", $produs['id_produs'])->get("produse")->row_array();
                    if( !empty( $cod_produs ) ) {
                        $cod_produse[] = $cod_produs['cod_ean13'];
                    }
                    //stergere cache categorii
                    $produse_categorii = $this->produsem->get_categorii_produs( $produs['id_produs'] );
                    foreach ($produse_categorii as $prod) {
                        $this->cache->file->delete_regexp("/produse_{$prod['id_categorie']}_.*/");
                    }
                }
                
                //sync stoc
                $this->functions->db_sync_request( $cod_produse );
                
                $this->cache->file->delete("produse_promovate_index");
            }
            $data['succes'] = "Starea comenzii a fost salvata cu succes.";
        }

        $this->comanda( $id_comanda, $data );
    }
    
    public function salveaza_nota_interna($id_comanda) {
        $p = $this->input->post();

        $this->db->set('nota_interna', $p['nota_interna']);
        $this->db->where('id', $id_comanda);
        if( $this->db->update( 'comenzi' ) ) {
            $this->session->set_flashdata('succes', "Nota interna a fost salvata cu succes.");
            redirect( "admin/comenzi/comanda/{$id_comanda}" );
        }
        
        $this->comanda( $id_comanda );
    }
    
    public function sterge_comenzi( $data = array() ) {
        $p = $this->input->post();
        if( isset( $p['id'] ) ) {
            foreach ($p['id'] as $id) {
                $comanda = $this->comenzim->get_comanda( $id );
                if( !empty( $comanda ) ) {
                    $this->db->delete('comenzi', array('id' => $id));
                    $this->db->delete('comenzi_produse', array('id_comanda' => $id));
                    @unlink( dirname(__FILE__) . "/../facturi/factura{$comanda['nr_factura']}.pdf" );
                }
            }
            $data['succes'] = "Comenzile selectate au fost sterse cu succes.";
        } else {
            $data['warning'] = "Nu ati selectat nici un banner.";
        }
        
        return $data;
    }
    
    public function sterge( $id ) {
        $comanda = $this->comenzim->get_comanda( $id );
        if( !empty( $comanda ) ) {
            $this->db->delete('comenzi', array('id' => $id));
            $this->db->delete('comenzi_produse', array('id_comanda' => $id));
            @unlink( dirname(__FILE__) . "/../facturi/factura{$comanda['nr_factura']}.pdf" );
        }

        $data["succes"] = "Comanda a fost stearsa cu succes";

        $this->index( $data );
    }
 

}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */