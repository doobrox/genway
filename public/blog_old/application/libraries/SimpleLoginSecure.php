<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



/**

 * SimpleLoginSecure Class

 *

 * Makes authentication simple and secure.

 *

 * Simplelogin expects the following database setup. If you are not using

 * this setup you may need to do some tweaking.

 *

 *

 *   CREATE TABLE `users` (

 *     `user_id` int(10) unsigned NOT NULL auto_increment,

 *     `user_email` varchar(255) NOT NULL default '',

 *     `user_pass` varchar(60) NOT NULL default '',

 *     `user_date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Creation date',

 *     `user_modified` datetime NOT NULL default '0000-00-00 00:00:00',

 *     `user_last_login` datetime NULL default NULL,

 *     PRIMARY KEY  (`user_id`),

 *     UNIQUE KEY `user_email` (`user_email`),

 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 *

 * @package   SimpleLoginSecure

 * @version   1.0.1

 * @author    Alex Dunae, Dialect <alex[at]dialect.ca>

 * @copyright Copyright (c) 2008, Alex Dunae

 * @license   http://www.gnu.org/licenses/gpl-3.0.txt

 * @link      http://dialect.ca/code/ci-simple-login-secure/

 */

class SimpleLoginSecure {

    var $CI;

    var $user_table = 'useri';



    /**

     * Create a user account

     *

     * @access	public

     * @param	string

     * @param	string

     * @param	bool

     * @return	bool

     */

    function create($user_email, $user_pass, $nume_complet, $telefon, $sex, $id_judet, $newsletter, $valid_str, $admin = 0, $valid = 0) {

        $this->CI =& get_instance();



        //Insert account into the database

        $data = array(

            'user_email' => $user_email,

            'user_pass' => md5($user_pass),

            'user_pass_clean' => $user_pass,

            'nume_complet' => $nume_complet,

            'telefon' => $telefon,

            'sex' => $sex,

            'id_judet' => $id_judet,

            'newsletter' => $newsletter,

            'valid_str' => $valid_str,

            'admin' => $admin,

            'valid' => $valid,

            'user_date' => date('Y-m-d H:i:s'),

            'user_modified' => date('Y-m-d H:i:s'),

        );



        $this->CI->db->set($data);



        if(!$this->CI->db->insert($this->user_table)) //There was a problem!

            return false;



        return true;

    }

    

    function update($user_email, $nume_complet, $telefon, $sex, $id_judet, $newsletter, $valid_str, $id, $admin = 0, $valid = 0) {

        $this->CI =& get_instance();





        //Insert account into the database

        $data = array(

            'user_email' => $user_email,

            'nume_complet' => $nume_complet,

            'telefon' => $telefon,

            'sex' => $sex,

            'id_judet' => $id_judet,

            'newsletter' => $newsletter,

            'admin' => $admin,

            'valid' => $valid,

            'user_modified' => date('Y-m-d H:i:s')

        );



        $this->CI->db->set($data);

        $this->CI->db->where('id', $id);



        if(!$this->CI->db->update($this->user_table)) //There was a problem!

            return false;



        return true;

    }



    function check_email_db( $user_email ) {

        $this->CI =& get_instance();



        //Check against user table

        $this->CI->db->where('user_email', $user_email);

        $query = $this->CI->db->get_where($this->user_table);



        if ($query->num_rows() > 0) //user_email already exists

            return true;



        return false;

    }

    

    function check_email_db_user( $user_email ) {

        $this->CI =& get_instance();



        $sessionUserEmail = $this->CI->session->userdata('user_email');



        //Check against user table

        $this->CI->db->where('user_email', $user_email);

        $this->CI->db->where('user_email <>', $sessionUserEmail );

        $query = $this->CI->db->get_where($this->user_table);



        if ($query->num_rows() > 0) //user_email already exists

            return true;



        return false;

    }



    function check_email_db_user_edit( $user_email ) {

        $this->CI =& get_instance();



        $sessionUserEmail = $this->CI->session->userdata('user_email');



        //Check against user table

        $this->CI->db->where('user_email', $user_email);

        $this->CI->db->where('user_email <>', $sessionUserEmail );

        $query = $this->CI->db->get_where($this->user_table);



        if ($query->num_rows() > 0) //user_email already exists

            return true;



        return false;

    }



    /**

     * Login and sets session variables

     *

     * @access	public

     * @param	string

     * @param	string

     * @return	bool

     */

