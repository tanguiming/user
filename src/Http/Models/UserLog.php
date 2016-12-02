<?php

namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

/**
 * 	用户操作日志
 * 
 * @author			hwx
 * @date			2015-11-06
 * @version	1.0
 */
//$journal = DB::connection('mongodb')->collection('operationjournal')->insert($data);

class UserLog extends Eloquent{
    protected $connection = 'mongodb';
	protected $collection = 'operationjournal';
    protected $table = "user_log";
	static public $backupFolder = 'storage/logs/userlog/';	// 根目录下

    /**
     * 保存添加数据
     * @param type $data
     * @return type
     */
    public function add($data)
    {
       
        $this->attributes = $data;
        if ($this->save()) {
            return array('status' => TRUE, 'msg' => '添加成功');
        } else {
            return array('status' => FALSE, 'msg' => '添加失败');
        }
    }


  

     /**
     *  设置条件
     *  
     *  @param array $where 键值对， 键为 字段和条件   值为 值
     *  @param string $type   "and" "or"
     *  @return $this
     */
    public function setWhere($where = null, $type = 'and')
    {

        if (empty($where) || !is_array($where))
        //echo '<pre>';
            return $this;

        $data = array();
        $searchString = '';

        foreach ($where as $search => $value) {

            $isIn = strstr($search, 'in');
            $isNull = strstr($search, 'IS');

            if ($isIn) {
                $searchString .= ' ' . trim($search) . " ($value) " . $type;
            } elseif ($isNull) {
                $searchString .= ' ' . trim($search) . " $value " . $type;
            } else {
                $searchString .= ' ' . trim($search) . ' ? ' . $type;
                $data['bind'][] = trim($value);
            }
        }


        $data['param'] = substr_replace(trim($searchString, ' '), '', -4); // 去掉最后的 " and"

        if (!empty($data) && isset($data['bind'])) {
            $obj = $this->whereRaw($data['param'], $data['bind']);
        } else {
            $obj = $this->whereRaw($data['param']);
        }

        return $obj;
    }
    

    // 删除功能
    public function del($id)
    {
        if ($this->where("_id", $id)->delete()) {
            return array('status' =>true, 'msg' => '删除成功！');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败！');
        }
    }
	
	/**
	 *	备份日志
	 *	@param 	string $targetFoler	基于 /data 目录下的
	 *	@return array 
	 *				status => boolean
	 *				msg => msg
	 *				file => 备份的文件地址
	 */
	public function backup($targetFoler = null)
	{
		$info = array('status'=>false, 'msg'=>'备份失败');

		$targetFoler = empty($targetFoler) ? self::$backupFolder : trim(trim($targetFoler), '/') . '/';

		// 放在根目录下? 还是放在哪个目录下， base_path() 随之做改变
		$backupFolder = base_path() . '/' . $targetFoler;

		if(!File::exists($backupFolder))
		{
			File::makeDirectory($backupFolder, 0777, true);
		}

		$logs = $this->getList();
		$backupName = 'userlog-' . date('Y-m-d', time()) . '.txt'; 

		$targetFile = $backupFolder . $backupName;
		
		if(!File::exists($targetFile))
		{
			$fp = fopen($targetFile, 'w');
		}
		else
		{
			$fp = fopen($targetFile, 'a+');
		}

		if(flock($fp, LOCK_EX))
		{
			foreach($logs as $log)
			{
				$txt =  $log['username'] . " | " . $log['action'] . " : " . $log['object_name'] . " | " .
						date('Y-m-d H:i:s', $log['time']) . " 星期" . self::$weeks[date('N', $log['time'])] . " | " .
						$log['ip'] . "\r\n";

				fwrite($fp, $txt);
			}

			flock($fp, LOCK_UN);

			$info = array('status'=>true, 'msg'=>'备份成功', 'file'=>$targetFile);
		}
		else
		{
			$info = array('status'=>false, 'msg'=>'文件正在被使用');
		}

		fclose($fp);
		return $info;
	}

}
