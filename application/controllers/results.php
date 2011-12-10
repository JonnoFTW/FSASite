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
        $result = $this->db->get_where("events",array("event_id"=>$id));
        $this->data['event'] = $result->row_array();
        if(date("Y-M-D H:i:s") > $this->data['event']['date']) {
            // Show the results for the competition
            $result = $this->db->get_where("results",array("event_id"=>$id));
            $this->data['result'] = $result->result_array(); // Table of competition results
            if($result->num_rows() == 0)
                $this->data['main_content']  .= $this->load->view('results/results_error',null,true);
            else
                $this->data['main_content'] .= $this->load->view('results/result',$this->data,true);
        }
        else {
            // Show the entrants
            $this->db->select("users.first_name,users.last_name,users.uid");
            $this->db->get_where("entrants",array("event_id"=>$id));
            $this->db->join('users','entrants.uid = users.uid');
            $this->data['entrant'] = $result->result_array();
            $this->data['main_content'] .= $this->load->view('results/entrants',$this->data,true);
		}
		$this->load->view('default',$this->data);
        
    }
    
    public function user($id = false) {
        // Show the results of a given user
        if($id) {
            // Show
        }
        else {
            // Please give a user
            $this->data['main_content'] .= $this->load->view('results/entrants',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
    
    public function search(){
        $this->db->join("events","events.event_id = results.event_id");
        $this->db->order_by("events.date","DESC");
        $result = $this->db->get("results");
        $this->data['search_results'] = $result->result_array();
        $this->data['main_content'] .= $this->load->view('results/results_search_results',$this->data,true);
        $this->load->view('default',$this->data);
    }
    

}
?>
