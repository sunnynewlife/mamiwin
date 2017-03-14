<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.log.LunaLogger");

LunaLoader::import("luna_lib.Kafka.KafkaSocket");
LunaLoader::import("luna_lib.Kafka.Protocol.KafkaEncoder");
LunaLoader::import("luna_lib.Kafka.Protocol.KafkaDecoder");

class KafkaQueue
{
	public $_config;
	
	private $_host;
	private $_port;
	
	private $_topicName;
	private $_partition_ids;
	private $_reqired_ack;
	private $_timeout;					
	private $_max_bytes;				//buff size
	
	private $_queue_name;				//消息队列配置名称
	
	public function __construct($queueName=null)
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
			if(empty($queueName)==false && isset($this->_configs["special"])){
				$this->_queue_name=$queueName;
				foreach ($this->_configs["special"] as $nodeName => $nodeCfg){
					if($nodeName==$queueName){
						if(isset($nodeCfg["host"])){
							$this->_host=$nodeCfg["host"];
						}
						if(isset($nodeCfg["port"])){
							$this->_port=$nodeCfg["port"];
						}
						if(isset($nodeCfg["topic"])){
							$this->_topicName=$nodeCfg["topic"];
						}
						if(isset($nodeCfg["reqired_ack"])){
							$this->_reqired_ack=$nodeCfg["reqired_ack"];
						}
						if(isset($nodeCfg["timeout"])){
							$this->_timeout=$nodeCfg["timeout"];
						}
						if(isset($nodeCfg["max_bytes"])){
							$this->_max_bytes=$nodeCfg["max_bytes"];
						}
						if(isset($nodeCfg["partition_ids"])){
							$this->_partition_ids=$nodeCfg["partition_ids"];
						}
						break;
					}
				}
			}
		}
	}
	
	//查询指定topic 队列息的消息
	//返回 array: 队列中所有消息列表,队列中push下一条消息的位置  
	public function getMessage($startOffset=0,$fetchOneTimes=false,$topicName=null,$partition_id=null)
	{
		$returnArray=array(
			"high_offset" 	=> 0,
			"messages"		=>	array(),
		);
		$msgList=array();
		$OffSet=$startOffset;
		$toPartitionIds=$this->_partition_ids;
		if(isset($partition_id) && empty($partition_id)==false){
			$toPartitionIds=$partition_id;
		}
		$partitions=explode(",", $toPartitionIds);
		
		try {
			
			$conn = new KafkaSocket($this->_host, $this->_port);
			$conn->connect();
			$encoder = new KafkaEncoder($conn);
			$decoder = new KafkaDecoder($conn);
			$continueFetchFromKafka=true;
			
			while($continueFetchFromKafka){
				$data = array(
						'required_ack' 	=>	$this->_reqired_ack,
						'timeout' 		=>  $this->_timeout,
						'data' => array(
								array(
										'topic_name' => (isset($topicName) && empty($topicName)==false)?$topicName:$this->_topicName,
										'partitions' => array(
											array(
												'partition_id'  => $partitions[0],
												'offset' 		=> $OffSet,
												'max_bytes' 	=> $this->_max_bytes,
											),
										),
								),
						),
				);
				
				$requestLog=json_encode($data,true);
				$_time_start = microtime(true);
				$encoder->fetchRequest($data);
				$topicMessages = $decoder->fetchResponse();
				$kafkaQueueMessages=array();
				foreach ($topicMessages as $topic_name => $partition) {
					foreach ($partition as $partId => $messageSet) {
						foreach ($messageSet as $message) {
							$kafkaQueueMessages[]=$message->getMessage();
						}
					}
					
					$newOffset=$partition->getMessageOffset();
					$highOffset=$partition->getHighOffset();
					$returnArray["high_offset"]=$highOffset;
					
					if($OffSet<=$newOffset){
						if(($highOffset-$newOffset)==1 ){
							$newOffset++;
						}
						$totalCount=$newOffset-$OffSet;
						$kafkaQueueMessages=array_splice($kafkaQueueMessages, count($kafkaQueueMessages)-$totalCount);
						$OffSet=$newOffset;
						
						foreach ($kafkaQueueMessages as $str){
							$msgList[]=$str;
						}
						if($fetchOneTimes || count($kafkaQueueMessages)<1 ){
							$continueFetchFromKafka=false;
							break;
						}
					}else{
						$continueFetchFromKafka=false;
						break;
					}
				}
			}
		}
		catch (Exception $e) {
			$responseLog="ERROR: " . get_class($e) . ': ' . $e->getMessage()."\n".$e->getTraceAsString()."\n";
			$logMsg=sprintf("<KafkaQueue>\t<%s>\t<%s>\t%s\t%s",empty($this->_queue_name)?"global":$this->_queue_name,microtime(true) - $_time_start,$requestLog,$responseLog);
			LunaLogger::getInstance()->info($logMsg);
		}
		$returnArray["messages"]=$msgList;
		return $returnArray;
	}
	
	//返回最后N条消息
	public function getTopNMessage($TopN=1,$topicName=null,$partition_id=null)
	{
		$queueMsg=$this->getMessage(0,true,$topicName,$partition_id);
		if(isset($queueMsg) && is_array($queueMsg) && isset($queueMsg["high_offset"]) && $queueMsg["high_offset"]>$TopN){
			$queueMsg=$this->getMessage($queueMsg["high_offset"]-$TopN,false,$topicName,$partition_id);
			
		}
		if(isset($queueMsg["messages"])){
			return $queueMsg["messages"];
		}
		return array();
	}
	
	public function sendMessageEx($msg)
	{
		$result=$this->sendMessage($msg);
		if(isset($result) && isset($result[$this->_topicName])){
			$paritionReturn=$result[$this->_topicName];
			if($paritionReturn[0]["errCode"]==0){
				return $paritionReturn[0]["offset"];
			}
		}
		return -1;
	}
	
	public function sendMessage($msg,$topicName=null,$partition_ids=null,$partitionAll=false)
	{
		$data = array(
			'required_ack' 	=> $this->_reqired_ack,
			'timeout' 		=> $this->_timeout,
			'data' => array(
				array(
					'topic_name' => (isset($topicName) && empty($topicName)==false)?$topicName:$this->_topicName,
					'partitions' => array(),
				),
			),
		);
		$message= is_array($msg)?json_encode($msg,true):$msg;
		$toPartitionIds=$this->_partition_ids;
		if(isset($partition_ids) && empty($partition_ids)==false){
			$toPartitionIds=$partition_ids;
		}
		$partitions=explode(",", $toPartitionIds);
		$partitionsArray=array();
		foreach ($partitions as $partitionId){
			$partitionsArray[]=array(
					"partition_id"	=>$partitionId,
					"messages"		=>array($message),
			);
			if($partitionAll==false){
				break;
			}
		}
		$data["data"][0]["partitions"]=$partitionsArray;
		$requestLog=json_encode($data,true);
		$responseLog="";
		$_time_start = microtime(true);
		$result=array();
		try {
			$conn = new KafkaSocket($this->_host, $this->_port);
			$conn->connect();
			$encoder = new KafkaEncoder($conn);
			$encoder->produceRequest($data);
			$decoder = new KafkaDecoder($conn);
			$result = $decoder->produceResponse();
			$responseLog=json_encode($result,true);
		}
		catch (Exception $e) {
			$responseLog="ERROR: " . get_class($e) . ': ' . $e->getMessage()."\n".$e->getTraceAsString()."\n";
		}
		$logMsg=sprintf("<KafkaQueue>\t<%s>\t<%s>\t%s\t%s",empty($this->_queue_name)?"global":$this->_queue_name,microtime(true) - $_time_start,$requestLog,$responseLog);
		LunaLogger::getInstance()->info($logMsg);
		return $result;
	}
}
