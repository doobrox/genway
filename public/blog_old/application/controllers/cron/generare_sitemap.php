<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CRON saptamanal
 * generare sitemap-uri cu index
 * wget http://www.lenjeriidepatdeosebite.ro/cron/generare_sitemap >/dev/null 2>&1
 */

/*
 * linkuri spre :
 * fiecare categorie in parte
 * fiecare produs in parte (doar produsele in stoc ?????)
 * fiecare pagina
 */

class generare_sitemap extends CI_Controller {

    public function index() {
//        $this->output->enable_profiler(true);
        $this->load->helper('setari');
        $this->load->model('cron_model', 'cronm');
        
        $sitemaps = array();
        $limit = 3000;
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' ."\r\n" .
                   '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
        
        // PAGINI PRINCIPALE
        $sitemap .= '<url> 
                            <loc>'. base_url() .'</loc> 
                            <changefreq>daily</changefreq> 
                            <priority>1.0</priority> 
                        </url>'."\r\n";
        
        $sitemap .= '<url> 
                            <loc>'. site_url("noutati") .'</loc> 
                            <changefreq>daily</changefreq> 
                            <priority>1.0</priority> 
                        </url>'."\r\n";
        
        $sitemap .= '<url> 
                            <loc>'. site_url("promotii") .'</loc> 
                            <changefreq>daily</changefreq> 
                            <priority>1.0</priority> 
                        </url>'."\r\n";
        
        // PAGINI DIN BAZA DE DATE
        $items = $this->cronm->get_pagini();
        foreach( $items as $item ) {
            $furl = site_url($item['slug']);
            $sitemap .= '<url> 
                            <loc>'. $furl .'</loc> 
                            <changefreq>daily</changefreq> 
                            <priority>1.0</priority> 
                        </url>'."\r\n";
        }
        
        // CATEGORII
        $items = $this->cronm->get_categorii();
        foreach( $items as $item ) {
            $furl = $this->functions->make_furl_categorie( $item['id'] );
            $sitemap .= '<url> 
                            <loc>'. $furl .'</loc> 
                            <changefreq>daily</changefreq> 
                            <priority>1.0</priority> 
                        </url>'."\r\n";
        }
        
        // PRODUSE
        $items = $this->cronm->get_produse();
        foreach( $items as $item ) {
            $furl = $this->functions->make_furl_produs( $item['nume'], $item['id'] );
            $sitemap .= '<url> 
                            <loc>'. $furl .'</loc> 
                            <changefreq>daily</changefreq> 
                            <priority>1.0</priority> 
                        </url>'."\r\n";
        }

        $sitemap .= '</urlset>';
        
        $fop = fopen( dirname(__FILE__) . "/../../../sitemap.xml", "w+");
        fwrite($fop, $sitemap);
        fclose($fop);
    }
}