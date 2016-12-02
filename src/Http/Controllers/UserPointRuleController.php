<?php

/**
 * 	UserPointRuleController 用户赚币控制器
 * 
 * @author		kxl
 * @date		2015-2-12
 * @version		1.0
 */
namespace Weitac\User\Http\Controllers;
use Response;
use Weitac;
use App\Http\Controllers\AdminController as AdminController;
use Illuminate\Support\Facades\Input;
use Weitac\User\Http\Models\UserPointRule as UserPointRule;


class UserPointRuleController extends AdminController {

    private $pagesize = 15;

    public function __construct() {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * 
     * @return type
     */
    public function index() {
        return View('user::userpointrule/index');
    }

    /**
     * 取得列表数据
     * @return type
     */
    public function ajaxIndex() {

        $order = $where = $data = array();

        $input = Input::all();

        $content = new UserPointRule();

        //条件查询
        if (Input::has('search')){
            $where['name like'] = "%" . Input::get('search') . "%";
		}
		
        $obj = $content->setWhere($where); //


        if (isset($_GET['sort']) && isset($_GET['order'])) {

            $res = $obj->orderBy($_GET['sort'], $_GET['order'])->select()->paginate(Input::get('limit'))->toArray();
        } else {
            $res = $obj->paginate($Input::get('limit'))->toArray();
        }

        foreach ($res['data'] as $k => $v) {
            //$res['data'][$k]['status'] = $v['status'] == 1 ? '活跃' : '禁止';
        }

        
        return Response::json($res);
    }
	
	/**
     * 添加获取模板
     * @return type
     */
    public function add() {
        return View('user::userpointrule/add');
    }
	
	/**
     * 添加保存
     * @return type
     */
    public function insert() {
		$data =Input::except('/admin/user/pointrule/insert','_token');
		$data['token'] = Weitac::getToken();
		//判断插入数据在数据库中是否存在
        $res = UserPointRule::where('name', '=', $data['name'])->get()->toArray();
        if(!$res){
			$obj = new UserPointRule;
			$result = $obj->add($data);
        }else{
			$result = array('status'=>false, 'msg'=>'名称已经存在');
		}
		return Response::json($result);
	}
	
	
	/**
	 * 修改获取模板
	 * @return type
	 */
	    public function edit() {
			$id = Input::get('id');
			$data = UserPointRule::find($id)->toArray();
			//修改数据时，将年月日和时分秒分开显示在要修改的框体中。
			$result = array(
				'name' => $data['name'],
				'content' => $data['content'],
				'currency' => $data['currency'],
				'value' => $data['value'],
				'id' => $id
			);
		return View('user::userpointrule/edit',$result);
    }
	
    /**
     * 保存修改
     * @return type
     */
    public function update() {
		
		//添加到数组，获取修改的id.
		$data =Input::except('/admin/user/pointrule/update','_token');
		$id = $data['id'];
        $obj = new UserPointRule;
        $result = $obj->edit($id, $data);
        return Response::json($result);
    }
	
	/**
     *  软删除用户
     */
    public function delete() {

        $ids = Input::get('ids');

        $ids = substr($ids, 0, -1);
        $array = explode(",", $ids);

        $obj = new UserPointRule();
        $result = $obj->del($array);

        return Response::json($result);
    }
}
