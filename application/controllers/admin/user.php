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
        $this->data['main_content'] .= $this->load->view('admin/users/user_list',$this->data,true);
        $this->data['user'] = array(0);
        $this->data['new_user'] = true;
        $this->data['main_content'] .= $this->load->view('admin/users/user_id',$this->data,true);
        
        $this->load->view('default',$this->data);
    }
    function filter($filter = false) {
        $this->index($filter);
    }
    private function _uuid() {
        // Get a UUID() from mysql
        $res = $this->db->query("SELECT UUID() as uid");
        $row = $res->row_array();
        return $row['uid'];
    }
    function id($id = false) {
        if($id) {
            $this->db->select('users.uid, last_name, first_name, users.email, users.dob , users.phone, users.licensed,clubs.short_name, users.address_1, users.address_1,users.address_2, users.suburb, users.post_code, users.state, (`clubs`.`clubid` = `users`.`uid` ) as `isClub` ,clubs.short_name as club, clubs.description as club_description, user_level.level as level, user_level.note as note');
            $this->db->join('clubs','clubs.clubid = users.clubid','left');
            $this->db->join('user_level','user_level.uid = users.uid','left');
            $this->db->where('users.uid',$id);
            $result = $this->db->get('users'); 
         //   echo $this->db->last_query();
            if($result->num_rows() > 0){
                // Show user info
                $this->data['user'] = $result->row_array();
                if($this->session->userdata('level') != 'club' || $this->session->userdata('clubid') == $this->data['user']['clubid']) {
                    // club accounts can only update information of those people in their club
                    $this->data['warning'] = "You may only view and update users from your club";
                    $this->data['main_content'] .= $this->load->view('admin/users/user_id',$this->data,true);
                } else {
                    // User is not in their club or they are not admin 
                    $this->data['warning'] = "You";
                    $this->data['main_content'] .= $this->load->view('admin/users/user_error',$this->data,true);
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
     /*   if($this->session->userdata('level') != 'executive'){
     // Who should be able to add new people?
            echo "You cannot make users!";
            return;
        } */
        var_dump($this->input->post());
        $required = array("last_name","first_name","phone"); 
        $uid = $this->_uuid();
        $vals = array(
                'uid' => $uid,
                'last_name'=>$this->input->post('last_name'),
                'first_name'=>$this->input->post('first_name'),
                'email'=>$this->input->post('email'),
                'phone'=>$this->input->post('phone'),
                'address_1'=>$this->input->post('addr1'),
                'address_2'=>$this->input->post('addr2'),
                'suburb'=>$this->input->post('suburb'), 
                'post_code'=>$this->input->post('post_code'),
                'state'=>$this->input->post('state'), #mailing address out of state?!
                'clubid'=> $this->input->post('club'),
                'dob'=> $this->input->post('dob')
        );
        $err = false;
        if(!filter_var($vals['email'],FILTER_VALIDATE_EMAIL)){
            echo "Please provide a properly formatted email<br/>";
            $err = true;
        }
        foreach($required as $v){
            if(!$vals[$v]) {
                echo "Please provide a {$v}</br>";
                $err = true;
            }
        }
        if($vals['clubid'] || !array_key_exists($vals['clubid'],$this->data['clubs'])) {
            echo "Please select a valid club<br/>";
            $err = true;
        }
        if(!$err){
            if($this->session->userdata('level') == 'executive'){
                // Can make any type of user
                if($this->input->post('type') == 'club'){
                    if($this->input->post('short_name') && $this->input->post('description')) {
                    // New clubs can only be made by execs
                        $this->db->insert('users',$vals);
                        $this->db->insert('clubs',array("clubid"=>$uid,"short_name"=>$this->input('short_name'),"description"=>$this->input('description')));
                        
                    } else {
                        echo "Please provide a short name and description for the club<br/>";
                    }
                } elseif($this->input->post('type') == 'executive') {
                    // make a new exec
                    $this->db->insert('users',$vals);
                    $this->db->insert('user_level',array("uid"=>$uid,'level'=>'executive','note'=>$this->input->post('note')));
                    //Make them an exec and notify them of this
                    $this->_make_exec($uid,$vals);
                    echo "Made a new executive";
                } else {
                    // We are making an 'other' user
                    $this->db->insert('users',$vals);
                }
            } elseif ($this->input->post('type') == 'other') {
                // A club is adding a new user, they must be 'other' type
                $this->db->insert('users',$vals);
            }
            echo "User was successfully created<br/> ";
        }  else {
            echo "User was not created<br/>";
        }
    }
    
    function delete_user($id = false) {
        // Delete a user, 
        // only executive should be able to do this
        if($this->session->userdata('level') == 'executive') {
            // Confirmation should be in user window through ajax
            if($this->input->is_ajax_request()) {
                echo "Do not visit this page directly.";
            }
            if(!$id) {
                echo "Please specify the id of the user to delete";
                return;
            }
            $this->db->delete('users',array('uid'=>$id));
            if($this->db->affected_rows()) {
                echo "Successfully deleted user. Their results are still visible though.";
            } else {
                echo "No such user exists!";
            }
        }
        else {
            echo "You are not permitted to perform this action";
        }
    }
    
    
    function update_user(){
        // Should be ajax only
        if(!$this->input->is_ajax_request()) {
            "Do not access this page directly";
            return;
        }
        $id = $this->input->post('uid');
        $type = $this->input->post('type');
        if(!$id) {
            //
            echo "Please specify a user";
            return;
        }
        
        // Only a an executive should be able to create new users.
        if($this->session->userdata('level') == 'club'){
            if($type != 'other' || $id != $this->session->userdata('uid')) {
                echo "You can only update the details of your club and the fencers in it";
                return;
            }
            $this->db->where('users.clubid',$this->session->userdata('uid'));
        }
        if($type == 'club') {
            echo "Clubs can only be made once";
            return;
        }
        $result = $this->db->get_where('users',array('uid'=>$id));
        if($result->num_rows() == 0) {
            echo "Please specify a user that exists. Club accounts can only update users from their club";
            return;
        }
        
        $result = $this->db->get_where('user_level',array('uid'=>$id));
        if($result->num_rows() != 0) {
            $ul = $result->row_array();
            if($this->session->userdata('level') == 'executive' && $ul['level'] == 'executive' && $type != 'executive') {
                /// Clubs will not become executive, ever
                // They are no longer executive
                $this->_remove_exec($id);
                echo "User is no longer an executive</br>";
            }
        }
        // Making someone an exec
        if($type == 'executive' && $result->num_rows() == 0) {
            // Only an executive can get here, so it's aaaaaaaaaalright.
            $this->_make_exec($id);
            echo "User is now an executive</br>";
        }
        
        // We have a real user who we can update
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
            'licensed'=>$this->input->post('licensed') #should be a date("Y") thing, but only for admin
        );
  
        // A club should not be able to change what club it is in
        $res = $this->db->get_where('clubs',array('clubid'=>'uid'));
        if($res->num_rows() == 0) {
            // Update the users clubid
            // this should not be a field in the form 
            $vals['clubid'] = $this->input->post('club');
        } else {
            echo "You cannot change the club a club belongs to!";
        }
        // If a user is changed away from an exec, their password and level should be stripped
        $this->db->update('users',$vals,array('uid',$id)); 
        if($this->db->rows_affected())
            echo "Updated user";
        else 
            echo "Nothing was updated!";
    }
    
    private function _make_exec($id,$user) {
        // Make a user with the given id an exec
        // send them an email
        $pass = uniqid(); // Generate a pass
        $encrypt_pass = crypt($pass,$this->pass_salt);
        $this->db->update('users',array('pass'=>$encrypt_pass),array('uid'=>$id));
        $this->load->library('email');
        $this->email->from('webmaster@fencingsa.org.au');
        $this->email->to($user['email']);
        $this->email->subject('Your account at fencingsa.org.au');
        $this->email->message('You are now an member of the FencingSA executive. You can logon to '.anchor('admin','fencings.org.au/admin').'. Use the password: "'.$pass.'" (without the quote marks), on the site you can enter results, update information, administrate users, update results for competitions and create competitions, as well as administrating users. You may also want to update your password, which is accessible from the admin page');
        $this->email->send();
    }
    private function _remove_exec($id) {
        // Remove executive user
        $this->db->update('users',array("pass"=>null),array("uid"=>$id));
        $this->db->delete('user_level',array("uid"=>$id));
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
                $user_table[] = array($v['last_name'].form_hidden('uid',$v['uid']),$v['first_name'],form_checkbox('licensed','licensed',$v['licensed'] == date("Y")),form_dropdown('levels',$license_types,$v['license_type']));
            }
            $this->data['user_table'] = $this->table->generate($user_table);
            $this->data['main_content'] .= $this->load->view('admin/users/licenses',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
    
    function update_licenses() {
        if(!$this->input->is_ajax_request()) {
            // 
            echo "Please do no access this page directly"; 
        } elseif($this->session->userdata('level') != 'executive') {
            echo "Forbidden!";
        } else {
            // Update from json
            if(!$data = json_decode($this->input->post('data'),true)) {
                echo "Could not decode json";
            } else {
                // Update each user with license type if they are this year
                // should be 'uid'=>array('users'=>bool,type=>lic_type);
                $users = array();
                foreach($data as $v) {
                    if($v['licensed'])
                        $lic = date("Y");
                    else
                        $lic = null;
                    if($v['type'] == "") {
                        $type = null;
                    } else {
                        $type = $v['type'];
                    }
                    $users[] = array('uid'=>$v['uid'],'licensed'=>$lic,'license_type'=>$type);
                }
                // use update batch
                var_dump($users);
                $this->db->update_batch('users',$users,'uid');
                $upd = $this->db->affected_rows();
                echo "$upd entries updated</br>";
            }
        }   
    }
    
}