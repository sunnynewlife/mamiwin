<?php
LunaLoader::import("luna_lib.core.LunaConfigMagt");
LunaLoader::import("luna_lib.cfg.LunaCfgPwd");
LunaLoader::import("luna_lib.util.LunaWebUtil");
LunaLoader::import("luna_lib.log.LunaLogger");
LunaLoader::import("luna_lib.cfg.view.LoginView");
LunaLoader::import("luna_lib.cfg.view.EditView");
LunaLoader::import("luna_lib.util.Curl");



class LunaCfgBaseController  extends CController 
{
	protected  $_operatorId;
	protected  $_configs;	
	protected  $_clientIp;
	
	protected  $_MAPPED_LOGIN_URL="/lunaCfg/index";
	protected  $_MAPPED_SAVE_URL="/lunaCfg/save";
	
	const LUNA_CFG_OPERATOR_LOGIN="LunaCfg.Operator";
	
	public function init()
	{
		header("Content-Type:text/html;charset=utf-8");
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("LunaCfg");
		$this->_clientIp=LunaWebUtil::getClientIp();
		$this->_operatorId=Yii::app()->session[self::LUNA_CFG_OPERATOR_LOGIN];
		
		if(isset($this->_configs["MapLoginUrl"]) && empty($this->_configs["MapLoginUrl"])==false){
			$this->_MAPPED_LOGIN_URL=$this->_configs["MapLoginUrl"];
		}
		if(isset($this->_configs["MapSaveUrl"]) && empty($this->_configs["MapSaveUrl"])==false){
			$this->_MAPPED_SAVE_URL=$this->_configs["MapSaveUrl"];
		}		
	}
	
	private function checkIPAcl($clientIp,$aclName)
	{
		if(isset($this->_configs[$aclName]) && empty($this->_configs[$aclName])==false){
			$editAcls=explode(",",$this->_configs[$aclName]);
			foreach ($editAcls as $allowedIp){
				if($allowedIp==$clientIp){
					return true;
				}
			}
		}
		return false;
	}
	
	private function checkEditAcl($clientIp)
	{
		return $this->checkIPAcl($clientIp, "editAcl");
	}
	private function checkSyncAcl($clientIp)
	{
		return $this->checkIPAcl($clientIp, "syncAcl");
	}
	private function checkOperatorAcl($operatorId,$pwd)
	{
		if(empty($operatorId) || empty($pwd)) return false;
		
		if(isset($this->_configs["operatorAcl"]) && is_array($this->_configs["operatorAcl"]) && count($this->_configs["operatorAcl"])>0){
			foreach ($this->_configs["operatorAcl"] as $usrId => $usrPwd){
				if($operatorId==$usrId){
					$secretPwd=LunaCfgPwd::getSecretKey($pwd);
					return $secretPwd==$usrPwd;
				}	
			}			
		}
		return false;
	}
	private function checkEditModel()
	{
		return $this->checkEditAcl($this->_clientIp) && isset($this->_operatorId) && !empty($this->_operatorId); 
	}
	private function LoadXMLCfgContent()
	{
		if(file_exists(LunaConfigMagt::getInstance()->getXmlFileName())){
			return 	file_get_contents(LunaConfigMagt::getInstance()->getXmlFileName());
		}
	}
	private function verifyXMLCfgContent($xmlContent)
	{
		$xmlDoc=new DOMDocument();
		$ret=@$xmlDoc->loadXML($xmlContent);
		if($ret){
			return true;
		}else{
			return false;
		}
	}
	private function saveHistoryVersion()
	{
		$pos=strrpos(LunaConfigMagt::getInstance()->getXmlFileName(),"/");
		if($pos){
			$path=substr(LunaConfigMagt::getInstance()->getXmlFileName(),0,$pos+1);
			$fileName=substr(LunaConfigMagt::getInstance()->getXmlFileName(), $pos+1);
			for($index=8;$index>0;$index--){
				$srcFileName=sprintf("%s%s.%s",$path,$fileName,$index);
				$destFileName=sprintf("%s%s.%s",$path,$fileName,($index+1));
				if(file_exists($srcFileName)){
					if(copy($srcFileName,$destFileName)==false){
						return false;
					}
				}
			}
			$destFileName=sprintf("%s%s.1",$path,$fileName);
			return copy(LunaConfigMagt::getInstance()->getXmlFileName(), $destFileName);
		}
		return false;
	}
	private function syncCfg()
	{
		$syncResult=true;
		if(isset($this->_configs["syncHost"]) && empty($this->_configs["syncHost"])==false){
			$syncHosts=explode(",",$this->_configs["syncHost"]);
			$serverIp=LunaWebUtil::getServerIp();
			$serverName=LunaWebUtil::getServerName();
			foreach ($syncHosts as $ip){
				if($serverIp!=$ip){
					$syncResult =  $syncResult && $this->sendCfgConents($ip,$serverName);
				}
			}			
		}
		return true;
	}
	private function sendCfgConents($ip,$serverName)
	{
		//$xmlContent=file_get_contents(LUNA_CONF_PATH);
		return true;		
	}
	
	
	public function actionIndex()
	{
		$responseHtml="";
		if($this->checkEditAcl($this->_clientIp)){
			if($this->checkEditModel()){
				$responseHtml=EditView::getHtml($this->_operatorId,"",$this->LoadXMLCfgContent(),$this->_MAPPED_SAVE_URL);
			}else{
				$usrId = Yii::app()->request->getParam('txtUserName');
				$usrPwd = Yii::app()->request->getParam('txtPassword');
				if(empty($usrId) || empty($usrPwd)){
 					$responseHtml=LoginView::getHtml("",$this->_MAPPED_LOGIN_URL);					
				}else {
					if($this->checkOperatorAcl($usrId, $usrPwd)){
						Yii::app()->session[self::LUNA_CFG_OPERATOR_LOGIN]=$usrId;
						$this->_operatorId=$usrId;
						LunaLogger::getInstance()->info(sprintf("<LunaCfg> <%s> logined at <%s>",$this->_operatorId,$this->_clientIp));
						$responseHtml=EditView::getHtml($this->_operatorId,"",$this->LoadXMLCfgContent(),$this->_MAPPED_SAVE_URL);
					}else{
						$responseHtml=LoginView::getHtml("账号或者密码输入错误!",$this->_MAPPED_LOGIN_URL);
					}
				}				
			}			
		}else{
			$msg=sprintf("您的IP<%s>不允许访问Luna配置管理",$this->_clientIp);
			$responseHtml=LoginView::getHtml($msg,$this->_MAPPED_LOGIN_URL);			
		}
		echo $responseHtml;
	}
	
