<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Profilul_meu extends CI_Controller {

    private $id_user;

    

    public function __construct() {

        parent::__construct();

        //$this->output->enable_profiler(true);

       

        $this->load->helper('setari');

        $this->load->model('index_page_model', 'indexm');

        $this->load->model('user_model', 'userm');

        

        if (!$this->simpleloginsecure->is_logat()) {

            redirect( base_url()."login?link=" . urlencode(site_url("profilul_meu") ) );

        }

        

        $this->id_user = $this->session->userdata("id");

    }



    public function index( $data = array() ) {

        $data["title"] = "Profilul meu | " . setare('TITLU_NUME_SITE');

        

        $data['item'] = $this->userm->get_user( $this->id_user );

        

        $data['options_tip'] = array(

            1 => "Persoana Fizica",

            2 => "Firma - persoana juridica",

        );

        

        $items = $this->indexm->get_judete();

        $data['options_judete'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "Selecteaza judet" ); 

        

        if( isset( $_POST['id_judet'] ) ) {

            $items = $this->indexm->get_localitati_by_id_judet( $_POST['id_judet'] );

            $data['options_localitati'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 

        } elseif( isset( $data['item']['id_judet'] ) ) {

            $items = $this->indexm->get_localitati_by_id_judet( $data['item']['id_judet'] );

            $data['options_localitati'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 

        } else {

            $data['options_localitati'] = array("" => "Selecteaza localitate");

        }

        

        if( isset( $_POST['livrare_id_judet'] ) ) {

            $items = $this->indexm->get_localitati_by_id_judet( $_POST['livrare_id_judet'] );

            $data['options_localitati_livrare'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 

        } elseif( isset( $data['item']['livrare_id_judet'] ) ) {

            $items = $this->indexm->get_localitati_by_id_judet( $data['item']['livrare_id_judet']  );

            $data['options_localitati_livrare'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 

        } else {

            $data['options_localitati_livrare'] = array("" => "Selecteaza localitate");

        }

        

        $data['link'] = $this->input->get('link');



        $data['breadcrumbs'] = array(

            array(

                "link" => base_url(),

                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',

                "class" => "acasa"

            ),

            array(

                "link" => site_url("profilul_meu"),

                "titlu" => "Profilul meu",

            ),

        );

        

        $data["page_view"] = "profilul_meu";



        $this->load->library('display', $data);

    }



    public function salveaza() {

        $this->load->library('form_validation');

        

        $data = array();

        $p = $this->input->post();



        $this->form_validation->set_rules('tip', 'Tip persoana', 'trim|required');

        $this->form_validation->set_rules('nume_firma', 'Firma', 'trim' . ($p['tip']==2 ? '|required' : ''));

        $this->form_validation->set_rules('cui', 'CUI', 'trim' . ($p['tip']==2 ? '|required' : ''));

        $this->form_validation->set_rules('nr_reg_comert', 'Nr.reg.com.', 'trim' . ($p['tip']==2 ? '|required' : ''));

        $this->form_validation->set_rules('autorizatie_igpr', 'Autorizatie IGPR', 'trim|xss_clean');

        $this->form_validation->set_rules('nume', 'Nume', 'trim|required');

        $this->form_validation->set_rules('prenume', 'Prenume', 'trim|required');

        $this->form_validation->set_rules('cnp', 'CNP', 'trim|numeric|required');

        $this->form_validation->set_rules('telefon', 'Telefon', 'trim|numeric|required');

        $this->form_validation->set_rules('id_judet', 'Judet', 'trim|required|numeric');

        $this->form_validation->set_rules('id_localitate', 'Localitate', 'trim|required|numeric');

        $this->form_validation->set_rules('adresa', 'Adresa', 'trim|required|xss_clean');

        $this->form_validation->set_rules('newsletter', 'Newsletter', 'trim');

        $this->form_validation->set_rules('livrare_adresa_1', 'Livrare adresa 2', 'trim');

        $this->form_validation->set_rules('livrare_id_localitate', 'Localitate livrare', 'trim|numeric' . (!isset( $p['livrare_adresa_1'] ) ? '|required' : ''));

        $this->form_validation->set_rules('livrare_adresa', 'Adresa de livrare', 'trim|xss_clean' . (!isset( $p['livrare_adresa_1'] ) ? '|required' : ''));



        if ($this->form_validation->run() == TRUE) {

            $p['newsletter'] = isset( $p['newsletter'] ) ? 1 : 0;

            $p['livrare_adresa_1'] = isset( $p['livrare_adresa_1'] ) ? 1 : 0;



            $cod_validare = substr( uniqid(rand()), 0, 8 );

            $dataDB = array(

                'tip' => $p['tip'],

                'nume' => $p['nume'],

                'prenume' => $p['prenume'],

                'cnp' => $p['cnp'],

                'telefon' => $p['telefon'],

                'id_localitate' => $p['id_localitate'],

                'adresa' => $p['adresa'],

                'livrare_adresa_1' => $p['livrare_adresa_1'],

                'livrare_id_localitate' => $p['livrare_id_localitate'],

                'livrare_adresa' => $p['livrare_adresa'],

                'nume_firma' => $p['nume_firma'],

                'cui' => $p['cui'],

                'nr_reg_comert' => $p['nr_reg_comert'],

                'autorizatie_igpr' => $p['autorizatie_igpr'],

                'newsletter' => $p['newsletter'],

                'cod_validare' => $cod_validare,

            );



            $this->db->set($dataDB);

            $this->db->where("id", $this->id_user);

            if( $this->db->update( 'useri' ) ) {

                $data['succes'] = "Informatiile au fost salvate cu succes.";

            }

        }

        

        if( validation_errors()!="" ) {

            $data['error'] = validation_errors();

        }

        

        $this->index($data);

    }

    

    public function schimba_parola( $data = array() ) {

        $data["title"] = "Schimba parola | " . setare('TITLU_NUME_SITE');

        $data['link'] = $this->input->get('link');



        $data['breadcrumbs'] = array(

            array(

                "link" => base_url(),

                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',

                "class" => "acasa"

            ),

            array(

                "link" => site_url("profilul_meu/schimba_parola"),

                "titlu" => "Schimba parola",

            ),

        );

        

        $data["page_view"] = "schimba_parola";



        $this->load->library('display', $data);

    }

    

    public function salveaza_parola() {

        $this->load->library('form_validation');

        

        $data = array();

        $data['salveaza_parola'] = true;

        

        $this->form_validation->set_rules('user_pass_old', 'Parola veche', 'trim|required|callback_check_old_pass');

        $this->form_validation->set_rules('user_pass', 'Parola noua', 'trim|required|min_length[4]|matches[user_pass_conf]');

        $this->form_validation->set_rules('user_pass_conf', 'Confirma parola noua', 'trim|required');



        if ($this->form_validation->run() == TRUE) {

            $p = $this->input->post();



            if ($p['user_pass']) {

                $data_update = array(

                    "user_pass" => md5($p['user_pass']),

                );

                $this->db->set($data_update);

                $this->db->where("id", $this->id_user);

                if ($this->db->update("useri")) {



                    $user = $this->userm->get_user($this->id_user);

                    $this->load->library('email');
                    $this->load->helper('email_template');


                    $this->email->from(setare('EMAIL_CONTACT'), setare('TITLU_NUME_SITE'));

                    $this->email->to($user['user_email']);



                    $this->email->subject('Schimbare parola ' . setare('TITLU_NUME_SITE'));


                    $info = array(
                        "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                        "__USER_EMAIL__" => $user['user_email'],
                        "__USER_PAROLA__" => $p['user_pass'],
                    );

                    $template['titlu'] = email_template(12, "subiect", $info);
                    $template['continut'] = email_template(12, "continut", $info);

                    $message = $this->load->view("template_email", $template, true);

                    $this->email->message($message);

                    $this->email->send();



                    $data['succesSalvareParola'] = "Noua parola a fost salvata cu succes.";

                }

            }

        }

        

        if( validation_errors()!="" ) {

            $data['errorSalvareParola'] = validation_errors();

        }



        $this->schimba_parola($data);

    }

    

    function check_old_pass($user_pass_old) {

        if (!$this->simpleloginsecure->check_user_pass($user_pass_old, "useri")) {

            $this->form_validation->set_message('check_old_pass', '%s nu este corecta.');

            return false;

        }



        return true;

    }

}



/* End of file login.php */

/* Location: ./application/controllers/login.php */