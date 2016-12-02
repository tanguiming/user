<?php

/**
 * 	积分等级
 * 
 * @author		wpz
 * @date		2015-01-25
 * @version		1.0
 */
namespace Weitac\User\Http\Controllers;
use Response;
use Weitac;
use App\Http\Controllers\AdminController as AdminController;
use Weitac\User\Http\Models\UserGrade as UserGrade;
use Illuminate\Support\Facades\Input;

class UserGradeController extends AdminController {

    private $pagesize = 15;

    public function __construct()
    {
        
    }

    /**
     * 
     * @return type
     */
    public function index() {
        return view('user::grade/index');
    }

    /**
     * 取得列表数据
     * @return type
     */
    public function ajaxIndex() {
		$order = $where = $data = array();
        $input = Input::all();

        $content = new UserGrade();
        //条件
        if (Input::has('search')) {
            if (Input::get('search')) {
                $where['grade like '] = "%" . Input::get('search') . "%";
            }
        }
	
		$obj = $content->setWhere($where);
        if (isset($_GET['sort']) && isset($_GET['order'])) {

            $res = $obj->orderBy($_GET['sort'], $_GET['order'])->select()->paginate(Input::get('limit'))->toArray();
        } else {
            $res = $obj->paginate($Input::get('limit'))->toArray();
        }
       
        foreach ($res['data'] as $k => &$v) {
            if($v['icon']!=null){
                //dd(UPLOAD_PATH.$v['icon']);
				$v['icon'] = '<img style="width:42%;height:auto;" src="' . UPLOAD_URL.$v['icon'] . '" />';
            }
        }

        return Response::json($res);
    }
    
    //添加
    public function add() {
        return view('user::grade/add');
    }
	
	public function insert() {
        $data = Input::except('_token', '/admin/user/usergrade/insert');
        $data['token'] = Weitac::getToken();
		$data['addtime'] = time();
		//dd($data);
		$obj = new UserGrade;
		$res = $obj->whereRaw('token=? and grade=?',array($data['token'],$data['grade']))->first();
		if(!empty($res)){
			$return = array('status'=>false, 'msg'=>'有重复的等级'); 
		}else{
			$return = $obj->add($data);
        }
		
		return Response::json($return);
    }
	
    public function edit() {
		$id = Input::get('id');
        $data = UserGrade::find($id);
       // dd($data);
        return view('user::grade/edit', array('data'=>$data));
    }
	 /**
     * 保存修改
     * @return type
     */
    public function update() {

        $data = Input::except('_token', '/admin/user/usergrade/update');
		$data['token'] = Weitac::getToken();
		$data['addtime'] = time();

        $obj = new UserGrade;
        $result = $obj->check($data);
        if ($result['status'] == true) {

            $result = $obj->edit($data);
        }

        return Response::json($result);
    }

	public function del() {
        $ids = Input::get('ids');

        $ids = substr($ids, 0, -1);
        $array = explode(",", $ids);

        $obj = new UserGrade();
        $result = $obj->del($array);

        return Response::json($result);
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
            
            $filePath = strchr($filePath,"user/grade");
            //dd($filePath); die;
            return json_encode($filePath);
            //die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
        } 
}