    function login($user_email = '', $user_pass = '', $admin = 0, $temp_login = false) {

        $this->CI =& get_instance();



        if($user_email == '' OR $user_pass == '')

            return false;



        

        //Check if already logged in

        if($this->CI->session->userdata('user_email') == $user_email)

            return true;

        

        //Check against user table

        $this->CI->db->where('user_email', $user_email);

        

        if( !$temp_login ) {

            $this->CI->db->where('valid', 1);

        }

        if( $admin==1 ) {

            $this->CI->db->where('admin', 1);

        }

        

        $query = $this->CI->db->get_where($this->user_table);



        if ($query->num_rows() > 0) {

            $user_data = $query->row_array();

            

            if( md5( $user_pass . trim( $user_data['user_pass_salt'] ) )!=$user_data['user_pass'] )

                return false;



            /*//Destroy old session

            $this->CI->session->sess_destroy();



            //Create a fresh, brand new session

            $this->CI->session->sess_create();*/

            $this->CI->session->unset_userdata('user_email');

            $this->CI->session->unset_userdata('user');

            $this->CI->session->unset_userdata('logged_in');



            $this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET user_last_login = NOW() WHERE id = ' . $user_data['id']);

            

            //Set session data

            unset($user_data['user_pass']);

            $user_data['user'] = $user_data['user_email']; // for compatibility with Simplelogin

            $user_data['logged_in'] = true;

            $this->CI->session->set_userdata($user_data);



            return true;

        }

        else {

            return false;

        }



    }



    /**

     * Logout user

     *

     * @access	public

     * @return	void

     */

    function logout() {

        $this->CI =& get_instance();



        $this->CI->session->unset_userdata('id');
        
        $this->CI->session->unset_userdata('user_email');

        $this->CI->session->unset_userdata('user');

        $this->CI->session->unset_userdata('logged_in');

    }



    /**

     * Delete user

     *

     * @access	public

     * @param integer

     * @return	bool

     */

    function delete($user_id) {

        $this->CI =& get_instance();



        if(!is_numeric($user_id))

            return false;



        return $this->CI->db->delete($this->user_table, array('id' => $user_id));

    }



    function is_logat() {

        $this->CI =& get_instance();



        $user_email = $this->CI->session->userdata('user_email');

        if( $user_email!="" ) {

            $this->CI->db->where("user_email", $user_email);

            $query = $this->CI->db->get( $this->user_table );

            return $query->num_rows();

        }

        return false;

    }



    function is_admin() {

        if( $this->is_logat() ) {

            return $this->CI->session->userdata('admin')==1 ? TRUE : FALSE;

        }

        

        return false;

    }



    function is_valid() {

        if( $this->is_logat() ) {

            $this->CI->db->where("id", $this->CI->session->userdata('id'));

            $get = $this->CI->db->get("useri");

            

            return $get->num_rows()>0 ? $get->row()->valid : FALSE;

        }

        

        return false;

    }

    

    function validare( $cod_validare ) {

        $this->CI =& get_instance();



        $this->CI->db->where('cod_validare', $cod_validare);

        $query = $this->CI->db->get( 'useri' );

        if( $query->num_rows()>=1 ) {

            $this->CI->db->set( 'valid', 1 );

            $this->CI->db->set( 'user_pass_clean', '' );

            $this->CI->db->where( 'cod_validare', $cod_validare );

            $this->CI->db->update( 'useri' );



            $this->CI->db->select("id, nume, prenume");

            $this->CI->db->where( 'cod_validare', $cod_validare );

            $this->CI->db->from( 'useri' );

            $user = $this->CI->db->get()->row();

            

            return true;

        }

        return false;

    }



    function check_user_pass( $user_pass ) {

        $this->CI =& get_instance();



        $this->CI->db->where("id", $this->CI->session->userdata('id'));

        $query = $this->CI->db->get( $this->user_table );

        if( $query->num_rows()>0 ) {

            $user_data = $query->row_array();

            if( md5($user_pass)==$user_data['user_pass'] )

                return true;

        }



        return false;

    }



    function update_user_pass( $user_id, $user_pass ) {

        $this->CI =& get_instance();



        $this->CI->db->set("user_pass", md5($user_pass));

        $this->CI->db->where("id", $user_id);

        if( $this->CI->db->update( $this->user_table ) ) {

            return true;

        }



        return false;

    }



}



/* End of file SimpleLoginSecure.php */

/* Location: ./application/libraries/SimpleLoginSecure.php */

