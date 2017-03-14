<?php

LunaLoader::import("luna_lib.util.LunaMemcache");
LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.CGuidManager");

class Footer extends CWidget
{
	const WX_APP_ID			="wx8a417e8a8315d161";
	const WX_SECRET			="c458469a3a7ba712d2d8408dc488a45a";
	
	public function run() 
	{
		$renderData=array(
			"A1"	=> 	array("url"	=> "/wx/index","class" => "menu_item"),
			"A2"	=>	array("url"	=> "/wx/gift","class" => "menu_item"),
			"A3"	=>	array("url"	=> "#","class" => "menu_item"),
			"A31"	=>	array("url"	=>	"/wx/myMoney","class" => "menu_item"),
			"A32"	=>	array("url"	=>	"/wx/myGift","class" => "menu_item"),
            "A33"   =>  array("url" =>  "/wx/myCoupon","class" => "menu_item"),
			"A4"	=>	array("url"	=> "/wx/lottery","class" => "menu_item"),
			"A34"	=>  array("url" =>  "/wx/home","class" => "menu_item"),
		);
		$requestUri=Yii::app()->request->getRequestUri();
        if(empty($requestUri) ||  $requestUri=="/" || $requestUri=="/wx/index" || stripos($requestUri,"wx/index")>0 || stripos($requestUri, "wx/evn")>0 || stripos($requestUri, "wx/evnJoin")>0  ){
			$renderData["A1"]["url"]="#";
			$renderData["A1"]["class"]="menu_item cur";
		}
		else if($requestUri=="/wx/gift" || stripos($requestUri, "wx/giftDetail")>0 || stripos($requestUri, "wx/giftCodeDraw")>0){ 
			$renderData["A2"]["url"]="#";
			$renderData["A2"]["class"]="menu_item cur";
		}
		else if(stripos($requestUri,"wx/myMoney")>0 || stripos($requestUri,"wx/getRedEnvelope")>0 || stripos($requestUri,"wx/sendRedEnvelope")>0){
			$renderData["A31"]["url"]="#";
			$renderData["A31"]["class"]="menu_item cur";				
		}
		else if(stripos($requestUri,"wx/myGift")>0){
			$renderData["A32"]["url"]="#";
			$renderData["A32"]["class"]="menu_item cur";
		}
		else if(stripos($requestUri,"wx/myCoupon")>0 || stripos($requestUri,"wx/couponUsing")>0){
			$renderData["A33"]["url"]="#";
			$renderData["A33"]["class"]="menu_item cur";
		}
		else if(stripos($requestUri,"wx/lottery")>0){
			$renderData["A4"]["url"]="#";
			$renderData["A4"]["class"]="menu_item cur";
		}
		else{
			$renderData["A3"]["class"]="menu_item";
		}		
		$renderData["JSSDKConfig"]=$this->getJSSDKData();	
        $renderData["JSSDKShareInfo"]=$this->getShareCfg($requestUri);
		
		//开发功能中的菜单
		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
		$renderData["DevelopingFunc"]= $this->checkDevelopingMenuShown($appConfig);

        $renderData["MenuShow"] = true;
        if(strripos($requestUri, "isShared") != false){
            //分享出去的不显示底部菜单 
            $renderData["MenuShow"] = false;
        }
        $this->render('footer',$renderData);     
    }
    
    private function checkDevelopingMenuShown($appConfig)
    {
    	if(isset($appConfig["showDevelopingMenu"]) && $appConfig["showDevelopingMenu"]=="true" && isset($appConfig["shownMenuAcl"]) && empty($appConfig["shownMenuAcl"])==false){
    		if(isset(Yii::app()->session["user"])){
    			$user=Yii::app()->session["user"];
    			if(isset($user["phone"]) && empty($user["phone"])==false && stripos(",".$appConfig["shownMenuAcl"],$user["phone"])>0){
    				return "1";
    			}
    		}
    	}
    	return "0";
    }
    
