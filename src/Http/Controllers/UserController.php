<?php

namespace Weitac\User\Http\Controllers;

use DB;
use Auth;
use Weitac;
use Session;
use Response;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\User as User;
use Weitac\User\Http\Models\WxUser as WxUser;
use Weitac\User\Http\Models\UserDetail as UserDetail;
use Illuminate\Support\Facades\Input;
use Weitac\User\Http\Models\Role as Role;
use Weitac\User\Http\Models\UserRole as UserRole;
use Weitac\User\Http\Models\RoleAca as RoleAca;
use Weitac\User\Http\Models\Aca as Aca;
use Weitac\User\Http\Models\UserCenter as UserCenter;
use Content\Weixin\Models\WxContent as WxContent;
//	部门 2016-9-22
use Service\Help\Models\OA_DeparTment as OA_DeparTment;
//获取ACCESS_TOKEN
use Oa\Help\Models\OA_WxResponse as OA_WxResponse;

class UserController extends AdminController {
	//设置页条数5条
	private $pagesize = 5, $upload_max_filesize;
	

    /**
     * 显示用户列表
     */
    public function index() {
        $reg = User::where('status', '1')->count();
        $wreg = User::where('status', '0')->count();
        $dat = WxUser::select('wxname', 'token')->get()->toArray();

        $res = array(
            'reg' => $reg,
            'wreg' => $wreg,
            'dat' => $dat,
        );
        return view('user::user.index', $res);
    }

