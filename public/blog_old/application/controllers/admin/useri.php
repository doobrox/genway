<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class useri extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);
        $this->load->library('functions');
        $this->load->library('table');
        $this->load->library('pagination');
        $this->load->helper('html');
        $this->load->helper('text');
        $this->load->helper('setari');
        $this->load->helper('breadcrumbs');
        $this->load->model('admin/index_page_model', 'indexmodel');
        $this->load->model('admin/useri_model', 'userim');
        $this->load->model('admin/judete_model', 'judetem');
        $this->load->model('admin/localitati_model', 'localitatim');
        $this->config->load('table');
        
        if( !$this->simpleloginsecure->is_logat( "administratori" ) ) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {

        $data["page"] = "useri";
        
        $g = $this->input->get();
        
        $data['select_dupa'] = array(
            "a.nume" => "Nume",
            "a.prenume" => "Prenume",
            "user_email" => "Email",
            "telefon" => "Telefon",
            "adresa" => "Adresa",
        );
        
        $order_by = isset( $g['order_by'] ) ? $g['order_by'] : "";
        $dir = isset( $g['dir'] ) ? $g['dir'] : "asc";
        
        $q = isset( $g['q'] ) ? $g['q'] : "";
        $dupa = isset( $g['dupa'] ) ? $g['dupa'] : "";
        
        $factura_neplatita =  isset( $g['factura_neplatita'] ) ? true : false;
        
        $offset = $this->input->get('per_page');
        $this->pConfig = array();
        $this->pConfig['per_page'] = 50; //200
        $this->pConfig['page_query_string'] = TRUE;
        $this->pConfig['base_url'] = base_url() . "admin/useri?".  build_url_string();
        $data['total_locatii'] = $this->pConfig['total_rows'] = count($this->userim->get_useri( $order_by, $dir, $q, $dupa, $factura_neplatita ));
        $this->pagination->initialize($this->pConfig);
        
        $useri = $this->userim->get_useri( $order_by, $dir, $q, $dupa, $factura_neplatita, $this->pConfig['per_page'], $offset );

        $this->table->set_template( $this->config->item('table_config') );
        $url = base_url() . 'admin/useri' . build_url_string();
        //nr, nume, firma, email, nr. Comenzi, data inscrierii, modifica, sterge
        $this->table->set_heading( '#', 'Nume', 'Firma', 'Email', 'Nr. comenzi', 'Data inscriere', 'Reseller', 'Admin', 'Valid', 'Optiuni' );
        $k = 1;
        foreach( $useri as $user ) {
            $js_titlu = str_replace("'", "`", $user['user_email']);
            $attrDelete = array(
                "onclick" => "return confirm('Esti sigur ca vrei sa stergi userul: {$js_titlu}?')"
            );
            $bg_color = $user['reseller_cerere']==1 ? "#F76060" : "";

            $row = array(
                array(
                    'data' => $k,
                    'width' => '20',
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => "{$user['nume']} {$user['prenume']}",
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['nume_firma'],
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['user_email'],
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['nr_comenzi'],
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['data_adaugare'],
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['reseller']==1 ? "DA" : "NU",
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['admin']==1 ? "DA" : "NU",
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' => $user['valid']==1 ? "DA" : "NU",
                    'bgcolor' => $bg_color
                ),
                array(
                    'data' =>
                        anchor("admin/comenzi_raport/genereaza?data_start=" . date("Y-m-01", strtotime("-1 day -1 month")) . "&data_sfarsit=" . date("Y-m-t", strtotime("-1 day -1 month")) . "&id_user={$user['id']}", img(ADMIN_STYLE_PATH."images/icn_rulaj.png"), "title='Rulajul lunii anterioare'") .
                        anchor("admin/useri/sterge/{$user['id']}", img(ADMIN_STYLE_PATH."images/icn_trash.png"), $attrDelete) .
                        anchor("admin/useri/editeaza/{$user['id']}", img(ADMIN_STYLE_PATH."images/icn_edit.png"), "title='Editeaza'"), 
                    'width' => '75',
                    'bgcolor' => $bg_color
                )
            );
            $this->table->add_row( $row );

            $k++;
        }
        if( $k>1 ) {
            $row = '<tfoot>
               <tr>
                <td colspan="9" align="right">
                    <div class="pagination">'. $this->pagination->create_links() .'</div>
                </td>
            </tfoot>
            </table>';
            
            $data['tabelDate'] = $this->table->generate();
            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );
        } else {
            $data['error'] = "Nici un user gasit.";
        }
        $data["page_view"] = "useri";
        
        $data["title"] = "Utilizatori";
        $data['breadcrumbs'] = array(
                array(
                    "link" => "",
                    "titlu" => "Utilizatori",
                    "class" => "current",
                ),
            );
        $this->load->library('admin/display', $data);
    }

    public function adauga($data = array()) {
        $data["page"] = "useri";
        $p = $this->input->post();
        
        $items = $this->judetem->get_judete();
        $data['options_judete'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "SELECTEAZA JUDET" ); 
        
        if( isset( $_POST['id_judet'] ) ) {
            $items = $this->localitatim->get_localitati_by_judet( $_POST['id_judet'] );
            $data['options_localitati'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 
        } else {
            $data['options_localitati'] = array("" => "SELECTEAZA LOCALITATE");
        }

        $data['options_tip'] = array(
            1 => "Persoana fizica",
            2 => "Firma",
        );
        
        $data['options_admin'] = array(
            0 => "NU",
            1 => "DA",
        );

        $data['options_valid'] = array(
            1 => "DA",
            0 => "NU"
        );

        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/useri"),
                    "titlu" => "Utilizatori",
                ),
                array(
                    "link" => "",
                    "titlu" => "Adaug&#259; utilizator",
                    "class" => "current",
                ),
            );
        $data["page_view"] = "useri_edit";

        $this->load->library('admin/display', $data);
    }

    public function editeaza( $id, $data = array() ) {
        $data["page"] = "useri";
        $p = $this->input->post();

        $item = $this->userim->get_user( $id );
        $data['item'] = $item[0];

        $items = $this->judetem->get_judete();
        $data['options_judete'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 
        
        $items = $this->localitatim->get_localitati_by_judet( isset( $_POST['id_judet'] ) ? $_POST['id_judet'] : $item[0]['id_judet'] );
        $data['options_localitati'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 

        $data['options_tip'] = array(
            1 => "Persoana fizica",
            2 => "Firma",
        );
        
        $data['options_admin'] = array(
            0 => "NU",
            1 => "DA",
        );

        $data['options_valid'] = array(
            1 => "DA",
            0 => "NU"
        );
        
        $data['breadcrumbs'] = array(
                array(
                    "link" => site_url("admin/useri"),
                    "titlu" => "Utilizatori",
                ),
                array(
                    "link" => "",
                    "titlu" => "Editeaz&#259; utilizator",
                    "class" => "current",
                ),
            );
        
        $data["page_view"] = "useri_edit";

        $this->load->library('admin/display', $data);
    }

    public function salveaza( $id = 0 ) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_email', 'Adresa de email', 'trim|required|valid_email|is_unique[useri.user_email'. ($id!=0 ? '.id.'.$id : '') .']');
        $this->form_validation->set_rules('telefon', 'Telefon', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');
        $this->form_validation->set_rules('prenume', 'Prenume', 'trim|required|xss_clean');
        $this->form_validation->set_rules('id_judet', 'Judet', 'trim|numeric');
        $this->form_validation->set_rules('id_localitate', 'Localitate', 'trim|required|numeric');
        $this->form_validation->set_rules('adresa', 'Adresa', 'trim|xss_clean');
        $this->form_validation->set_rules('tip', 'Tip utilizator', 'trim|required|numeric');
        $this->form_validation->set_rules('nume_firma', 'Nume firma', 'trim|xss_clean');
        $this->form_validation->set_rules('cui', 'CUI', 'trim|xss_clean');
        $this->form_validation->set_rules('nr_reg_comert', 'Nr. reg. comert.', 'trim|xss_clean');
        $this->form_validation->set_rules('autorizatie_igpr', 'Autorizatie IGPR', 'trim|xss_clean');
        $this->form_validation->set_rules('discount_fidelitate', 'Discount fidelitate', 'trim|required|numeric');
        $this->form_validation->set_rules('reseller', 'Reseller', 'trim|required|numeric');
        $this->form_validation->set_rules('admin', 'Admin', 'trim|required|numeric');
        $this->form_validation->set_rules('valid', 'Valid', 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            if( $id == 0 ) {
                $pass = substr(md5(time()), 5);
                
                $this->db->set('user_email', $p['user_email']);
                $this->db->set('user_pass', md5($pass));
                $this->db->set('tip', $p['tip']);
                $this->db->set('nume', $p['nume']);
                $this->db->set('prenume', $p['prenume']);
                $this->db->set('telefon', $p['telefon']);
                $this->db->set('id_localitate', $p['id_localitate']);
                $this->db->set('adresa', $p['adresa']);
                $this->db->set('nume_firma', $p['nume_firma']);
                $this->db->set('cui', $p['cui']);
                $this->db->set('nr_reg_comert', $p['nr_reg_comert']);
                $this->db->set('autorizatie_igpr', $p['autorizatie_igpr']);
                $this->db->set('discount_fidelitate', $p['discount_fidelitate']);
                $this->db->set('data_adaugare', 'NOW()', FALSE);
                $p['reseller']==1 ? $this->db->set('reseller_cerere', 0) : "";
                $this->db->set('reseller', $p['reseller']);
                $this->db->set('admin', $p['admin']);
                $this->db->set('valid', $p['valid']);

                if( $this->db->insert( 'useri' ) ) {
                    $this->load->library('email');

                    $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                    $this->email->to( $p['user_email'] );

                    $this->email->subject('Cont nou pe '. setare('TITLU_NUME_SITE'));

                    $template['titlu'] = "Bine ati venit pe ". setare('TITLU_NUME_SITE') ."!";
                    $template['continut'] = '<p>
Contul tau a fost creat cu succes. Pentru a va loga in cont folositi:
<br />
<br />
<strong>User: '. $p['user_email'] .'</strong><br />
<strong>Parola: '. $pass .'</strong><br />
<br /><br />
Multumim.<br />
</p>';
                    $message = $this->load->view("template_email", $template, true);
                    $this->email->message( $message );
                    $this->email->send();
                    
                    $data['succes'] = "Userul a fost salvat cu succes si a fost trimis un email catre: {$p['user_email']} cu datele de conectare.";
                    
                    $this->form_validation->_field_data = array();
                    $_POST = array();
                }
            } else {
                $this->db->set('user_email', $p['user_email']);
                $this->db->set('tip', $p['tip']);
                $this->db->set('nume', $p['nume']);
                $this->db->set('prenume', $p['prenume']);
                $this->db->set('telefon', $p['telefon']);
                $this->db->set('id_localitate', $p['id_localitate']);
                $this->db->set('adresa', $p['adresa']);
                $this->db->set('nume_firma', $p['nume_firma']);
                $this->db->set('cui', $p['cui']);
                $this->db->set('nr_reg_comert', $p['nr_reg_comert']);
                $this->db->set('discount_fidelitate', $p['discount_fidelitate']);
                $this->db->set('autorizatie_igpr', $p['autorizatie_igpr']);
                $p['reseller']==1 ? $this->db->set('reseller_cerere', 0) : "";
                $this->db->set('reseller', $p['reseller']);
                $this->db->set('valid', $p['valid']);
                $this->db->set('admin', $p['admin']);
                $this->db->where('id', $id );

                if( $this->db->update( 'useri' ) ) {
                    $data['succes'] = "Userul a fost salvat cu succes.";
                }
            }
        }

        if( $id==0 ) {
            $this->adauga($data);
        } else {
            $this->editeaza( $id, $data );
        }
    }
    
    public function salveaza_parola( $id_user ) {
        $this->load->library('form_validation');
        $data = array();

        $this->form_validation->set_rules('user_pass', 'Schimba Parola', 'trim|required|min_length[4]|matches[user_pass_conf]');
        $this->form_validation->set_rules('user_pass_conf', 'Confirma parola', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $p = $this->input->post();
            
            if( $p['user_pass'] ) {
                $this->db->set( "user_pass", md5( $p['user_pass'] ) );
                $this->db->where( "id", $id_user);
                if( $this->db->update( "useri" ) ) {
                    if( isset( $p['trimite_email'] ) ) {
                        $user = $this->indexmodel->get_user( $id_user );

                        $this->load->library('email');

                        $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                        $this->email->to( $user['user_email'] );

                        $this->email->subject('Schimbare parola '. setare('TITLU_NUME_SITE'));

                        $template['titlu'] = "Schimbare parola". setare('TITLU_NUME_SITE');
                        $template['continut'] = '<p>
Parola dvs a fost schimbata cu succes. Noile date de logare sunt: <br /><br />
<strong>User: '. $user['user_email'] .'</strong><br />
<strong>Parola: '. $p['user_pass'] .'</strong><br />
</p>
<br />
<p>
O zi buna.<br />
</p>
';
                        $message = $this->load->view("template_email", $template, true);

                        $this->email->message( $message );
                        $this->email->send();
                    }
                    
                    $data['succes'] = "Datele au fost salvate cu succes.";

                }
            }
        }

        $this->editeaza( $id_user, $data );
    }

    public function sterge( $id ) {
        $this->load->model('istoric_comenzi_model', 'istoric_comenzi_m');
        
        $data = array();
        $user = $this->userim->get_user( $id );
        if( $user ) {
            $comenzi = $this->userim->get_comenzi_user( $id );
            foreach ($comenzi as $id_comanda) {
                $comanda = $this->istoric_comenzi_m->get_comanda( $id_comanda['id'] );
                if( !empty( $comanda ) ) {
                    $this->db->delete('comenzi', array('id' => $id_comanda['id']));
                    $this->db->delete('comenzi_produse', array('id_comanda' => $id_comanda['id']));
                    @unlink( dirname(__FILE__) . "/../facturi/factura{$comanda['nr_factura']}.pdf" );
                }
            }
            
            $this->db->delete( 'newsletters_queue', array('id_user'=>$id) );
            $this->db->delete( 'useri', array('id'=>$id) );

            $data["succes"] = "Userul a fost sters cu succes.";
        }
        $this->index( $data );
    }
    
        
    public function export_xls() {
        $this->load->library('PHPExcel');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("S.C. Concept Invest Online S.R.L.")
                ->setLastModifiedBy("S.C. Concept Invest Online S.R.L.")
                ->setTitle("Export clienti www.lenjeriidepatdeosebite.ro - " . date("d-m-Y"))
                ->setSubject("Export clienti www.lenjeriidepatdeosebite.ro - " . date("d-m-Y"))
                ->setDescription("Acest document contine clientii din baza de date a www.lenjeriidepatdeosebite.ro la data de: " . date("d-m-Y H:i"))
                ->setKeywords("clienti, lenjerii deosebite")
                ->setCategory("Clienti");

        //setare header
        //id | email | nume | prenume | telefon | localitate | judet | adresa | alta adresa de livrare | livrare localitate | livrare judet | livrare adresa | 
        //tip cont | nume_firma | cui | nr_reg_com | data_adaugare | ultima logare | abonat newsletter | valid
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'Email')
                ->setCellValue('C1', 'Nume')
                ->setCellValue('D1', 'Prenume')
                ->setCellValue('E1', 'Telefon')
                ->setCellValue('F1', 'Localitate')
                ->setCellValue('G1', 'Judet')
                ->setCellValue('H1', 'Adresa')
                ->setCellValue('I1', 'Alta adresa livrare?') // DA / NU
                ->setCellValue('J1', 'Localitate livrare')
                ->setCellValue('K1', 'Judet livrare')
                ->setCellValue('L1', 'Adresa livrare')
                ->setCellValue('M1', 'Tip cont') // PF / Firma
                ->setCellValue('N1', 'Nume firma')
                ->setCellValue('O1', 'CUI')
                ->setCellValue('P1', 'Nr. reg. com.')
                ->setCellValue('Q1', 'Data adaugare')
                ->setCellValue('R1', 'Ultima logare')
                ->setCellValue('S1', 'Abonat newsletter') //DA / NU
                ->setCellValue('T1', 'Valid')
                ->setCellValue('U1', 'Nr. comenzi');
        
        $p = $this->input->post();
        $q = isset( $p['q'] ) && isset( $p['export_doar_filtrati'] ) ? $p['q'] : "";
        $dupa = isset( $p['dupa'] ) && isset( $p['export_doar_filtrati'] ) ? $p['dupa'] : "";
        
        $useri = $this->userim->get_useri_export( $q, $dupa );

        $k = 2;
        foreach( $useri as $user ) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$k}", $user['id'])
                ->setCellValue("B{$k}", $user['user_email'])
                ->setCellValue("C{$k}", $user['nume'])
                ->setCellValue("D{$k}", $user['prenume'])
                ->setCellValueExplicit("E{$k}", $user['telefon'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("F{$k}", $user['nume_localitate'])
                ->setCellValue("G{$k}", $user['nume_judet'])
                ->setCellValue("H{$k}", $user['adresa'])
                ->setCellValue("I{$k}", ($user['livrare_adresa_1']==1 ? "DA" : "NU") )
                ->setCellValue("J{$k}", $user['nume_localitate_livrare'])
                ->setCellValue("K{$k}", $user['nume_judet_livrare'])
                ->setCellValue("L{$k}", $user['livrare_adresa'])
                ->setCellValue("M{$k}", ($user['tip']=="2" ? "Firma" : "PF") )
                ->setCellValue("N{$k}", $user['nume_firma'])
                ->setCellValue("O{$k}", $user['cui'])
                ->setCellValue("P{$k}", $user['nr_reg_comert'])
                ->setCellValue("Q{$k}", $user['data_adaugare'])
                ->setCellValue("R{$k}", $user['user_last_login'])
                ->setCellValue("S{$k}", ($user['newsletter']==1 ? "DA" : "NU") )
                ->setCellValue("T{$k}", ($user['valid']==1 ? "DA" : "NU") )
                ->setCellValue("U{$k}", $user['nr_comenzi']);
                
            if( $k%2!=0 ) {
                $objPHPExcel->getActiveSheet()->getStyle("A{$k}:U{$k}")->getFill()
                        ->applyFromArray(
                                array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'startcolor' => array('rgb' => "DDE3ED" )
                                    )
                                );
            }
            
//            if( $k==10 ) break;
                
            $k++;
        }
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        
        //stil prima linie
        $objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFill()
                ->applyFromArray(
                        array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'startcolor' => array('rgb' => "0C306B" )
                            )
                        );
        
        $objPHPExcel->getActiveSheet()->getStyle("A1:U1")->applyFromArray(
                    array(
                        "font" => array(
                            "bold" => true,
                            "color" => array('rgb' => 'FFFFFF')
                        )
                    )
                );

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export-clienti-lenjeriidepatdeosebite-'. date("d-m-Y") .'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


}

/* End of file useri.php */
/* Location: ./application/controllers/admin/useri.php */