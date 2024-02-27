<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CRON o data pe luna pe data de 1 a lunii, ora 00:00
 * resetare discount fidelitate pentru toti clientii
 * wget http://www.genway.ro/cron/resetare_discount_fidelitate >/dev/null 2>&1
 */

class resetare_discount_fidelitate extends CI_Controller {
    public function index() {
        $this->db->set("discount_fidelitate", 0);
        $this->db->update("useri");
    }
}