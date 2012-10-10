<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Listings extends Controller {
	function Listings(){
		error_reporting("E_ALL");
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
		$page["page_title"]="Some 365 Classified Ads";
		$login=array();
		$account=array();
		if(!$_SESSION["who"]["id"]){
			$login[]=array("aa"=>"aa");
		}else{
			$account[]=array("name"=>$_SESSION["who"]["username"]);
		}
		$page["login"]=$login;
		$page["account"]=$account;
		if($m=="" || $m==0){
			$location = "United States";
			$page["page_title"]="Some 365 Classified Ads - All Cities";	
		}else{
			$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$m."'");
			$r = $q->row();
			$location = $r->name;
			$page["page_title"]=strtoupper($r->name)." Classified Ads";	
		}
		if($n=="" || $n==0){
			$location.= " > All Categories";
			$page["page_title"].=" > All Categories";	
		}else{
			$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$n."'");
			$r = $q->row();
			$location.= " > ".$r->name;
			$page["page_title"].=" > ".$r->name;
		}
		$page['location']=$location;
		$m=$this->uri->segment(2);
		$n=$this->uri->segment(3);
		$p=$this->uri->segment(4);
		$o=$this->uri->segment(5);
		$s=$this->uri->segment(6);
		$page["us_cities"]=$this->Setup->us_cities(1);
		$page["us_states"]=$this->Setup->us_states();
		$page["comm_rows"]=$this->Setup->comm_rows($m);
		if($m=="details"){
			unset($_SESSION["return"]);
			$q = $this->db->query("SELECT `listings`.*,`categories`.`id` as `par_id` FROM `listings`,`categories` WHERE `listings`.`category`=`categories`.`id` AND `listings`.`id`='".$n."'");
			$r = $q->row();
			$page["comm_rows"]=$this->Setup->comm_rows($r->category);
			$page["page_title"]="Miami Classified Ads - ".$r->title;
			$page["listing_title"]=$r->title;
			$page["id"]=$r->id;
			$page["listing_uid"]=$r->uid;
			$admin_menu=array();
			if($_SESSION["who"]["admin"]==1){
				$admin_menu[]=array("aa"=>"aa");
			}
			$page["admin_menu"]=$admin_menu;
			$images=array();
			$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
			if ($qi!==false && $qi->num_rows()>0){
				foreach($qi->result() as $ri){
					$images[]=array("img_file"=>$ri->file,"img_id"=>$ri->id);
				}
			}
			$page["images"]=$images;
			$qr = $this->db->query("SELECT AVG(rating) as avg FROM `reviews` WHERE `user`='".$r->owner."'");
			$rr=$qr->row();
			$page["rating"]=substr($rr->avg,0,strlen($rr->avg)-2);
			if($r->age){
				$page["listing_title"].=" - ".$r->age;
			}
			if($r->location){
				$page["listing_title"].=" (".$r->location.")";
			}
			if($r->start_date!="0000-00-00"){
				$add=str_replace("-",".",$r->start_date);
				if($r->end_date!="0000-00-00"){
					$add.=" - ".str_replace("-",".",$r->end_date);
				}
				$page["listing_title"]=$add.": ".$page["listing_title"];
			}
			$q = $this->db->query("SELECT * FROM `users` WHERE `id`='".$r->owner."'");
			$ro = $q->row();
			$page["listing_owner"]=$ro->username;
			$page["listing_owner_id"]=$ro->id;
			if($r->email_show==1){
				$page["listing_email"]=$r->email;
			}elseif($r->email_show==3){
				$page["listing_email"]="see below";
			}else{
				$page["listing_email"]='<a href="'.site_url().'/listings/contact/'.$ro->id.'" class="nyroModal">click here to contact</a>';
			}
			$page["listing_date"]=substr($r->date,0,4)."-".substr($r->date,2,2)."-".substr($r->date,4,2)." ".$r->time;
			$page["listing_body"]=$r->body;
			if($r->add_street){
				$address=$r->add_street;
			}
			if($r->add_cross_street){
				$address.=" at ".$r->add_cross_street;
			}
			if($r->add_city){
				$address.=" ".$r->add_city;
			}
			if($r->add_state){
				$address.=" ".$r->add_state." US";
			}
			if($address){
				$page["listing_body"].='<div style="padding-top:15px;" class="green">'.$address.' - <strong><a href="http://maps.yahoo.com/map?ard=1&q1='.$address.'" class="green" target="_blank">See Map</a></strong></div>';
			}
			$listing_info=array();
			if($r->location){
				$listing_info[]=array("info_body"=>"Location: ".$r->location);
			}
			if($r->cats){
				$listing_info[]=array("info_body"=>"Cats are OK");
			}
			if($r->dogs){
				$listing_info[]=array("info_body"=>"Dogs are OK");
			}
			if($r->other_int){
				$listing_info[]=array("info_body"=>"you can contact this poster with services or other commercial interests");
			}else{
				$listing_info[]=array("info_body"=>"it's NOT ok to contact this poster with services or other commercial interests");
			}
			if($r->commuting){
				$listing_info[]=array("info_body"=>"Commuting are OK");
			}
			if($r->parttime){
				$listing_info[]=array("info_body"=>"Part-time is OK");
			}
			if($r->contract){
				$listing_info[]=array("info_body"=>"This is a contract job");
			}
			if($r->nonprofit){
				$listing_info[]=array("info_body"=>"This is at a non-profit organization");
			}
			if($r->internship){
				$listing_info[]=array("info_body"=>"This is an intern position");
			}
			if($r->par_id==3){
				if($r->recruitors){
					$listing_info[]=array("info_body"=>"Recruitors can contact this job poster");
				}else{
					$listing_info[]=array("info_body"=>"Principals only. Recruiters, please don't contact this job poster. ");
				}
			}
			if($r->calls){
				$listing_info[]=array("info_body"=>"Please, no phone calls about this job!");
			}
			if($r->compensation){
				$listing_info[]=array("info_body"=>"Compensation: ".$r->compensation);
			}
			$page["listing_info"]=$listing_info;
			$page["content"]=$this->parser->parse('details.html',$page, TRUE);
		}elseif($m=="delete_img"){
			$q = $this->db->query("SELECT * FROM `images` WHERE `id`='".$n."'");
			$r = $q->row();
			unlink("listing_images/".$r->id."_".$r->file);
			$q = $this->db->query("DELETE FROM `images` WHERE `id`='".$n."'");
			header("Location:".$_SERVER['HTTP_REFERER']);
		}elseif($m=="contact"){
			$_SESSION["return"]=$_SERVER['HTTP_REFERER'];
			$page["id"]=$n;
			echo $this->parser->parse('contact_me.html',$page);
			die;
		}elseif($m=="contact_send"){
			$q = $this->db->query("SELECT * FROM `users` WHERE `id`='".$_POST["id"]."'");
			$ro = $q->row();
			$header = "From: ". $_POST["name"] . " <" . $_POST["email"] . ">\r\n";
			mail($ro->email,"Contact from a prospect from $SITENAME",$_POST["body"],$header);
			//echo $this->parser->parse('contact_sent.html',$page);
			$return=$_SESSION["return"];
			unset($_SESSION["return"]);
			header("Location:".$return);
		}elseif($m=="search"){
			$page["page_title"]="Miami Classified Ads - Search Results";
			$page["comm_rows"]=$this->Setup->comm_rows(0);
			$page["listings"]=$this->Setup->show_search($_POST["search"]);
			$no_results=array();
			if(count($page["listings"])==0){
				$no_results[]=array("aa"=>"aa");
			}
			$page["no_results"]=$no_results;
			$page["content"]=$this->parser->parse('listings.html',$page, TRUE);
		}elseif($m=="post"){
			header("Location:".site_url()."/listings/add/".$n."/".$p);
		}elseif($m=="delete"){
			if(!$_SESSION["who"]["admin"]){
				$q = $this->db->query("DELETE FROM `listings` WHERE `uid`='".$n."' AND `owner`='".$_SESSION["who"]["id"]."'");
			}else{
				$q = $this->db->query("DELETE FROM `listings` WHERE `uid`='".$n."'");
			}
			header("Location:".site_url()."/account/main/");
		}elseif($m=="add_mult"){
			if(!$_SESSION["who"]["admin"]){
				header("Location:".site_url()."/account/login/");
			}
			if(!$n){
				$page["city_list"]=$this->Setup->show_cities(0);
				$page["content"]=$this->parser->parse('post_mult_cities.html',$page, TRUE);
			}elseif(!$p){
				$page["cities"]=",".implode(",",$_POST["cities"]).",";
				$page["cat_list"]=$this->Setup->show_post_categories($n);
				$page["content"]=$this->parser->parse('post_mult_categories.html',$page, TRUE);
			}else{
				$uid=rand(1000000000,9999999999);
				$this->db->query("INSERT INTO `listings` SET `uid`='".$uid."',`category`='".$_POST["category"]."', `cities`='".$_POST["cities"]."',`owner`='".$_SESSION["who"]["id"]."'");
				$page["uid"]=$uid;
				$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='1'");
				$r = $q->row();
				$page["city_name"]=$r->name;
				$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$_POST["category"]."'");
				$r = $q->row();
				$page["category_name"]=$r->name;
				
				$q = $this->db->query("SELECT * FROM `listings` WHERE `uid`='".$uid."'");
				$r = $q->row();
				foreach($r as $f=>$v){
					if($f!="email_show"){
						if($v==1){
							$value='checked="checked"';
						}else{
							$value=$v;
						}
						$page[$f]=$value;
					}
				}
				$page["email_show1"]="";
				$page["email_show2"]="";
				$page["email_show3"]="";
				if($r->email_show==1){
					$page["email_show1"]='checked="checked"';
				}elseif($r->email_show==3){
					$page["email_show3"]='checked="checked"';
				}else{
					$page["email_show2"]='checked="checked"';
				}
				$uploads=array();
				$images=array();
				for($i=0;$i<4;$i++){
					$uploads[]=array("iid"=>$i);
				}
				$page["uploads"]=$uploads;
				$page["images"]=$images;
				$page["city"]=1;
				$page["category"]=$_POST["category"];
				$p=(int)$_POST["category"];
				if($p>5 && $p<14){
					$page["content"]=$this->parser->parse('post_rs_rent.html',$page, TRUE);
				}elseif($p==14 || $p==15){
					$page["content"]=$this->parser->parse('post_rs_comm.html',$page, TRUE);
				}elseif($p>27 && $p<45){
					$page["content"]=$this->parser->parse('post_regular.html',$page, TRUE);
				}elseif($p>44 && $p<78){
					$page["content"]=$this->parser->parse('post_job.html',$page, TRUE);
				}elseif($p>77 && $p<87){
					$page["content"]=$this->parser->parse('post_pers2.html',$page, TRUE);
				}elseif($p==124 || $p==132){
					$page["end_mo"]=$page["end_day"]=$page["st_mo"]=$page["st_day"]="";
					$page["end_year"]=$page["st_year"]=2008;
					$page["content"]=$this->parser->parse('post_event.html',$page, TRUE);
				}elseif($p>86 && $p<116){
					$page["content"]=$this->parser->parse('post_forsale.html',$page, TRUE);
				}else{
					$page["content"]=$this->parser->parse('post_regular.html',$page, TRUE);
				}				
			}
		}elseif($m=="add"){
			$page["page_title"]="Miami Classified Ads - Add Listing";
			if(!$_SESSION["who"]["id"]){
				header("Location:".site_url()."/account/login/");
			}
			if(!$n || !is_numeric($n)){
				if($n=="cities"){
					if($p=="" || $p==0){
						$location = "United States";
					}else{
						$q = $this->db->query("SELECT * FROM `states` WHERE `id`='".$p."'");
						$r = $q->row();
						$location = $r->name;
					}
					$page['location']=$location;
					$page["city_list"]=$this->Setup->show_cities($p);
					$page["content"]=$this->parser->parse('post_cities.html',$page, TRUE);
				}else{
					$page["states_list"]=$this->Setup->us_states($n);
					$page["content"]=$this->parser->parse('post_states.html',$page, TRUE);
				}
			}elseif(!$p || !is_numeric($p)){
				$page["cat_list"]=$this->Setup->show_post_categories($n);
				$page["content"]=$this->parser->parse('post_categories.html',$page, TRUE);
			}else{
				$uid=rand(1000000000,9999999999);
				$this->db->query("INSERT INTO `listings` SET `uid`='".$uid."',`owner`='".$_SESSION["who"]["id"]."'");
				$page["uid"]=$uid;
				$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$n."'");
				$r = $q->row();
				$page["city_name"]=$r->name;
				$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$p."'");
				$r = $q->row();
				$page["category_name"]=$r->name;
				
				$q = $this->db->query("SELECT * FROM `listings` WHERE `uid`='".$uid."'");
				$r = $q->row();
				foreach($r as $f=>$v){
					if($f!="email_show"){
						if($v==1){
							$value='checked="checked"';
						}else{
							$value=$v;
						}
						$page[$f]=$value;
					}
				}
				$page["email_show1"]="";
				$page["email_show2"]="";
				$page["email_show3"]="";
				if($r->email_show==1){
					$page["email_show1"]='checked="checked"';
				}elseif($r->email_show==3){
					$page["email_show3"]='checked="checked"';
				}else{
					$page["email_show2"]='checked="checked"';
				}
				$uploads=array();
				$images=array();
				for($i=0;$i<4;$i++){
					$uploads[]=array("iid"=>$i);
				}
				$page["uploads"]=$uploads;
				$page["images"]=$images;
				
				$page["city"]=$n;
				$page["category"]=$p;
				$p=(int)$p;
				if($p>5 && $p<14){
					$page["content"]=$this->parser->parse('post_rs_rent.html',$page, TRUE);
				}elseif($p==14 || $p==15){
					$page["content"]=$this->parser->parse('post_rs_comm.html',$page, TRUE);
				}elseif($p>27 && $p<45){
					$page["content"]=$this->parser->parse('post_regular.html',$page, TRUE);
				}elseif($p>44 && $p<78){
					$page["content"]=$this->parser->parse('post_job.html',$page, TRUE);
				}elseif($p>77 && $p<87){
					$page["content"]=$this->parser->parse('post_pers2.html',$page, TRUE);
				}elseif($p==124 || $p==132){
					$page["end_mo"]=$page["end_day"]=$page["st_mo"]=$page["st_day"]="";
					$page["end_year"]=$page["st_year"]=2008;
					$page["content"]=$this->parser->parse('post_event.html',$page, TRUE);
				}elseif($p>86 && $p<116){
					$page["content"]=$this->parser->parse('post_forsale.html',$page, TRUE);
				}else{
					$page["content"]=$this->parser->parse('post_regular.html',$page, TRUE);
				}				
			}
		}elseif($m=="edit"){
				$page["page_title"]="Miami Classified Ads - Edit Listing";
				if(!$_SESSION["who"]["id"]){
					header("Location:".site_url()."/account/login/");
				}
				$q = $this->db->query("SELECT * FROM `listings` WHERE `uid`='".$n."'");
				$r = $q->row();
				$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$r->city."'");
				$r1 = $q->row();
				$page["city_name"]=$r1->name;
				$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$r->category."'");
				$r1 = $q->row();
				$page["category_name"]=$r1->name;
				foreach($r as $f=>$v){
					if($f!="email_show"){
						if($v==1){
							$value='checked="checked"';
						}else{
							$value=$v;
						}
						$page[$f]=$value;
					}
				}
				$uploads=array();
				$images=array();
				$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
				if ($qi!==false && $qi->num_rows()>0){
					foreach($qi->result() as $ri){
						$images[]=array("id"=>$r->id,"img_file"=>$ri->file,"img_id"=>$ri->id);
					}
				}
				for($i=mysql_affected_rows();$i<4;$i++){
					$uploads[]=array("iid"=>$i);
				}
				$page["uploads"]=$uploads;
				$page["images"]=$images;
				
				$page["email_show1"]="";
				$page["email_show2"]="";
				$page["email_show3"]="";
				if($r->email_show==1){
					$page["email_show1"]='checked="checked"';
				}elseif($r->email_show==3){
					$page["email_show3"]='checked="checked"';
				}else{
					$page["email_show2"]='checked="checked"';
				}
				
				$page["city"]=$r->city;
				$page["category"]=$r->category;
				$p=$r->category;
				if($p>5 && $p<14){
					$page["content"]=$this->parser->parse('post_rs_rent.html',$page, TRUE);
				}elseif($p==14 || $p==15){
					$page["content"]=$this->parser->parse('post_rs_comm.html',$page, TRUE);
				}elseif($p>27 && $p<45){
					$page["content"]=$this->parser->parse('post_regular.html',$page, TRUE);
				}elseif($p>44 && $p<78){
					$page["content"]=$this->parser->parse('post_job.html',$page, TRUE);
				}elseif($p>77 && $p<87){
					$page["content"]=$this->parser->parse('post_pers.html',$page, TRUE);
				}elseif($p>86 && $p<116){
					$page["content"]=$this->parser->parse('post_forsale.html',$page, TRUE);
				}elseif($p==124 || $p==132){
					$page["st_year"]=substr($r->start_date,0,4);
					$page["st_day"]=substr($r->start_date,8,2);
					$page["st_mo"]=substr($r->start_date,5,2);
					$page["end_year"]=substr($r->end_date,0,4);
					$page["end_day"]=substr($r->end_date,8,2);
					$page["end_mo"]=substr($r->end_date,5,2);
					$page["content"]=$this->parser->parse('post_event.html',$page, TRUE);
				}else{
					$page["content"]=$this->parser->parse('post_regular.html',$page, TRUE);
				}
		}elseif($m=="save"){
			$page["page_title"]="Miami Classified Ads - Add Listing";
			$sql="UPDATE `listings` SET ";
			foreach($_POST as $f=>$v){
				if($f!="uid" && $f!="submit" && $f!="email2" && $f!="pt1" && $f!="pt2" && $f!="st_day" && $f!="st_mo" && $f!="st_year" && $f!="end_day" && $f!="end_mo" && $f!="end_year" && substr($f,0,5)!="image"){
					if($f=="title" && $_POST["pt1"]){
						$v.=" - ".$_POST["pt1"]."4".$_POST["pt2"];
					}
					$sql.="`".$f."`='".mysql_escape_string($v)."',";
				}
			}
			if($_POST["st_day"] && $_POST["st_mo"] && $_POST["st_year"]){
				$sql.="`start_date`='".$_POST["st_year"]."-".$_POST["st_mo"]."-".$_POST["st_day"]."',";
			}
			if($_POST["end_day"] && $_POST["end_mo"] && $_POST["end_year"]){
				$sql.="`end_date`='".$_POST["end_year"]."-".$_POST["end_mo"]."-".$_POST["end_day"]."',";
			}
			$sql=substr($sql,0,strlen($sql)-1);
			$target_path = "listing_images/";
			if($_SESSION["who"]["admin"]==1){
				$sql.=" WHERE `uid`='".$_POST["uid"]."'";
			}else{
				$sql.=" WHERE `uid`='".$_POST["uid"]."' AND `owner`='".$_SESSION["who"]["id"]."'";
			}
			$q = $this->db->query($sql);
			
			$q = $this->db->query("SELECT `id` FROM `listings` WHERE `uid`='".$_POST["uid"]."'");
			$rid= $q->row();
			
			$images=array();
			$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$rid->id."'");
			if ($qi!==false && $qi->num_rows()>0){
				foreach($qi->result() as $ri){
					$images[]=array("id"=>$rid->id,"img_file"=>$ri->file,"img_id"=>$ri->id);
				}
			}
			$page["images"]=$images;
			
			foreach($_FILES as $f=>$v){
				if($_FILES[$f]['name']){
					$q = $this->db->query("INSERT INTO `images` SET `list_id`='".$rid->id."',`file`='".$_FILES[$f]['name']."'");
					$target_path = $target_path.$rid->id."_".basename( $_FILES[$f]['name']); 
					move_uploaded_file($_FILES[$f]['tmp_name'], $target_path);
				}
			}		
			
			$q = $this->db->query("SELECT `listings`.*,`categories`.`id` as `par_id` FROM `listings`,`categories` WHERE `listings`.`category`=`categories`.`id` AND `listings`.`uid`='".$_POST["uid"]."'");
			$r = $q->row();
			$page["comm_rows"]=$this->Setup->comm_rows($r->category);
			$page["listing_title"]=$r->title;
			if($r->age){
				$page["listing_title"].=" - ".$r->age;
			}
			if($r->location){
				$page["listing_title"].=" (".$r->location.")";
			}
			if($r->start_date!="0000-00-00"){
				$add=str_replace("-",".",$r->start_date);
				if($r->end_date!="0000-00-00"){
					$add.=" - ".str_replace("-",".",$r->end_date);
				}
				$page["listing_title"]=$add.": ".$page["listing_title"];
			}
			$page["listing_email"]=$r->email;
			$page["listing_date"]=substr($r->date,0,4)."-".substr($r->date,2,2)."-".substr($r->date,4,2)." ".$r->time;
			$page["listing_body"]=$r->body;
			$listing_info=array();
			if($r->location){
				$listing_info[]=array("info_body"=>"Location: ".$r->location);
			}
			if($r->cats){
				$listing_info[]=array("info_body"=>"Cats are OK");
			}
			if($r->dogs){
				$listing_info[]=array("info_body"=>"Dogs are OK");
			}
			if($r->other_int){
				$listing_info[]=array("info_body"=>"you can contact this poster with services or other commercial interests");
			}else{
				$listing_info[]=array("info_body"=>"it's NOT ok to contact this poster with services or other commercial interests");
			}
			if($r->commuting){
				$listing_info[]=array("info_body"=>"Commuting are OK");
			}
			if($r->parttime){
				$listing_info[]=array("info_body"=>"Part-time is OK");
			}
			if($r->contract){
				$listing_info[]=array("info_body"=>"This is a contract job");
			}
			if($r->nonprofit){
				$listing_info[]=array("info_body"=>"This is at a non-profit organization");
			}
			if($r->internship){
				$listing_info[]=array("info_body"=>"This is an intern position");
			}
			if($r->par_id==3){
				if($r->recruitors){
					$listing_info[]=array("info_body"=>"Recruitors can contact this job poster");
				}else{
					$listing_info[]=array("info_body"=>"Principals only. Recruiters, please don't contact this job poster. ");
				}
			}
			if($r->calls){
				$listing_info[]=array("info_body"=>"Please, no phone calls about this job!");
			}
			if($r->compensation){
				$listing_info[]=array("info_body"=>"Compensation: ".$r->compensation);
			}
			$page["uid"]=$_POST["uid"];
			$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$_POST["city"]."'");
			$r = $q->row();
			$page["city_name"]=$r->name;
			$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$_POST["category"]."'");
			$r = $q->row();
			$page["category_name"]=$r->name;
			$page["listing_info"]=$listing_info;
			$page["content"]=$this->parser->parse('post_review.html',$page, TRUE);
			
		}elseif($m=="activate"){
			$page["page_title"]="Miami Classified Ads - Add Listing";
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			$md5 = md5(microtime() * mktime());
			$string = substr($md5,0,5);
						
			$sql="UPDATE `listings` SET `string`='".$string."' WHERE `uid`='".$n."'";
			if($_SESSION["who"]["admin"]!=1){
				$sql.=" AND `owner`='".$_SESSION["who"]["id"]."'";
			}
			$q = $this->db->query($sql);
			
			$q = $this->db->query("SELECT * FROM `listings` WHERE `uid`='".$n."'");
			$rl = $q->row();
							
			$this->Setup->create_captcha($n,$rl->string);
			
			$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$rl->city."'");
			$r = $q->row();
			$page["city_name"]=$r->name;
			$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$rl->category."'");
			$r = $q->row();
			$page["category_name"]=$r->name;
			
			$page["uid"]=$n;
			$page["content"]=$this->parser->parse('post_activate.html',$page, TRUE);
		}elseif($m=="pay"){
			$page["page_title"]="Miami Classified Ads - Add Listing";
			$q = $this->db->query("SELECT * FROM `listings` WHERE `uid`='".$n."'");
			$rl = $q->row();
			if(strtolower($_POST["code"])!=trim(strtolower($rl->string))){
				header("Location:".site_url()."/listings/activate/".$n."/error");
			}else{
				$qc = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$rl->category."'");
				$rc = $qc->row();
				$qp = $this->db->query("SELECT * FROM `prices` WHERE `idcat`='".$rc->parent."'");
				$rp = $qp->row();
				if($rp->price=="0" || $_SESSION["who"]["admin"]==1 || $rp->active==1){
					$q = $this->db->query("UPDATE `listings` SET `active`='1' WHERE `uid`='".$n."'");
					header("Location:".site_url()."/listings/finish/".$n."/ok");
				}else{
					$page["id"]=$rl->id;
					$page["uid"]=$rl->uid;
					$page["price"]=$rp->price;
					
					$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$rl->city."'");
					$r = $q->row();
					$page["city_name"]=$r->name;
					$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$rl->category."'");
					$r = $q->row();
					$page["category_name"]=$r->name;
					$page["content"]=$this->parser->parse('pay.html',$page, TRUE);
				}
			}
		}elseif($m=="finish"){
			$page["page_title"]="Miami Classified Ads - Add Listing";
			$q = $this->db->query("SELECT * FROM `listings` WHERE `uid`='".$n."'");
			$rl = $q->row();
			
			if(!$rl->date){
				$q = $this->db->query("UPDATE `listings` SET `date`='".date("Ymd")."',`time`='".date("G:i:s T")."' WHERE `uid`='".$n."'");
			}
			
			$page["id"]=$rl->id;
			$page["uid"]=$rl->uid;
			
			$q = $this->db->query("SELECT * FROM `cities` WHERE `id`='".$rl->city."'");
			$r = $q->row();
			$page["city_name"]=$r->name;
			$q = $this->db->query("SELECT * FROM `categories` WHERE `id`='".$rl->category."'");
			$r = $q->row();
			$page["category_name"]=$r->name;
			
			
			if($p=="ok"){
				if($rl->active==1){
					$page["content"]=$this->parser->parse('finish_OK.html',$page, TRUE);
				}else{
					$page["content"]=$this->parser->parse('finish_FAILED.html',$page, TRUE);
				}
			}else{
				$page["content"]=$this->parser->parse('finish_CANCEL.html',$page, TRUE);
			}
			
		}else{
			if($n==28){
				header("Location:http://www.everypiecedelivered.com/");
			}
			if($n==53){
				header("Location:http://www.thebreakcorner.com/");
			}
			if($n==134){
				header("Location:http://www.bangbus.com/t1/pps=solomon/");
			}
			$page["city_id"]=$m;
			$page["cat_id"]=$n;
			unset($_SESSION["return"]);
			if($n!=124 && $n!=132){
				if(!$p){
					$date=date("Y-m-d");
				}else{
					$date=$p;
				}
				$no_results=array();
				$page["listings"]=$this->Setup->show_listings($m,$n,$p);
				if(count($page["listings"])==0){
					$no_results[]=array("aa"=>"aa");
				}
				$page["no_results"]=$no_results;
				$page["content"]=$this->parser->parse('listings.html',$page, TRUE);
			}else{
				$class_no_results=array();
				$event_no_results=array();
				$page["class_list"]=$this->Setup->show_classes($m,$n,$date);
				if(count($page["class_list"])==0){
					$class_no_results[]=array("aa"=>"aa");
				}
				$page["event_list"]=$this->Setup->show_events($m,$n,$date);
				if(count($page["event_list"])==0){
					$event_no_results[]=array("aa"=>"aa");
				}
				$page["class_no_results"]=$class_no_results;
				$page["event_no_results"]=$event_no_results;
				$page["content"]=$this->parser->parse('classes_events.html',$page, TRUE);
			}		
		}
		$page["calendar"]=$this->Setup->calendar($m);
		$page["page_title"]=ucwords(strtolower($page["page_title"]));
		$this->parser->parse('header.html',$page);
	}
}
?>