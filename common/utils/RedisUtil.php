<?php
namespace common\utils;

/**
 * Redis工具类
 * Class RedisUtil
 * @package common\utils
 * @author Gene <https://github.com/Talkyunyun>
 */
class RedisUtil {
    private $_redis;

    public function __construct() {
        $this->_redis = \Yii::$app->redis;
    }


    /**
     * 判断KEY是否存在
     * @param string $key
     * @return bool
     */
    public function exists(string $key) {
        try {
            if (empty($key)) {
                return false;
            }

            if ($this->_redis->exists($key)) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 设置过期时间,单位秒
     * @param string $key
     * @param int $seconds
     * @return bool
     */
    public function expire(string $key, int $seconds) {
        try {
            if (!$this->exists($key)) {
                return false;
            }

            return $this->_redis->expire($key, $seconds);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取字符串类型数据
     * @param string $key
     * @return bool|mixed
     */
    public function get(string $key) {
        try {
            if (!$this->exists($key)) {
                return false;
            }

            $data = $this->_redis->get($key);
            if (mb_substr($data, 0, 5) == 'json:') {
                $data = json_decode(mb_substr($data, 5), true);
            }

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 设置字符串类型
     * @param string $key
     * @param mixed $value
     * @param int $seconds
     * @return bool
     */
    public function set(string $key, $value, int $seconds = 0) {
        try {
            if (empty($key)) {
                return false;
            }
            if (is_array($value)) {
                 $value =  'json:' . json_encode($value);
            }

            if ($seconds !== 0 && $this->_redis->set($key, $value)) {
                return $this->expire($key, $seconds);
            }

            return $this->_redis->set($key, $value);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 删除相应的key
     * @param string $key
     * @return bool
     */
    public function del(string $key) {
        try {
            return $this->_redis->del($key);
        } catch (\Exception $e) {
            return false;
        }
    }




    // TODO
    public function hmset(string $key, array $val) {
        try {
            if (empty($key) || !is_array($val)) return false;

            $data[] = $key;
            foreach ($val as $key => $row) {
                array_push($data, $key);
                array_push($data, $row);
            }

            return $this->_redis->executeCommand('HMSET', $data);
        } catch (\Exception $e) {
            return false;
        }
    }

    // TODO
    public function hgetall(string $key) {
        try {
            if (empty($key)) return [];
            $data = $this->_redis->hgetall($key);

            $result = [];
            $len = count($data);
            for ($i = 0; $i < $len; $i++) {

            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }
}