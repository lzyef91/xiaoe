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

    protected function request(string $url, string $method, string $cookie,
        array $params = [], bool $returnResponseInstance = false)
    {
        // api请求
        try {
            // 请求方法
            $method = \strtoupper($method);
            // 请求对象
            $request = new Request($method, $url);
            // 请求配置
            $options = [
                'json' => $params
            ];
            if (!empty($cookie)) {
                $options = array_merge($options, ['headers' => ['Cookie' => $cookie]]);
            }
            // 响应对象
            $response = $this->client->send($request, $options);

            if ($returnResponseInstance) return $response;

            $res = \json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            throw new HttpClientException("web-api-request-error-{$method}-{$url}-{$msg}");
        }

        // 业务错误
        if ($res['code'] != 0) {
            $msg = $res['msg'];
            throw new HttpClientException("web-api-request-error-{$method}-{$url}-{$msg}");
        }

        // 返回结果
        return $res['data'];
    }
}