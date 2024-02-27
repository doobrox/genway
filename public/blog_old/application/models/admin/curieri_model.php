<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class curieri_model extends CI_Model {

    public function get_curieri() {
        $this->db->select('a.*', FALSE)
            ->from('curieri a')
            ->order_by('a.nume');

        return $this->db->get()->result_array();
    }

    public function get_curier( $id ) {
        $this->db->select('a.*')
            ->from('curieri a')
            ->where('a.id', $id);

        return $this->db->get()->row_array();
    }

}

/* End of file index_page_model.php */
/* Location: ./application/models/admin/index_page_model.php */
