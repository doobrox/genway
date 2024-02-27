<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');



class Email_templates_model extends CI_Model {

    public function get_templates() {
        $this->db->from("email_template");

        return $this->db->get()->result_array();

    }
    
    
    public function get_template( $id ) {
        $this->db->from("email_template");
        $this->db->where("id", $id);

        return $this->db->get()->row_array();
    }
}