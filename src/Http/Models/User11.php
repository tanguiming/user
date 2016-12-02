<?php

/**
 *  用户 Model 层
 *  
 * 	@author		songmw<song_mingwei@cdv.com>
 * @date			2013-11-18
 * @version	1.0
 */
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends UserBaseModel implements UserInterface, RemindableInterface {

    protected $table = 'user';      // 表名
    protected $hidden = array('password');
    protected $primaryKey = 'user_id';  // 主键
    protected $fields = array('name', 'desc', 'system');
    protected $_where;               // 搜索条件 array 'param' => 'name like ? and ...'  'bind' => 'array("admin", "...")'
    protected $_order = 'user_id desc';         // 默认排序条件
    public $remember_token;

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * laravel 框架规定设置
     * 
     *  多对多 关联设置
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'user_role', 'user_id', 'role_id');
    }

    public function detail()
    {
        return $this->hasOne('UserDetail', 'user_id');
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getReminderEmail()
    {
        return $this->email;
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

        switch ($type) {
            case 'add' :
                $info = $this->_checkUserAdd($data);
                break;
            case 'edit' :
                $info = $this->_checkUserEdit($data);
                break;
            case 'delete' :
                $info = $this->_checkUserDelete($data);
                break;
            case 'pwd' :
                $info = $this->_checkUserPwd($data);
                break;
        }

        return $info;
    }

    /**
     * 	用户登录验证
     * 
     * @param string username
     * @param string password
     * @param boolean remember
     * @return boolean 
     */
    public function checkUserLogin($username, $password, $remember = false)
    {
        $user = array(
            'username' => $username,
            'password' => $password
        );
        $eve['username'] = $username;
        $eve['status'] = 0;

        // 用户 密码 验证通过
        if (Auth::validate($user, $remember)) {
            $user = $this->findByUsername($username);

            // 用户为管理员，将用户的角色 和 权限 存放到SESSION中
            if ($user->system == 1 && $user->status == 1) {
                // 将该用户的信息、权限、角色 存到 session中
                $this->_userHandle($user);
                Auth::login($user); // 执行登录
                $eve['status'] = 1;  // 登录成功

                $info = array('status' => true, 'msg' => '登录成功');
            }
            // 用户被禁用
            else if ($user->status < 1) {
                $info = array('status' => false, 'msg' => '抱歉、您的帐号已被禁用');
            }
            // 用户不是管理员
            else if ($user->system != 1) {
                $info = array('status' => false, 'msg' => '抱歉、您不是管理员');
            }
        } else {
            $info = array('status' => false, 'msg' => '用户名或密码错误');
        }

        // 调用用户登录事件
        Event::fire('user.login', $eve);

        return $info;
    }

    /**
     * 	通过uid 登录
     * 	return boolean
     */
    public function loginUsingId($primaryKey)
    {
        if (empty($primaryKey))
            return false;

        $user = $this->findById($primaryKey);

        if (empty($user))
            return false;

        $this->_userHandle($user);
        Auth::loginUsingId($primaryKey);

        return true;
    }

    /**
     *  处理 组织 用户session信息
     */
    private function _userHandle($user)
    {
        $data = $acas = $role = $detail = $condition = array();

        // 存放用户信息
        $data = $user->toArray();

        // 该用户具有的角色
        $roles = $user->roles;
        if (!empty($roles)) {
            $role = $roles->toArray();
        }

        // 该用户的附属信息
        $detail = $user->detail;
        if (!empty($detail)) {
            $detail = $detail->toArray();
        }

        $data['detail'] = $detail;
        $data['role'] = $role;
        $role_ids = array_fetch($role, 'role_id');
        $token = array();
        // 获取该用户所有角色所拥有的权限
        if (!empty($role_ids)) {
            $obj = new Role;
            $objRoleAca = new RoleAca;
            $i = 0;
            foreach ($role_ids as $role_id) {
                $role = $obj->getShow($role_id);
                $acas[$role_id] = array_fetch($role['acas'], 'action');

                $where = array('role_id =' => $role_id);
                $objRoleAca->setWhere($where);
                $condition[$role_id] = array_fetch($objRoleAca->getList(), 'condition');

                $tokens = WxUser::where('groupid', $role_id)->select('id', 'token')->first();
                if ($tokens) {

                    $token[$i] = $tokens->toArray();
                    $token[$i]['flag'] = 0;
                    if ($i == 0) {
                        $token[$i]['flag'] = 1;
                    }
                    $i++;
                }
            }

            // 创建 以 动作为键 条件为值 的  数组
            foreach ($acas as $key => $val) {
                $condition[$key] = array_combine($val, $condition[$key]);
            }

            // 合并去重
            $acas = array_unique(array_flatten($acas));
        }
        $data['aca'] = $acas;
        $data['condition'] = $condition;

        $data['token'] = $token;
        // 生成一个 权限对应的条件数组

        Session::put('admin.user', $data);
        return true;
    }

    /**
     * 	验证用户提交的信息
     * @param array $users
     * @return boolean true
     * 				  				  false array 错误代码 错误信息
     */
    private function _checkUserAdd($users)
    {
        if (empty($users['username'])) {
            return array('status' => false, 'msg' => '用户名不许为空');
        }

        if (empty($users['password'])) {
            return array('status' => false, 'msg' => '密码不许为空');
        }

        $result = $this->findByUsername($users['username']);

        if (!empty($result)) {
            return array('status' => false, 'msg' => '用户名重复');
        }

        $result = $this->findByEmail($users['email']);
        if (!empty($result)) {
            return array('status' => false, 'msg' => '用户邮箱重复');
        }

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     * 	验证用户修改的信息
     */
    private function _checkUserEdit($users)
    {
        if (isset($users['old_email'])) {
            if ($users['email'] != $users['old_email']) {
                $result = $this->findByEmail($users['email']);

                if (!empty($result)) {
                    return array('status' => false, 'msg' => '用户邮箱重复');
                }
            }
        }
        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     *  删除 - 验证 是否为系统内置
     */
    private function _checkUserDelete($primaryKey)
    {
        $rs = $this->getShow($primaryKey);

        if ($rs == false) {
            return array('status' => false, 'msg' => '用户不存在');
        }

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     * 	修改密码 验证
     */
    private function _checkUserPwd($data)
    {
        if (empty($data[$this->primaryKey]))
            return array('status' => false, 'msg' => 'id不存在');

        if (empty($data['password']))
            return array('status' => false, 'msg' => '密码不允许为空');

        return array('status' => true, 'msg' => '验证通过');
    }

    /**
     *  获取角色信息
     *  
     *  @param int primaryKey
     *  @return array 角色信息
     */
    public function getShow($primaryKey = null)
    {
        if (empty($primaryKey))
            return false;

        $rs = $this->where($this->primaryKey, '=', $primaryKey)->first();

        if (!empty($rs)) {
            $roles = $rs->roles;
            if (!empty($roles))
                $roles = $roles->toArray();

            $detail = $rs->detail;
            if (!empty($detail)) {
                $detail = $detail->toArray();
            }

            $rs = $rs->toArray();
            $rs['roles'] = $roles;
            $rs['detail'] = $detail;
            return $rs;
        }

        return false;
    }

    /**
     *  填充数据
     *  
     *  @param array 传递进来一个 获取的数组
     *  @return array 返回本表字段的填充后的数组
     */
    public function fullData($data)
    {
        $rs = array(
            'username' => isset($data['username']) ? $data['username'] : null,
            'email' => isset($data['email']) ? $data['email'] : null,
            'last_login' => isset($data['last_login']) ? $data['last_login'] : date('Y-m-d H:i:s', time()),
            'last_ip' => isset($data['last_ip']) ? $data['last_ip'] : $_SERVER['REMOTE_ADDR'],
            'login_times' => isset($data['login_times']) ? $data['login_times'] : 1,
            'system' => isset($data['system']) ? $data['system'] : 0,
            'status' => isset($data['status']) ? $data['status'] : 0,
            'token' => isset($data['token']) ? $data['token'] : NULL,
        );

        // 如果主键不为空，则包含主键
        if (!empty($data[$this->primaryKey])) {
            $rs[$this->primaryKey] = $data[$this->primaryKey];
        }

        // 如果含有密码、则密码进行 hash::make 加密
        if (!empty($data['password'])) {
            $rs['password'] = Hash::make($data['password']);
        }

        // 如果含有old_email
        if (!empty($data['old_email'])) {
            $rs['old_email'] = $data['old_email'];
        }

        return $rs;
    }

    /**
     *  软删除用户
     */
    public function del($primaryKey)
    {
        $status = $this->where($this->primaryKey, '=', $primaryKey)->update(array('status' => -1));

        if (!$status) {
            return array('status' => false, 'msg' => '禁用失败');
        }

        return array('status' => true, 'msg' => '禁用成功，您可以通过编辑状态来恢复用户');
    }

    /**
     * 	修改 用户密码
     */
    public function changePassword($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->edit($data);
    }

    /**
     * 	用户注销
     */
    public function logout()
    {
        $user = Session::get('admin.user');
        Auth::logout();
        Session::forget('admin.user');

        // 调用退出事件
        Event::fire('user.logout', $user);

        return true;
    }

    /**
     * 
     * 通过用户名查找信息
     * @param string $username
     */
    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * 
     * 通过用id查找信息
     * @param string $username
     */
    public function findById($primaryKey)
    {
        return $this->where($this->primaryKey, $primaryKey)->first();
    }

    /**
     * 
     * 通过邮箱查找信息
     * @param string $email
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     *  获取数组，返回给dataTables
     */
    public function fullDataForTables($data)
    {
        if (empty($data))
            return false;

        $rs = array();
        $role = new Role;

        foreach ($data as $key => $val) {
            $rs[$key][] = '<label>
											<input type="checkbox" class="ace" name="check[]" value="' . $val['user_id'] . '"/>
											<span class="lbl"></span>
										</label>';
            $rs[$key][] = $val['user_id'];
            $rs[$key][] = $val['username'];
            $rs[$key][] = $this->_getUser($val['user_id']);

            if ($val['status'] == 1) {
                $rs[$key][] = '<span class="label label-success arrowed label-large">正常</span>';
            } else if ($val['status'] == 0) {
                $rs[$key][] = '<span class="label label-info arrowed-in-right arrowed label-large">待审</span>';
            } else {
                $rs[$key][] = '<span class="label label-important arrowed-in label-large">禁用</span>';
            }

            // 获取该用户所拥有的角色名
            $roles = $role->getRoleByUser($val['user_id']);
            $roleNameString = '注册用户';

            if (!empty($roles)) {
                $rolesName = array_fetch($roles, 'name');
                $roleNameString = implode($rolesName, ',');
            }

            $rs[$key][] = $roleNameString;
            $rs[$key][] = $val['created_at'];
            $rs[$key][] = $val['last_login'];
            $rs[$key][] = $this->_getButton($val[$this->primaryKey]);
        }

        return $rs;
    }

    private function _getUser($id)
    {
        $detail = UserDetail::find($id);
        if ($detail) {
            return $detail->name;
        } else {
            return "";
        }
    }

    /**
     *  返回 按钮html
     *  
     *  @param int $primaryKey
     *  @return html
     */
    private function _getButton($id)
    {
        $showUrl = URL::route('admin.user.show', array('user_id' => $id, 'in_ajax' => 1));
        $editUrl = URL::route('admin.user.edit', array('user_id' => $id));
        $pwdUrl = URL::route('admin.user.pwd', array('user_id' => $id));

        $html = <<<HTML
		<div class="visible-desktop">
							
			<div class="btn btn-mini btn-success" onclick="ajax_get_html('$showUrl');">
				<i class="icon-zoom-in bigger-120" title="查看"></i>
			</div>

			<div class="btn btn-mini btn-info" onclick="$.weitac.formShow('$editUrl');">
				<i class="icon-edit bigger-120" title="编辑"></i>
			</div>

			<div class="btn btn-mini btn-warning" onclick="$.weitac.formShow('$pwdUrl');">
				<i class="icon-unlock bigger-120" title="修改密码"></i>
			</div>

			<div class="btn btn-mini btn-danger" onclick="del($id);">
				<i class="icon-trash bigger-120" title="删除"></i>
			</div>
	
		</div>
HTML;

        return $html;
    }

}
