<?phpif (!defined('BASEPATH'))    exit('No direct script access allowed');class curieri extends CI_Controller {    public function __construct() {        parent::__construct();        //$this->output->enable_profiler(true);        parent::__construct();        $this->load->library('functions');        $this->load->library('table');        $this->load->helper('html');        $this->load->helper('text');        $this->load->helper('breadcrumbs');        $this->load->helper('form');        $this->load->model('admin/curieri_model', 'curierim');        $this->config->load('table');        if (!$this->simpleloginsecure->is_admin()) {            redirect('admin/login');        }    }    public function index( $data = array() ) {        $p = $this->input->post();        if( isset( $p['sterge'] ) ) {            $data = $this->sterge_curieri( $data );        }                $data["page"] = "curieri";        $curieri = $this->curierim->get_curieri();        $this->table->set_template( $this->config->item('table_config') );        $this->table->set_heading('#', 'Nume', 'Pret primul kg', 'Pret kg aditional', 'Optiuni');        $k = 1;        foreach ($curieri as $item) {            $js_titlu = str_replace("'", "`", $item['nume']);            $attrDelete = array(                "onclick" => "return confirm('Esti sigur ca vrei sa stergi curierul: {$js_titlu}?')"            );                            $row = array(                array(                    'data' => '<input type="checkbox" name="id[]" value='. $item['id'] .' />',                    'width' => '20',                ),                anchor("admin/curieri/editeaza/{$item['id']}", $item['nume']),                array(                    'data' => $item['pret_primul_kg'],                ),                array(                    'data' => $item['pret_kg_aditional'],                ),                array(                    'data' =>                        anchor("admin/curieri/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) .                        anchor("admin/curieri/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) .                         form_hidden('id_edit[]', $item['id']),                    'width' => '50'                )            );            $this->table->add_row($row);            $k++;        }        if ($k > 1) {            $row = '<tfoot>               <tr>                <td>                    <input type="checkbox" id="check_all" onclick="return updateCheckAll()" />                </td>                <td colspan="6">                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi curierii selectati?\')" />                </td>            </tfoot>            </table>';                        $data['tabelDate'] = $this->table->generate();            $data['tabelDate'] = str_replace( '</table>', $row, $data['tabelDate'] );        } else {            $data['warning'] = "Nici un curier gasit. " . anchor("admin/curieri/adauga", "Adauga un nou curier");        }                $data['breadcrumbs'] = array(                array(                    "link" => "",                    "titlu" => "Curieri",                    "class" => "current",                ),            );        $data["page_view"] = "curieri";        $this->load->library('admin/display', $data);    }        public function sterge_curieri( $data = array() ) {        $p = $this->input->post();        if( isset( $p['id'] ) ) {            foreach ($p['id'] as $id) {                $this->db->where('id', $id);                $this->db->delete('curieri');            }            $data['succes'] = "Curierii selectati au fost stersi cu succes.";        } else {            $data['warning'] = "Nu ati selectat nici un curier.";        }                return $data;    }    public function adauga($data = array()) {        $data["page"] = "curieri";               $data['options_activ'] = array(            1 => "DA",            0 => "NU"        );                $data['breadcrumbs'] = array(                array(                    "link" => site_url("admin/curieri"),                    "titlu" => "Curieri",                ),                array(                    "link" => "",                    "titlu" => "Adaug&#259; curier",                    "class" => "current",                ),            );        $data["page_view"] = "curieri_edit";        $this->load->library('admin/display', $data);    }    public function editeaza( $id, $data = array() ) {        $data["page"] = "curieri";        $data['options_activ'] = array(            1 => "DA",            0 => "NU"        );                $data['item'] = $this->curierim->get_curier( $id );        $data["page_view"] = "curieri_edit";                $data['breadcrumbs'] = array(                array(                    "link" => site_url("admin/curieri"),                    "titlu" => "Curieri",                ),                array(                    "link" => "",                    "titlu" => "Editeaz&#259; curier",                    "class" => "current",                ),            );        $this->load->library('admin/display', $data);    }    public function salveaza( $id = 0 ) {        $data = array();        $this->load->library('form_validation');        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');        $this->form_validation->set_rules('pret_primul_kg', 'Pret primul kg', 'trim|required|numeric');        $this->form_validation->set_rules('pret_kg_aditional', 'Pret kg aditional', 'trim|required|numeric');        $this->form_validation->set_rules('taxa_ramburs', 'Taxa ramburs', 'trim|required|numeric');        $this->form_validation->set_rules('procent_ramburs', 'Procent ramburs', 'trim|required|numeric');        $this->form_validation->set_rules('taxa_km_exteriori', 'Taxa km exteriori', 'trim|required|numeric');        $this->form_validation->set_rules('activ', 'Activ', 'trim|required|numeric');        if ($this->form_validation->run() == TRUE) {            $p = $this->input->post();                        if( $id == 0 ) {                $this->db->set('nume', $p['nume']);                $this->db->set('pret_primul_kg', $p['pret_primul_kg']);                $this->db->set('pret_kg_aditional', $p['pret_kg_aditional']);                $this->db->set('taxa_ramburs', $p['taxa_ramburs']);                $this->db->set('procent_ramburs', $p['procent_ramburs']);                $this->db->set('taxa_km_exteriori', $p['taxa_km_exteriori']);                $this->db->set('activ', $p['activ']);                if( $this->db->insert( 'curieri' ) ) {                    $data['succes'] = "Curierul a fost salvat cu succes.";                    $this->form_validation->_field_data = array();                }            } else {                $this->db->set('nume', $p['nume']);                $this->db->set('pret_primul_kg', $p['pret_primul_kg']);                $this->db->set('pret_kg_aditional', $p['pret_kg_aditional']);                $this->db->set('taxa_ramburs', $p['taxa_ramburs']);                $this->db->set('procent_ramburs', $p['procent_ramburs']);                $this->db->set('taxa_km_exteriori', $p['taxa_km_exteriori']);                $this->db->set('activ', $p['activ']);                $this->db->where('id', $id);                if( $this->db->update( 'curieri' ) ) {                    $data['succes'] = "Curierul a fost salvat cu succes.";                }            }        }        if( $id==0 ) {            $this->adauga($data);        } else {            $this->editeaza( $id, $data );        }    }        public function sterge( $id ) {        $this->db->delete( 'curieri', array('id'=>$id) );        $data["succes"] = "Curierul a fost sters cu succes.";        $this->index( $data );    }}/* End of file index_page.php *//* Location: ./application/controllers/admin/index_page.php */