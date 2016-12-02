<?php

/**
 * 	用户管理
 * 
 * @author		songmw<song_mingwei@cdv.com>  wpz
 * @date			2013-11-14   2015-10-10
 * @version	1.0  2.0
 */

namespace Weitac\User\Http\Controllers;
use Session;
use Response;
use DB;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\User as User;
use Weitac\User\Http\Models\Role as Role;
use Weitac\User\Http\Models\UserRole as UserRole;
use Weitac\User\Http\Models\UserMenu as UserMenu;
use Illuminate\Support\Facades\Input;
use Weitac\User\Http\Models\UserAdmin as UserAdmin;
use Weitac\User\Http\Models\SectionsClass as SectionsClass;	//自由列表内容到时候改路径
use Weitac\User\Http\Models\Sections as Sections;	//自由列表内容到时候改路径
use Weitac\User\Http\Models\Categoryitem as Categoryitem;	//自由列表内容到时候改路径
//	部门 2016-9-22
use Service\Help\Models\OA_DeparTment as OA_DeparTment;
class UserAdminController extends AdminController {
	private $pagesize = 15;
	
    public function __construct() {
        parent::__construct();
        // post 进行csrf验证
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
	
    /**
     * 	用户的家
     * 	进入后台的显示界面？
     */
    public function home() {
        return View::make('user::user.home');
    }

    /**
     * 显示用户列表
     */
    public function index() {
        $user = Role::all()->toArray();
        return view('user::user.admin', array('user' => $user));
    }

    /**
     *  ajax数据源
     *  
     *  为dataTables提供JSON数据源
     */
    public function ajaxIndex() {
        $order = $where = $data = array();  // 创建 排序 和 条件数组
        $input = Input::all();
        $content = new UserAdmin;
		
        // 获取搜索条件
        if (Input::has('search')){
			$where['username like'] = "%" . Input::get('search') . "%";
		}
        /*if (Input::has('email'))
          $where['email like'] = "%" . Input::get('email') . "%";

          if (Input::has('created_at'))
          $where['created_at >='] = Input::get('created_at');


          if (Input::has('role')){
          $id = DB::table('user_role')->where('role_id',Input::get('role'))->select('user_id')->get();

          //判断传值为空则返返数值
          if(empty($id)){
          $data['aaData'] = array();
          return Response::json($data);
          }

          //如果不为空则查找所有数值
          $ro = DB::table('user_role')->where('role_id',Input::get('role'))->select('user_id')->get();
          $arr = array();
          foreach($ro as $k=>$v){
          $arr[] = $v->user_id;
          }

          foreach ($arr as $v) {
          $userid = $v['user_id'];
          if ($userid) {
          $ides .= "'$userid'" . ',';
          }
          }
          $ides = substr($ides, 0, -1);

          $where['user_id in'] = "$ides";
          } */

        $where['system = '] = 1;
        // 获取总条数
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;

        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;

        $obj = $content->setWhere($where);

        if (isset($_GET['orderby'])) {
            $order = explode('|', $_REQUEST['orderby']);
            $res = $obj->orderBy($order[0], $order[1])->paginate($pagesize)->toArray();
        } else {
            $res = $obj->paginate($pagesize)->toArray();
        }

        //循环遍历数据将数据库中的时间戳，转换成Y-m-d H:i:s的形式
        $res['data'] = $content->fullDataForTables($res['data']);
        return Response::json($res);
    }

    /**
     * 
     * 显示用户信息
     */
    public function show() {
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

        // 部门显示
        $user['bm'] = $this->_getBm($pid = 0);

        Event::fire('log.action', array('user.show', $user_id));
        return View::make('user::user.show', $user);
    }

    /**
     *  添加用户
     */
    public function add() {
        $obj = new Role;
        $role = $obj->getList();
        $data['roles'] = $role;

        $data['bm'] = $this->_getBm($pid = 0);

        return View::make('user::user.add', $data);
    }

    // //获取部门菜单
    // private function _getBm($pid, $result = array(), $span = "") {
        // $charents = UserDepartment::where('pid', '=', $pid)->orderBy('sort')->get();

        // $span .= '&nbsp&nbsp&nbsp';
        // if (count($charents)) {
            // foreach ($charents as $item) {
                // $result[] = array('id' => $item->id, 'department' => $span . '|--' . $item->department);
                // $num = UserDepartment::where('pid', '=', $item->id)->count();
                // if ($num != 0) {
                    // $result = $this->_getBm($item->id, $result, $span);
                // }
            // }
        // }

        // return $result;
    // }

    /**
     * 	执行添加
     */
    public function insert() {
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
	
	//------------------------------------发送消息开始
	public function award(){
		$id = Input::get('id');
		dd($id);
		$content = WxContent::find($id);

        $result = array(
            'id' => $id,
            'token' => $content->token,
            'openid' => $content->FromUserName
        );

        return View('weixin::wx_content/award', $result);
	}

	/**
     * 回复消息
     * @return type
     */
    public function postAward()
    {
        $text = Input::get('text');
        $token = Input::get('token');
        $openid = Input::get('openid');
        $comment_id = Input::get('id');

        //保存发送的消息
        if (!empty($text)) {
            //$openid = UserDetail::whereRaw('content_id = ? and fakeid = ?', array($content_id,$fakeid))->select('openid')->first()->toArray();
            $data['cmid'] = $comment_id;
            $data['openid'] = $openid;
            $data['says'] = $text;
            $data['token'] = $token;
            $data['mkdate'] = time();
            $aa = DB::table('wx_comment_replys')->insert($data);
        }else{
			return Response::json(array('status' => false, 'msg' => "信息为空！"));
		}
        $result = Message::weixinSend($token, $text, $openid,"text");
		
		if(!$result['errcode']){
			return Response::json(array('status' => TRUE, 'msg' => "信息已发送！"));
		}else{
			return Response::json(array('status' => false, 'msg' => $result['errmsg']));
		}
    }
	//-----------------------------------发送消息结束
	
    /**
     *  编辑用户
     */
    public function edit() {
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
        // 部门显示
        $user['bm'] = $this->_getBm($pid = 0);
        return View::make('user::user.edit', $user);
    }

    /**
     *  执行编辑用户
     */
    public function update() {
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
    public function pwd() {
        return View::make('user::user.pwd', array('user_id' => Input::get('user_id')));
    }

    /**
     * 	执行修改密码
     */
    public function doPwd() {
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
    public function delete() {
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
    public function destroy() {
		/*  if (Request::ajax()) {
            $user_id = Input::get('user_id');
            $obj = new User;

            Event::fire('log.action', array('user.destroy', $user_id));
            return Response::json($obj->des($user_id));
        } */
		
		$ids = Input::get('ids');
        $ids = substr($ids, 0, -1);
        $array = explode(",", $ids);
        $obj = new User();
        $result = $obj->des($array);

        return Response::json($result);
    }

    /**
     * 
     */
    public function setCategoryShow() {
        $user_id = Input::get('user_id');
        $result = array(
            'user_id' => $user_id,
        );

        return view('user::user.setCategory', $result);
    }

    /**
     * 
     * @return type
     */
    public function setCategoryTree() {

        $user_id = Input::get('user_id');
        //栏目权限

        $user_arr = array();
        $catgorys = Categoryitem::where('catid', 1)->select('id', 'name', 'catid', 'parentid')->get()->toArray();

        $user_temp = DB::table('user_category')->where('user_id', intval($user_id))->first();

        if ($user_temp) {
            $user = $user_temp->value;
            $user_str = rtrim($user, '|');
            $user_arr = explode("|", $user_str);
        }

        foreach ($catgorys as $k => $v) {

            $select = FALSE;
            if ($user_arr) {
                if (in_array($v['id'], $user_arr)) {
                    $select = TRUE;
                }
            }
            if (!$v['parentid']) {
                $is_ok = Categoryitem::where('parentid', $v['id'])->count();
                if ($is_ok) {
                    $rs['data'][$k] = array('name' => $v['name'], 'type' => 'folder', 'additionalParameters' => $this->getChCategory($v['id'], $user_arr));
                } else {
                    $rs['data'][$k] = array('name' => $v['name'], 'type' => 'item', 'parentid' => $v['id'], 'selected' => $select);
                }
            }
        }

        $rs['status'] = true;

        return Response::json($rs);
    }

    /**
     * 子栏目
     * @param type $catid
     * @return type
     */
    private function getChCategory($catid, $user_arr) {
        $data = array();
        $catgorys = Categoryitem::where('parentid', $catid)->select('id', 'name', 'catid', 'parentid')->get()->toArray();

        foreach ($catgorys as $k => $v) {

            $select = FALSE;
            //根据选择进行判断是否为真		
            if (in_array($v['id'], $user_arr)) {
                $select = TRUE;
            }
            $is_ok = Categoryitem::where('parentid', $v['id'])->count();
            if ($is_ok) {
                $data['children'][] = array('name' => $v['name'], 'type' => 'folder', 'additionalParameters' => $this->getChCategory($v['id'], $user_arr));
            } else {
                $data['children'][] = array('name' => $v['name'], 'type' => 'item', 'parentid' => $v['id'], 'selected' => $select);
            }
        }

        return $data;
    }

    /**
     * 更新
     * @return type
     */
    public function setCategoryUpdate() {
        $data = Input::all();

        $count = DB::table('user_category')->where('user_id', $data['user_id'])->count();

        if (!$count) {
            $resule = DB::insert('insert into user_category (user_id, value) values (?, ?)', array($data['user_id'], $data['aca']));
        } else {
            $resule = DB::update('update user_category set value = ? where user_id = ?', array($data['aca'], $data['user_id']));
        }
        if (!$resule) {
            $info = array('status' => FALSE, 'msg' => '操作失败!');
        } else {
            $info = array('status' => TRUE, 'msg' => '操作成功!');
        }

        return Response::json($info);
    }

    /**
     * 
     */
    public function setSectionShow() {
        $user_id = Input::get('user_id');
        $result = array(
            'user_id' => $user_id,
        );

        return view('user::user.setSection', $result);
    }

    /**
     * 返回区块权限数据
     * @return type
     */
    public function setSectionTree() {
        $user_id = Input::get('userid');
		//自由列表
        $SectionsClass = SectionsClass::all();

        $user_temp = DB::table('user_section')->where('user_id', intval($user_id))->first();
        $user_arr = array();
        if ($user_temp) {
            $user = $user_temp->value;
            $user_str = rtrim($user, '|');
            $user_arr = explode("|", $user_str);
        }


        foreach ($SectionsClass as $k => $v) {
			$select = FALSE;
			if ($user_arr) {
				if (in_array($v['sectionid'], $user_arr)) {
					$select = TRUE;
				}
			}
            $rs['data'][$k] = array('name' => $v['name'], 'type' => 'folder', 'additionalParameters' => $this->getChSection($v['classid'], $user_arr));
        }

        $rs['status'] = true;
        return Response::json($rs);
    }

    /**
     * 子栏目
     * @param type $catid
     * @return type
     */
    private function getChSection($catid, $user_arr = array()) {
        $data = array();
        $sections = Sections::where('classid', $catid)->select('sectionid as id', 'name')->get()->toArray();

        foreach ($sections as $k => $v) {
            $select = FALSE;
            if (in_array($v['id'], $user_arr)) {
                $select = TRUE;
            }

            $data['children'][] = array('name' => $v['name'], 'type' => 'item', 'catid' => $v['id'], 'selected' => $select);
        }

        return $data;
    }

    /**
     * 更新
     * @return type
     */
    public function setSectionUpdate() {
        $data = Input::all();
        $count = DB::table('user_section')->where('user_id', $data['user_id'])->count();

        if (!$count) {
            $resule = DB::insert('insert into user_section (user_id, value) values (?, ?)', array($data['user_id'], $data['aca']));
        } else {
            $resule = DB::update('update user_section set value = ? where user_id = ?', array($data['aca'], $data['user_id']));
        }
        if (!$resule) {
            $info = array('status' => FALSE, 'msg' => '操作失败!');
        } else {
            $info = array('status' => TRUE, 'msg' => '操作成功!');
        }

        return Response::json($info);
    }

    //-----------------------权限表的展示-----------------------------------
    public function setMenushow() {
        $user_id = Input::get('user_id');
        //调用方法：非admin帐号不允许修改admin信息
       
        $edit_result= $this->_isCanEdit($user_id);
        if($edit_result['status']==false){
            echo '<script>alert("研发专用帐号不允许操作")</script> ';
             // return Response::json($edit_result);
        }else{
            $return = array(
                'user_id' => $user_id
            );
            return view('user::user.setMenushow', $return);

        }
        //调用方法：非admin帐号不允许修改admin信息
      
    }

   public function setMenuTree(){
		//获取所需的信息
		$user_id = Input::get('user_id');	

        

		//dd($user_id);
		//查询表的字段 查询出 menuid  parentid  name  childids等字段 并且将其转为数组
		$menus = UserMenu::where('parentid',null)->select('menuid','parentid','name','childids')->get()->toArray();
		//dd($menus );
		//查询权限表  user_menu  根据user_id获得所需的信息
		$user_temp = DB::table('user_menu')->where('user_id',intval($user_id))->first();
		$user_arr = array();
		
		//进行判断$user_temp
		if($user_temp){
			$user = $user_temp->value;				
			//去除符号 "|"
			$user_str = rtrim($user,'|');
			//用 "|" 对结果进行拆分
			$user_arr = explode("|",$user_str);
		}
		$rs = array();
		//进行foreach循环输出第一层目录
		foreach($menus  as $k => $v){
			
		//进行判断目录		
					$rs['data'][$k] = array('name'=>$v['name'], 'type' =>'folder','additionalParameters'=>$this->getMenu($v['menuid'],$user_arr));

		}
		//dd($rs['data']);
			$rs['status'] = TRUE;
			//返回数据$rs
			return Response::json($rs);
	}
		
		/*	
		子栏目
	*/
	private function getMenu($menuid,$user_arr){
		//一个空的数组
		$data = array();
		//查询menu表   根据条件获取一些值 menuid   name  
		$menus = UserMenu::where('parentid',$menuid)->select('menuid','childids','name')->get()->toArray();
		//dd($menus);
		//foreach  循环遍利	
		foreach($menus as $k=>$v){			
			$select = FALSE;
		//根据选择进行判断是否为真		
			if(in_array($v['menuid'],$user_arr)){
				$select = TRUE;
			}	
		
			$is_ok = UserMenu::where('parentid',$v['menuid'])->count();
			//dd($is_ok);
			if($is_ok){
				$data['children'][] = array('name' =>$v['name'],'type'=>'folder','additionalParameters'=>$this->getMenu($v['menuid'],$user_arr));
			}else{
				$data['children'][] =array('name' =>$v['name'],'type'=>'item','parentid'=>$v['menuid'],'selected'=>$select);
		
			}	
		}
			return $data;
	}

    public function setMenuupdae() {
        //获取所提交的信息
        $data = Input::all();
		
		//查询表 user_enu   根据user_id  查询所有的信息
        $count = DB::table('user_menu')->where('user_id', $data['user_id'])->count();
        //dd($count);
        //根据所查询的信息($count)  进行判断
        if(!empty($data['aca'])){
			if ($count == 0) {
				//没有信息  则把数据插进去  user_id   vlaue
				$resule = DB::insert('insert into user_menu(user_id, value) values (?, ?)', array($data['user_id'], $data['aca']));
				//dd($resule);
			} else {
				//如果有 则进行修改添加
				$resule = DB::update('update user_menu set value =? where user_id = ?', array($data['aca'], $data['user_id']));
			}
			
		}else{
			//如果权限传值为空，并且里面有数据 则删除
			if($count !=0){
				$resule = DB::delete('delete from user_menu where user_id=?', array($data['user_id']));
			}
		}
        
        $info = array('status' => TRUE, 'msg' => '操作成功');
        
        return Response::json($info);
    }


    /*
       本方法是控制非admin管理员帐号不允许修改admin信息。
        xgh  2016-01-29
     */
    public function _isCanEdit($user_id){
        $Session_user_id = Session::get('admin.user.user_id'); 
        // 非admin帐号，不允许修改admin信息  2016-01-29
        if($Session_user_id != 1 ){
            // 当前修改的是admin，就直接提示研发帐号不允许修改
            if($user_id ==1){
                 return array('status' => FALSE, 'msg' => '研发专用帐号不允许操作');
            }else{
                 return array('status' => true, 'msg' => '可以操作');
            }

        }else{
             return array('status' => true, 'msg' => '可以操作');
        }
        // 非admin帐号，不允许修改admin信息 end

    }

	/*
	*
	*
	*/
	public function qiluindex(){
		
		
		 $user = Role::all()->toArray();
		
        return view('user::qiluuser.index', array('user' => $user));
		
	
	}
	
	public function qiluajax(){
		 $order = $where = $data = array();  // 创建 排序 和 条件数组
        $input = Input::all();
        $content = new UserAdmin;
		
        // 获取搜索条件
        if (Input::has('search')){
			$where['username like'] = "%" . Input::get('search') . "%";
		}
        /*if (Input::has('email'))
          $where['email like'] = "%" . Input::get('email') . "%";

          if (Input::has('created_at'))
          $where['created_at >='] = Input::get('created_at');


          if (Input::has('role')){
          $id = DB::table('user_role')->where('role_id',Input::get('role'))->select('user_id')->get();

          //判断传值为空则返返数值
          if(empty($id)){
          $data['aaData'] = array();
          return Response::json($data);
          }*/

          //如果不为空则查找所有数值
		  $roel = new UserRole;
		  $jizhe = DB::table('role')->where('name','记者')->pluck('role_id');
		  $zebian = DB::table('role')->where('name','责编')->pluck('role_id');
		 
          $ud = $roel->whereIn('role_id',array($jizhe,$zebian))->select('user_id')->get()->toArray();
		
		if ($ud) {
                $ides = '';
                foreach ($ud as $v) {
                    $userid = $v['user_id'];
                    if ($userid) {
                        $ides .= "'$userid'" . ',';
                    }
                }
                $ides = substr($ides, 0, -1);

                $where['user_id in'] = "$ides";
            } else {
                $where['user_id ='] = '';
            }

			//$where['system = '] = 1;
        // 获取总条数
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;

        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;

        $obj = $content->setWhere($where);

        if (isset($_GET['orderby'])) {
            $order = explode('|', $_REQUEST['orderby']);
            $res = $obj->orderBy($order[0], $order[1])->paginate($pagesize)->toArray();
        } else {
            $res = $obj->paginate($pagesize)->toArray();
        }

        //循环遍历数据将数据库中的时间戳，转换成Y-m-d H:i:s的形式
        $res['data'] = $content->fullDataForTables($res['data']);
		
        return Response::json($res);
		
		
	}
	
	public function qiluadd(){
		
		$obj = new Role;
		
		$jizhe = DB::table('role')->where('name','记者')->pluck('role_id');
		$zebian = DB::table('role')->where('name','责编')->pluck('role_id');
		
        $role = $obj->whereIn('role_id',array($jizhe,$zebian,1))->get()->toArray();
       
		$data['roles'] = $role;
		
		
		
        $data['category'] = $this->_getBms($pid = 0);
		
        return view('user::qiluuser.add', $data);
	}
	
	//获取部门菜单
    private function _getBms($pid, $result = array(), $span = "") {
        $charents = DB::table('user_department')->where('pid', '=', $pid)->orderBy('sort')->get();
		
        $span .= '&nbsp&nbsp&nbsp';
        if (count($charents)) {
            foreach ($charents as $item) {
                $result[] = array('id' => $item->id, 'department' => $span . '|--' . $item->department);
                $num = DB::table('user_department')->where('pid', '=', $item->id)->count();
                if ($num != 0) {
                    $result = $this->_getBms($item->id, $result, $span);
                }
            }
        }
		
        return $result;
    }
	
	public function qiluedit(){
	 $user_id = Input::get('user_id', null);
        $obj = new User;
        $user = $obj->getShow($user_id);
		// 用户具有的角色数组
        if (!empty($user['roles'])) {
            $user['roles'] = array_fetch($user['roles'], 'role_id');
		}
        // 所有的角色数组

        
		$objs = new Role;
		$jizhe = DB::table('role')->where('name','记者')->pluck('role_id');
		$zebian = DB::table('role')->where('name','责编')->pluck('role_id');
		
        $role = $objs->whereIn('role_id',array($jizhe,$zebian,1))->get()->toArray();
		$user['allRole'] = $role;
		
		$user['category'] = $this->_getBms($pid = 0);
        // 部门显示
        /* $user['bm'] = $this->_getBm($pid=0);
          return View::make('user::user.edit', array('user'=>$user,'detail'=>$user['detail']));
         */
		 
		 return view('user::qiluuser.edit', $user); 
	
	}

}
