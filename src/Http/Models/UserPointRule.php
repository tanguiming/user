<?php

/**
 * UserPointRule  用户赚币模版
 * 
 * @author		kxl
 * @date		2015-2-12
 * @version		1.0
 */
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;

class UserPointRule extends Model {

    protected $table = 'user_point_rule';
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

    public function add($data)
    {
        if ($this->insert($data)) {

            return array("status" => true, "msg" => "保存成功!");
        } else {

            return array("status" => true, "msg" => "保存失败!");
        }
    }

    /**
     * 
     * @param type $data
     * @return string
     */
    public function edit($id, $data)
    {

        if ($this->where("id", $id)->update($data)) {

            return array("status" => true, "msg" => "保存成功!");
        } else {

            return array("status" => true, "msg" => "保存失败!");
        }
    }

    public function del($ids)
    {


        if ($this::destroy($ids)) {

            return array('status' => true, 'msg' => "删除成功!");
        } else {

            return array('status' => true, 'msg' => "删除失败!");
        }
    }

}
