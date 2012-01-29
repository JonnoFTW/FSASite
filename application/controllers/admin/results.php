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
        
        foreach($this::_get_unentered() as $v) {
            $events[] = array($v['date'],$v['time'],anchor('admin/results/entry/'.$v['event_id'],"{$v['name']}"));
        }
        
        $this->data['events'] = $this->table->generate($events);
        $this->data['main_content'] .= $this->load->view('admin/events/list_events',$this->data,true);
        $this->load->view('default',$this->data);
	}
    
    function entry($id = False) {
        if($id) {
            // We have been blessed with an id
            // Check if this event exists
            $result = $this->db->select('events.event_id,name, date')->get('events');
            $found = false;
            foreach($result->result_array() as $i){
                if ($i['event_id']  == $id) {
                    $found = true; 
                    $this->data['name'] = $i['name'].' '. $i['date'];
                    $this->data['event_id'] = $i['event_id'];
                    break;
                }
            }
            if(!$found) {
                // Show the list of events and a message saying that event doesn't exist
                $this->data['warning'] = "No such event exists!";
                $this::index();
                return;
            } else {
                // Get all the entrants, tell the user they need to add someone 
                // as an entrant before their result can be entered
                $this->db->select('users.first_name , users.last_name, users.uid, users.licensed');
                $result = $this->db->join('users','users.uid = results.uid')->get_where('results',array('event_id'=>$id));
                $table = array();
                $this->table->set_heading(array("Name","Position","Licensed?"));
                foreach($result->result_array() as $v) {
                    $table[] = array("{$v['first_name']} {$v['last_name']}",form_input($v['uid']),$v['licensed'] == date("Y"));
                }
                $this->data['entrants'] = $this->table->generate($table);
                $this->data['main_content'] .= $this->load->view('admin/enter_results',$this->data,true);
            }
        } else {
            $this::index();
            return;
        }
        $this->load->view('default',$this->data);
    }
    
    function add_event_result() {
        // get the input
        // should be able to take files etc.
        
        // validate input
        
        //insert the result
    }
    
}