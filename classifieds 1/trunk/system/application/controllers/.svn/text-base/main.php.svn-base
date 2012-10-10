<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Main extends Controller {
	function Main(){
		error_reporting("E_ERROR");
		parent::Controller();	
		session_start();
		$this->load->model('Setup');
		
	}
	function index(){
		$page = array();
		$page['base_url'] = base_url();
		$page['site_url'] = site_url();
		$page['SITENAME'] = 'Classifieds';
		$page["page_title"]="Miami Classified Ads";
		$m=$this->uri->segment(2);
		$n=$this->uri->segment(3);
		$p=$this->uri->segment(4);
		$o=$this->uri->segment(5);
		$s=$this->uri->segment(6);
		$login=array();
		$account=array();
		if(!$_SESSION["who"]["id"]){
			$login[]=array("aa"=>"aa");
		}else{
			$account[]=array("name"=>$_SESSION["who"]["username"]);
		}
		$page["login"]=$login;
		$page["account"]=$account;
		$page["us_cities"]=$this->Setup->us_cities(1);
		if($m==""){
			header("Location:".site_url()."/main/0/");
		}
		$page["comm_rows"]=$this->Setup->comm_rows($m);
		$page["us_states"]=$this->Setup->us_states();
		if($m=="cities"){
			if($n=="" || $n==0){
				$location = "United States";
			}else{
				$q = $this->db->query("SELECT * FROM `states` WHERE `id`='".$n."'");
				$r = $q->row();
				$location = $r->name;
			}
			$page['location']=$location;
			if($m==""){
				$m=0;
			}
			$page["page_title"]=strtoupper($location)." Classified Ads - City List";
			$page["city_list"]=$this->Setup->show_cities($n);
			$page["content"]=$this->parser->parse('cities.html',$page, TRUE);
		}else{
			if($m=="" || $m==0){
				$location = "United States";
				$page["page_title"]="Some 365 Classified Ads - All States";
			}else{
				$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$m."'");
				$r = $q->row();
				$location = $r->name;
				$page["page_title"]=strtoupper($r->name)." Classified Ads";
			}
			$page['location']=$location;
			if($m==""){
				$m=0;
			}
			$page["cat_list"]=$this->Setup->show_categories($m);
			$page["content"]=$this->parser->parse('categories.html',$page, TRUE);
		}
		$page["calendar"]=$this->Setup->calendar($m);
		$page["city_id"]=$m;
		if($m=="terms"){
			$page["content"]=$this->parser->parse('terms.html',$page, TRUE);
		}
		$page["page_title"]=ucwords(strtolower($page["page_title"]));
		$this->parser->parse('header.html',$page);
	}
}
?>