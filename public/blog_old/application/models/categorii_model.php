<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');





class Categorii_model extends CI_Model {


    private $camp_pret;


    


    public function __construct() {


        


        parent::__construct();


        


        $this->camp_pret = $this->session->userdata("reseller")==1 ? "pret" : "pret_user";


    }


    


    public function get_categorie_by_slug($slug) {


        $this->db->from('categorii');


        $this->db->where('slug', $slug);


        $this->db->where('activ', 1);





        return $this->db->get()->row_array();


    }





    public function get_producator_by_slug($slug) {


        $this->db->from('produse_producatori');


        $this->db->where('slug', $slug);





        return $this->db->get()->row_array();


    }


    


    public function get_produse( $q = array(), $limit = "", $offset = "" ) {


        


        if( isset( $q['count'] ) && $q['count']==true ) {


            $this->db->select('COUNT(*) nr_produse', FALSE);


        } else {


            $this->db->select('a.*, 


                    ROUND(' . $this->camp_pret . ', 2) pret_intreg,
                    ROUND(' . $this->camp_pret . ', 2) pret_intreg_cu_tva,

                    ROUND( IF(a.reducere_tip = "1", (' . $this->camp_pret . '-reducere_valoare), IF(a.reducere_tip="2", ' . $this->camp_pret . '-(reducere_valoare/100*' . $this->camp_pret . '), ' . $this->camp_pret . ')), 2) pret,
                    ROUND( IF(a.reducere_tip = "1", ((' . $this->camp_pret . '*1.20)-reducere_valoare), IF(a.reducere_tip="2", (' . $this->camp_pret . '*1.20)-(reducere_valoare/100*(' . $this->camp_pret . '*1.20)), (' . $this->camp_pret . '*1.20))), 2) pret_cu_tva,


                    IFNULL(c.fisier, "default.png") imagine', FALSE);


        }


        


        $this->db->from('produse a');


        $this->db->join('general_galerie c', 'c.id_produs = a.id AND c.principala = 1', 'LEFT');


        $this->db->join('produse_categorii b', 'a.id = b.id_produs', 'INNER');


        $this->db->join('categorii d', 'b.id_categorie = d.id', 'INNER');


        $this->db->where("d.activ", 1);


        


        $this->db->where("a.activ", 1 );


//        $this->db->where("a.stoc > ", 0 );


        


        if( isset($q['id_categorie']) && $q['id_categorie']!=0 ) {


            $this->db->where("b.id_categorie", $q['id_categorie']);


        }


        


        if( isset($q['id_producator']) && $q['id_producator']!=0 && is_numeric( $q['id_producator'] ) ) {


            $this->db->where("a.id_producator", $q['id_producator']);


        }


        


        if( ( isset($q['pret_de_la']) && $q['pret_de_la']!="" && is_numeric( $q['pret_de_la'] ) ) && 


            ( isset($q['pret_pana_la']) && $q['pret_pana_la']!="" && is_numeric( $q['pret_de_la'] ) ) ) {


            $this->db->where("ROUND( IF(a.reducere_tip = '1', ({$this->camp_pret}-reducere_valoare), IF(a.reducere_tip='2', {$this->camp_pret}-(reducere_valoare/100*{$this->camp_pret}), {$this->camp_pret})), 2) BETWEEN {$q['pret_de_la']} AND {$q['pret_pana_la']}", "", FALSE);


        } elseif( isset($q['pret_de_la']) && $q['pret_de_la']!="" && is_numeric( $q['pret_de_la'] ) ) {


            $this->db->where("ROUND( IF(a.reducere_tip = '1', ({$this->camp_pret}-reducere_valoare), IF(a.reducere_tip='2', {$this->camp_pret}-(reducere_valoare/100*{$this->camp_pret}), {$this->camp_pret})), 2) >= {$q['pret_de_la']}", "", FALSE);


        } elseif( isset($q['pret_pana_la']) && $q['pret_pana_la']!="" && is_numeric( $q['pret_pana_la'] ) ) {


            $this->db->where("ROUND( IF(a.reducere_tip = '1', ({$this->camp_pret}-reducere_valoare), IF(a.reducere_tip='2', {$this->camp_pret}-(reducere_valoare/100*{$this->camp_pret}), {$this->camp_pret})), 2) <= {$q['pret_pana_la']}", "", FALSE);


        }





        if( isset( $q['q'] ) && $q['q']!="" ) {


            $this->db->where("(CONCAT(' ', a.nume, ' ') LIKE ". $this->db->escape( "% {$q['q']} %" ) ." OR CONCAT(' ', a.descriere, ' ') LIKE ". $this->db->escape( "% {$q['q']} %" ) .")", "", FALSE);                


        }


        


        if( isset($q['reducere']) && $q['reducere']==1 ) {


            $this->db->where("a.reducere_tip <> '0'", "" );


        }


        


        if( isset($q['promotie']) && $q['promotie']==1 ) {


            $this->db->where("a.promotie", 1 );


        }


        


        if( isset( $q['sortare_dupa'] ) ) {


            switch ($q['sortare_dupa']) {


                case 0:


                    $this->db->order_by("a.nume");


                    break;


                case 1:


                    $this->db->order_by("a.nume", "DESC");


                    break;





                case 2:


                    $this->db->order_by("a.data_adaugare", "DESC");


                    break;





                case 3:


                    $this->db->order_by("a.data_adaugare");


                    break;





                case 4:


                    $this->db->order_by('ROUND( IF(a.reducere_tip = "1", (' . $this->camp_pret . '-reducere_valoare), IF(a.reducere_tip="2", ' . $this->camp_pret . '-(reducere_valoare/100*' . $this->camp_pret . '), ' . $this->camp_pret . ')), 2)');


                    break;





                case 5:


                    $this->db->order_by('ROUND( IF(a.reducere_tip = "1", (' . $this->camp_pret . '-reducere_valoare), IF(a.reducere_tip="2", ' . $this->camp_pret . '-(reducere_valoare/100*' . $this->camp_pret . '), ' . $this->camp_pret . ')), 2)', "DESC");


                    break;





                default:


                    $this->db->order_by("a.ordonare_popular");
//                    $this->db->order_by("a.nume");


                    break;


            }


        } 


        


        $limit!="" ? $this->db->limit($limit, $offset) : "";


        if( isset( $q['count'] ) && $q['count']==true ) {


            return $this->db->get()->row()->nr_produse;


        } else {


            return $this->db->get()->result_array();


        }


    }


}





/* End of file index_page_model.php */


/* Location: ./application/models/index_page_model.php */


