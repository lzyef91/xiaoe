<?php

namespace Nldou\Xiaoe;

use Nldou\Xiaoe\Exceptions\InvalidApiException;
use Nldou\Xiaoe\Api\UserApi;
use Nldou\Xiaoe\Api\GoodsApi;
use Nldou\Xiaoe\Api\OrderApi;

use Nldou\Xiaoe\Exceptions\InvalidWebException;
use Nldou\Xiaoe\Web\Comments;
use Nldou\Xiaoe\Web\Exercises;
use Nldou\Xiaoe\Web\Auth;
use Nldou\Xiaoe\Message\Server;

class Xiaoe
{
    /**
     * @var TokenClient
     */
    private $tokenClient;

    /**
     * api服务实例
     * @var array
     */
    private $apiServices = [];

    /**
     * api服务提供者
     * @var array
     */
    private $apiProviders = [
        'user' => UserApi::class,
        'goods' => GoodsApi::class,
        'order' => OrderApi::class
    ];

    /**
     * web服务提供者
     * @var array
     */
    private $webProviders = [
        'comments' => Comments::class,
        'exercises' => Exercises::class,
        'auth' => Auth::class
    ];

    /**
     * web服务实例
     * @var array
     */
    private $webServices = [];

    /**
     * 消息推送
     */
    private $server;

    /**
     * Constructor
     *
     * @param Token $token
     */
    public function __construct(TokenClient $tokenClient, Server $server)
    {
        $this->tokenClient = $tokenClient;
        $this->server = $server;
    }

    /**
     * 获取api服务实例
     *
     * @param string $class
     *
     * @return \Nldou\Youzan\Api\YouzanApi
     */
    public function api(string $class)
    {
        // 是否存在api
        if (!array_key_exists($class, $this->apiProviders)) {
            throw new InvalidApiException("{$class}_api_not_exist");
        }

        // 获取api
        $apiClass = $this->apiProviders[$class];

        // 获取api实例
        if (!array_key_exists($apiClass, $this->apiServices)) {
            $this->apiServices[$apiClass] = new $apiClass($this->tokenClient);
        }

        return $this->apiServices[$apiClass];
    }

    /**
     * 获取web服务实例
     *
     * @param string $class
     *
     * @return \Nldou\Youzan\Api\YouzanApi
     */
    public function web($class)
    {
        // 是否存在web
        if (!array_key_exists($class, $this->webProviders)) {
            throw new InvalidWebException("{$class}_web_not_exist");
        }

        // 获取web
        $webClass = $this->webProviders[$class];

        // 获取web实例
        if (!array_key_exists($webClass, $this->webServices)) {
            $this->webServices[$webClass] = new $webClass();
        }

        return $this->webServices[$webClass];
    }

    public function server()
    {
        return $this->server;
    }
}