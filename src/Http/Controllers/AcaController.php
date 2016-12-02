<?php

/**
 * 	权限
 * 
 * 	@author		wpz
 * @date			2015-09-24
 * @version	2.0
 */
namespace Weitac\User\Http\Controllers;

use Auth;
use Response;
use DB;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\PackageModule;
use Weitac\User\Http\Models\Aca;
use Illuminate\Support\Facades\Input;
 
class AcaController extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * 
     * 权限列表
     */
    public function index() {
        return view('user::aca.index');
    }
	
	//获取权限列表
	public function ajaxindex(){
		$obj = new Aca();
		
		$data = $this->cycle();
		
		return Response::json(json_encode($data));
	}
	
	//添加
	public function add(){
		$classify = Aca::where('parent_id','0')->get()->toArray();
		$array = array();
		if(!empty($classify)){
			foreach($classify as $k=>$v){
				$array[] = "<option value='".$v['aca_id']."'>". $v['remark'] ."</option>";
			}
		}
		return Response::json($array);
	}
	
	//获取小类
	public function sid(){
		$id = Input::get('id');
		$classify = $this->classify($id);
		return Response::json($classify);
	}
	
	public function insert(){
		$data = Input::except('/admin/core/user/aca/insert');
		if($data['sid'] !=""){
			$data['parent_id'] = $data['sid'];
			unset($data['sid']);
		}else{
			unset($data['sid']);
		}
		$baoming = DB::table('aca')->where('remark',$data['remark'])->get();
		$action ="";
		if(!empty($date['action'])){
			$action = DB::table('aca')->where('action',$data['action'])->get();
		}
		if(!empty($baoming)){
			$return = array('status'=>false, 'msg'=>'描述已存在');
		}elseif(!empty($action)){
			$return = array('status'=>false, 'msg'=>'路由已存在');
		}else{
			$data['status'] = 1;
			$obj = new Aca();
			$return = $obj->check($data,'add');
			if ($return['status']) {
				$return = $obj->adds($data);
			}
		}
        return Response::json($return);
	}
	
	
	//循环获取全部
	private function cycle($parent_id = 0){
		$array = array();
		$data = Aca::where('parent_id',$parent_id)->get()->toArray();
		foreach($data as $k=>$v){
			$array[$k]['text'] = $v['remark'];
			$array[$k]['id'] = $v['aca_id'];
			$array[$k]['parent_id'] = $v['parent_id'];
			$is_ok = Aca::where('parent_id',$v['aca_id'])->count();
			if($is_ok){
				$array[$k]['nodes'] = $this->cycle($v['aca_id']);
			}
		}
		return $array;
	}
	
	//修改
	public function edit(){
		$id = Input::get('id');
		$obj = new Aca();
		
		//获取修改的值
		$data = $obj->where('aca_id',$id)->first();
		
		//$data['action']= strtok($data['action'],'.');
		// if($data['action'] != ''){
			// // $aa= explode('.',$data['action']);
			// // $data['action'] = $aa['1'];
		// }else{
			// $data['action'] = '';
		// }
		
		$classi = Aca::where('parent_id','0')->get()->toArray();
		if($data['parent_id'] !=0){
			$did = $this->getDid($data['parent_id']);
		}else{
			$did = $data['parent_id'];
		}
		 
		foreach($classi as $k=>$v){
			if($did == $v['aca_id']){
				$aca[] = "<option value='".$v['aca_id']."' selected=\"selected\" >". $v['remark'] ."</option>";
			}
			$aca[] = "<option value='".$v['aca_id']."'>". $v['remark'] ."</option>";
		}
		
		//判断是否有选择 标识1有 标识0没有
		if($data['parent_id'] != $did){
			$biaoshi = 1;
			//传值顶级ID 与自己当前的父ID
			$classify = $this->classify($did,$data['parent_id']);
		}else{
			$biaoshi = 0;
			$classify = $this->classify($data['parent_id']);
		}
		// dd($id,$data['parent_id']);
		$array = array(
			'data'=>$data,
			'aca' =>$aca,
			'classify'=>$classify,
			'biaoshi'=>$biaoshi
		);
		
		return Response::json($array);
	}
	
	//查询一级id
	private function getDid($id){
		
		$str = Aca::where('aca_id',$id)->select('aca_id','parent_id')->first();
		if($str->parent_id !=0){
			return ($this->getDid($str->parent_id));
		}else{
			return $str->aca_id;
		}
	}
	
	
	//修改保存
	public function update(){
		$data = Input::except('/admin/core/user/aca/update');
		if(!empty($data['sid']) && $data['sid'] !="请选择"){
			$data['parent_id'] = $data['sid'];
			unset($data['sid']);
		}else{
			unset($data['sid']);
		}
		if($data['action'] != ''){
			// $data['action'] = $data['parent'] .'.'. $data['action'];
		}else{
			$data['action'] ='';
		}
		
		$obj = new Aca();
		
		$return = $obj->check($data,'edit');
        if ($return['status']) {
            $return = $obj->edits($data);
        }
		
		return Response::json($return);
	}
	
	//获取类别
	private function classify($parent_id = 0,$id = '',$type = ''){
		$array = array();
		$data = Aca::where('parent_id',$parent_id)->get()->toArray();
		foreach($data as $k=>$v){
			$select = '';
			if($id == $v['aca_id']){
				$select = "selected = 'selected'";	
			}
		
			$array[] = "<option value='".$v['aca_id']."' ".$select." >". $type . $v['remark'] ."</option>";
			
			$is_ok = Aca::where('parent_id',$v['aca_id'])->count();
			if($is_ok){
				if($type == ''){
					$arrays = $this->classify($v['aca_id'],$id,"&nbsp;&nbsp;&nbsp;&nbsp;|—");
				}else{
					$arrays = $this->classify($v['aca_id'],$id,"&nbsp;&nbsp;". $type);
				}
				
				foreach($arrays as $vs){
					$array[] = $vs;
				}
			}
	
		}
		return $array;
	}
	
	//删除权限
	public function del(){
		$id = Input::get('listid');
		
        $obj = new Aca;
		
		$count = Aca::where('parent_id',$id)->count();
		if(!$count){
			DB::table('role_aca')->where('aca_id',$id)->delete();
			return Response::json($obj->dels($id));
		}else{
			return Response::json(array('status' => false, 'msg' => '有子类，无法删除'));
		}
	}
	//
	private function cycle2($parent_id = 0){
		$array = array();
		$data = Aca::where('parent_id',$parent_id)->get()->toArray();
		foreach($data as $k=>$v){
			$array[$k] = $v;
			$array[$k]['nodes'] = array();
			$is_ok = Aca::where('parent_id',$v['aca_id'])->count();
			if($is_ok){
				$array[$k]['nodes'] = $this->cycle2($v['aca_id']);
			}
		}
		return $array;
	}
	//导出
	public function export(){
		// header('Content-Type: plain/text');
		$id = Input::get('id');
		$array = array();
		$data = Aca::where('aca_id',$id)->get()->toArray();
		foreach($data as $k=>$v){
			// $array[$k]['text'] = $v['remark'];
			// $array[$k]['id'] = $v['aca_id'];
			$array[$k] = $v;
			$array[$k]['nodes'] = array();
			$is_ok = Aca::where('parent_id',$v['aca_id'])->count();
			if($is_ok){
				$array[$k]['nodes'] = $this->cycle2($v['aca_id']);
			}
		}
		$targetDir = dirname(WEITAC_PATH)."/public/upload/aca/export/";
		if (!is_dir($targetDir)) {
			mkdir($targetDir,0777,true);     
		}
		$filename = $targetDir . $id .'.txt';
		file_put_contents($filename, json_encode($array));
		if(is_file($filename)) {
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=".basename($filename));
            readfile($filename);
            exit;
        }else{
            echo "文件不存在！";
            exit;
        }
	}
	public function import_add(){
		return view('user::aca.import_add');
	}
	public function import_insert(){
		$str=Input::get('thumb');
		$arr = json_decode($str,true);
		// $f = 0;
		DB::beginTransaction();
		$return = $this->insert_data($arr, 0);
		if($return['status']){
			DB::commit();
		}else{
			DB::rollBack();
		}
		return json_encode($return);
	}
	public function insert_data($arr,$pid = 0){
		$data = array();
		// dd($arr);
		$return = array();
		foreach ($arr as $k => $v) {
			$kv = $v;
			unset($kv['aca_id']);
			unset($kv['nodes']);
			// unset($kv['parent_id']);
			$kv['parent_id'] = $pid;
			$data = $kv;
			$baoming = DB::table('aca')->where('remark',$data['remark'])->get();
			$action ="";
			$f = 0;
			if(!empty($data['action'])){
				$action = DB::table('aca')->where('action',$data['action'])->get();
			}
			if(!empty($baoming)){
				$f = 1;
				$return = array('status'=>false, 'msg'=>'描述已存在');
				break;
			}elseif(!empty($action)){
				$f = 1;
				$return = array('status'=>false, 'msg'=>'路由已存在');
				break;
			}else{
				if($id = DB::table('aca')->insertGetId($data)){
					if($v['nodes']){
						$this->insert_data($v['nodes'],$id);
					}
					// $return = array('status'=>true, 'msg'=>'导入成功');
				}else{
					$f = 1;
					$return = array('status'=>false, 'msg'=>'导入失败');
					break;
				}
			}
		}
		if(!$f){
			$return = array('status'=>true, 'msg'=>'导入成功');
		}
		return $return;
		// if($f){
		// 	return true;
		// }else{
		// 	return false;
		// }
	}
	public function import_upload(){
		$str=file_get_contents($_FILES['file']['tmp_name']);
		echo $str;
	}
}
