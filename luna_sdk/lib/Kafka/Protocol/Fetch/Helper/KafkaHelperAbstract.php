<?php


abstract class KafkaHelperAbstract
{
    // {{{ members
    // }}}
    // {{{ functions
    // {{{ abstract public function onStreamEof()

    /**
     * on stream eof
     *
     * @param string $streamKey
     * @access public
     * @return void
     */
    abstract public function onStreamEof($streamKey);

    // }}}
    // {{{ abstract public function onTopicEof()

    /**
     * on topic eof
     *
     * @param string $topicName
     * @access public
     * @return void
     */
    abstract public function onTopicEof($topicName);

    // }}}
    // {{{ abstract public function onPartitionEof()

    /**
     * on partition eof
     *
     * @param \Kafka\Protocol\Fetch\Partition $partition
     * @access public
     * @return void
     */
    abstract public function onPartitionEof($partition);

    // }}}
    // }}}
}
