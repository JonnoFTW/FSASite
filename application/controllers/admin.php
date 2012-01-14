<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MY_Controller {
    function __construct(){
		parent::__construct();
    //    var_dump($this->session->all_userdata());
		$this->data['title'] = 'Administration';
        if(!$this->session->userdata('logged')){
            redirect('login');
        }
        else{
         //   $this->res = $this->db->query("SELECT * FROM user_level");
         //   $this->data['side'] = $this->res->result_array();
            $this->data['main_content'] = $this->load->view('admin/admin_side',$this->data,true);
        }
        $this->load->library('table');
        $this->load->library('form_validation');
        
        $clubs = array();
        $result = $this->db->get('clubs');
        foreach($result->result_array() as $k=>$v){
            $clubs[$v['clubid']] = $v['short_name'];
        }
        $clubs[''] = '';
        $this->data['clubs'] = $clubs;
        
	}
	function index(){
		$this->data['main_content'] .= $this->load->view('admin/admin',$this->data,true);
		$this->load->view('default',$this->data);
	}
    
    private function _get_unentered() {
        $res = $this->db->select('events.event_id, events.name, events.date')->order_by('date',"desc")->join('results','results.event_id = events.event_id','left outer')->where('results.event_id IS NULL',null,false)->get('events');
        return $res->result_array();
    }
	function enter(){ 
        // Enter competitions
        // Withdrawl
		
		$this->data['main_content']  .= $this->load->view('rules/rules',$this->data,true);
		$this->load->view('default',$this->data);
	}
    function mail(){
        // Manage mailing options
        // check the post to see if you need to update db
        // mailing on:
        //  -- comp entry
        //  -- comp cancellation
        //  -- news items
        return false;
    }
    function page($page = false) {
        if($this->session->userdata('level') != 'executive') {
            // User does not have the right priviliges!
        }
        elseif($page == "home") {
            // Update the front page
            $this->data['main_content'] .= $this->load->view('admin/admin_home',$this->data,true);
        } elseif($page == "news") {
            // Update the news
            $this->data['main_content'] .= $this->load->view('admin/admin_news',$this->data,true);
        } elseif($page == "calendar") {
            // Update or add calendar events
            $this->data['main_content'] .= $this->load->view('admin/admin_events',$this->data,true);
        } elseif($page == "forms") {
            // Add/update/delete the forms
        }
        
        else {
            // Select a page/error
        }
        $this->load->view('default',$this->data);
    }
    function licenses() {
        // Show all users with their licenses,
        if($this->session->userdata('level') != 'executive') {
            // Not allowed!
            $this->data['main_content'] .= $this->load->view('admin/admin_forbidden',$this->data,true);
        } else {
            $license_types = array(
                'j'=>'junior',
                's'=>'senior',
                'c'=>'coach',
                ''=>'');
            $this->db->select('users.uid, users.first_name, users.last_name, users.licensed, users.license_type');
            $this->db->join('clubs','clubs.clubid = users.uid','left')->where("clubs.clubid is null",NULL,FALSE);
            $this->db->order_by('users.last_name','desc');
            $result = $this->db->get('users');
            $user_table = array();
            $user_table[] = array('Last Name', 'First Name', 'Licensed for '.date("Y"), 'License Type');
            foreach($result->result_array() as $v) {
                $user_table[] = array($v['last_name'],$v['first_name'],form_checkbox('licensed','licensed',$v['licensed'] == date("Y")),form_dropdown('levels',$license_types,$v['license_type']));
            }
            $this->data['user_table'] = $this->table->generate($user_table);
            $this->data['main_content'] .= $this->load->view('admin/admin_licenses',$this->data,true);
        }
        $this->load->view('default',$this->data);
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
                if(!checkdate($parts[1],$parts[2],$parts[0]) || $i['date'] > date("Y-m-d")){
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
            if($i['gender'] == "M") $i['gender'] = "Mens";
            elseif ($i['gender'] == "F") $i['gender'] = "Womens";
            elseif ($i['gender'] == "O") $i['gender'] = "Mixed";
            if($i['name'] == "") 
                $i['name'] = "{$i['category']} {$i['gender']} {$i['weapon']}";
            $this->db->insert('events',$i);
        }
        // return success or failure
        echo "Success . Added ".count($events). " events</br>\n ";
      //  var_dump($events);
    }
    function add_news() {
        // check user level
        if($this->session->userdata['level'] != 'executive') {
            echo 'You cannot add news items';
            redirect('admin');
        }
        // validate input
        if($this->input->post('title') && $this->input->post('Message')) {
            $vars = array(
                'title'=>htmlentities($this->input->post('title')),
                'message'=>htmlentities($this->input->post('Message')),
                'posted'=>date("Y-m-d H:i:s"),
                'author'=>$this->session->userdata('uid')
            );
            $this->db->insert('news',$vars);
            // show success view
            $this->data['main_content'] .= $this->load->view('admin/admin_news_success',$this->data,true);
        } else {
            // show error view
            $this->data['err'] = true;
            $this->data['main_content'] .= $this->load->view('admin/admin_news',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }

    function user($id = false,$filter = false) {
        $this->db->select('short_name')->from('clubs');
        $result = $this->db->get();
        $this->db->from('users');
        $this->db->order_by('users.last_name','desc');
        $this->db->join('clubs','users.clubid = clubs.clubid','left');
        $this->db->join('user_level','user_level.uid = users.uid','left');
        if(!$id || $id == 'filter'){
        $this->db->select('users.uid, last_name, first_name, users.email, users.phone, users.licensed,clubs.short_name as club');
        if($id = 'filter') {
            if($filter == 'licensed')
                $this->db->where('users.licensed',date("Y"));
            elseif($filter == 'unlicensed')
                $this->db->where('users.licensed !=',date("Y"))->or_where('users.licensed IS NULL',null,false);
            elseif($filter == 'clubs')
                $this->db->where('users.uid = users.clubid',null,false);
        }
        if ($this->session->userdata('level') == 'club') {
            // Show users that belong to club
            $this->db->where('users.clubid',$this->session->userdata[]);
            $this->result = $this->db->get();
            $this->data['users'] = $this->result->result_array();
        }
        else {
            // Show all users
            $this->result = $this->db->get();
            $this->data['users'] = $this->result->result_array();
        }
        foreach($this->data['users'] as &$v){
            $v['last_name'] = anchor('admin/user/'.$v['uid'],$v['last_name']);
            $v['email'] = mailto($v['email'],$v['email']);
            unset($v['uid']);
        }
        $this->table->set_template(array('table_open'=>'<table id="users" >'));
        $this->table->set_heading('Last Name','First Name','Email','Phone','Licensed','Club');
        $this->data['user_table'] = $this->table->generate($this->data['users']);
        $this->data['main_content'] .= $this->load->view('admin/admin_user_list',$this->data,true);
        $this->data['user'] = array(0);
        $this->data['new_user'] = true;
        $this->data['main_content'] .= $this->load->view('admin/admin_user',$this->data,true);
        } else {
        $this->db->select('users.uid, last_name, first_name, users.email, users.phone, users.licensed,clubs.short_name, users.address_1, users.address_1,users.address_2, users.suburb, users.post_code, users.state, clubs.short_name as club, user_level.level as level, user_level.note as note');
        $this->db->where('users.uid',$id);
        $this->result = $this->db->get(); 
        if($this->result->num_rows() > 0){
            // Show user info
            $this->data['users'] = $this->result->result_array();
            foreach($this->data['users'] as $u) {
                if($u['uid'] == $id){
                    $this->data['user'] = $u;
                    break;
                }
            }
            if($this->session->userdata['level'] != 'club' || $this->session->userdata['clubid'] == $this->data['user']['clubid']) {
                $this->data['main_content'] .= $this->load->view('admin/admin_user',$this->data,true);
            } else {
                // User is not in their club or they are not admin 
                $this->data['main_content'] .= $this->load->view('admin/admin_user_error',$this->data,true);
            }
        } else {
            $this->data['user'] = null;
            $this->data['main_content'] .= $this->load->view('admin/admin_user',$this->data,true);
        }
     }
        $this->load->view('default',$this->data);
    }
    function new_user() {
        // Add a new user!
        $required = array("last_name","first_name","email","phone");
        $vals = array(
                'last_name'=>$this->input->post('last_name'),
                'first_name'=>$this->input->post('first_name'),
                'email'=>$this->input->post('email'),
                'phone'=>$this->input->post('phone'),
                'address_1'=>$this->input->post('addr1'),
                'address_2'=>$this->input->post('addr2'),
                'suburb'=>$this->input->post('suburb'), 
                'post_code'=>$this->input->post('post_code'),
                'state'=>$this->input->post('state'), #mailing address out of state?!
            //    'pass'=>encrypt($this->input->post('pass')), HAve another page to manage passwords
                'clubid'=> $this->input->post('clubid'), #get the clubid into here, from a query
           //     'licensed'=>$this->input->post('licensed'), #should be a date("Y") thing, but only for admin
        );
        if($this->session->userdata('level') != 'executive'){
            echo "You cannot make new clubs or executives!";
            return;
        }
        $err = false;
        if(!filter_var($vals['email'],FILTER_VALIDATE_EMAIL)){
            echo "Please provide a properly formatted email";
            $err = true;
        }
        foreach($required as $v){
            if(!$vals[$v]) {
                echo "Please provide a {$v}</br>";
                $err = true;
            }
        }
        var_dump($vals);
        if($vals['clubid'] || !array_key_exists($vals['clubid'],$this->data['clubs'])) {
            echo "Please select a valid club</br>";
            $err = true;
        }
        if(!$err){
            if($this->input('type') == 'club') {
                if($this->input('short_name') && $this->input('description')){
                    $this->db->insert('users',$vals);
                    $this->db->insert('clubs',array("clubid"=>$this->db->insert_id(),"short_name"=>$this->input('short_name'),"description"=>$this->input('description')));
                } else {
                    echo "Please provide a short name and description for the club</br>";
                }
            } else {
                $this->db->insert('users',$vals);
                echo "Success! User was created";
            }
            // If user to be made is a club, insert them into the clubs as well, 
            // allong with the stuff which should be done when making a club
        }
        else
            echo "User was not created<br/>";
    }
    
    function delete_user($id = false) {
        // Delete a user, 
    }
    
    function update_user($id = false){
        // Should create a user as well as update, because of brevity.
        
        // Only a an executive should be able to create new users.
        $result = $this->db->select('uid')->from('users')->get();
        if($this->session->userdata('level') == 'club'){
            $this->db->where('users.clubid',$this->session->userdata('uid'));
        }
        $users = $result->result_array();
    //    var_dump($users);
        $this->contains = 0;
        foreach($users as $u){
            if($id == $u['uid']){
                $this->contains = 1;
                break;
            }
        }
        if($this->contains == 1) {
            // Accept form and validate for current user level
            $this->club = null;
            if($this->input->post('club')) {
                $this->db->from('clubs');
                $this->db->where('short_name',$this->input('club'));
                $this->result = $this->db->get();
                $this->club = $this->result->row_array();
            }
            //check confirm new pword box
            if($this->input->post('pass') && ($this->session->userdata('uid') == $id)){
                //Check if they are even allowed to have a pass
                if(true);
            }
            $vals = array(
                'last_name'=>$this->input->post('last_name'),
                'first_name'=>$this->input->post('first_name'),
                'email'=>$this->input->post('email'),
                'phone'=>$this->input->post('phone'),
                'address_1'=>$this->input->post('addr1'),
                'address_2'=>$this->input->post('addr2'),
                'suburb'=>$this->input->post('suburb'),
                'post_code'=>$this->input->post('post_code'),
                'state'=>$this->input->post('state'), #mailing address out of state?!
                'pass'=>encrypt($this->input->post('pass')),
                'clubid'=> $this->club['clubid'], #get the clubid into here, from a query
                'licensed'=>$this->input->post('licensed'), #should be a date("Y") thing, but only for admin
            );
            $this->update('users',$vals,array('uid',$id));
        } else {
            // Specify a valid user!
            $this->data['user'] = null;
            $this->data['main_content'] .= $this->load->view('admin/admin_user',$this->data,true);
            
        }
        $this->load->view('default',$this->data);
    }
    function resources(){
        if($this){
            // File is being uploaded
            $data = array(
                'link'=>'form',
                'description'=>'Some worksheet',
                'name'=>'Some name',
                'type'=>'res or comp'
                );
            $this->db->insert('forms',$data);
            $this->load->view('admin',$this->data);
        }else{
            // Display form
            $this->data['main_content'] .= $this->load->view('admin/form_upload',true);
        }
    }
    
    function rules($rule = false) {
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .= "You are not permitted to access this!";
            
        } else {
            $res = $this->db->get("rules");
            $this->data['rules'] = $res->result_array();
            
            if(!$rule) {
                // Show all the rules
                $this->data['main_content'] .= $this->load->view('admin/list_rules',$this->data,true);
            } else {
                //check the rule to edit actually exists!
                foreach($this->data['rules'] as $v) {
                    if($rule == $v['id']){ 
                        $this->data['rule'] = $v['id'];
                        break;
                    }
                }
                if(isset($this->data['rule'])){
                    // Load the rule into a form view
                    $this->data['main_content'] .= $this->load->view('admin/update_rule',$this->data,true);
                } else {
                    $this->data['warning'] = "No such rule exists!";
                    $this->data['main_content'] .= $this->load->view('admin/list_rules',$this->data,true);
                }
                
            }
        }
        $this->load->view('default',$this->data);
    }
    
    function update_rule() {
        // Read in the JSON and update
        if($info = $this->input->post('rule')){
            $vars = array();
          //  var_dump($info);
            foreach($info as $v) {
                $vars[$v["name"]] = $v["value"];
            }
            // Make sure everything is there first
            
            $valid = true;
            foreach(array('title','id','brief','description') as $v) {
                if(!isset($vars[$v]) && $vars[$v]) {
                    echo "Please provide a ".$v;
                    $valid = false;
                }
            }
            if($valid){
                $id = $vars['id'];
                unset($vars['id']);
                $this->db->update('rules',$vars,array("id"=>$id));
               // echo $this->db->last_query();
                echo "success";
            }
            else {
                echo "Rule was not updated";
            }
        } else {
            // error
            var_dump($this->input->post('rule'));
            echo "There was an error!";
        }   
    }

    
    function comp_entry($id = false) { 
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
                $this->db->where('YEAR(DATE_SUB(`events.date`, INTERVAL TO_DAYS(`users.dob`))) >= 40',null,false);
            }
            if($this->session->userdata('level') == 'club'){
                $this->db->where('users.clubid',$this->session->userdata('uid'));
            }
            // exclude those users who are already entered
            $this->db->join('entrants','entrants.uid = users.uid','left outer')->where('entrants.uid IS NULL',null,false);
            $res = $this->db->get('users');
            echo $this->db->last_query();
            $this->data['users'] = $res->result_array();
            $this->data['main_content'] .= $this->load->view('admin/comp_entry',$this->data,true);
        } else {
            // list competitions that do not yet have results
            // this should allow us to add competitors after the event has begun
            err2:
            $this->data['events'] = array();
            foreach($this::_get_unentered() as $v) {
                $this->data['events'][] = heading(anchor('admin/comp_entry/'.$v['event_id'],"{$v['name']} {$v['date']}"),3);
            }
            $this->data['main_content'] .= $this->load->view('admin/show_events',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
    
    function add_entrants() {
        // get the json

        // insert it into the entrants for that compettition id
    }
    function pass_reset() {
        $reset = $this->input->post('reset');
        if($reset) {
            // reset their password using the input
            if($this->input->post('pass') == $this->input->post('pass_confirm')){
                $this->db->update('users',array('pass'=>crypt($this->input->post('pass'),'$2a$07$FdAQgn8nY8NdOqs9OIGIGA$')))->where('uid',$this->session->userdata('uid'));
                $this->data['main_content'] .= $this->load->view('admin/pass_reset_sucess',$this->data,true);
            }
            else {
                $this->data['warning'] = "There was a mismatch between the provded passwords. Your password not updated, please try again";
                $this->data['main_content'] .= $this->load->view('admin/pass_reset',$this->data,true);
            }
        } else {
            // Display the form
            $this->data['main_content'] .= $this->load->view('admin/pass_reset',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
    

}
?>
