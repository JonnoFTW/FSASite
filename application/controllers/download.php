<?php
class Download extends MY_Controller {
    function __construct(){
		parent::__construct();
	}
	function index(){
        redirect('forms');
	}
	function get($id = false){
        if(!$id) {
            redirect('forms');
        }
        $this->load->helper('download');
        $this->load->helper('file');
		// Check if the file exists
        $result = $this->db->get_where('forms',array('fid'=>$id)); 
        if($result->num_rows() == 0) {
            $this->data['main_content'] .= $this->load->view('download_missing',$this->data,true);
            $this->load->view('default',$this->data);
        }  else {
            $file = $result->row_array();
            $path = 'assets/documents/'.$file['link'];
            $finfo = get_file_info($path);
            $ext = end(explode(".",$finfo["name"]));
            // Make the file name should be safely stored in the db
            $name = "{$file["name"]}.{$ext}";
            $data = read_file($path);
            force_download($name,$data);
        }        
	}
}
?>