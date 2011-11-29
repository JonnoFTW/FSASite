<?php
class Clubs extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Clubs';
	}
	public function index()	{
		$this->db->from('users')->join('clubs','clubs.clubid = users.uid');
		$this->result = $this->db->get();
		$this->data['club'] = $this->result->result_array();
		$this->data['main_content'] = $this->load->view('clubs/clubs',$this->data,true);
		$this->load->view('default',$this->data);
	}
}
?>