    /**
     * 
     * @return type
     */
    public function ajaxIndex() {
        $content = new User;
        $where = array();

        // 获取搜索条件
        if (Input::has('username')) {
            $str = Input::get('username');
			$ides = '';
            $user = "%" . Input::get('username') . "%";
			//先去用户表搜索
            $useri = DB::select('select user_id from user where username like ? or email like ?', array($user, $user));
			if ($useri) {
                foreach ($useri as $k => $v) {
                    $userid = $v->user_id;
                    if ($userid) {
                        $ides .= "'$userid'" . ',';
						
                    }
                }
				$ides = substr($ides, 0, -1);
            }
			
			
            $pm = preg_match("/^\d*$/", $str);
			//查找字符串类
            if ($pm != 1) {

                //通过user_deatail表的数据来关联数据
                $ud = DB::select('select user_id from user_detail where name like ? or address like ? or country like ? or province like ? or city like ?', array($user, $user, $user, $user, $user));
                if ($ud) {
                    foreach ($ud as $k => $v) {
                        $userid = $v->user_id;
                        if ($userid) {
                            $ides .= "'$userid'" . ',';
                        }
                    }
                    $ides = substr($ides, 0, -1);
                    $where['user_id in'] = "$ides";
                } else {
					if($ides){
						$where['user_id in'] = "$ides";	
					}else{
						$where['user_id ='] = "";
					}
                  
                }
				
                //查找数字类
            } else {
                //通过user_deatail表的数据来关联数据
                $ud = DB::select('select user_id from user_detail where telephone like ? or mobile like ? or zipcode like ? or qq like ? or experience like ? or grade_id like ? or card like ?', array($user, $user, $user, $user, $user, $user, $user));
                if ($ud) {
                    foreach ($ud as $k => $v) {
                        $userid = $v->user_id;
                        if ($userid) {
                            $ides .= "'$userid'" . ',';
                        }
                    }
                    $ides = substr($ides, 0, -1);
                    $where['user_id in'] = "$ides";
                } else {
					
                    if($ides){
						$where['user_id in'] = "$ides";	
					}else{
						$where['user_id ='] = "";
					}
                }
            }
        }
        //IP地址
        if (Input::has('last_ip')) {
            $where['last_ip = '] = Input::get('last_ip');
        }

        //创建时间
        if (Input::has('created_at')) {
            $where['created_at >='] = Input::get('created_at');
        }


        //最后登陆时间
        if (Input::has('last_login')) {
            $where['last_login >='] = Input::get('last_login');
        }


        //来源
        if (Input::has('source')) {
            $where['source ='] = Input::get('source');
        }

        // 获取状态搜索 正常 待审
        if (Input::has('status')) {
            $where['status ='] = "%" . Input::get('status') . "%";
        }

        //栏目
        if (Input::has('token')) {
            $where['token = '] = Input::get('token');
        }

        //验证方式  0未验证  1验证
        if (Input::has('is_validate')) {

            $validate = Input::get('is_validate');

            //通过user_deatail表的数据来关联数据
            $ud = UserDetail::where('is_validate', $validate)->select('is_validate', 'user_id')->get()->toArray();
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
        }

        //性别搜索
        if (Input::has('sex')) {
            $sex = Input::get('sex');
            if ($sex != "") {
                //通过user_deatail表的数据来关联数据
                $ud = UserDetail::where('sex', $sex)->select('sex', 'user_id')->get()->toArray();
            }
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
        }

        //地区搜索
        if (Input::has('city')) {
            $city = Input::get('city');

            if ($city != "") {
                //通过user_deatail表的数据来关联数据
                $ud = UserDetail::where('city', 'like', "%$city%")->select('city', 'user_id')->get()->toArray();
            }
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
        }
		
        // $users = Session::get('admin.user.token');
        // if (count($users)) {
        // $token = "";
        // foreach ($users as $v) {
        // $token .= "'" . $v['token'] . "',";
        // }
        // }

        $where['system ='] = 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;

        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;
		// echo "<pre>";
		// dd($where);
		$obj = $content->setWhere($where);
		if (isset($_GET['orderby'])) {
            $order = explode('|', $_REQUEST['orderby']);
            $res = $obj->orderBy($order[0], $order[1])->paginate($pagesize)->toArray();
        } else {
            $res = $obj->paginate($pagesize)->toArray();
        }

        //循环遍历数据将数据库中的时间戳，转换成Y-m-d H:i:s的形式
        foreach ($res['data'] as $k => &$v) {
			$detail = UserDetail::find($v['user_id']);
			if($detail){
				$v['nickname'] = $detail->name;
			}else{
				$v['nickname'] = "无昵称";
			}
			$userinfo = $this->_getNameHead($v['username']);
			$v['username'] = '<img src="' . WWW_URL . $userinfo['head_picture'] . '" style="width:50px;height:50px;" />';

            if ($v['status'] == 1) {
                $v['status'] = '<span class="label label-success arrowed label-large">正常</span>';
            } else if ($v['status'] == 0) {
                $v['status'] = '<span class="label label-info arrowed-in-right arrowed label-large">待审</span>';
            } else {
                $v['status'] = '<span class="label label-important arrowed-in label-large">禁用</span>';
            }
			$role = new Role;
            // 获取该用户所拥有的角色名
            $roles = $role->getRoleByUser($v['user_id']);
            $roleNameString = '注册用户';

            if (!empty($roles)) {
                $rolesName = array_fetch($roles, 'name');
                $roleNameString = implode($rolesName, ',');
            }

            $v['role'] = $roleNameString;
			// $v['nickname'] = $this->_getUser($v['user_id']);
			//$res['data'] = $obj->fullDataForTables($res['data']);
        }
        return Response::json($res);
    }
	
	/**
     *  根据获得的openid在user_detail表里找出该用户，并得到该用户的微信名字等
     *  return $arr
     */
    private function _getNameHead($openid)
    {
        $data = DB::table('user_detail')->where('openid', '=', $openid)->select('name','mobile','head_picture')->first();
        if(empty($data)){
            return array('mobile'=>'无号码','name'=>'无昵称','head_picture'=>'#');

        }

        $data = get_object_vars($data); //对象转数组
        
        if ($data['mobile'] == null) {
             $data['mobile']='无号码';
        }

        if ($data['name'] == null) {
            $data['name']='无昵称';
        }

        if ($data['head_picture'] == null) {
             $data['head_picture']='#';
        }

        // dd($data);
        return $data;
    }

    //添加页面
    public function add() {
        $obj = new Role;
        $role = $obj->getList();
        $data['roles'] = $role;
		//	获取部门数据表
		
		$data['category'] = $this->_getBm($pid = 0);
		
        return view('user::user.add', $data);
    }
	
