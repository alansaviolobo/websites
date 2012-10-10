<?
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Setup extends Model
{
	function Setup()
	{
		parent::Model();
	}
	function check_login()
	{
		if (isset($_SESSION['who']['username']) && isset($_SESSION['who']['password']))
		{
			$username = mysql_real_escape_string($_SESSION['who']['username']);
			$password = mysql_real_escape_string($_SESSION['who']['password']);
			if (strlen($username)>0 && strlen($password)>0)
			{
				$q = $this->db->query("SELECT * FROM `users` WHERE `email`='".$username."'");
				if ($q!==false && $q->num_rows()>0)
				{
					$r = $q->row();
					if ($r->pass == $password){
						$_SESSION['who']['name']=$r->fname;
						if ($r->admin == 1){
							$_SESSION['who']['admin']=1;
						}
						$q->free_result();
						return true;
					}
				}
				$q->free_result();
			}
		}
		return false;
	}
	function save_post_img($name,$max_x,$max_y,$path,$water=false)
	{
		if (isset($_FILES[$name]) && isset($_FILES[$name]['tmp_name']) && $_FILES[$name]['size']>0 && is_file($_FILES[$name]['tmp_name']) && strpos($_FILES[$name]['name'],'.php')===false && strpos($_FILES[$name]['type'],'image/')==0 && strpos($_FILES[$name]['type'],'image/')!==false)
		{
			$error = error_reporting();
			error_reporting(0);
			$img = @imagecreatefromjpeg($_FILES[$name]['tmp_name']);
			error_reporting($error);
			if (!is_resource($img)) return false;
			$x = imagesx($img); $y = imagesy($img);
			if ($x>$max_x || $y>$max_y)
			{//resize
				$new_x = $x; $new_y = $y;
				if (($new_x>$max_x) || ($new_y>$max_y))
				{
					if ( ($new_x/$max_x)>($new_y/$max_y) )
					{
						$r = $new_x/$max_x; $new_x = $max_x; $new_y /= $r;
					}else
					{
						$r = $new_y/$max_y; $new_y = $max_y; $new_x /= $r;
					}
				}
				$img2 = imagecreatetruecolor($new_x,$new_y);
				imagecopyresampled($img2,$img,0,0,0,0,$new_x,$new_y,$x,$y);
				if ($water===true) $this->water($img2);
				imagejpeg($img2,$path,100);
				imagedestroy($img2);
				imagedestroy($img);
				return true;
			}elseif ($water===true)
			{
				$this->water($img);
				imagejpeg($img,$path,100);
				imagedestroy($img);
				return true;
			}else
			{//do not resize or watermark, just save
				imagedestroy($img);
				$f = fopen($_FILES[$name]['tmp_name'],"rb");
				$photo = fread($f,filesize($_FILES[$name]['tmp_name']));
				fclose($f);
				$f = fopen($path,"wb");
				fwrite($f,$photo);
				fclose($f);
				return true;
			}
		}
		return false;
	}
	function water(&$img)
	{
		$size_x = imagesx($img); $size_y = imagesy($img); // becouse id could be resized!!
		$img_water = imagecreatefrompng('images/img_watermark.png');
		//$img_water = imagecreatefromgif('img/img_watermark.gif');
		$w_x = imagesx($img_water); $w_y = imagesy($img_water);
		$step_x = $size_x/$w_x; $step_y = $size_y/$w_y;
		if ($step_x==0)$step_x = 1; if($step_y==0)$step_y=1;
		for ($i=0;$i<$step_x;$i++)
			for ($j=0;$j<$step_y;$j++)
			{
				$f_x = ($i+1)*$w_x; $f_y = ($j+1)*$w_y;//final x/y - for last repeat to not go outside the image
				if ($f_x>$size_x) $width_x = $size_x - $w_x*$i; else $width_x = $w_x;
				if ($f_y>$size_y) $width_y = $size_y - $w_y*$j; else $width_y = $w_y;
				imagecopyresampled($img,$img_water,$i*$w_x,$j*$w_y,0,0,$width_x,$width_y,$width_x,$width_y);
			}
		imagedestroy($img_water);
	}
	function proc_img($path_load,$path_save,$max_x,$max_y,$water=false,$photo_id=0)
	{
		$x_to_save = 0; $y_to_save = 0;
		$error = error_reporting();
		error_reporting(0);
		$img = @imagecreatefromjpeg($path_load);
		error_reporting($error);
		if (!is_resource($img)) return false;
		$x = imagesx($img); $y = imagesy($img);
		$x_to_save = $x; $y_to_save = $y;
		if ($x>$max_x || $y>$max_y)
		{//resize
			$new_x = $x; $new_y = $y;
			if (($new_x>$max_x) || ($new_y>$max_y))
			{
				if ( ($new_x/$max_x)>($new_y/$max_y) )
				{
					$r = $new_x/$max_x; $new_x = $max_x; $new_y /= $r;
				}else
				{
					$r = $new_y/$max_y; $new_y = $max_y; $new_x /= $r;
				}
			}
			$x_to_save = $new_x; $y_to_save = $new_y;
			$img2 = imagecreatetruecolor($new_x,$new_y);
			imagecopyresampled($img2,$img,0,0,0,0,$new_x,$new_y,$x,$y);
			if ($water===true) $this->water($img2);
			imagejpeg($img2,$path_save,100);
			imagedestroy($img2);
			imagedestroy($img);
		}elseif ($water===true)
		{
			$this->water($img);
			imagejpeg($img,$path_save,100);
			imagedestroy($img);
		}else
		{//do not resize or watermark, just save
			imagedestroy($img);
			$f = fopen($path_load,"rb");
			$photo = fread($f,filesize($path_load));
			fclose($f);
			$f = fopen($path_save,"wb");
			fwrite($f,$photo);
			fclose($f);
		}
		if ($photo_id>0)
			$this->db->query("UPDATE `` SET `size_x`='".$x_to_save."',`size_y`='".$y_to_save."' WHERE `id`='".$photo_id."'");
		return true;
	}
	function get_photo_size($path,$max_x,$max_y)
	{
		if (!file_exists($path)) return array('x'=>$max_x,'y'=>$max_y);;
		$error = error_reporting();
		error_reporting(0);
		$img = imagecreatefromjpeg($path);
		error_reporting($error);
		if (!is_resource($img)) return array('x'=>$max_x,'y'=>$max_y);
		$x = imagesx($img); $y = imagesy($img);
		imagedestroy($img);
		$new_x = $x; $new_y = $y;
		if (($new_x>$max_x) || ($new_y>$max_y))
		{
			if ( ($new_x/$max_x)>($new_y/$max_y) )
			{
				$r = $new_x/$max_x; $new_x = $max_x; $new_y /= $r;
			}else
			{
				$r = $new_y/$max_y; $new_y = $max_y; $new_x /= $r;
			}
		}
		return array('x'=>$new_x,'y'=>$new_y);
	}
}
?>