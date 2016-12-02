<?php
namespace Core\User\Http\Models;
use Illuminate\Database\Eloquent\Model;

class FrontUserPoint extends Model{
	protected $table = 'user_point';      // 表名
    protected $primaryKey = 'id';  // 主键
    protected $fields = array('id', 'name', 'pinyin', 'content', 'point', 'num');
	public $timestamps = false;
	
	
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
	public function del($ids)
    {
        if ($this::destroy($ids)) {
		
            return array('status' => true, 'msg' => "删除成功!");
        } else {

            return array('status' => true, 'msg' => "删除失败!");
        }
    }
	
	
}
