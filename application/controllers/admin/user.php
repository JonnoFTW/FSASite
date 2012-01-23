<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MY_Admin {

    function __construct(){
		parent::__construct();	
        $this->data['title'] .= ':: Manage Users';
    }
    
    function index($filter = false) {
        $this->db->select('short_name')->from('clubs');
        $result = $this->db->get();
        $this->db->from('users');
        $this->db->order_by('users.last_name','desc');
        $this->db->join('clubs','users.clubid = clubs.clubid','left');
        $this->db->join('user_level','user_level.uid = users.uid','left');
        $this->db->select('users.uid, last_name, first_name, users.email, users.phone, users.licensed,clubs.short_name as club');
        if($filter) {
            if($filter == 'licensed')
                $this->db->where('users.licensed',date("Y"));
            elseif($filter == 'unlicensed')
                $this->db->where('users.licensed !=',date("Y"))->or_where('`users`.`licensed` IS NULL',null,false);
            elseif($filter == 'clubs')
                $this->db->where('`users`.`uid` = `users`.`clubid`',null,false);
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
            $v['last_name'] = anchor('admin/user/id/'.$v['uid'],$v['last_name']);
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
        
        $this->load->view('default',$this->data);
    }
    function filter($filter = false) {
        $this::index($filter);
    }
    
    function id($id = false) {
        if($id) {
            $this->db->select('users.uid, last_name, first_name, users.email, users.phone, users.licensed,clubs.short_name, users.address_1, users.address_1,users.address_2, users.suburb, users.post_code, users.state, clubs.short_name as club, user_level.level as level, user_level.note as note');
            $this->db->join('clubs','clubs.clubid = users.clubid','left');
            $this->db->join('user_level','user_level.uid = users.uid','left');
            $this->db->where('users.uid',$id);
            $this->result = $this->db->get('users'); 
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
                    // club accounts can only update information of those people in their club
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
        else {
            // No user specified!
            $this->data['main_content'] .= "Please specify a user";
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
    function licenses() {
        // Show all users with their licenses,
        if($this->session->userdata('level') != 'executive') {
            // Not allowed!
            $this->data['main_content'] .= $this->load->view('admin/forbidden',$this->data,true);
        } else {
            $license_types = array(
                'j'=>'junior',
                's'=>'senior',
                'c'=>'coach',
                ''=>'');
            $this->db->select('users.uid, users.first_name, users.last_name, users.licensed, users.license_type');
            $this->db->join('clubs','clubs.clubid = users.uid','left')->where("`clubs`.`clubid` is null",NULL,FALSE);
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
    
}