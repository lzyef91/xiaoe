<?php

namespace Nldou\Xiaoe\Web;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Nldou\Xiaoe\Exceptions\HttpClientException;

class WebApi
{
    // 客户端
    protected $client;

    // 请求地址
    protected $url;

    public function __construct()
    {
        $this->client = new HttpClient();
    }

    protected function request(string $url, string $method, string $cookie, array $params = [])
    {
        // api请求
        try {
            // 请求方法
            $method = \strtoupper($method);
            // 请求对象
            $request = new Request($method, $url);
            // 请求配置
            $options = [
                'headers' => ['Cookie' => $cookie],
                'json' => $params
            ];
            // 响应对象
            $response = $this->client->send($request, $options);
            $res = \json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            throw new HttpClientException("web-api-request-error-{$method}-{$url}-{$msg}");
        }

        // 返回结果
        return $res['data'];
    }
}