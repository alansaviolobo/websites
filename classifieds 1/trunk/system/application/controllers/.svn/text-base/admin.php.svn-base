<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends Controller {
	function Admin(){
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
		if($_SESSION["who"]["admin"]!=1 && $m!="login"){
			header("Location:".site_url()."admin/login");
		}
		if($m=="login"){
			if($_POST["username"] && $_POST["password"]){
				$q = $this->db->query("SELECT * FROM `admins` WHERE `username`='".$_POST["username"]."' && `password`='".$_POST["password"]."'");
				if($q!==false && $q->num_rows()>0){
					$r = $q->row();
					$_SESSION["who"]["id"]=$r->id;
					$_SESSION["who"]["username"]=$r->username;
					$_SESSION["who"]["admin"]=1;
					header("Location:".site_url()."/admin/users");
				}else{
					header("Location:".site_url()."/admin/login/error");
				}
			}else{
				$error=array();
				if($n=="error"){
					$error[]=array("aa"=>"aa");
				}
				$page["error"]=$error;
				$page["content"]=$this->parser->parse('admin_login.html',$page,TRUE);
			}
		}elseif($m=="newsletter"){
			if($_POST["body"]){
				$q = $this->db->query("SELECT * FROM `users`");
				$css=file_get_contents("style/style.css");
				$body=$_POST["body"];
				$body=str_replace("../../../../",base_url(),$body);
				$body=str_replace("../../../",base_url(),$body);
				$body=str_replace("../../",base_url(),$body);
				if ($q!==false && $q->num_rows()>0){
					foreach($q->result() as $r){
						$header = "From: $SITENAME <newsletter@".base_url().">\r\n";
						$header.= "Content-Type: text/html; charset=UTF-8\n";
						if(mail($r->email,$_POST["subject"],$body,$header)){
							$results.='<div style="color:#00FF00; font-weight:bold; padding:3px;">'.$r->email." - SUCCESS</div>";
						}else{
							$results.='<div style="color:#FF0000; font-weight:bold; padding:3px;">'.$r->email." - ERROR</div>";
						}
					}
				}
				$page["content"]=$results;
			}else{
				$page["ns_content"]='<div id="wrapper">
									<div id="col_header">
									<div class="logo">
									<a href="'.base_url().'">
									<img src="'.base_url().'/images/logo.jpg" alt="" border="0" width="224" height="119" alt="Logo" />
									</a>
									</div>
									  </div>
									  <div style="float:left; width:720px;" id="content">
										<div align="right" class="green"><strong>
										  March Newsletter    </strong></div> 
										<p class="title1">Newsletter Title</p>
										<p class="title2">Newsletter Subtitle</p>
										<p>Newsletter body</p>
									  </div>
									
									</div>';
				$page["content"]=$this->parser->parse('newsletter.html',$page,TRUE);
			}
		}elseif($m=="site_video"){
			if($_FILES){
				$target_path = "uploads/movie2.flv";				
				if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					$page["content"]='<strong class="green">The file '.  basename( $_FILES['uploadedfile']['name']). 
					" has been uploaded</strong>";
				} else{
					$page["content"]= '<strong class="green">There was an error uploading the file, please try again!';
				}

			}else{
				$page["content"]=$this->parser->parse('upload_video.html',$page,TRUE);
			}
		}elseif($m=="change_pass"){
			if($_POST["old_pass"] && $_POST["new_pass"]){
				$q = $this->db->query("SELECT * FROM `admins` WHERE `username`='admin' AND `password`='".$_POST["old_pass"]."'");
				if ($q!==false && $q->num_rows()>0){
					$q = $this->db->query("UPDATE `admins` SET `password`='".$_POST["new_pass"]."' WHERE `username`='admin' AND `password`='".$_POST["old_pass"]."'");
					header("Location:".site_url()."/admin/change_pass/success");
				}else{
					header("Location:".site_url()."/admin/change_pass/error");
				}
			}else{
				$error=array();
				if($n=="success"){
					$error[]=array("msg"=>"Password changed succesfully");
				}
				if($n=="error"){
					$error[]=array("msg"=>"Password NOT changed");
				}
				$page["error"]=$error;
				$page["content"]=$this->parser->parse('admin_pass.html',$page,TRUE);
			}
		}elseif($m=="users"){
			if($n=="delete"){
				$q = $this->db->query("DELETE FROM `users` WHERE `id`='".$p."'");
				header("Location:".site_url()."/admin/users");
			}elseif($n=="active"){
				$q = $this->db->query("UPDATE `users` SET `active`='1' WHERE `id`='".$p."'");
				header("Location:".site_url()."/admin/users");
			}elseif($n=="edit"){
				$q = $this->db->query("SELECT * FROM `users` WHERE `id`='".$p."'");
				$r = $q->row();
				$page["username"]=$r->username;
				$page["email"]=$r->email;
				$page["password"]=$r->password;
				$page["id"]=$r->id;
				$page["content"]=$this->parser->parse('edit_user.html',$page,TRUE);
			}elseif($n=="save"){
				$q = $this->db->query("UPDATE `users` SET `username`='".$_POST["username"]."',`password`='".$_POST["password"]."', `email`='".$_POST["email"]."' WHERE `id`='".$_POST["id"]."'");
				header("Location:".site_url()."/admin/users");
			}else{
				$users=array();
				$q = $this->db->query("SELECT * FROM `users` WHERE `active`='1'");
				if ($q!==false && $q->num_rows()>0){
					foreach($q->result() as $r){
						$users[]=array("username"=>$r->username,"email"=>$r->email,"password"=>$r->password,"joined"=>$r->joined,"id"=>$r->id);
					}
				}
				$nona_users=array();
				$q = $this->db->query("SELECT * FROM `users` WHERE `active`!='1'");
				if ($q!==false && $q->num_rows()>0){
					foreach($q->result() as $r){
						$nona_users[]=array("username"=>$r->username,"email"=>$r->email,"password"=>$r->password,"joined"=>$r->joined,"id"=>$r->id);
					}
				}
				$page["nona_users"]=$nona_users;
				$page["users"]=$users;
				$page["content"]=$this->parser->parse('users.html',$page, TRUE);
			}
		}elseif($m=="cities"){
			if($n=="delete"){
				$q = $this->db->query("DELETE FROM `cities` WHERE `id`='".$p."'");
				header("Location:".site_url()."/admin/cities");
			}elseif($n=="add"){
				if($p){
					$page["states"]=$this->Setup->state_select($p);
				}else{
					$page["states"]=$this->Setup->state_select(0);
				}
				$page["content"]=$this->parser->parse('add_city.html',$page,TRUE);
			}elseif($n=="edit"){
				$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$p."'");
				$r = $q->row();
				$page["name"]=$r->name;
				if($r->big){
					$page["big"]='checked="checked"';
				}else{
					$page["big"]="";
				}
				$page["id"]=$r->id;
				$page["states"]=$this->Setup->state_select($r->state);
				$page["content"]=$this->parser->parse('edit_city.html',$page,TRUE);
			}elseif($n=="save"){
				if($_POST["id"]){
					$q = $this->db->query("UPDATE `cities` SET `name`='".$_POST["name"]."',`state`='".$_POST["state"]."', `big`='".$_POST["big"]."' WHERE `id`='".$_POST["id"]."'");
				}else{
					$q = $this->db->query("INSERT INTO `cities` SET `name`='".$_POST["name"]."',`state`='".$_POST["state"]."', `big`='".$_POST["big"]."'");
				}
				header("Location:".site_url()."/admin/cities");
			}else{
				$states=array();
				$q = $this->db->query("SELECT * FROM `states`");
				if ($q!==false && $q->num_rows()>0){
					foreach($q->result() as $r){
						$cities=array();
						$qc = $this->db->query("SELECT * FROM `cities` WHERE `state`='".$r->id."'");
						if ($qc!==false && $qc->num_rows()>0){
							foreach($qc->result() as $rc){
								$cities[]=array("city_name"=>$rc->name,"city_id"=>$rc->id);
							}
						}
						$states[]=array("state_id"=>$r->id,"state_name"=>$r->name,"cities"=>$cities);
					}
				}
				$page["states"]=$states;
				$page["content"]=$this->parser->parse('manage_cities.html',$page, TRUE);
			}
		}elseif($m=="prices"){
			if($n=="save"){
				foreach($_POST as $f=>$v){
					if($f!="submit"){
						$qc = $this->db->query("UPDATE `prices` SET `price`='".$v."' WHERE `idcat`='".substr($f,3)."'");
					}
				}
				header("Location:".site_url()."/admin/prices");
			}else{
				$q = $this->db->query("SELECT * FROM `prices`");
				if ($q!==false && $q->num_rows()>0){
					foreach($q->result() as $r){
						$page["value_".$r->idcat]=$r->price;
					}
				}
				$page["content"]=$this->parser->parse('prices.html',$page, TRUE);
			}
		}else{
			header("Location:".site_url()."/admin/users");
		}
		$page["page_title"]=ucwords(strtolower($page["page_title"]));
		$this->parser->parse('header_admin.html',$page);
	}
}
?>