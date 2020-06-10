<?php

namespace Nldou\Xiaoe\Api;

use Nldou\Xiaoe\TokenClient;

class UserApi extends XiaoeApi
{
    public function __construct(TokenClient $tokenClient)
    {
        parent::__construct($tokenClient);
    }

    /**
     * 注册新用户
     * @param $options
     * [
     *   wx_union_id, 微信union_id, wx_union_id和phone至少有一个
     *   phone, 联系方式, wx_union_id和phone至少有一个
     *   avatar,
     *   nickname,
     *   gender, 0-无 1-男 2-女
     *   city,
     *   province,
     *   country
     * ]
     */
    public function register($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.user.register';

        $options = [
            'data' => $options
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }

    /**
     * 获取用户信息
     * @param $options
     * [
     *    user_id/phone/wx_union_id, 三选一
     *    field_list => [
     *      'wx_union_id', 'wx_open_id', 'wx_app_open_id', 'nickname', 'name', 'avatar', 'gender',
     *      'city', 'province', 'country', 'phone', 'birth', 'address', 'company', 'job', 'phone_collect'
     *    ]
     * ]
     */
    public function info($options = [])
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.user.info.get';

        $params = [];
        if (array_key_exists('user_id', $options)) {
            $params['user_id'] = $options['user_id'];
        } elseif (array_key_exists('phone', $options)) {
            $params['data'] = [
                'phone' => $options['phone']
            ];
        } elseif (array_key_exists('wx_union_id', $options)) {
            $params['data'] = [
                'wx_union_id' => $options['wx_union_id']
            ];
        }

        $fileds = array_key_exists('field_list', $options) ? $options['field_list']
                    : ['wx_union_id', 'wx_open_id', 'wx_app_open_id', 'nickname', 'name', 'avatar', 'gender',
                        'city', 'province', 'country', 'phone', 'birth', 'address', 'company', 'job', 'phone_collect'];

        $params = array_merge_recursive($params, [
                    'data' => ['field_list' => $fileds]
                ]);

        return $this->request($params, $method, $api, $apiVersion);
    }

    /**
     * 更新用户信息
     * @param $options
     * [
     *   'user_id',
     *   'update_data' => [
     *     nickname, name, avatar, gender, city,
     *     province, country, phone, birth, address,
     *     company, job
     *   ]
     * ]
     */
    public function update($options)
    {
        $method = 'POST';
        $apiVersion = '1.0.0';
        $api = 'xe.user.info.update';

        $options = [
            'user_id' => $options['user_id'],
            'data' => [
                'update_data' => $options['update_data']
            ]
        ];

        return $this->request($options, $method, $api, $apiVersion);
    }
}