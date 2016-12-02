<?php

/**
 * 	用户管理
 * 
 * @author		songmw<song_mingwei@cdv.com>
 * @date			2013-11-14
 * @version	1.0
 */
class UserController extends AdminController {

    public function __construct()
    {
        parent::__construct();
        // post 进行csrf验证
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    // 过滤器，必须加的函数
    // @parma1 , @param2 为 框架规定
    // @param3 为 传递过来的参数
    public function filter($route, $request, $type)
    {
        if (Session::get('admin.user.username') != 'admin') {
            switch ($type) {
                case 'index' :

                    if (!Security::check('user.index')) {
                        echo '<script>alert("您没有这个权限");</script>';
                        die;
                    }

                    break;
                case 'show' :

                    if (!Security::check('user.show')) {
                        echo '<script>alert("您没有这个权限");$.weitac.formHide();</script>';
                        die;
                    }

                    break;
                case 'add' :

                    if (!Security::check('user.add')) {
                        echo '<script>alert("您没有这个权限");$.weitac.formHide();</script>';
                        die;
                    }

                    break;
                case 'edit' :

                    if (!Security::check('user.edit')) {
                        echo '<script>alert("您没有这个权限");$.weitac.formHide();</script>';
                        die;
                    }

                    break;
                case 'delete' :

                    if (!Security::check('user.delete')) {
                        return Response::json(array('status' => false, 'msg' => '您没有这个权限'));
                        die;
                    }

                    break;
                case 'destroy' :

                    if (!Security::check('user.destroy')) {
                        return Response::json(array('status' => false, 'msg' => '您没有这个权限'));
                        die;
                    }

                    break;
                case 'pwd' :
                    // 修改密码
                    if (!Security::check('user.pwd')) {
                        echo '<script>alert("您没有这个权限");$.weitac.formHide();</script>';
                        die;
                    }

                    break;
            }
        }
    }

    /**
     * 	用户的家
     * 	进入后台的显示界面？
     */
    public function home()
    {
        return View::make('user::user.home');
    }

    /**
     * 显示用户列表
     */
    public function index()
    {
        return View::make('user::user.index');
    }

    /**
     *  ajax数据源
     *  
     *  为dataTables提供JSON数据源
     */
    public function ajaxIndex()
    {
        $order = $where = $data = array();  // 创建 排序 和 条件数组
        $input = Input::all();
        $obj = new User;

        // 排序方式 string asc / desc
        $sorts = array(1 => 'user_id', 2 => 'username', 3 => 'email', 4 => 'status', 6 => 'created_at', 7 => 'last_login');   // 要排序的字段
        $orderType = $input['sSortDir_0'];      // 排序类型  string "asc / desc"
        $orderColumn = $sorts[$input['iSortCol_0']];  // 传过来的列 ID 从0开始
        $order = array(
            $orderColumn => $orderType
        );

        // 获取搜索条件
        if (Input::has('username'))
            $where['username like'] = "%" . Input::get('username') . "%";

        if (Input::has('email'))
            $where['email like'] = "%" . Input::get('email') . "%";

        if (Input::has('created_at'))
            $where['created_at >='] = Input::get('created_at');

        if (Input::has('status'))
            $where['status ='] = Input::get('status');


        $users = Session::get('admin.user.token');
        if (count($users)) {
            $token = "";
            foreach ($users as $v) {
                $token .= "'" . $v['token'] . "',";
            }
            //dd($token);
             //  $where['token in'] = rtrim($token, ',');
        }

        $where['system ='] = 0;

        // 根据分页获取数据 页数，每页显示条数
        $user = $obj->setWhere($where)
                ->setOrder($order)
                ->getListByPage($input['iDisplayStart'], $input['iDisplayLength']);
        $total = $obj->getCount();                   // 获取总条数
        $data['aaData'] = array();                   // 返回的数据
        $data['sEcho'] = $input['sEcho'];                // 返回传递的参数即可
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $total;   // 总数据条数

        if (!empty($user)) {
            // 组织数组格式为DataTables服务
            $data['aaData'] = $obj->fullDataForTables($user);
        }

        return Response::json($data);
    }

    /**
     * 
     * 显示用户信息
     */
    public function show()
    {
        $user_id = Input::get('user_id', null);
        $obj = new User;
        $user = $obj->getShow($user_id);

        // 用户具有的角色数组
        if (!empty($user['roles'])) {
            $user['roles'] = array_fetch($user['roles'], 'role_id');
        }

        // 所有的角色数组
        $obj = new Role;
        $role = $obj->getList();
        $user['allRole'] = $role;

        // dd($user);
        Event::fire('log.action', array('user.show', $user_id));
        return View::make('user::user.show', $user);
    }

    /**
     *  添加用户
     */
    public function add()
    {
        $obj = new Role;
        $role = $obj->getList();
        $data['roles'] = $role;

        return View::make('user::user.add', $data);
    }

    /**
     * 	执行添加
     */
    public function insert()
    {
        if (Request::ajax()) {
            $data = Input::all();
            $obj = new User;

            $user = $obj->fullData($data);
            // 验证提交的表单信息
            $info = $obj->check($user);

            if ($info['status'] == true) {
                $info = $obj->add($user);

                if ($info['status'] == true) {
                    $data['user_id'] = $obj->user_id;

                    $obj = new UserDetail;
                    $userDetail = $obj->fullData($data);
                    $inf = $obj->check($userDetail);

                    if ($inf['status'] == true) {
                        $obj->add($userDetail);
                    }

                    $obj = new UserRole;
                    $userRole = $obj->fullData($data);
                    $inf = $obj->check($userRole);

                    if ($inf['status'] == true) {
                        $obj->add($userRole);
                    }
                }

                Event::fire('log.action', array('user.add', $data['user_id']));
            }

            return Response::json($info);
        }
    }

    /**
     *  编辑用户
     */
    public function edit()
    {
        $user_id = Input::get('user_id', null);
        $obj = new User;
        $user = $obj->getShow($user_id);

        // 用户具有的角色数组
        if (!empty($user['roles'])) {
            $user['roles'] = array_fetch($user['roles'], 'role_id');
        }

        // 所有的角色数组
        $obj = new Role;
        $role = $obj->getList();
        $user['allRole'] = $role;

        return View::make('user::user.edit', $user);
    }

    /**
     *  执行编辑用户
     */
    public function update()
    {
        if (Request::ajax()) {
            $data = Input::all();
            $obj = new User;

            $user = $obj->fullData($data);
            // 验证提交的表单信息
            $info = $obj->check($user, 'edit');

            if ($info['status'] == true) {
                unset($user['old_email']);
                $info = $obj->edit($user);

                if ($info['status'] == true) {
                    $obj = new UserDetail;
                    $userDetail = $obj->fullData($data);
                    $inf = $obj->check($userDetail);

                    if ($inf['status'] == true) {
                        $obj->edit($userDetail);
                    }

                    $obj = new UserRole;
                    $obj->deleteRoleByUser($user['user_id']);

                    $userRole = $obj->fullData($data);
                    $inf = $obj->check($userRole, 'edit');

                    if ($inf['status'] == true) {
                        $obj->add($userRole);
                    }
                }

                Event::fire('log.action', array('user.edit', $data['user_id']));
            }

            return Response::json($info);
        }
    }

    /**
     * 	修改密码
     */
    public function pwd()
    {
        return View::make('user::user.pwd', array('user_id' => Input::get('user_id')));
    }

    /**
     * 	执行修改密码
     */
    public function doPwd()
    {
        if (Request::ajax()) {
            $data['user_id'] = Input::get('user_id');
            $data['password'] = Input::get('password');
            $obj = new User;

            $info = $obj->check($data, 'pwd');

            if ($info['status'] == true) {
                $info = $obj->changePassword($data);

                Event::fire('log.action', array('user.pwd', $data['user_id']));
            }

            return Response::json($info);
        }
    }

    /**
     *  软删除用户
     */
    public function delete()
    {
        if (Request::ajax()) {
            $user_id = Input::get('user_id');
            $obj = new User;

            Event::fire('log.action', array('user.delete', $user_id));
            return Response::json($obj->del($user_id));
        }
    }

    /**
     *  彻底删除用户
     */
    public function destroy()
    {
        if (Request::ajax()) {
            $user_id = Input::get('user_id');
            $obj = new User;

            Event::fire('log.action', array('user.destroy', $user_id));
            return Response::json($obj->des($user_id));
        }
    }

}
