<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class judete_model extends CI_Model {

    public function get_judete() {
        $this->db->select('a.*', FALSE)
            ->from('geo_judete a')
            ->order_by('a.nume');

        return $this->db->get()->result_array();
    }

    public function get_judet( $id ) {
        $this->db->select('a.*')
            ->from('geo_judete a')
            ->where('a.id', $id);

        return $this->db->get()->result_array();
    }

}

/* End of file index_page_model.php */
/* Location: ./application/models/admin/index_page_model.php */
