<?php

namespace Weitac\User\Http\Controllers;
use Weitac;
use DB;
use Auth;
use Session;
use Response;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\Usertable as Usertable;
use Weitac\User\Http\Models\UserDetail as UserDetail;
use Weitac\User\Http\Models\FrontUserpd as FrontUserpd;
use Illuminate\Support\Facades\Input;

class UsertableController extends AdminController {
	//设置页条数5条
	private $pagesize = 5, $upload_max_filesize;
	

    /**
     * 显示用户列表
     */
    public function index() {
        return view('user::usertable.index');
    }

    /**
     * 
     * @return type
     */
    public function ajaxIndex() {
        $content = new Usertable;
        $where = array();

		if (Input::has('search')) {
            if (Input::get('search')) {
                $where['name like '] = "%" . Input::get('search') . "%";
            }
        }
		
        $where['token = '] = Weitac::getToken();  //当前栏目token

        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;
        $obj = $content->setWhere($where);
        if (isset($_GET['sort']) && isset($_GET['order'])) {
            $res = $obj->orderBy($_GET['sort'], $_GET['order'])->select('id', 'activityid', 'token', 'openid', 'time', 'integral','describe','periodtime','name')->paginate(Input::get('limit'))->toArray();
        } else {
            $res = $obj->paginate($Input::get('limit'))->toArray();
        }

        //循环遍历数据将数据库中的时间戳，转换成Y-m-d H:i:s的形式
        foreach ($res['data'] as $k => &$v) {
			$userinfo = $this->_getNameHead($v['openid']);
			$res['data'][$k]['openid'] = '<img src="' . WWW_URL . $userinfo['head_picture'] . '" style="width:36px;height:36px;"/>';
			//$res['data'][$k]['name'] = $userinfo['name'];
			$res['data'][$k]['name'] = $v['name'];
			$res['data'][$k]['time'] = date("Y-m-d H:i:s", $v['time']);	
			$res['data'][$k]['periodtime'] = $this->_pingbi($v['periodtime']);
        }
		//dd($res);
        return Response::json($res);
    }
	
	/**
     *  根据获得的openid在user_detail表里找出该用户，并得到该用户的微信名字等
     *  return $arr
     */
    private function _getNameHead($openid)
    {
        $data = DB::table('user_detail')->where('openid', '=', $openid)->select('name','head_picture')->first();
        if(empty($data)){
            return array('name'=>'无昵称','head_picture'=>'#');

        }

        $data = get_object_vars($data); //对象转数组

        if ($data['name'] == null) {
            $data['name']='无昵称';
        }

        if ($data['head_picture'] == null) {
             $data['head_picture']='#';
        }

        //dd($data);
        return $data;
    }
	
	/**
     *  根据获得的periodtime在user_shield表里找出该数据id，并得到该数据的periodtime名字等
     *  return $arr
     */
    private function _pingbi($periodtime)
    {
        switch ($periodtime) {
            case 0:
                return '不屏蔽';
                break;
            case 1:
                return '一天';
                break;
            case 2:
                return '一周';
                break;
            case 3:
                return '一月';
                break;
        }
    }
	
	/*
	删除操作
	*/
	public function delete(){
		$ids=Input::get('ids');
		$ids2=substr($ids,0,-1);  //去掉最后的一个逗号
		$idsToArr=explode(",", $ids2);  //获取ids组成的数组
		
		$obj=new Usertable();  //实例化model对象
		$result=$obj->del($idsToArr);
		
		return Response::json($result);
	}
	
