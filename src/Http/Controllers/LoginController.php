<?php
namespace Weitac\User\Http\Controllers;

use Auth;
use Response;
use Route;
use Request;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\AdminController;
use Event;
use App\Events\SignEvent;
use Weitac\User\Http\Models\User as User;


/**
 * 	登录
 * 
 * @author		tgm
 * @date		2015-09-02
 * @version	1.0
 */

class LoginController extends AdminController {
	
	public function __construct()
	{
		//  POST表单 进行 csrf验证
		//$this->beforeFilter('csrf', array('on' => 'post'));
            //parent::__construct();
             //$this->middleware('auth');
                
	}
	
	/**
	 * 	
	 * 后台登录界面
	 */
	public function login()
	{ 
		// 如果已登录的用户，则跳转到后台首页
		if(Auth::check())
		{
			return redirect('admin/index');
		}
		return view('user::login.index');	
	}
	
	/**
	 * 用户	登录
	 * 成功 记录 IP 登录时间 
	 * 失败 记录 IP 错误次数 超过多少次 禁止N分钟 继续
	 */
	public function doLogin()
	{
            
			$status = $msg = '';
			$username = Input::get('username');
			$password = Input::get('password');
			$rememer = Input::get('remember');
			
			$user = new User;
			$info = $user->checkUserLogin($username, $password, $rememer);
			
			//----------------11.4登录日志开始----------------------------------
			
			//dd($username,$info['status']);
			if($info['status']){
				$status = 1;
			}else{
				$status = 0;
			}
			$userid = User::where('username',$username)->pluck('user_id');
			$data = array(
				'username'=>$username,
				'type'=>1,
				'time'=>time(),
				'userid'=>$userid,
				'ip'=>Request::server('REMOTE_ADDR'),
				'status'=>$status,
			);
			//dd($data);
			Event::fire(new SignEvent($data));
			//-------------------11.4登录日志结束----------------------------------------
			
                        //$info = array('status' => true, 'msg' => '登录成功');
			return Response::json($info);

	}

	/**
	 *  忘记密码
	 */
	public function forginPassword()
	{
		
	}
	
	/**
	 * 	用户注销
	 */
	public function logout()
	{
		$user = new User;
		$soso = $user->logout();
		
		//----------------11.5退出日志开始----------------------------------
		$username = $soso['username'];
		//dd($username);
		$userid = $soso['user_id'];
		$data = array(
			'username'=>$username,
			'type'=>0,
			'time'=>time(),
			'userid'=>$userid,
			'ip'=>Request::server('REMOTE_ADDR'),
			'status'=>1,
		);
		//dd($data);
		Event::fire(new SignEvent($data));
		//----------------11.5退出日志结束----------------------------------
		
		return redirect('admin/login');//Redirect::route('admin.user.login');
	}
	
	/**
	 *  临时添加管理员
	 */
	public function init()
	{
		if(User::count() > 0)
		{
			return Redirect::route('admin.user.login');
		}

		$user = new User;
		$user->username = 'admin';
		$user->password = Hash::make('123456');
		$user->email = 'admin@cdv.com';
		$user->system = 1;
		$user->save();
		$user_id = $user->user_id;
		$detail = new UserDetail;
		$detail->user_id = $user_id;
		$detail->save();
		
		return Redirect::route('admin.user.login');
	}
	
}