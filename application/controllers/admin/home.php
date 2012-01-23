<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Admin {

    function __construct(){
		parent::__construct();	
    }
    function index(){
		$this->data['main_content'] .= $this->load->view('admin/admin',$this->data,true);
		$this->load->view('default',$this->data);
	}
    

}