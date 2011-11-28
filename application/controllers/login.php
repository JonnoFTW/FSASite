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
            $this->db->where(array('email'=>$this->input->post('user'),'pass'=>crypt($this->input->post('pass'),'$2a$07$FdAQgn8nY8NdOqs9OIGIGA$')));
            $this->db->join('user_level','users.uid = user_level.uid');
            $query = $this->db->get('users');
            if($query->num_rows() > 0) {
                $user = $query->row_array();
                $this->session->set_userdata(array('uid'=>$user['uid'],
                                                   'logged'=>true,
                                                   'name'=>$user['first_name'].' '.$user['last_name'],
                                                   'clubid'=>$user['clubid'],
                                                   'level'=>$user['level']
                                                   ));   
                redirect('admin');
            }
            else{
                $this->data['err'] = true;
            }
        } elseif ($this->input->post('user') xor $this->input->post('pass')){
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
}
