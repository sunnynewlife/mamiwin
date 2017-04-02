<?php

LunaLoader::import("luna_lib.verify.ILunaImgCodeDrawer");

class LunaImgElephant implements ILunaImgCodeDrawer{
	
	private $width;
	private $height;
	
	private $_noise_line_count=6;
	private $_noise_snowflake_count=100;
	
	//实现者相关参数
	private $_font_size=20;
	
	
	private $_img;
	
	private function draw_backgroud()
	{
		$rand_color_min=157;
		$rand_color_max=255;
		$color = imagecolorallocate($this->_img, mt_rand($rand_color_min,$rand_color_max), mt_rand($rand_color_min,$rand_color_max), mt_rand($rand_color_min,$rand_color_max));
		imagefilledrectangle($this->_img,0,$this->height,$this->width,0,$color);		
	}
	private function draw_noise()
	{
		$rand_color_min=0;
		$rand_color_max=156;
		for ($i=0;$i<$this->_noise_line_count;$i++) {
			$color = imagecolorallocate($this->_img,mt_rand($rand_color_min,$rand_color_max),mt_rand($rand_color_min,$rand_color_max),mt_rand($rand_color_min,$rand_color_max));
			imageline($this->_img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
		$rand_color_min=200;
		$rand_color_max=255;
		for ($i=0;$i<$this->_noise_snowflake_count;$i++) {
			$color = imagecolorallocate($this->_img,mt_rand($rand_color_min,$rand_color_max),mt_rand($rand_color_min,$rand_color_max),mt_rand($rand_color_min,$rand_color_max));
			imagestring($this->_img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
		}	
	}
	
	function init($configure,$width,$height)
	{
		$this->width=$width;
		$this->height=$height;
	}
	function getImgDatas($code)
	{
		$this->_img=imagecreatetruecolor($this->width, $this->height);
		$this->draw_backgroud();
		$this->draw_noise();
		
		$font_name=dirname(__FILE__)."/font/Elephant.ttf";
		
		$code_len=strlen($code);
		$_x = $this->width / $code_len;
		$rand_color_min=0;
		$rand_color_max=156;
		for ($i=0;$i<$code_len;$i++) {
			$font_color = imagecolorallocate($this->_img,mt_rand($rand_color_min,$rand_color_max),mt_rand($rand_color_min,$rand_color_max),mt_rand($rand_color_min,$rand_color_max));
			imagettftext($this->_img,$this->_font_size,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$font_color,$font_name,$code[$i]);
		}
		
		$img_name="/tmp/".uniqid().".png";
		imagepng($this->_img,$img_name);
		if($img_file=fopen($img_name,"rb")){
			$Img_Content=fread($img_file,filesize($img_name));
			fclose($img_file);
		}
		@unlink($img_name);
		imagedestroy($this->_img);
		return $Img_Content;
	}
	function getImgContentType(){
		return "image/png";
	}	
}
