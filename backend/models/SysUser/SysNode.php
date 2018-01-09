<?php
namespace backend\models\SysUser;

use yii\db\ActiveRecord;

/**
 * Class SysNode 节点表
 * @package app\models\SysUser
 * @property int $pid 父节点ID
 * @property string $name 节点名称
 * @property string $url URL地址
 * @property int $status 状态值
 * @property int $is_menu 是否是菜单，1：是 0：否
 * @property int $level 等级
 * @property int $can_del 是否可以删除，1：是 0：否
 * @property int $sort 排序
 * @property string $font_icon 菜单字体图标
 * @author Gene <https://github.com/Talkyunyun>
 */
class SysNode extends ActiveRecord {

    private static $forNodes = [];

    private static $oldNodes = [];

    public static function tableName() {
        return 'sys_node';
    }

    // 验证
    public function rules() {
        return [
            [['name', 'url'], 'required', 'message' => '{attribute}为必填字段'],
            [['name', 'url', 'sort', 'font_icon', 'pid', 'status', 'is_menu', 'level', 'can_del'], 'safe']
        ];
    }

    // 别名
    public function attributeLabels() {
        return [
            'name' => '名称',
            'url'  => 'url地址'
        ];
    }

    // 场景定义
    public function scenarios() {
        return [
            'create' => ['name', 'url', 'sort', 'font_icon', 'pid', 'status', 'is_menu'],
            'update' => ['name', 'url', 'sort', 'font_icon', 'status', 'is_menu']
        ];
    }


    /**
     * 获取用户菜单列表,缓存权限
     * @return array|mixed|string|\yii\db\ActiveRecord[]
     */
    public static function getMenus() {
        $user = \Yii::$app->user->identity;
        try {
            $session = \Yii::$app->session;

            $menu = $session->get('menu');
            if (empty($menu)) {
                if (strtolower($user->email) == \Yii::$app->params['sys_user_email']) {
                    $menu = self::getAllMenus();
                } else {
                    $menu = self::getUserMenus($user->id);
                }

                $session->set('menu', $menu);
            }

            return $menu;
        } catch (\Exception $e) {
            return [];
        }
    }



    // 获取全部jstree格式数据
    public static function getTreeMenu($pid = 0, $roleNodes = []) {
        $field = ['id', 'name text', 'pid', 'url'];
        $data = self::find()
            ->select($field)
            ->where([
                'pid' => $pid
            ])
            ->orderBy('id desc')
            ->asArray()
            ->all();
        foreach ($data as $key => $row) {
            $son = self::find()
                ->select($field)
                ->where([
                    'pid' => $row['id']
                ])
                ->orderBy('id desc')
                ->asArray()->all();
            if (in_array($row['id'], $roleNodes)) {
                $data[$key]['state']['selected'] = true;
            }

            if ($son) {
                $data[$key]['icon'] = 'fa fa-folder';
                $data[$key]['children'] = self::getTreeMenu($row['id'], $roleNodes);
            } else {
                $data[$key]['icon'] = 'fa fa-file-text-o';
            }
        }

        return $data;
    }


    // 根据ID获取信息
    public static function getDataById($id = 0) {
        try {
            return self::find()->where([
                'id' => $id
            ])->asArray()->one();
        } catch (\Exception $e) {
            return [];
        }
    }


    /**
     * 获取所有子节点
     * @param $id
     * @param array $ids
     * @return array
     */
    public static function getMenuAllChildById($id) {
        $ids  = [];
        $data = self::find()
            ->select('id, pid')
            ->where([
                'pid'     => $id,
                'can_del' => 1
            ])->asArray()->all();
        if(!empty($data)){
            foreach($data as $item) {
                $ids[]  = $item['id'];
                $result = self::getMenuAllChildById($item['id']);
                if (count($result) > 0) {
                    $ids = array_merge($ids, $result);
                }
            }
        }

        return $ids;
    }


