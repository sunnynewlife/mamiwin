<?php

class FAppUtilController  extends CController 
{
	//测试用
	public function actionReceive()
	{
		header("Content-Type:application/json;charset=utf-8");
		$ret=array(
			"return_code"	=> -1,
			"return_msg"	=> "no data",
		);
		$raw_post = file_get_contents("php://input");
		if (!empty($raw_post)) {
			$post_data = json_decode($raw_post, true);
			$msgType		=$post_data["msg_type"];
			$msgId			=$post_data["msg_id"];
			$ret=array(
				"return_code"	=> 0,
				"return_msg"	=> "success",
				"data"	=>array(
					"msg_type"	=> $msgType,
					"msg_id"	=> $msgId,	
				),
			);
		}
		echo json_encode($ret,true);
	}
	
	public function actionQueryInfo()
	{
		header("Content-Type:application/json;charset=utf-8");
		$phoneNo=Yii::app()->request->getParam("PhoneNo",'');
		$ret=array(
				"return_code"	=> -1,
				"return_msg"	=>	"no PhoneNo.",
				"data"			=> array("PhoneNo" => $phoneNo ),
		);
		if(empty($phoneNo)==false){
			$fAppData=new FAppData();
			$info=$fAppData->QueryInfo($phoneNo);
			if(count($info)>0){
				$ret["return_code"]	=0;
				$ret["return_msg"]	="query success.";
				$loginChannel="";
				if(isset($info["Memo"]) && empty($info["Memo"])==false){
					$loginInfo=@json_decode($info["Memo"],true);
					if(isset($loginInfo) && is_array($loginInfo) && isset($loginInfo["channel"])){
						$loginChannel=$loginInfo["channel"];
					}
				}
				$data=array(
					"PromoterId"		=> 	$info["PromoterId"],
					"PhoneNo"			=> 	$info["PhoneNo"],
					"AliPayNo"			=> 	$info["AliPayNo"],
					"AliPayName"		=> 	$info["AliPayName"],
					"ClientType"		=> 	(isset($info["ClientType"]) && empty($info["ClientType"])==false)?$info["ClientType"]:"App",
					"ClientAppVersion"	=> 	$info["ClientAppVersion"],
					"RegistChannel"		=>	$info["Channel"],
					"LoginDt"			=>	$info["LoginDt"],
					"LoginIp"			=>	$info["LoginIp"],
					"PhoneType"			=>	$info["PhoneType"],
					"DeviceId"			=>	$info["DeviceId"],
					"LoginChannel"		=>	$loginChannel,
				);
				$ret["data"]	=$data;
			}	
		}
		echo json_encode($ret);
	}
	
