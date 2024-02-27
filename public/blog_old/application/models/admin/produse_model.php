<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');





class produse_model extends CI_Model {





    public function get_produse( $q = array(), $limit = "", $offset = "") {


        $this->db->select("a.*, c.nume nume_categorie");


        $this->db->from("produse a");


        $this->db->join("produse_categorii b", "a.id = b.id_produs", "INNER");


        $this->db->join("categorii c", "b.id_categorie = c.id", "INNER");


        $this->db->group_by("a.id");


        


        if( isset($q['dupa']) && $q['dupa']!="" ) {


            switch ($q['dupa']) {


                case "a.nume":


                    $this->db->where("a.nume LIKE ". $this->db->escape( "%{$q['q']}%" ), "", FALSE);


                    break;





                default:


                    $this->db->where($q['dupa'], $q['q']);


                    break;


            }


        }


        


        if( isset($q['id_categorie']) && $q['id_categorie']!=0 ) {


            $this->db->where("b.id_categorie", $q['id_categorie']);


        }


        


        if( isset($q['stoc']) ) {


            switch ($q['stoc']) {


                case 1:


                    $this->db->where('a.stoc >', 0);


                    break;


                case 2:


                    $this->db->where('a.stoc =', 0);


                    break;


            }


        }


        


        if( isset($q['reducere']) && $q['reducere']!=0 ) {


            $this->db->where('a.reducere_tip <>', 0);


        }


        


        if( isset($q['promovat']) && $q['promovat']==true ) {


            $this->db->where('(a.promovat_index = 1 OR a.promotie = 1)', '', FALSE);


        }


        


        $limit!="" ? $this->db->limit($limit, $offset) : "";


        


        if( isset($q['order_by_nume']) ) {


            $this->db->order_by("a.nume", "ASC");


        } else {


            $this->db->order_by("a.ordonare_popular");
//            $this->db->order_by("a.data_adaugare", "DESC");


        }





        return $this->db->get()->result_array();


    }





    public function get_produse_promovate_index() {


        $this->db->select("a.*, c.nume nume_categorie");


        $this->db->from("produse a");


        $this->db->join("produse_categorii b", "a.id = b.id_produs", "INNER");


        $this->db->join("categorii c", "b.id_categorie = c.id", "INNER");


        $this->db->where('a.activ', 1);


        $this->db->where('a.promovat_index', 1);


        $this->db->where('a.stoc > ', 0);


        $this->db->group_by("a.id");


        $this->db->order_by("a.ordonare");





        return $this->db->get()->result_array();


    }





    public function get_produs( $id ) {


        $this->db->select("a.*, ROUND(pret, 2) pret", FALSE);


        $this->db->from("produse a");


        $this->db->where("a.id", $id);





        return $this->db->get()->result_array();


    }


    


    public function get_galerie_imagini( $id, $field = "id_produs" ) {


        $this->db->select('id, fisier, principala')


            ->from('general_galerie')


            ->where($field, $id);





        return $this->db->get()->result_array();


    }





    public function get_imagine( $id ) {


        $this->db->from('general_galerie')


            ->where('id', $id);





        return $this->db->get()->row_array();


    }


    


    public function get_categorii_produs($id_produs) {


        $this->db->select('a.*, b.nume')


            ->from('produse_categorii a')


            ->join('categorii b', 'a.id_categorie = b.id', 'inner')


            ->where('a.id_produs', $id_produs);





        return $this->db->get()->result_array();


    }


    


    public function get_produse_recomandate($id_produs) {


        $this->db->select('a.id_produs_recomandat, b.nume')


            ->from('produse_recomandate a')


            ->join('produse b', 'a.id_produs_recomandat = b.id', 'inner')


            ->where('a.id_produs', $id_produs);





        return $this->db->get()->result_array();


    }


    


    public function get_filtre_produs($id_produs) {


        $this->db->select('a.*, ROUND(IFNULL(a.pret, c.pret), 2) pret, ROUND(IFNULL(a.greutate, c.greutate), 2) greutate', FALSE)


            ->from('produse_filtre a')


            ->join('produse c', 'a.id_produs = c.id', 'INNER')


            ->where('a.id_produs', $id_produs);





        return $this->db->get()->result_array();


    }


    


    public function get_filtru_produs($id) {


        $this->db->select('a.*')


            ->from('produse_filtre a')


            ->where('a.id', $id);





        return $this->db->get()->row_array();


    }


    


    public function get_nume_filtru($id) {


        $this->db->select('a.nume')


            ->from('filtre a')


            ->where('a.id', $id);





        return $this->db->get()->row()->nume;


    }


    


    public function get_comentarii_produse( $q = array(), $limit = "", $offset = "") {


        $this->db->select("a.*, b.cod_ean13, b.nume nume_produs");


        $this->db->from("produse_comentarii a");


        $this->db->join("produse b", "a.id_produs = b.id", "INNER");


        


        if( isset($q['id_produs']) && $q['id_produs']!="" ) {


            $this->db->where("a.id_produs", $q['id_produs']);


        }


        


        $this->db->order_by("a.data_adaugare", "DESC");


        


        $limit!="" ? $this->db->limit($limit, $offset) : "";





        return $this->db->get()->result_array();


    }


    


    public function get_produse_ce_recomanda_prod($id_produs_recomandat) {


        $this->db->select("id_produs")


                ->from("produse_recomandate")


                ->where("id_produs_recomandat", $id_produs_recomandat);


        


        return $this->db->get()->result_array();


    }
    
    public function get_fisiere_tehnice($id_produs) {
        $this->db->from("produse_fisiere");
        $this->db->where("id_produs", $id_produs);

        return $this->db->get()->result_array();
    }
    
    public function get_fisier($id) {
        $this->db->from("produse_fisiere");
        $this->db->where("id", $id);

        return $this->db->get()->row_array();
    }


}





/* End of file produse_model.php */


/* Location: ./application/models/admin/produse_model.php */


