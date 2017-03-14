<?php
/* 
 * 红金宝对外合作对外服务:
 * 1. 用户登录和充值推送 
 */

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../../../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../../../../adminEvnCfgScriptNotify.xml');
require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');
LunaLoader::$_FORCE_INCLUDE = true;

LunaLoader::import("luna_lib.util.LunaPdo");
LunaLoader::import("luna_lib.log.LunaLogger");

LunaLoader::import("luna_lib.Kafka.KafkaQueue");

LunaLoader::import("luna_lib.Kafka.KafkaSocket");
LunaLoader::import("luna_lib.Kafka.Protocol.KafkaEncoder");
LunaLoader::import("luna_lib.Kafka.Protocol.KafkaDecoder");

LunaLoader::import("luna_lib.util.LunaKafka");

require_once(dirname(__FILE__) .'/../../models/FAppDepend.php');
require_once(dirname(__FILE__) . '/../../models/FAppData.php');

class NotifyServiceCommand extends ScriptBase
{
	public function init()
	{
		set_time_limit(0);
		$this->logServiceConsole("Publish Service Start",true);
		date_default_timezone_set("Asia/Shanghai");
		$this->_server_start=date("Y-m-d H:i:s");
		$this->loadWorkPoint();
		$this->loadBizConfiguration();
		$this->loadKafkaConfiguration();
		$this->logServiceConsole("Init finished.",true);
	}
	
	//消息队列位置
	private  $_Offset=0;
	private  $_HAVE_WORK_POINT=false;
	
	private function loadWorkPoint()
	{
		$fAppData=new FAppData();
		$data=$fAppData->QueryPublishCfg("workpoint");
		if(isset($data) && is_array($data) && count($data)>0){
			$this->_Offset=$data["CfgValue"];
			$this->_HAVE_WORK_POINT=true;
		}
		$this->logServiceConsole("Load work point finished.",true);
	}
	
	private function saveWorkPoint()
	{
		$fAppData=new FAppData();
		if($fAppData->UpdatePublishCfg("workpoint", $this->_Offset)==0){
			if($this->_HAVE_WORK_POINT==false){
				$fAppData->AddPublishCfg("workpoint",$this->_Offset);
				$this->_HAVE_WORK_POINT=true;
			}
		}
	}
	

	//加载 kafka 服务参数
	private $_config;
	private $_host;
	private $_port;
	private $_topicName;
	private $_partition_ids;
	private $_reqired_ack;
	private $_timeout;
	private $_max_bytes;				//buff size
	private function loadKafkaConfiguration()
	{
		$this->_config=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("KafkaQueue");
		if(isset($this->_config) && is_array($this->_config)){
			if(isset($this->_config["host"])){
				$this->_host=$this->_config["host"];
			}
			if(isset($this->_config["port"])){
				$this->_port=$this->_config["port"];
			}
			if(isset($this->_config["topic"])){
				$this->_topicName=$this->_config["topic"];
			}
			if(isset($this->_config["reqired_ack"])){
				$this->_reqired_ack=$this->_config["reqired_ack"];
			}
			if(isset($this->_config["timeout"])){
				$this->_timeout=$this->_config["timeout"];
			}
			if(isset($this->_config["max_bytes"])){
				$this->_max_bytes=$this->_config["max_bytes"];
			}
			if(isset($this->_config["partition_ids"])){
				$this->_partition_ids=$this->_config["partition_ids"];
			}
		}
		$this->logServiceConsole("Load Kafka configuration finished.",true);
	}
	
	//加载业务配置
	private $_sleep_out_of_new_msg=3;			//无新消息处理时,休眠时间
	private $_try_count=3;						//重试次数
	private $_report_period		=300;			//报告工作统计时间周期	
	private $_subscriber_cfg=array();			//订阅者配置参数
	
	private $_last_biz_load_time="";
	private function loadBizConfiguration()
	{
		//重新加载配置
		LunaConfigMagt::getInstance()->reload();
		
		$fAppData=new FAppData();
		$data=$fAppData->QueryPublishCfg("ServerCfg");
		if(isset($data) && is_array($data) && count($data)>0){
			$bizCfg=@json_decode($data["CfgValue"],true);
			if(isset($bizCfg) && is_array($bizCfg)){
				if(isset($bizCfg["sleep_seconds"])){
					$this->_sleep_out_of_new_msg=$bizCfg["sleep_seconds"];
				}
				if(isset($bizCfg["try_count"])){
					$this->_try_count=$bizCfg["try_count"];
				}
				if(isset($bizCfg["report_period"])){
					$this->_report_period=$bizCfg["report_period"];
				}
				if(isset($bizCfg["subscriber_cfg"])){
					$this->_subscriber_cfg=$bizCfg["subscriber_cfg"];
				}				
			}
			$bizLogMsg=print_r($bizCfg,true);
			$this->logServiceConsole($bizLogMsg);
		}		
		$this->_last_biz_load_time=date("Y-m-d H:i:s");
		$this->logServiceConsole("Load Biz Configuration finished.",true);
	}
	