	public function actionProfit()
	{
		$appId			=Yii::app()->request->getParam("appId",'');
		if(empty($appId)){
			echo "no parameters.";
			return;
		}
		$fAppData=new FAppData();
		$App=$fAppData->getViewApp($appId);
		if(count($App)==0){
			echo "no ".$appId." game.";
			return;				
		}
		$days		=Yii::app()->request->getParam("day_count",'15');
		if(empty($days) || $days<1 ){
			$days=15;
		}
		
		$data=array("AppName" => $App["AppName"]);
		$currentTime=time();
		
		$endDate=date("Y-m-d 00:00:00",$currentTime);
		$startDate=date("Y-m-d 00:00:00",$currentTime-$days*24*60*60);
		$appEntryDate=$fAppData->getAppEntryDate($appId);
		$startDate=$this->getStartDate($appEntryDate, $startDate);
		
		$profitData=$this->getXAxisRange($startDate, $endDate,$days);
		$prpfitApp=$fAppData->getAppProfitRange($appId,$startDate, $endDate);
		
		foreach ($prpfitApp as $row){
			$profitData[$row["SumDt"]]["v"]=$row["GameAmount"];
		}
		$data["profit"]=$profitData;
		
		$expireTime=mktime(23,59,30,substr($endDate,5,2),substr($endDate,8,2),substr($endDate,0,4));
		$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", $expireTime)." GMT";
		header("Cache-Control: public");
		header("Pragma: cache");
		header($ExpStr);		
		
		$this->layout="profit";
		$this->render("profit",$data);		
	}
	private function getXAxisShownPos($days=15,$dataCount=15)
	{
		if($days==15){
			if($dataCount>=15){
				return array("1"=>1,"5"=>5,"10"=>10,"15"=>15);
			}
			else {
				if($dataCount<=5){
					return array("1"=>1,$dataCount=>$dataCount);
				}
				else if($dataCount<=10){
					$midDay=(int)$dataCount/2;
					return array("1"=>1,$midDay=>$midDay,$dataCount=>$dataCount);
				}else {
					$midDay=5+(int)($dataCount-5)/2;
					return array("1"=>1,"5"=>5,$midDay=>$midDay,$dataCount=>$dataCount);
				}
			}
		}
		else{
			$xAxis=array("1"=>1,$days=>$days);
			$LoopCount=$days/4;
			$lastIndex=1;
			for($index=1;$index<$LoopCount;$index++){
				$lastIndex=$lastIndex+$index*4;
				if($lastIndex<$days){
					$xAxis[$lastIndex]=$lastIndex;
				}
			}
			return $xAxis;
		}
	}
	private function getXAxisRange($startDateInclude,$endDateExlcude,$daysCount=15)
	{
		$profitData=array();
		$endYmd=$this->getYmd($endDateExlcude);
		$startYmd=$this->getYmd($startDateInclude);
		
		$endTime=mktime(0, 0, 0, $endYmd["m"]  , $endYmd["d"], $endYmd["y"]);
		$startTime=mktime(0, 0, 0, $startYmd["m"]  , $startYmd["d"], $startYmd["y"]);
		$oneDaySeconds=24*60*60;
		$dayIndex=1;
		$xShownPos=$this->getXAxisShownPos($daysCount,($endTime-$startTime)/$oneDaySeconds);
		while($startTime<$endTime){
			$key=date("Y-m-d",$startTime);
			$profitData[$key]=array(
				"s"	=>	(array_key_exists($dayIndex,$xShownPos)?date("m/d",$startTime):""),
				"v"	=>	"0.00",	
				);
			$startTime+=$oneDaySeconds;
			$dayIndex++;
		}
		return $profitData;
	}
	private function getStartDate($AppEntryDate,$startDate)
	{
		$appYmd=$this->getYmd($AppEntryDate);
		$startYmd=$this->getYmd($startDate);
		$t1	=	mktime(0, 0, 0, $appYmd["m"]  , $appYmd["d"], $appYmd["y"]);
		$t2	=	mktime(0, 0, 0, $startYmd["m"]  , $startYmd["d"], $startYmd["y"]);
		return date("Y-m-d 00:00:00",($t1>$t2?$t1:$t2));
	}
	private function getYmd($dateStr)
	{
		return array(
			'y'	=> 	substr($dateStr,0,4),
			'm'	=>	substr($dateStr,5,2),
			'd'	=>	substr($dateStr,8,2),		
		);
	}
	public function actionNoPackage()
	{
		$this->layout="error";
		$this->render("error_no_package",array());		
	}
	public function actionBusy()
	{
		$this->layout="error";
		$this->render("error_other",array());		
	}
	
