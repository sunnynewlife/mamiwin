<?php

LunaLoader::import("luna_lib.Kafka.Protocol.Fetch.Help.KafkaHelperAbstract");
LunaLoader::import("luna_lib.Kafka.KafkaOffset");

class CommitOffset extends KafkaHelperAbstract
{
    // {{{ members

    /**
     * consumer group
     *
     * @var string
     * @access protected
     */
    protected $group = '';

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    // }}}
    // {{{ public function setGroup()

    /**
     * set consumer group
     *
     * @access public
     * @return void
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    // }}}
    // {{{ public function onStreamEof()

    /**
     * on stream eof call
     *
     * @param string $streamKey
     * @access public
     * @return void
     */
    public function onStreamEof($streamKey)
    {
    }

    // }}}
    // {{{ public function onTopicEof()

    /**
     * on topic eof call
     *
     * @param string $topicName
     * @access public
     * @return void
     */
    public function onTopicEof($topicName)
    {
    }

    // }}}
    // {{{ public function onPartitionEof()

    /**
     * on partition eof call
     *
     * @param \Kafka\Protocol\Fetch\Partition $partition
     * @access public
     * @return void
     */
    public function onPartitionEof($partition)
    {
        $partitionId = $partition->key();
        $topicName = $partition->getTopicName();
        $offset    = $partition->getMessageOffset();
        $offsetObject = new  KafkaOffset($this->client, $this->group, $topicName, $partitionId);
        $offsetObject->setOffset($offset);
    }

    // }}}
    // }}}
}