	private function logServiceConsole($msg,$bSendConsole=false)
	{
		$msg=sprintf("%s\t%s",date("Y-m-d H:i:s"),$msg);
		LunaLogger::getInstance()->info($msg);
		if($bSendConsole){
			echo $msg."\n";
		}
	}
	
	//记录向订阅者发送的工作统计
	private $_subscriber_status=array();		//向订阅者累计发送的消息情况
	private function incBiz($subscriber)
	{
		if(isset($this->_subscriber_status[$subscriber])){
			$msgCount=$this->_subscriber_status[$subscriber]["msg_count"]+1;
			$this->_subscriber_status[$subscriber]=array(
					"msg_count" 		=> $msgCount,
					"last_msg_time"		=> date("Y-m-d H:i:s"),
			);						
		}else{
			$this->_subscriber_status[$subscriber]=array(
				"msg_count" 		=> 1,
				"last_msg_time"		=> date("Y-m-d H:i:s"),	
			);
		}		
	}
	
	//记录工作统计
	private $_last_rec_time=0;
	private function report()
	{
		$period=time()-$this->_last_rec_time;
		if($period>=$this->_report_period){
			$reportArr=array(
				"server_start"	=>$this->_server_start,
				"subscriber"	=>$this->_subscriber_status,
				"reprot_time"	=>date("Y-m-d H:i:s"),
				"biz_cfg_load_time" =>$this->_last_biz_load_time,		
			);
			$logMsg=json_encode($reportArr,true);
			$fAppData=new FAppData();
			if($fAppData->AddPublishServerStat($logMsg)<=0){
				LunaPdo::ResetInstance("FHDatabase");
			}
			$this->_last_rec_time=time();
			$this->logServiceConsole("Publish Server report its state and biz status.",true);
		}
	} 
	
	//处理消息
	//	type  		消息类型，分为 ctl 和  biz 两类消息
	//	subscriber	消息订阅者
	//  data		消息内容	  
	private function doBiz($msg)
	{
		$msgArr=@json_decode($msg,true);
		if(isset($msgArr) && is_array($msgArr)){
			try {
				if(isset($msgArr["type"]) && $msgArr["type"]=="ctl"){
					$this->logServiceConsole("One control message received,command verb is:".$msgArr["data"],true);
					if($msgArr["data"]=="loadCfg"){
						$this->loadBizConfiguration();
					}					
				}
				else if(isset($msgArr["type"]) &&  $msgArr["type"]=="biz"){
					//不认识的订阅者,重新加载订阅者业务配置
					if(isset($this->_subscriber_cfg[$msgArr["subscriber"]])==false){
						$this->loadBizConfiguration();
					}
					if(isset($this->_subscriber_cfg[$msgArr["subscriber"]])){
						$cfg=$this->_subscriber_cfg[$msgArr["subscriber"]];
						for($sendCount=0;$sendCount<$this->_try_count;$sendCount++){
							$returnCode=$this->publish($cfg["System"], $cfg["Interface"], $msgArr["data"]);
							if($returnCode==0){
								break;
							}
						}
						$this->incBiz($msgArr["subscriber"]);
						$this->logServiceConsole("One message is deliver:".$msgArr["subscriber"],true);
					}else{
						$this->logServiceConsole("One message is abandon:".$msgArr["subscriber"],true);
					}
					
				}else{
					$this->logServiceConsole("Unknown msg:".$msg,true);
				}
			}
			catch (Exception $e) {
			}			
		}else{
			$this->logServiceConsole("Unknown msg:".$msg,true);
		}	
	}
	
	//向订阅者推送消息
	private function publish($ExtralSystem,$InterfaceName,$params)
	{
		$http=new HttpInterface($ExtralSystem, $InterfaceName);
		$data=$http->submit($params,true);
		if(isset($data) && is_array($data) && isset($data["return_code"]) && $data["return_code"]==0){
			return 0;
		}
		return -1;
	}
	
