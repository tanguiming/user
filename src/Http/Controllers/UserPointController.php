<?php

/**
 *  用户积分
 *
 */
namespace Weitac\User\Http\Controllers;
use Response;
use DB;
use Weitac;
use App\Http\Controllers\AdminController as AdminController;
use Illuminate\Support\Facades\Input;
use Weitac\User\Http\Models\FrontUserPoint as FrontUserPoint;
use Weitac\User\Http\Models\UserIntegralSet as UserIntegralSet;
use Weitac\User\Http\Models\UserPointClass as UserPointClass;
use Weitac\User\Http\Models\UserDetail as UserDetail;

class UserPointController extends AdminController {

    private $pagesize = 15, $upload_max_filesize;

    public function __construct() {
        parent::__construct();
        // post 进行csrf验证
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    //积分规则显示
    public function index() {
    	$token = Weitac::getToken();
    	$res = UserPointClass::where('token',$token)->get()->toArray();
		foreach($res as $k=>$v){
			$res[$k]['point'] = FrontUserPoint::where('token',$token)->where('classid',$v['classid'])->get()->toArray();
		}
		$globalname = $this->globalname();
		//dd($res);
        return view('user::point/index',array('res'=>$res,'globalname'=>$globalname));
    }
	//切换启动状态
	public function pointaudit(){
		$id = Input::get('id');
		$token = Weitac::getToken();
		$enabled = Input::get('enabled');
		if($enabled == 1){
			FrontUserPoint::where('token',$token)->where('id',$id)->update(array('enabled'=>2));
			$return = array('status' => TRUE, 'msg' => "开启操作成功");
		}else{
			FrontUserPoint::where('token',$token)->where('id',$id)->update(array('enabled'=>1));
			$return = array('status' => TRUE, 'msg' => "关闭操作成功");
		}
		return Response::json($return);
	}
	
	//查询全局配置的虚拟币名称
	public function globalname(){
		$token = Weitac::getToken();
		$globalname = UserIntegralSet::where('token',$token)->pluck('name');
		if($globalname){
			return $globalname;
		}else{
			return '值';
		}
		
	}

    //添加
    public function add() {
		$token = Weitac::getToken();
		$pointcount = UserPointClass::count();
		$classtitle = UserPointClass::where('token',$token)->select('classid','classtitle')->get()->toArray();
		$globalname = $this->globalname();
        return view('user::point/add',array('pointcount'=>$pointcount,'classtitle'=>$classtitle,'globalname'=>$globalname));
    }

    //执行插入方法
    public function insert() {
        $data = Input::except('/admin/userpoint/insert', '_token','classtitle');
		//dd($data);
		if(empty($data['classid'])){
			$return = array('status' => FALSE, 'msg' => "请选择规则分类");
		}else{
			if($data['chooseway']){
				$data['token'] = Weitac::getToken();
				$data['addtime'] = time();
				$data['coinid'] = UserIntegralSet::where('token',$data['token'])->pluck('coinid');
				//dd($data);
		        //判断插入数据在数据库中是否存在
		        $res = FrontUserPoint::where('name', '=', $data['name'])->where('token',$data['token'])->count();
		        
		        if (!$res) {
		        	$respinyin = FrontUserPoint::where('pinyin', '=', $data['pinyin'])->where('token',$data['token'])->count();
					if(!$respinyin){
						$obj = new FrontUserPoint();
						$data['token'] = Weitac::getToken();
						//dd($data);
			            $return = $obj->check($data);
			            if ($return['status']) {
			                $return = $obj->add($data);
			            }
					}else{
						$return = array('status' => FALSE, 'msg' => "规则字符已存在");
					}
		        } else {
		            $return = array('status' => FALSE, 'msg' => "名称已存在");
		        }
			}else{
				$return = array('status' => FALSE, 'msg' => "请选择方式");
			}
		}
        return Response::json($return);
    }
	//添加分类
	public function pointinput(){
		$data['classtitle'] = Input::get('classtitle');
		$res = UserPointClass::where('classtitle', '=', $data['classtitle'])->count();
		$data['token'] = Weitac::getToken();
		if($res){
			$return = array('status' => FALSE, 'msg' => "分类已存在");
			return Response::json($return);
		}else{
			if($data['classtitle']){
				$obj = new UserPointClass();
				$return = $obj->add($data);
				$classtitle = UserPointClass::where('token',$data['token'])->select('classid','classtitle')->get()->toArray();
				return Response::json(array('status'=>true,'classtitle'=>$classtitle));
			}else{
				$classtitle = UserPointClass::where('token',$data['token'])->select('classid','classtitle')->get()->toArray();
				return Response::json(array('status'=>true,'classtitle'=>$classtitle));
			}
		}
	}

    //修改方法
    public function edit() {
        $id = Input::get('id');
        $data = FrontUserPoint::find($id)->toArray();
		$class = UserPointClass::where('token',$data['token'])->select('classid','classtitle')->get()->toArray();
		$pointcount = UserPointClass::count();
		$globalname = $this->globalname();
		//dd($data);
        //修改数据时，将年月日和时分秒分开显示在要修改的框体中。
        $result = array(
            'name' => $data['name'],
            'pinyin' => $data['pinyin'],
            'content' => $data['content'],
            'point' => $data['point'],
            'experience' => $data['experience'],
            'coinid' => $data['coinid'],
            'classid' => $data['classid'],
            'reward_bean' => $data['reward_bean'],
            'deduct_bean' => $data['deduct_bean'],
            'enabled' => $data['enabled'],
            'chooseway' => $data['chooseway'],
            'id' => $id,
            'class'=>$class,
            'pointcount'=>$pointcount,
            'globalname'=>$globalname
        );
		//dd($result);
        return view('user::point/edit', $result);
    }

    //执行修改方法
    public function update() {
        //添加到数组，获取修改的id.
        $data = Input::except('/admin/userpoint/update', '_token','classtitle');
		if(empty($data['classid'])){
			$return = array('status' => FALSE, 'msg' => "请选择规则分类");
		}else{
			if($data['chooseway']){
				$data['token'] = Weitac::getToken();
				$data['addtime'] = time();
				$data['coinid'] = UserIntegralSet::where('token',$data['token'])->pluck('coinid');
				//dd($data);
		        $obj = new FrontUserPoint();
		        $return = $obj->check($data);
		
		        if ($return['status'] == true) {
		
		            $return = $obj->edit($data);
		        }
		    }else{
		    	$return = array('status' => FALSE, 'msg' => "请选择方式");
		    }
		}
        return Response::json($return);
    }

    //删除方法
    public function delete() {
		$ids = Input::get('ids');

        $ids = substr($ids, 0, -1);
        $array = explode(",", $ids);

        $obj = new FrontUserPoint();
        $result = $obj->del($array);

        return Response::json($result);
    }

    //积分的配置  2015-09-09 by wpz
    public function set() {
        $token = Weitac::getToken();
        $obj = new UserIntegralSet();

        $datas = $obj->where('token', $token)->get()->toArray();
        if (empty($datas)) {
            $data['id'] = '';
            $data['name'] = '';
            $data['picurl'] = '';
            $data['coinway'] = 1;
        }else{
            $data['coinid'] = $datas[0]['coinid'];
            $data['name'] = $datas[0]['name'];
            $data['picurl'] = $datas[0]['picurl'];
            $data['coinway'] = $datas[0]['coinway'];
        }

        return view('user::point/set', $data);
    }

    //积分配置入库 2015-09-09 by wpz
    public function setupdate() {
        //添加到数组，获取修改的id.
        $data = Input::except('/admin/userpoint/setupdate', '_token');
        $obj = new UserIntegralSet();
        if($data['coinid']==''){
            unset($data['coinid']);
            $data['token'] = Weitac::getToken();
            $return = $obj->add($data);
            
        }else{

            $return = $obj->edit($data);
        }
        return Response::json($return);
    }
	
	/**
     * 上传处理  上传控制
     * @return type
     */
        public function upload(){
            // Make sure file is not cached (as it happens for example on iOS devices)
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            @set_time_limit(5 * 60);

            $targetDir = UPLOAD_PATH."user/grade/".date('y-m-d',time()).'/';
			//$targetDir = public_path().'/upload/user/grade/'.date('y-m-d',time()).'/';
            //$targetDir = 'uploads';
            $cleanupTargetDir = true; // Remove old files
            $maxFileAge = 5 * 3600; // Temp file age in seconds
            // Create target dir
            //判断是不是有这么一个目录，如果没有，那就递归创建这个目录
            if (!is_dir($targetDir)) {
                    mkdir($targetDir,0777,true);
					chmod($targetDir,0777);
            }

            // Get a file name
            if (isset($_REQUEST["name"])) {
                    $fileName = $_REQUEST["name"];
            } elseif (!empty($_FILES)) {
                    $fileName = $_FILES["file"]["name"];
            } else {
                    $fileName = uniqid("file_");
            }
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

            $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
            $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
            // Remove old temp files    
            if ($cleanupTargetDir) {
				if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
						die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
				}

				while (($file = readdir($dir)) !== false) {
						$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

						// If temp file is current file proceed to the next
						if ($tmpfilePath == "{$filePath}.part") {
								continue;
						}

						// Remove temp file if it is older than the max age and is not the current file
						if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
								@unlink($tmpfilePath);
						}
				}
				closedir($dir);
            }   

