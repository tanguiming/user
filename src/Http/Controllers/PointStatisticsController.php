<?php
namespace Weitac\User\Http\Controllers;
use Response;
use App\Http\Controllers\AdminController as AdminController;
use Illuminate\Support\Facades\Input;
use Weitac\User\Http\Models\PointLogs as PointLogs;
use Weitac\User\Http\Models\UserDetail as UserDetail;
use Weitac\User\Http\Models\UserGrade as UserGrade;
use Weitac;
use DB;

class PointStatisticsController extends AdminController{
    
    private $pagesize = 15;
    
    public function __construct()
    {
         parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function index(){

        $token = Weitac::getToken();
        $name = DB::table('user_integral_set')->where('token',$token)->pluck('name');
        $return = array('name' => $name);

        return View("user::pointstatistics/index",$return);
    }
    
    // 获取排行
    public function getrank(){

        $limit1 = Input::get('limit1');
        $limit2 = Input::get('limit2');

        $obj = new UserDetail();
        $sum = $obj->count();
        
        $res = [];
        // 积分
        $res[0] = $obj->select('name','point')->orderBy('point','desc')->limit($limit1)->get()->toArray();
        
        foreach ($res[0] as $k => $v) {

            $res[0][$k]['percent'] = round((($sum-$k)/$sum)*100,2).'%';
        }


        // 经验
        $res[1] = $obj->select('name','experience')->orderBy('experience','desc')->limit($limit2)->get()->toArray();

        foreach ($res[1] as $k => $v) {

            $res[1][$k]['percent'] = round((($sum-$k)/$sum)*100,2).'%';
        }

        return Response::json($res);
    }

    // 获取消费能力
    public function getability(){

        $obj = new PointLogs();

        $res = [];

        $total = $obj->sum('cpoint');

        $res[0] = -$total;

        // $total1 = DB::select("SELECT sum(cpoint) FROM user_point_detail group by user_id ");

        // $a = DB::table('user_point_detail')
        //         ->select('user_id', DB::raw('SUM(cpoint) as total_sales'))
        //         ->groupBy('user_id')
        //         ->havingRaw('SUM(cpoint) < -1')
        //         ->get();
        
        $res[5] = count($obj->select('user_id')->groupBy('user_id')->havingRaw('SUM(cpoint) > '.$total * 0.6.'')->get()->toArray());
        $res[6]= count($obj->select('user_id')->groupBy('user_id')->havingRaw('SUM(cpoint) < '.$total * 0.6.'')->havingRaw('SUM(cpoint) > '.$total * 0.7.'')->get()->toArray());
        $res[7] = count($obj->select('user_id')->groupBy('user_id')->havingRaw('SUM(cpoint) < '.$total * 0.7.'')->havingRaw('SUM(cpoint) > '.$total * 0.8.'')->get()->toArray());
        $res[8] = count($obj->select('user_id')->groupBy('user_id')->havingRaw('SUM(cpoint) < '.$total * 0.8.'')->havingRaw('SUM(cpoint) > '.$total * 0.9.'')->get()->toArray());
        $res[9] = count($obj->select('user_id')->groupBy('user_id')->havingRaw('SUM(cpoint) < '.$total * 0.9.'')->get()->toArray());

        return Response::json($res);
    }

    // 获取男女比例
    public function getsex(){

        $obj = new UserDetail();

        $res = [];

        $res[0] = $obj->where('sex',1)->where('name','!=','')->count();
        $res[1] = $obj->where('sex',2)->where('name','!=','')->count();

        return Response::json($res);
    }

    // 获取增减趋势
    public function gettrend(){

        $obj = new PointLogs();

        // $one = strtotime('last Monday');
        // $two = strtotime('last Tuesday');
        // $three = strtotime('last Wednesday');
        // $four = strtotime('last Thursday');
        // $five = strtotime('last Friday');
        // $six = strtotime('last Saturday');
        // $seven = strtotime('last Sunday');
        $one = strtotime("-7 day");
        $two = strtotime("-6 day");
        $three = strtotime("-5 day");
        $four = strtotime("-4 day");
        $five = strtotime("-3 day");
        $six = strtotime("-2 day");
        $seven = strtotime("-1 day");
        $thisone = time();

        $res = array();

        $total = $obj->sum('cpoint');

        $res[2] = -$total;
        // 积分
        $res[0][1] = $obj->where('datetime','>',$one)->where('datetime','<',$two)->sum('cpoint');
        $res[0][2] = $obj->where('datetime','>',$two)->where('datetime','<',$three)->sum('cpoint');
        $res[0][3] = $obj->where('datetime','>',$three)->where('datetime','<',$four)->sum('cpoint');
        $res[0][4] = $obj->where('datetime','>',$four)->where('datetime','<',$five)->sum('cpoint');
        $res[0][5] = $obj->where('datetime','>',$five)->where('datetime','<',$six)->sum('cpoint');
        $res[0][6] = $obj->where('datetime','>',$six)->where('datetime','<',$seven)->sum('cpoint');
        $res[0][7] = $obj->where('datetime','>',$seven)->where('datetime','<',$thisone)->sum('cpoint');

        // 经验
        $res[1][1] = $obj->where('datetime','>',$one)->where('datetime','<',$two)->sum('experience');
        $res[1][2] = $obj->where('datetime','>',$two)->where('datetime','<',$three)->sum('experience');
        $res[1][3] = $obj->where('datetime','>',$three)->where('datetime','<',$four)->sum('experience');
        $res[1][4] = $obj->where('datetime','>',$four)->where('datetime','<',$five)->sum('experience');
        $res[1][5] = $obj->where('datetime','>',$five)->where('datetime','<',$six)->sum('experience');
        $res[1][6] = $obj->where('datetime','>',$six)->where('datetime','<',$seven)->sum('experience');
        $res[1][7] = $obj->where('datetime','>',$seven)->where('datetime','<',$thisone)->sum('experience');

        return Response::json($res);
    }

    // 级别统计
    public function getgrade(){

        $obj1 = new UserDetail();
        $obj2 = new UserGrade();

        $res = $obj2->select('id','title')->get()->toArray();

        $count = array();
        foreach ($res as $k => $v) {
            $data['count'][$k] = $obj1->where('grade_id',$v['id'])->count();
            $data['title'][$k] = $v['title'];
        }

        return Response::json($data);
    }

}