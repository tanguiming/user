<?php

/**
 * 	WxUserPointDetailController微信用户积分展示
 * 
 * @author		lq
 * @date		2015-02-13
 * @version		1.0
 */
namespace Weitac\User\Http\Controllers;
use Response;
use Weitac;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\WxUserDo as WxUserDo;

class WxUserPointDetailController extends AdminController {

    private $pagesize = 15, $upload_max_filesize;

    public function __construct() {

    }

    /**
     * 
     * @return type
     */
    public function index() {
	
        return view('user::wxuser/user_index');
    }

    /**
     * 取得列表数据
     * @return type
     */
    public function ajaxIndex() {
		$content = new WxUserDo();
        $where = array();	
		$token = Weitac::getToken();
		
		if (Input::has('search')) {
			$name = Input::get('search') . "%";
			//$show = DB::table('user_detail')->where('name','like',$name)->get();
			$show = DB::table('user_detail')->join('user','user.user_id', '=', 'user_detail.user_id')->where('user.token', '=', $token)->where('user_detail.name','like',$name)->get();
			foreach($show as $k=>$v){
				$where[] = $v->user_id;
			}
		}

		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;

		
		$res = $content->getdata($pagesize,$page,$token,$where);

        return Response::json($res);
    }
	
	 /**
     * 
     * @param type $user_id
     * @return string
     */
	private function _getUser($user_id) {
        //$obj = new WxUser();
        //$user = $obj->getUser($openid);
        $users = UserDetail::where('user_id', '=', $user_id)->first();
		$user = array();
		if(!empty($users)){
			$users = $users->toArray();
			$user['head_picture'] = empty($users['head_picture']) ? '' : '<img src="' . asset($users['head_picture']) . '" style="width:60px;height:60px;"/>&nbsp;&nbsp;';
            $user['name'] = isset($users['name']) ? $users['name'] : '匿名';
            $user['user_id'] = $user_id;
            $user['point'] = $users['point'];
            $user['experience'] = $users['experience'];
            $user['last_time'] = $users['last_time'];
			
		}
		return  $user;
    }
	
	/*
	删除操作
	*/
	public function delete(){
		$ids=Input::get('ids');  //获取ids
		if(empty($ids)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出要删除的项"
				);
			return Response::json($return);
		}
		$ids2=substr($ids,0,-1);  //去掉最后的一个逗号
		$idsToArr=explode(",", $ids2);  //获取ids组成的数组
		$obj=new WxUserDo();
		$return=$obj->del($idsToArr);
		return Response::json($return);
	}
}
