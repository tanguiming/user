<?php

/**
 * user_point_detail
 * 
 * @author		kxl
 * @date		2014-10-27
 * @version		1.0
 */
namespace Core\User\Http\Models;
use Illuminate\Database\Eloquent\Model;

class FrontUserpd extends Model {

    protected $table = 'user_point_detail';
    protected $primaryKey = 'id';
    protected $_where;
	protected $fillable = array('id', 'user_id', 'cpoint', 'jpoint', 'ip', 'datetime', 'operation');
    protected $_order = 'id desc';
    public $timestamps = false;

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
}
