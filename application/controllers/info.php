<?php
class Info extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Info';
	}
	public function index()	{
		$this->result = $this->db->query("SELECT * FROM forms where type = 'res'");
		$this->data['res'] = $this->result->result_array();
		$this->result = $this->db->query("SELECT * FROM forms where type = 'comp'");
		$this->data['comp'] = $this->result->result_array();
		$this->data['main_content'] = $this->load->view('info/resources',$this->data,true);
		$this->load->view('default',$this->data);
	}
}
?>
