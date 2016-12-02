<?php

/**
 * 积分等级
 * 
 * @author		wpz
 * @date		2015-09-09
 * @version		1.0
 */
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Weitac\User\Http\Models\UserGrade as UserGrade;


class UserIntegralSet extends Model {

    protected $table = 'user_integral_set';
    protected $primaryKey = 'coinid';
    protected $_where;
    protected $_order = 'coinid desc';
    public $timestamps = false;

    /**
     *  设置条件
     *  
     *  @param array $where 键值对， 键为 字段和条件   值为 值
     *  @param string $type   "and" "or"
     *  @return $this
     */
    public function setWhere($where = null, $type = 'and') {
        if (empty($where) || !is_array($where))
            return $this;

        $data = array();
        $searchString = '';

        foreach ($where as $search => $value) {

            $isIn = strstr($search, 'in');

            if ($isIn) {
                $searchString .= ' ' . trim($search) . " ($value) " . $type;
            } else {
                $searchString .= ' ' . trim($search) . ' ? ' . $type;
                $data['bind'][] = trim($value);
            }
        }

        $data['param'] = substr_replace(trim($searchString, ' '), '', -4); // 去掉最后的 " and"

        if (!empty($data)) {
            $obj = $this->whereRaw($data['param'], $data['bind']);
        }

        return $obj;
    }

    /**
     * 
     * @param type $date
     * @return type
     */
    public function check($date) {

        return array('status' => TRUE, 'msg' => '表单验证通过');
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function add($data) {

        $this->attributes = $data;
        if ($this->save()) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function edit($data) {

        $id = $data['coinid'];
        unset($data['coinid']);

        if ($this::where('coinid', '=', $id)->update($data)) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }

    /**
     * 
     * @param type $id
     */
    public function del($id) {
        $user = UserGrade::find($id);
        if ($user->delete()) {
            return array('status' => TRUE, 'msg' => '删除成功!');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败!');
        }
    }

}
