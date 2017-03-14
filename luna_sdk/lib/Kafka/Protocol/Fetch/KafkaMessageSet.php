<?php

LunaLoader::import("luna_lib.Kafka.KafkaException");
LunaLoader::import("luna_lib.Kafka.KafkaLog");
LunaLoader::import("luna_lib.Kafka.Exception.KafkaExceptionOutOfRange");

LunaLoader::import("luna_lib.Kafka.Protocol.KafkaDecoder");
LunaLoader::import("luna_lib.Kafka.Protocol.Fetch.KafkaPartition");
LunaLoader::import("luna_lib.Kafka.Protocol.Fetch.Helper.KafkaHelper");

LunaLoader::import("luna_lib.Kafka.Protocol.Fetch.KafkaMessage");

class KafkaMessageSet implements \Iterator
{
    // {{{ members

    /**
     * kafka socket object
     *
     * @var mixed
     * @access private
     */
    private $stream = null;

    /**
     * messageSet size
     *
     * @var float
     * @access private
     */
    private $messageSetSize = 0;

    /**
     * validByteCount
     *
     * @var float
     * @access private
     */
    private $validByteCount = 0;

    /**
     * messageSet offset
     *
     * @var float
     * @access private
     */
    private $offset = 0;

    /**
     * valid
     *
     * @var mixed
     * @access private
     */
    private $valid = false;

    /**
     * partition object
     *
     * @var \Kafka\Protocol\Fetch\Partition
     * @access private
     */
    private $partition = null;

    /**
     * request fetch context
     *
     * @var array
     */
    private $context = array();

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @param \Kafka\Socket $stream
     * @param int $initOffset
     * @access public
     * @return void
     */
    public function __construct(KafkaPartition $partition, $context = array())
    {
        $this->stream = $partition->getStream();
        $this->partition = $partition;
        $this->context   = $context;
        $this->messageSetSize = $this->getMessageSetSize();
        KafkaLog::log("messageSetSize: {$this->messageSetSize}", LOG_INFO);
    }

    // }}}
    // {{{ public function current()

    /**
     * current
     *
     * @access public
     * @return void
     */
    public function current()
    {
        return $this->current;
    }

    // }}}
    // {{{ public function key()

    /**
     * key
     *
     * @access public
     * @return void
     */
    public function key()
    {
        return $this->validByteCount;
    }

    // }}}
    // {{{ public function rewind()

    /**
     * implements Iterator function
     *
     * @access public
     * @return integer
     */
    public function rewind()
    {
        $this->valid = $this->loadNextMessage();
    }

    // }}}
    // {{{ public function valid()

    /**
     * implements Iterator function
     *
     * @access public
     * @return integer
     */
    public function valid()
    {
        if (!$this->valid) {
            $this->partition->setMessageOffset($this->offset);

            // one partition iterator end
            KafkaHelper::onPartitionEof($this->partition);
        }

        return $this->valid;
    }

    // }}}
    // {{{ public function next()

    /**
     * implements Iterator function
     *
     * @access public
     * @return integer
     */
    public function next()
    {
        $this->valid = $this->loadNextMessage();
    }

    // }}}
    // {{{ protected function getMessageSetSize()

    /**
     * get message set size
     *
     * @access protected
     * @return integer
     */
    protected function getMessageSetSize()
    {
        // read message size
        $data = $this->stream->read(4, true);
        $data = KafkaDecoder::unpack(KafkaDecoder::BIT_B32, $data);
        $size = array_shift($data);
        if ($size <= 0) {
            throw new KafkaExceptionOutOfRange($size . ' is not a valid message size');
        }

        return $size;
    }

    // }}}
    // {{{ public function loadNextMessage()

    /**
     * load next message
     *
     * @access public
     * @return void
     */
    public function loadNextMessage()
    {
        if ($this->validByteCount >= $this->messageSetSize) {
            return false;
        }

        try {
            if ($this->validByteCount + 12 > $this->messageSetSize) {
                // read socket buffer dirty data
                $this->stream->read($this->messageSetSize - $this->validByteCount);
                return false;
            }
            $offset = $this->stream->read(8, true);
            $this->offset  = KafkaDecoder::unpack(KafkaDecoder::BIT_B64, $offset);
            $messageSize = $this->stream->read(4, true);
            $messageSize = KafkaDecoder::unpack(KafkaDecoder::BIT_B32, $messageSize);
            $messageSize = array_shift($messageSize);
            $this->validByteCount += 12;
            if (($this->validByteCount + $messageSize) > $this->messageSetSize) {
                // read socket buffer dirty data
                $this->stream->read($this->messageSetSize - $this->validByteCount);
                return false;
            }
            $msg  = $this->stream->read($messageSize, true);
            $this->current = new KafkaMessage($msg);
        } catch (KafkaException $e) {
            KafkaLog::log("already fetch: {$this->validByteCount}, {$e->getMessage()}", LOG_INFO);
            return false;
        }

        $this->validByteCount += $messageSize;

        return true;
    }

    // }}}
    // {{{ public function messageOffset()

    /**
     * current message offset in producer
     *
     * @return void
     */
    public function messageOffset()
    {
        return $this->offset;
    }

    // }}}
    // }}}
}
