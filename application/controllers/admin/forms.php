<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends MY_Admin {

    function __construct(){
		parent::__construct();
        $this->data['title'] .= " :: Manage Forms and Resources";
        $this->load->helper('file'); 
        $this->types = array("res"=>"Resources","comp"=>"Competition Resources");
    }
    
    function index() {
        // List forms etc.
        // Have fields to update their info
        $result = $this->db->get('forms');
        foreach($result->result_array() as $v) {
            $this->table->add_row(array(anchor('download/get/'.$v['fid'],$v['name'],array('name'=>$v['fid'])),form_input('name',$v['name']),form_input('description',$v['description']),form_dropdown('type',$this->types,$v['type']),form_checkbox('delete','delete',false)));
        }   
        $this->table->set_heading('Link',"Name","Description","Type","Delete?");
        $this->data['forms'] = $this->table->generate();
        $this->data['main_content'] .= $this->load->view('admin/forms/list_forms',$this->data,true);
        $this->load->view('default',$this->data);
    }
    
    private function _sanitize($string, $force_lowercase = true) {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
}

    
    private function _delete($id = false) {
        // check if it exists
        $result = $this->db->get_where('forms',array('fid'=>$id));
        if($result->num_rows() == 0) {
            echo "No such file with that id exists!";
        } else {
            // Delete the file
            // Turn on any warnings that might occur.
            error_reporting( E_WARNING );
            $file = $result->row_array();
            if(unlink('assets/documents/'.$file['link'])) {
                $this->db->delete('forms',array('fid'=>$file['fid']));
                echo "Successfully deleted file {$file['name']}";
            } else {
                echo "Could not delete file $id";
            }
            echo "</br>";
        }
    }
    function update() {
        // Update details of a form, through jquery on the list.
        if($vals = json_decode($this->input->post('forms_list'),true)) {
            // validate it in here
           // var_dump($vals);
            foreach($vals as $k=>$v) {
                // use update_batch or something
                // validate input
                if(isset($v['delete'])) {
                    $this->_delete($k);
                } else {
                    $vals = array(
                        "name"=>$v['name'],
                        "description"=>$v["description"],
                        "type"=>$v["type"]
                    );
                    $this->db->update('forms',$vals,array('fid' =>$k));
                    echo "Updated form {$k}</br>";
                }
            }
        }
    
    }
    
    function add(){
        // File is being uploaded
        // check the file upload helper
        // store in assets
        
        $config['upload_path'] = 'assets/documents/';
		$config['allowed_types'] = 'doc|docx|pdf';
        $config['encrypt_name'] = true;
        
        $this->load->library('upload',$config);
        
        if(!$this->upload->do_upload()) {
            $errors = array('error'=>$this->upload->display_errors());
            $this->data['main_content'] .= $this->load->view('admin/forms/upload_error',$errors,true);
        } else {
            $file_data =  $this->upload->data();
            if(!$name = $this->input->post('name')) {
                $name = $this->_sanitize($file_data['orig_name']);
            }
            $data = array(
                'link'=> $file_data['file_name'], 
                'description'=>$this->input->post('description'),
                'name'=>$name,
                'type'=>$this->input->post('type')
                );
            $this->db->insert('forms',$data);
            $file = array('file'=>$file_data);
            $this->data['main_content'] .= $this->load->view('admin/forms/upload_success',$file,true);
        }
        $this->load->view('default',$this->data);
    }
}
    