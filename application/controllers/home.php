<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->result = $this->db->query('SELECT * FROM news, users WHERE news.author = users.uid ORDER BY posted asc LIMIT 0,3');
		$this->data['news'] = $this->result->result_array();
		$this->data['main_content'] = $this->load->view('home',$this->data,true);
		$this->data['title'] = 'Home Page';
		$this->load->view('default',$this->data);
	}
}
