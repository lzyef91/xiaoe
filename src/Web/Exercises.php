<?php

namespace Nldou\Xiaoe\Web;

use Nldou\Xiaoe\Exceptions\InvalidParamsException;

class Exercises extends WebApi
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 作业本列表
     * @param $options
     * [
     *   page, 页码
     *   search, 搜索内容
     * ]
     */
    public function bookIndex($cookie, $options = [])
    {
        $method = 'POST';
        $url = 'https://admin.xiaoe-tech.com/evaluation_work/exercise/get_exercise_book_list';

        $params['page_index'] = array_key_exists('page', $options) ? $options['page'] : 1;
        $params['search_content'] = array_key_exists('search', $options) ? $options['search'] : 1;

        return $this->request($url, $method, $cookie, $params);
    }

    /**
     * 作业列表
     * @param $options
     * [
     *   exercise_book_id, 作业本id
     *   page_size, 每页数量
     *   page, 页码
     *   search, 搜索内容
     * ]
     */
    public function index($cookie, $options = [])
    {
        $method = 'POST';
        $url = 'https://admin.xiaoe-tech.com/evaluation_work/exercise/get_exercise_list';

        if (!array_key_exists('exercise_book_id', $options)) {
            throw new InvalidParamsException('exercise_book_id can not be empty');
        }

        $params['exercise_book_id'] = $options['exercise_book_id'];
        $params['page_size'] = array_key_exists('page_size', $options) ? $options['page_size'] : 20;
        $params['page_index'] = array_key_exists('page', $options) ? $options['page'] : 1;
        $params['search_content'] = array_key_exists('search', $options) ? $options['search'] : 1;

        return $this->request($url, $method, $cookie, $params);
    }

    /**
     * 作业提交列表
     * @param $options
     * [
     *   exercise_book_id, 作业本id
     *   exercise_id, 作业id
     *   page_size, 每页数量
     *   page, 页码
     * ]
     */
    public function ansIndex($cookie, $options = [])
    {
        $method = 'POST';
        $url = 'https://admin.xiaoe-tech.com/evaluation_work/exercise/get_exercise_user_list';

        if (!array_key_exists('exercise_book_id', $options) ||
            !array_key_exists('exercise_id', $options)) {
            throw new InvalidParamsException('exercise_book_id or exercise_id can not be empty');
        }

        $params['exercise_book_id'] = $options['exercise_book_id'];
        $params['exercise_id'] = $options['exercise_id'];
        $params['page_size'] = array_key_exists('page_size', $options) ? $options['page_size'] : 20;
        $params['page_index'] = array_key_exists('page', $options) ? $options['page'] : 1;

        return $this->request($url, $method, $cookie, $params);
    }

    /**
     * 作业详情
     * @param $options
     * [
     *   exercise_book_id, 作业本id
     *   exercise_id, 作业id
     *   exercise_answer_id, 学员提交id
     * ]
     */
    public function ansShow($cookie, $options = [])
    {
        $method = 'POST';
        $url = 'https://admin.xiaoe-tech.com/evaluation_work/exercise/get_exercise_user_answer_info';

        if (!array_key_exists('exercise_book_id', $options) ||
            !array_key_exists('exercise_id', $options) ||
            !array_key_exists('exercise_answer_id', $options)) {
            throw new InvalidParamsException('exercise_book_id or exercise_id or exercise_answer_id can not be empty');
        }

        $params['exercise_book_id'] = $options['exercise_book_id'];
        $params['exercise_id'] = $options['exercise_id'];
        $params['exercise_answer_id'] = $options['exercise_answer_id'];

        return $this->request($url, $method, $cookie, $params);
    }

}