	private $_server_start;						//服务器启动时间
	 
	public function run($args)
	{
		try {
			$kafkaReader	=new LunaKafka();			
			$this->logServiceConsole("Staring listen queue...",true);
			while (true)
			{
				try {
					$this->logServiceConsole("read queue at offset=".$this->_Offset,true);
					$shownMsgs=$kafkaReader->consumeMessage($this->_Offset);
					if(isset($shownMsgs) && is_array($shownMsgs) && count($shownMsgs)>0){
						foreach ($shownMsgs  as $msg){
							$this->doBiz($msg);
						}
						$this->_Offset+=count($shownMsgs);		
						$this->saveWorkPoint();
					}else{
						sleep($this->_sleep_out_of_new_msg);
					}
					$this->report();
				} catch (Exception $e) {
					$this->logServiceConsole("ERROR: " . get_class($e) . ': ' . $e->getMessage()."\n".$e->getTraceAsString(),true);
					sleep($this->_sleep_out_of_new_msg);
					$kafkaReader	=new LunaKafka();	
				}
			}
		}
		catch (Exception $e) {
			$logMsg="ERROR: NotifyService Starting... " . get_class($e) . ': ' . $e->getMessage()."\n".$e->getTraceAsString()."\n";
			LunaLogger::getInstance()->info($logMsg);
		}
	}	
	
	public function run_old($args)
	{
		try {
			$conn = new KafkaSocket($this->_host,$this->_port);
			$conn->connect();
			
			$encoder = new KafkaEncoder($conn);
			$decoder = new KafkaDecoder($conn);
			
			$partitions=explode(",", $this->_partition_ids);
				
			$this->logServiceConsole("Staring listen queue...",true);
			while (true)
			{
				try {
					$data = array(
						'required_ack' 	=> $this->_reqired_ack,
						'timeout' 		=> $this->_timeout,
						'data' 			=> array(
							array(
								'topic_name' => $this->_topicName,
								'partitions' => array(
									array(
										'partition_id' 	=> $partitions[0],
										'offset' 		=> $this->_Offset,
										'max_bytes' 	=> $this->_max_bytes,
									),
								),
							),
						),
					);
					$this->logServiceConsole(print_r($data,true),true);
					
					$encoder->fetchRequest($data);
					$topic = $decoder->fetchResponse();
					
					$this->logServiceConsole(print_r($topic,true),true);
					
					$shownMsgArr=array();
					foreach ($topic as $topic_name => $partition) {
						foreach ($partition as $partId => $messageSet) {
							foreach ($messageSet as $message) {
								$shownMsgArr[]=$message->getMessage();
							}
						}
					
						$newOffset=$partition->getMessageOffset();
						$highOffset=$partition->getHighOffset();
						
						$this->logServiceConsole(sprintf("Queue Info: this->_Offset=%s newOffset=%s highOffset=%s",$this->_Offset,$newOffset,$highOffset),true);
						
						if($this->_Offset<=$newOffset){
							if(($highOffset-$newOffset)==1 ){
								$newOffset++;
							}
							$totalCount=$newOffset-$this->_Offset;
							$shownCount=count($shownMsgArr);
							$shownMsgArr=array_splice($shownMsgArr, $shownCount-$totalCount);
							
							foreach ($shownMsgArr as $msg){
								$this->doBiz($msg);
							}
							if($this->_Offset==$newOffset){
								if(($highOffset-$newOffset)>2){		//队列里有消息，跳过当前这条读不到的消息
									$this->_Offset++;
								}								
							}else{
								$this->_Offset=$newOffset;
							}
							$this->saveWorkPoint();
						} else {
							sleep($this->_sleep_out_of_new_msg);
						}
					}
					$this->report();
				} catch (Exception $e) {
					$this->logServiceConsole("ERROR: " . get_class($e) . ': ' . $e->getMessage()."\n".$e->getTraceAsString(),true);
					sleep($this->_sleep_out_of_new_msg);
					
					$conn = new KafkaSocket($this->_host,$this->_port);
					$conn->connect();
					$encoder = new KafkaEncoder($conn);
					$decoder = new KafkaDecoder($conn);
				}
			}			
		}
		catch (Exception $e) {
			$logMsg="ERROR: NotifyService Starting... " . get_class($e) . ': ' . $e->getMessage()."\n".$e->getTraceAsString()."\n";
			LunaLogger::getInstance()->info($logMsg);
		}
	}
}
