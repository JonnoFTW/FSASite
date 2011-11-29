<?php  //  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MY_Controller {
    function __construct(){
		parent::__construct();
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
	}
	function index(){
	//	$this->data['main_content']  = $this->load->view('admin/admin_user',$this->data,true);
	//	$this->load->view('admin/admin',$this->data);
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
    function user($id = false){
        $this->db->select('users.uid, last_name, first_name, users.email, users.phone, users.licensed,clubs.short_name');
        $this->db->from('users');
        $this->db->order_by('users.last_name','desc');
        $this->db->join('clubs','users.clubid = clubs.clubid','left');
      if(!$id){
      
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
     } else {
        $this->db->where('uid',$id);
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
    function resources(){
        if($this){
            // File is being uploaded
            $data = array(
                'link'=>'form',
                'description'=>'Some sheet',
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
