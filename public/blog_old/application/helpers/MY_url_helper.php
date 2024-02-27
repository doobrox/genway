<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('build_url_string'))
{
    function build_url_string() {
        $CI =& get_instance();
        $p = $CI->input->post();
        if( empty( $p ) ) {
             $p = $CI->input->get();
        }

        $url_string = "";
        if( is_array($p) ) {
            foreach ($p as $key=>$val) {
                if( $key!='per_page' ) {
                    if( is_array($val) ) {
                        foreach( $val as $val2 ) {
                            $url_string .= "{$key}[]=" . urlencode($val2) ."&";
                        }
                    } else {
                        $url_string .= "{$key}=" . urlencode($val) ."&";
                    }
                }
            }
        }
        if(!strstr( $url_string, 'urlencode' ))
        $url_string .= "urlencode=1";

        return $url_string;
    }
}