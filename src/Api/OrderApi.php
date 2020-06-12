<?php

namespace Nldou\Xiaoe\Api;

use Nldou\Xiaoe\TokenClient;

class OrderApi extends XiaoeApi
{
    public function __construct(TokenClient $tokenClient)
    {
        parent::__construct($tokenClient);
    }

    /**
     * 查询是否订阅
     * @param $options
     * [
     *    user_id, 用户id
     *    product_id, 资源id
     *    payment_type, 付费类型：2-单笔、3-付费产品包、4-团购、5-单笔的购买赠送、6-产品包的购买赠送、
     *                  7-问答提问、8-问答偷听、11-付费活动报名、12-打赏类型、13-拼团单个资源、14-拼团产品包、15-超级会员
     * ]
     */
    public function available($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.product.available';

        $options = [
            'user_id' => $options['user_id'],
            'data' => [
                'product_id' => $options['product_id'],
                'payment_type' => $options['payment_type']
            ]
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 查询产品包是否订阅
     * @param $options
     * [
     *   user_id, 用户ID
     *   goods_id, 产品包ID
     * ]
     */
    public function check($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.user.asset.check';

        $appid = $this->tokenClient->getAppId();

        $options = array_merge($options, ["app_id" => $appid, "asset_type" => "goods"]);

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 获取订单列表
     * @param $options
     * [
     *    （均为非必填）
     *    order_by 排序方式。格式为column:asc/desc，column可选值：created_at ，默认为created_at:desc
     *    page_index 页码，从0开始 ,默认为0
     *    page_size	每页请求条数，最大支持50,默认取10条
     *    user_id 用户id
     *    begin_time 查询起始时间，unix 时间戳，单位为秒。与end_time的差值不能超过86400（24小时），如果为空，则默认取以end_time为准24小时前的时间戳
     *    end_time 查询结束时间，unix 时间戳，单位为秒。与begin_time的差值不能超过86400（24小时），如果为空，则默认取当前最新时间戳
     *    order_state 订单状态：0-未支付 1-支付成功 2-支付失败 3-已退款(如拼团未成功等情况) 6-订单超时未支付，自动取消
     *    resource_type 查询单笔资源时(即payment_type=2时)需传入该参数，1-图文 2-音频 3-视频 4-直播 5-活动 7-社群 不填查全部类型
     *    payment_type 付费类型 2-单笔 3-订阅付费产品包(专栏和会员) 4-购买赠送 5-拼团 默认取出这四种类型的所有订单
     *    client_type 购买时使用的客户端类型，0-小程序 1-公众号 10-其他，不填查全部类型
     *    goods_no 商品编码
     * ]
     */
    public function index($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.order.list.get';

        $appid = $this->tokenClient->getAppId();

        $options = [
            'app_id' => $appid,
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 获取用户订单列表
     * @param $options
     * [
     *    user_id 必填 用户id
     *    （以下均为非必填）
     *    order_id 订单id
     *    product_id 资源id
     *    begin_time 订单创建开始时间，例如：2019-08-01 12:00:00 格式
     *    end_time 订单创建结束时间，例如：2019-08-01 12:00:00 格式
     *    order_state 订单状态：0-未支付 1-支付成功 2-支付失败 6-订单超时未支付，自动取消 10-退款成功
     *    payment_type 付费类型：2-单笔、3-付费产品包、4-团购、5-单笔的购买赠送、6-产品包的购买赠送、7-问答提问、8-问答偷听、11-付费活动报名、12-打赏类型、13-拼团单个资源、14-拼团产品包、15-超级会员
     *    resource_type 资源类型：0-无（不通过资源的购买入口）、1-图文、2-音频、3-视频、4-直播、5-活动报名、6-专栏/会员、7-社群、8-大专栏、20-电子书、21-实物商品、23-超级会员 25-训练营 29-面授课
     *    client_type 购买时使用的客户端类型，0-小程序 1-公众号 10-开放API，不填查全部类型
     *    page_index 页码，从0开始 ,默认为0
     *    page_size	每页请求条数，最大支持50,默认取10条
     *    order_by 排序方式。格式为column:asc/desc，column可选值：created_at ，默认为created_at:desc
     * ]
     */
    public function userIndex($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.get.user.orders';

        $userid = $options['user_id'];

        unset($options['user_id']);

        $options = [
            'user_id' => $userid,
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 查询用户订阅列表
     * @param $options
     * [
     *   user_id, 必填 用户ID
     *   payment_type, 非必填，2-单笔（图文音频视频直播社群）、3-付费产品包（专栏大专栏会员训练营）、15-超级会员,
     *   resource_type, 非必填，1-图文、2-音频、3-视频、4-直播、5-活动报名、6-专栏/会员、7-社群、8-大专栏、
     *                  20-电子书、21-实物商品、23-超级会员 25-训练营 29-面授课,
     *   product_id, 非必填，资源id
     *   begin_time, 非必填，订购创建的开始时间，例：2019-08-01 12:00:00
     *   end_time, 非必填，订购创建的结束时间
     *   page_index, 非必填，页码，从1开始 ，默认为1
     *   page_size, 非必填，每页请求条数，最大支持50，默认取10条
     *   order_by, 非必填，排序方式。格式为column:asc/desc，column可选值：created_at 创建时间，默认为created_at:desc
     * ]
     */
    public function userGoodsIndex($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.resource.purchase.get';

        $appid = $this->tokenClient->getAppId();

        $options = [
            'app_id' => $appid,
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 取消订阅关系
     * @param $options
     * [
     *    user_id, 购买商品的用户id
     *    payment_type, 2-单笔（图文音频视频直播社群）、3-付费产品包（专栏大专栏会员训练营）、15-超级会员
     *    resource_type, 单笔购买时为必要参数，资源类型：1-图文、2-音频、3-视频、4-直播、23-超级会员
     *    resource_id, 单笔购买时为必要参数，资源id
     *    product_id, 购买产品包时和超级会员时为必要参数，产品包id
     * ]
     */
    public function delete($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.purchase.delete';

        $options = [
            'user_id' => $options['user_id'],
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 订阅课程
     * @param $options
     * [
     *    user_id, 购买商品的用户id
     *    resource_type, 必填，资源类型：0-会员/专栏、1-图文、2-音频、3-视频、4-直播、8-大专栏、23-超级会员、25-训练营
     *    resource_id, 资源id，payment_type=2时必填
     *    product_id, 产品包id, payment_type=3时必填
     *    payment_type, 必填，2-单笔（图文音频视频直播社群）、3-付费产品包（专栏大专栏会员训练营）、15-超级会员
     *    pay_way, 非必填，默认0 0-线上微信，2-线上支付宝，1-未指定方式，
     *    period, 非必填，有效期（秒）；超级会员订单：必填；目前小鹅通只支持类型：7天，一个月（30天），3个月（90天），半年（180天），一年（365天）
     *    period_time, 非必填，购买会员的开始(生效)时间，例：2019-02-20 15:15:00；超级会员订单：必填；
     *    channel_id, 非必填，渠道ID
     *    channel_info, 非必填，渠道来源
     *    agent，非必填，用户设备信息
     * ]
     */
    public function create($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.order.delivery';

        $options = [
            'user_id' => $options['user_id'],
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }
}