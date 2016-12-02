<?php

/**
 * 红包
 * 
 * @author		hwx
 * @date		2015-10-21
 * @version		2.0
 */

namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class User_DeparTment extends Model{

	protected $table = 'user_bumen';
    protected $primaryKey = 'id';
    protected $_where;
	protected $fields = array('');
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

        $id = $data['id'];
        unset($data['id']);
		
        if ($this::where('id', '=', $id)->update($data)) {
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
		$is_ok = $this->where('parentid',$id)->count();

        if($is_ok){
            return array('status' => FALSE, 'msg' => '有子部门，删除失败!');
        }else{
			if ($user->delete()) {
				return array('status' => TRUE, 'msg' => '删除成功!');
			} else {
				return array('status' => FALSE, 'msg' => '删除失败!');
			}
		}
    }
	
	//遍历栏目
	 public function getShowCategory($id) {
        $OA_DeparTment = OA_DeparTment::get()->toArray();
        
        $data = array();
        $datas = array();
        $this->getAll($data, $OA_DeparTment);
		// dd($OA_WenTiFenLei);  
        foreach ($data as $item) {
            $datas[$item['id']] = $item['name'];
        }

        return $datas;
    }
	
	protected function getAll(&$data, $OA_DeparTment, $parentid = 0, $separate = '') {
        
        foreach ($OA_DeparTment as $k => $v) {

            if ($v['parentid'] == $parentid) {
				$v['name'] = $separate . $v['name'];
				$data[] = $v;
				$this->getAll($data, $OA_DeparTment, $v['id'], $separate . "&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        }
    }
	
}
