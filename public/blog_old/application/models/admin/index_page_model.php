<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');



class Index_page_model extends CI_Model {

    

    public function get_user_email( $id_user ) {

        $this->db->select("user_email");

        $this->db->from("useri");

        $this->db->where('id', $id_user );



        return $this->db->get()->row()->user_email;

    }

    

    public function get_last_5_useri() {

        $this->db->select("a.id, a.user_email, a.nume, a.prenume");

        $this->db->from("useri a");

        $this->db->order_by("a.data_adaugare", "DESC");

        $this->db->limit(5);



        return $this->db->get()->result_array();

    }

    

    public function get_user( $id_user ) {

        $this->db->from("useri");

        $this->db->where("id", $id_user);



        return $this->db->get()->row_array();

    }

    

    public function get_categorii() {

        $this->db->select('a.*')

            ->from('categorii a')

            ->order_by('a.ordonare, a.id_parinte'); 



        return $this->db->get()->result_array();

    }



    public function get_categorie( $id ) {

        $this->db->select('a.*')

            ->from('categorii a')

            ->where('id', $id);



        return $this->db->get()->result_array();

    }



    public function count_produse_by_id_categorie( $id_categorie ) {

        $this->db->from('produse_categorii a')

            ->where('a.id_categorie', $id_categorie)

            ->or_where("a.id_categorie IN (SELECT id FROM categorii WHERE id_parinte = {$id_categorie})", "", FALSE);



        return $this->db->get()->num_rows();

    }



    public function get_max_ordonare( $table ) {

        $this->db->select("MAX(ordonare)+1 max_ordonare", FALSE);

        $this->db->from($table);



        return $this->db->get()->row()->max_ordonare;

    }

    

    public function get_ultimele_comenzi( $limit = 5 ) {

        $this->db->select('a.*, IF(tip="2", b.nume_firma, CONCAT( b.nume, " ", b.prenume )) nume_client,

            DATE_FORMAT(NOW(), "%d.%m.%Y") data_adaugare_f, 

            (SELECT ROUND((SUM(pret*cantitate)+a.taxa_livrare+a.valoare_voucher+a.valoare_discount_fidelitate)+(SUM(pret*cantitate)*a.tva/100), 2) FROM comenzi_produse WHERE id_comanda = a.id) valoare', FALSE);

        $this->db->from('comenzi a');

        $this->db->join('useri b', 'a.id_user = b.id', 'inner');

        $this->db->order_by('a.data_adaugare', 'DESC');

        $this->db->limit( $limit );



        return $this->db->get()->result_array();    

    }

    

    public function get_produse_stoc_limitat( $limit = 5 ) {

        $this->db->select('a.*', FALSE);

        $this->db->from('produse a');

        $this->db->where('a.stoc <= ', 5);

        $this->db->where('a.stoc_la_comanda',0);

        $this->db->order_by('a.stoc');

        $this->db->limit( $limit );



        return $this->db->get()->result_array();    

    }

    

    public function get_filtre( $id_parinte = NULL ) {

        $this->db->from("filtre");

        $id_parinte!==NULL ? $this->db->where( "id_parinte", $id_parinte ) : "";

        $this->db->order_by("ordonare, id_parinte");



        return $this->db->get()->result_array();

    }

    

    public function get_producator($id) {

        $this->db->select('a.*')

            ->from('produse_producatori a')

            ->where('id', $id);



        return $this->db->get()->row_array();

    }

}



/* End of file index_page_model.php */

/* Location: ./application/models/admin/index_page_model.php */

