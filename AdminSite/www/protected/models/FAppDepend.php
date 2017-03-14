<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.http.HttpInterface");

class FAppDepend
{
	private $_ExtraSystemName="PackingSystem";
	private $_PackingClientUUID="";
	public $_LastErrorMessage="";
	
	public function __construct()
	{
		$appCfg=LunaConfigMagt::getInstance()->getAppConfig();
		if(isset($appCfg["FApp_game_packing_uuid"])){
			$this->_PackingClientUUID=$appCfg["FApp_game_packing_uuid"];
		}
	}	
	
	
	public function RegisterGame($gameId,$gameName,$gamePrefix)
	{
		$http=new HttpInterface($this->_ExtraSystemName, "RegisterGame");
		$params=array(
				"clientUUID" => $this->_PackingClientUUID,
				"gameid" 	=>$gameId,
				"name"		=> $gameName,
				"prefix"	=>$gamePrefix,
		);
		$data=$http->submit($params);
		if(isset($data) && is_array($data) && isset($data["rcode"])){
			if($data["rcode"]==0){
				return true;
			}else {
				switch ($data["rcode"]){
					case -1003:
						$this->_LastErrorMessage="参数错误,请确保打包游戏名和打包文件名使用英文字符,不含有其他符号。";
						break;
					case -80001:
						$this->_LastErrorMessage="clientUUID不正确";
						break;
					case -80003:
						$this->_LastErrorMessage="此游戏APPID已经被注册";
						break;
					default:
						$this->_LastErrorMessage="Undocument code:".$data["rcode"].$data["rdesc"];
						break; 
				}
				return false;				
			}			
		}else{
			$this->_LastErrorMessage="Unknown RegisterGame's response. ";
			return false;
		}
	}	
	
	public function RegisterGameVersion($appId,$appVersionId,$sourceUrl,$md5,$channelId)
	{
		$http=new HttpInterface($this->_ExtraSystemName, "CreateTask");
		$params=array(
				"clientUUID" 	=>	$this->_PackingClientUUID,
				"gameid" 		=>	$appId,
				"version"		=> 	$appVersionId,
				"isForce"		=>	0,
				"sourceUrl"		=>	$sourceUrl,
				"md5"			=>	$md5,
				"channels"		=>	array(array("code"=>$channelId,"publish"=>1)),
		);
		$data=$http->submit($params,true);
		if(isset($data) && is_array($data) && isset($data["rcode"])){
			if($data["rcode"]==0){
				return true;
			}else {
				switch ($data["rcode"]){
					case -1003:
						$this->_LastErrorMessage="参数错误";
						break;
					case -80001:
						$this->_LastErrorMessage="clientUUID不正确";
						break;
					case -80002:
						$this->_LastErrorMessage="此游戏版本已经被创建";
						break;
					case -20001 :
						$this->_LastErrorMessage="此游戏未注册,请先注册后才可以创建版本";
						break;
					default:
						$this->_LastErrorMessage="Undocument code:".$data["rcode"].$data["rdesc"];
						break;
				}
				return false;
			}
		}else{
			$this->_LastErrorMessage="Unknown RegisterGame's response. ";
			return false;
		}		
	}

	public function BuildPromoterPackage($appId,$appVersionId,$startId,$requestCount,$channelId,$callbackUrl,$isPublisCDN="0")
	{
		$promoterValue=$this->GetPromoterList($appId, $appVersionId, $startId, $requestCount);
		$http=new HttpInterface($this->_ExtraSystemName, "CreatePromoterPackage");
		$params=array(
				"clientUUID" 	=>	$this->_PackingClientUUID,
				"gameid" 		=>	$appId,
				"version"		=> 	$appVersionId,
				"publish"		=>	0,
				"channel"		=>	$channelId,
				"callbackUrl"	=>	$callbackUrl,
				"promoter"		=>  $promoterValue,
		);
		if($isPublisCDN=="1"){
			$params["publish"]=1;
			$params["promoter2cdn"]=$promoterValue;
		}
		
		$data=$http->submit($params,true);
		if(isset($data) && is_array($data) && isset($data["rcode"])){
			if($data["rcode"]==0){
				return true;
			}else {
				switch ($data["rcode"]){
					case -1003:
						$this->_LastErrorMessage="参数错误";
						break;
					default:
						$this->_LastErrorMessage="Undocument code:".$data["rcode"].$data["rdesc"];
						break;
				}
				return false;
			}
		}else{
			$this->_LastErrorMessage="Unknown RegisterGame's response. ";
			return false;
		}		
	}
	private function GetPromoterList($appId,$appVersionId,$startId,$requestCount)
	{
		$promoter="";
		for($index=0;$index<$requestCount;$index++){
			$promoter.=sprintf(",%s_%s_%s",$appId,$appVersionId,($startId+$index));
		}
		return substr($promoter, 1);
	}
	public function QueryPromoterTask($AppId,$AppVersionId,$channelId)
	{
		$http=new HttpInterface($this->_ExtraSystemName, "QueryPromoterTask");
		$params=array(
				"clientUUID" 	=>	$this->_PackingClientUUID,
				"gameid" 		=>	$AppId,
				"version"		=> 	$AppVersionId,
				"promoter"		=>	"",
				"channel"		=>	$channelId,
		);	
		$data=$http->submit($params,true);
		if(isset($data) && is_array($data) && isset($data["rcode"])){
			if($data["rcode"]==0){
				return $data["data"];
			}else {
				switch ($data["rcode"]){
					case -1003:
						$this->_LastErrorMessage="参数错误";
						break;
					default:
						$this->_LastErrorMessage="Undocument code:".$data["rcode"].$data["rdesc"];
						break;
				}
				return false;
			}
		}else{
			$this->_LastErrorMessage="Unknown RegisterGame's response. ";
			return false;
		}			
	}

	public function DeletePromoterTask($appId,$appVersionId,$channelId,$Promoters)
	{
		if(is_array($Promoters) && count($Promoters)>0){
			$http=new HttpInterface($this->_ExtraSystemName, "DeletePromoterTask");
			$params=array(
					"clientUUID" 	=> 	$this->_PackingClientUUID,
					"gameid" 		=>	$appId,
					"version"		=> 	$appVersionId,
					"channel"		=>	$channelId,
					"promoter"		=>	implode(",", $Promoters),
			);
			$data=$http->submit($params);
			if(isset($data) && is_array($data) && isset($data["rcode"])){
				if($data["rcode"]==0){
					return true;
				}else {
					switch ($data["rcode"]){
						case -1003:
							$this->_LastErrorMessage="参数错误。";
							break;
						case -80001:
							$this->_LastErrorMessage="clientUUID不正确";
							break;
						default:
							$this->_LastErrorMessage="Undocument code:".$data["rcode"].$data["rdesc"];
							break;
					}
					return false;
				}
			}else{
				$this->_LastErrorMessage="Unknown RegisterGame's response. ";
				return false;
			}
		}
	}

}