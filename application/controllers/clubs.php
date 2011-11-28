<?php
class Clubs extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Clubs';
	}
	public function index()	{
		$this->sql = "SELECT * FROM clubs";
		$this->result = $this->db->query($this->sql);
		$this->data['club'] = $this->result->result_array();
		$this->data['main_content'] = $this->load->view('clubs/clubs',$this->data,true);
		$this->load->view('default',$this->data);
	}
}
?>
