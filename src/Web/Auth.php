<?php

namespace Nldou\Xiaoe\Web;
use Nldou\Xiaoe\Exceptions\HttpClientException;

class Auth extends WebApi
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 模拟登录获取cookie
     * @param $options
     * [
     *   state, 评论状态 显示-0 隐藏-1 未精选-2 精选-3 黑名单-4 屏蔽-5
     *   source, 来源 小程序-0 公众号-1
     *   search, 搜索字段 评论内容-content 用户昵称-name 内容名称-title
     *   content, 搜索内容
     *   record_id, 资源id
     * ]
     */
    public function get($username, $password)
    {
        $method = 'POST';
        $url = 'https://admin.xiaoe-tech.com/dologin';
        $params = [
            'username' => $username,
            'password' => $password
        ];

        $response = $this->request($url, $method, '', $params, true);

        $res = \json_decode($response->getBody(), true);

        // 业务错误
        if ($res['code'] != 0) {
            $msg = $res['msg'];
            throw new HttpClientException("web-api-request-error-{$method}-{$url}-{$msg}");
        }

        return ($response->getHeaders())['Set-Cookie'];

    }
}

