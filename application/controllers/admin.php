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
	}
	function index(){
		$this->data['main_content'] .= $this->load->view('admin/admin',$this->data,true);
		$this->load->view('default',$this->data);
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
        
        }
        
        else {
            // Select a page/error
        }
        $this->load->view('default',$this->data);
    }
    function event_result($id = False) {
        if($id) {
            // We have been blessed with an id
            // Check if this event exists
            $result = $this->db->select('events.event_id')->get('events');
            $found = false;
            foreach($result->restult_array() as $i){
                if ($i['event_id']  == $id) {
                    $found = true; 
                    break;
                }
            }
            if(!$found) {
                // Show the list of events and a message saying that event doesn't exist
            }
            // Get all the entrants, tell the user they need to add someone 
            // as an entrant before their result can be entered
            $this->db->select('users.first_name , users.last_name, users.uid');
            $result = $this->db->join('users','users.uid = entrants.uid')->get_where('entrants',array('event_id'=>$id));
            $this->data['entrants'] = $this->table->generate($result);
            $this->data['main_content'] .= $this->load->view('admin/admin_show_entrants',$this->data,true);
        } else {
            // Show list of events that have not been cancelled without entries
            $this->data['main_content'] .= $this->load->view('admin/admin_show_events',$this->data,true);
        }
        $this->load->view('default',$data->true);
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
        $clubs = array();
        foreach($result->result_array() as $v) {
            $clubs[$v['short_name']] = $v['short_name'];
        }
        $clubs[''] = '';
        $this->data['clubs'] = $clubs;
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
}
?>