	public function actionShare()
	{
		$appId			=Yii::app()->request->getParam("appId",'');
		$downloadUrl	=Yii::app()->request->getParam("url",'');
		if(empty($appId) || empty($downloadUrl)){
			echo "no parameters.";
			return;
		}
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$fAppData=new FAppData();
		$appInfo=$fAppData->getAppByAppId($appId);
		if(count($appId)<=0){
			echo "no game :".$appId;
			return;				
		}
		$PageTitle=$appInfo["AppName"];
		$PageShareImg="";
		$PageDesc="";
		if(isset($appInfo["WeixinContent"])){
			$PageDesc=$appInfo["WeixinContent"];
		}
		if(isset($appInfo["WeixinPic"])){
			$IconFile=$fAppData->getFileById($appInfo["WeixinPic"]);
			if(count($IconFile)>0){
				$PageShareImg=sprintf("%s%s",$appConfig["FApp_game_domain"],$IconFile["FileUrl"]);
			}			
		}
		if(isset($appInfo["WeixinTitle"])){
			$PageTitle=$appInfo["WeixinTitle"];
		}
		
		
		$IconFile=$fAppData->getFileById($appInfo["FileId"]);
		$ImgName="";
		if(count($IconFile)>0){
			$ImageName=$IconFile["FileUrl"];
		}
		$gameType="";
		$gameTypes=$fAppData->getGameTypeDefinition();
		foreach ($gameTypes as $gameItem){
			if($gameItem["code"]==$appInfo["AppType"]){
				$gameType=$gameItem["name"];
				break;
			}
		}
		$Size="";
		$gamePics=array();
		$appVersion=$fAppData->getLatestAppVersion($appId);
		if(count($appVersion)>0){
			$Size=$appVersion["GameSize"];
			$picIds=explode(",",$appVersion["GamePics"]);
			foreach ($picIds as $pId){
				if(empty($pId)==false){
					$picFile=$fAppData->getFileById($pId);
					if(count($picFile)>0){
						$gamePics[]=sprintf("%s%s",$appConfig["FApp_game_domain"],$picFile["FileUrl"]);
					}
				}	
			}			
		}
		$style="body{font-size:14px;color: #888888;line-height: 1.6;font-family: \"microsoft yahei\"; }\n.wraper{ padding: 10px; position: relative;}\n";
		if($this->isMobileDevice()==false){
			$style="body{font-size:14px;color: #888888;line-height: 1.6;font-family: \"microsoft yahei\"; background: #EFEFEF;}\n.wraper{ padding: 10px; position: relative;width: 320px;margin: 0 auto; border-left: 1px solid #CCC; border-right: 1px solid #CCC;background: #FFF;}\n";
		}
		
		$data=array(
			"GameName"	=> 	$appInfo["AppName"],
			"Size"		=>	$Size,
			"AppDetail"	=>	$appInfo["AppDetail"],
			"GameIcon"	=>	sprintf("%s%s",$appConfig["FApp_game_domain"],$ImageName),
			"GameType"	=>	$gameType,
			"GamePics"	=>  $gamePics,
			"DownloadUrl"	=>	$downloadUrl,
			"PageTitle"	=>	$PageTitle,
			"PageShareImg"=>	$PageShareImg,
			"PageDesc"	=>	$PageDesc,
			"Style"		=>  $style,
		);
		
		$this->layout="share";
		$this->render("share",$data);		
	}
	
	public function actionChannelShow()
	{
		$channelId	=Yii::app()->request->getParam("channelId",'');
		$phone		=Yii::app()->request->getParam("phone",'');
		$userToken	=Yii::app()->request->getParam("userToken",'');
		
		if(empty($channelId) || empty($phone)){
			echo "no parameters.";
			return;
		}
		$fAppData=new FAppData();
		$msg_list=$fAppData->getUserChannelList($phone, $channelId);
		$picFiles=$fAppData->getFiles();
		$appConf=LunaConfigMagt::getInstance()->getAppConfig();
		
		foreach ($msg_list as & $rowItem){
			$picName="";
			foreach ($picFiles as $picItem){
				if($picItem["FileId"]==$rowItem["ImageUrl"]){
					$picName=$picItem["FileUrl"];
					break;
				}
			}
			if(empty($picName)){
				$rowItem["Image_Path"]="";
			}else{
				$rowItem["Image_Path"]=sprintf("%s%s",$appConf["FApp_game_domain"],$picName);
			}				
		}
		
		$data=array(
				"userToken" => $userToken,
				"msg_list"	=> $msg_list,
		);
		$this->layout="msg_list";
		$this->render("list",$data);
	}
	
