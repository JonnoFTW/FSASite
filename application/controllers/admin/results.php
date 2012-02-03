<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Results extends MY_Admin {

    function __construct(){
		parent::__construct();
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',true);
            $this->load->view('default',$this->data);
        }    
        $this->data['title'] .= " :: Event Results";
    }
    function index(){
         // Show list of events that have not been cancelled without entries
        $events = array();
        $this->table->set_heading('Date','Time','Name');
        
        foreach($this->_get_unentered() as $v) {
            $events[] = array($v['date'],$v['time'],anchor('admin/results/entry/'.$v['event_id'],"{$v['name']}"));
        }
        
        $this->data['events'] = $this->table->generate($events);
        $this->data['message'] = "The events listed here have no results entered for them yet. To update results of events with results already recorded, please use the link on the page of the event in the calendar view.".anchor("calendar/type/Local","You can view events here");
        $this->data['main_content'] .= $this->load->view('admin/events/list_events',$this->data,true);
        $this->load->view('default',$this->data);
	}
    
    function entry($id = False) {
        if($id) {
            // We have been blessed with an id
            // Check if this event exists
            $result = $this->db->select('events.event_id,name, date')->get_where('events',array('event_id'=>$id));
            if($result->num_rows() == 0) {
                // Show the list of events and a message saying that event doesn't exist
                $this->data['warning'] = "No such event exists!";
                $this->index();
                return;
            } else {
                $event = $result->row_array();
                $this->data['name'] = $event['name'].' '. $event['date'];
                $this->data['event_id'] = $event['event_id'];
                // Get all the entrants, tell the user they need to add someone 
                // as an entrant before their result can be entered
                $this->db->select('users.first_name , users.last_name, users.uid, users.licensed, results.res');
                $result = $this->db->join('users','users.uid = results.uid')->get_where('results',array('event_id'=>$id));
                $table = array();
                $this->table->set_heading(array("Name","Position","Licensed?"));
                foreach($result->result_array() as $v) {
                    $table[] = array("{$v['first_name']} {$v['last_name']}",form_input($v['uid'],$v['res']),$v['licensed'] == date("Y"));
                }
                $this->data['entrants'] = $this->table->generate($table);
                $this->data['main_content'] .= $this->load->view('admin/events/enter_results',$this->data,true);
            }
        } else {
            // No event_id given!
            $this->index();
            return;
        }
        $this->load->view('default',$this->data);
    }
    
    function add_event_result() {
        if(!$this->input->is_ajax_request()) {
            echo "You shouldn't be here";
            return;
        }
        if(!($data = $this->input->post('data'))){
            echo "Please actually send some data";
        } else {
            //var_dump($data);
            // validate
            $res = $this->db->select("CONCAT(`first_name`,' ',`last_name`) as `name`, `results`.`uid`",false)->join('users','users.uid = results.uid')->get_where('results',array('event_id'=>intval($data['event_id'])));
            $fencers = array();
            foreach($res->result_array() as $v) { 
                $fencers[$v['uid']] = $v['name'];
            }
            $c = 0;
            foreach($data['results'] as $v) {
                if(!isset($v['res'])) {
                    echo "Please provide a result for ".$fencers[$v['uid']];
                    $c++;
                }
            }
            if($c == 0){
                $this->db->where('event_id',$data['event_id']);
                $this->db->update_batch('results',$data['results'],'uid');
              //  echo $this->db->last_query();
                echo "\nUpdated ".$this->db->affected_rows()." results</br>";
            } else {
                echo "No results updated</br>";
            }
        }
        // get the input
        // should be able to take files etc.
        
        // validate input
        
        //insert the result
    }
    
}