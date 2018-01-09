<?php
namespace common\utils;

/**
 * 其他工具类
 * Class Util
 * @package app\utils
 * @author Gene <https://github.com/Talkyunyun>
 */
class Util {
    /**
     * 模型错误格式化
     * @param $errors
     * @param string $type
     * @return string
     */
    public static function getModelError($errors) {
        $str = [];
        if (is_array($errors)) {
            foreach($errors as $field => $error){
                $str[] = implode(',', $error);
            }
        }

        return implode('<br>', $str);
    }



    /**
     *    跳转提示函数
     * @author: Gene
     * @param : string $msg    弹窗提示内容
     * @param : string $url    跳转地址
     * @param : boolen $parent 是否在父窗口中打开
     * @param : int    $time    跳转时间(默认300)
     * @param : int    $status 状态提示取值范围：1绿色勾---2红色叉---3黄色问号---4灰色锁---5红色笑脸--6绿色笑脸---7黄色感叹号
     */
    public static function alert($msg, $url = NULL, $status = 2, $parent = FALSE, $time = 1) {
        $html  = '';
        $time  = $time * 900;
        $html .= '<meta charset="UTF-8">';
        $html .= '<title>' . $msg . '</title>';
        $html .= '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />';
        $html .= '<style>.layui-layer-btn{display:none;}</style><script type="text/javascript" src="http://s2.ystatic.cn/lib/jQuery/jQuery-2.2.3.min.js"></script>';
        $html .= '<script type="text/javascript" src="/js/plugins/layer/layer.min.js"></script>';
        $html .= '<script type="text/javascript" >';
        $html .= '$(function(){';
        $html .= "layer.alert('{$msg}', {
					title    : '温馨提示',
			      	icon	 : {$status},
			      	skin	 : 'layer-ext-moon',
			    	time	 : {$time},
			    	scrollbar: false,
			    	shift    : 0,
			    	closeBtn : 0,";
        if ($parent) {// 在父窗口中打开
            $html .= "end :function(){parent.window.location.reload();}";
            $url = false;
        }
        if ($url) {// 跳转提示
            $html .= "end :function(){window.location.href='{$url}';}";
        }
        $html .= '});});</script>';

        echo $html;die;
    }
}