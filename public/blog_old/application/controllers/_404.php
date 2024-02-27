<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _404 extends CI_Controller {

    public function index() {
        $data['page_view'] = '_404';
        $data['title'] = 'Eroare 404. Pagina solicitata nu a fost gasita.';
        
        $this->load->library('display', $data);
    }

    
}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */