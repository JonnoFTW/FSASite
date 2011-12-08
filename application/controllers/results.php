<?php
class Results extends MY_Controller { 
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Results';
		$this->data['main_content']  = $this->load->view('results/results_search','',true);
	}
	public function index()	{
		$this->data['main_content']  .= $this->load->view('results/results_main','',true);
		$this->load->view('default',$this->data);
	}
    public function event($id = False){
    
        $id = (int)$id;
		$result = $this->db->get_where("results",array("event_id"=>$id));
		if(!$id || $result->num_rows() == 0){
			$this->data['main_content']  .= $this->load->view('results/results_error','',true);
		}
		else{
			$this->data['result'] = $result->row_array(); // Table of competition results
            if(date() > $this->data['result']['date'])
                $this->data['main_content'] .= $this->load->view('results/result',$this->data,true);
            else
                $this->data['main_content'] .= $this->load->view('results/entrants',$this->data,true);
		}
		$this->load->view('default',$this->data);
        
    }
    public function search(){
        $this->db->query("SELECT * FROM results ORDER BY date");
        
        $this->data['main_content'] .= $this->load->view('results/result',$this->data,true);
        $this->load->view('default',$this->data);
    }

}
?>
