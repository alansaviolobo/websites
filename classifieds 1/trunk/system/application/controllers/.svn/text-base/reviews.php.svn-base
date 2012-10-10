<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Reviews extends Controller {
	function Reviews(){
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
		$m=$this->uri->segment(2);
		$n=$this->uri->segment(3);
		$p=$this->uri->segment(4);
		$o=$this->uri->segment(5);
		$s=$this->uri->segment(6);
		if($m=="view_reviews"){
			$reviews=array();
			$q = $this->db->query("SELECT `reviews`.*,`users`.`username` FROM `reviews`,`users` WHERE `users`.`id`=`reviews`.`reviewer` AND `reviews`.`user`='".$n."'");
			if ($q!==false && $q->num_rows()>0){
				foreach($q->result() as $r){
					$admin_menu=array();
					if($_SESSION["who"]["admin"]==1){
						$admin_menu[]=array("aa"=>"aa");
					}
					$reviews[]=array("id"=>$r->id,"username"=>$r->username,"rating"=>$r->rating,"body"=>$r->body,"time"=>$r->time,"date"=>substr($r->date,0,4)."-".substr($r->date,2,2)."-".substr($r->date,4,2),"admin_menu"=>$admin_menu);
				}
			}
			$page["reviews"]=$reviews;
			$page["content"]=$this->parser->parse('view_reviews.html',$page, TRUE);
		}elseif($m=="delete"){
			if($_SESSION["who"]["admin"]==1){
				$q = $this->db->query("DELETE FROM `reviews` WHERE `id`='".$n."'");
				header("Location:".$_SERVER['HTTP_REFERER']);
			}
		}elseif($m=="add"){
			$_SESSION["return"]=$_SERVER['HTTP_REFERER'];
			if(!$_SESSION["who"]["id"]){
				header("Location:".base_url()."account/login_small/");
			}
			$page["user"]=$n;
			$page["content"]=$this->parser->parse('add_review.html',$page, TRUE);
		}elseif($m=="save"){
			$sql="INSERT INTO `reviews` SET ";
			foreach($_POST as $f=>$v){
				if($f!="uid" && $f!="button"){
					$sql.="`".$f."`='".$v."',";
				}
			}
			$sql.=" `reviewer`='".$_SESSION["who"]["id"]."',`date`='".date("Ymd")."',`time`='".date("G:i:s T")."'";
			$q = $this->db->query($sql);
			header("Location:".$_SESSION["return"]);
		}
		$this->parser->parse('header_popup.html',$page);
	}
}
?>