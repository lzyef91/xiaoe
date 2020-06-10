<?php

namespace Nldou\Xiaoe\Api;

use Nldou\Xiaoe\TokenClient;

class GoodsApi extends XiaoeApi
{
    public function __construct(TokenClient $tokenClient)
    {
        parent::__construct($tokenClient);
    }

    /**
     * 商品详情
     * @param $options
     * [
     *   goods_id,
     *   goods_type 图文-1，音频-2，视频-3，直播-4，会员-5，专栏-6，大专栏-8，电子书-20
     * ]
     */
    public function detail($options = [])
    {
        $method = 'POST';
        $apiVersion = '3.0.0';
        $api = 'xe.goods.detail.get';

        $options = [
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }
}