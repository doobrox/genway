<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class Cron_model extends CI_Model {

    public function get_newsletter_queque( $limit = 1 ) {
        $this->db->from('newsletters_queue');
        $this->db->order_by('id');
        $this->db->limit( $limit );

        return $this->db->get()->result_array();
    }
    
    public function get_newsletter( $id ) {
        $this->db->from("newsletters");
        $this->db->where("id", $id);

        return $this->db->get()->row_array();
    }
    
    public function get_categorii() {
        $this->db->select("id");
        $this->db->from("categorii");
        $this->db->where("activ", 1);
        $this->db->order_by("id_parinte, ordonare");

        return $this->db->get()->result_array();
    }
    
    public function get_produse() {
        $this->db->select("id, nume");
        $this->db->from("produse");
        $this->db->where("activ", 1);
        $this->db->where("stoc > ", 0);

        return $this->db->get()->result_array();
    }
    
    public function get_pagini() {
        $this->db->select("slug");
        $this->db->from("pagini");
        $this->db->where("activ", 1);
        $this->db->where("link_extern", "");
        $this->db->order_by("ordonare", 1);

        return $this->db->get()->result_array();
    }
    
    public function get_alerta_produse() {
        $this->db->select("a.id, a.id_produs, a.cantitate, a.email, b.nume, b.stoc");
        $this->db->from("produse_alerte a");
        $this->db->join("produse b", "a.id_produs = b.id", "INNER");
        $this->db->where("a.email_trimis", 0);
        $this->db->where("b.activ", 1);
        $this->db->where("a.cantitate <= ", "b.stoc", FALSE);

        return $this->db->get()->result_array();
    }

}

/* End of file index_page_model.php */
/* Location: ./application/models/index_page_model.php */
