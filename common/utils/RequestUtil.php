<?php
namespace common\utils;

/**
 * CURL工具类
 * Class RequestUtil
 * @package app\utils
 * @author Gene <https://github.com/Talkyunyun>
 */
class RequestUtil {


    /**
     * curl请求
     * @param string $url   [请求的URL地址]
     * @param array $params [请求的参数]
     * @param bool $isPost  [是否采用POST形式]
     * @param bool $isJson  [是否采用json格式发送数据]
     * @return bool|mixed
     */
    public static function curlReq(string $url, array $params = [], bool $isPost = false, bool $isJson = false) {
        if (empty($url)) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// 结果以文件流返回，不直接输出
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);// 使用http/1.1
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // 不验证ssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($isPost) {// POST请求
            if ($isJson) {// 是否为json发送
                $params = json_encode($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type:application/json;charset=utf-8',
                    'Content-Length:'.strlen($params)
                ]);
            } else {// 普通post请求
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
        } else {// GET请求
            if ($params) {
//                var_dump($url.'?'.http_build_query($params));die;
                curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $res   = curl_exec($ch);
        $error = curl_errno($ch);
        if ($error) {
            return false;
        }
        curl_close($ch);

        return json_decode($res, true);
    }
}