	public function actionAppHelp()
	{
		$this->layout="help";
		$data=array("HelpItems" => $this->getHelpData());
		$this->render("help",$data);
	}
	
	private function isMobileDevice()
	{
		$mobileAgent=array("iphone" =>"iphone",
				"ipad" =>"ipad",
				"itouch" =>"itouch",
				"netfront" =>"netfront",
				"midp-2.0" =>"midp-2.0",
				"opera mini" =>"opera mini",
				"ucweb" =>"ucweb",
				"android" =>"android",
				"windows ce" =>"windows ce",
				"symbianos" =>"symbianos",
				"windows mobile" =>"windows mobile",
				"windows phone" =>"windows phone",
				"240x320" =>"240x320",
				"acer" =>"acer","acoon" =>"acoon","acs-" =>"acs-","abacho" =>"abacho","ahong" =>"ahong","airness" =>"airness","alcatel" =>"alcatel","amoi" =>"amoi","anywhereyougo.com" =>"anywhereyougo.com","applewebkit/525" =>"applewebkit/525","applewebkit/532" =>"applewebkit/532","asus" =>"asus","audio" =>"audio","au-mic" =>"au-mic","avantogo" =>"avantogo",
				"becker" =>"becker","benq" =>"benq","bilbo" =>"bilbo","bird" =>"bird","blackberry" =>"blackberry","blazer" =>"blazer","bleu" =>"bleu",
				"cdm-" =>"cdm-","compal" =>"compal","coolpad" =>"coolpad",
				"danger" =>"danger","dbtel" =>"dbtel","dopod" =>"dopod",
				"elaine" =>"elaine","eric" =>"eric","etouch" =>"etouch",
				"fly" =>"fly",
				"go.web" =>"go.web","goodaccess" =>"goodaccess","gradiente" =>"gradiente","grundig" =>"grundig",
				"haier" =>"haier","hedy" =>"hedy","hitachi" =>"hitachi","htc" =>"htc","huawei" =>"huawei","hutchison" =>"hutchison",
				"inno" =>"inno","ipaq" =>"ipaq","ipod" =>"ipod",
				"jbrowser" =>"jbrowser",
				"kddi" =>"kddi","kgt" =>"kgt","kwc" =>"kwc",
				"lenovo" =>"lenovo","lg" =>"lg","longcos" =>"longcos",
				"maemo" =>"maemo","mercator" =>"mercator","meridian" =>"meridian","micromax" =>"micromax","midp" =>"midp","mini" =>"mini","mitsu" =>"mitsu","mmm" =>"mmm","mmp" =>"mmp","mobi" =>"mobi","mot-" =>"mot-","moto" =>"moto",
				"nec-" =>"nec-","newgen" =>"newgen","nexian" =>"nexian","nf-browser" =>"nf-browser","nintendo" =>"nintendo","nitro" =>"nitro","nokia" =>"nokia","nook" =>"nook","novarra" =>"novarra",
				"obigo" =>"obigo",
				"palm" =>"palm","panasonic" =>"panasonic","pantech" =>"pantech","philips" =>"philips","phone" =>"phone","pg-" =>"pg-","playstation" =>"playstation","pocket" =>"pocket","pt-" =>"pt-",
				"qc-" =>"qc-","qtek" =>"qtek",
				"rover" =>"rover",
				"sagem" =>"sagem","sama" =>"sama","samu" =>"samu","sanyo" =>"sanyo","samsung" =>"samsung","sch-" =>"sch-","scooter" =>"scooter","sec-" =>"sec-","sendo" =>"sendo","sgh-" =>"sgh-","sharp" =>"sharp","siemens" =>"siemens","sie-" =>"sie-","softbank" =>"softbank","sony" =>"sony","spice" =>"spice","sprint" =>"sprint","spv" =>"spv","symbian" =>"symbian",
				"talkabout" =>"talkabout","tcl-" =>"tcl-","teleca" =>"teleca","telit" =>"telit","tianyu" =>"tianyu","tim-" =>"tim-","toshiba" =>"toshiba","tsm" =>"tsm",
				"up.browser" =>"up.browser","utec" =>"utec","utstar" =>"utstar",
				"verykool" =>"verykool","virgin" =>"virgin","vk-" =>"vk-","voda" =>"voda","voxtel" =>"voxtel","vx" =>"vx",
				"wap" =>"wap","wellco" =>"wellco","wig browser" =>"wig browser","wii" =>"wii","wireless" =>"wireless",
				"xda" =>"xda","xde" =>"xde","zte" =>"zte",				
		);
		$isMobile=false;
		if(isset($_SERVER["HTTP_USER_AGENT"])){
			$useAgent=strtolower($_SERVER["HTTP_USER_AGENT"]);
			foreach( $mobileAgent as $userAgentKey )
			{
				if(strpos($useAgent,$userAgentKey)){
					$isMobile=true;
					break;
				}
			}
		}
		return $isMobile;
	}
	
	
	
