<?php
class  MY_Controller  extends  CI_Controller  {
	
	function __construct(){
		parent::__construct();	
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('directory');
		$this->load->helper('form');
		$this->data['menu'] = $this->_generate_menu(); 
	}
	private function _generate_menu(){
		$out = '';
		$pages = array(
			'Home'=>array('link'=>'home','sub'=>array()),
			'News'=>array('link'=>'news','sub'=>array()),
			'Calendar'=>array('link'=>'calendar','sub'=>array('Events','National','Local','Robyn Chaplin')),
			'Results'=>array('link'=>'results','sub'=>array()),
			'Clubs'=>array('link'=>'clubs','sub'=>array()),
			'Rules'=>array('link'=>'rules','sub'=>array( 'Categories','Competitions','Entry Costs','Equipment','Team Competitions')),
			'Forms and Resources'=>array('link'=>'info','sub'=>array()),
			'Contact'=>array('link'=>'contact','sub'=>array())
		);
		
		//Links to pages
		foreach($pages as $name => $i){
			$out .= "<li>\n\t".anchor($i['link'],$name)."\n<ul>";
			foreach($i['sub'] as $j){
				$out .= "<li>".anchor($i['link'].'/type/'.url_title($j),$j)."</li>\n";
			}
			$out .= "</ul>\n</li>";
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
?>