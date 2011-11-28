<?php
class Calendar extends MY_Controller {
	function __construct(){
		parent::__construct();
        
        $this->result = $this->db->query('SELECT distinct(year(date)) as year from events');
        $this->data['years'] = $this->result->result_array();
		$this->data['main_content']  = $this->load->view('calendar/calendar_side',$this->data,true);
		$this->data['title'] = 'Calendar';
	}
	public function index()	{
		$this->data['main_content']  .= $this->load->view('calendar/calendar_main','',true);
		$this->load->view('default',$this->data);
	}
    
    public function year($year){
        $type = 'Local';
		$this->data['type'] = $type;
        $this->result = $this->db->query("SELECT event_id, name, weapon, category, gender, type, date_format(date,'%W %D %M, %Y') as date, date_format(date,'%k:%i') as time  FROM events WHERE type = 'L' AND year(date) = '$year'");
        if($this->result->num_rows() == 0){
			$this->data['main_content']  .= $this->load->view('calendar/calendar_error','',true);
		}
		else{
			$this->data['title'] .= ': Local events: '. $year;
			$this->data['news_item'] = $this->result->result_array();
			$this->data['days'] = array();
			foreach($this->data['news_item'] as $event){
				$this->data['days'][$event['date']][] = $event;
			}
			$this->data['main_content'] .= $this->load->view('calendar/calendar',$this->data,true);
		}
		$this->load->view('default',$this->data);
    }
    
    public function type($type = False){
		//get Calendar
		$this->data['type'] = $type;
		switch($type){
			case 'Local': $type = 'L';break;
			case 'National': $type = 'N';break;
			case 'Events': $type = 'E';break;
			case 'Robyn Chaplin': $type = 'E';break;
		}
		$this->sql = "SELECT event_id, name, weapon, category, gender, type, date_format(date,'%W %D %M, %Y') as date, date_format(date,'%k:%i') as time  FROM events WHERE type = '$type' AND year(date) = year(now())"; //get the current year only
		$this->result = $this->db->query($this->sql);
		if(!$type|| $this->result->num_rows() == 0){
			$this->data['main_content']  .= $this->load->view('calendar/calendar_error','',true);
		}
		else{
			$this->data['title'] .= ': '. $this->data['type'];
			$this->data['news_item'] = $this->result->result_array();
			$this->data['days'] = array();
			foreach($this->data['news_item'] as $event){
				$this->data['days'][$event['date']][] = $event;
			}
			$this->data['main_content'] .= $this->load->view('calendar/calendar',$this->data,true);
		}
		$this->load->view('default',$this->data);
        
    }
    
    private function _makeCalendar($query,$type){
    
    }

}
?>