	private function getHelpData()
	{
		return array(
			array(	"q"	=>	"1. 什么是红金宝？",
					"a"	=>	"红金宝是一款通过分享游戏获得分红返利的手机应用软件，专为游戏爱好者或者有资源的推广员搭建的一个赚钱平台。目前仅提供安卓版本。",),
			array(  "q"	=>	"2. 如何成为红金宝用户？",
					"a" =>	"下载并安装红金宝手机客户端，第一次打开客户端后完成手机绑定，即填入手机号码并获取下行短信后正确填入指定输入框内，成功完成上述流程后您就成为红金宝用户了；您绑定的手机号码是您唯一的账号信息，因此请您务必保证是本人的号码，以免为他人做嫁衣裳哦！ ",),				
			array(  "q"	=>	"3. 我已经是红金宝用户，如何赚钱？",
					"a" =>	"点击“赚钱”页游戏列表中任意一款游戏右边的“点击分享”按钮，将游戏信息分享给您的朋友（游戏信息中包含了您专属游戏包的下载链接）。您的朋友下载登录成功后，一旦在您的游戏包内充值，您就可以马上获得返利。",),				
			array(  "q"	=>	"4. 我怎么把红金宝上的游戏包下载到手机上和电脑上?",
					"a" =>	"微信：点开您发布在微信中的分享信息，点击页面详情中的“下载”按钮即可将游戏包下载到本地。<br/>短信：打开短信中的“链接”对应的游戏介绍页面，点击页面中的“下载“按钮即可将游戏包下载到本地。",),				
			array(  "q"	=>	"5. 我自己玩红金宝里面的游戏可以给自己返利吗?",
					"a" =>	"只要您使用的是您的专属游戏包，自己消费也同样可以获得返利，相关信息将体现在红金宝产品中。 ",),				
			array(  "q"	=>	"6. 除了红金宝提供的几种分享方式外，还有别的方法吗?",
					"a" =>	"您可以通过红金宝提供的几种分享方式获得您需要的属于您的专属游戏下载链接。用此链接将游戏下载到本地后（如电脑），即可使用其他通讯软件将您的游戏包分享给您的朋友们。",),				
			array(  "q"	=>	"7. 我还有一部苹果手机，可以使用红金宝软件吗?",
					"a" =>	"红金宝产品目前只有安卓版本，很抱歉您的苹果手机暂时无法使用红金宝产品。",),				
			array(  "q"	=>	"8. 我的朋友使用的是苹果手机，可以玩我分享的游戏吗?",
					"a" =>	"红金宝产品目前只有安卓版本，推广的游戏也都是安卓版本的，所以您的朋友如果使用的是苹果手机，暂时还没有办法玩。 ",),				
			array(  "q"	=>	"9. 我推广后如何知道谁玩了我的游戏？谁为我带来了返利？多久能够知道？",
					"a" =>	"请您在登录红金宝后进入【财富】->【累积财富】->【推广用户】可以查看哪些用户下载安装您分享的游戏包并成功登录；进入【财富】->【累积财富】->【财富明细】->【收入明细】可以查看到每一笔您推广玩家的充值及对应分红的记录，每一笔分红还会以消息的方式及时通知。以上信息，系统都会实时通过红金宝展示给您。",),				
			array(  "q"	=>	"10. 我获得的返利可以立即提现吗?",
					"a" =>	"您获得的已经进入帐户余额的返利均可立即提现，提现操作成功后一般在48小时内可到账。",),				
			array(  "q"	=>	"11. 我推广的玩家充值是否永远都会分给我?",
					"a" =>	"您推广的玩家必须使用您专属的游戏包充值才能返利给您；如果您推广的玩家使用其他的游戏包充值，则您无法获得返利。",),				
			array(  "q"	=>	"12. 我有两个支付宝账号，可以都绑定吗?",
					"a" =>	"红金宝产品目前只提供绑定一个支付宝提现账户，如果您需要往其他支付宝账户提现，请先修改绑定支付宝账户再提现；目前仅支持同一个真实姓名下的不同支付宝账户的修改；即已经成功绑定了“张三“的支付宝账号A，修改支付宝账号时也只能是”张三“名下的其他支付宝账号，而不能是”李四“的支付宝账号。",),				
			array(  "q"	=>	"13. 为什么有时候提现收取手续费，有时候不收?",
					"a" =>	"红金宝产品目前的手续费金额收取规则是：首次提现任意大于零的金额均不收取任何手续费，从第二次提现开始：当提现金额大于等于10元则无需手续费；当提现金额小于10元则需要收取0.5元手续费（支付宝的收费标准）；如果想要避免手续费，您可以每次提现金额大于等于10元即可。",),				
			array(  "q"	=>	"14. 提现操作完成后，钱多久能到我支付宝账户?",
					"a" =>	"红金宝产品目前提现到账支持48小时内到账。",),				
			array(  "q"	=>	"15. 为什么我提现时候提示我“冻结中”?",
					"a" =>	"可能是您的账户存在安全风险或有资金来源异常，为保证资金的安全红金宝平台通过该方式进行资金保护和安全核查；处于“冻结”状态并不影响您的收入结算，只是暂停提现操作；一般3-7个工作日后，经过系统评估会自动解冻，若有其他问题或要延期解冻会有客服与您联系。",),				
			array(  "q"	=>	"16. 不联网是否可以使用红金宝?",
					"a" =>	"不联网时，红金宝产品仅能看到您上次缓存的游戏和财富信息，不能获取最新的数据，可能跟真实数据有误差，且不能够进行分享操作。",),				
			array(  "q"	=>	"17. 我以前使用的手机号码不用了，我之前手机号码对应的收入怎么办?",
					"a" =>	"由于手机号码是您红金宝唯一账号信息，如果您准备取消手机号码，请您先将对应账户中的分红金额全部提取，并不要再用此手机号码对应的专用游戏包推广，否则您手机号码注销后的分红收益将不能再提取。",),				
			array(  "q"	=>	"18. 手机丢了怎么办?",
					"a" =>	"到手机运营商进行SIM卡补卡，补卡后，在装有新SIM卡的手机（号码不变）上重新安装和登录软件。",),				
			array(  "q"	=>	"19. 我在操作中遇到错误怎么办？",
					"a" =>	"操作过程中如果遇到无法解决的错误，建议尝试以下几个方面：<br>1）卸载原软件后并尝试重新安装最新版本的软件；<br>2）检查手机的联网状态是否正常；<br>3）如果还未能解决，您可以将问题反馈给我们。",),				
		);
	}
}
?>