            // Open temp file
            if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if (!empty($_FILES)) {
                if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }

				// Read binary input stream and append it to temp file
				if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}
            } else {    
				if (!$in = @fopen("php://input", "rb")) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}
            }

            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }

            @fclose($out);
            @fclose($in);

            // Check if file has been uploaded
            if (!$chunks || $chunk == $chunks - 1) {
                    // Strip the temp .part suffix off 
                    rename("{$filePath}.part", $filePath);
            }
            // Return Success JSON-RPC response
            //处理要存入数据库的路径
            
            $filePath = strchr($filePath,"user/grade/");
			//dd($filePath); die;
            return json_encode($filePath);
			//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
        } 


	//-----------------新加分类的修改和删除
	public function classedit(){
		$id = Input::get('id');
		$token = Weitac::getToken();
		$data = UserPointClass::where('classid',$id)->where('token',$token)->get()->toArray();
		//dd($data[0]);
		$return = array(
			'classid'=>$data[0]['classid'],
			'classtitle'=>$data[0]['classtitle'],
		);
		return view('user::point/classindex',$return);
	}
	public function classupdate(){
		$data = Input::except('/admin/userpoint/classupdate','_token');
		//dd($data);
		$obj = new UserPointClass();
		$result = $obj->edit($data);
		return Response::json($result);
	}
	public function classdel(){
		
		//关联积分规则，需要四个参数，
		//第一个($userid)：用户的user_id；
		//第二个($pinyin)：你将要关联积分规则的规则字符；
		//第三个($token)：token，为了防止在不同平台有相同ID的规则；
		//第四个($beans)：奖励或扣除的积分值，假如你不需要积分规则中的奖励扣除机制就自己给这个变量定义一个数值【调用这个方法给我传四个值】。假如你需要积分规则中的奖励扣除机制就不用管这个变量啦【调用这个方法给我传三个值】。。。

		//【测试积分规则假数据】
		/*$userid = 1;
		$pinyin = 'yihao';
		$token = 'weitachelper';
		$result = $this->encapsulation($userid,$pinyin,$token);
		
		//$beans = -105;
		//$result = $this->encapsulation($userid,$pinyin,$token,$beans);
		return Response::json($result);*/
		 
		$token = Weitac::getToken();
		$ids = Input::get('ids');
        $ides = substr($ids, 0, -1);
		//dd($ides);
		$countclass = FrontUserPoint::where('token',$token)->where('classid',$ides)->count();
		if($countclass){
			$result = array('status'=>false,'msg'=>'该分类下有规则！');
		}else{
			$obj = new UserPointClass();
        	$result = $obj->del($ides);
		}
        return Response::json($result);
	}

	//------------------积分规则封装方法----
	//关联积分规则，需要四个参数，
	//第一个($userid)：用户的user_id；
	//第二个($pinyin)：你将要关联积分规则的规则字符；
	//第三个($token)：token，为了防止在不同平台有相同ID的规则；
	//第四个($beans)：奖励或扣除的积分值，假如你不需要积分规则中的奖励扣除机制就自己给这个变量定义一个数值【调用这个方法给我传四个值】。假如你需要积分规则中的奖励扣除机制就不用管这个变量啦【调用这个方法给我传三个值】。。。
	
	function encapsulation($userid,$pinyin,$token,$beans=0){
		$point = UserDetail::where('user_id',$userid)->pluck('point');
		
		$countclass = FrontUserPoint::where('token',$token)->where('pinyin',$pinyin)->select('experience','reward_bean','deduct_bean','chooseway','content')->first();
		if(!$countclass){
			return array('status'=>FALSE,'msg'=>'没有对应的积分规则');
		}
		//$beans有：表示自定义扣除和奖励
		if($beans){
			//$beans > 0：表示在该用户积分基础上奖励【表示正数】
			if($beans > 0){
				$points = $point+$beans;
				$userdetail = UserDetail::where('user_id',$userid)->update(array('point'=>$points));
				//这里所有入库都是：积分日志表【user_point_detail】
				$data['user_id'] = $userid;
				$data['cpoint'] = 0;
				$data['jpoint'] = $beans;
				$data['ip'] = $_SERVER["REMOTE_ADDR"];
				$data['datetime'] = time();
				$data['experience'] = $countclass->experience;
				$data['operation'] = $countclass->content;
				$data['pinyin'] = $pinyin;
				$result = DB::table('user_point_detail')->insert($data);
				return array('status'=>TRUE,'msg'=>'积分操作成功。');
			}else{
			//$beans <= 0：表示在该用户积分基础上扣除【表示负数】
				//abs($beans) > $point：表示扣除数大于该用户原积分数，就返回“积分不够扣的”
				if(abs($beans) > $point){
					return array('status'=>FALSE,'msg'=>'你的积分都不够扣得，别玩儿了！');
				}else{
				//abs($beans) <= $point：表示扣的积分在该用户原积分数之内，够扣的
					$points = $point+$beans;
					$userdetail = UserDetail::where('user_id',$userid)->update(array('point'=>$points));
					$data['user_id'] = $userid;
					$data['cpoint'] = abs($beans);
					$data['jpoint'] = 0;
					$data['ip'] = $_SERVER["REMOTE_ADDR"];
					$data['datetime'] = time();
					$data['experience'] = $countclass->experience;
					$data['operation'] = $countclass->content;
					$data['pinyin'] = $pinyin;
					$result = DB::table('user_point_detail')->insert($data);
					return array('status'=>TRUE,'msg'=>'积分操作成功。');
				}
			}
		}else{
		//$beans没有：表示关联规则进行扣除和奖励

			//$countclass['chooseway']：表示不同的选择方式
			//$countclass['chooseway']==1：表示按固定值奖励；$countclass['chooseway']==2：表示按比例奖励；$countclass['chooseway']==3：表示按固定值扣除；$countclass['chooseway']==4：表示按比例扣除。
			$CCre = $countclass->reward_bean;
			$CCde = $countclass->deduct_bean;
			if($countclass['chooseway'] == 1){
				//表示按固定值奖励
				if($CCre){
					$points = $point + $CCre;
					$userdetail = UserDetail::where('user_id',$userid)->update(array('point'=>$points));
					$data['user_id'] = $userid;
					$data['cpoint'] = 0;
					$data['jpoint'] = $CCre;
					$data['ip'] = $_SERVER["REMOTE_ADDR"];
					$data['datetime'] = time();
					$data['experience'] = $countclass->experience;
					$data['operation'] = $countclass->content;
					$data['pinyin'] = $pinyin;
					$result = DB::table('user_point_detail')->insert($data);
					return array('status'=>TRUE,'msg'=>'积分操作成功。');
				}
			}elseif($countclass['chooseway'] == 2){
				//表示按比例奖励
				$CCres=$point*($CCre/100);
				$points = $point + $CCres;
				$userdetail = UserDetail::where('user_id',$userid)->update(array('point'=>$points));
				$data['user_id'] = $userid;
				$data['cpoint'] = 0;
				$data['jpoint'] = $CCres;
				$data['ip'] = $_SERVER["REMOTE_ADDR"];
				$data['datetime'] = time();
				$data['experience'] = $countclass->experience;
				$data['operation'] =  $countclass->content;
				$data['pinyin'] = $pinyin;
				$result = DB::table('user_point_detail')->insert($data);
				return array('status'=>TRUE,'msg'=>'积分操作成功。');
			}elseif($countclass['chooseway'] == 3){
				//------------------表示按固定值扣除
				if($CCde){
					if($point < $CCde){
						return array('status'=>FALSE,'msg'=>'你的积分都不够扣得，别玩儿了！');
					}else{
						$points = $point - $CCde;
						$userdetail = UserDetail::where('user_id',$userid)->update(array('point'=>$points));
						$data['user_id'] = $userid;
						$data['cpoint'] = $CCde;
						$data['jpoint'] = 0;
						$data['ip'] = $_SERVER["REMOTE_ADDR"];
						$data['datetime'] = time();
						$data['experience'] = $countclass->experience;
						$data['operation'] =  $countclass->content;
						$data['pinyin'] = $pinyin;
						$result = DB::table('user_point_detail')->insert($data);
						return array('status'=>TRUE,'msg'=>'积分操作成功。');
					}
				}
			}else{
				//----------------表示按比例扣除
				$CCdes=$point*($CCde/100);
				//dd($CCdes);
				if($point < $CCdes){
					return array('status'=>FALSE,'msg'=>'你的积分都不够扣得，别玩儿了！');
				}else{
					$points = $point - $CCdes;
					$userdetail = UserDetail::where('user_id',$userid)->update(array('point'=>$points));
					$data['user_id'] = $userid;
					$data['cpoint'] = $CCdes;
					$data['jpoint'] = 0;
					$data['ip'] = $_SERVER["REMOTE_ADDR"];
					$data['datetime'] = time();
					$data['experience'] = $countclass->experience;
					$data['operation'] =  $countclass->content;
					$data['pinyin'] = $pinyin;
					$result = DB::table('user_point_detail')->insert($data);
					return array('status'=>TRUE,'msg'=>'积分操作成功。');
				}
			}
		}
	}
}
