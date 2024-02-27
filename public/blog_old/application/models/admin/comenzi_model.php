<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');



class comenzi_model extends CI_Model {



    public function get_comenzi( $q = array(), $limit = "", $offset = "") {

        $this->db->select('a.*, 

            IF(tip="2", b.nume_firma, CONCAT( b.nume, " ", b.prenume )) nume_client,

            DATE_FORMAT(a.data_adaugare, "%d.%m.%Y") data_adaugare_f, 

            (SELECT ROUND((SUM(pret*cantitate)+a.taxa_livrare+a.valoare_voucher+a.valoare_discount_fidelitate)+(SUM(pret*cantitate)*a.tva/100), 2) FROM comenzi_produse WHERE id_comanda = a.id) valoare', FALSE);

        $this->db->from('comenzi a');

        $this->db->join('useri b', 'a.id_user = b.id', 'inner');

        

        if( isset( $q['stare'] ) && $q['stare']!="" ) {

            $this->db->where("a.stare", $q['stare']);

        } 

        

        if( isset( $q['id_tip_plata'] ) && $q['id_tip_plata']!="" ) {

            $this->db->where("a.id_tip_plata", $q['id_tip_plata']);

        } 

        

        if( isset( $q['q'] ) && $q['q']!="" && isset( $q['dupa'] ) && $q['dupa']!="" ) {

            if( $q['dupa']=="nume_client" ) {

                $this->db->like('IF(tip="2", b.nume_firma, CONCAT( b.nume, " ", b.prenume ))', $q['q'], FALSE);

            } else {

                $this->db->where("{$q['dupa']}", $q['q']);

            }

        } 

        

        if( ( isset( $q['data_start'] ) && $q['data_start']!="" ) && ( isset( $q['data_sfarsit'] ) && $q['data_sfarsit']!="" ) ) {

            $this->db->where("a.data_adaugare BETWEEN '{$q['data_start']}' AND DATE_ADD('{$q['data_sfarsit']}', INTERVAL 1 DAY)", "", FALSE);

        } elseif( isset( $q['data_start'] ) && $q['data_start']!="" && ( !isset($q['data_sfarsit']) || $q['data_sfarsit']=="" ) ) {

            $this->db->where("a.data_adaugare BETWEEN '{$q['data_start']}' AND DATE_ADD(NOW(), INTERVAL 1 DAY)", "", FALSE);

        } elseif( isset( $q['data_sfarsit'] ) && $q['data_sfarsit']!="" && ( !isset($q['data_start']) || $q['data_start']=="" ) ) {

            $this->db->where("a.data_adaugare BETWEEN NOW() AND DATE_ADD('{$q['data_sfarsit']}', INTERVAL 1 DAY)", "", FALSE);

        }

        

        $this->db->order_by('a.data_adaugare', 'DESC');

        

        $limit!="" ? $this->db->limit($limit, $offset) : "";

        

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

    

    public function get_filtru($id_filtru) {

        $this->db->select("a.*, (SELECT nume FROM filtre WHERE id = a.id_parinte) nume_parinte")

                ->from("filtre a")

                ->where("a.id", $id_filtru);

        

        return $this->db->get()->row_array();

    }

    

    public function get_raport($data_start, $data_sfarsit, $id_user) {

        $this->db->select("COUNT(a.id_produs) nr_comenzi, SUM(a.cantitate) cantitate, SUM( a.cantitate * a.pret ) valoare, a.id_produs, c.nume nume_produs, c.cod_ean13", FALSE)

                ->from("comenzi_produse a")

                ->join("comenzi b", "a.id_comanda = b.id", "INNER")

                ->join("produse c", "a.id_produs = c.id", "INNER")

                ->where("b.stare_plata", 1);
        
        if( $id_user!="" && is_numeric($id_user) ) {
            $this->db->where("b.id_user", $id_user);
        }
        
        $this->db->where("b.data_adaugare BETWEEN '{$data_start}' AND DATE_ADD('{$data_sfarsit}', INTERVAL 1 DAY)")

                ->group_by("a.id_produs");

                

        return $this->db->get()->result_array();

    }
    
    public function get_lista_useri() {
        $this->db->select("id, IF(nume_firma='' OR nume_firma IS NULL, CONCAT(nume, ' ', prenume), CONCAT(nume_firma, ' - ', nume, ' ', prenume)) nume_intreg", FALSE);
        $this->db->from("useri");
        $this->db->order_by("nume_intreg");
        
        return $this->db->get()->result_array();
    }

}



/* End of file comenzi_model.php */

/* Location: ./application/models/admin/comenzi_model.php */

