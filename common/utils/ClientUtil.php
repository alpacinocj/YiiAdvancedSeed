<?php
namespace common\utils;

/**
 * 客户端工具类
 * Class ClientUtil
 * @package app\utils
 * @author Gene <https://github.com/Talkyunyun>
 */
class ClientUtil {

    /**
     * 获取操作系统或者浏览器已经版本信息
     * @return array
     */
    public static function getClientInfo() {
        $uAgent   = $_SERVER['HTTP_USER_AGENT'];
        $bname    = 'Unknown';
        $platform = 'Unknown';
        $ub       = '';
        $version  = '';

        // 获取操作系统
        if (preg_match('/linux/i', $uAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $uAgent)) {
            $platform = 'MAC';
        } elseif (preg_match('/windows|win32/i', $uAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/unix/i', $uAgent)) {
            $platform = 'Unix';
        } elseif (preg_match('/bsd/i', $uAgent)) {
            $platform = 'BSD';
        } elseif (preg_match('/iPhone/i', $uAgent)) {
            $platform = 'iPhone';
        } elseif (preg_match('/iPad/i', $uAgent)) {
            $platform = 'iPad';
        } elseif (preg_match('/iPod/i', $uAgent)) {
            $platform = 'iPod';
        } elseif (preg_match('/android/i', $uAgent)) {
            $platform = 'Android';
        }

        // 获取浏览器名称
        if (
            (preg_match('/MSIE/i', $uAgent) ||
            strpos($uAgent, 'rv:11.0')) &&
            !preg_match('/Opera/i', $uAgent)
        ) {
            $bname = 'Internet Explorer';
            $ub = 'MSIE';
        } elseif (preg_match('/Firefox/i', $uAgent)) {
            $bname = 'Mozilla Firefox';
            $ub = 'Firefox';
        } elseif (preg_match('/Edge/i', $uAgent)) {
            //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
            $bname = 'Microsoft Edge';
            $ub = 'Edge';
        } elseif (preg_match('/Chrome/i', $uAgent)) {
            $bname = 'Google Chrome';
            $ub = 'Chrome';
        } elseif (preg_match('/Safari/i', $uAgent)) {
            $bname = 'Apple Safari';
            $ub = 'Safari';
        } elseif (preg_match('/Opera/i', $uAgent)) {
            $bname = 'Opera';
            $ub = 'Opera';
        } elseif (preg_match('/Netscape/i', $uAgent)) {
            $bname = 'Netscape';
            $ub = 'Netscape';
        } elseif (preg_match('/Maxthon/i', $uAgent)) {
            $bname = 'Maxthon';
            $ub = 'Maxthon';
        } elseif (preg_match('/Lynx/i', $uAgent)) {
            $bname = 'Lynx';
            $ub = 'Lynx';
        } elseif (preg_match('/w3m/i', $uAgent)) {
            $bname = 'w3m';
            $ub = 'w3m';
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $uAgent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($uAgent, 'Version') < strripos($uAgent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = '?';
        }

        return [
            'userAgent'=> $uAgent,    //用户客户端信息
            'name'     => $bname,     //浏览器名称
            'version'  => $version,   //浏览器版本
            'platform' => $platform   //使用平台
        ];
    }
}