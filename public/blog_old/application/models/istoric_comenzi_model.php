<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');



class Istoric_comenzi_model extends CI_Model {



    public function get_comenzi( $id_user ) {

        $this->db->select('a.*, DATE_FORMAT(a.data_adaugare, "%d.%m.%Y") data_adaugare_f, (SELECT ROUND((SUM(pret*cantitate)+a.taxa_livrare+a.valoare_voucher+a.valoare_discount_fidelitate)+(SUM(pret*cantitate)*a.tva/100), 2) FROM comenzi_produse WHERE id_comanda = a.id) valoare, (SELECT SUM(cantitate) FROM comenzi_produse WHERE id_comanda = a.id) nr_produse', FALSE);

        $this->db->from('comenzi a');

        $this->db->where('a.id_user', $id_user);

        $this->db->order_by('a.data_adaugare', 'DESC');



        return $this->db->get()->result_array();

    }

    

    public function get_comanda( $id_comanda ) {

        $this->db->select('a.*, b.nume nume_curier,

            DATE_FORMAT(a.data_adaugare, "%d.%m.%Y") data_adaugare_f, 

            (SELECT ROUND((SUM(pret*cantitate)+a.taxa_livrare+a.valoare_voucher+a.valoare_discount_fidelitate)+(SUM(pret*cantitate)*a.tva/100), 2) FROM comenzi_produse WHERE id_comanda = a.id) valoare, 

            (SELECT ROUND(SUM(pret*cantitate), 2) FROM comenzi_produse WHERE id_comanda = a.id) subtotal, 

            (SELECT ROUND((SUM(pret*cantitate)*a.tva/100), 2) FROM comenzi_produse WHERE id_comanda = a.id) valoare_tva', FALSE);

        $this->db->from('comenzi a');

        $this->db->join('curieri b', 'a.id_curier = b.id', 'inner');

        $this->db->where('a.id', $id_comanda);



        return $this->db->get()->row_array();

    }

    

    public function get_produse_comanda( $id_comanda ) {

        $this->db->select('a.*, b.nume, b.cod_ean13, ROUND((a.cantitate*a.pret), 2) valoare', FALSE);

        $this->db->from('comenzi_produse a');

        $this->db->join('produse b', 'a.id_produs = b.id', 'inner');

        $this->db->where('a.id_comanda', $id_comanda);



        return $this->db->get()->result_array();

    }

    

    public function get_stare_comanda( $order_id ) {

        $this->db->select("stare_plata");

        $this->db->from("comenzi");

        $this->db->where("order_id", $order_id);



        return $this->db->get()->row()->stare_plata;

    }

    

    public function get_comanda_by_order_id( $order_id ) {

        $this->db->select('a.*, DATE_FORMAT(a.data_adaugare, "%d.%m.%Y") data_adaugare_f', FALSE);

        $this->db->from("comenzi a");

        $this->db->where("a.order_id", $order_id);



        return $this->db->get()->row_array();

    }

    

    public function get_comanda_by_nr_factura( $nr_factura ) {

        $this->db->select('a.*', FALSE);

        $this->db->from("comenzi a");

        $this->db->where("a.nr_factura", $nr_factura);



        return $this->db->get()->row_array();

    }

    

}



/* End of file index_page_model.php */

/* Location: ./application/models/index_page_model.php */

