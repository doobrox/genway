<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_user( $id ) {
        $this->db->select("a.*, b.id_judet, b.nume nume_localitate, c.nume nume_judet, (SELECT id_judet FROM geo_localitati WHERE id = livrare_id_localitate) livrare_id_judet", FALSE)
                ->from("useri a")
                ->join("geo_localitati b", "a.id_localitate = b.id", "INNER")
                ->join("geo_judete c", "b.id_judet = c.id", "INNER")
                ->where("a.id", $id);

        return $this->db->get()->row_array();
    }

    public function get_user_livrare( $id ) {
        $this->db->select("a.*, b.id_judet, b.nume nume_localitate, c.nume nume_judet", FALSE)
                ->from("useri a")
                ->join("geo_localitati b", "b.id = a.livrare_id_localitate", "INNER")
                ->join("geo_judete c", "b.id_judet = c.id", "INNER")
                ->where("a.id", $id);

        return $this->db->get()->row_array();
    }
}

/* End of file index_page_model.php */
/* Location: ./application/models/index_page_model.php */
