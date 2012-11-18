<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rankings extends MY_Controller {

	function __construct(){
		parent::__construct();
        // Show sidebar with menu for weapons/year/category
        $this->data['title'] = "Rankings";
        $result = $this->db->select('YEAR(`date`)',false)->distinct()->get('events');
        $years = $result->result_array();
        $this->data['years'] = array();
        foreach($result->result_array() as $row) {
            $y = end(array_values($row));
            $this->data['years'][$y] = $y;
        }
        $this->data['main_content'] = $this->load->view('rankings/side_panel',$this->data,true);
        $this->load->library('table');
	}
    public function index() {
        // Do something
        $this->load->view('default',$this->data);
    }
    function cmp_rank($a,$b) {
        return ($a['points']-$b['points']);
    }
    function intcmp($b,$a) {
        if($a['points'] == $b['points'])return 0;
        if($a['points']  > $b['points'])return 1;
        if($a['points']  < $b['points'])return -1;
    }
    public function rank($year = false,$weapon = false,$category = false,$gender = false) {
        // If a param is
        foreach(array($year=>$this->data['years'],
                      $weapon=>array_values($this->data['WEAPONS']),
                      $gender=>array_keys($this->data['GENDERS']),
                      $category=>array_values($this->data['CATEGORIES']))
                        as $k=>$v) {
            if(!in_array($k,$v)) {
                echo "$k not in ";
                var_dump($v);
               // echo "Please specify a valid year, weapon and category";
            }
        }
        // Join the entrants, 
        $this->db->select("users.uid,users.first_name,users.last_name,events.titled,events.event_id,DATE_FORMAT(events.date,'%b %d') as `date`,results.res",false);
        $this->db->where("YEAR(`events`.`date`) = $year",null,false);
        $this->db->where(array("weapon"=>$weapon,"category"=>$category,"events.cancelled"=>false));
        $this->db->where_in('events.gender',array('O',$gender));
        $this->db->order_by('events.date','ASC');
        $result = $this->db->join('users','users.uid = results.uid','left outer')/*->join('clubs','clubs.clubid = users.clubid')*/->join('events','events.event_id = results.event_id','right outer')->get('results');
        
   //     echo $this->db->last_query();
     //   echo "\n";
        $this->data['wep'] = $weapon;
        $this->data['year'] = $year;
        $this->data['category'] = $category;
        $this->data['gender'] = $gender;
        // Generate the table
        $results = $result->result_array();
        $headers = array("Rank","Name");
        $rankings = array();
        $dates = array();
        foreach($results as $v) {
            $d = anchor("results/event/{$v['event_id']}",$v['date']);
 
            if(!in_array($d,$dates)) {
                $dates[] = $d;
            }
            if($v['uid'] == null) {
                continue;
            }
            if(!isset($rankings[$v['uid']])) {
                $rankings[$v['uid']] = array("points"=>0,"name"=>anchor('results/user/'.$v['uid'],"{$v['first_name']} {$v['last_name']}"));
            }
            $rankings[$v['uid']][$d] = $v['res']; 
            $rankings[$v['uid']]["points"] += $this->points($v['res']);
            if($v['titled']) {
                if( $v['res'] < 17)
                    $rankings[$v['uid']]["points"] +=2;
                elseif($v['res'] <33)
                    $rankings[$v['uid']]["points"] +=1;
            }
        }
        $headers = array_merge($headers,$dates);
        foreach($dates as $d) {
            foreach($rankings as &$r) {
                if(!isset($r[$d]))
                    $r[$d] = "";
            }
        }
        $headers[] = "Total Points";
        $this->table->set_heading($headers);
        usort($rankings,'Rankings::intcmp');
        foreach($rankings as $k=>&$r) {
            $po = $r['points'];
            unset($r['points']);
            $r['points'] = $po;
            array_unshift($r,$k+1);
            $this->table->add_row(array_values($r));
        }
        $this->data['ranks'] = $this->table->generate();
        $this->data['main_content'] .= $this->load->view('rankings/rankings',$this->data,true);
        $this->load->view('default',$this->data);
    }
    /**
      */
    private static function points($pos) {
        $out = 0;
        $p = array(1=>10,
                        2=>9,
                        3=>8,
                        4=>8,
                        5=>6,
                        6=>5,
                        7=>4,
                        8=>3);
        if($pos <9)
            $out = $p[$pos];
        elseif($pos <=32)
            $out = 1;
        return $out;
    }

}