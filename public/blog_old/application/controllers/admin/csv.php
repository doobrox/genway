<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class csv extends CI_Controller {
    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);
        
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->model('admin/produse_model', 'produsem');
//        $this->load->model('admin/csv_model', 'indexm');
        $this->load->library('functions');
        
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
    }

    public function index( $data = array() ) {
        
        $data["page"] = "csv";
        $data["page_view"] = "csv";

        $this->load->library('admin/display', $data);
    }
    
    public function export() {
        $fisier = dirname(__FILE__) . "/csv/produse.csv";
        if($fp = fopen($fisier, 'w')) {
            $header = array(
                "COD",
                "COD EAN13",
                "NUME",
                "PRODUCATOR",
                "CATEGORII",
                "DESCRIERE",
                "GREUTATE",
                "PRET",
                "TIP REDUCERE",
                "VALOARE REDUCERE",
                "STOC",
                "STOC LA COMANDA",
                "PRODUSE RECOMANDATE",
                "META DESCRIPTION",
                "META KEYWORDS",
                "PROMOVAT INDEX",
                "PROMOTIE",
                "ACTIV",
            );
            fputcsv($fp, $header, '^', '"');
            $items = $this->produsem->get_produse();
            foreach ($items as $item) {
                $producator = $this->indexm->get_producator( $item['id_producator'] );
                $producator = "[{$producator['id']}] {$producator['nume']}";
                
                $categorii = $this->produsem->get_categorii_produs( $item['id'] );
                $categorii_txt = "";
                $k = 1;
                $nr = count($categorii);
                foreach ($categorii as $categorie) {
                    $categorii_txt .= "[{$categorie['id_categorie']}] {$categorie['nume']}" . ($k<$nr ? "\n" : "");
                    $k++;
                }
                
                $produse_recomandate = $this->produsem->get_produse_recomandate( $item['id'] );
                $produse_recomandate_txt = "";
                $k = 1;
                $nr = count($produse_recomandate);
                foreach ($produse_recomandate as $prod_recomandat) {
                    $produse_recomandate_txt .= "[{$prod_recomandat['id_produs_recomandat']}] {$prod_recomandat['nume']}" . ($k<$nr ? "\n" : "");
                }
                
                switch ($item['reducere_tip']) {
                    case "1":
                        $tip_reducere = "[1] Reducere valorica";
                        break;

                    case "2":
                        $tip_reducere = "[2] Reducere procentuala";
                        break;

                    default:
                        $tip_reducere = "[0] Fara reducere";
                        break;
                }
                
                $produs = array(
                    $item['id'],
                    $item['cod_ean13'],
                    $item['nume'],
                    $producator,
                    $categorii_txt,
                    $item['descriere'],
                    str_replace( ".", ",", $item['greutate']),
                    str_replace( ".", ",", $item['pret']),
                    $tip_reducere,
                    str_replace( ".", ",", $item['reducere_valoare']) . ( $item['reducere_tip']=="2" ? "%" : "" ),
                    $item['stoc'],
                    $item['stoc_la_comanda']==1 ? "DA" : "NU",
                    $produse_recomandate_txt,
                    $item['meta_description'],
                    $item['meta_keywords'],
                    $item['promovat_index']==1 ? "DA" : "NU",
                    $item['promotie']==1 ? "DA" : "NU",
                    $item['activ']==1 ? "DA" : "NU",
                );
                
                fputcsv($fp, $produs, '^', '"');
            }
            
            fclose($fp);
        }

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"produse_". date('d-m-Y') .".csv\"");

        echo file_get_contents($fisier);
    }
    
    public function import() {
        //6
        $data = array();
        $p = $this->input->post();
        
        $config['upload_path'] = dirname(__FILE__) . "/csv/import/";
        $config['allowed_types'] = 'csv';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload( 'csvfile' )) {
            $uploaded_data = $this->upload->data();
            
            $fisier = $config['upload_path'] . $uploaded_data['file_name'];
            //$fisier = dirname(__FILE__) . "/csv/import/produse_30-03-2013.csv";
            if ($fp = fopen($fisier, 'r')) {
                $line = 1;
                $data['error'] = "";
                $data['succes'] = "";
                while ($row = fgetcsv($fp, 1024, '^', '"')) {
                    if( trim( $row[0] )=="" && !isset( $p['add_produse'] ) ) continue;
                    
                    if( $line>1 ) {
                        $row_error = "";
                        //if(!is_numeric($row[0])) $row_error .= "Eroare la randul: {$line}. Campul COD trebuie sa fie numeric.<br />";
                        if(trim($row[1])=="") $row_error .= "Eroare la randul: {$line}. Campul COD EAN13 este obligatoriu.<br />";
                        if(trim($row[2])=="") $row_error .= "Eroare la randul: {$line}. Campul NUME este obligatoriu.<br />";
                        if(trim($row[3])=="") $row_error .= "Eroare la randul: {$line}. Campul PRODUCATOR este obligatoriu.<br />";
                        if(trim($row[4])=="") $row_error .= "Eroare la randul: {$line}. Produsul trebuie sa faca parte din cel putin o categorie.<br />";
                        if(trim($row[5])=="") $row_error .= "Eroare la randul: {$line}. Campul DESCRIERE este obligatoriu.<br />";
                        if(trim($row[6])=="") $row_error .= "Eroare la randul: {$line}. Campul GREUTATE este obligatoriu.<br />";
                        if(trim($row[7])=="") $row_error .= "Eroare la randul: {$line}. Campul PRET este obligatoriu.<br />";
                        if(!is_numeric($row[10])) $row_error .= "Eroare la randul: {$line}. Campul STOC trebuie sa fie numeric.<br />";

                        preg_match('/\[([0-9]+)\]/i', $row[3], $matches);
                        if( isset( $matches[1] ) ) {
                            $producator = $this->indexm->get_producator( $matches[1] );
                            if( !empty( $producator ) ) {
                                $id_producator = $matches[1];
                            }
                        }

                        if( !isset( $id_producator ) ) {
                            $row_error = "Eroare la randul: {$line}. Nu a fost gasit nici un producator cu id-ul specificat.<br />";
                        }

                        $greutate = str_replace( ",", ".", $row[6] );
                        $pret = str_replace( ",", ".", $row[7] );

                        preg_match_all('/\[([0-9]+)\]/i', $row[4], $matches);
                        $categorii_produs = array();
                        if( isset( $matches[1] ) ) {
                            foreach ($matches[1] as $id) {
                                $categorie = $this->indexm->get_categorie( $id );
                                if( !empty($categorie) ) {
                                    $categorii_produs[] = $id;
                                } else {
                                    $row_error = "Eroare la randul: {$line}. Una din categoriile adaugate nu a fost gasita in baza de date.<br />";
                                }
                            }
                        } else {
                            $row_error = "Eroare la randul: {$line}. Nu a fost gasita nici o categorie valida.<br />";
                        }

                        preg_match('/\[([0-9]+)\]/i', $row[8], $matches);
                        $reducere_tip = 0;
                        if( isset( $matches[1] ) ) {
                            if( $matches[1]==0 || $matches[1]==1 || $matches[1]==2 ) {
                                $reducere_tip = $matches[1];
                            } else {
                                $row_error = "Eroare la randul: {$line}. Campul TIP REDUCERE trebuie sa fie egal cu: 0,1 sau 2.<br />";
                            }
                        }

                        $reducere_valoare = str_replace( "%", "", $row[9] );
                        $reducere_valoare = str_replace( ",", ".", $reducere_valoare );
                        if( !is_numeric($reducere_valoare) ) {
                            $row_error = "Eroare la randul: {$line}. Campul VALOARE REDUCERE trebuie sa fie numeric.<br />";
                        }

                        $stoc_la_comanda = $row[11]=="DA" ? 1 : 0;

                        preg_match_all('/\[([0-9]+)\]/i', $row[12], $matches);
                        $produse_recomandate = array();
                        if( isset( $matches[1] ) ) {
                            foreach ($matches[1] as $id) {
                                $produs = $this->produsem->get_produs( $id );
                                if( !empty($produs) ) {
                                    $produse_recomandate[] = $id;
                                } else {
                                    $row_error = "Eroare la randul: {$line}. Unul din produsele recomandate nu a fost gasita in baza de date.<br />";
                                }
                            }
                        } else {
                            $row_error = "Eroare la randul: {$line}. Nu a fost gasita nici o categorie valida.<br />";
                        }

                        $promovat_index = $row[15]=="DA" ? 1 : 0;
                        $promotie = $row[15]=="DA" ? 1 : 0;
                        $activ = $row[17]=="DA" ? 1 : 0;

                        if( $row_error == "" ) {
                            $this->db->set('id_producator', $id_producator);
                            $this->db->set('cod_ean13', $row[1]);
                            $this->db->set('nume', $row[2]);
                            $this->db->set('descriere', $row[5]);
                            $this->db->set('greutate', $greutate);
                            $this->db->set('pret', $pret);
                            $this->db->set('reducere_tip', $reducere_tip);
                            $this->db->set('reducere_valoare', $reducere_valoare);
                            $this->db->set('stoc', $row[10]);
                            $this->db->set('stoc_la_comanda', $stoc_la_comanda);
                            $this->db->set('meta_description', $row[13]);
                            $this->db->set('meta_keywords', $row[14]);
                            $this->db->set('promovat_index', $promovat_index);
                            $this->db->set('promotie', $promovat_index);
                            $this->db->set('activ', $activ);

                            $db_succes = false;
                            if( $row[0]!="" ) {
                                $this->db->where('id', $row[0]);
                                if( $this->db->update( 'produse' ) ) {
                                    $db_succes = true;
                                }
                            } else {
                                $this->db->set('data_adaugare', 'NOW()', FALSE);
                                if( $this->db->insert( 'produse' ) ) {
                                    $row[0] = $this->db->insert_id();
                                    $insert = true;
                                    $db_succes = true;
                                }
                            }

                            if( $db_succes==true ) {
                                if( isset( $insert ) ) {
                                    $data['succes'] = "A fost adaugat un nou produs cu COD: {$row[0]}.<br />" . $data['succes'];
                                } else {
                                    $data['succes'] = "Produsul cu COD: {$row[0]} a fost editat cu succes.<br />" . $data['succes'];
                                }
                                
                                if( !empty( $categorii_produs ) ) {
                                    $this->db->delete( 'produse_categorii', array('id_produs'=>$row[0]) );

                                    foreach( $categorii_produs as $id_categorie ) {
                                        $this->db->set('id_produs', $row[0]);
                                        $this->db->set('id_categorie', $id_categorie);
                                        $this->db->insert('produse_categorii');
                                    }
                                }

                                if( isset( $produse_recomandate ) && is_array( $produse_recomandate ) ) {
                                    $this->db->delete( 'produse_recomandate', array('id_produs'=>$row[0]) );
                                    $produse_recomandate = array_unique($produse_recomandate);

                                    foreach ($produse_recomandate as $id_produs_recomandat) {
                                        if( $id_produs_recomandat!="" && $id_produs_recomandat!=$row[0] ) {
                                            $this->db->set('id_produs', $row[0]);
                                            $this->db->set('id_produs_recomandat', $id_produs_recomandat);
                                            $this->db->insert('produse_recomandate');
                                        }
                                    }
                                }
                            }
                        }

                        $data['error'] = $row_error . $data['error'];
                    }

                    $line++;
                }
                
                fclose($fp);
                @unlink( $config['upload_path'] . $uploaded_data['file_name'] );
            }
        } else {
            $data['error'] = $this->upload->display_errors();
        }
        
        $this->index($data);
    }

}

/* End of file csv.php */
/* Location: ./application/controllers/admin/csv.php */