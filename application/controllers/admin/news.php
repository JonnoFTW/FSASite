<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Results extends MY_Admin {

    function __construct(){
		parent::__construct();	
        // check user level
        if($this->session->userdata['level'] != 'executive') {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',true);
            $this->load->view('default',$this->data);
        }
    }
    function index(){
		$this->data['main_content'] .= $this->load->view('admin/admin_news',$this->data,true);
		$this->load->view('default',$this->data);
	}
    

    function add_news() {
        // validate input
        if($this->input->post('title') && $this->input->post('Message')) {
            $vars = array(
                'title'=>htmlentities($this->input->post('title')),
                'message'=>htmlentities($this->input->post('Message')),
                'posted'=>date("Y-m-d H:i:s"),
                'author'=>$this->session->userdata('uid')
            );
            $this->db->insert('news',$vars);
            // show success view
            $this->data['main_content'] .= $this->load->view('admin/admin_news_success',$this->data,true);
        } else {
            // show error view
            $this->data['err'] = true;
            $this->data['main_content'] .= $this->load->view('admin/admin_news',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
}