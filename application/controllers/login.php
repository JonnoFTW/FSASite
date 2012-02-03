<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	function __construct(){
		parent::__construct();
        $this->load->helper('form');
        $this->data['err'] = false;
        $this->data['logged_out'] = false;
		$this->data['title'] = 'Login';
	}
	public function index(){
        if($this->input->post('user') && $this->input->post('pass')){
            $this->db->where(array('email'=>$this->input->post('user'),'pass'=>crypt($this->input->post('pass'),$this->pass_salt)));
            $this->db->join('user_level','users.uid = user_level.uid');
            $query = $this->db->get('users');
            if($query->num_rows() > 0) {
                $user = $query->row_array();
                $this->session->set_userdata(array('uid'=>$user['uid'],
                                                   'logged'=>true,
                                                   'name'=>$user['first_name'].' '.$user['last_name'],
                                                   'clubid'=>$user['clubid'],
                                                   'level'=>$user['level'],
                                                   ));   
                redirect('admin');
            }
            else{
                $this->data['err'] = true;
            }
        } elseif ($this->input->post('user') xor $this->input->post('pass')){
                // User didn't provide both inputs
                $this->data['err'] = true;
        }
		$this->data['main_content'] = $this->load->view('login',$this->data,true);
		$this->load->view('default',$this->data);
	} 
    public function logout(){
        $this->session->sess_destroy();
        $this->data['logged_out'] = true;
        $this->data['main_content'] = $this->load->view('login',$this->data,true);
		$this->data['title'] = 'Login';
		$this->load->view('default',$this->data);
    }
    
    public function request_reset() {
        $this->data['title'] = "Password Reset";
        if($user = $this->input->post('user')) {
            $res = $this->db->get_where('user_level',array('uid'=>$user));
            // Generate and send a password reset key
            if($res->num_rows() > 0) {
                // Generate a key
                $this->load->library('email');
                $key = uniqid();
                $this->db->insert('reset_keys',array('key'=>$key,'requested'=>date("'Y-m-d H:i:s'"),"uid"=>$user));
                $key = $this->db->insert_id();
                $this->data['main_content'] .= $this->load->view('login/pass_reset_requested',$this->data,true);
                $res = $this->db->get_where('users',array('uid'=>$user));
                $user = $res->row_array();
                $this->email->from('webmaster@fencingsa.org.au','Web Master');
                $this->email->to($user['email']);
                $this->email->subject('Password reset request for fencingsa.org.au');
                $this->email->message('You have received this message because a password reset request has been made for you account on fencingsa.org.au. If you did not make this request, you can safely ignore this email. Please follow this link to have your password reset \n <a href="http://fencingsa.org.au/login/pass_reset/'.$key.'">http://fencingsa.org.au/login/pass_reset/'.$key.'</a> \n. If you have any troubles, you can reply to this email, and hopefully I will be able to help you.');
                $this->email->send();
            } else {
                $this->data['main_content'] .= $this->load->view('login/pass_reset_forbidden',$this->data,true);
            }
        } else {
            // Load the form
            $this->data['main_content'] = $this->load->view('login/pass_reset',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
    public function pass_reset($key = false) {
        // Check if the key exists
        if($key) {
            // Don't get old records
            $this->db->where('HOUR(TIMEDIFF(`requested`,NOW())) <= 1',null,false);
            $res = $this->db->get_where('reset_keys',array('key'=>$key));
            if($res->num_rows() == 1) {
                // Reset with a generated password
                $row = $res->row_array();
                $pass = end(explode('-',uniqid()));
                $this->db->update('users',array('pass'=>$pass),array('uid'=>$row['uid']));
                // delete the update key 
                $this->data['pass'] = $pass;
                if($this->db->affected_rows() == 1) {
                    $this->data['main_content'] .=  $this->load->view('login/pass_reset_success',$this->data,true);
                } else {
                    $this->data['err'] = "Your password could not be updated. Please request a new password reset key and try again";
                    $this->data['main_content'] .= $this->load->view('login/pass_reset_failure',$this->data,true);
                }
                // Delete old records while we're here
                $this->db->or_where('HOUR(TIMEDIFF(`requested`,NOW())) > 1',null,false);
                $this->db->delete('reset_keys',array('uid'=>$row['uid']));
            } else {
                // Warn no key, do not reset
                    $this->data['err'] = "No such key exists, it may have expired already. Password not reset!";
                    $this->data['main_content'] .= $this->load->view('login/pass_reset_failure',$this->data,true);
            }
            
        } else {
            //Please provide a key
            $this->data['err'] = "Please provide a key</br>";
            $this->data['main_content'] .= $this->load->view('login/pass_reset_failure',$this->data,true);
        }
        $this->load->view('default',$this->data);
    }
}
