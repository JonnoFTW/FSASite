<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MY_Admin {

    function __construct(){
		parent::__construct();
        $this->data['title'] .= " :: Manage the front page message";
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',null,true);
            $this->load->view('default',$this->data);
        }   
    }
    
    function home() {
        // Update the text on the home page
        $result = $this->db->get_where('pages',array('title'=>'home'));
        $this->data['page'] = $result->row_array();
        $this->data['main_content'] .=  $this->load->view('admin/page/update_home',$this->data,true);
        $this->load->view('default',$this->data);
    }
    function update() {
        // Get the json
        $values = array(
            'message' => $this->input->post('data'),
            'author' => $this->session->userdata('uid'),
            'updated'=> date("Y-m-d")
        );
        // update db
        $this->db->update('pages',$values,array('title'=>'home'));
        echo 'Success';
    }
    
}