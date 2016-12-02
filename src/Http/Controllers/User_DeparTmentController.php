<?php
/**
 * 部门  
 * @author    wpz
 * @date	2015-03-20
 * @version	2.0
 */

namespace Weitac\User\Http\Controllers;

use Auth;
use Response;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\User_DeparTment as User_DeparTment;
use Weitac\User\Http\Models\User_WxResponse as User_WxResponse;
use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Support\Facades\DB;

class User_DeparTmentController extends AdminController {

    private $pagesize = 15, $upload_max_filesize;

    public function __construct() {
       // parent::__construct();
        //$this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * 
     * @return type
     */
    public function index() {
        return View('user::bumen.index');
    }

    /**
     * 取得列表数据
     * @return type
     */

	public function ajaxIndex() {
	
		//查询栏目表的字段 查询出 menuid  parentid  name  childids等字段 并且将其转为数组
		$menus = User_DeparTment::where('parentid',0)->orderBy('sort','asc')->get()->toArray();
	
		$rs = array();
		//进行foreach循环输出第一层目录
		if(!empty($menus)){
			foreach($menus  as $k => $v){
			//进行判断目录		
				$name = '<span onmousedown="right('.$v["id"].')">'.$v['name'].'</span>';
				$rs['data'][] = array(
					//'name' =>$name . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='daoru(".$v['id'].")'>导入成员列表</a>",
					'name' =>$name,
					'type'=>'folder',
					'additionalParameters'=>$this->getMenu($v['id'])
				);
			}
			//dd($rs['data']);
		}
		$rs['status'] = TRUE;
		//返回数据$rs      
		return Response::json($rs);
	}
		
		/*	
		子栏目
	*/
	private function getMenu($id){
		//一个空的数组
		$data = array();
		//查询menu表   根据条件获取一些值 menuid   name  
		$menus = User_DeparTment::where('parentid',$id)->orderBy('sort','asc')->get()->toArray();
		//dd($menus);
		//foreach  循环遍利	
		foreach($menus as $k=>$v){
			$select = FALSE;
			$is_ok = User_DeparTment::where('parentid',$v['id'])->count();
			$name = '<span onmousedown="right('.$v["id"].')">'.$v['name'].'</span>';
			if($is_ok){
				$data['children'][] = array(
					//'name' =>$name. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='daoru(".$v['id'].")'>导入成员列表</a>",
					'name' =>$name,
					'type'=>'folder',
					'additionalParameters'=>$this->getMenu($v['id'])
				);
			}else{
				$data['children'][] =array(
					//'name' =>$name. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='daoru(".$v['id'].")'>导入成员列表</a>",
					'name' =>$name,
					'type'=>'item',
					'parentid'=>$v['id'],
					'selected'=>$select
				);
			}	
		}
		return $data;
	}

    //添加
    public function add() {
        $menus = User_DeparTment::where('parentid',0)->get();
        $dara = array();
        $data['pids'] = array();
        foreach($menus as $v){
            $is_ok = User_DeparTment::where('parentid',$v->id)->count();
            //dd($is_ok);
			$data['pids'][$v->id] =$v->name;
            if($is_ok){
				$data['pids'][] = $this->getAll($data,$v->id,'--');
            }
        } 
        
		//dd($data);
        return View('user::bumen.add',$data);
    }
    
    protected function getAll(&$data, $id, $separate = '',$sid = '')
    {
        $menus = User_DeparTment::where('parentid',$id)->get();
	
        foreach ($menus as $k => $v) {
			if($v['id'] != $sid){
				$data['pids'][$v->id] = $separate .$v->name;
				$is_ok = User_DeparTment::where('parentid',$v['id'])->count();
				if($is_ok){
					$this->getAll($data, $v->id, $separate . "--",$sid);
				}
			}
        }
    }
	
    public function insert() {
        $data = Input::except('_token', '/admin/user/User_DeparTmentController/insert');
		//dd($data);
		$res = User_DeparTment::insert($data);
        if (!$res) {
            $obj = new User_DeparTment;
            $return = $obj->check($data, 'add');
            if ($return['status']) {
                $return = $obj->add($data);
            }
        } else {
            $return = array('status' => TRUE, 'msg' => "添加成功");
        }

        return Response::json($return);
		
    }
	
    public function edit() {
		$id = Input::get('id');
        $return = User_DeparTment::find($id);
        $data['id'] = $return['id'];
        $data['parentid'] = $return['parentid'];
        $data['sort'] = $return['sort'];
        $data['name'] = $return['name'];
        $menus = User_DeparTment::where('parentid',0)->get();
        foreach($menus as $v){
			if($v->id != $id){
				$is_ok = User_DeparTment::where('parentid',$v->id)->count();
				$data['pids'][$v->id] = $v->name;
				if($is_ok){
					$data['pids'][] = $this->getAll($data,$v->id,'--',$id);	
				}
			}else{
				$data['pids'][] = $this->getAll($data,$v->id,'--',$id);
			}
        }
		//dd($data);
        return View('user::bumen.edit', $data);
    }
	 /**
     * 保存修改
     * @return type
     */
    public function update() {
        $data = Input::except('_token', '/admin/user/User_DeparTmentController/update');
        $obj = new User_DeparTment;
        $result = $obj->check($data);
        if ($result['status'] == true) {
            $result = $obj->edit($data);
        }
        return Response::json($result);
    }

    public function delete() {
        $id = Input::get('id');
        $obj = new User_DeparTment;
        return Response::json($obj->del($id));
    }
	//导入成员列表
	public function daoru(){
		$id = Input::get('aid');
		$acc_token= new User_WxResponse();
		$access = $acc_token->get_token();
		$url ="https://qyapi.weixin.qq.com/cgi-bin/user/list?access_token=$access&department_id=$id&fetch_child=1&status=0";
		$type = 'get';
		$result = $acc_token->_sendHttps($url,$type);
		$res =json_decode($result,true,JSON_UNESCAPED_UNICODE);
		if($res['errcode']!==0){
			$return = array(
				'status'=>False,
				'msg'=>'错误代码'.$res['errcode']
			);
			return Response::json($return);
		}else{
			//dd($res);department目前从0开始取得...
			$date =array();
			foreach($res['userlist'] as $k=>$v){
				if(array_key_exists('extattr',$v)){
					$v['extattr']=json_encode($v['extattr'],JSON_UNESCAPED_UNICODE);
				}else{
					$v['extattr']=json_encode('');
				}
				if(array_key_exists('department',$v)){
					$v['department'] = $v['department']['0'];
				}
				$date[$k]=$v;
			}
			$user = new OA_TongXunLu();
			//遍历插入数据
			foreach($date  as $k=>$v){
				//$datt['userid']=$v['userid'];
				$obj = $user->where('userid','=',$v['userid'])->pluck('id');
				if($obj==null){
					$rees = $user->insert($date[$k]);		
				}else{					
					$res = $user->where('userid','=',$v['userid'])->update($date[$k]);
				}
			}
			if($res){
				$return = array('status' => FALSE, 'msg' => '失败');
			}else{
				$return = array('status' => TRUE , 'msg' => '成功');
			}
			return Response::json($return);	
		}
	}
	
	//同步部门的数据结构组织
	public function parentitb(){
		$acc_token= new User_WxResponse();
		$access = $acc_token->get_token();
		$url="https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=$access";		
		$type = 'get';
		$result = $acc_token->_sendHttps($url,$type);
		//解析成数组  拿到所需的东西 
		$res = json_decode($result, true);
		if($res['errcode']!==0){
			$return = array(
				'status'=>False,
				'msg'=>'错误代码'.$res['errcode']
			);
			return Response::json($return);		
		}else{
			$datest=array();
			foreach($res['department'] as $k=>$v){
				$datest[$k]['id'] =$v['id'];
				$datest[$k]['parentid'] =$v['parentid'];
				$datest[$k]['name'] =$v['name'];
				$datest[$k]['parentids'] =$v['parentid'];
				$datest[$k]['order'] =$v['order'];									
			}
			$obj = new User_DeparTment();
			$dat = DB::table('user_bumen')->delete();
			$res =$obj->insert($datest);
			if($res){
				$return = array('status'=>TRUE,'msg'=>"同步成功");
			}else{
				$return = array('status'=>TRUE,'msg'=>"同步失败");
			}
			return Response::json($return);
		}
		
	}
}
