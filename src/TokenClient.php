<?php

namespace Nldou\Xiaoe;

use Nldou\Xiaoe\Exceptions\InvalidCacheSystemException;
use Nldou\Xiaoe\Exceptions\HttpClientException;
use Illuminate\Support\Facades\Cache;

use GuzzleHttp\Client as HttpClient;

class TokenClient
{
    // 店铺授权id
    private $appid;
    // 应用标识
    private $clientid;
    // 应用秘钥
    private $secretKey;

    // 调用凭证
    protected $accessToken;
    // 凭证有效期7200s
    const EXPIRE_IN = 7200;
    // 凭证类型
    const GRANT_TYPE = 'client_credential';

    public function __construct($appid, $clientid, $secretKey)
    {
        $this->appid = $appid;
        $this->clientid = $clientid;
        $this->secretKey = $secretKey;
    }

    public function getAccessToken()
    {
        if (!class_exists(Cache::class)) {
            throw new InvalidCacheSystemException('InvalidLaravelCacheSystem');
        }
        $cachekey = 'nldou-xiaoe:token';
        $token = Cache::get($cachekey, null);
        if (!is_null($token)) {
            // 获取缓存结果
            $this->accessToken= $token;
        } else {
            // 重新获取
            $res = $this->refreshToken();
            $this->accessToken = $res['access_token'];
            // 缓存结果
            $expireIn = (int)$res['expires_in'];
            Cache::put($cachekey, $res['access_token'], $expireIn);
        }
        return $this->accessToken;
    }

    /**
     * 请求token信息
     */
    private function refreshToken()
    {
        $client = new HttpClient();
        try {
            $res = $client->request('GET', 'https://api.xiaoe-tech.com/token', [
                'query' => [
                    'app_id' => $this->appid,
                    'client_id' => $this->clientid,
                    'secret_key' => $this->secretKey,
                    'grant_type' => self::GRANT_TYPE
                ]
            ]);
        } catch(\Exception $e) {
            throw new HttpClientException('TokenClientError-request-error-code-'.$e->getCode().'-'.$e->getMessage());
        }

        $res = json_decode($res->getBody(), true);
        $code = $res['code'];
        $msg = $res['msg'];
        if ($code != 0) {
            throw new HttpClientException("TokenClientError-{$code}-{$msg}-{$this->appid}-{$this->clientid}->{$this->secretKey}");
        }
        return $res['data'];
    }

    public function getAppId()
    {
        return $this->appid;
    }


}