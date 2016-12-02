<?php
/**
 *  用户 - 角色 关联表
 *  
 * 	@author		songmw<song_mingwei@cdv.com>
 * @date			2013-11-24
 * @version	1.0
 */

namespace Weitac\User\Http\Models;
 
 
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Weitac\User\Http\Models\UserBaseModel as UserBaseModel;
use Illuminate\Support\Facades\Event as Event;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model; 
 
 
 
class UserRole extends UserBaseModel {
	
	public $table = 'user_role';
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
		$user_id = empty($data['user_id']) ? null : intval($data['user_id']) ;
		
		// 生成角色的数组
		if(!empty($data['role']))
		{
			$roles = $data['role'];
			
			foreach($roles as $k => $role)
			{
				$rs[$k]['user_id'] = $user_id;
				$rs[$k]['role_id'] = $role;
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
			case 'edit' :
				$info = $this->_checkUserRole($data);
				break;
			case 'delete' :
				$info = $this->_checkUserRoleDelete($data);
				break;
		}
		
		return $info;
	}
	
	/**
	 * 	添加 - 验证数组
	 */
	private function _checkUserRole($data)
	{
		if(empty($data) || !is_array($data))
		{
			return array('status'=>false, 'msg'=>'角色数组为空');
		}
		
		foreach($data as $userRole)
		{
			if(empty($userRole['user_id']))
			{
				return array('status'=>false, 'msg'=>'用户id为空');
			}
			
			if(empty($userRole['role_id']))
			{
				return array('status'=>false, 'msg'=>'角色id为空');
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
	 *  删除角色 通过 user_id
	 *  
	 *  @param int $user_id
	 */
	public function deleteRoleByUser($user_id)
	{
		if(empty($user_id))
			return false;
			
		return $this->where('user_id', '=', $user_id)->delete();
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

}