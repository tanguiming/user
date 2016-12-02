<?php
namespace Weitac\User\Http\Controllers;
use Response;
use App\Http\Controllers\AdminController as AdminController;
use Illuminate\Support\Facades\Input;
use Weitac\User\Http\Models\PointLogs as PointLogs;
use Weitac\User\Http\Models\UserDetail as UserDetail;
use Weitac\User\Http\Models\UserPointClass as UserPointClass;
use Weitac\User\Http\Models\FrontUserPoint as FrontUserPoint;
use Weitac\User\Http\Models\UserGrade as UserGrade;
use Weitac\User\Http\Controllers\UserPointController as UserPointController;
use Weitac;
use DB;


class PointLogsController extends AdminController{
	
	private $pagesize = 15;
	
    public function __construct()
    {
         parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function index()
    {
        $token = Weitac::getToken();
        $name = DB::table('user_integral_set')->where('token',$token)->pluck('name');
        $return = array('name' => $name);
        return View("user::pointlogs/index",$return);
    }
	

    public function ajaxIndex(){
	
		$content = new PointLogs();
		$where = array();
            //条件
        if (Input::has('search')) {
            if (Input::get('search')){
                $name = Input::get('search');
				if ($name) {
                    //$ud = UserDetail::where('name',$name)->select('openid')->get()->toArray();
                    $ud = UserDetail::where('name', 'like', "%$name%")->select('user_id')->get()->toArray();

                    //$ud = DB::table('user_detail')->where('name','like',$name)->get();
                    //dd(DB::getQueryLog());
                    if ($ud) {
                        $ides = '';
                        foreach ($ud as $v) {
                            //$openids = DB::table('user')->where('username',$v['openid'])->where('token',Input::get('token'))->first();
                            $user_id = $v['user_id'];
                            if ($user_id) {
                                $ides .= "'$user_id'" . ',';
                            }
                        }
                        $ides = substr($ides, 0, -1);
                        $where['user_id in'] = "$ides";
                    } else {
                        $where['user_id ='] = '';
                    }
                }
            }
        }

        $obj = $content->setWhere($where); //


        if (isset($_GET['sort']) && isset($_GET['order'])) {
            $res = $obj->orderBy($_GET['sort'], $_GET['order'])->select()->paginate(Input::get('limit'))->toArray();
        } else {
            $res = $obj->paginate($Input::get('limit'))->toArray();
        }
		
		foreach($res['data'] as $k=>$v){
            $res['data'][$k]['datetime'] = date('Y-m-d H:i:s',$v['datetime']);
			$res['data'][$k]['user_id'] = UserDetail::where('user_id',$v['user_id'])->pluck('name').'（ID：'.$v['user_id'].'）';
		}
		
        return Response::json($res);
	 
	}

    public function add(){

        $obj1 = new UserPointClass();
        $obj2 = new FrontUserPoint();
        $obj3 = new UserGrade();

        $res1 = $obj1->select('classid','classtitle')->get()->toArray();

        $res2 = [];
        foreach ($res1 as $k => $v) {
            $res2[$k] = $obj2->where('classid',$v['classid'])->select('pinyin','name')->get()->toArray();
        }

        $res3 = $obj3->select('id','title')->get()->toArray();

        $return = array(
            'res1' => $res1,
            'res2' => $res2,
            'res3' => $res3
            );

        return View("user::pointlogs/reward",$return);
    }

    // 查询积分
    public function search(){
        
        $id = Input::get('id');

        $obj = new UserDetail();

        $point = $obj->where('user_id',$id)->pluck('point');

        return Response::json($point);
    }

    // 插入电话数据
    public function insert(){
        $data = Input::except("_token", "/admin/user/pointlogs/insert");

        $obj1 = new UserPointController();
        $obj2 = new UserDetail();

        $token = Weitac::getToken();

        if($data['type'] == 1){
            // 单独
            $res = $obj1->encapsulation($data['id'],$data['guize'],$token);

        }else{
            // 批量
            $result = $obj2->where('grade_id',$data['grade'])->lists('user_id')->toArray();

            foreach ($result as $k => $v) {
                $res = $obj1->encapsulation($v,$data['guize'],$token);
            }

        }

        return Response::json($res);

    }

}