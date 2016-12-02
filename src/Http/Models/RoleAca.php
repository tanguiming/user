<?php
/**
 *  角色 - 权限 关联表
 *  
 * 	@author		songmw<song_mingwei@cdv.com>
 * @date			2013-11-22
 * @version	1.0
 */
namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class RoleAca extends Model {
	
	public $table = 'role_aca';
	protected $fields = array('role_id', 'aca_id', 'condition');
	protected $_where;															// 搜索条件 array 'param' => 'name like ? and ...'  'bind' => 'array("admin", "...")'
	protected $_order;
	public $timestamps = false;
	
	/**
	 *  填充数据
	 *  
	 *  @param array 传递进来一个 获取的数组
	 *  @return array 返回一条键值对 为一条插入的数据
	 *  						  如果权限数组为空，则返回empty
	 */
	public function fullData($data)
	{
		$tmp = '';
		$rs = array();
		$roleId = empty($data['role_id']) ? null : intval($data['role_id']) ;
		
		// 判断条件数组
		if(!empty($data['condition']) && is_array($data['condition']))
		{
			$condition = $data['condition'];
		}
		
		// 生成权限的数组
		if(!empty($data['aca']))
		{
			$tmp = trim($data['aca'], '|');
			$acas = explode('|', $tmp);
			
			foreach($acas as $k => $aca)
			{
				$rs[$k]['role_id'] = $roleId;
				$rs[$k]['condition'] = 0;
				
				if(isset($condition))
				{
					// 如果权限值 在 条件数组里，则 该权限的条件 为开启
					if(in_array($aca, $condition))
					{
						$rs[$k]['condition'] = 1;
					}
				}
				$rs[$k]['aca_id'] = $aca;
			}
		}
		
 		return $rs;
	}
	
	/**
	 *  检查数据
	 *  
	 *  @param array / primaryKey $data 		验证的数组
	 *  @param string 		$type  						验证的类型  
	 *  @return boolean $info 							返回的状态 status = boolean, msg = '提示信息'
	 */
	public function check($data, $type = 'add')
	{
		$info = array();
		
		switch($type)
		{
			case 'add' :
				$info = $this->_checkRoleAcaAdd($data);
				break;
			case 'edit' :
				$info = $this->_checkRoleAcaEdit($data);
				break;
			case 'delete' :
				$info = $this->_checkRoleAcaDelete($data);
				break;
		}
		
		return $info;
	}
	
	/**
	 * 	添加 - 验证数组
	 */
	private function _checkRoleAcaAdd($data)
	{
		if(empty($data) || !is_array($data))
		{
			return array('status'=>false, 'msg'=>'权限为空');
		}
		
		foreach($data as $roleAca)
		{
			if(empty($roleAca['role_id']))
			{
				return array('status'=>false, 'msg'=>'角色id为空');
			}
			
			if(empty($roleAca['aca_id']))
			{
				return array('status'=>false, 'msg'=>'权限id为空');
			}
		}
		
		return array('status'=>true, 'msg'=>'验证通过');
	}
	
	/**
	 * 	编辑 - 验证数组
	 */
	private function _checkRoleAcaEdit($data)
	{
		if(empty($data) || !is_array($data))
		{
			return array('status'=>false, 'msg'=>'权限为空');
		}
		
		foreach($data as $roleAca)
		{
			if(empty($roleAca['role_id']))
			{
				return array('status'=>false, 'msg'=>'角色id为空');
			}
			
			if(empty($roleAca['aca_id']))
			{
				return array('status'=>false, 'msg'=>'权限id为空');
			}
		}
		
		return array('status'=>true, 'msg'=>'验证通过');
	}
	
	/**
	 *  添加中间关联数据
	 */
	public function add($data = null)
	{
		if(empty($data))
			return false;
			
		$rs = $this->insert($data);
		
		if($rs)
		{
			return array('status'=>true, 'msg'=>'创建成功');
		}
		else
		{
			return array('status'=>false, 'msg'=>'创建失败');
		}
	}
	
	/**
	 * 通过role_id 删除对应的权限
	 * 
	 * @param int $role_id
	 */
	public function deleteAcaByRole($role_id)
	{
		if(empty($role_id))
			return false;
			
		return $this->where('role_id', '=', $role_id)->delete();
	}
	
	/**
	 * 	获取信息
	 */
	public function getList()
	{	
		
		$obj = $this;
		// 注册搜索条件
		if(!empty($this->_where))
		{
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
		}
		
		$rs = $obj->get();
		return empty($rs) ? false : $rs->toArray();
	}
	
	
	 /**
     *  设置条件
     *  
     *  @param array $where 键值对， 键为 字段和条件   值为 值
     *  @param string $type   "and" "or"
     *  @return $this
     */
    public function setWhere($where = null, $type = 'and') {
        if (empty($where) || !is_array($where)) {
            return $this;
        }
        $data = array();
        $searchString = '';
        foreach ($where as $search => $value) {

            $isIn = strstr($search, 'in');
            if ($search == 'last_login >=') {
                $searchString .= ' ' . trim($search) . ' ? ' . $type;
                $data['bind'][] = trim($value);
            } else {
                if ($isIn) {
                    $searchString .= ' ' . trim($search) . " ($value) " . $type;
                } else {
                    $searchString .= ' ' . trim($search) . ' ? ' . $type;
                    $data['bind'][] = trim($value);
                }
            }
        }
		
        $data['param'] = substr_replace(trim($searchString, ' '), '', -4); // 去掉最后的 " and"
        $this->_where = $data;
        //print_r($this->_where);exit;
		return $this;
    }

    
}