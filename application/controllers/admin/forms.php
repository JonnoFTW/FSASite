<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends MY_Admin {

    function __construct(){
		parent::__construct();
        $this->data['title'] .= " :: Manage Forms and Resources";
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',true);
            $this->load->view('default',$this->data);
        }        
    }
    
    function index() {
        // List forms etc.
        // Have fields to update their info
        $result = $this->db->get('forms');
        $this->data['forms'] = $result->result_array();
        $this->load->view('admin/list_forms',$this->data,true);
        $this->load->view('default',$this->data);
    }
    
    function delete() {
        // Delete a form
    }
    function update() {
        // Update details of a form, through jquery on the list.
        if($vals = json_decode($this->input->post('forms'))) {
            // validate it in here
            
            foreach($vals as $v) {
                $this->db->update('forms',$v,'fid = '.$v['id']);
            }
        }
    
    }
    function add(){
        // File is being uploaded
        // check the file upload helper
        // store in assets
        $data = array(
            'link'=>'form',
            'description'=>'Some worksheet',
            'name'=>'Some name',
            'type'=>'res or comp'
            );
        $this->db->insert('forms',$data);
        $this->load->view('admin',$this->data);
    }
}
    