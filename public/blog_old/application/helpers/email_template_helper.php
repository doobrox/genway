<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('email_template')) {
    function email_template($id, $field, $info = array()) {
        $CI = & get_instance();

        $CI->db->select($field);
        $CI->db->from('email_template');
        $CI->db->where('id', $id);

        $query = $CI->db->get();
        $result = $query->num_rows() > 0 ? $query->row()->$field : FALSE;
        
        if( is_array( $info ) && !empty( $info ) ) {
            $search = array();
            $replace = array();
            foreach( $info as $key=>$val ) {
                $search[] = $key;
                $replace[] = $val;
            }
            
            $result = str_replace( $search, $replace, $result );
        }
        
        return $result;
    }
}