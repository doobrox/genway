<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('check_captcha'))
{
    function check_captcha( $captcha ) {
        $CI =& get_instance();
        $expiration = time()-7200;
        $CI->db->where( "captcha_time < ", $expiration );
        $CI->db->delete( "captcha" );

        $CI->db->from( "captcha" );
        $CI->db->where( "word", $captcha );
        $CI->db->where( "ip_address", $CI->input->ip_address() );
        $CI->db->where( "captcha_time > ", $expiration );

        if ($CI->db->count_all_results() == 0) {
            return false;
        }
        
        return true;
    }
}

if ( ! function_exists('captcha_insert_db'))
{
    function captcha_insert_db( $captcha ) {
        $CI =& get_instance();

        $captcha_db = array(
            'captcha_time' => $captcha['time'],
            'ip_address' => $CI->input->ip_address(),
            'word' => $captcha['word']
        );
        $CI->db->insert('captcha', $captcha_db);
    }
}

if ( ! function_exists('clear_captcha'))
{
    function clear_captcha( $captcha ) {
        $CI =& get_instance();

        $CI->db->where( "word", $captcha );
        $CI->db->where( "ip_address", $CI->input->ip_address() );
        $CI->db->delete( "captcha" );
    }
}
