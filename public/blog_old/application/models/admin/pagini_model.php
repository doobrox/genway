<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class Pagini_model extends CI_Model {

    public function get_pagini() {
        $this->db->from("pagini");
        $this->db->order_by("ordonare ASC, posibilitate_stergere DESC");

        return $this->db->get()->result_array();
    }

    public function get_pagina( $id ) {
        $this->db->from("pagini");
        $this->db->where("id", $id);

        return $this->db->get()->result_array();
    }

    public function get_max_ordonare() {
        $this->db->select_max("ordonare", "max_ordonare");
        $this->db->from("pagini");

        return $this->db->get()->row()->max_ordonare;
    }

}

/* End of file pagini_model.php */
/* Location: ./application/models/admin/pagini_model.php */
