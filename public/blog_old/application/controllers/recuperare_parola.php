<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');





class recuperare_parola extends CI_Controller {


    public function __construct() {


        parent::__construct();


        //$this->output->enable_profiler(true);


       


        $this->load->helper('setari');


        $this->load->model('index_page_model', 'indexm');


        $this->load->model('user_model', 'userm');


    }





    public function index( $data = array() ) {


        $data["title"] = "Recuperare parola | " . setare('TITLU_NUME_SITE');


        $data["page_view"] = "recuperare_parola";





        $this->load->library('display', $data);


    }


    


    public function salveaza() {


        $this->load->library('form_validation');





        $data = array();


        $data['title'] = "Recuperare parola ... | " . setare('TITLU_NUME_SITE');


        $data['header_simplu'] = true;


        


        $p = $this->input->post();


        if( $this->simpleloginsecure->check_email_db( $p['email_recuperare'] ) ) {


            $new_pass = substr(md5(microtime()), 0, 5);


            $cod_validare = substr( uniqid(rand()), 0, 8 );





            $this->db->set("reset_pass_new", md5( $new_pass ) );


            $this->db->set("reset_pass_validare", $cod_validare );


            $this->db->where("user_email", $p['email_recuperare']);





            if( $this->db->update( "useri" ) ) {


                $this->load->library('email');
                $this->load->helper('email_template');




                $this->email->from( setare('EMAIL_CONTACT'), setare('TITLU_NUME_SITE'));


                $this->email->to( $p['email_recuperare'] );






                
                //preluare template email din baza de date
                $info = array(
                    "__NUME_SITE__" => setare('TITLU_NUME_SITE_SCURT'),
                    "__LINK_SITE__" => base_url(),
                    "__LINK_VALIDARE__" => base_url() ."login/validare_resetare_parola/{$cod_validare}",
                    "__USER_PAROLA__" => $new_pass,
                );

                $template['titlu'] = email_template(13, "subiect", $info);
                $template['continut'] = email_template(13, "continut", $info);
                

                $message = $this->load->view("template_email", $template, true);


                $this->email->subject( $template['titlu'] );
                $this->email->message( $message );

                $this->email->send();

                $data['succesRecuperarePass'] = "Un email a fost trimis catre adresa specificata.";
            }


        }  else {
            $data['errRecuperarePass'] = "Adresa de email: {$p['email_recuperare']} nu a fost gasita in baza de date.";
        }

        $this->index( $data);

    }





}





/* End of file login.php */


/* Location: ./application/controllers/login.php */