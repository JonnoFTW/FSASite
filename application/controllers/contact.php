<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {

	function __construct(){
		parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->data['mailed']=false;
        $this->data['send_fail']=false;
        $this->data['email'] = "enquiries@fencingsa.org.au";
		$this->data['title'] = 'Contact';
        
	}
	public function index(){
        $this->db->select('users.first_name, users.last_name, users.phone, users.email, user_level.note ');
        $this->db->from('users');
        $this->db->join('user_level','users.uid = user_level.uid');
        $this->db->where('user_level.level','executive');
        $query = $this->db->get();
        $this->data['mails'] = $query->result_array();
        $config = array(
            array(
                'field'   => 'name',
                'label'   => 'Name',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'email',
                'label'   => 'Email',
                'rules'   => 'required'
              ),
            array(
                'field' => 'message',
                'label' => 'Last_name',
                'rules' => 'required'
              )
            );
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()){
            $email = $this->input->post('email');
            $msg = $this->input->post('message');
            $name = $this->input->post('name');
            $subject = "Fencing SA Enquiry";
            $message = "From: {$name}\n{$msg}";
            $headers = "From: {$email}" . "\r\n" .
                       "Reply-To: {$email}" . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();
            // Store the email in a table or something so it can be changed later
            mail($this->data['email'],$subject,$message,$headers);
            $this->data['mailed'] = true;
        }
		$this->data['main_content'] = $this->load->view('contact',$this->data,true);
		$this->load->view('default',$this->data);
	}
  
}