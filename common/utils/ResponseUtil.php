<?php
namespace common\utils;

use yii\web\Response;

/**
 * 响应工具类
 * Class ResponseUtil
 * @package app\utils
 * @author Gene <https://github.com/Talkyunyun>
 */
class ResponseUtil {

    // 响应错误message
    private static $msg;

    // 响应数据
    private static $data = [];

    // 响应错误码
    private static $code;

    // 响应类型
    public static $type = Response::FORMAT_JSON;


    /**
     * 错误响应
     * @param string $msg
     * @param int $code
     * @return array
     */
    public static function error(string $msg = '错误的请求', int $code = 1000) {
        \Yii::$app->response->format = self::$type;

        self::$code = $code;
        self::$msg  = $msg;

        return self::_getData();
    }


    /**
     * 成功响应
     * @param $data
     * @return array
     */
    public static function success($data = []) {
        \Yii::$app->response->format = self::$type;

        self::$data = $data;
        self::$code = 0;
        self::$msg  = '操作成功';

        return self::_getData();
    }

    // 获取统一格式
    private static function _getData() {
        return [
            'code' => self::$code,
            'data' => self::$data,
            'msg'  => self::$msg
        ];
    }
}