	public function actionSave()
	{
		$responseHtml="";
		if($this->checkEditModel()){
			$newXMLContent=Yii::app()->request->getParam("txtCfgXML");
			if(empty($newXMLContent)){
				$responseHtml=EditView::getHtml($this->_operatorId,"配置内容错误，不能为空",$this->LoadXMLCfgContent(),$this->_MAPPED_SAVE_URL);
			}else{
				$oldXMLContent=$this->LoadXMLCfgContent();
				if($oldXMLContent==$newXMLContent){
					$responseHtml=EditView::getHtml($this->_operatorId,"配置内容没有变化",$oldXMLContent,$this->_MAPPED_SAVE_URL);
				}else{
					if($this->verifyXMLCfgContent($newXMLContent)){
						if($this->saveHistoryVersion()){
							if(file_put_contents(LunaConfigMagt::getInstance()->getXmlFileName(),$newXMLContent)==false){
								$responseHtml=EditView::getHtml($this->_operatorId,"配置保存错误",$oldXMLContent,$this->_MAPPED_SAVE_URL);
							}else{
								//sync
								if($this->syncCfg()){
									$responseHtml=EditView::getHtml($this->_operatorId,"配置保存成功",$newXMLContent,$this->_MAPPED_SAVE_URL);
								}else{
									$responseHtml=EditView::getHtml($this->_operatorId,"配置保存成功,同步未成功",$newXMLContent,$this->_MAPPED_SAVE_URL);
								}
							}							
						}else{
							$responseHtml=EditView::getHtml($this->_operatorId,"配置历史保存错误",$oldXMLContent,$this->_MAPPED_SAVE_URL);
						}												
					}else{
						$responseHtml=EditView::getHtml($this->_operatorId,"配置内容格式错误",$oldXMLContent,$this->_MAPPED_SAVE_URL);
					}
				}				
			}
		}else{
			$responseHtml=LoginView::getHtml("",$this->_MAPPED_LOGIN_URL);
		}
		echo $responseHtml;
	}
	
	public function actionMakePwd()
	{
		$pwd = Yii::app()->request->getParam('pwd');
		if(empty($pwd)==false && $this->checkEditAcl($this->_clientIp)){
			echo LunaCfgPwd::getSecretKey($pwd);
		}else{
			echo "i'm so sorray.";	
		}		
	}
}

?>