<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class filtre_model extends CI_Model {

    public function get_filtre( $id_parinte = NULL ) {
        $this->db->from("filtre");
        $id_parinte!==NULL ? $this->db->where( "id_parinte", $id_parinte ) : "";
        $this->db->order_by("ordonare, id_parinte");

        return $this->db->get()->result_array();
    }

    public function get_filtru( $id ) {
        $this->db->from("filtre");
        $this->db->where("id", $id);

        return $this->db->get()->result_array();
    }

    public function get_max_ordonare() {
        $this->db->select_max("ordonare", "max_ordonare");
        $this->db->from("filtre");

        return $this->db->get()->row()->max_ordonare;
    }

}

/* End of file filtre_model.php */
/* Location: ./application/models/admin/filtre_model.php */
