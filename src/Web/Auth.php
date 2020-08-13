<?php

namespace Nldou\Xiaoe\Web;

class Auth extends WebApi
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 模拟登录获取cookie
     * @param $username, 用户名
     * @param $password, 密码
     * @param $appid, 店铺id
     */
    public function get($username, $password, $appid)
    {
        $method = 'POST';
        $url = 'https://admin.xiaoe-tech.com/dologin';
        $params = [
            'username' => $username,
            'password' => $password
        ];

        // 登录
        $response = $this->request($url, $method, '', $params, true);

        // 获取cookie
        $cookie = ($response->getHeaders())['Set-Cookie'];
        $cookie = implode(';', $cookie);

        // 选择店铺
        $url = "https://admin.xiaoe-tech.com/choose_shop?app_id={$appid}";
        $response = $this->request($url, 'GET', $cookie, [], true);

        // 获取cookie
        $cookie = ($response->getHeaders())['Set-Cookie'];
        $cookie = implode(';', $cookie);

        return $cookie;

    }
}

