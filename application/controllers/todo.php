<?php
class Todo extends MY_Controller {
    function __construct(){
		parent::__construct();
		$this->data['title'] = 'Todo';
        $this->data['warn'] = false;
	}
	function index(){
        $this->db->order_by('done');
        $this->res = $this->db->get('todo');
        $this->data['todo'] = $this->res->result_array();
		$this->data['main_content']  = $this->load->view('todo',$this->data,true);
		$this->load->view('default',$this->data);
	}
    function finished(){
        if($this->input->post('pass') == 'dicks'){
            if($this->input->post('new')){
                $this->db->insert('todo',array('todo'=>htmlentities($this->input->post('new'))));
            }
            $this->db->select('id');
            $this->res = $this->db->get('todo');
            $result = $this->res->result_array();
            foreach($result as $row){
                $todos[] = $row['id'];
            }
            foreach($this->input->post() as $key=>$val){
                if(in_array($key,$todos)){
                    $this->db->where('id',$key);
                    $this->db->update('todo',array('done'=>true));
                }
            }
            $this->data['warn'] = 'Entries have been updated';
        }else{
            $this->data['warn'] = 'Try using the right password';
        }
        $this->index();
    }
}
?>