	//获取部门菜单
    private function _getBm($pid, $result = array(), $span = "") {
        $charents = DB::table('user_department')->where('pid', '=', $pid)->orderBy('sort')->get();
		
        $span .= '&nbsp&nbsp&nbsp';
        if (count($charents)) {
            foreach ($charents as $item) {
                $result[] = array('id' => $item->id, 'department' => $span . '|--' . $item->department);
                $num = DB::table('user_department')->where('pid', '=', $item->id)->count();
                if ($num != 0) {
                    $result = $this->_getBm($item->id, $result, $span);
                }
            }
        }
		
        return $result;
    }
	
	

    public function insert() {
		$data = Input::all();
		
		$obj = new User;
		$user = $obj->fullData($data);
		// 验证提交的表单信息
		$info = $obj->check($user);

		if ($info['status'] == true) {
			$user['token'] = Weitac::getToken();
			$info = $obj->add($user);
			//	判断是否推送
			if($data['push_type'] ==1){
				$this->_pusheusr($data,'POST','create');
			}
			
			
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
					//dd($userRole);
					$obj->add($userRole);
				}
			}

			//Event::fire('log.action', array('user.add', $data['user_id']));
		}

		return Response::json($info);
      
    }

    //编辑页面
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
		
		$user['category'] = $this->_getBm($pid = 0);
        // 部门显示
        /* $user['bm'] = $this->_getBm($pid=0);
          return View::make('user::user.edit', array('user'=>$user,'detail'=>$user['detail']));
         */
		 
		 
        return view('user::user.edit', $user);
    }

    /**
     *  执行编辑用户
     */
    public function update() {

        $data = Input::all();
        
        //调用方法：非admin帐号不允许修改admin信息
        $edit_result=$this->_isCanEdit($data['user_id']);
        if($edit_result['status']==false){
             return Response::json($edit_result);
        }
        //调用方法：非admin帐号不允许修改admin信息

        $obj = new User;
        $user = $obj->fullData($data);

        // 验证提交的表单信息
        $info = $obj->check($user, 'edit');

        if ($info['status'] == true) {
            unset($user['old_email']);
			$user['token'] = Weitac::getToken();
			//dd($user);
            $info = $obj->edit($user);
			
			//	根据值判断 是否推送到企业号
			if($data['push_type'] ==1){
				$this->_pusheusr($data,'POST','update');
			}
			
	
	
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

            // Event::fire('log.action', array('user.edit', $data['user_id']));
        }

        return Response::json($info);
    }

    /**
     * 	修改密码
     */
    public function pwd() {
        return view('user::user.pwd', array('user_id' => Input::get('user_id')));
    }

    /**
     * 	执行修改密码
     */
    public function doPwd() {


        $data['user_id'] = Input::get('user_id');
        $data['password'] = Input::get('password');

        //调用方法：非admin帐号不允许修改admin信息  
        $edit_result=$this->_isCanEdit($data['user_id']);
        if($edit_result['status']==false){
             return Response::json($edit_result);
        }
        //调用方法：非admin帐号不允许修改admin信息


        if (!empty($data['password'])) {
            $obj = new User;
            $info = $obj->check($data, 'pwd');

            if ($info['status'] == true) {
                $info = $obj->changePassword($data);

                //Event::fire('log.action', array('user.pwd', $data['user_id']));
            }

            return Response::json($info);
        } else {
            $info = array('status' => false, 'msg' => '密码不可以为空');
            return Response::json($info);
        }
    }

    /**
     *  软删除用户
     */
    public function delete() {

        $user_id = Input::get('user_id');

         //所有帐号包括admin帐号不允许删除admin帐号
        if($user_id==1){
            return Response::json(array('status' => false, 'msg' => '研发专用帐号不允许删除'));
        }
        //所有帐号包括admin帐号不允许删除admin帐号
        

        $obj = new User;
        Event::fire('log.action', array('user.delete', $user_id));
        return Response::json($obj->del($user_id));
    }

    /**
     *  彻底删除用户
     */
    public function destroy() {

        $ids = Input::get('ids');

        $ids = substr($ids, 0, -1);
        $array = explode(",", $ids);

        //所有帐号包括admin帐号不允许删除admin帐号
        foreach ($array as $key => $value) {
            if($value==1){
               return Response::json(array('status' => false, 'msg' => '研发专用帐号不允许删除'));
            }
        }
        //所有帐号包括admin帐号不允许删除admin帐号

       
        $obj = new User();
        $result = $obj->des($array);

        return Response::json($result);
    }

    //批量取消用户
    public function pdelete() {
        $data = Input::all();
        $id = explode(',', $data['ids']);
        //如果最后一个值为"",则删除该值
        if (end($id) == "") {
            $ids = array_pop($id);
            $obj = User::whereIn('user_id', $id)->pluck('status');
            if ($obj == 1) {
                $obj = User::whereIn('user_id', $id)->update(array('status' => 0));
                return Response::json(array('status' => true, 'msg' => '取消审核成功'));
            } else {
                return Response::json(array('status' => false, 'msg' => '取消审核失败'));
            }
        }
    }

    //批量审核用户
    public function pexamine() {
        $data = Input::all();
        $id = explode(',', $data['ids']);
        //如果最后一个值为"",则删除该值
        if (end($id) == "") {
            $ids = array_pop($id);
            $obj = User::whereIn('user_id', $id)->pluck('status');
            if ($obj == 0) {
                $obj = User::whereIn('user_id', $id)->update(array('status' => 1));
                return Response::json(array('status' => true, 'msg' => '批量审核成功'));
            } else {
                return Response::json(array('status' => false, 'msg' => '批量审核失败'));
            }
        }
    }

    //批量禁止用户
    public function ban() {
        $data = Input::all();
        $id = explode(',', $data['ids']);
        //如果最后一个值为"",则删除该值
        if (end($id) == "") {
            $ids = array_pop($id);
            $obj = User::whereIn('user_id', $id)->pluck('status');
            if ($obj != 3) {
                $obj = User::whereIn('user_id', $id)->update(array('status' => 3));
                return Response::json(array('status' => true, 'msg' => '批量禁止成功'));
            } elseif ($obj == 3) {
                $obj = User::whereIn('user_id', $id)->update(array('status' => 1));
                return Response::json(array('status' => true, 'msg' => '批量信任成功'));
            } else {
                return Response::json(array('status' => false, 'msg' => '批量修改失败'));
            }
        }
    }
	
	/**
     * 
     * 显示用户信息新版
     */ 
    public function show()
    {
	
        $user_id = Input::get('user_id', null);
        $obj = new User;
        $user = $obj->getShow($user_id);
		$res = array();
		if(count($user['roles']) != 0){
			//拼装角色节点表
			$roleid = array();
			foreach($user['roles'] as $k=>$v){
				$roleid[] = $v['role_id'];
			}
			
			//角色与权限节点表
			$aca =array();
			$aca_id = RoleAca::whereIn('role_id',$roleid)->get()->toArray();
			if(count($aca_id) != 0){
				foreach($aca_id as $k=>$v){
					$aca[] = $v['aca_id'];
				}
				//查权限
				$res = Aca::whereIn('aca_id',$aca)->select('remark')->get()->toArray();
			}
			
		}
        
		//所属栏目查询		
		if(!empty($user['token'])){
			$wxuser = WxUser::where('token',$user['token'])->pluck('wxname');
		}else{
			$wxuser ="";
		}
		
		//查询给那个栏目发过信息，都是多少条
		$mobile = UserDetail::where('user_id',$user_id)->pluck('mobile');
		$wxsum = 0;
		$wxcount ='';
		if(!empty($mobile)){
			
			//通过电话获取对应的openid
			$openid = UserDetail::where('mobile',$mobile)->select('openid')->get()->toArray();
			
			if(!empty($openid[0]['openid'])){
				$openi = '';
				//$openidd =array();
				foreach($openid as $k=>$v){
					if(!empty($v['openid'])){
						$nid = $v['openid'];
						$openi .= "'$nid'" . ',' ; 
						//$openidd[] = $v['openid'];
					}
				}
				$openidd= substr($openi, 0, -1);
				$openidd="($openidd)";
				//用拼装好的openid去wx_content中查询得到想要的结果。
				
				$wxcount = DB::select("select token,count('token') as sum from wx_content where FromUserName in $openidd group by token");
				
				foreach($wxcount as $ks=>$vs){
					$vs->token = $this->_getWxname($vs->token);
				}
				//用户发信息数
				$opearr = array();
				foreach($openid as $k=>$v){
					if(!empty($v['openid'])){
						$opearr[] = $v['openid']; 
					}
				}
				//$wxsum = WxContent::whereIn('FromUserName',$opearr)->count();
				$wxcount ='';
			}else{
				$wxcount ='';
			}
		}else{
			
			$openid = UserDetail::where('user_id',$user_id)->pluck('openid');
			if(!empty($openid)){
				$wxcount = DB::select("select token,count('token') as sum from wx_content where FromUserName = ? group by token",array($openid));
				foreach($wxcount as $ks=>$vs){
					$vs->token = $this->_getWxname($vs->token);
				}
				//用户发信息数
				//$wxsum = WxContent::where('FromUserName',$openid)->count();
				$wxsum = '';
			}
		}
		
		//爆料条数
		$baoliao = DB::table('wt_fact_list')->where('fact_tel',$mobile)->count();
		
		//栏目
		$lanmu = WxUser::select('token','wxname')->get()->toArray();
		//基本信息查询
		$center = array();
		if(!empty($mobile)){
			$phone = UserCenter::where('phone',$mobile)->first();
			
			if(!empty($phone)){
				$center = $phone->toArray();
			}
		}
		
		//-------新加：最新动态部分，以user_id查询wx_content表。注意：wx_content表的这个字段居然都是0，我暂时写几个假数据，把信息先调出来。------
		$token = Weitac::getToken();
		//$information = WxContent::where("user_id",$user_id)->select('CreateTime','Content')->orderBy('CreateTime','desc')->get()->toArray();
		$information = WxContent::where("user_id",$user_id)->where('token',$token)->select('CreateTime','Content')->orderBy('CreateTime','desc')->paginate(6)->toArray();
		//dd($information);
		//-------新加结束------
		
		//获取线索条数
		$result = array(
			'user'=>$user, 
			'detail'=>$user['detail'], 
			'role'=>$user['roles'], 
			'wxuser'=>$wxuser,
			'wxsum'=>$wxsum,		//总信息
			'wxcount'=>$wxcount,	//各栏目信息
			'baoliao'=>$baoliao,	//爆料
			'lanmu'=>$lanmu,		//栏目
			'center'=>$center,		//基本信息
			'information'=>$information['data'],	//最新动态信息内容
			'user_id'=>$user_id,
		);
		
		//dd($result,$user_id);
        //Event::fire('log.action', array('user.show_xin', $user_id));
        return view('user::user.show',$result);
    }
	
	public function more(){
		$user_id = $_GET['user_id'];
		$_GET['p']=$_GET['page'];
		$token = Weitac::getToken();
		$pagesize = 6;
		$str = '<div class="ibox-content infinite_scroll">';
		$list = WxContent::where("user_id",$user_id)->where('token',$token)->select('CreateTime','Content')->orderBy('CreateTime','desc')->paginate(6)->toArray();
		//dd($list);
     	$count = count($list);
        // if(4*intval($_GET['p']-1)>=$count){
        //         die();
        // }
       	$data = $list['data'];
        foreach ($data as $key => $val) {
        	$str .='<div class="item"><div class="feed-activity-list" style="height:45px;line-height:45px;border-bottom:1px solid #E7EAEC">';
			$str .='<div>'.date('Y-m-d H:i:s',$val['CreateTime']).'：'.$val['Content'].'</div></div>';	
        }
        $str .="</div>";
            echo $str;
            die();
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
	*	推送人到企业号那边入库
	*
	*/
	public function _pusheusr($data,$type,$type_caozuo){
		$bojtoken = new OA_WxResponse(); 
		$accessToken = $bojtoken->_assetoken(); 
		$url = 'https://qyapi.weixin.qq.com/cgi-bin/user/'.$type_caozuo.'?access_token='.$accessToken;
		$push_user['userid'] = $data['username'];
		$push_user['name'] = $data['name'];
		$push_user['department'] = $data['bm_id'];
		$push_user['mobile'] = $data['mobile'];
		$res_data = $bojtoken->_jsondate($push_user,$url,$type);
		
	}
	
	
	
	
	
	
	
	
}
