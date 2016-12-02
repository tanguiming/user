<?php

/**
 * 权限 Model 层
 * 
 * 	@author		wpz
 * @date			2015-09-24
 * @version	2.0
 */
 
namespace Core\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Core\User\Http\Models;
 
class Aca extends Model {

    protected $table = 'aca';
    protected $primaryKey = 'aca_id';
    protected $fields = array('parent', 'action', 'package', 'remark', 'status');  // 表字段
    public $timestamps = false;
    protected $_where;                // 搜索条件 array 'param' => 'name like ? and ...'  'bind' => 'array("admin", "...")'
    protected $_order = 'aca_id asc';         // 默认排序条件

    /**
     * laravel 框架规定设置
     * 
     *  多对多 关联设置
     */

    public function roles() {
        return $this->belongsToMany('Role', 'role_aca', 'aca_id', 'role_id');
    }

    /**
     *  检查数据
     *  
     *  @param array 		$data 		验证的数组
     *  @param string 		$type  	验证的类型  
     *  @return boolean $info 		返回的状态 status = boolean, msg = '提示信息'
     */
    public function check($data = null, $type = 'add') {
        $info = array();

        switch ($type) {
            case 'add' :
                $info = $this->_checkAcaAdd($data);
                break;
            case 'edit':
                $info = $this->_checkAcaEdit($data);
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
    public function fullData($data = null) {
        $rs = array(
            'parent' => isset($data['parent']) ? $data['parent'] : '',
            'action' => isset($data['action']) ? $data['action'] : '',
            'package' => isset($data['package']) ? $data['package'] : '',
            'remark' => isset($data['remark']) ? $data['remark'] : '',
            'status' => isset($data['status']) ? $data['status'] : 0,
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
    private function _checkAcaAdd($data) {
		if($data['action'] != null){
			if (stripos($data['action'], '.') == 0) {
				return array('status' => false, 'msg' => '权限格式不正确，权限格式为 controller.action');
			}
		}
        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     * 修改 - 验证数组
     */
    private function _checkAcaEdit($data) {
        if (empty($data[$this->primaryKey])) {
            return array('status' => false, 'msg' => '主键为空');
        }
		if($data['action'] != null){
			if (stripos($data['action'], '.') == 0) {
				return array('status' => false, 'msg' => '权限格式不正确，权限格式为 controller.action');
			}
		}
        return array('status' => true, 'msg' => '验证通过');
    }
	
	//执行添加
	public function adds($data) {
        $this->attributes = $data;
        if ($this->save()) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }
	//执行修改
	public function edits($data) {

        $id = $data['aca_id'];
        unset($data['aca_id']);

        if ($this::where('aca_id', '=', $id)->update($data)) {
            return array('status' => TRUE, 'msg' => '修改成功');
        } else {
            return array('status' => FALSE, 'msg' => '修改失败');
        }
    }
	//执行删除
	public function dels($id) {
        $user = $this::find($id);
		
        if ($user->delete()) {
            return array('status' => TRUE, 'msg' => '删除成功!');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败!');
        }
    }
	
    /**
     *  提供trees需要的数据格式
     * // 返回的数组格式 必须如下：
     *  array(
      'name' => 'user',
      'type' => 'folder',
      'additionalParameters' => array(
      'children' => array(
      'user_index' => array(
      'name' => '用户列表',
      'type' => 'item'
      ),
      )
      )
      );
     *  
     *  @param array $data 	权限数组
     *  @param json	   $aca 	拥有的权限json串
     *  @param json   $condition 权限对应的条件开启串
     *  @return array 符合TREE格式的数组
     */
    public function fullDataForTrees($data, $aca = null, $condition = null) {
        $parent = array();
        $package = new PackageModule;
        $moduleDesc = $package->getAllPackageModuleDesc();
        $i = 0;


        if (!empty($aca)) {
            $aca = json_decode($aca, true);
        }

        if (!empty($condition)) {
            $condition = json_decode($condition, true);
        }

        foreach ($moduleDesc as $name => $desc) {
            $parent[$i]['name'] = $desc;
            $parent[$i]['type'] = 'folder';
            $item = array();
            $item['children'] = array();

            foreach ($data as $key => $action) {
                if ($name != $action['parent'])
                    continue;

                $checked = '';
                // 判断权限条件是否开启
                if (!empty($condition)) {
                    $checked = in_array($action[$this->primaryKey], $condition) ? 'checked' : '';
                }

                // 返回的子节点，同时带有一个 条件开关按钮
                $item['children'][$action['action']]['name'] = $action['remark'] . '
						<span class="lbl"></span>';
                $item['children'][$action['action']]['type'] = 'item';
                $item['children'][$action['action']][$this->primaryKey] = $action[$this->primaryKey];


                //<input type="checkbox" class="ace ace-switch ace-switch-3" 
//									name="condition[' . $action[$this->primaryKey] . ']" value="' . $action[$this->primaryKey] . '" style="top:0px;" ' . $checked . '/>
                // 将被选中的权限 给以checked
                if (!empty($aca)) {
                    if (in_array($action[$this->primaryKey], $aca)) {
                        $item['children'][$action['action']]['selected'] = true;
                    } else {
                        $item['children'][$action['action']]['selected'] = false;
                    }
                } else {
                    $item['children'][$action['action']]['selected'] = false;
                }
            }
            $parent[$i]['additionalParameters'] = $item;
            ++$i;
        }

        return $parent;
    }

    /**
     * 	通过action查找
     */
    public function findByAction($action) {
        $rs = $this->where('action', '=', $action)->first();
        return empty($rs) ? false : $rs->toArray();
    }

    /**
     *  扫描权限后 入库
     *  Q 删除权限后，数据库的不会自动删除
     *  
     *  @param array $acas 通过扫描获取到的权限数组 多维数组
     *  @return boolean
     */
    public function scanAdd($acas) {
        $data = $error = array();

        if (!is_array($acas)) {
            return array('status' => false, 'msg' => '未扫描到权限');
        }

        // ------------------------------------- 在aca.php文件里 去除掉某 权限后，同时删除数据库里对应的 权限 
        // ------------------------------------- 此操作 会产生联级操作， 同时删除角色对应的权限
        $this->setWhere(array('status =' => 1));
        // 获取数据库里的权限数组
        $rs = $this->getList();
        // 获取包里面的数组
        $packageAca = array_flip(array_dot($acas));
        // 删除数据库存在但配置文件里不存在的 aca
        foreach ($rs as $key => $val) {
            $tmp = $val['package'] . '.' . $val['action'];

            if (!in_array($tmp, $packageAca)) {
                $this->des($val[$this->primaryKey]);
            }
        }

        // 清空权限表 涉及到联级操作，无法使用truncate
        // $this->truncate();
        // 获取现在的权限表数据
        // 添加数据库不存在但配置文件里存在的aca
        foreach ($acas as $packageName => $packages) {

            if (!is_array($packages)) {
                continue;
            }

            foreach ($packages as $module => $aca) {

                if (!is_array($aca)) {
                    continue;
                }

                foreach ($aca as $action => $remark) {
                    $data['action'] = $module . '.' . $action;
                    $data['parent'] = $module;
                    $data['package'] = $packageName;
                    $data['remark'] = $remark;
                    $data['status'] = 1;

                    // 查找 权限是否存在于数据库， 如果不存在 则添加，存在 则判断是否需要修改remark
                    $where = array(
                        'package =' => $packageName,
                        'parent =' => $module,
                        'action =' => $module . '.' . $action);
                    $this->setWhere($where);
                    $rs = $this->getShow();

                    if (empty($rs)) {
                        // 添加action
                        $info = $this->add($data);
                    } else {
                        if ($rs['remark'] != $data['remark']) {
                            $data[$this->primaryKey] = $rs[$this->primaryKey];
                            $info = $this->edit($data);
                        } else {
                            continue;
                        }
                    }

                    if ($info['status'] == false) {
                        $error[] = $this->$this->primaryKey;
                        continue;
                    }

                    // 休息一会 - -!!
                    usleep(500);
                }
            }
        }

        if (count($error) > 0) {
            return array('status' => false, 'msg' => '权限未全部添加成功，请重新再试');
        }
        return array('status' => true, 'msg' => '扫描权限完成');
    }

    /**
     * 	权限扫描初始化操作
     * 
     * 该操作会将所有角色的权限都清空！ 同时创建新的权限表
     */
    public function scanInit($acas) {
        $data = $error = array();

        if (!is_array($acas)) {
            return array('status' => false, 'msg' => '未扫描到权限');
        }

        $this->setWhere(array("$this->primaryKey >=" => 0));
        $this->des();

        foreach ($acas as $packageName => $packages) {

            if (!is_array($packages)) {
                continue;
            }

            foreach ($packages as $module => $aca) {

                if (!is_array($aca)) {
                    continue;
                }

                foreach ($aca as $action => $remark) {
                    $data['action'] = $module . '.' . $action;
                    $data['parent'] = $module;
                    $data['package'] = $packageName;
                    $data['remark'] = $remark;
                    $data['status'] = 1;

                    // 添加action
                    $info = $this->add($data);

                    if ($info['status'] == false) {
                        $error[] = $this->$this->primaryKey;
                        continue;
                    }

                    // 休息一会 - -!!
                    usleep(500);
                }
            }
        }


        if (count($error) > 0) {
            return array('status' => false, 'msg' => '权限未全部初始化成功，请重新再试');
        }
        return array('status' => true, 'msg' => '权限扫描初始化成功');
    }

}
