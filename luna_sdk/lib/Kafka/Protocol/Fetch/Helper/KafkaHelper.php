<?php

LunaLoader::import("luna_lib.Kafka.Protocol.Fetch.Helper.KafkaHelperAbstract");
LunaLoader::import("luna_lib.Kafka.KafkaException");

class KafkaHelper
{
    // {{{ members

    /**
     * helper object
     */
    private static $helpers = array();

    // }}}
    // {{{ functions
    // {{{ public staitc function registerHelper()

    /**
     * register helper
     *
     * @param string $key
     * @param \Kafka\Protocol\Fetch\Helper\HelperAbstract $helper
     * @static
     * @access public
     * @return void
     */
    public static function registerHelper($key, $helper = null)
    {
        if (is_null($helper)) {
            $className = 'Kafka' . $key;
            if (!class_exists($className)) {
                throw new KafkaException('helper is not exists.');
            }
            $helper = new $className();
        }

        if ($helper instanceof KafkaHelperAbstract) {
            self::$helpers[$key] = $helper;
        } else {
            throw new KafkaException('this helper not instance of `\Kafka\Protocol\Fetch\Helper\HelperAbstract`');
        }
    }

    // }}}
    // {{{ public staitc function unRegisterHelper()

    /**
     * unregister helper
     *
     * @param string $key
     * @static
     * @access public
     * @return void
     */
    public static function unRegisterHelper($key)
    {
        if (isset(self::$helpers[$key])) {
            unset(self::$helpers[$key]);
        }
    }

    // }}}
    // {{{ public static function onStreamEof()

    /**
     * on stream eof call
     *
     * @param string $streamKey
     * @static
     * @access public
     * @return void
     */
    public static function onStreamEof($streamKey)
    {
        if (empty(self::$helpers)) {
            return false;
        }

        foreach (self::$helpers as $key => $helper) {
            if (method_exists($helper, 'onStreamEof')) {
                $helper->onStreamEof($streamKey);
            }
        }
    }

    // }}}
    // {{{ public static function onTopicEof()

    /**
     * on topic eof call
     *
     * @param string $topicName
     * @static
     * @access public
     * @return void
     */
    public static function onTopicEof($topicName)
    {
        if (empty(self::$helpers)) {
            return false;
        }

        foreach (self::$helpers as $key => $helper) {
            if (method_exists($helper, 'onTopicEof')) {
                $helper->onStreamEof($topicName);
            }
        }
    }

    // }}}
    // {{{ public static function onPartitionEof()

    /**
     * on partition eof call
     *
     * @param \Kafka\Protocol\Fetch\Partition $partition
     * @static
     * @access public
     * @return void
     */
    public static function onPartitionEof($partition)
    {
        if (empty(self::$helpers)) {
            return false;
        }

        foreach (self::$helpers as $key => $helper) {
            if (method_exists($helper, 'onPartitionEof')) {
                $helper->onPartitionEof($partition);
            }
        }
    }

    // }}}
    // }}}
}
