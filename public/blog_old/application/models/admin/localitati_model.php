<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class localitati_model extends CI_Model {

    public function get_localitati( $q = array(), $limit = "", $offset = "") {
        $this->db->select('a.*, b.nume nume_judet, IFNULL(c.nume, "-") nume_zona', FALSE)
            ->from('geo_localitati a')
            ->join('geo_judete b', 'a.id_judet = b.id', 'inner')
            ->join('geo_zone c', 'a.id_zona = c.id', 'left')
            ->order_by('a.nume');
        
        if( $q['nume']!="" ) {
            $this->db->like('a.nume', $q['nume']);
        }
        
        if( $q['id_judet']!=0 ) {
            $this->db->where('a.id_judet', $q['id_judet']);
        }
        
        if( $q['id_zona']!=0 ) {
            $this->db->where('a.id_zona', $q['id_zona']);
        }
        
        $limit!="" ? $this->db->limit($limit, $offset) : "";

        return $this->db->get()->result_array();
    }

    public function get_localitati_by_judet( $id_judet, $id_zona = NULL ) {
        $this->db->select('a.*', FALSE)
            ->from('geo_localitati a')
            ->where('a.id_judet', $id_judet);
        
        $id_zona!==NULL ? $this->db->where('a.id_zona', $id_zona) : "";
        
        $this->db->order_by('a.nume');

        return $this->db->get()->result_array();
    }

    public function get_localitati_by_zona( $id_zona) {
        $this->db->select('a.*', FALSE)
            ->from('geo_localitati a')
            ->where('a.id_zona', $id_zona);
        
        $this->db->order_by('a.nume');

        return $this->db->get()->result_array();
    }

    public function get_localitate( $id ) {
        $this->db->select('a.*')
            ->from('geo_localitati a')
            ->where('a.id', $id);

        return $this->db->get()->result_array();
    }
    
    public function get_galerie_imagini( $id, $field = "id_localitate" ) {
        $this->db->select('id, titlu, fisier, principala')
            ->from('general_galerie')
            ->where($field, $id);

        return $this->db->get()->result_array();
    }
    
    public function get_imagine( $id ) {
        $this->db->from('general_galerie')
            ->where('id', $id);

        return $this->db->get()->row_array();
    }

}

/* End of file index_page_model.php */
/* Location: ./application/models/admin/index_page_model.php */
