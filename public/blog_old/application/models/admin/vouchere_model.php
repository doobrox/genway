<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class vouchere_model extends CI_Model {

    public function get_vouchere() {
        $this->db->from("vouchere");
        $this->db->order_by("id", "DESC");

        return $this->db->get()->result_array();
    }

    public function get_voucher( $id ) {
        $this->db->from("vouchere");
        $this->db->where("id", $id);

        return $this->db->get()->result_array();
    }
}

/* End of file vouchere_model.php */
/* Location: ./application/models/admin/vouchere_model.php */
