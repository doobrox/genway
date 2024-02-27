<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class producatori_model extends CI_Model {

    public function get_producatori() {
        $this->db->select('a.*', FALSE)
            ->from('produse_producatori a')
            ->order_by('a.nume');

        return $this->db->get()->result_array();
    }

    public function get_producator( $id ) {
        $this->db->select('a.*')
            ->from('produse_producatori a')
            ->where('a.id', $id);

        return $this->db->get()->result_array();
    }

}

/* End of file index_page_model.php */
/* Location: ./application/models/admin/index_page_model.php */
