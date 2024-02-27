<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index_Page extends CI_Controller {
    public function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(true);
        
        $this->load->model('admin/index_page_model', 'indexm');
        $this->load->library('functions');
    }

    public function index( $data = array() ) {
        
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
        
        if( $this->session->flashdata('succes')!="" ) {
            $data['succes'] = $this->session->flashdata('succes');
        }
        
        $items = $this->indexm->get_ultimele_comenzi();
        $data['comenzi'] = array();
        $k = 0;
        foreach( $items as $item ) {
            $data['comenzi'][$k] = $item;
            $data['comenzi'][$k]['furl'] = site_url( "admin/comenzi/comanda/{$item['id']}" ); 
            
            switch ($item['stare']) {
                case "-1":
                    $data['comenzi'][$k]['text_stare'] = "Anulata";
                    break;

                case "0":
                    $data['comenzi'][$k]['text_stare'] = "Comanda noua";
                    break;

                case "1":
                    $data['comenzi'][$k]['text_stare'] = "Comanda preluata";
                    break;

                case "2":
                    $data['comenzi'][$k]['text_stare'] = "Comanda livrata";
                    break;

                case "3":
                    $data['comenzi'][$k]['text_stare'] = "Comanda finalizata";
                    break;

                default:
                    break;
            }
            
            $k++;
        }
        
        $items = $this->indexm->get_produse_stoc_limitat();
        $data['stoc_limitat'] = array();
        $k = 0;
        foreach( $items as $item ) {
            $data['stoc_limitat'][$k] = $item;

            $k++;
        }
            
            
        $data["page"] = "index_page";

        $data["page_view"] = "index_page";

        $this->load->library('admin/display', $data);
    }
    
    public function ajax_get_localitati( $id = "" ) {
        
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
        
        $this->load->model('admin/localitati_model', 'localitatim');
        $data = array();
        if( $id!="" ) {
            $localitati = $this->localitatim->get_localitati_by_judet( $id, NULL );
            $data['select_localitati'] = array();
            $k = 1;
            foreach( $localitati as $localitate ) {
                $data['select_localitati'][$k]['id'] = $localitate['id'];
                $data['select_localitati'][$k]['nume'] = $localitate['nume'];

                $k++;
            }
            echo json_encode( $data['select_localitati'] );
        }
    }
    
    public function clear_cache() {
        
        if( !$this->simpleloginsecure->is_admin() ) {
            redirect('admin/login');
        }
        
        $this->load->driver('cache');
        $data = array();
        
        $files = scandir( APPPATH.'cache/' );
        foreach ($files as $file) {
            if( $file == "." || $file == ".." || $file == "index.html" || $file == ".htaccess" ) continue;
            
            $this->cache->file->delete($file);
        }
        
        $data['succes'] = "Cache-ul a fost sters cu succes."; 
        $this->index($data);
    }
    
    public function uploadify() {
        $targetFolder = MAINSITE_STYLE_PATH . "images/produse/temp/";

        if (!empty($_FILES)) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $fileName = strtolower( $_FILES['Filedata']['name'] );
            $targetFile = rtrim($targetFolder, '/') . '/' . $fileName;

            $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
            $fileParts = pathinfo($fileName);

            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
                echo '1';
            } else {
                echo 'Invalid file type.';
            }
        }
    }
    
    public function stergere_imagine( $id_imagine ) {
        $this->load->model('admin/produse_model', 'produsem');
        
        $imagine = $imagine = $this->produsem->get_imagine( $id_imagine );
        if( $imagine ) {
            @unlink( MAINSITE_STYLE_PATH . 'images/produse/' . $imagine['fisier'] );
            @unlink( MAINSITE_STYLE_PATH . 'images/produse/85x85/' . $imagine['fisier'] );
            
            $this->db->where('id', $id_imagine);
            if( $this->db->delete( 'general_galerie' ) ) {
                echo "1";
            }
        }
    }

    public function ajax_build_slug() {
        $q = $this->input->get('q');
        $q = urldecode($q);
        
        echo $this->functions->build_slug( $q );
    }
}

/* End of file index_page.php */
/* Location: ./application/controllers/admin/index_page.php */