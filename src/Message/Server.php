<?php

namespace Nldou\Xiaoe\Message;

use Nldou\Xiaoe\Contracts\MessageHandlerInterface;
use Nldou\Xiaoe\Message\Message;
use Nldou\Xiaoe\Exceptions\InvalidParamsException;
use Nldou\Xiaoe\Exceptions\XMLDecryptException;

use Nldou\Xiaoe\Message\XiaoeSDK\WXBizMsgCrypt;

class Server
{

    private $appId;
    private $token;
    private $aesKey;

    protected $encryptMsg;

    /**
     * @var Message
     */
    protected $message;

    /**
     * 消息处理器
     * @var array
     */
    private $handlers = [];

    public function __construct($appId, $token, $aesKey)
    {
        $this->appId = $appId;
        $this->token = $token;
        $this->aesKey = $aesKey;
    }

    protected function checkValidMsgType($type)
    {
        if (!array_key_exists($type, Message::MESSAGE_TYPE_MAP)) {
            throw new InvalidParamsException('invalid_message_type');
        }
    }

    /**
     * 将处理器加入队列
     *
     * @param MessageHandlerInterface $handlers
     * @param mixed $msgType
     */
    public function push(MessageHandlerInterface $handler, $msgType)
    {
        if (is_array($msgType)) {
            foreach($msgType as $t){
                $this->checkValidMsgType($t);
                $this->handlers[$t][] = $handler;
            }
        } else {
            $this->checkValidMsgType($msgType);
            $this->handlers[$msgType][] = $handler;
        }

    }

    public function serve()
    {
        // msg请求信息
        $this->encryptMsg = file_get_contents('php://input');

        // 解密
        $d= $this->decrypt();
        // 封闭标签
        $msg = '<xml>'.$d['msg'].'</xml>';
        // 错误码
        $code = $d['code'];

        if ($code != 0) {
            throw new XMLDecryptException($code);
        }

        //获取message实例
        $this->message = $this->getMessageInstance($msg);

        // 消息合法性
        if (!is_null($this->message) && $this->message instanceof Message) {
            // 消息类型对应的处理器
            if (array_key_exists($this->message->type, $this->handlers)) {
                // 处理器集合
                $handlers = $this->handlers[$this->message->type];
                // 按顺序执行
                foreach ($handlers as $handler) {
                    // 如果处理器返回false，则接下来的处理器不执行
                    $res = $handler->handle($this->message);
                    if ($res === false) {
                        break;
                    }
                }
            }
        }

        return 'success';
    }

    protected function decrypt()
    {
        $xml_tree = new \DOMDocument();
        $xml_tree->loadXML($this->encryptMsg);
        //获取XML中的数据
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $array_m = $xml_tree->getElementsByTagName('MsgSignature');
        $array_t = $xml_tree->getElementsByTagName('TimeStamp');
        $array_n = $xml_tree->getElementsByTagName('Nonce');

        $encrypt = trim($array_e->item(0)->nodeValue);    //加密字符串
        $msg_sign = trim($array_m->item(0)->nodeValue);   //消息体签名
        $timeStamp = trim($array_t->item(0)->nodeValue);  //时间戳
        $nonce = trim($array_n->item(0)->nodeValue);  //随机数


        $format = "<xml><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);

        $msg = '';
        $pc = new WXBizMsgCrypt($this->token, $this->aesKey, $this->appId);
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);

        return ['code' => $errCode, 'msg' => $msg];

    }

    protected function getMessageInstance($msg)
    {
        $xml = new \DOMDocument();
        $xml->loadXML($msg);
        $type = trim($xml->getElementsByTagName('type')->item(0)->nodeValue);

        if (array_key_exists($type, Message::MESSAGE_TYPE_MAP)) {
            // message实例
            $msgCls = Message::MESSAGE_TYPE_MAP[$type];
            return new $msgCls($msg);
        } else {
            return NULL;
        }
    }
}