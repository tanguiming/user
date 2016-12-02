<?php

/**
 *   用户信息 model 层
 *   
 *   @author	songmw<song_mingwei@cdv.com>
 *   @date		2013-12-04
 *   @version  1.0
 */
 
namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Weitac\User\Http\Models\UserBaseModel as UserBaseModel;

class UserDetail extends UserBaseModel {

    protected $table = 'user_detail';
    protected $primaryKey = 'user_id';
    protected $fields = array('name', 'sex', 'birthday', 'telephone', 'mobile', 'address', 'zipcode', 'qq', 'head_picture');  // 表字段
    protected $_where;    // 搜索条件 array 'param' => 'name like ? and ...'  'bind' => 'array("admin", "...")'
    public $timestamps = false;
    // 收录类型
    static public $sex = array(1 => '男', 2 => '女');

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     *  填充数据
     *  
     *  @param array 		传递进来一个 获取的数组
     *  @return array 		返回本表字段的填充后的数组
     */
    public function fullData($data = null) {
        $rs = array(
            'name' => isset($data['name']) ? $data['name'] : '',
            'sex' => isset($data['sex']) ? $data['sex'] : '',
            'birthday' => isset($data['birthday']) ? $data['birthday'] : '',
            'telephone' => isset($data['telephone']) ? $data['telephone'] : '',
            'mobile' => isset($data['mobile']) ? $data['mobile'] : '',
            'address' => isset($data['address']) ? $data['address'] : '',
            'zipcode' => isset($data['zipcode']) ? $data['zipcode'] : '',
            'qq' => isset($data['qq']) ? $data['qq'] : '',
            'head_picture' => isset($data['head_picture']) ? $data['head_picture'] : '',
            'openid' => isset($data['openid']) ? $data['openid'] : '',
            'fakeid' => isset($data['fakeid']) ? $data['fakeid'] : '',
            'last_time' => isset($data['last_time']) ? $data['last_time'] : '',
            'province' => isset($data['province']) ? $data['province'] : '',
            'country' => isset($data['country']) ? $data['country'] : '',
            'subscribe_time' => isset($data['subscribe_time']) ? $data['subscribe_time'] : '',
             'city' => isset($data['city']) ? $data['city'] : '',
        );

        // 如果主键不为空，则包含主键
        if (!empty($data[$this->primaryKey])) {
            $rs[$this->primaryKey] = $data[$this->primaryKey];
        }

        return $rs;
    }

    /**
     *  检查数据
     *  
     *  @param array		$data 						验证的数组	
     *  @return boolean $info 							返回的状态 status = boolean, msg = '提示信息'	
     */
    public function check($data) {
        if (empty($data))
            return array('status' => false, 'msg' => '附属信息为空');

        if (empty($data[$this->primaryKey]))
            return array('status' => false, 'msg' => '主键为空');

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     * 重写父方法
     *
     */
    public function add($data = null) {
        if (empty($data))
            return false;

        $rs = $this->insert($data);

        if ($rs) {
            return array('status' => true, 'msg' => '创建成功');
        } else {
            return array('status' => false, 'msg' => '创建失败');
        }
    }

    /**
     * 
     * @param type $uid
     * @param type $point
     */
    static public function addPoint($uid, $point) {
        $obj = $this::find($uid);

        $obj->point = $obj->point + $point;
        $obj->save();
    }

    /**
     * 
     * @param type $uid
     * @param type $point
     */
    static public function subtractionPoint($uid, $point) {
        $obj = $this::find($uid);

        $obj->point = $obj->point - $point;
        $obj->save();
    }
    
    /**
     * 修改头像
     * 
     * **/
    public function usersedit($id, $data = null) {
        if (empty($data))
            return false;

        $status = $this->where($this->primaryKey, '=', $id)->update($data);


        if ($status) {
            return array('status' => TRUE, 'msg' => '修改成功');
        } else {
            return array('status' => FALSE, 'msg' => '修改失败');
        }
    }
  
}