    private function getShareCfg($requestUri)
    {
    	$shareCfg=array(
    		"img"			=>	"http://dd.f.sdo.com/static/img/logo.png",
    		"share"			=>	"“逗逗游戏”",
    		"send_title"	=>	"“逗逗游戏”",
    		"send_desc"		=>	"“逗逗游戏”",
    		"page_id"		=>	"evn_list",	
    	);
    	$fAppData=new FAppData();
    	$shareData=$fAppData->getSystemConfigItem("tencent.share.cfg","",true);
    	if(isset($shareData) && count($shareData)>0){
    		$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
    		
	    	if(empty($requestUri) ||  $requestUri=="/" || $requestUri=="/wx/index"){	//热门
	    		if(isset($shareData["hot"])){
	    			$shareCfg=$this->mergeShareCfg($shareCfg, $shareData["hot"]);
	    		}
	    		$shareCfg["page_id"]="evn_list";
	    	}
	    	else if($requestUri=="/wx/gift"){											//礼包
	    		if(isset($shareData["gift"])){
	    			$shareCfg=$this->mergeShareCfg($shareCfg, $shareData["gift"]);
	    		}
	    		$shareCfg["page_id"]="gift_list";
	    	}
	    	else if(stripos($requestUri, "wx/evn")>0 || stripos($requestUri, "wx/evnJoin")>0 ){	//活动详情页
	    		if(isset($shareData["hot_detail"])){
	    			$idx=Yii::app()->request->getParam('idx',"");
	    			$varParams=array();
	    			if(empty($idx)==false){
	    				$promotionEvn=$fAppData->getQ_AppPromotionEvnByIdx($idx);
	    				if(isset($promotionEvn) && count($promotionEvn)>0){
	    					$varParams=array(
	    							"img"	=>	sprintf("%s%s",$appConfig["FApp_game_domain"],$promotionEvn["FileUrl"]),
	    							"var"	=>	array("AppName"=>$promotionEvn["AppName"],"EvnName" =>$promotionEvn["EvnName"],"MaxProprate" => bcmul($promotionEvn["Prorate"],"100",0),),
	    					);
	    				}
	    			}
	    			$shareCfg=$this->mergeShareCfg($shareCfg, $shareData["hot_detail"],$varParams);
	    		}
	    		$shareCfg["page_id"]="evn_detail";
	    	}
	    	else if(stripos($requestUri, "wx/giftDetail")>0 || stripos($requestUri, "wx/giftCodeDraw")>0){	//礼包详情页
	    		if(isset($shareData["gift_detail"])){
	    			$varParams=array();
	    			$idx=Yii::app()->request->getParam('giftid',"");
	    			if(empty($idx)==false){
	    				$gift_detail = $fAppData->getQ_AppPromotionGiftByIdx($idx);
	    				if(isset($gift_detail) && count($gift_detail)>0){
	    					$varParams=array(
	    							"img"	=>	sprintf("%s%s",$appConfig["FApp_game_domain"],$gift_detail["FileUrl"]),
	    							"var"	=>	array("AppName"=>$gift_detail["AppName"],"GiftName" =>$gift_detail["Name"],),
	    					);
	    				}
	    			}
	    			$shareCfg=$this->mergeShareCfg($shareCfg, $shareData["gift_detail"],$varParams);
	    		}
	    		$shareCfg["page_id"]="gift_detail";
	    	}
	    	else if(stripos($requestUri, "wx/gameDetail")>0){		//游戏详情页
	    		if(isset($shareData["game_detail"])){
	    			$varParams=array();
	    			$idx=Yii::app()->request->getParam('AppId',"");
	    			if(empty($idx)==false){
	    				$appInfo=$fAppData->getAppInfoByAppId($idx);
	    				if(isset($appInfo) && count($appInfo)>0){
	    					$IconFile=$fAppData->getFileById($appInfo["FileId"]);
	    					$ImageName="";
	    					if(count($IconFile)>0){
	    						$ImageName=$IconFile["FileUrl"];
	    					}
	    					$MaxProprate="20";
	    					$currentEvn=$fAppData->getCurrentEvnByAppId($idx);
	    					if(count($currentEvn)>0){
	    						$MaxProprate=bcmul($currentEvn["Prorate"],"100",0);
	    					}
	    					$varParams=array(
	    							"img"	=>	sprintf("%s%s",$appConfig["FApp_game_domain"],$ImageName),
	    							"var"	=>	array("AppName"=>$appInfo["AppName"],"MaxProprate" =>$MaxProprate,),
	    					);
	    				}
	    			}	    			
	    			$shareCfg=$this->mergeShareCfg($shareCfg, $shareData["game_detail"],$varParams);
	    		}
	    		$shareCfg["page_id"]="game_detail";
	    	}
            else if(stripos($requestUri, "wx/lottery")>0){                                           //礼包
                if(isset($shareData["lottery"])){
                    $shareCfg=$this->mergeShareCfg($shareCfg, $shareData["lottery"]);
                }
                $shareCfg["page_id"]="lottery";
            }
            else if(stripos($requestUri, "wx/loginEvn")>0){
            	if(isset($shareData["loginEvn"])){
            		$shareCfg=$this->mergeShareCfg($shareCfg, $shareData["loginEvn"]);
            	}
            	$shareCfg["page_id"]="loginEvn";            	 
            }                                           
    	}
    	return $shareCfg;
    }
    
