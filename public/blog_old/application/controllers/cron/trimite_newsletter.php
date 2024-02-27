<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
/*
 * CRON 1 MINUT
 * trimite cate un mail la fiecare minut cu newslleterele salvate in newsletters_queue,
 * apoi sterge din newsletters_queue
 * wget http://www.starpet.ro/cron/trimite_newsletter >/dev/null 2>&1
 */

class Trimite_newsletter extends CI_Controller {

    public function index() {
        $this->load->model("cron_model", "cronm");
        $this->load->model("user_model", "userm");
        $this->load->helper('setari');
        $this->load->library('email');
        
        $queue = $this->cronm->get_newsletter_queque();
        
        foreach ( $queue as $item ) {
            $user = $this->userm->get_user( $item['id_user'] );
            $newsletter = $this->cronm->get_newsletter( $item['id_newsletter'] );
            if( !empty( $user ) && !empty( $newsletter ) ) {
                $this->email->from(setare('EMAIL_CONTACT'), setare('TITLU_NUME_SITE'));
                $this->email->to($user['user_email']);
                $this->email->subject( $newsletter['titlu'] );
                
                $newsletter['continut'] = str_replace("/userfiles/", base_url() . "/userfiles/", $newsletter['continut']);
                
                $template['titlu'] = $newsletter['titlu'];
                $template['continut'] = $newsletter['continut'];
                $message = $this->load->view("template_email", $template, true);
                $this->email->message( $message );
                        
                if( $this->email->send() ) {
                    $this->db->delete("newsletters_queue", array( "id" => $item['id'] ));
                }
            }
        } 
    }
 
}