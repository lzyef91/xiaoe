<?php

namespace Nldou\Xiaoe\Message;

class Order extends Message
{
    /**
     * @var string
     */
    public $type = 'order';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
        'type', // order
        'app_id', // 店铺id
        'order_id', // 订单id
        'user_id', // 用户id
        'product_id', // 资源id
        'purchase_name', // 购买时资源的名字，为unicode编码需要转换
        'order_state', // 0-待支付，1-支付成功，2-支付失败
        'resource_type', // 资源类型：0-无（不通过资源的购买入口）1-图文 2-音频 3-视频 4-直播 5-活动报名 6-专栏/会员 7-社群 8-大专栏 20-电子书 21-实物商品
        'payment_type', // 付费类型：2-单笔 3-付费产品包 4-团购 5-单笔的购买赠送 6-产品包的购买赠送 7-问答提问 8-问答偷听 11-付费活动报名 12-打赏类型 13-拼团单个资源 14-拼团产品包 15-超级会员
        'created_at', // 订单创建时间
        'transaction_id' // 交易单号 order_state=3时存在
    ];
    // <type>order</type>
    // <app_id>appXaayutBi4270</app_id>
    // <order_id>o_1591660014_5edecdeea7b0a_66638252</order_id>
    // <user_id>u_5edecda17347a_q2msdED1jY</user_id>
    // <product_id>p_5ebce27febff9_8pMlFTqn</product_id>
    // <purchase_name>u7236u6bcdu6210u957fu8badu7ec3u8425 u8ba9u4e2du56fdu5bb6u5eadu6559u80b2u6b63u672cu6eafu6e90</purchase_name>
    // <order_state>0</order_state>
    // <resource_type>6</resource_type>
    // <payment_type>3</payment_type>
    // <created_at>2020-06-09 07:46:54</created_at>

    // <type>order</type>
    // <app_id>appXaayutBi4270</app_id>
    // <order_id>o_1591660014_5edecdeea7b0a_66638252</order_id>
    // <user_id>u_5edecda17347a_q2msdED1jY</user_id>
    // <product_id>p_5ebce27febff9_8pMlFTqn</product_id>
    // <purchase_name>u7236u6bcdu6210u957fu8badu7ec3u8425 u8ba9u4e2du56fdu5bb6u5eadu6559u80b2u6b63u672cu6eafu6e90</purchase_name>
    // <order_state>1</order_state>
    // <resource_type>6</resource_type>
    // <payment_type>3</payment_type>
    // <created_at>2020-06-09 07:46:54</created_at>
    // <transaction_id>4200000541202006090956355598</transaction_id>

    /**
     * @param string $msg
     */
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}