<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');





class Produs_model extends CI_Model {


    


    private $camp_pret;


    


    public function __construct() {


        


        parent::__construct();


        


        $this->camp_pret = $this->session->userdata("reseller")==1 ? "pret" : "pret_user";


    }


    


    public function get_produs( $id_produs ) {


        $this->db->select('a.*, 


                '. $this->camp_pret .' pret,

                ROUND(' . $this->camp_pret . ', 2) pret_intreg,
                ROUND(' . $this->camp_pret . ', 2) pret_intreg_cu_tva,

                ROUND( IF(a.reducere_tip = "1", (' . $this->camp_pret . '-reducere_valoare), IF(a.reducere_tip="2", ' . $this->camp_pret . '-(reducere_valoare/100*' . $this->camp_pret . '), ' . $this->camp_pret . ')), 2) pret,
                ROUND( IF(a.reducere_tip = "1", ((' . $this->camp_pret . '*1.20)-reducere_valoare), IF(a.reducere_tip="2", (' . $this->camp_pret . '*1.20)-(reducere_valoare/100*(' . $this->camp_pret . '*1.20)), (' . $this->camp_pret . '*1.20))), 2) pret_cu_tva,


                IFNULL(c.fisier, "default.png") imagine,


                d.slug slug_producator, d.nume nume_producator,


                IFNULL(ROUND( AVG( CAST(nota as DECIMAL(10,2))-1 )), 0) nota_medie', FALSE);


        $this->db->from('produse a');


        $this->db->join('general_galerie c', 'c.id_produs = a.id AND c.principala = 1', 'LEFT');


        $this->db->join('produse_producatori d', 'a.id_producator = d.id', 'LEFT');


        $this->db->join('produse_comentarii e', 'e.id_produs = a.id AND e.activ = 1', 'LEFT');


        $this->db->where('a.id', $id_produs);


        $this->db->where('a.activ', 1);





        return $this->db->get()->row_array();


    }


    


    public function get_produs_by_cod_ean13( $cod_ean13 ) {


        $this->db->select('a.id, a.nume');


        $this->db->from('produse a');


        $this->db->where('a.cod_ean13', $cod_ean13);





        return $this->db->get()->row_array();


    }


    


    public function get_cod_produs_joomla_vechi( $id_produs ) {


        $this->db->select('a.cod_ean13');


        $this->db->from('produse_joomla_vechi a');


        $this->db->where('a.id', $id_produs);


        $query = $this->db->get();


        


        return $query->num_rows() > 0 ? $query->row()->cod_ean13 : NULL;


    }


    


    public function get_galerie_foto( $id_produs ) {


        $this->db->select("fisier")


                ->from("general_galerie")


                ->where("principala", 0)


                ->where("activ", 1)


                ->where("id_produs", $id_produs);


        


        return $this->db->get()->result_array();


    }


    


    public function get_produse_recomandate( $id_produs ) {


        $this->db->select('a.id, a.nume, a.descriere,


					a.' . $this->camp_pret . ' pret_intreg, 


					ROUND( IF(a.reducere_tip = "1", (a.' . $this->camp_pret . '-a.reducere_valoare), IF(a.reducere_tip="2", a.' . $this->camp_pret . '-(a.reducere_valoare/100*a.' . $this->camp_pret . '), a.' . $this->camp_pret . ')), 2) pret,


					IFNULL(b.fisier, "default.png") imagine', FALSE);


        $this->db->from('produse a');


        $this->db->join('general_galerie b', 'b.id_produs = a.id AND b.principala = 1', 'LEFT');


        $this->db->join('produse_recomandate c', 'c.id_produs_recomandat = a.id', 'LEFT');


        $this->db->join('produse d', 'c.id_produs_recomandat = d.id', 'LEFT');


        $this->db->where('c.id_produs', $id_produs);


        $this->db->where('d.activ', 1);


        $this->db->order_by('RAND()', '');


        $this->db->limit(4);





        return $this->db->get()->result_array();


    }


    


    public function get_comentarii( $id_locatie, $limit = NULL ) {


        $this->db->select("*, DATE_FORMAT(data_adaugare, '%d %M %Y') data_adaugare_f")


                ->from("produse_comentarii")


                ->where("id_produs", $id_locatie)


                ->where("activ", 1)


                ->order_by("data_adaugare", "DESC");


        


        $limit!==NULL ? $this->db->limit( $limit ) : "";





        return $this->db->get()->result_array();


    }


    


    public function get_nota_medie( $id_produs ) {


        $this->db->select('IFNULL(ROUND(AVG(CAST(CAST(nota AS CHAR) AS SIGNED)),1), 0) nota', FALSE);


        $this->db->from('produse_comentarii');


        $this->db->where('activ', 1);


        $this->db->where('id_produs', $id_produs);


        $query = $this->db->get();


        


        return $query->num_rows() > 0 ? $query->row()->nota : 0;


    }


    


    public function check_ip_address($id_produs, $ip) {


        $this->db->select("IF(HOUR(TIMEDIFF(NOW(),data_adaugare))>=24, 1, 0) check_ip", FALSE)


                ->from("produse_comentarii")


                ->where("id_produs", $id_produs)


                ->where("ip", $ip)


                ->order_by("data_adaugare", "DESC")


                ->limit(1);


        $get = $this->db->get();


        


        return $get->num_rows()>0 ? $get->row()->check_ip : TRUE;


    }


    


    public function check_produs_categorie($id_produs, $id_categorie) {


        $this->db->from("produse_categorii a")


                ->join("categorii b", "a.id_categorie = b.id", "inner")


                ->where("b.activ", 1)


                ->where("a.id_produs", $id_produs)


                ->where("a.id_categorie", $id_categorie);


                


        $get = $this->db->get();


        


        return $get->num_rows()>0 ? TRUE : FALSE;


    }





    public function get_filtru_produs($id_produs, $id_filtre) {


        $this->db->select("a.*") 


                ->from("produse_filtre a")


                ->where("a.id_filtre", $id_filtre)


                ->where("a.id_produs", $id_produs);


        


        return $this->db->get()->row_array();


    }


    


    public function get_filtre_produs($id_produs) {


           $this->db->select("a.id_filtre")


                ->from("produse_filtre a")


                ->where("a.id_produs", $id_produs);


        


        return $this->db->get()->result_array();


    }


    


    public function get_filtru($id_filtru) {


        $this->db->select("a.*, (SELECT nume FROM filtre WHERE id = a.id_parinte) nume_parinte")


                ->from("filtre a")


                ->where("a.id", $id_filtru);


        


        return $this->db->get()->row_array();


    }


    


    public function get_filtru_principal($id_filtru) {


        $this->db->select("a.*")


                ->from("filtre a")


                ->where("a.id", $id_filtru);


        


        return $this->db->get()->row_array();


    }


    


    public function get_subfiltre_by_id_parinte($id_parinte) {


        $this->db->select("a.*")


                ->from("filtre a")


                ->where("a.id_parinte", $id_parinte);


        


        return $this->db->get()->result_array();


    }


    


    public function get_stoc_nume_produs($id_produs) {


        $this->db->select("a.id, a.nume, a.stoc")


                ->from("produse a")


                ->where("a.id", $id_produs);


        


        return $this->db->get()->row_array();


    }


    


    public function get_produse_ce_recomanda_prod($id_produs_recomandat) {


        $this->db->select("id_produs")


                ->from("produse_recomandate")


                ->where("id_produs_recomandat", $id_produs_recomandat);


        


        return $this->db->get()->result_array();


    }


    


    public function get_categorii_produs($id_produs) {


        $this->db->select("id_categorie")


                ->from("produse_categorii")


                ->where("id_produs", $id_produs);


        


        return $this->db->get()->result_array();


    }
    
    public function get_fisiere_produs($id_produs) {
        $this->db->from("produse_fisiere");
        $this->db->where("id_produs", $id_produs);
        
        if( $this->session->userdata("reseller")==1 ) {
            $this->db->where("(reseller=0 OR reseller=1)", "", FALSE);
        } else {
            $this->db->where("reseller", 0);
        }
        
        return $this->db->get()->result_array();
    }


}





/* End of file index_page_model.php */


/* Location: ./application/models/index_page_model.php */


