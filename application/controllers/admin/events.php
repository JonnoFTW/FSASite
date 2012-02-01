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
        
        $this->data['main_content'] .=  $this->load->view('admin/events/events_home',$this->data,true);
        $this->load->view('default',$this->data);
	}
    
    function add() {
        $this->data['main_content'] .=  $this->load->view('admin/events/add_events',$this->data,true);
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
    
    private function _get_possible_entrants($id) {
        // Return an array of people who can enter a given event 
        $res = $this->db->get_where('events',array('event_id'=>$id));
        if($res->num_rows() == 0){
            // No such event exists!
          //  echo $this->db->last_query();
           return null;
        } else {
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
            // Determine whether they are entered or not
            $this->db->join('results','results.uid = users.uid','left outer');//->where('`results`.`uid` IS NULL',null,false);
            $this->db->select('DISTINCT(`results`.`uid`) AS entered, `users`.`first_name`, `users`.`last_name`, `users`.`uid`',null,false);
            $res = $this->db->get('users');
            return $res->result_array();
        }
    }
    function add_entrants() {  
        // get the json
        if(!$this->input->is_ajax_request()) {
            echo "You shouldn't be here";
        }
        elseif(!($data = json_decode($this->input->post('entrants'),true))) {
            echo "Error . Could not decode json!";
        } else {
           // var_dump($data);
            $fencers = array();
            foreach($this->_get_possible_entrants($data['event_id']) as $k=>$v) {
                $fencers[$v['uid']] = $v;
            }
            foreach($data['fencers'] as $k=>$v) {
                if(isset($fencers[$v['uid']])) {
                    $name = $fencers[$v['uid']]['first_name'] .' '.  $fencers[$v['uid']]['last_name'];
                    $key = array('uid'=>$this->db->escape($v['uid']),'event_id'=>$this->db->escape($data['event_id']));
                  //  var_dump($v);
                    if($v['entered']) {
                        // Make sure they are entered
                        $this->db->query("INSERT IGNORE INTO `results` (`event_id`,`uid`) VALUES ({$key['event_id']},{$key['uid']})");
                        if($this->db->affected_rows())
                            echo "$name is now entered in this event</br>";
                        else 
                            echo "$name is already in this event!</br>";
                    } else {
                        // Remove them from entrants
                        $this->db->delete('results',array('uid'=>$v['uid'],'event_id'=>$data['event_id']));
                        if($this->db->affected_rows())
                            echo "$name is no longer entered in this event</br>";
                    }
                } else {
                    echo $name." is not elligible for this event </br>";
                }
            }
        }
        // insert it into the entrants for that compettition id
    }
        
    function entry($id = false) { 
        if($id) {
        
            // Check if comp actually exists 
            // show users eligible for comp with a checkbox
            // clubs should only be able to enter their own users, this will be in the insert function too.
            // should probably combine those 2 tables (entrants, results)
            if(($fencers = $this->_get_possible_entrants($id)) === null) {
                $this->data['warning'] = "No such event exists!";
                goto err;
            }
         //   var_dump($fencers);
            $this->data['title'] .= " :: Add Fencers to Event";
            $arr = array();
            foreach($fencers as $k=>$v){
                $ent = ($v['entered'] != null);
                $arr[] = array("{$v['first_name']} {$v['last_name']}".form_hidden('uid',$v['uid']),form_checkbox(array('name'=>'entered','checked'=>$ent,'value'=>'true'))); 
            }
            $this->data['event_id'] = $id;
            $this->table->set_heading("Name","Entered");
            $this->table->set_template(array('table_open'=>'<table id="fencers">'));
            $this->data['fencers'] = $this->table->generate($arr);
            $this->data['main_content'] .= $this->load->view('admin/events/comp_entry',$this->data,true);
        } else {
            // list competitions that do not yet have results
            // this should allow us to add competitors after the event has begun
            err:
            $this->data['title'] .= " :: Select Event to add Fencers to";
            $events = array();
            $this->table->set_heading('Date','Time','Name');
            foreach($this->_get_unentered() as $v) {
                $events[] = array($v['date'],$v['time'],anchor('admin/events/entry/'.$v['event_id'],"{$v['name']}"));
            }
        
            $this->data['events'] = $this->table->generate($events); 
            $this->data['main_content'] .= $this->load->view('admin/events/list_events',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
}