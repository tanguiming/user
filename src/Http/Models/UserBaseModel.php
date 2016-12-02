<?php
namespace Weitac\User\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
/**
 *   封装了一些常用的数据库操作
 *   如果是中间表、或者是没有主键的表， 则需要重写一些函数
 *   
 *   @author	songmw<song_mingwei@cdv.com>
 *   @date		2013-11-26
 *   @version  1.0
 */

class UserBaseModel extends Model {
	
	protected $_where;
	protected $_order;
	
	// -------------------------------------------------------------------------------------  查询
	
	/**
	 * 	分页查询
	 * 如果需要带有条件，则设置 setWhere 后 查询
	 * 
	 * @param int 		$page  		当前页数
	 * @param limit		$limit			每页显示条数
	 * @return array 	
	 */
	public function getListByPage($page = 0, $limit = 10)
	{
		$obj = $this;
		
		if(!empty($this->_where))
		{
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
		}
		$rs = $obj->offset($page)->limit($limit)
							->orderByRaw($this->_order)
							->get();
							
		return empty($rs) ? false : $rs->toArray() ;
	}
	
	/**
	 * 	不带分页的查询
	 * 如果需要带有条件，则设置 setWhere 后 查询
	 * 
	 * @return array 	
	 */
	public function getList()
	{
		$obj = $this;
		
		if(!empty($this->_where))
		{
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
		}
		$rs = $obj->orderByRaw($this->_order)
						  ->get();
						  
		return empty($rs) ? false : $rs->toArray() ;
	}
	
	/**
	 *  获取 单个 信息
	 *  支持 传入 主键 或者 setWhere后 再获取数据 
	 *  
	 *  @param int primaryKey
	 *  @return array 角色信息
	 */
	public function getShow($primaryKey = null)
	{
		if(!empty($primaryKey))
		{
			$rs = $this->where($this->primaryKey, '=', $primaryKey)->first();
		}
		else
		{
			$obj = $this;
			
			// 注册搜索条件
			if(!empty($this->_where))
			{
				$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
			}
			
			$rs = $obj->first();
		}
		
		return empty($rs) ? false : $rs->toArray() ;
	}
	
	/**
	 *  获取数据总数
	 *  支持setWhere后 再获取数据 
	 */
	public function getCount()
	{
		$obj = $this;
		
		if(!empty($this->_where))
		{
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
		}
		
		return $obj->count();
	}
	
	/**
	 *	清空表数据 truncate()
	 */
	public function clear()
	{
		$this->truncate();
	}
	
	// -------------------------------------------------------------------------------------  设置查询条件

	/**
	 *  设置条件
	 *  
	 *  @param array $where 键值对， 键为 字段和条件   值为 值
	 *  		如果条件键为相同的，则在传递的数组键名前加 "数字@"
	 *  @param string $type   "and" "or"
	 *  @return $this
	 */
	public function setWhere($where = null, $type = 'and')
	{
		if(empty($where) || !is_array($where))
			return $this;
			
		$data = array();
		$searchString = $this->_where = '';
		
		foreach($where as $search => $value)
		{
			$searchParam = trim($search);
			$cut = stripos($searchParam, '@');
			
			// 如果键带有 "@" 则会去掉 "@" 前面的
			if( $cut > 0)
			{
				$searchString .= ' ' . substr($searchParam, $cut + 1) . ' ? ' . $type;
			}
			else 
			{
				$searchString .= ' ' . $searchParam . ' ? ' . $type;
			}
			
			$data['bind'][] = trim($value);
		}

		$cutNum = -strlen($type) - 1;
		$data['param'] = substr_replace( trim($searchString, ' '), '',  $cutNum);	// 去掉最后的 " and"
		$this->_where = $data;
		
		return $this;
	}
	
	/**
	 *  获取条件
	 */
	public function getWhere()
	{
		return $this->_where;
	}
	
	/**
	 *	设置排序
	 *
	 * @param array $order 键值对  键为字段 值为排序类型
	 * @return $this
	 */
	public function setOrder($order = null)
	{
		if(empty($order) || !is_array($order))
			return $this;
			
		$orderString = '';
		
		foreach($order as $field => $type)
		{
			$orderString .= $field . ' ' . $type . ',';
		}
		$this->_order = trim($orderString . $this->_order, ',');
		
		return $this;
	}
	
	/**
	 *  获取排序
	 */
	public function getOrder()
	{
		return $this->_order;
	}

	// -------------------------------------------------------------------------------------  增 删 改
	
	/**
	 * 	创建数据
	 *  成功  会 将 主键赋值为 自增id
	 * 
	 * @return boolean
	 */	
	public function add($data = null)
	{
		if(empty($data))
			return false;
		
		if($this->timestamps)
		{
			$data['created_at'] = date('Y-m-d H:i:s', time());
		}
		$primaryKey = $this->primaryKey;
		$this->$primaryKey = $this->insertGetId($data);		// 此方式不会加上created_at 创建事件
		
		if($this->$primaryKey > 0)
		{
			return array('status'=>true, 'msg'=>'创建成功');
		}
		else
		{
			return array('status'=>false, 'msg'=>'创建失败');
		}
	}
	
	/**
	 * 	根据主键 修改
	 * 
	 * 参数中要带有主键
	 * @return boolean
	 */
	public function edit($data = null)
	{
		if(empty($data))
			return false;
		if(empty($this->_where))
		{
			$status = $this->where($this->primaryKey, '=', $data[$this->primaryKey])->update($data);
		}
		else
		{
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
			$status = $obj->update($data);
		}
		
		if($status)
		{
			return array('status'=>true, 'msg'=>'修改成功');
		}
		else 
		{
			return array('status'=>false, 'msg'=>'修改失败');		
		}
	}
	
	/**
	 *  销毁数据
	 *  支持 传入 主键 或者 setWhere后 再销毁数据
	 *  !! 该操作 会 删除数据库记录 无法恢复
	 *  
	 *  @param int primaryKey
	 *  @return array 角色信息
	 */
	public function des($primaryKey = null)
	{
		if(!empty($primaryKey))
		{
			$rs = $this->where($this->primaryKey, '=', $primaryKey)->delete();
		}
		else
		{
			$obj = $this;
			
			// 注册搜索条件
			if(!empty($this->_where))
			{
				$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
			}
			
			$rs = $obj->delete();
		}
		
		if($rs)
		{
			return array('status'=>true, 'msg'=>'删除成功');
		}
		else
		{
			return array('status'=>false, 'msg'=>'删除失败');		
		}
	}
	
	

}