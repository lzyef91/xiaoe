<?php

namespace Nldou\Xiaoe\Message;

class Coupons extends Message
{
    /**
     * @var string
     */
    public $type = 'coupons';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
        'type', // order
        'app_id', // 店铺id
        'coupon_id', // 优惠券id
        'coupon_name', // 优惠券名称
        'valid_at', // 优惠券有效期开始时间
        'invalid_at', // 优惠券领取时间
        'receive_at', // 0-待支付，1-支付成功，2-支付失败
        'user_id', // 用户id
        'price', // 优惠券面值，单位：分
    ];

    /**
     * @param string $msg
     */
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}