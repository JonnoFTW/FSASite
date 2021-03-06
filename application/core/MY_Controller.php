<?php

class  MY_Controller  extends  CI_Controller  {
	
	function __construct(){
		parent::__construct();	
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('directory');
		$this->load->helper('form');
        $this->load->library('table');
		$this->data['menu'] = $this->_generate_menu(); 
        $this->pass_salt = '$2a$07$FdAQgn8nY8NdOqs9OIGIGA$';
        $this->data['main_content'] = '';
        $this->data['EVENT_TYPE'] = array(
            "Local"=> array('type'=>'Local','description'=>"All local events"),
            "Robyn-Chaplin"=> array('type'=>'Robyn Chaplin','description'=>"Details of the Robyn Chaplin Memorial Event"),
            "National"=> array('type'=>'National','description'=>"All national events"),
            "Events"=> array("type"=>"Events","description"=>"All other events organised by FSA such as dinners and functions")
        );
        $this->data['GENDERS'] = array(
            'M'=>'Mens',
            'F'=>'Womens',
            'O'=>'Mixed'
        );
        $this->data['WEAPONS'] = array(
            'Foil'=>'Foil',
            'Epee'=>'Epee',
            'Sabre'=>'Sabre'
        );
        $this->data['CATEGORIES'] = array(
            'U11'=>'U11',
            'U13'=>'U13',
            'U15'=>'U15',
            'U17'=>'U17',
            'U20'=>'U20',
            'Novice'=>'Novice',
            'Intermediate'=>'Intermediate',
            'Open'=>'Open',
            'Veteran'=>'Veteran'
        );
        
	}
    protected function _addOrdinal($num) {
        if (!in_array(($num % 100),array(11,12,13))){
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:  return $num.'st';
                case 2:  return $num.'nd';
                case 3:  return $num.'rd';
            }
        }
        return $num.'th';
    }
 
	private function _generate_menu(){
		$out = '';
		$pages = array(
			'Home'=>array('link'=>'home'),
			'News'=>array('link'=>'news'),
			'Calendar'=>array('link'=>'calendar','sub'=>array('Events','National','Local','Robyn Chaplin')),
			'Results'=>array('link'=>'results'),
            'Rankings'=>array('link'=>'rankings'),
			'Clubs'=>array('link'=>'clubs'),
			'Rules'=>array('link'=>'rules','sub'=>array( 'Categories','Competitions','Entry Costs','Equipment','Team Competitions')),
			'Forms and Resources'=>array('link'=>'info'),
			'Contact'=>array('link'=>'contact')
		);
		
		//Links to pages
		foreach($pages as $name => $i){
			$out .= "<li>\n\t".anchor($i['link'],$name);
            if (isset($i['sub'])) {
                $out .= "\n<ul>";
                foreach($i['sub'] as $j){
                    $out .= "\n<li>".anchor($i['link'].'/type/'.url_title($j),$j)."</li>";
                }
                $out .= "\n</ul>\n</li>";
            }
            else {
                $out .= "</li>\n";
            }
		}
		if($this->session->userdata('name')){
			$out .= "<li class='secondary'>".anchor('admin',"Admin")."</li>\n";
			$out .= "<li class='secondary'>".anchor('login/logout',"Logout ({$this->session->userdata('name')})")."</li>\n";
		}
		else{
			//Add login box possibly
			$out .= "<li class=\"secondary\">".anchor('login','Login')."</li>";
		}
		return $out;
	}
}  
 
class MY_Admin extends MY_Controller {

    function __construct(){
		parent::__construct(); 
        //    var_dump($this->session->all_userdata());
            $this->data['title'] = 'Administration';
            if(!$this->session->userdata('logged')){
                if($this->input->is_ajax_request()) {
                    echo "Request failed, you will need to login</br>";
                    return;
                }
                else
                    redirect('login');
            }
        else{
             //   $this->res = $this->db->query("SELECT * FROM user_level");
             //   $this->data['side'] = $this->res->result_array();
                $this->data['main_content'] = $this->load->view('admin/admin_side',$this->data,true);
            }
            
            $this->load->library('form_validation');
            $this->load->library('user_agent');
            if($this->agent->is_browser('MSIE')) {
                $this->data['IS_IE'] = true;
            } else {
                $this->data['IS_IE'] = false;
            }
            $clubs = array();
            $result = $this->db->get('clubs');
            foreach($result->result_array() as $k=>$v){
                $clubs[$v['clubid']] = $v['short_name'];
            }
            $clubs[''] = '';
            $this->data['clubs'] = $clubs;
            $this->data['main_content'] .= $this->load->view('admin/table_sort',$this->data,true);
	}
    
    protected function _get_unentered() {
        $res = $this->db->select("`events`.`event_id`, `events`.`name`, DATE_FORMAT(`events`.`date`,'%Y-%c-%d') AS `date`, DATE_FORMAT(`events`.`date`,'%H:%i') as `time`",FALSE)->order_by('events.date',"desc")->join('results','results.event_id = events.event_id','left outer')->where('`results`.`event_id` IS NULL',null,false)->or_where('`results`.`res` IS NULL',null,false)->get('events');
        return $res->result_array();
    }
}
?>
