<?php

/**
 * UserCenter
 * @object      用户中心后台方法配置
 * @author		原版lq   现版kxl
 * @date		2015-8-13
 * @version		1.0
 */
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
class UserCenter extends Model {

    protected $table = 'user_center';
    protected $primaryKey = 'id';
    protected $_where;
    protected $_order = 'id desc';
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
		/**
		*对数据的检查，如果数据不正确就返回flase
		**/
		
        return array('status' => TRUE, 'msg' => '表单验证通过');
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function add($datas) {

        if ($this->insert($datas)) {
            return array('status' => TRUE, 'msg' => '添加成功');
        } else {
            return array('status' => FALSE, 'msg' => '添加失败');
        }
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function edit($datas) {

        $id = $datas['id'];

        if ($this::where('id', '=', $id)->update($datas)) {
            return array('status' => TRUE, 'msg' => '修改成功');
        } else {
            return array('status' => FALSE, 'msg' => '修改失败');
        }
    }

    /**
     * 
     * @param type $id
     */
    public function del($id) {

        $user = $this::find($id);
		
        if ($user->delete()) {
            return array('status' => TRUE, 'msg' => '删除成功!');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败!');
        }
    }

}
