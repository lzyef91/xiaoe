<?php

namespace Nldou\Xiaoe\Web;

class Comments extends WebApi
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 评论列表
     * @param $options
     * [
     *   state, 评论状态 显示-0 隐藏-1 未精选-2 精选-3 黑名单-4 屏蔽-5
     *   source, 来源 小程序-0 公众号-1
     *   search, 搜索字段 评论内容-content 用户昵称-name 内容名称-title
     *   content, 搜索内容
     *   record_id, 资源id
     * ]
     */
    public function get($cookie, $options = [])
    {
        $method = 'GET';
        $url = 'https://admin.xiaoe-tech.com/get/comment_admin_page?';
        $url .= \http_build_query($options);

        return $this->request($url, $method, $cookie);
    }
}

