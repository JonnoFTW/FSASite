<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Results extends MY_Admin {

    function __construct(){
		parent::__construct();	
        if($this->session->userdata('level') != "executive") {
            $this->data['main_content'] .=  $this->load->view('admin/forbidden',true);
            $this->load->view('default',$this->data);
        }
    }
    function index(){
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
    }