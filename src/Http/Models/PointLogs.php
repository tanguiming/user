<?php
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * 水印管理
 * 
 * @author	   wudeyi
 * @date		2014-11-17 14:19:18
 * @version		1.0
 */
 
class PointLogs extends Model {

    protected $table = 'user_point_detail';
    protected $primaryKey = 'id';
    protected $_where;
	protected $fields = array('id', 'user_id', 'cpoint','jpoint', 'ip', 'datetime', 'operation' );
    protected $_order = 'id desc';
	
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
            if(isset($data['bind'])){
                $obj = $this->whereRaw($data['param'], $data['bind']);
            }else{
                $obj = $this->whereRaw($data['param']);
            }
        }

        return $obj;
    }
}
