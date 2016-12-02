<?php

namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

/**
 * 	用户登录日管理
 * 
 * @author			hwx
 * @date			2015-11-05
 * @version	1.0
 */
//$journal = DB::connection('mongodb')->collection('signjournal')->insert($data);

class UserLoginLog extends Eloquent{
    protected $connection = 'mongodb';
	protected $collection = 'signjournal';
    protected $table = "user_login_log";


    /**
     * 保存添加数据
     * @param type $data
     * @return type
     */
    public function add($data)
    {
       
        $this->attributes = $data;
        if ($this->save()) {
            return array('status' => TRUE, 'msg' => '添加成功');
        } else {
            return array('status' => FALSE, 'msg' => '添加失败');
        }
    }


  

     /**
     *  设置条件
     *  
     *  @param array $where 键值对， 键为 字段和条件   值为 值
     *  @param string $type   "and" "or"
     *  @return $this
     */
    public function setWhere($where = null, $type = 'and')
    {

        if (empty($where) || !is_array($where))
        //echo '<pre>';
            return $this;

        $data = array();
        $searchString = '';

        foreach ($where as $search => $value) {

            $isIn = strstr($search, 'in');
            $isNull = strstr($search, 'IS');

            if ($isIn) {
                $searchString .= ' ' . trim($search) . " ($value) " . $type;
            } elseif ($isNull) {
                $searchString .= ' ' . trim($search) . " $value " . $type;
            } else {
                $searchString .= ' ' . trim($search) . ' ? ' . $type;
                $data['bind'][] = trim($value);
            }
        }


        $data['param'] = substr_replace(trim($searchString, ' '), '', -4); // 去掉最后的 " and"

        if (!empty($data) && isset($data['bind'])) {
            $obj = $this->whereRaw($data['param'], $data['bind']);
        } else {
            $obj = $this->whereRaw($data['param']);
        }

        return $obj;
    }
    

    // 删除功能
    public function del($id)
    {
        if ($this->where("_id", $id)->delete()) {
            return array('status' =>true, 'msg' => '删除成功！');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败！');
        }
    }

}
