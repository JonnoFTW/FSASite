<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Admin {

    function __construct(){
		parent::__construct();	
    }
    function index(){
		$this->data['main_content'] .= $this->load->view('admin/admin',$this->data,true);
		$this->load->view('default',$this->data);
	}
    
    function pass_reset() {
        $reset = $this->input->post('reset');
        if($reset) {
            // reset their password using the input
            if($this->input->post('pass') == $this->input->post('pass_confirm')){
                $this->db->update('users',array('pass'=>crypt($this->input->post('pass'),'$2a$07$FdAQgn8nY8NdOqs9OIGIGA$')))->where('uid',$this->session->userdata('uid'));
                $this->data['main_content'] .= $this->load->view('admin/pass_reset_sucess',$this->data,true);
            }
            else {
                $this->data['warning'] = "There was a mismatch between the provded passwords. Your password not updated, please try again";
                $this->data['main_content'] .= $this->load->view('admin/pass_reset',$this->data,true);
            }
        } else {
            // Display the form
            $this->data['main_content'] .= $this->load->view('admin/pass_reset',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
}