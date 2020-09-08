<?php

namespace Nldou\Xiaoe\Api;

use Nldou\Xiaoe\TokenClient;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Nldou\Xiaoe\Exceptions\HttpClientException;

class XiaoeApi
{
    // api客户端
    protected $client;
    // token客户端
    protected $tokenClient;

    public function __construct(TokenClient $tokenClient)
    {
        $this->tokenClient = $tokenClient;
        $this->client = new HttpClient();
    }

    private function formatRequestUrl($api, $apiVersion)
    {
        return "http://api.xiaoe-tech.com/{$api}/{$apiVersion}";
    }

    protected function request(array $options = [], string $method, string $api, string $apiVersion)
    {
        // 获取凭证
        $acceessToken = $this->tokenClient->getAccessToken();
        $options = array_merge($options, ['access_token' => $acceessToken]);

        // api请求
        try {
            // 请求方法
            $method = \strtoupper($method);
            // 请求地址
            $url = $this->formatRequestUrl($api, $apiVersion);
            // 请求对象
            $request = new Request($method, $url);
            // 响应对象
            $response = $this->client->send($request, ['json' => $options]);
            $res = \json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            throw new HttpClientException("request-error-{$api}-{$apiVersion}-{$msg}", $e->getCode());
        }

        // 请求错误处理
        $code = $res['code'];
        $msg = $res['msg'];
        if ($code != 0) {
            throw new HttpClientException("ApiHttpError-{$api}-{$apiVersion}-{$code}-{$msg}", $code);
        }

        // 返回结果
        return $res['data'];
    }
}