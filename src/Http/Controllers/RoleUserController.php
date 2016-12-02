<?php

/**
 * 	角色
 * 
 * 	@author		tyh
 * @date			2015-09-26
 * @version	2.0
 */
namespace Weitac\User\Http\Controllers;

use Auth;
use Response;
use Session;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\PackageModule;
use Illuminate\Support\Facades\Input;
//角色Models
use Weitac\User\Http\Models\RoleUser as RoleUser;
use Illuminate\Support\Facades\DB;
use Weitac\User\Http\Models\Aca as Aca;

 
class RoleUserController extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
    }


    /**
     * 
     * 权限列表
     */
    public function index() {
        return view('user::roleuser.index');
    }
	
	//获取权限列表
	public function ajaxindex(){
		$content = new RoleUser();
		 $where = array();

        //条件
        if (Input::has('name')) {
            if (Input::get('name')) {
                $where['name like '] = "%" . Input::get('name') . "%";
            }
        }

        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;

        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize;

        $obj = $content->setWhere($where);

        if (isset($_GET['orderby'])) {
            $order = explode('|', $_REQUEST['orderby']);
            $res = $obj->orderBy($order[0], $order[1])->paginate($pagesize)->toArray();
        } else {
            $res = $obj->paginate($pagesize)->toArray();
        }
			
		return Response::json($res);
	}
	
	//添加
	public function add(){
		
		 return view('user::roleuser.add');
	}
	
	public function insert(){
		$data['name'] = Input::get('name');
		$name = DB::table('role')->where('name',$data['name'])->get();
		if(empty($name)){
			$data['description'] = Input::get('description');
			$datest= Input::get('aca');
			//名字  简介存入一张表  
			$data['created_at'] =date('Y-m-d',time());
			$data['updated_at'] =date('Y-m-d',time());
			$data['system']  = 0;
			$obj = new RoleUser();
			$role_id = $obj->insertGetId($data);
			//查询当前角色权限是否存在
			$aca_role = DB::table('role_aca')->where('role_id',$role_id)->get();
			if(!empty($aca_role)){
				//如果不为空则删除所有该角色ID
				DB::table('role_aca')->where('role_id',$role_id)->delete();
			}
			//角色权限存入另外一张表role_id 为关联的字段
			$aca_id = explode('|',$datest);
			array_pop($aca_id);
			if(!empty($aca_id)){
				foreach($aca_id as $k=>$v){
					$role_aca[$k]['role_id'] = $role_id;
					$role_aca[$k]['aca_id'] = $v;
					$role_aca[$k]['condition'] = 0;
				}
				//角色权限附件表
				$res =DB::table('role_aca')->insert(($role_aca));
				if($res){
					$return =array(
							'status'=>true,
							'msg'=>'创建成功'
					);
				
				}else{
					$return =array(
							'status'=>false,
							'msg'=>'创建失败'
					);
				
				}
			}else{
				$return = array(
					'status'=>true,
					'msg'=>'创建成功'
				);
			}
		}else{
			$return = array(
				'status'=>false,
				'msg'=>'角色已存在'
			);
		}
		
        return Response::json($return);
	}
	
	//修改
	public function edit(){
		$id = Input::get('role_id');

		//调用方法：非admin帐号不允许修改admin信息
        $edit_result=$this->_isCanEdit($id);
        if($edit_result['status']==false){
             return Response::json($edit_result);
        }
        //调用方法：非admin帐号不允许修改admin信息

		//先去原表里找到标题 简介
		$data = RoleUser::find($id)->toArray();
        $data = array('data' => $data);
		return Response::json($data);
	}
	
	//附件表里找到相对应的权限
	public function roleidaca(){
		//所有的数据
		$id = Input::get('role_id');

		//调用方法：非admin帐号不允许修改admin信息
        $edit_result=$this->_isCanEdit($id);
        if($edit_result['status']==false){
             return Response::json($edit_result);
        }
        //调用方法：非admin帐号不允许修改admin信息

		$obj = new Aca;
		$role_aca =$obj->whereRaw('parent_id  =? and status =?',array('0',1))->select('remark','aca_id')->get()->toArray();
		//角色权限表的数据
		$aca_id = DB::table('role_aca')->where('role_id',$id)->get();
		$user_arr = '';
		/* //将字符串拆分成数组
		if($aca_id){
			$user_aca = rtrim($aca_id, '|');
			$user_arr = explode('|',$user_aca);		
		} */
		
		if($aca_id){
			foreach($aca_id as $k=>$v){
				$user_arr[] = $v->aca_id;
			}
		}
		
		//开始遍历数据
		$rs = array();
		foreach ($role_aca as $k => $v) {
			$select = FALSE;
			//判断是否在数据里面
			if ($user_arr) {
				if (in_array($v['aca_id'], $user_arr)) {
					$select = TRUE;
				}
			}
			if(!empty($v['aca_id'])) {
                $is_ok = Aca::where('parent_id', $v['aca_id'])->count();
				
                if ($is_ok) {
                    $rs['data'][$k] = array('name' => $v['remark'], 'type' => 'folder', 'additionalParameters' => $this->_acarid($v['aca_id'], $user_arr));
                } else {
                    $rs['data'][$k] = array('name' => $v['remark'], 'type' => 'item', 'parentid' => $v['aca_id'], 'selected' => $select);
                }
			}
        }
        $rs['status'] = true;
        return Response::json($rs);
    }
	
	
	private function _acarid($catid, $user_arr)
    {
        $data = array();
        $catgorys = Aca::where('parent_id', $catid)->select('aca_id', 'remark','parent_id')->get()->toArray();
		
        foreach ($catgorys as $k => $v) {

            $select = FALSE;
		//根据选择进行判断是否为真
			if($user_arr){
				if(in_array($v['aca_id'],$user_arr)){
					$select = TRUE;
				}
			}
            $is_ok = Aca::where('parent_id', $v['aca_id'])->count();
            if ($is_ok) {
                $data['children'][] = array('name' => $v['remark'], 'type' => 'folder', 'additionalParameters' => $this->_acarid($v['aca_id'], $user_arr));
            } else {
                $data['children'][] = array('name' => $v['remark'], 'type' => 'item', 'parentid' => $v['aca_id'], 'selected' => $select);
				
			}
        }
		
        return $data;
    }
	
	
	//修改保存
	public function update(){
		$date = Input::except('/admin/core/user/roleuser/update','_');
		
		//调用方法：非admin帐号不允许修改admin信息
        $edit_result=$this->_isCanEdit($date['role_id']);
        if($edit_result['status']==false){
             return Response::json($edit_result);
        }
        //调用方法：非admin帐号不允许修改admin信息


		//开始更新数据
		$data  =array(
			'name' =>$date['name'],
			'description' =>$date['description'],
			'role_id' =>$date['role_id'],
			'updated_at' =>date('Y-m-d',time()),
		);
		$obj = new RoleUser();
		$return = $obj->check($data,'edit');
        if ($return['status']) {
            $ret= $obj->edits($data);
        }
		$role_aca = explode('|',$date['aca']);
		array_pop($role_aca);
		$count = DB::table('role_aca')->where('role_id',$date['role_id'])->count();
		if($count !=0){
			DB::table('role_aca')->where('role_id',$date['role_id'])->delete();
		}
		if(!empty($role_aca)){
			foreach($role_aca as $k=>$v){
				$rol_aca[$k]['role_id'] = $date['role_id'];
				$rol_aca[$k]['aca_id'] = $v;
				$rol_aca[$k]['condition'] = 0;
			}
			$res =DB::table('role_aca')->insert($rol_aca);
			
			if($res || $ret){
				$return =array(
						'status'=>true,
						'msg'=>'修改成功'
				);
			
			}else{
				$return =array(
						'status'=>false,
						'msg'=>'修改失败'
				);
			}
		}else{
			$return =array(
					'status'=>false,
					'msg'=>'修改失败'
			);
		}
		
		return Response::json($return);
	}
	
	
	//删除权限
	public function del(){
		$role_id = Input::get('role_id');

		//调用方法：非admin帐号不允许修改admin信息
        $edit_result=$this->_isCanEdit($role_id);
        if($edit_result['status']==false){
             return Response::json($edit_result);
        }
        //调用方法：非admin帐号不允许修改admin信息

		$result = DB::table('role_aca')->where('role_id',$role_id)->get();
		if(!empty($result)){
			//如果有数据则删除
			DB::table('role_aca')->where('role_id',$role_id)->delete();
			//删除角色表
			DB::table('role')->where('role_id',$role_id)->delete();
		}else{
			//删除角色表
			DB::table('role')->where('role_id',$role_id)->delete();
		}
		return Response::json(array('status'=>true, 'msg'=>'删除成功'));
    }

	//获取aca的扩展包的目录结构
	public function role_aca(){
		
		 $obj = new Aca;
		 //开始找出表里的目录结构	
		$role_aca = $obj->whereRaw('parent_id  =? and status =?',array('0',1))->select('remark','aca_id','package')->get()->toArray();
		//开始遍历数据
		$rs = array();
		$user_arr = array();
		foreach($role_aca as $k=>$v){
			
			$rs['data'][$k] = array(
					'name'=>$v['remark'],
					'type' =>'folder',
					'additionalParameters'=>$this->_getMenu($v['aca_id'],$user_arr)
					
				);

		}	
	
		if($rs !=null){
		
			//返回数据$rs
				$rs['status'] = TRUE;
				return Response::json($rs);
		}	
				
		}


	private function _getMenu($id,$user_arr){
		//一个空的数组		
		$data = array();
		$obj = new Aca;
		$aca_ro = $obj->where('parent_id',$id)->select('aca_id','remark')->get()->toArray();
		
		//foreach  循环遍利	
		foreach($aca_ro as $k=>$v){			
			$select = FALSE;	
			$is_ok = Aca::where('parent_id',$v['aca_id'])->count();
			if($is_ok){
				$data['children'][] = array(
				$id = $v['aca_id'],
				'name' =>$v['remark'],
				'type'=>'folder',
				'additionalParameters'=>$this->_getMenu($v['aca_id'],$user_arr));
			}else{
				$data['children'][] =array(
				'name' =>$v['remark'],
				'type'=>'item',
				'parentid'=>$v['aca_id'],
				'selected'=>$select);
		
			}	
		}
			return $data;
	}	



    /*
       本方法是控制非admin管理员帐号不允许修改admin信息。
        xgh  2016-01-29
     */
    public function _isCanEdit($user_id){
        $Session_user_id = Session::get('admin.user.user_id'); 
        // 非admin帐号，不允许修改admin信息  2016-01-29
        if($Session_user_id != 1 ){
            // 当前修改的是admin，就直接提示研发帐号不允许修改
            if($user_id ==1){
                 return array('status' => FALSE, 'msg' => '研发专用帐号不允许操作');
            }else{
                 return array('status' => true, 'msg' => '可以操作');
            }

        }else{
             return array('status' => true, 'msg' => '可以操作');
        }
        
        // 非admin帐号，不允许修改admin信息 end

    }
		
		
		
		
		
}