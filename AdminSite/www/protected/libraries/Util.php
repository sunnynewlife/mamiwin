<?php
class Util {
	const OFFSET=3;
	/*
	//获取OSAdmin的action_url，用于权限验证
	public static function getActionUrl(){
		$action_script=$_SERVER['SCRIPT_NAME'];
		$admin_url = strtolower(CONF_MAIN_DOMAIN);
		if($admin_url{strlen($admin_url)-1}=="/"){
			$admin_url = substr($admin_url,0,strlen($admin_url)-1);
		}
	
		$http_pos = strpos($admin_url,'http://');
		
		if($http_pos !== false){
			$admin_url_no_http = substr($admin_url,7);			
		}else{
			$admin_url_no_http=$admin_url;
		}
		$slash = 0;
		$slash=strpos($admin_url_no_http,'/');
		
		if($slash){
			$sub_dir = substr($admin_url_no_http,$slash);
			$action_url = substr($action_script,strlen($sub_dir));
		}else{
			$action_url =$action_script;
		}
		return str_replace('//','/',$action_url);
	}
	*/

	public static function getSysInfo() {
		$sys_info_array = array ();
		$sys_info_array ['gmt_time'] = gmdate ( "Y年m月d日 H:i:s", time () );
		$sys_info_array ['bj_time'] = gmdate ( "Y年m月d日 H:i:s", time () + 8 * 3600 );
		$sys_info_array ['server_ip'] = gethostbyname ( $_SERVER ["SERVER_NAME"] );
		$sys_info_array ['software'] = $_SERVER ["SERVER_SOFTWARE"];
		$sys_info_array ['port'] = $_SERVER ["SERVER_PORT"];
		$sys_info_array ['admin'] = isset($_SERVER ["SERVER_ADMIN"])?$_SERVER ["SERVER_ADMIN"]:"" ;
		$sys_info_array ['diskfree'] = intval ( diskfreespace ( "." ) / (1024 * 1024) ) . 'Mb';
		$sys_info_array ['current_user'] = @get_current_user ();
		$sys_info_array ['timezone'] = date_default_timezone_get();
		return $sys_info_array;
	}
	
	
	/**
	 * 分页
	 * @param unknown $link
	 * @param unknown $page_no
	 * @param unknown $page_size
	 * @param unknown $row_count
	 * @return string
	 */
	public static function showPager($link,&$page_no,$page_size,$row_count){
		$url="";
		$params="";
		if($link != ""){
			$pos = strpos($link,"?");
	
			if($pos ===false ){
				$url = $link;
			}else{
				$url=substr($link,0,$pos);
				$params=substr($link,$pos+1);
			}
		}
			
		$navibar = "<div class=\"pagination\"><ul>";
		$offset=self::OFFSET;
		//$page_size=10;
		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
	
		$page_no=$page_no<1?1:$page_no;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		if ($page_no > 1){
			$navibar .= "<li><a href=\"$url?$params&page_no=1\">首页</a></li>\n <li><a href=\"$url?$params&page_no=".($page_no-1)." \">上一页</a></li>\n";
		}
		/**** 显示页数 分页栏显示11页，前5条...当前页...后5条 *****/
		$start_page = $page_no -$offset;
		$end_page =$page_no+$offset;
		if($start_page<1){
			$start_page=1;
		}
		if($end_page>$total_page){
			$end_page=$total_page;
		}
		for($i=$start_page;$i<=$end_page;$i++){
			if($i==$page_no){
				$navibar.= "<li><span>$i</span></li>";
			}else{
				$navibar.= "<li><a href=\" $url?$params&page_no=$i \">$i</a></li>";
			}
		}
	
		if ($page_no < $total_page){
			$navibar .= "<li><a href=\"$url?$params&page_no=".($page_no+1)."\">下一页</a></li>\n <li><a href=\"$url?$params&page_no=$total_page\">末页</a></li>\n ";
		}
		if($total_page>0){
			$navibar.="<li><a>".$page_no ."/". $total_page."</a></li>";
		}
		$navibar.="<li><a>共".$row_count."条</a></li>";
		$jump ="";
		//$jump ="<li><form action='$url' method='GET' name='jumpForm'><input type='text' name='page_no' value='$page_no'></form></li>";
	
		$navibar.=$jump;
		$navibar.="</ul></div>";
	
		return $navibar;
	}	
	
	
	public static function dump($vars, $label = '', $return = false) {
    	if (ini_get(html_errors)) {
    		$content = "<pre>\n";
    		if ($label != '' ) {
    			$content .= "<strong>{$label} :</strong>\n";
    		}
    		$content .= htmlspecialchars(print_r($vars, true));
    		$content .= "\n</pre>\n";
    	} else {
    		$content = $label . " :\n" . print_r($vars, true);
    	}
    	if ($return) { return $content; }
    	echo $content;
    	return null;
    }

    public static function get_extension($filename){
		$path_info = pathinfo($filename);
		$ext = $path_info['extension'];
		return $ext;
	}	
}

?>