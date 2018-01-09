<?php
namespace slow\controllers;

use common\utils\RedisUtil;
use common\utils\RequestUtil;
use common\utils\ResponseUtil;
use slow\models\Clients;


/**
 * 登录接口
 * Class LoginController
 * @package slow\controllers
 */
class LoginController extends BaseController {

    // 错误信息
    private $errMsg = false;

    // 参数对象
    private $data = [];

    // 请求对象
    private $request = false;

    // 初始化参数
    private function _initParams() {
        $request = $this->request = \Yii::$app->request;

        if (!$request->isPost) {
            $this->errMsg = '非法请求';
            return false;
        }

        try {
            $code = $request->post('code', false);
            $rawData = $request->post('rawData', false);
            $signature = $request->post('signature', false);
            $encryptedData = $request->post('encryptedData', false);
            $iv = $request->post('iv', false);
            $user = json_decode($rawData, true);
            if (empty($code) || empty($rawData) || empty($signature) || empty($encryptedData) || empty($iv) || empty($user) || !is_array($user)) {
                $this->errMsg = '缺省必要参数';
                return false;
            }

            $this->data['code'] = $code;
            $this->data['rawData'] = $rawData;
            $this->data['signature'] = $signature;
            $this->data['encryptedData'] = $encryptedData;
            $this->data['iv'] = $iv;
            $this->data['user'] = $user;
        } catch (\Exception $e) {
            $this->errMsg = '缺省必要参数';
        }
    }


    /**
     * 登录注册接口
     * @return array
     */
    public function actionIndex() {
        // 初始化参数
        $this->_initParams();

        if ($this->errMsg != false) {
            return ResponseUtil::error($this->errMsg);
        }

        try {
            $params = \Yii::$app->params;
            $result = RequestUtil::curlReq($params['wechat_session_key_url'], [
                'appid'     => $params['slow_appid'],
                'secret'    => $params['slow_secret'],
                'js_code'   => $this->data['code'],
                'grant_type'=> 'authorization_code'
            ]);

            // {"openid": "OPENID", "session_key": "SESSIONKEY", "unionid": "UNIONID"}
            if (isset($result['errcode'])) throw new \Exception($result['errmsg']);
        } catch (\Exception $e) {
            return ResponseUtil::error($e->getMessage());
        }

        // 参数合法验证
        $signature1 = sha1($this->data['rawData'] . $result['session_key']);
        if ($signature1 != $this->data['signature']) {
            return ResponseUtil::error('非法签名');
        }

        $fields = ['mobile', 'email', 'nick_name', 'first_name', 'last_name', 'avatar_url', 'gender', 'language'];
        // 判断是否存在该用户
        $client = Clients::find()
            ->select($fields)
            ->where(['wechat_openid' => $result['openid']])
            ->asArray()->one();
        if (empty($client)) {// 注册用户
            $model = new Clients();
            $model->nick_name = $this->data['user']['nickName'];
            $model->wechat_openid = $result['openid'];
            $model->avatar_url = $this->data['user']['avatarUrl'];
            $model->gender = $this->data['user']['gender'];
            $model->language = $this->data['user']['language'];
            if (!$model->save()) {
                return ResponseUtil::error('操作失败');
            }

            $client = Clients::find()
                ->select($fields)
                ->where(['wechat_openid' => $result['openid']])
                ->asArray()->one();
        }

        // 缓存数据,生成token
        $expire= 86400;
        $token = hash('sha256', microtime(true) . $result['openid'] . 'gene-slow');
        $redis = new RedisUtil();
        $redis->set($token, $client);
        $redis->expire($token, $expire);

        return ResponseUtil::success([
            'token' => $token,
            'expire'=> date('Y-m-d H:i:s', time() + $expire),
            'user'  => $client
        ]);
    }
}