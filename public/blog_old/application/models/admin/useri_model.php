<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class Useri_model extends CI_Model {

    public function get_useri( $order_by, $dir, $q, $dupa, $factura_neplatita = false, $limit = "", $offset = "" ) {
        $this->db->select("a.*, (SELECT COUNT(*) FROM comenzi WHERE id_user = a.id AND stare_plata = '1') nr_comenzi");
        $this->db->from("useri a");
        
        if( $dupa!="" && $q!="" ) {
            $this->db->like( $dupa, $q );
        }
        
        if( $factura_neplatita ) {
            $this->db->where( "(SELECT stare FROM useri_abonamente_facturi WHERE id_user = a.id AND metoda_plata = 3 ORDER BY data_adaugare DESC LIMIT 1) = ", 0, FALSE );
        }
        
        if( $order_by!="" ) {
            $this->db->order_by( $order_by, $dir );
        } else {
            $this->db->order_by("a.data_adaugare", "DESC");
        }
        
        $limit!="" ? $this->db->limit($limit, $offset) : "";
        
        return $this->db->get()->result_array();
    }
    
    public function get_useri_export( $q, $dupa ) {
        $this->db->select("a.*, b.nume nume_localitate, c.nume nume_judet, d.nume nume_localitate_livrare, e.nume nume_judet_livrare, (SELECT COUNT(*) FROM comenzi WHERE id_user = a.id AND stare_plata = '1') nr_comenzi");
        $this->db->from("useri a");
        $this->db->join("geo_localitati b", "a.id_localitate = b.id", "INNER");
        $this->db->join("geo_judete c", "b.id_judet = c.id", "INNER");
        $this->db->join("geo_localitati d", "a.livrare_id_localitate = d.id", "LEFT");
        $this->db->join("geo_judete e", "d.id_judet = e.id", "LEFT");
        
        if( $dupa!="" && $q!="" ) {
            $this->db->like( $dupa, $q );
        }
        
        $this->db->order_by("a.data_adaugare", "DESC");
        
        return $this->db->get()->result_array();
    }

    public function get_user( $id ) {
        $this->db->select("a.*, b.id_judet");
        $this->db->from("useri a");
        $this->db->join("geo_localitati b", "a.id_localitate = b.id", "INNER");
        $this->db->where("a.id", $id);

        return $this->db->get()->result_array();
    }
    
    public function get_comenzi_user( $id ) {
        $this->db->select("a.*")
                ->from("comenzi a")
                ->where("a.id_user", $id);

        return $this->db->get()->result_array();
    }
}

/* End of file pagini_model.php */
/* Location: ./application/models/admin/pagini_model.php */
