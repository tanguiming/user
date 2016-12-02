<?php

namespace Weitac\User\Http\Controllers;

use Auth;
use Response;
use Route;
use Request;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\AdminController;
use Weitac\User\Http\Models\UserLoginLog as UserLoginLog;

/**
 * 	用户登录日管理
 * 
 * @author			hwx
 * @date			2015-11-05
 * @version	1.0
 */

class UserLoginLogController extends AdminController {

	public function __construct()
	{
		parent::__construct();
		// post 进行csrf验证
		$this->beforeFilter('csrf', array('on'=>'post'));
	}

	// 登录日志列表
	public function index()
	{
		return View('user::loginLog.index');
	}

	/**
	 *  ajax数据源
	 *  
	 *  为dataTables提供JSON数据源
	 */
	public function ajaxIndex()
	{
		$order = $where = $data = array();		// 创建 排序 和 条件数组
		$input = Input::all();
		$content = new UserLoginLog;

		$obj = $content->setWhere($where); //


        if (isset($_GET['sort']) && isset($_GET['order'])) {
			$limit = (int)Input::get('limit');
			$pageNumber = (int)Input::get('pageNumber');
            $res = $obj->orderBy($_GET['sort'], $_GET['order'])->select('username', 'ip', 'time','type','status')->paginate($limit,$pageNumber)->toArray();
        } else {
            $res = $obj->paginate($pagesize)->toArray();
        }

        foreach ($res['data'] as $k => $v) {
			$res['data'][$k]['time'] = date("Y-m-d H:i:s", $v['time']);
			if($res['data'][$k]['type'] == 1){
				$res['data'][$k]['type'] = '<span class="label label-success arrowed label-large">登录</span>' ;
			}else if($res['data'][$k]['type'] == 0){
				$res['data'][$k]['type'] = '<span class="label label-info arrowed-in-right arrowed label-large">退出</span>';
			}

			if($res['data'][$k]['status'] == 1){
				$res['data'][$k]['status'] = '<span class="label label-success arrowed label-large">成功</span>' ;
			}else if($res['data'][$k]['status'] == 0){
				$res['data'][$k]['status'] = '<span class="label label-info arrowed-in label-large">失败</span>';
			}
        }

        return Response::json($res);
	}

	/**
	 *	备份日志
	 */
	public function backup()
	{
		if(Input::has('do') && Input::get('do') == 'backup')
		{
			$obj = new UserLoginLog;
			$info = $obj->backup();

			if($info['status'] == true)
			{
				$obj->clear();

				Event::fire('log.action', array('userLoginLog.backup', null));
			}

			return Response::json($info);
		}
	}

}