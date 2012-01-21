<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends MY_Admin {

    function __construct(){
		parent::__construct();
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',true);
            $this->load->view('default',$this->data);
        }        
    }
    
    function index() {
        // List forms etc.
        $this->load->view('default',$this->data);
    }
    function resources(){
        if($this){
            // File is being uploaded
            $data = array(
                'link'=>'form',
                'description'=>'Some worksheet',
                'name'=>'Some name',
                'type'=>'res or comp'
                );
            $this->db->insert('forms',$data);
            $this->load->view('admin',$this->data);
        }else{
            // Display form
            $this->data['main_content'] .= $this->load->view('admin/form_upload',true);
        }
    
}
    