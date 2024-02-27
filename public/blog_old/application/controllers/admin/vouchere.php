<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class vouchere extends CI_Controller {



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

        $this->load->model('admin/index_page_model', 'indexm');

        $this->load->model('admin/vouchere_model', 'voucherem');

        $this->config->load('table');

        if (!$this->simpleloginsecure->is_admin()) {

            redirect('admin/login');

        }

    }



    public function index($data = array()) {



        $p = $this->input->post();

        if (isset($p['sterge'])) {

            $data = $this->sterge_vouchere($data);

        } elseif (isset($p['salveaza'])) {

            $data = $this->update_vouchere($data);

        }



        $data["page"] = "vouchere";

        $vouchere = $this->voucherem->get_vouchere();



        $this->table->set_template($this->config->item('table_config'));

        $this->table->set_heading('#', 'Cod', 'Nume', 'Tip', 'Valoare', 'Data expirare', 'Caracter', 'Activ', 'ID produs', 'Optiuni');

        $k = 1;

        foreach ($vouchere as $item) {

            $js_titlu = str_replace("'", "`", $item['nume']);

            $attrDelete = array(

                "onclick" => "return confirm('Esti sigur ca vrei sa stergi voucherul: {$js_titlu}?')"

            );



            $tip = "";

            switch ($item['tip']) {

                case "1":

                    $tip = "Valoric";

                    break;



                case "2":

                    $tip = "Procentual";

                    break;

            }



            $caracter = "";

            switch ($item['caracter']) {

                case "1":

                    $caracter = "Permanent";

                    break;



                case "2":

                    $caracter = "Unic";

                    break;

            }



            $row = array(

                array(

                    'data' => '<input type="checkbox" name="id[]" value=' . $item['id'] . ' />',

                    'width' => '20',

                ),

                $item['cod'],

                array(

                    'data' => anchor("admin/vouchere/editeaza/{$item['id']}", $item['nume']),

                    'width' => '150',

                ),

                $tip,

                $item['valoare'] . ( $item['tip'] == "2" ? "%" : "" ),

                $item['data_expirare'],

                $caracter,

                form_dropdown(

                        'activ[]', array(

                            "1" => "DA",

                            "0" => "NU",

                        ), $item['activ']

                ),

                $item['id_produs'],

                array(

                    'data' =>

                    anchor("admin/vouchere/sterge/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_trash.png"), $attrDelete) .

                    anchor("admin/vouchere/editeaza/{$item['id']}", img(ADMIN_STYLE_PATH . "images/icn_edit.png")) .

                    form_hidden('id_edit[]', $item['id']),

                    'width' => '50'

                )

            );

            $this->table->add_row($row);



            $k++;

        }

        if ($k > 1) {

            $row = '<tfoot>

               <tr>

                <td>

                    <input type="checkbox" id="check_all" onclick="return updateCheckAll()" />

                </td>

                <td>

                    <input type="submit" name="sterge" value="Sterge" onclick="return confirm(\'Esti sigur ca vrei sa stergi voucherele selectate?\')" />

                </td>

                <td colspan="6" align="right">

                    <input type="submit" name="salveaza" value="Salveaza" class="alt_btn" />

                </td>

            </tfoot>

            </table>';



            $data['tabelDate'] = $this->table->generate();

            $data['tabelDate'] = str_replace('</table>', $row, $data['tabelDate']);

        } else {

            $data['warning'] = "Nici un voucher gasit. " . anchor('admin/vouchere/adauga', 'Adaug&#259; unul');

        }



        $data['breadcrumbs'] = array(

            array(

                "link" => "",

                "titlu" => "Vouchere",

                "class" => "current",

            ),

        );

        $data["page_view"] = "vouchere";



        $this->load->library('admin/display', $data);

    }



    public function sterge_vouchere($data = array()) {

        $p = $this->input->post();

        if (isset($p['id'])) {

            foreach ($p['id'] as $id) {

                $this->db->delete('vouchere', array('id' => $id));

            }

            $data['succes'] = "Voucherele selectate au fost sterse cu succes.";

        } else {

            $data['warning'] = "Nu ati selectat nici un voucher.";

        }



        return $data;

    }



    public function update_vouchere($data = array()) {

        $p = $this->input->post();

        if (isset($p['id_edit'])) {

            $k = 0;



            foreach ($p['id_edit'] as $id) {

                $this->db->set('activ', $p['activ'][$k]);

                $this->db->where('id', $id);

                $this->db->update('vouchere');



                $k++;

            }



            $data['succes'] = "Voucherele au fost salvate cu succes.";

        }



        return $data;

    }



    public function adauga($data = array()) {

        $data["page"] = "vouchere";



        $data['options_tip'] = array(

            "1" => "Valoric",

            "2" => "Procentual"

        );



        $data['options_caracter'] = array(

            "1" => "Permanent",

            "2" => "Unic"

        );



        $data['options_activ'] = array(

            1 => "DA",

            0 => "NU"

        );



        $data['breadcrumbs'] = array(

            array(

                "link" => site_url("admin/vouchere"),

                "titlu" => "Vouchere",

            ),

            array(

                "link" => "",

                "titlu" => "Adaug&#259; voucher",

                "class" => "current",

            ),

        );

        $data["page_view"] = "vouchere_edit";



        $this->load->library('admin/display', $data);

    }



    public function editeaza($id, $data = array()) {

        $data["page"] = "vouchere";



        $item = $this->voucherem->get_voucher($id);

        $data['item'] = $item[0];



        $data["page_view"] = "vouchere_edit";



        $data['options_tip'] = array(

            "1" => "Valoric",

            "2" => "Procentual"

        );



        $data['options_caracter'] = array(

            "1" => "Permanent",

            "2" => "Unic"

        );



        $data['options_activ'] = array(

            1 => "DA",

            0 => "NU"

        );



        $data['breadcrumbs'] = array(

            array(

                "link" => site_url("admin/vouchere"),

                "titlu" => "Vouchere",

            ),

            array(

                "link" => "",

                "titlu" => "Editeaz&#259; voucher",

                "class" => "current",

            ),

        );



        $this->load->library('admin/display', $data);

    }



    public function salveaza($id = 0) {

        $data = array();

        

        $this->load->library('form_validation');

        $this->form_validation->set_rules('cod', 'Cod voucher', 'trim|required|alpha_numeric|max_length[15]|is_unique[vouchere.cod'. ( $id!=0 ? '.id.'.$id : '') .']');

        $this->form_validation->set_rules('nume', 'Nume', 'trim|required|xss_clean');

        $this->form_validation->set_rules('tip', 'Tip', 'trim|required');

        $this->form_validation->set_rules('valoare', 'Valoare', 'trim|required|numeric');

        $this->form_validation->set_rules('data_expirare', 'Data expirare', 'trim|required');

        $this->form_validation->set_rules('id_produs', 'ID produs', 'trim|numeric');

        $this->form_validation->set_rules('caracter', 'Caracter', 'trim|required');

        $this->form_validation->set_rules('activ', 'Activ', 'trim|required|numeric');



        if ($this->form_validation->run() == TRUE) {

            $p = $this->input->post();



            if ($id == 0) {

                $this->db->set('cod', $p['cod']);

                $this->db->set('nume', $p['nume']);

                $this->db->set('tip', $p['tip']);

                $this->db->set('valoare', $p['valoare']);

                $this->db->set('data_expirare', $p['data_expirare']);

                $this->db->set('id_produs', $p['id_produs']);

                $this->db->set('data_adaugare', 'NOW()', false);

                $this->db->set('caracter', $p['caracter']);

                $this->db->set('activ', $p['activ']);

                if ($this->db->insert('vouchere')) {

                    $data['succes'] = "Voucherul a fost salvat cu succes.";

                    $this->form_validation->_field_data = array();

                }

            } else {

                $this->db->set('cod', $p['cod']);

                $this->db->set('nume', $p['nume']);

                $this->db->set('tip', $p['tip']);

                $this->db->set('valoare', $p['valoare']);

                $this->db->set('data_expirare', $p['data_expirare']);

                $this->db->set('id_produs', $p['id_produs']);

                $this->db->set('caracter', $p['caracter']);

                $this->db->set('activ', $p['activ']);

                $this->db->where('id', $id);

                if ($this->db->update('vouchere')) {

                    $data['succes'] = "Voucherul a fost salvat cu succes.";

                }

            }

        }



        if ($id == 0) {

            $this->adauga($data);

        } else {

            $this->editeaza($id, $data);

        }

    }



    public function sterge($id) {

        $data = array();



        $this->db->delete('vouchere', array('id' => $id));

        $data["succes"] = "Voucherul a fost sters cu succes";



        $this->index($data);

    }

    

}



/* End of file index_page.php */

/* Location: ./application/controllers/admin/index_page.php */