    /**
     * 获取所有菜单列表
     */
    public static function getAllMenus($pid = 0) {
        try {
            $result = self::find()
                ->select('id, name text, url url_key, font_icon')
                ->where([
                    'pid'    => $pid,
                    'status' => 1,
                    'is_menu'=> 1
                ])
                ->asArray()
                ->all();
            foreach ($result as $key => $row) {
                $result[$key]['children'] = self::getAllMenus($row['id']);
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }


    /**
     * 获取用户菜单
     * @param int $uid
     * @return string
     */
    public static function getUserMenus($uid = 0) {
        try {
            $query = SysRole::find()
                ->from(SysRole::tableName() . ' a')
                ->rightJoin(SysUserRole::tableName() . ' b', 'a.id=b.role_id')
                ->rightJoin(SysPermission::tableName() . ' c', 'a.id=c.role_id')
                ->leftJoin(SysNode::tableName() . ' d', 'c.node_id=d.id')
                ->select('d.id, d.name text, d.url url_key, d.pid, d.font_icon')
                ->asArray()
                ->orderBy('d.sort desc')
                ->where('d.pid<>0 AND a.status=1 AND b.user_id=:uid AND d.status=1 AND d.is_menu=1', [':uid' => $uid]);

            $nodeIds = $query->all();
            $nodeIds = self::getAllUserNodes($nodeIds);
            $data = self::doUserMenu($nodeIds);

            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 获取用户全部节点
     * @param array $nodeIds
     * @return array
     */
    private static function getAllUserNodes($nodeIds = []) {
        try {
            foreach ($nodeIds as $row) {
                self::forGetParentNodes($row);
            }

            $oldNodes = [];
            $forNodes = array_merge($nodeIds, self::$forNodes);
            // 过滤节点
            foreach ($forNodes as $key => $row) {
                if (in_array($row['id'], $oldNodes)) {
                    unset($forNodes[$key]);
                    continue;
                }

                foreach ($forNodes as $r) {
                    if ($row['id'] == $r['id']) {
                        array_push($oldNodes, $r['id']);
                        continue 2;
                    }
                }
            }

            return $forNodes;
        } catch (\Exception $e) {
            return [];
        }
    }


    /**
     * 递归处理菜单
     * @param array $node
     * @return array|null|ActiveRecord
     */
    private static function forGetParentNodes($node = []) {
        $data = self::find()
            ->select('id, name text, pid, url url_key, font_icon')
            ->where('status=1 AND is_menu=1 AND id=:id', [
                ':id' => $node['pid']
            ])->asArray()->one();

        array_push(self::$forNodes, $data);
        if (!empty($data) && $data['pid'] != 0 && !in_array($data['id'], self::$oldNodes)) {
            array_push(self::$oldNodes, $data['id']);
            self::forGetParentNodes($data);
        }

        return $data;
    }


    /**
     * 菜单数据处理,只处理到第三级
     * @param array $data
     * @return array
     */
    private static function doUserMenu($data = []) {
        try {
            $oneIds = [];
            $result = [];
            $i = 0;
            foreach ($data as $key=>$row) {
                if ($row['pid'] == 0) {// 顶级菜单
                    $oneIds[$i] = $row['id'];
                    $result[$i] = $row;
                    $i++;
                    unset($data[$key]);
                }
            }

            $twoIds = [];
            $j = 0;
            foreach ($data as $key => $row) {
                $oneKey = array_keys($oneIds, $row['pid']);
                if (!empty($oneKey)) {
                    $twoIds[$row['id']] = [$oneKey[0], $j];
                    $result[$oneKey[0]]['children'][$j] = $row;
                    $j++;
                    unset($data[$key]);
                }
            }

            foreach ($data as $key => $row) {
                $twoKey = isset($twoIds[$row['pid']]) ? $twoIds[$row['pid']] : false;
                if (!empty($twoKey)) {
                    $result[$twoKey[0]]['children'][$twoKey[1]]['children'][] = $row;
                    unset($data[$key]);
                }
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

}

