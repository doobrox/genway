<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display {

    public function display( $data ) {
        $CI =& get_instance();
        $CI->load->helper('text');
        $CI->load->library('functions');

        if( $CI->simpleloginsecure->is_logat() ) {
            $data['isLogat'] = true;
            $data['numeCompletUser'] = $CI->session->userdata('nume_complet');
        }
        
        $data['selectableYears'] = "";
        for( $i=2013; $i<=date("Y"); $i++ ) {
            $data['selectableYears'] .= "{$i}, ";
        }
        $data['selectableYears'] = rtrim($data['selectableYears'], ", ");
        
        $CI->load->view('admin/header', $data);
        $CI->load->view('admin/'.$data['page_view'], $data);
        $CI->load->view('admin/footer', $data);
    }
}

/* End of file display.php */
/* Location: ./application/libraries/display.php */