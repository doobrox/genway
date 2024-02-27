<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('breadcrumbs'))
{
    function display_breadcrumbs( $breadcrumbs = array() )
    {   
        $CI =& get_instance();
        
        foreach ($breadcrumbs as $key => $link) {
            if ($breadcrumbs[$key] == '') {
                unset($breadcrumbs[$key]);
            }
        }
        
        $nr = count( $breadcrumbs );
        $output = "";
        for( $i=0; $i<$nr; $i++ ) {
            if( empty( $breadcrumbs[$i] ) ) continue;
            
            if( $CI->uri->segment(1)=="admin" ) {
                $separator = ($i<$nr-1 ? ' <div class="breadcrumb_divider"></div> ' : "");
            } else {
            $separator = "\r\n"; //($i<$nr-1 ? ' / ' : "");
            }
            if( $breadcrumbs[$i]['link']=="" || $i==$nr-1 ) {
                if( $CI->uri->segment(1)=="admin" ) {
                    $output .= "<a class='current'>{$breadcrumbs[$i]['titlu']}</a>" . $separator;
                } else {
                    $output .= "<span class='pagina-curenta'>{$breadcrumbs[$i]['titlu']}</span>" . $separator;
                }
            } else {
                $output .= anchor( $breadcrumbs[$i]['link'], $breadcrumbs[$i]['titlu'], array("title"=>strip_tags( $breadcrumbs[$i]['titlu'] ), "class"=>isset($breadcrumbs[$i]['class']) ? $breadcrumbs[$i]['class']  : "") ) . $separator;
            }
        }
        
        return $output;
    }
}
