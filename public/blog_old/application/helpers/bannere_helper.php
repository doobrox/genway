<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('afisare_bannere'))
{
    function afisare_bannere( $zona, $field = "" )
    {
        $CI =& get_instance();
        $CI->load->helper('html');
        
        $CI->db->from('bannere');
        $CI->db->where('zona', $zona);
        $field!="" ? $CI->db->where($field, 1) : "";
        $CI->db->where('NOW() BETWEEN data_start AND data_sfarsit', '', FALSE);
        $CI->db->where('activ', 1);
        $CI->db->order_by('RAND()', '');
        $CI->db->limit(1);
        $item = $CI->db->get()->row_array();
        
        if( !empty($item) ) {
            if( $item['imagine']!="" ) {
                $CI->db->set('id_banner', $item['id']);
                $CI->db->set('ip', $CI->input->ip_address() );
                $CI->db->set('data_adaugare', 'NOW()', FALSE );
                $CI->db->insert('bannere_afisari_clickuri');
                
                return anchor( base_url()."index_page/redirect_banner?id={$item['id']}&link=".urlencode($item['link']), img( base_url() . MAINSITE_STYLE_PATH . "images/bannere/{$item['imagine']}" ), "target='_blank'" );
            } else {
                return $item['text'];
            }
        }
        
        return false;
    }
}