    private function mergeShareCfg($defaultCfg,$dataFromDB,$replaceParams=array())
    {
    	if(isset($dataFromDB["img"])){
    		$defaultCfg["img"]=$dataFromDB["img"];
    	}	
    	
    	if(isset($replaceParams["img"])){
    		$defaultCfg["img"]=$replaceParams["img"];
    	}
    	
    	foreach ($dataFromDB as $key => $value){
    		if($key!="img"){
    			$defaultCfg[$key]=$this->replaceVar($value,isset($replaceParams["var"])?$replaceParams["var"]:array());
    		}
    	}
    	return $defaultCfg;
    }
    
    private function replaceVar($src,$varParams=array())
    {
    	if(isset($varParams) && count($varParams)>0){
    		foreach ($varParams as $varName => $varValue){
    			$src=str_replace("{".$varName."}", $varValue, $src);
    		}
    	}
    	return $src;
    }

    private function getJSSDKData()
    {
    	$data=array(
    			"appId"			=>	self::WX_APP_ID,
    			"timestamp"		=>	time(),
    			"nonceStr"		=>	CGuidManager::GetGuid(),
    	);
    	$params=array(
    			"jsapi_ticket"	=>	$this->getJsApiTicket(),
    			"noncestr"		=>	$data["nonceStr"],
    			"timestamp"		=>	$data["timestamp"],
    			"url"			=>	Yii::app()->request->hostInfo.Yii::app()->request->getRequestUri(),
    	);
    	$buff="";
    	foreach ($params as $k => $v){
    		$buff .= $k . "=" . $v . "&";
    	}
    	$buff=substr($buff, 0, strlen($buff)-1);
    	$data["signature"]=sha1($buff);
    	return $data;
    }
    
    private function getJsApiTicket()
    {
    	$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
    	if(isset($appConfig["skipJSSDKTicket"]) && $appConfig["skipJSSDKTicket"]=="true"){
    		return "skip_wx_jssdk_ticket";
    	}
    	 
    	$cacheCfgNodeName="TencentWx";
    	$cacheKey="WX.ACCESS.JSSDK_TICKET";
    	$expire=7100;
    
    	$cachedValue =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
    	if(isset($cachedValue) &&  $cachedValue!=false && empty($cachedValue)==false){
    		return $cachedValue;
    	}else{
    		$http=new HttpInterface("Tencent","JSApiTicket");
    		$params=array(
    				"type"			=>	"jsapi",
    				"access_token"	=>	$this->getAccessToken(),
    		);
    		$data=$http->submit($params);
    		if(isset($data) && is_array($data) && isset($data["errcode"]) && $data["errcode"]=="0" && isset($data["ticket"]) && empty($data["ticket"])==false){
    			$cachedValue=$data["ticket"];
    			LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
    		}else{
    			//重新获取 access token
    			if(isset($data) && is_array($data) && isset($data["errcode"]) && ($data["errcode"]=="40001" || $data["errcode"]=="40002" || $data["errcode"]=="42001")){
    				$params[access_token] = $this->getAccessToken(false);
    				$data=$http->submit($params);
    				if(isset($data) && is_array($data) && isset($data["errcode"]) && $data["errcode"]=="0" && isset($data["ticket"]) && empty($data["ticket"])==false){
    					$cachedValue=$data["ticket"];
    					LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
    				}    				
    			}
    		}
    		return $cachedValue;
    	}
    }    
    
    private function getAccessToken($bUsingCache=true)
    {
    	$cacheCfgNodeName="TencentWx";
    	$cacheKey="WX.ACCESS.TOKEN";
    	$expire=7100;
    
    	$cachedValue =LunaMemcache::GetInstance($cacheCfgNodeName)->read($cacheKey);
    	if(isset($cachedValue) &&  $cachedValue!=false && empty($cachedValue)==false && $bUsingCache){
    		return $cachedValue;
    	}else{
    		$http=new HttpInterface("Tencent","AccessToken");
    		$params=array(
    				"grant_type"		=>	"client_credential",
    				"appid"				=>	self::WX_APP_ID,
    				"secret"			=>	self::WX_SECRET,
    		);
    		$data=$http->submit($params);
    		if(isset($data) && is_array($data) && isset($data["access_token"]) && empty($data["access_token"])==false){
    			$cachedValue=$data["access_token"];
    			LunaMemcache::GetInstance($cacheCfgNodeName)->write($cacheKey,$cachedValue,$expire);
    		}
    		return $cachedValue;
    	}
    }
}

