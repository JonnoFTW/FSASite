<?php
class RSS extends MY_Controller {
	function __construct(){
		parent::__construct();
	}
	public function index()	{
		$this->result = $this->db->query('SELECT * FROM news ORDER BY posted desc LIMIT  0, 20');
		$this->data['news'] = $this->result->result_array();
		$this->load->view('rss',$this->data);
	}
}
?>
