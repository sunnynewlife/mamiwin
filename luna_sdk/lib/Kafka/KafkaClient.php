<?php

LunaLoader::import("luna_lib.Kafka.KafkaZooKeeper");
LunaLoader::import("luna_lib.Kafka.KafkaSocket");


class KafkaClient
{
    // {{{ consts
    // }}}
    // {{{ members

    /**
     * zookeeper
     *
     * @var mixed
     * @access private
     */
    private $zookeeper = null;

    /**
     * broker host list
     *
     * @var array
     * @access private
     */
    private $hostList = array();

    /**
     * save broker connection
     *
     * @var array
     * @access private
     */
    private static $stream = array();

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct(KafkaZooKeeper $zookeeper)
    {
        $this->zookeeper = $zookeeper;
    }

    // }}}
    // {{{ public function getBrokers()

    /**
     * get broker server
     *
     * @access public
     * @return void
     */
    public function getBrokers()
    {
        if (empty($this->hostList)) {
            $brokerList = $this->zookeeper->listBrokers();
            foreach ($brokerList as $brokerId => $info) {
                if (!isset($info['host']) || !isset($info['port'])) {
                    continue;
                }
                $this->hostList[$brokerId] = $info['host'] . ':' . $info['port'];
            }
        }

        return $this->hostList;
    }

    // }}}
    // {{{ public function getHostByPartition()

    /**
     * get broker host by topic partition
     *
     * @param string $topicName
     * @param int $partitionId
     * @access public
     * @return string
     */
    public function getHostByPartition($topicName, $partitionId = 0)
    {
        $partitionInfo = $this->zookeeper->getPartitionState($topicName, $partitionId);
        if (!$partitionInfo) {
            throw new KafkaException('topic:' . $topicName . ', partition id: ' . $partitionId . ' is not exists.');
        }

        $hostList = $this->getBrokers();
        if (isset($partitionInfo['leader']) && isset($hostList[$partitionInfo['leader']])) {
            return $hostList[$partitionInfo['leader']];
        } else {
            throw new KafkaException('can\'t find broker host.');
        }
    }

    // }}}
    // {{{ public function getZooKeeper()

    /**
     * get kafka zookeeper object
     *
     * @access public
     * @return \Kafka\ZooKeeper
     */
    public function getZooKeeper()
    {
        return $this->zookeeper;
    }

    // }}}
    // {{{ public function getStream()

    /**
     * get broker broker connect
     *
     * @param string $host
     * @access private
     * @return void
     */
    public function getStream($host, $lockKey = null)
    {
        if (!$lockKey) {
            $lockKey = uniqid($host);
        }

        list($hostname, $port) = explode(':', $host);
        // find unlock stream
        if (isset(self::$stream[$host])) {
            foreach (self::$stream[$host] as $key => $info) {
                if ($info['locked']) {
                    continue;
                } else {
                    self::$stream[$host][$key]['locked'] = true;
                    $info['stream']->connect();
                    return array('key' => $key, 'stream' => $info['stream']);
                }
            }
        }

        // no idle stream
        $stream = new KafkaSocket($hostname, $port);
        $stream->connect();
        self::$stream[$host][$lockKey] = array(
            'locked' => true,
            'stream' => $stream,
        );
        return array('key' => $lockKey, 'stream' => $stream);
    }

    // }}}
    // {{{ public function freeStream()

    /**
     * free stream pool
     *
     * @param string $key
     * @access public
     * @return void
     */
    public function freeStream($key)
    {
        foreach (self::$stream as $host => $values) {
            if (isset($values[$key])) {
                self::$stream[$host][$key]['locked'] = false;
            }
        }
    }

    // }}}
    // {{{ public function getTopicDetail()

    /**
     * get topic detail info
     *
     * @param  string $topicName
     * @return array
     */
    public function getTopicDetail($topicName)
    {
        return $this->zookeeper->getTopicDetail($topicName);
    }

    // }}}
    // }}}
}
