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
    
    private function _makeTT($result) {
        // Takes a db result and returns an array of tables
        $events = $result->result_array();
        // var_dump($events);
        $days = array();
        foreach($events as $event){
            $days[$event['date']][] = $event;
        }
        $tables = array();
        foreach($days as $k=>$v) {
            $this->table->set_heading('Time','Title','Weapon','Gender','Category');
            $this->table->set_template(array(
            'table_open'=> "\n<table>\n<thead>\n<tr><th colspan=\"5\" class=\"table-head\">".$k."</th></tr>",
            'thead_open' =>'',
            'thead_close' =>'',
            'cell_start' => '<td class="fixed">',
            'heading_row_end' => '</tr></thead>',
            ));
            $arr = array();
            foreach($v as $w) {
                $arr[] = array(anchor('results/event/'.$w['event_id'],$w['time']),$w['name'],$w['weapon'],$w['gender'],$w['category']);
            }
            $tables[] = $this->table->generate($arr);
            $this->table->clear();
        }
        return $tables;
    }

    public function type($type = False,$year = false){
        if($year == false || !is_numeric($year) || !checkdate(1,1,$year)){
            $year = date("Y");
         }
		//get Calendar
        $this->data['type'] = $type;
		switch($type){
			case 'Local': $type = 'L';break;
			case 'National': $type = 'N';break;
			case 'Events': $type = 'E';break;
			case 'Robyn-Chaplin': $type = 'E';break;
            
		}

		$sql = "SELECT event_id, name, weapon, category, gender, type, date_format(date,'%W %D %M, %Y') as date, date_format(date,'%k:%i') as time  FROM events WHERE type = '$type' AND year(date) = $year"; 
        $result = $this->db->query($sql);
		if(!$type|| $result->num_rows() == 0){
			$this->data['main_content']  .= $this->load->view('calendar/calendar_error','',true);
		}
		else{
			$this->data['title'] .= ': '. $this->data['type'];
            $this->data['year'] = $year;
            $this->data['tables'] = $this->_makeTT($result);
			$this->data['main_content'] .= $this->load->view('calendar/calendar',$this->data,true);
		}
		$this->load->view('default',$this->data);
        
    }
}
?>
