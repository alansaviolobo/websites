<?
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Setup extends Model
{
	function Setup()
	{
		parent::Model();
	}
	function us_cities($big){
		if($big){
			$q = $this->db->query("SELECT * FROM `cities` WHERE `big`='1'");
		}else{
			$q = $this->db->query("SELECT * FROM `cities`");
		}
		$us_cities=array();
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$us_cities[]=array("city_id"=>$r->id,"city_name"=>$r->name);
			}
		}
		return $us_cities;
	}
	function us_states(){
		$q = $this->db->query("SELECT * FROM `states`");
		$us_states=array();
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$us_states[]=array("state_id"=>$r->id,"state_name"=>$r->name);
			}
		}
		return $us_states;
	}
	function state_select($p){
		$q = $this->db->query("SELECT * FROM `states`");
		$us_states=array();
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				if($p==$r->id){
					$selected='selected="selected"';
				}else{
					$selected="";
				}
				$us_states[]=array("state_id"=>$r->id,"state_name"=>$r->name,"selected"=>$selected);
			}
		}
		return $us_states;
	}
	function show_categories($location){
		$cat_list=array();
		$q = $this->db->query("SELECT * FROM `categories` WHERE `parent`='0' AND `id`!='117'");
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$sub_cat_list=array();
				$qs = $this->db->query("SELECT * FROM `categories` WHERE `parent`='".$r->id."' ORDER BY `name` ASC");
				if ($qs!==false && $qs->num_rows()>0){
					foreach($qs->result() as $rs){
						$sub_cat_list[]=array("sub_cat_name"=>$rs->name,"sub_cat_link"=>"listings/".$location."/".$rs->id);
					}
				}
				$cat_list[]=array("cat_name"=>$r->name,"sub_cat_list"=>$sub_cat_list);
			}
		}
		return $cat_list;
	}
	function show_post_categories($location){
		$cat_list=array();
		$q = $this->db->query("SELECT * FROM `categories` WHERE `parent`='0'");
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$sub_cat_list=array();
				$qs = $this->db->query("SELECT * FROM `categories` WHERE `parent`='".$r->id."' ORDER BY `name` ASC");
				if ($qs!==false && $qs->num_rows()>0){
					foreach($qs->result() as $rs){
						$sub_cat_list[]=array("sub_cat_name"=>$rs->name,"sub_cat_link"=>"listings/add/".$location."/".$rs->id,"sub_cat_id"=>$rs->id);
					}
				}
				$cat_list[]=array("cat_name"=>$r->name,"sub_cat_list"=>$sub_cat_list);
			}
		}
		return $cat_list;
	}
	function comm_rows($n){
		$comm_rows=array();
		$i=0;
		$link="listings/".$n."/";
		$qs = $this->db->query("SELECT * FROM `categories` WHERE `parent`='117' ORDER BY `name` ASC");
		if ($qs!==false && $qs->num_rows()>0){
			foreach($qs->result() as $rs){
				if($i%2==0){
					$comm_name=$rs->name;
					$comm_id=$rs->id;
				}else{
					$comm_rows[]=array("comm1_name"=>$comm_name,"comm2_name"=>$rs->name,"comm2_link"=>$link."".$rs->id,"comm1_link"=>$link."".$comm_id);
				}
				$i++;
			}
		}
		return $comm_rows;
	}
	function show_search($search){
		$listings=array();
		$sql="SELECT `listings`.*,`cities`.`name` AS `city_name`,`categories`.`name` AS `cat_name` FROM `listings`,`cities`,`categories` WHERE `listings`.`city`=`cities`.`id` AND `listings`.`category`=`categories`.`id` AND (`listings`.`title` LIKE '%".$search."%' OR `listings`.`body` LIKE '%".$search."%' OR `listings`.`location` LIKE '%".$search."%' OR `categories`.`name` LIKE '%".$search."%' OR `cities`.`name` LIKE '%".$search."%') AND `listings`.`active`='1' ";
		$day_list=array();
		$date=date("Ymd");
		$sql.="ORDER BY `id` DESC";
		$q = $this->db->query($sql);
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				if($r->date!=$date){
					$date=$r->date;
					if(count($day_list)){
						$listings[]=array("list_day"=>date("F j, Y",mktime(0,0,0,substr($r_old->date,4,2),substr($r_old->date,6,2),substr($r_old->date,0,4))),"day_list"=>$day_list);
					}
					$day_list=array();
				}
				$imgs=array();
				$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
				if($qi->num_rows()>0){
					$imgs[]=array("aa"=>"aa");
				}
				$day_list[]=array("list_id"=>$r->id,"list_name"=>$r->title,"list_location"=>$r->city_name,"list_cat"=>$r->cat_name,"list_imgs"=>$imgs);
				$r_old=$r;
			}
			if(count($day_list)){
				$listings[]=array("list_day"=>date("F j, Y",mktime(0,0,0,substr($r->date,4,2),substr($r->date,6,2),substr($r->date,0,4))),"day_list"=>$day_list);
			}
		}
		return $listings;
	}
	function show_listings($m,$n,$p){
		$listings=array();
		$sql="SELECT `listings`.*,`cities`.`name` AS `city_name`,`categories`.`name` AS `cat_name` FROM `listings`,`cities`,`categories` WHERE `listings`.`city`=`cities`.`id` AND `listings`.`category`=`categories`.`id` AND `listings`.`active`='1' ";
		if($m){
			$sql.="AND (`city`='".$m."' OR `cities` LIKE '%,".$m.",%') ";
		}
		if($n){
			$sql.="AND `category`='".$n."' ";
		}
		$sql.="ORDER BY `id` DESC";
		$day_list=array();
		$date=date("Ymd");
		
		$q = $this->db->query($sql);
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				if($r->date!=$date){
					$date=$r->date;
					if(count($day_list)){
						$listings[]=array("list_day"=>date("F j, Y",mktime(0,0,0,substr($r_old->date,4,2),substr($r_old->date,6,2),substr($r_old->date,0,4))),"day_list"=>$day_list);
					}
					$day_list=array();
				}
				$imgs=array();
				$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
				if($qi->num_rows()>0){
					$imgs[]=array("aa"=>"aa");
				}
				$day_list[]=array("list_id"=>$r->id,"list_name"=>$r->title,"list_location"=>$r->city_name,"list_cat"=>$r->cat_name,"list_imgs"=>$imgs);
				$r_old=$r;
			}
			if(count($day_list)){
				$listings[]=array("list_day"=>date("F j, Y",mktime(0,0,0,substr($r->date,4,2),substr($r->date,6,2),substr($r->date,0,4))),"day_list"=>$day_list);
			}
		}
		return $listings;
	}
	function show_classes($m,$n,$p){
		$sql="SELECT `listings`.*,`cities`.`name` AS `city_name`,`categories`.`name` AS `cat_name` FROM `listings`,`cities`,`categories` WHERE `listings`.`city`=`cities`.`id` AND `listings`.`category`=`categories`.`id` AND `start_date`<='".$p."' AND `end_date`>='".$p."' AND `category`='132' AND `listings`.`active`='1' ";
		if($m){
			$sql.="AND `city`='".$m."' ";
		}
		$day_list=array();		
		$q = $this->db->query($sql);
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$imgs=array();
				$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
				if($qi->num_rows()>0){
					$imgs[]=array("aa"=>"aa");
				}
				$title=$r->title;
				if($r->start_date){
					$add=str_replace("-",".",$r->start_date);
					if($r->end_date){
						$add.=" - ".str_replace("-",".",$r->end_date);
					}
					$title=$add.": ".$title;
				}
				$day_list[]=array("list_id"=>$r->id,"list_name"=>$title,"list_location"=>$r->city_name,"list_cat"=>$r->cat_name,"list_imgs"=>$imgs);
			}
		}
		return $day_list;
	}
	function show_events($m,$n,$p){
		$sql="SELECT `listings`.*,`cities`.`name` AS `city_name`,`categories`.`name` AS `cat_name` FROM `listings`,`cities`,`categories` WHERE `listings`.`city`=`cities`.`id` AND `listings`.`category`=`categories`.`id` AND `start_date`<='".$p."' AND `end_date`>='".$p."' AND `category`='124' AND `listings`.`active`='1' ";
		if($m){
			$sql.="AND `city`='".$m."' ";
		}
		$day_list=array();		
		$q = $this->db->query($sql);
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$imgs=array();
				$qi = $this->db->query("SELECT * FROM `images` WHERE `list_id`='".$r->id."'");
				if($qi->num_rows()>0){
					$imgs[]=array("aa"=>"aa");
				}
				$title=$r->title;
				if($r->start_date){
					$add=str_replace("-",".",$r->start_date);
					if($r->end_date){
						$add.=" - ".str_replace("-",".",$r->end_date);
					}
					$title=$add.": ".$title;
				}
				$day_list[]=array("list_id"=>$r->id,"list_name"=>$title,"list_location"=>$r->city_name,"list_cat"=>$r->cat_name,"list_imgs"=>$imgs);
			}
		}
		return $day_list;
	}
	function show_cities($n){
		if($n){
			$q = $this->db->query("SELECT * FROM `cities` WHERE `state`='".$n."'");
		}else{
			$q = $this->db->query("SELECT * FROM `cities`");
		}
		$us_cities=array();
		if ($q!==false && $q->num_rows()>0){
			foreach($q->result() as $r){
				$us_cities[]=array("city_id"=>$r->id,"city_name"=>$r->name);
			}
		}
		return $us_cities;
	
	}
	function create_captcha($uid,$string){
		$captcha = imagecreatefrompng("./images/captcha.png");

		$black = imagecolorallocate($captcha, 0, 0, 0);
		$line = imagecolorallocate($captcha,182,182,182);
		
		for($i=0;$i<15;$i++)
		imageline($captcha,rand(0,254),rand(0,91),rand(0,254),rand(0,91),$line);
		
		
		$font = 'amsterdam.ttf';
		imagettftext($captcha, rand(24,25),rand(-20,20), rand(20,150), rand(30,50), $black, $font, $string);
		
		imagegif($captcha,"./images/captchas/".$uid.".gif", 100);
	}
	function calendar($m){
         //If no parameter is passed use the current date.
         if($date == null){
            $date = date("Y-m-d");
         }
		 $dt=explode("-",$date);
         $day = $dt[2];
         $month = $dt[1];
         $month_name = $date["month"];
         $year = $dt[0];
         
         $this_month = getDate(mktime(0, 0, 0, $month, 1, $year));
         $next_month = getDate(mktime(0, 0, 0, $month + 1, 1, $year));
         
         //Find out when this month starts and ends.         
         $first_week_day = $this_month["wday"];
         $days_in_this_month = round(($next_month[0] - $this_month[0]) / (60 * 60 * 24));
                          
         $calendar_html = '<table style="color:#0ad322; font-size:14px;" cellspacing="4" cellpadding="4">';
         
         $calendar_html .= "<tr align=\"center\" style=\"background-color:#3a3a3a; color:#eaeaea;\"><td>S</td><td>M</td><td>T</td><td>W</td><td>T</td><td>F</td><td>S</td></tr>";
                           
         $calendar_html .= "<tr style=\"background-color:#e5e5e5;\">";
          
         //Fill the first week of the month with the appropriate number of blanks.       
         for($week_day = 0; $week_day < $first_week_day; $week_day++)
            {
            $calendar_html .= "<td style=\"background-color:none;\"> </td>";   
            }
            
         $week_day = $first_week_day;
         for($day_counter = 1; $day_counter <= $days_in_this_month; $day_counter++)
            {
            $week_day %= 7;
            
            if($week_day == 0)
               $calendar_html .= "</tr><tr style=\"background-color:#e5e5e5;\">";
            
            //Do something different for the current day.
            if($day == $day_counter)   
               $calendar_html .= '<td align=\"center\" style=\"font-weight:bold;\"><a href="'.base_url().'listings/'.$m.'/121/'.$year."-".$month."-".$day_counter.'" style="text-decoration:none; color:#0ad322;"><strong>'.
                                 $day_counter . "</strong></a></td>";
            else
               $calendar_html .= '<td align=\"center\" style=\"background-color:#e5e5e5;\"><a href="'.base_url().'listings/'.$m.'/121/'.$year."-".$month."-".$day_counter.'" style="text-decoration:none; color:#0ad322;">'.
                                 $day_counter . "</a></td>";
            
            $week_day++;
            }
            
         $calendar_html .= "</tr>";
         $calendar_html .= "</table>";
                   
         return($calendar_html);
	}
}
?>
