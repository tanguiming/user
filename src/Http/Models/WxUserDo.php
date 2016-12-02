<?php

/**
 * WxUserDo
 * 
 * @author		lq
 * @date		2014-01-11
 * @version		1.0
 */
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
use DB;


class WxUserDo extends Model {

    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $_where;
    protected $_order = 'id desc';
    public $timestamps = false;

    /**
     *  设置条件
     *  
     *  @param array $where 键值对， 键为 字段和条件   值为 值
     *  @param string $type   "and" "or"
     *  @return $this
     */
    public function setWhere($where = null, $type = 'and') {
        if (empty($where) || !is_array($where))
            return $this;

        $data = array();
        $searchString = '';

        foreach ($where as $search => $value) {

            $isIn = strstr($search, 'in');

            if ($isIn) {
                $searchString .= ' ' . trim($search) . " ($value) " . $type;
            } else {
                $searchString .= ' ' . trim($search) . ' ? ' . $type;
                $data['bind'][] = trim($value);
            }
        }

        $data['param'] = substr_replace(trim($searchString, ' '), '', -4); // 去掉最后的 " and"

        if (!empty($data)) {
            $obj = $this->whereRaw($data['param'], $data['bind']);
        }

        return $obj;
    }

	
	public function getdata($pageSize,$page,$token,$where=array()){
		//*************************
		//**   选择数据库与表    **//
		//*************************
		if(empty($where)){
			$count = DB::table('user_detail')->join('user','user.user_id', '=', 'user_detail.user_id')->where('user.token', '=', $token)->count(); 
		}else{
			$count = DB::table('user_detail')->join('user','user.user_id', '=', 'user_detail.user_id')->where('user.token', '=', $token)->whereIn('user.user_id',$where)->count();
		}
		
		if($page==0){
			$from = 1;
			$to = $pageSize;
			$page = 1;
			$pages = 0;
		}else{
			$from = $pageSize*($page-1)+1;
			$to = $pageSize*($page)>$count ? $count:$pageSize*($page);
			$pages = $pageSize*($page-1);
		}
		if(empty($where)){
			$array =  DB::table('user_detail')->join('user','user.user_id', '=', 'user_detail.user_id')->where('user.token', '=', $token)->orderBy('point', 'desc')->limit($pageSize)->skip($pages)->get();
		}else{
			$array =  DB::table('user_detail')->join('user','user.user_id', '=', 'user_detail.user_id')->where('user.token', '=', $token)->whereIn('user.user_id',$where)->orderBy('point', 'desc')->limit($pageSize)->skip($pages)->get();
		}
		$arr1 = array(
			'total' => $count,
			'per_page' => $pageSize,
			'current_page' => $page,
			'last_page' => ceil($count/$pageSize),
			'from' => $from,
			'to' => $to,
			'data' => $array,
		);
			
		return $arr1;
	}
    /**
     * 
     * @param type $date
     * @return type
     */
    public function check($date) {
		/**
		*对数据的检查，如果数据不正确就返回flase
		**/
		
        return array('status' => TRUE, 'msg' => '表单验证通过');
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function add($datas) {

        $data['name']= $datas['name']; 
        $data['partment']= $datas['partment']; 
        $data['level']= $datas['level']; 
        $data['phone']= $datas['phone']; 
        $data['mktime']= $datas['time']; 
        $data['status']= $datas['status']; 
        if ($this->insert($data)) {
            return array('status' => TRUE, 'msg' => '添加成功');
        } else {
            return array('status' => FALSE, 'msg' => '添加失败');
        }
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function edit($datas) {

        $id = $datas['id'];
		$data['name']= $datas['name']; 
        $data['partment']= $datas['partment']; 
        $data['level']= $datas['level']; 
        $data['phone']= $datas['phone']; 
        if ($this::where('id', '=', $id)->update($data)) {
            return array('status' => TRUE, 'msg' => '修改成功');
        } else {
            return array('status' => FALSE, 'msg' => '修改失败');
        }
    }

	/**
     * 加入回收站
     * @param type $data
     * @return type
     */
    public function updateStatus($datas) {

        $id = $datas['id'];
		$data['status']= $datas['status']; 
        if ($this::where('id', '=', $id)->update($data)) {
            return array('status' => TRUE, 'msg' => '删除成功');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败');
        }
    }
	
	/**
     * 撤销删除
     * @param type $data
     * @return type
     */
    public function backStatus($datas) {

        $id = $datas['id'];
		$data['status']= $datas['status']; 
        if ($this::where('id', '=', $id)->update($data)) {
            return array('status' => TRUE, 'msg' => '撤销成功');
        } else {
            return array('status' => FALSE, 'msg' => '撤销失败');
        }
    }
    /*
	删除操作
	*/
	public function del($ids){
		if($this::destroy($ids)){
			return array(
				"status"=>true,
				"msg"=>"删除成功！"
			);
		}
		else{
			return array(
				"status"=>false,
				"msg"=>"删除失败！"
			);
		}
	}

}