	//-------------------------------以下为手动为用户添加积分页面内容和屏蔽--------------------------------------------------------
	/**
     * 单个数据：手动为用户添加积分和屏蔽
     * @return type
     */
	public function send() {
		$huodongtoken = Input::get('token');
		$huodongopenid = Input::get('openid');
		$idtime = Usertable::whereRaw('token = ? and openid = ?',array($huodongtoken,$huodongopenid))->orderBy('id', 'desc')->select('periodtime','time')->first();
		//dd($idtime->periodtime,$idtime->time);
		$bianlis = array(
			'huodongtoken' => $huodongtoken,
			'huodongopenid' => $huodongopenid,
			'yemian' => 0
		);
		$yemians = array(
			'huodongtoken' => $huodongtoken,
			'huodongopenid' => $huodongopenid,
			'yemian' => 1
		);
		//----------------6.23修改屏蔽方法开始-------------------
		if($idtime == null || $idtime->periodtime == 0){
			return view('user::usershield.send',$bianlis);
		}elseif($idtime->periodtime == 1){
			$start = $idtime->time;
			$end = date("Y-m-d H:i:s",strtotime("+1 day",$start));
			$ends = strtotime("+1 day",$start);
			$time = time();
			//dd(date("Y-m-d H:i:s",$start),$end,$time);
			if($start < $time && $time <= $ends){
				//屏蔽
				return view('user::usershield.send',$yemians);
			}else{
				//不屏蔽
				return view('user::usershield.send',$bianlis);
			}
		}elseif($idtime->periodtime == 2){
			$start = $idtime->time;
			$end = date("Y-m-d H:i:s",strtotime("+1 week",$start));
			$ends = strtotime("+1 week",$start);
			$time = time();
			//dd(date("Y-m-d H:i:s",$start),$end,$time);
			if($start < $time && $time <= $ends){
				//屏蔽
				return view('user::usershield.send',$yemians);
			}else{
				//不屏蔽
				return view('user::usershield.send',$bianlis);
			}
		}else{
			$start = $idtime->time;
			$end = date("Y-m-d H:i:s",strtotime("+1 month",$start));
			$ends = strtotime("+1 month",$start);
			$time = time();
			//dd(date("Y-m-d H:i:s",$start),$end,$time);
			if($start < $time && $time <= $ends){
				//屏蔽
				return view('user::usershield.send',$yemians);
			}else{
				//不屏蔽
				return view('user::usershield.send',$bianlis);
			}
		}
		//----------------6.23修改屏蔽方法结束-------------------
    }
	
	public function postsend(){
		//--------------------------------------------入库部分开始
		$data = Input::except('/admin/user/UsertableController/postsend','_token');
		if(isset($data['integral'])){
		
			$userinfo = $this->_getNameHead($data['huodongopenid']);
			$datas = array(
				'activityid' => 0,
				'openid' => $data['huodongopenid'],
				'token' => $data['huodongtoken'],
				'time' => time(),
				'integral' => $data['integral'],
				'describe' => $data['describe'],
				'periodtime' => $data['periodtime'],
				'name' => $userinfo['name'],
				
			);
			//dd($datas);
			$obj = new Usertable;
			$return = $obj->check($datas);
			if ($return['status']) {
				$return = $obj->add($datas);
			}
			//入库部分结束
			
			//---------------------------------------关联积分规则开始【这里居然直接 添加入积分记录表 了】
			$userid = UserDetail::where('openid',$data['huodongopenid'])->pluck('user_id');
			if($userid){
				$keyword = $data['integral'];
				Weitac::point($userid,$keyword);	
			}
			//关联积分规则结束

		}else{
			return array('status'=>false,'msg'=>'好吧！');
		}
		
        return Response::json($return);
	}
	
	/**
     * 多个数据：手动为用户添加积分和屏蔽
     * @return type
     */
	public function sendes() {
		$token = Weitac::getToken();
		$bianlis = array(
			'huodongid' => '',
			'huodongtoken' => $token,
			'huodongopenid' => '',
			'yemian' => 0
		);
		return view('user::usershield.send',$bianlis);
		
	}
	
