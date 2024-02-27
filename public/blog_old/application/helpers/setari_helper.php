<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('setare'))
{
    function setare( $field )
    {
        $CI =& get_instance();
        
        $CI->db->select('valoare');
        $CI->db->from('setari');
        $CI->db->where('camp', $field);
        
        $query = $CI->db->get();
        
        return $query->num_rows() > 0 ? $query->row()->valoare : FALSE;
    }
}
