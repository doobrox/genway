<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');



class Index_page_model extends CI_Model {

    private $camp_pret;

    

    public function __construct() {

        

        parent::__construct();

        

        $this->camp_pret = $this->session->userdata("reseller")==1 ? "pret" : "pret_user";

    }

    

    public function get_judet( $slug ) {

        $this->db->from('geo_judete');

        $this->db->where('slug', $slug);



        return $this->db->get()->result_array();

    }



    public function get_judet_by_id( $id ) {

        $this->db->from('geo_judete');

        $this->db->where('id', $id);



        return $this->db->get()->row_array();

    }



    public function get_localitati_by_id_judet( $id_judet ) {

        $this->db->select('a.id, a.nume');

        $this->db->from('geo_localitati a');

        $this->db->where('a.id_judet', $id_judet);

        

        $this->db->order_by('a.nume');

        

        return $this->db->get()->result_array();

    }



    public function get_localitate( $judet_slug, $localitate_slug ) {

        $this->db->select('a.*');

        $this->db->from('geo_localitati a');

        $this->db->join('geo_judete b', 'a.id_judet = b.id', 'INNER');

        $this->db->where('a.slug', $localitate_slug);

        $this->db->where('b.slug', $judet_slug);



        return $this->db->get()->result_array();

    }



    public function get_localitate_by_id( $id_judet, $id_localitate = NULL ) {

        if( $id_localitate==NULL ) {

            $id_localitate = $id_judet;

            $id_judet = NULL;

        }

        

        $this->db->select('a.*, b.slug slug_judet');

        $this->db->from('geo_localitati a');

        $this->db->join('geo_judete b', 'a.id_judet = b.id', 'INNER');

        $this->db->where('a.id', $id_localitate);

        

        $id_judet!=NULL ? $this->db->where('a.id_judet', $id_judet) : "";

        

        $this->db->order_by("a.nume");

        

        return $this->db->get()->row_array();

    }



    public function get_localitati_by_nume( $nume_localitate ) {

        $this->db->select('a.*, b.slug slug_judet');

        $this->db->from('geo_localitati a');

        $this->db->join('geo_judete b', 'a.id_judet = b.id', 'INNER');

        $this->db->join('locatii c', 'a.id = c.id_localitate', 'INNER');

        $this->db->like('a.nume', $nume_localitate, 'after');

        $this->db->or_like('a.nume', " ".$nume_localitate);

        $this->db->order_by("a.nume");

        $this->db->group_by("a.id");

        

        return $this->db->get()->result_array();

    }



    public function get_judete() {

        $this->db->select('id, nume');

        $this->db->from('geo_judete');

        $this->db->order_by('nume');



        return $this->db->get()->result_array();

    }

    

    public function get_footer_pages() {

        $this->db->from('pagini');

        $this->db->where('activ', 1);

        $this->db->where('in_meniu', 1);

        $this->db->order_by('ordonare');



        return $this->db->get()->result_array();

    }

    

    public function get_header_pages() {

        $this->db->from('pagini');

        $this->db->where('activ', 1);

        $this->db->where('in_meniu_principal', 1);

        $this->db->order_by('ordonare');



        return $this->db->get()->result_array();

    }

    

    public function get_pagina( $id ) {

        $this->db->from('pagini');

        $this->db->where('id', $id);

        $this->db->where('activ', 1);



        return $this->db->get()->row_array();

    }

    

    public function get_bannere( $zona = 'HEADER' ) {

        $this->db->from('bannere');

        $this->db->where('zona', $zona);

        $this->db->where('activ', 1);

        $this->db->order_by('data_adaugare', 'DESC');



        return $this->db->get()->result_array();

    }

    

    public function get_categorii() {

        $this->db->select('*, IF(imagine="" OR imagine IS NULL, "default.png", imagine) imagine', FALSE);

        $this->db->from('categorii');

        $this->db->where('activ', 1);

        $this->db->order_by('ordonare');



        return $this->db->get()->result_array();

    }

    

    public function get_id_parinte( $id ) {

        $this->db->select('id_parinte', FALSE);

        $this->db->from('categorii');

        $this->db->where('id', $id);



        return $this->db->get()->row()->id_parinte;

    }

    

    public function get_ids_categorii($id) {

        $this->db->select('id');

        $this->db->from('categorii');

        $this->db->where('id_parinte', $id);

        $ids = array();

        foreach ($this->db->get()->result_array() as $id) {

            $ids[] = $id['id'];

        }



        return $ids;

    }

    

