<?php

/**
 * role Model 层
 *  
 * 	@author		songmw<song_mingwei@cdv.com>
 * @date			2013-11-18
 * @version	1.0
 */

namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Weitac\User\Http\Models\UserBaseModel as UserBaseModel;
 
class Role extends UserBaseModel {

    protected $table = 'role';
    protected $primaryKey = 'role_id';
    protected $fields = array('name', 'desc', 'system');
    protected $_where;               // 搜索条件 array 'param' => 'name like ? and ...'  'bind' => 'array("admin", "...")'
    protected $_order = 'role_id asc';         // 默认排序条件

    /**
     * laravel 框架规定设置
     * 
     *  多对多 关联设置
     */

    public function acas() {
        return $this->belongsToMany('Aca', 'role_aca', 'role_id', 'aca_id');
    }

    public function users() {
        return $this->belongsToMany('User', 'user_role', 'role_id', 'user_id');
    }

    /**
     *  获取某用户的所有角色
     *  
     *  @param int $user_id
     */
    public function getRoleByUser($user_id) {
        if (empty($user_id))
            return false;

        $rs = $this->leftJoin('user_role', 'role.role_id', '=', 'user_role.role_id')
                ->where('user_role.user_id', '=', $user_id)
                ->get();

        return empty($rs) ? false : $rs->toArray();
    }

    /**
     *  检查数据
     *  
     *  @param array / primaryKey $data 		验证的数组
     *  @param string 		$type  						验证的类型  
     *  @return boolean $info 							返回的状态 status = boolean, msg = '提示信息'
     */
    public function check($data, $type = 'add') {
        $info = array();

        switch ($type) {
            case 'add' :
                $info = $this->_checkRoleAdd($data);
                break;
            case 'edit' :
                $info = $this->_checkRoleEdit($data);
                break;
            case 'delete' :
                $info = $this->_checkRoleDelete($data);
                break;
        }

        return $info;
    }

    /**
     *  填充数据
     *  
     *  @param array 传递进来一个 获取的数组
     *  @return array 返回本表字段的填充后的数组
     */
    public function fullData($data) {
        $rs = array(
            'name' => isset($data['name']) ? $data['name'] : '',
            'desc' => isset($data['desc']) ? $data['desc'] : '',
            'system' => isset($data['system']) ? $data['system'] : 0,
        );

        // 如果主键不为空，则包含主键
        if (!empty($data[$this->primaryKey])) {
            $rs[$this->primaryKey] = $data[$this->primaryKey];
        }

        return $rs;
    }

    /**
     * 	添加 - 验证数组
     */
    private function _checkRoleAdd($data) {
        if (empty($data['name'])) {
            return array('status' => false, 'msg' => '角色名不允许为空');
        }

        if ($rs = $this->findByName($data['name'])) {
            return array('status' => false, 'msg' => '角色已经存在');
        }

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     * 	编辑 - 验证数组
     */
    private function _checkRoleEdit($data) {
        if (empty($data[$this->primaryKey])) {
            return array('status' => false, 'msg' => '主键为空');
        }

        $rs = $this->getShow($data[$this->primaryKey]);

        if ($rs['system'] == 1) {
            return array('status' => false, 'msg' => '不允许编辑系统内置角色');
        }

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     *  删除 - 验证 是否为系统内置
     */
    private function _checkRoleDelete($primaryKey) {
        $rs = $this->getShow($primaryKey);

        if ($rs == false) {
            return array('status' => false, 'msg' => '角色不存在');
        }

        if ($rs['system'] == 1) {
            return array('status' => false, 'msg' => '不允许删除系统内置角色');
        }

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     *  获取角色信息
     *  
     *  @param int primaryKey
     *  @return array 角色信息
     */
    public function getShow($role_id = null) {
        if (empty($role_id))
            return false;
	
        $rs = $this->where('role_id', '=', $role_id)->first();
        if (!empty($rs)) {
            $roleaca= RoleAca::where('role_id',$rs->role_id)->get();
			$ac = array();
            //权限ID数组
            foreach($roleaca->toArray() as $k=>$v){
                $ac[] = $v['aca_id'];
            }
			
            if(!empty($ac)){
                $aca = Aca::whereIn('aca_id',$ac)->get()->toArray();
                //权限数组
                foreach($aca as $k=>$v){
                    $acas[$k]= $v;
                    $acas[$k]['pivot'] = array('role_id'=>$role_id,'aca_id'=>$v['aca_id']);
                }

                $rs = $rs->toArray();
                $rs['acas'] = $acas;
                return $rs;
            }else{
				$rs = $rs->toArray();
				$rs['acas'] = array();
				return $rs;
			}
            return false;
        }
        
        return false;
    }

    /**
     * 通过name查找角色
     * 
     * @param string $name 
     */
    public function findByName($name) {
        $rs = $this->where('name', '=', $name)->first();
        return empty($rs) ? false : $rs->toArray();
    }

    /**
     *  获取数组，返回给dataTables
     */
    public function fullDataForTables($data) {
        if (empty($data))
            return false;

        $rs = array();

        foreach ($data as $key => $val) {
            $rs[$key][] = $val['role_id'];
            $rs[$key][] = $val['name'];

//				if(	$val['system'] == 1)
//				{
//					$rs[$key][] = '<span class="label label-success arrowed label-large">系统内置</span>' ;
//				}				
//				else
//				{
//					$rs[$key][] = '<span class="label label-important arrowed-in label-large">后期创建</span>' ;	
//				}							
            $rs[$key][] = $this->getUserCount($val[$this->primaryKey]);
            $rs[$key][] = $this->_getButton($val[$this->primaryKey]);
        }

        return $rs;
    }

    private function getUserCount($rid) {
        return UserRole::where('role_id', $rid)->count();
    }

    /**
     *  返回 按钮html
     *  
     *  @param int $primaryKey
     *  @return html
     */
    private function _getButton($id) {
        $showUrl = URL::route('admin.role.show', array('role_id' => $id));
        $editUrl = URL::route('admin.role.edit', array('role_id' => $id));

        $html = <<<HTML
		<div class="visible-desktop">
							
			<div class="btn btn-mini btn-success" onclick="$.weitac.formShow('$showUrl');">
				<i class="icon-zoom-in bigger-120" title="查看"></i>
			</div>

			<div class="btn btn-mini btn-info" onclick="$.weitac.formShow('$editUrl');">
				<i class="icon-edit bigger-120" title="编辑"></i>
			</div>

			<div class="btn btn-mini btn-danger" onclick="del($id);">
				<i class="icon-trash bigger-120" title="删除"></i>
			</div>
	
		</div>
HTML;

        return $html;
    }

}
