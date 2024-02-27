<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class bannere_model extends CI_Model {

    public function get_bannere() {
        $this->db->from("bannere");
        $this->db->order_by("id", "DESC");

        return $this->db->get()->result_array();
    }

    public function get_banner( $id ) {
        $this->db->from("bannere");
        $this->db->where("id", $id);

        return $this->db->get()->result_array();
    }
}

/* End of file bannere_model.php */
/* Location: ./application/models/admin/bannere_model.php */
