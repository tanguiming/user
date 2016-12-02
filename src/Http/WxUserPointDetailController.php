<?php

/**
 * 	WxUserPointDetailController微信用户积分展示
 * 
 * @author		lq
 * @date		2015-02-13
 * @version		1.0
 */
class WxUserPointDetailController extends AdminController {

    private $pagesize = 15, $upload_max_filesize;

    public function __construct() {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * 
     * @return type
     */
    public function index() {
	
        return View::make('user::wxuser/user_index');
    }

    /**
     * 取得列表数据
     * @return type
     */
    public function ajaxIndex() {
		$content = new WxUserDo();
        $where = array();	
  
		if (Input::has('name')) {
            if (Input::get('name')) {
                $where['name like '] = "%" . Input::get('name') . "%";
            }
        }

		$token = Weitac::getToken();
		$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;

		
		$res = $content->getdata($pagesize,$page,$token);
		
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
	
	public function demo(){
		dd('1111');
	}

}
