<?php
class Info extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Info';
	}
	public function index()	{
        $result = $this->db->get_where('forms',array('type'=>'res'));
        $this->data['res'] = $result->result_array();
        $result = $this->db->get_where('forms',array('type'=>'comp'));
        $this->data['comp'] = $result->result_array();

		$this->data['main_content'] = $this->load->view('info/resources',$this->data,true);
		$this->load->view('default',$this->data);
	}
}
?>
