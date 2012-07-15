<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct(){
		parent::__construct();
        $this->load->helper('text');
	}
	public function index(){
		$result = $this->db->query('SELECT * FROM news, users WHERE news.author = users.uid ORDER BY posted desc LIMIT 0,3');
		$this->data['news'] = $result->result_array();
        $result = $this->db->order_by("date","asc")->where('`events`.`date` > NOW() ',null,false)->get('events',10);
        $this->data['events'] = $result->result_array();

        $results = array();
        $result = $this->db->order_by('events.date','desc')->order_by('results.res','asc')->join('results','results.event_id = events.event_id')->join('users','users.uid = results.uid')->where('results.res IS NOT NULL',NULL,false)->get('events',3);
        foreach($result->result_array() as $v) {
            $pieces = explode(' ',$v['date']);
            $date = $pieces[0];
            $results[$v['event_id']][] = array(
                             'event_name' => "{$date} {$v['name']}",
                              'date' =>  $v['date'],
                              'name'=> anchor("results/user/".$v['uid'],"{$v['first_name']} {$v['last_name']}"),
                              'pos' =>  $this->_addOrdinal($v['res'])
                            );
        }
        $tables = array();
        foreach($results as $k=>$v) {
            $this->table->set_heading('Position','Name');
            $this->table->set_template(array(
                'table_open'=> "\n<table>\n<colgroup>\n\t<col class=\"colA\">\n\t<col class=\"colB\">\n</colgroup>\n<thead>\n<tr><th class=\"table-head\" colspan=\"3\">".anchor('results/event/'.$k,$v[0]['event_name']).'</th></tr>',
                'thead_open' =>'',
                'thead_close' =>'',
                'heading_row_end' => '</tr></thead>',
            ));
            $arr = array();
            foreach($v as $w){
                $arr[] = array($w['pos'],$w['name']);
            }
            $tables[] = $this->table->generate($arr);
            $this->table->clear();
        } 
        $this->data['results'] = $tables;
        $result = $this->db->select('updated, message, users.first_name, users.last_name ')->join('users','users.uid = pages.author','left')->get_where('pages',array('title'=>'home'));
        
        $this->data['message'] = $result->row_array();
		$this->data['main_content'] = $this->load->view('home',$this->data,true);
		$this->data['title'] = 'Home Page';
		$this->load->view('default',$this->data);
	}
    
    function getting_started() {
        $this->data['title'] = "Getting Started";
        $this->data['main_content'] = $this->load->view('home/getting_started',$this->data,true);
        $this->load->view('default',$this->data);
    }
}
