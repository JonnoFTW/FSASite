<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct(){
		parent::__construct();
        $this->load->helper('text');
	}
	public function index(){
		$result = $this->db->query('SELECT * FROM news, users WHERE news.author = users.uid ORDER BY posted desc LIMIT 0,3');
		$this->data['news'] = $result->result_array();
        $result = $this->db->order_by("date","asc")->get('events',10);
        $this->data['events'] = $result->result_array();
        
		$this->data['main_content'] = $this->load->view('home',$this->data,true);
		$this->data['title'] = 'Home Page';
		$this->load->view('default',$this->data);
	}
}