    public function get_categorii_by_id_parinte( $id_parinte = 0 ) {

        $this->db->select('id, id_parinte, slug, nume, IF(imagine="" OR imagine IS NULL, "default.png", imagine) imagine', FALSE);

        $this->db->from('categorii');

        $this->db->where('id_parinte', $id_parinte);

        $this->db->where('activ', 1);

        $this->db->order_by('ordonare');



        return $this->db->get()->result_array();

    }

    

    public function get_produse_promovate_index() {

        $this->db->select('a.id, a.nume, a.descriere,

            ROUND(' . $this->camp_pret . ', 2) pret_intreg,

            ROUND( IF(a.reducere_tip = "1", (' . $this->camp_pret . '-reducere_valoare), IF(a.reducere_tip="2", ' . $this->camp_pret . '-(reducere_valoare/100*' . $this->camp_pret . '), ' . $this->camp_pret . ')), 2) pret,

            IFNULL(b.fisier, "default.png") imagine', FALSE);

        $this->db->from('produse a');

        $this->db->join('general_galerie b', 'b.id_produs = a.id AND b.principala = 1', 'LEFT');

        $this->db->where('a.activ', 1);

        $this->db->where('a.promovat_index', 1);

        $this->db->where('a.stoc > ', 0);

        $this->db->order_by('ordonare');

//        $this->db->order_by('RAND()');

//        $this->db->limit(54);



        return $this->db->get()->result_array();

    }

    

    public function get_ultimele_produse() {

        $this->db->select('a.id, a.nume, IFNULL(b.fisier, "default.png") imagine', FALSE);

        $this->db->from('produse a');

        $this->db->join('general_galerie b', 'b.id_produs = a.id AND b.principala = 1', 'LEFT');

        $this->db->where('a.activ', 1);

        $this->db->order_by('a.data_adaugare', 'DESC');

        $this->db->limit(14);



        return $this->db->get()->result_array();

    }

    

    public function get_producatori() {

        $this->db->from('produse_producatori a');

        $this->db->order_by('a.nume');



        return $this->db->get()->result_array();

    }

    

    public function get_curieri() {

        $this->db->from('curieri a');

        $this->db->where('a.activ', 1);

        $this->db->order_by('a.nume');



        return $this->db->get()->result_array();

    }

    

    public function get_curier( $id ) {

        $this->db->from('curieri a');

        $this->db->where('a.id', $id);

        $this->db->where('a.activ', 1);

        $this->db->order_by('a.nume');



        return $this->db->get()->row_array();

    }

    

    public function get_curier_default() {

        $this->db->select('a.id');

        $this->db->from('curieri a');

        $this->db->where('a.default', 1);

        

        $sql = $this->db->get();



        return $this->db->count_all_results()>0 ? $sql->row()->id : FALSE;

    }

    

    public function get_voucher($cod_voucher) {

        $this->db->select("*, IF(DATEDIFF(data_expirare, now())<0, 1, 0) expirat", FALSE);

        $this->db->from("vouchere");

        $this->db->where("cod", $cod_voucher);       

        return $this->db->get()->row_array();

    }

    public function get_voucher_produs($cod_voucher, $id_produs) {

        $this->db->select("*, IF(DATEDIFF(data_expirare, now())<0, 1, 0) expirat", FALSE);

        $this->db->from("vouchere");

        $this->db->where("cod", $cod_voucher);
        $this->db->where("id_produs", $id_produs);

        

        return $this->db->get()->row_array();

    }

    public function get_voucher_produs_exista($cod_voucher) {

        $this->db->select("*, IF(DATEDIFF(data_expirare, now())<0, 1, 0) expirat", FALSE);

        $this->db->from("vouchere");

        $this->db->where("cod", $cod_voucher);
        $this->db->where("id_produs >", 0);

        

        return $this->db->get()->row_array();

    }
    
    public function get_km_exteriori($id_localitate) {
        $this->db->select("km_exteriori");
        $this->db->from("geo_localitati");
        $this->db->where("id", $id_localitate);
        $sql = $this->db->get();
        
        return $this->db->count_all_results()>0 ? $sql->row()->km_exteriori : 0;
    }
    
    public function get_discount_fidelitate($id_user) {
        if( is_numeric( $id_user ) ) {
            $this->db->select("discount_fidelitate");
            $this->db->from("useri");
            $this->db->where("id", $id_user);
            $sql = $this->db->get();

            return $this->db->count_all_results()>0 ? $sql->row()->discount_fidelitate : 0;
        }
        
        return 0;
    }



}



/* End of file index_page_model.php */

/* Location: ./application/models/index_page_model.php */

