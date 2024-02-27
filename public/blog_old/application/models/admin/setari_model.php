<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class Setari_model extends CI_Model {

    public function get_setari() {
        $this->db->from("setari");

        return $this->db->get()->result_array();
    }

    public function get_setare( $id ) {
        $this->db->from("setari");
        $this->db->where("id", $id);

        return $this->db->get()->result_array();
    }

}

/* End of file setari_model.php */
/* Location: ./application/models/admin/setari_model.php */
