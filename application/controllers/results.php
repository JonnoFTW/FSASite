<?php
class Results extends MY_Controller { 
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Results';
		$this->data['main_content']  = $this->load->view('results/results_search',$this->data,true);
	}
	public function index()	{
		$this->data['main_content']  .= $this->load->view('results/results_main',$this->data,true);
		$this->load->view('default',$this->data);
	}
    public function event($id = False){
        $id = (int)$id;
        $result = $this->db->get_where("events",array("event_id"=>$id));
        if($result->num_rows() == 0) {
            // Event does not exist
            $this->data['main_content'] .= $this->load->view('results/missing_event',$this->data,true);
        }
        else{
            $event = $result->row_array();
            $this->data['name'] = $event['name'];
            // Show the entrants and their result if it is set
            $this->db->select("users.first_name,users.last_name,users.uid, results.res");
            $this->db->order_by('results.res','asc');
            $this->db->join('users','results.uid = users.uid');
            $result = $this->db->get_where("results",array("event_id"=>$id));
            $entrants = array();
            foreach($result->result_array() as $k=>$v) {
                $entrants[] = array($this->_addOrdinal($v['res']),anchor("results/user/{$v['uid']}","{$v['first_name']} {$v['last_name']}"));
            }
            $this->table->set_heading('Position', 'Name');
            $this->data['entrants'] = $this->table->generate($entrants);
            $this->data['main_content'] .= $this->load->view('results/entrants',$this->data,true);
		}
		$this->load->view('default',$this->data);
    }
    
    public function user($id = false) {
        // Show the results of a given user
        if($id) {
            // Check if the user exists!
            $result = $this->db->get_where('users',array('uid'=>$id));
            if($result->num_rows() == 0) {
                // doesn't exist
                $this->data['main_content'] .= $this->load->view('results/user_not_found',null,true);
            } else {
                // Show the users results
                $this->data['fencer'] = $result->row_array();
                $this->db->join('events','events.event_id = results.event_id');
                $result = $this->db->get_where('results',array('results.uid'=>$id)); 
                $event = array();
                foreach($result->result_array() as $v) {
                    $event[] = array(anchor("results/event/{$v['event_id']}",$v['name']),$v['res']);
                }
                $this->table->set_heading('Event Name','Rank');
                $this->data['events'] = $this->table->generate($event);
                $this->data['main_content'] .= $this->load->view('results/user',$this->data,true);
            }
        }
        else {
            // Please give a user
            $this->data['main_content'] .= $this->load->view('results/user_not_found',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
    
    public function search(){
    // Search by event or by name or by club etc.
        if($name = $this->input->post('name')) {
            // Searching through fencers
            $this->db->like("CONCAT(users.first_name,' ',users.last_name)",$name);
            $this->db->order_by('users.last_name','asc');
            $result = $this->db->get('users');
            $this->data['fencers'] = $result->result_array();
        }
        else  {
            // Searching through events
               
            $this->db->join("events","events.event_id = results.event_id");
            $this->db->order_by("events.date","DESC");
            $result = $this->db->get("results");
            $this->data['events'] = $result->result_array();
        }
        $this->data['query'] = $this->db->last_query();
        $this->data['main_content'] .= $this->load->view('results/results_search_results',$this->data,true);
        $this->load->view('default',$this->data);
    }
    

}
?>
