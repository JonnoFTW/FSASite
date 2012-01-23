<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pass_reset extends MY_Admin {

    function __construct(){
		parent::__construct();	
        $this->data['title'] = "Reset Your Password";
    }
    
    function index() {
        $this->data['main_content'] .= $this->load->view('admin/pass_reset',$this->data,true);
        $this->load->view('default',$this->data);
    }
    
    function pass_reset() {
        $reset = $this->input->post('reset');
        // reset their password using the input
        if($this->input->post('pass') == $this->input->post('pass_confirm')){
            $this->db->update('users',array('pass'=>crypt($this->input->post('pass'),$this->pass_salt)))->where('uid',$this->session->userdata('uid'));
            $this->data['main_content'] .= $this->load->view('admin/pass_reset_sucess',$this->data,true);
        }
        else {
            $this->data['warning'] = "There was a mismatch between the provded passwords. Your password not updated, please try again";
            $this->data['main_content'] .= $this->load->view('admin/pass_reset',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
}