<?php if( !defined( 'BASEPATH' ) ) exit('No direct script access allowed');

class Pagina_model extends CI_Model {

    public function get_pagina( $id_pagina ) {
        $this->db->from("pagini");
        $this->db->where("id", $id_pagina );
        $this->db->where("activ", 1 );

        return $this->db->get()->row_array();
    }

    public function get_pagina_by_slug( $slug_pagina ) {
        $this->db->from("pagini");
        $this->db->where("slug", $slug_pagina );
        $this->db->where("activ", 1 );

        return $this->db->get()->row_array();
    }

}

/* End of file pagina_model.php */
/* Location: ./application/models/pagina_model.php */
