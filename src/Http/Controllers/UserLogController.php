<?php

namespace Weitac\User\Http\Controllers;

use Auth;
use Response;
use Route;
use Request;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\AdminController;
use Weitac\User\Http\Models\UserLog as UserLog;

/**
 * 	用户操作日志
 * 
 * @author			hwx
 * @date			2015-11-06
 * @version	1.0
 */

class UserLogController extends AdminController {

	public function __construct()
	{
		parent::__construct();
		// post 进行csrf验证
		$this->beforeFilter('csrf', array('on'=>'post'));
	}

	// 操作日志列表
	public function index()
	{
		return View('user::log.index');
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
		$content = new UserLog;

		$obj = $content->setWhere($where); //


        if (isset($_GET['sort']) && isset($_GET['order'])) {
			$limit = (int)Input::get('limit');
			$pageNumber = (int)Input::get('pageNumber');
            $res = $obj->orderBy($_GET['sort'], $_GET['order'])->select('username', 'action', 'time','ip')->paginate($limit,$pageNumber)->toArray();
        } else {
            $res = $obj->paginate($pagesize)->toArray();
        }

        foreach ($res['data'] as $k => $v) {
			$res['data'][$k]['time'] = date("Y-m-d H:i:s", $v['time']);
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
			$obj = new UserLog;
			$info = $obj->backup();
			
			if($info['status'] == true)
			{
				$obj->clear();

				Event::fire('log.action', array('userLog.backup', null));
			}

			return Response::json($info);
		}
	}

}