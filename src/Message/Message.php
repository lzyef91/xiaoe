<?php

namespace Nldou\Xiaoe\Message;

class Message
{
    const ORDER = 'order';
    const COUPONS = 'coupons';
    const MESSAGE_TYPE_MAP = [
        'order' => Order::class,
        'coupons' => Coupons::class
    ];

    /**
     * 消息体
     * @var array
     */
    protected $msgxml;

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * @param string $msg
     */
    public function __construct($msg)
    {
        logger($msg);
        $this->msgxml = new \DOMDocument();
		$this->msgxml->loadXML($msg);
    }

    public function getProperty($name)
    {
        if (in_array($name, $this->properties)) {
            return trim($this->msgxml->getElementsByTagName($name)->item(0)->nodeValue);
        } else {
            return NULL;
        }
    }

    public function __get($prop)
    {
        return $this->getProperty($prop);
    }

    public function unicodeDecode($unicode_str)
    {
        $json = '{"str":"'.$unicode_str.'"}';
        $arr = json_decode($json, true);
        return empty($arr) ? '' : $arr['str'];
    }
}