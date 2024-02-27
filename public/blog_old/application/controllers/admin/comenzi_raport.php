<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class comenzi_raport extends CI_Controller {



    public function __construct() {

        parent::__construct();

        //$this->output->enable_profiler(true);
        

        parent::__construct();

        $this->load->library('functions');

        $this->load->library('table');

        $this->load->helper('html');

        $this->load->helper('text');

        $this->load->helper('form');

        $this->load->helper('breadcrumbs');

        $this->load->helper('setari');

        $this->load->model('admin/index_page_model', 'indexm');

        $this->load->model('admin/comenzi_model', 'comenzim');

        $this->load->model('user_model', 'userm');

        $this->config->load('table');

        if (!$this->simpleloginsecure->is_admin()) {

            redirect('admin/login');

        }

        

        setlocale(LC_MONETARY, 'ro_RO');

    }



    public function index( $data = array() ) {
        
        $items = $this->comenzim->get_lista_useri();
        $data['options_useri'] = $this->functions->build_form_dropdown( $items, 'id', 'nume_intreg', "--TOTI--" ); 

        $data['breadcrumbs'] = array(

                array(

                    "link" => "",

                    "titlu" => "Raport comenzi",

                    "class" => "current",

                ),

            );

        

        $data["page"] = "comenzi_raport";

        $data["page_view"] = "comenzi_raport";



        $this->load->library('admin/display', $data);

    }

    

    public function genereaza($data = array()) {

        $p = $this->input->get();

        

        //produs/ cod produs/ cantitate / pret/ valoare 

        if( !isset($p['data_start']) || !isset($p['data_sfarsit'])) {

            redirect("admin/comenzi_raport");

        }

        

        if( $p['data_start']=="" || $p['data_sfarsit']=="" ) {

            redirect("admin/comenzi_raport");

        }

        

        $items = $this->comenzim->get_raport( $p['data_start'], $p['data_sfarsit'], $p['id_user'] );

        

        $this->table->set_template( $this->config->item('table_config') );

        $this->table->set_heading('Nume produs', 'Cod EAN13', 'Nr. comenzi', 'Cantitatea', 'Valoare (RON)');

        $k = 1;

        $total_cantitate = $total_valoare = 0;

        foreach ($items as $item) {

            $row = array(

                anchor($this->functions->make_furl_produs( $item['nume_produs'], $item['id_produs'] ), $item['nume_produs']),

                $item['cod_ean13'],

                array(

                    "data" => $item['nr_comenzi'],

                    "align" => "center",

                ),

                array(

                    "data" => $item['cantitate'],

                    "align" => "center",

                ),

                array(

                    "data" => $this->functions->pret_format( $item['valoare'] ),

                    "align" => "right",

                ),

            );

            $this->table->add_row($row);

            

            $total_cantitate += $item['cantitate'];

            $total_valoare += $item['valoare'];

            

            $k++;



            }

        $total_valoare = $this->functions->pret_format( $total_valoare );

        

        if ($k > 1) {

            $row = '<tfoot>

               <tr>

                <td colspan="3">

                    <strong>TOTAL:</strong>

                </td>

                <td align="center"><strong>' . $total_cantitate . '</strong></td>

                <td align="right"><strong>' . $total_valoare . ' RON</strong></td>

            </tfoot>

            </table>';

            

            $data['tabelDate'] = $this->table->generate();

            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );

        } else {

            $data['warning'] = "Nu a fost gasita nici o comanda in intervalul selectat.";

        }

        

        $this->index( $data );

    }

}



/* End of file index_page.php */

/* Location: ./application/controllers/admin/index_page.php */
