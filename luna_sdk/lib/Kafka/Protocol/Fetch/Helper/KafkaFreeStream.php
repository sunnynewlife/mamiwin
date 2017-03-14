<?php

LunaLoader::import("luna_lib.Kafka.Protocol.Fetch.Help.KafkaHelperAbstract");

class KafkaFreeStream extends KafkaHelperAbstract
{
    // {{{ members

    /**
     * streams
     *
     * @var array
     * @access protected
     */
    protected $streams = array();

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
    // {{{ public function setStreams()

    /**
     * set streams
     *
     * @access public
     * @return void
     */
    public function setStreams($streams)
    {
        $this->streams = $streams;
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
        if (isset($this->streams[$streamKey])) {
            $this->client->freeStream($streamKey);
        }
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
    }

    // }}}
    // }}}
}
