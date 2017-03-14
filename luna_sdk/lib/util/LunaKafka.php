<?php

LunaLoader::import("luna_core.LunaConfigMagt");
LunaLoader::import("luna_lib.log.LunaLogger");

// Apache Kafka 0.8x client
// Be sure to install kafka extension before using the class.
// You can install this extension from http://cwiki.apache.org/confluence/display/KAFKA/Clients

class LunaKafka
{
	private $_topic_name;
	private $_host_info;
	private $_kafka_conn=null;
	private $_read_len=20;

	public function __construct($cfgNodeName='')
	{
		$this->_configs=LunaConfigMagt::getInstance()->getLunaSDKConfigSection("LunaKafka");
		$globalCfg=$this->_configs["global"];
		$this->_host_info	=$globalCfg["hostInfo"];
		$this->_topic_name	=$globalCfg["topic"];
		$this->_read_len	=$globalCfg["readLength"];
		
		if(empty($cfgNodeName)==false && isset($this->_configs["special"])){
			foreach ($this->_configs["special"] as $nodeName => $nodeCfg){
				if($nodeName==$cfgNodeName){
					if(isset($nodeCfg["hostInfo"])){
						$this->_host_info=$nodeCfg["hostInfo"];
					}
					if(isset($nodeCfg["topic"])){
						$this->_topic_name=$nodeCfg["topic"];
					}
					if(isset($nodeCfg["readLength"])){
						$this->_read_len=$nodeCfg["readLength"];
					}						
					break;
				}		
			}
		}
		$this->_kafka_conn=new Kafka($this->_host_info,
				array(
					Kafka::LOGLEVEL         => Kafka::LOG_OFF,
					Kafka::CONFIRM_DELIVERY => Kafka::CONFIRM_OFF,
					Kafka::RETRY_COUNT      => 1,
					Kafka::RETRY_INTERVAL   => 25,						
				));
	}
	
	public function close()
	{
		if($this->_kafka_conn!=null){
			$this->_kafka_conn->disconnect();
		}
	}
		
	public function produceMessage($msg)
	{
		if(empty($msg)==false){
			return $this->_kafka_conn->produce($this->_topic_name,$msg);
		}
	}
	
	public function consumeMessage($offset)
	{
		$partitions = $this->_kafka_conn->getPartitionsForTopic($this->_topic_name);
		if(isset($partitions) && is_array($partitions) && count($partitions)>0){
			$this->_kafka_conn->setPartition(intval($partitions[0]));
		}
		return $this->_kafka_conn->consume($this->_topic_name, intval($offset),intval($this->_read_len));		
	} 
}
