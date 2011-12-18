<?php
class News extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->data['title'] = 'News';
        $this->result = $this->db->query('SELECT distinct(year(posted)) FROM news');
        $this->data['years'] = $this->result->result_array();
		$this->data['main_content']  = $this->load->view('news/news_side',$this->data,true);
	}
	public function index()	{
		$this->sql = "SELECT * FROM news, users WHERE news.author = users.uid AND YEAR(news.posted) = YEAR(NOW()) ORDER BY posted DESC";
		$this->result = $this->db->query($this->sql);
		$this->data['news_item'] = $this->result->result_array();
		$this->data['main_content'] .= $this->load->view('news/news',$this->data,true);
		$this->load->view('default',$this->data);
	}
    public function item($id = False){
		//get news item of id from db otherwise show error
		$this->sql = "SELECT * FROM news, users WHERE news.author = users.uid AND newsid='$id'";
		$this->result = $this->db->query($this->sql);
		if(!$id || $this->result->num_rows() == 0){
			$this->data['main_content']  .= $this->load->view('news/news_error','',true);
		}
		else{
			$this->data['news_item'] = $this->result->result_array();
			$this->data['main_content'] .= $this->load->view('news/news',$this->data,true);
		}
		$this->load->view('default',$this->data);
        
    }
    public function search(){
        $this->data['title'] .= ' Search';
        if(!$this->input->post()){
            $this->data['news_item'] = array(
                                        array("newsid"=>0,
                                              "title"=>"Please use the search form to the left",
                                              "message"=>"All arguments are optional",
                                              "first_name"=>"FencingSA",
                                              "last_name"=>"",
                                              "posted"=>date('Y')
                                            )
                                        );
        }else{
            $this->db->from('news');
            $this->db->join('users','news.author = users.uid');
            if($this->input->post('Author')) $this->db->or_like('concat(users.first_name,\' \',users.last_name)',$this->input->post('Author'));
            if($this->input->post('Description')) $this->db->or_like('message',$this->input->post('Description'));
            if($this->input->post('from')) $this->db->where('news.posted >=',$this->input->post('from'));
            if($this->input->post('to')) $this->db->where('news.posted <=',$this->input->post('to'));
            $this->result = $this->db->get();
            $this->data['news_item'] = $this->result->result_array();
        }// get some pagination in here or something
		$this->data['main_content'] .= $this->load->view('news/news',$this->data,true);
        $this->load->view('default',$this->data);
    }

}
?>