	public function postsendes() {
		//-----区分出：还没有屏蔽过和屏蔽已经过期的用户，给他们进行入库等操作开始（屏蔽期还没有过的不再进行入库等以下操作）
		$data = Input::except('/admin/user/UsertableController/postsendes','_token');
		
		$ids = Input::get('ids');
		$idarr = explode(',',$ids);
		$token = Weitac::getToken();
		
		$openids = array();
		foreach($idarr as $k=>$v){
			$openid = DB::table('wx_content')->where('id' , $v)->where('token',$token)->pluck('FromUserName');
			
			$ss = Usertable::where('token','=',$token)->get()->toArray();
			if($ss){
				foreach($ss as $kss=>$vss){
					$gun[] = $vss['openid'];
				}
			}else{
				$gun[] = "oK8eft8Xl97WZMG2VPLeKJJOqccU665544332211";
			}
			if(!in_array($openid,$gun)){
				$none[] = $openid;
			}
			
			$openids[] = $openid;
		}
		
		//----------------只是针对于没有进过屏蔽表的用户 入库部分开始
		if(isset($none)){
			$nones = array_unique($none);
			
			foreach($nones as $nok=>$nov){
				//dd($nones,$nok,$nov,$nones[$nok]);
				//----------------只是针对于没有进过屏蔽表的用户 入库部分开始
				$userinfo = $this->_getNameHead($nones[$nok]);
				$datas[$nok]['activityid'] = "";
				$datas[$nok]['openid'] = $nones[$nok];
				$datas[$nok]['token'] = $token;
				$datas[$nok]['time'] = time();
				$datas[$nok]['integral'] = $data['integral'];
				$datas[$nok]['describe'] = $data['describe'];
				$datas[$nok]['periodtime'] = $data['periodtime'];
				$datas[$nok]['name'] = $userinfo['name'];
				//入库分装部分结束（最后一句入库在方法后几行）

				//-----------------------关联积分规则开始【这里居然直接 添加入积分记录表 了】
				$userid = UserDetail::where('openid',$nones[$nok])->pluck('user_id');
				if($userid){
					$keyword = $data['integral'];
					Weitac::point($userid,$keyword);	
				}
				//关联积分规则结束
			}
			
		}
		$openides = array_unique($openids);
		foreach($openides as $usk=>$usv){
			$usid[] = DB::table('user_table')->where('openid',$usv)->select('openid','periodtime','time')->orderBy('id', 'desc')->first();
		}
		//dd($usid);
		foreach($usid as $kk=>$vv){
			if(!empty($vv)){
				//----------------6.23修改屏蔽方法开始-------------------
				if($vv->periodtime == 0){
					$start[$kk] = '1451577600';
					$end[$kk] = '1451577600';
					$time = time();
				}elseif($vv->periodtime == 1){
					$start[$kk] = $vv->time;
					$end[$kk] = strtotime("+1 day",$start[$kk]);
					$time = time();
				}elseif($vv->periodtime == 2){
					$start[$kk] = $vv->time;
					$end[$kk] = strtotime("+1 week",$start[$kk]);
					$time = time();
				}else{
					$start[$kk] = $vv->time;
					$end[$kk] = strtotime("+1 month",$start[$kk]);
					$time = time();
				}
				//dd(date("Y-m-d H:i:s",$start[$kk]),date("Y-m-d H:i:s",$end[$kk]),$time);
				//----------------6.23修改屏蔽方法结束------------------------
				//-------------------在此处：进了屏蔽表并且屏蔽期还没有过的用户过滤
				if($start[$kk] > $time && $end[$kk] > $time || $start[$kk] < $time && $end[$kk] < $time){
					//----------------只是针对于进了屏蔽表，但屏蔽期已过的用户 入库部分开始
					$userinfo = $this->_getNameHead($vv->openid);
					$datas[$kk]['activityid'] = "";
					$datas[$kk]['openid'] = $vv->openid;
					$datas[$kk]['token'] = $token;
					$datas[$kk]['time'] = time();
					$datas[$kk]['integral'] = $data['integral'];
					$datas[$kk]['describe'] = $data['describe'];
					$datas[$kk]['periodtime'] = $data['periodtime'];
					$datas[$kk]['name'] = $userinfo['name'];
					//入库分装部分结束（最后一句入库在方法后几行）

					//-----------------------关联积分规则开始【这里居然直接 添加入积分记录表 了】
					$userid = UserDetail::where('openid',$vv->openid)->pluck('user_id');
					if($userid){
						$keyword = $data['integral'];
						Weitac::point($userid,$keyword);	
					}
					//关联积分规则结束
				}
			}
		}
		if(isset($datas)){
			$result = DB::table('user_table')->insert($datas);
			$result = array('status'=>true,'msg'=>'送积分成功');
		}else{
			$result = array('status'=>false,'msg'=>'送积分失败');
		}
		
		return Response::json($result);
	}
	
	
	
	
	
	
	
}
