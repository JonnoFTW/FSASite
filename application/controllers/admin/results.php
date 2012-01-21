<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Results extends MY_Admin {

    function __construct(){
		parent::__construct();	
    }
    function index(){
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',true);
            $this->load->view('default',$this->data);
        }    
	}
    
    function result_entry($id = False) {
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
                goto err;
            } else {
                // Get all the entrants, tell the user they need to add someone 
                // as an entrant before their result can be entered
                $this->db->select('users.first_name , users.last_name, users.uid, users.licensed');
                $result = $this->db->join('users','users.uid = entrants.uid')->get_where('entrants',array('event_id'=>$id));
                $table = array();
                $this->table->set_heading(array("Name","Position","Licensed?"));
                foreach($result->result_array() as $v) {
                    $table[] = array("{$v['first_name']} {$v['last_name']}",form_input($v['uid']),$v['licensed'] == date("Y"));
                }
                $this->data['entrants'] = $this->table->generate($table);
                
                $this->data['main_content'] .= $this->load->view('admin/enter_results',$this->data,true);
            }
        } else {
            // Show list of events that have not been cancelled without entries
            err:
            $this->data['events'] = array();
            foreach($this::_get_unentered() as $v) {
                $this->data['events'][] = heading(anchor('admin/result_entry/'.$v['event_id'],"{$v['name']} {$v['date']}"),3);
            }
            $this->data['main_content'] .= $this->load->view('admin/show_events',$this->data,true);
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