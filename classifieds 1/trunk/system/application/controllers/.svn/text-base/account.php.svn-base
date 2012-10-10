<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Account extends Controller {
	function Account(){
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
		$page["page_title"]="Miami Classified Ads - Account Section";
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
		$page["comm_rows"]=$this->Setup->comm_rows(0);
		$page["us_states"]=$this->Setup->us_states();
		if($m=="signup"){
			$page["page_title"]="Miami Classified Ads - Account Signup";
			if($_SESSION["who"]["id"]){
				header("Location:".site_url()."/account/main/");
			}
			$uid=rand(100000000,999999999);
			$md5 = md5(microtime() * mktime());
			$string = substr($md5,0,5);
			
			$this->Setup->create_captcha($uid,$string);
			
			$_SESSION["string"]=$string;
			$wrong_code=array();
			if($n=="error"){
				$wrong_code[]=array("aa"=>"aa");
			}
			$page["wrong_code"]=$wrong_code;
			$page["email"]=$_SESSION["email"];
			$page["username"]=$_SESSION["username"];
			$page["password"]=$_SESSION["password"];
			$page["uid"]=$uid;
			$page["content"]=$this->parser->parse('signup.html',$page, TRUE);
			
		}elseif($m=="activate"){
			$page["page_title"]="Miami Classified Ads - Activate Account";
			if($n){
					$q = $this->db->query("SELECT * FROM `users` WHERE `active`='".$n."'");
					$r = $q->row();
					$q = $this->db->query("UPDATE `users` SET `active`='1' WHERE `active`='".$n."'");
					if(mysql_affected_rows()){
						$_SESSION["who"]["id"]=$r->id;
						$_SESSION["who"]["username"]=$r->username;
						$_SESSION["who"]["email"]=$r->email;
						$page["content"]=$this->parser->parse('activated_OK.html',$page, TRUE);
					}else{
						$page["content"]=$this->parser->parse('activated_FAILED.html',$page, TRUE);
					}
			}else{
				if(strtolower($_SESSION["string"])!=trim(strtolower($_POST["code"]))){
					$_SESSION["email"]=$_POST["email"];
					$_SESSION["username"]=$_POST["nick"];
					$_SESSION["password"]=$_POST["pass"];
					header("Location:".site_url()."/account/signup/error/");
				}else{
					if($_POST){
						unset($_SESSION["email"]);
						unset($_SESSION["username"]);
						unset($_SESSION["password"]);
						$activate=rand(100000000,999999999);
						$q = $this->db->query("INSERT INTO `users` SET `email`='".$_POST["email"]."', `username`='".$_POST["nick"]."', `password`='".$_POST["pass"]."',`active`='".$activate."', `joined`='".date("Y-m-d, g:i a")."'");
						mail($_POST["email"],"Your $SITENAME Activation",site_url()."/account/activate/".$activate);
						$page["content"]=$this->parser->parse('signup_OK.html',$page, TRUE);
					}
				}
			}
		}elseif($m=="reset_pass"){
			if($_POST["email"]){
				$q = $this->db->query("SELECT * FROM `users` WHERE `email`='".$_POST["email"]."'");
				if($q->num_rows()>0){
					$q = $this->db->query("UPDATE `users` SET `password`='".rand(100000000,999999999)."' WHERE `email`='".$_POST["email"]."'");
					$q = $this->db->query("SELECT * FROM `users` WHERE `email`='".$_POST["email"]."'");
					$r = $q->row();
					mail($r->email,"Your $SITENAME Password",$r->username."\n".$r->password);
					header("Location:".site_url()."/account/login/");
				}else{
					$error=array();
					$error[]=array("aa"=>"aa");
					$page["error"]=$error;
					$page["content"]=$this->parser->parse('forgot_password.html',$page, TRUE);
				}
			}else{
				$error=array();
				$page["error"]=$error;
				$page["content"]=$this->parser->parse('forgot_password.html',$page, TRUE);
			}
		}elseif($m=="resend_activation"){
			if($_POST["email"]){
				$q = $this->db->query("SELECT * FROM `users` WHERE `email`='".$_POST["email"]."'");
				if($q->num_rows()>0){
					$r = $q->row();
					mail($r->email,"Your $SITENAME Activation",site_url()."/account/activate/".$r->active);
					header("Location:".site_url()."/account/login/");
				}else{
					$error=array();
					$error[]=array("aa"=>"aa");
					$page["error"]=$error;
					$page["content"]=$this->parser->parse('resend_activation.html',$page, TRUE);
				}
			}else{
				$error=array();
				$page["error"]=$error;
				$page["content"]=$this->parser->parse('resend_activation.html',$page, TRUE);
			}
		}elseif($m=="login"){
			if($_POST["username"] && $_POST["password"]){
				$q = $this->db->query("SELECT * FROM `users` WHERE `username`='".$_POST["username"]."' AND `password`='".$_POST["password"]."'");
				$r = $q->row();
				if($r->active!=1 && mysql_affected_rows()){
					$page["content"]=$this->parser->parse('activated_FAILED.html',$page, TRUE);
				}else{
					$_SESSION["who"]["id"]=$r->id;
					$_SESSION["who"]["username"]=$r->username;
					$_SESSION["who"]["email"]=$r->email;
					if($_SESSION["return"]){
						header("Location:".$_SESSION["return"]);
					}else{
						header("Location:".site_url()."/account/main/");
					}
				}
			}else{
				$page["content"]=$this->parser->parse('login.html',$page, TRUE);
			}
		}elseif($m=="login_small"){
			if($_POST["username"] && $_POST["password"]){
				$q = $this->db->query("SELECT * FROM `users` WHERE `username`='".$_POST["username"]."' AND `password`='".$_POST["password"]."'");
				$r = $q->row();
				$_SESSION["who"]["id"]=$r->id;
				$_SESSION["who"]["username"]=$r->username;
				$_SESSION["who"]["email"]=$r->email;
				if($_SESSION["return"]){
					header("Location:".$_SESSION["return"]);
				}else{
					header("Location:".site_url()."/account/main/");
				}
			}else{
				$page["content"]=$this->parser->parse('login.html',$page, TRUE);
				$this->parser->parse('header_popup.html',$page);
			}
		}elseif($m=="logout"){
			session_destroy();
			header("Location:".base_url());
		}elseif($m=="edit"){
			$page["email"]=$_SESSION["who"]["email"];
			$page["content"]=$this->parser->parse('edit_account.html',$page, TRUE);
		}elseif($m=="edit_save"){
			if($_POST["email"]){
				$q = $this->db->query("UPDATE `users` SET `email`='".$_POST["email"]."' WHERE `id`='".$_SESSION["who"]["id"]."'");
				$_SESSION["who"]["email"]=$_POST["email"];
			}
			if($_POST["pass"]){
				$q = $this->db->query("UPDATE `users` SET `password`='".$_POST["pass"]."' WHERE `id`='".$_SESSION["who"]["id"]."'");
				$_SESSION["who"]["password"]=$_POST["pass"];
			}
			header("Location:".site_url()."/account/main/");
		}elseif($m=="main"){
			if(!$_SESSION["who"]["id"]){
				header("Location:".site_url()."/account/login/");
			}
			$list=array();
			$sql="SELECT `listings`.*,`cities`.`name` AS `city_name`,`categories`.`name` AS `cat_name` FROM `listings`,`cities`,`categories` WHERE `listings`.`city`=`cities`.`id` AND `listings`.`category`=`categories`.`id` AND `listings`.`owner`='".$_SESSION["who"]["id"]."'";
			$q = $this->db->query($sql);
			if ($q!==false && $q->num_rows()>0){
				foreach($q->result() as $r){
					$imgs=array();
					$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
					if($qi->num_rows()>0){
						$imgs[]=array("aa"=>"aa");
					}
					$list[]=array("list_id"=>$r->id,"list_uid"=>$r->uid,"list_name"=>$r->title,"list_location"=>$r->city_name,"list_cat"=>$r->cat_name,"list_imgs"=>$imgs);
				}
			}
			$no_results=array();
			if(count($list)==0){
				$no_results[]=array("aa"=>"aa");
			}
			$page["no_results"]=$no_results;
			$page["list"]=$list;
			$page["content"]=$this->parser->parse('my_account.html',$page, TRUE);
		}elseif($m=="check_email"){
			$q = $this->db->query("SELECT * FROM `users` WHERE `email`='".$n."'");
			if($q->num_rows()>0){
				echo "it exists";
			}	
		}else{
			if($m=="" || $m==0){
				$location = "United States";
			}else{
				$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$m."'");
				$r = $q->row();
				$location = $r->name;
			}
			$page['location']=$location;
			if($m==""){
				$m=0;
			}
			$page["cat_list"]=$this->Setup->show_categories($m);
			$page["content"]=$this->parser->parse('categories.html',$page, TRUE);
		}
		if($m!="login_small" && $m!="check_email"){
			$page["calendar"]=$this->Setup->calendar($m);
			$page["page_title"]=ucwords(strtolower($page["page_title"]));
			$this->parser->parse('header.html',$page);
		}
	}
}
?>