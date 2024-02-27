<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('setari');
        $this->load->model('index_page_model', 'indexm');
        $this->load->helper('email_template');
        $this->load->helper('captcha');
        $this->config->load('captcha');
    }

    public function index( $errorCode = "", $data = array() ) {
        $data["title"] = "Autentificare | " . setare('TITLU_NUME_SITE');
        
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        switch( $errorCode ) {
            case "error":
                $data["errorLogin"] = "Pentru a accesa aceasta pagina trebuie sa fii logat.";
                break;
        }
        
        $data['link'] = $this->input->get('link');
        
        $data['breadcrumbs'] = array(
            array(
                "link" => base_url(),
                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',
                "class" => "acasa"
            ),
            array(
                "link" => site_url("login"),
                "titlu" => "Autentificare",
            ),
        );
        
        $data["page_view"] = "login";

        $this->load->library('display', $data);
    }
    
    
    public function inregistrare( $data = array() ) {
        $data["title"] = "Creaza cont nou | " . setare('TITLU_NUME_SITE');
        
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $data['options_tip'] = array(
            1 => "Persoana Fizica",
            2 => "Firma - persoana juridica",
        );
        
        $items = $this->indexm->get_judete();
        $data['options_judete'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', "Selecteaza judet" ); 
        
        if( isset( $_POST['id_judet'] ) ) {
            $items = $this->indexm->get_localitati_by_id_judet( $_POST['id_judet'] );
            $data['options_localitati'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 
        } else {
            $data['options_localitati'] = array("" => "Selecteaza localitate");
        }
        
        if( isset( $_POST['livrare_id_judet'] ) && $_POST['livrare_id_judet']!=0 ) {
            $items = $this->indexm->get_localitati_by_id_judet( $_POST['livrare_id_judet'] );
            $data['options_localitati_livrare'] = $this->functions->build_form_dropdown( $items, 'id', 'nume', FALSE ); 
        } else {
            $data['options_localitati_livrare'] = array("" => "Selecteaza localitate");
        }

        $captcha = create_captcha( $this->config->item('config') );
        captcha_insert_db($captcha);
        $data['captcha'] = $captcha['image'];
        
        $data['breadcrumbs'] = array(
            array(
                "link" => base_url(),
                "titlu" => '<img src="'. base_url() . MAINSITE_STYLE_PATH .'images/home.png">',
                "class" => "acasa"
            ),
            array(
                "link" => site_url("login/inregistrare"),
                "titlu" => "Cont nou",
            ),
        );
        
        $data["page_view"] = "cont_nou";

        $this->load->library('display', $data);
    }


    public function verificare() {
        $data = array();

        $data['link'] = $this->input->get('link');
        $data['header_simplu'] = true;

        if( $this->simpleloginsecure->login( $this->input->post('user_email_login'), $this->input->post('user_pass_login') ) ) {
            $link = urldecode( $data['link'] );
            
            if( $link=="" ) {
                redirect('istoric_comenzi'); //TODO: redirect catre ce ??? 
            }
            redirect( $link );
        } else {
            $data["errorLogin"] = "Adresa de email sau parola gresita.";
        }

        $this->index('', $data);
    }

    function validare( $cod_validare = "" ) {
        $data = array(
            "title" => "Validare cont | " . setare('TITLU_NUME_SITE'),
            "header_simplu" => true,
        );
        if( $this->simpleloginsecure->validare($cod_validare) ) {
            $data['succesLogin'] = "Contul a fost  validat cu succes. ";
        } else {
            $data['errorLogin'] = "Acest cont nu a fost gasit in baza de date.";
        }
        
        $this->index("", $data);
    }

    function iesire() {
        $this->simpleloginsecure->logout();
        redirect();
    }
    
    public function validare_resetare_parola($cod_validare = "") {
        $data = array();
        $data['title'] = "Confirmare resetare parola ... | " . setare('TITLU_NUME_SITE');
        $data['header_simplu'] = true;
        
        $this->db->set("user_pass", "reset_pass_new", FALSE);
        $this->db->where("reset_pass_validare", $cod_validare);
        if( $this->db->update( "useri" ) ) {
            
            $this->db->set("reset_pass_new", "");
            $this->db->set("reset_pass_validare", "");
            $this->db->where("reset_pass_validare", $cod_validare);
            $this->db->update( "useri" );
            
            $data['succesLogin'] = "Noua parola a fost confirmata cu succes.";
        }
        
        $data["page_view"] = "login";
        $this->index( '', $data);
    }
    
    public function cont_nou() {
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
        $this->form_validation->set_rules('cnp', 'CNP', 'trim|required|numeric');
        $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|callback_check_email_db');
        $this->form_validation->set_rules('telefon', 'Telefon', 'trim|numeric|required');
        $this->form_validation->set_rules('id_judet', 'Judet', 'trim|required|numeric');
        $this->form_validation->set_rules('id_localitate', 'Localitate', 'trim|required|numeric');
        $this->form_validation->set_rules('adresa', 'Adresa', 'trim|required|xss_clean');
        $this->form_validation->set_rules('termeni', 'Termeni si conditii', 'trim|required');
        $this->form_validation->set_rules('newsletter', 'Newsletter', 'trim');
        $this->form_validation->set_rules('livrare_adresa_1', 'Livrare adresa 2', 'trim');
        $this->form_validation->set_rules('livrare_id_judet', 'Judet livrare', 'trim|numeric' . (isset( $p['livrare_adresa_1'] ) ? '|required' : ''));
        $this->form_validation->set_rules('livrare_id_localitate', 'Localitate livrare', 'trim|numeric' . (isset( $p['livrare_adresa_1'] ) ? '|required' : ''));
        $this->form_validation->set_rules('livrare_adresa', 'Adresa de livrare', 'trim|xss_clean' . (isset( $p['livrare_adresa_1'] ) ? '|required' : ''));
        $this->form_validation->set_rules('captcha', 'Cod de securitate', 'callback_check_captcha');
        $this->form_validation->set_rules('user_pass', 'Parola', 'trim|min_lenght[4]|required|matches[user_pass_check]');
        $this->form_validation->set_rules('user_pass_check', 'Repeta Parola', 'trim|min_lenght[4]|required');

        if ($this->form_validation->run() == TRUE) {
            $p['newsletter'] = isset( $p['newsletter'] ) && $p['newsletter']==1 ? 1 : 0;
            $p['livrare_adresa_1'] = isset( $p['livrare_adresa_1'] ) ? 1 : 0;
            $p['reseller_cerere'] = isset( $p['reseller_cerere'] ) ? 1 : 0;

            $cod_validare = substr( uniqid(rand()), 0, 8 );
            $dataDB = array(
                'user_email' => $p['user_email'],
                'user_pass' => md5( $p['user_pass'] ),
                'tip' => $p['tip'],
                'nume' => $p['nume'],
                'prenume' => $p['prenume'],
                'cnp' => $p['cnp'],
                'telefon' => $p['telefon'],
                'id_localitate' => $p['id_localitate'],
                'adresa' => $p['adresa'],
                'livrare_adresa_1' => $p['livrare_adresa_1'],
                'livrare_id_localitate' => (isset( $p['livrare_id_localitate'] ) ? $p['livrare_id_localitate'] : ""),
                'livrare_adresa' => (isset( $p['livrare_adresa'] ) ? $p['livrare_adresa'] : ""),
                'nume_firma' => $p['nume_firma'],
                'cui' => $p['cui'],
                'nr_reg_comert' => $p['nr_reg_comert'],
                'autorizatie_igpr' => $p['autorizatie_igpr'],
				'reseller_cerere' => $p['reseller_cerere'],
                'newsletter' => $p['newsletter'],
                'cod_validare' => $cod_validare,
                'data_adaugare' => date('Y-m-d H:i:s'),
            );

            $this->db->set($dataDB);
            if( $this->db->insert( 'useri' ) ) {
                //preluare template email din baza de date
                $info = array(
                    "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                    "__LINK_LOGIN__" => base_url() . "login",
                    "__USER_EMAIL__" => $p['user_email'],
                    "__USER_PAROLA__" => $p['user_pass'],
                );

                $subiect = email_template(1, "subiect", $info);
                $continut = email_template(1, "continut", $info);
                
                $this->load->library('email');

                $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                $this->email->to( $p['user_email'] );

                $this->email->subject( $subiect );

                $template['titlu'] = $subiect;
                $template['continut'] = $continut;
                $message = $this->load->view("template_email", $template, true);

                $this->email->message( $message );
                
                if( $this->email->send() ) {
                    /* trimitere notificare catre admin */
                    $this->email->clear();
                    
                    //preluare template email din baza de date
                    $info = array(
                        "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                        "__NUME__" => $p['nume'],
                        "__PRENUME__" => $p['prenume'],
                        "__USER_EMAIL__" => $p['user_email'],
                        "__LINK_USERI_ADMIN__" => site_url("admin/useri"),
                    );

					$id_template = $p['reseller_cerere']==1 ? 14 : 2;
                    $template['titlu'] = email_template($id_template, "subiect", $info);
                    $template['continut'] = email_template($id_template, "continut", $info);

                    $message = $this->load->view("template_email", $template, true);

                    $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                    $this->email->to( setare('EMAIL_CONTACT') );
                    $this->email->subject( $template['titlu'] );
                    $this->email->message( $message );
                    $this->email->send();
                        
                    clear_captcha($p['captcha']);
                    $this->simpleloginsecure->login( $p['user_email'], $p['user_pass'], 0, TRUE );
                    $this->session->set_flashdata('succes', "Contul a fost creat cu succes. Un email cu datele de logare a fost trimis.");

                    $link = $this->input->get("link");
                    if( $link!="" ) {
                        redirect( urldecode( $link ) );
                    }
                }
                
                redirect( "login/inregistrare" );
            }
        }
        
        $this->inregistrare($data);
    }
    
    function retrimite_mail_activare() {
        
        $data = array();
        if( $this->simpleloginsecure->is_logat() ) {
            $this->load->model("user_model", "userm");
            
            $user = $this->userm->get_user( $this->session->userdata("id") );
            if( !empty( $user ) && !$user['valid'] ) {
                $this->load->library('email');

                $this->email->from( setare('EMAIL_CONTACT') , setare('TITLU_NUME_SITE'));
                $this->email->to( $user['user_email'] );

                $this->email->subject('Cont nou '. setare('TITLU_NUME_SITE'));

                $template['titlu'] = "Bine ati venit pe ". setare('TITLU_NUME_SITE') ."!";
                $template['continut'] = '<p>
        Contul tau a fost creat cu succes. Pentru a-l valida acceseaza linkul de mai jos: <br />
        <a href="'. base_url() .'login/validare/'. $user['cod_validare'] .'">'. base_url() .'login/validare/'.$user['cod_validare'].'</a>
        </p>
        <p>
        Pentru a va loga in cont folositi:<br />
        <strong>User: '. $user['user_email'] .'</strong><br />
        <strong>Parola: '. $user['user_pass_clean'] .'</strong><br />
        </p>
        <p>
        Multumim.<br />
        </p>';
                $message = $this->load->view("template_email", $template, true);

                $this->email->message( $message );
                $this->email->send();

                $data['succesLogin'] = "Mail-ul de activare cont a fost retrimis cu succes. ";    
            }
        }
        
        $this->index('', $data);
    }

    function check_email_db( $user_email ) {
        if( $this->simpleloginsecure->check_email_db( $user_email ) ) {
            $this->form_validation->set_message('check_email_db', 'Adresa %s exista deja in baza de date.');
            return false;
        }

        return true;
    }
    
    function check_captcha( $captcha ) {
        if( !check_captcha( $captcha ) ) {
            $this->form_validation->set_message("check_captcha", "Campul %s nu este corect.");
            return false;
        }
        return  true;
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */