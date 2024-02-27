<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index() {
        $this->load->view('admin/login');
    }

    public function verificare() {
        $data = array();
        $p = $this->input->post();
        if( $this->simpleloginsecure->login( $p['user_email'], $p['user_pass'], 1 ) ) {
            redirect('admin/index_page');
        } else {
            $data["errLogin"] = "Adresa de email sau parola gresita.";
        }
        $this->load->view('admin/login', $data);
    }

    function iesire() {
        $this->simpleloginsecure->logout();
        redirect('admin/login/');
    }


}

/* End of file login.php */
/* Location: ./application/controllers/admin/login.php */