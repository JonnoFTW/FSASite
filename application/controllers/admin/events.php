<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Events extends MY_Admin {

    function __construct(){
		parent::__construct(); 
        if($this->session->userdata('level') != 'executive') {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',$this->data,true);
            $this->load->view('default',$this->data);
        }
    }
    function index(){
        // Give option of adding events, or adding competitors
        
        $this->data['main_content'] .=  $this->load->view('admin/events',$this->data,true);
        $this->load->view('default',$this->data);
	}
    
    function add() {
        $this->data['main_content'] .=  $this->load->view('admin/admin_events',$this->data,true);
        $this->load->view('default',$this->data);
    }
     function add_events() {
        //read in the incoming json
        $events = json_decode($this->input->post('events'),true);
        if(!$events) {
            echo "Could not decode json!";
            return;
        }
        // make sure all fields are valid
        $out = '';
        foreach($events as $k=>$i) {
            if($i['date'] == "" or $i['time'] == ""){
                $out .= "Please provide a valid date and time for event {$k}</br>"; 
            } else {
                $parts = explode("-",$i['date']);
               /* echo "event_date > Curr_date".(strtotime($i['date']) >  strtotime(date("Y-m-d")));
                echo "</br>curr_date ".date("Y-m-d")."</br>event_date ".$i['date']."</br>";
                echo "checkdate: ".checkdate($parts[1],$parts[2],$parts[0]);*/
                if(!checkdate($parts[1],$parts[2],$parts[0]) || $i['date'] < date("Y-m-d")){
                    $out .= "Please provide a valid date that is in the future for event {$k}</br>";
                }
                $parts = explode(':',$i['time']);
                if(($parts[1] >= 59) || ($parts[0] >= 23) || !date('G:i', strtotime($i['time']))) {
                    $out .= "Please provide a valid 24 hour time for event {$k}</br>";
                }
            }
        }
        if ($out != "") {
            echo "No events were added.</br>";
            echo $out;
            return;
        }
       // insert into db
        
        foreach($events as &$i) {
            $i['date'] .= ' '.$i['time'];
            unset($i['time']);
            if($i['gender'] == "M") $i['gender'] = "M";
            elseif ($i['gender'] == "F") $i['gender'] = "F";
            elseif ($i['gender'] == "O") $i['gender'] = "O";
            if($i['name'] == "") 
                $i['name'] = "{$i['category']} {$i['gender']} {$i['weapon']}";
            $this->db->insert('events',$i);
        }
        // return success or failure
        echo "Success . Added ".count($events). " events</br>\n ";
      //  var_dump($events);
    }
    
    function add_entrants() {
        // get the json

        // insert it into the entrants for that compettition id
    }
        
    function entry($id = false) { 
        if($id) {
            // Check if comp actually exists 
            // show users eligible for comp with a checkbox
            // clubs should only be able to enter their own users, this will be in the insert function too.
            // should probably combine those 2 tables (entrants, results)
            $res = $this->db->get_where('events',array('event_id'=>$id));
            if($res->num_rows() == 0){
                // No such event exists!
                $this->data['warning'] = "No such event exists!";
                goto err2;
            }
            $this->data['event'] = $res->row_array();
            switch($this->data['event']['gender']){
                case 'F':
                    $this->db->where('users.gender','F');
                    break;
                case 'M':
                    $this->db->where('users.gender','M');
                    break;
            }
            $cat = $this->data['event']['category'];
            if(in_array($cat,array('U11','U13','U15','U17','U20')) ){
                $date = date('Y-1-1');
                $this->db->where("YEAR(DATE_SUB('$date', INTERVAL TO_DAYS(`users`.`dob`) DAY)) <= ".substr($cat,1),null, false);
            }
            if($cat == 'Veteran'){
                $this->db->where('YEAR(DATE_SUB(`events`.`date`, INTERVAL TO_DAYS(`users`.`dob`))) >= 40',null,false);
            }
            if($this->session->userdata('level') == 'club'){
                $this->db->where('users.clubid',$this->session->userdata('uid'));
            }
            // exclude those users who are already entered
            $this->db->join('results','results.uid = users.uid','left outer')->where('`results`.`uid` IS NULL',null,false);
            $res = $this->db->get('users');
           // echo $this->db->last_query();
            $this->data['users'] = $res->result_array();
            $this->data['main_content'] .= $this->load->view('admin/comp_entry',$this->data,true);
        } else {
            // list competitions that do not yet have results
            // this should allow us to add competitors after the event has begun
            err2:
            $this->data['events'] = array();
            foreach($this::_get_unentered() as $v) {
                $this->data['events'][] = heading(anchor('admin/events/entry/'.$v['event_id'],"{$v['name']} {$v['date']}"),3);
            }
            $this->data['main_content'] .= $this->load->view('admin/show_events',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
}