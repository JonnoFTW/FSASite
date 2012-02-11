<?php
class Results extends MY_Controller { 
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'Results';
		$this->data['main_content']  = $this->load->view('results/results_search',$this->data,true);
        $this->data['logged'] = ($this->session->userdata('level') == 'executive');
        
	}
	public function index()	{
        $this->db->select("DISTINCT(`results`.`event_id`), `events`.`name` as Name ,`events`.`date` as Date, `events`.`gender` as Gender",null,false);
	    $res = $this->db->join('results','events.event_id = results.event_id')->order_by('events.date','desc')->get('events',10);
        //echo $this->db->last_query();
        $arr = array();
        $this->table->set_heading('Name','Date','Gender');
        foreach($res->result_array() as $v) {
            $arr[] = array(anchor('results/event/'.$v['event_id'],$v['Name']),$v['Date'],$v['Gender']);
        }
        $this->data['latest_results'] = $this->table->generate($arr);
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
            $this->db->select("users.first_name,users.last_name,users.uid, results.res, users.licensed");
            $this->db->order_by('results.res','asc');
            $this->db->join('users','results.uid = users.uid');
            $result = $this->db->get_where("results",array("event_id"=>$id));
            $entrants = array();
            foreach($result->result_array() as $k=>$v) {
                $e = array($this->_addOrdinal($v['res']),anchor("results/user/{$v['uid']}","{$v['first_name']} {$v['last_name']}"));
                if($this->session->userdata('logged')){
                    if($v['licensed'] == date("Y")){
                        $l = "Yes";
                    } else {
                        $l = "No"; 
                    }
                    $e[] = anchor('admin/user/id/'.$v['uid'],$l);
                }
                $entrants[] = $e;
            }
            $headings = array('Position', 'Name');
            if($this->session->userdata('logged'))
                $headings[] = 'Licensed in '.date("Y").'?';
            $this->table->set_heading($headings);
            $this->data['event_id'] = $id;
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
                    $parts = explode(' ',$v['date']);
                    $date = $parts[0];
                    $event[] = array($date,anchor("results/event/{$v['event_id']}",$v['name']),$v['res']);
                }
                $this->table->set_heading('Date','Event Name','Rank');
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
        $arr = array();
        if($name = $this->input->post('name')) {
            // Searching through fencers
            $this->db->like("CONCAT(users.first_name,' ',users.last_name)",$name);
            $this->db->order_by('users.last_name','asc');
            $result = $this->db->get('users');
            $this->table->set_heading('Name','Gender');
            foreach($result->result_array() as $v) {
                 $arr[] = array(anchor("results/user/{$v['uid']}","{$v['first_name']} {$v['last_name']}"),$v['gender']);
            }
        }
        else  {
            // Searching through events
               
            $this->db->join("events","events.event_id = results.event_id");
            if(in_array($this->input->post('category'),$this->data['CATEGORIES'])) {
                $this->db->where('category',$this->input->post('category'));
            }
            if(in_array($this->input->post('weapon'),$this->data['WEAPONS'])) {
                $this->db->where('weapon',$this->input->post('weapon'));
            }
            if($this->input->post('from')) {
                // Get all results after
                $this->db->where('events.date >=',$this->input->post('from'));
            }
            if($this->input->post('to')) {
                // Get all results after
                $this->db->where('events.date <=',$this->input->post('to'));
            }
            $this->db->order_by("events.date","DESC");
            $result = $this->db->get("results");
            foreach($result->result_array() as $v) {
                $arr[] = array(anchor("results/event/{$v['event_id']}","{$v['name']}"),$v['date'],$v['category'],$v['weapon']);
            }
            $this->table->set_heading('Name','Date','Category','Weapon');
        }
       // $this->data['query'] = $this->db->last_query();
        $this->data['results'] = $this->table->generate($arr);
        $this->data['main_content'] .= $this->load->view('results/results_search_results',$this->data,true);
        $this->load->view('default',$this->data);
    }
    

}
?>
