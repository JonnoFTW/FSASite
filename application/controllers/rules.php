<?php
class Rules extends MY_Controller {
    function __construct(){
		parent::__construct();
		$this->data['title'] = 'Rules';
		$this->res = $this->db->query("SELECT title,brief FROM rules");
		$this->data['side'] = $this->res->result_array();
		$this->data['main_content'] = $this->load->view('rules/rules_side',$this->data,true);
	}
	function index(){
		$this->data['main_content']  .= $this->load->view('rules/rules_main',$this->data,true);
		$this->load->view('default',$this->data);
	}
	function type($type = false){
		$type = str_replace('-', ' ',$type);
		$res = $this->db->get_where("rules",array("title"=>$type));
		$this->data['res'] = $res->row_array();
		$this->data['title'] .= ' ::  '.$this->data['res']['title'];
		$this->data['main_content']  .= $this->load->view('rules/rules',$this->data,true);
		$this->load->view('default',$this->data);
	}
}
?>
