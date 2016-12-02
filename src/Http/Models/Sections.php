<?php

namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
class Sections extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'section';
	protected $guarded = array('id', 'sectionid');
	protected $primaryKey = 'sectionid';
	public $timestamps = false;
	protected $fillable = array('classid', 'name', 'type', 'size', 'origdata', 'out_type', 'settings', 'where', 'option', 'data', 'locked','lockedby','updated','updatedby','created','createdby');
	public $treeData = array();

	protected $_where;									// 搜索条件 array 'param' => 'name like ? and ...'  'bind' => 'array("admin", "...")'
	protected $_order = 'id asc';					// 默认排序条件

	static public $sectionType = array('自动', '推荐');	// 区块类型
	static public $sectionOutType = array('html', 'xml', 'json');	// out_type
	static public $sectionFieldType = array('通用', '扩展'); //区块字段类型

	// 区块模版生成位置, 如果输出三HTML，则在/html/下 如果是xml，则在/xml/下, json not create file
	static public $sectionFolder = 'sections';		

	public function fields()
	{
		return $this->hasMany('SectionField', 'section_id', 'sectionid');
	}

	// 生成区块模版文件
	// @param array $section 存放的区块内容
	// @param string $folder 存在区块的目录
	public function createSectionFile($section, $folder = 'html')
	{
		$fileName = $section['sectionid'] . '.blade.php';
		$filePath = app_path() . '/views/' . self::$sectionFolder . '/' . $folder . '/';

		// 如果目录不存在， 则创建
		if(!File::exists($filePath))
		{
			File::makeDirectory($filePath, 0777, true);
		}

		$html = trim($section['template']);
		$boo = File::put($filePath . $fileName, $html);

		// 返回模版页标记
		$returnHtml = '@include("'. self::$sectionFolder . '.' . $section['sectionid'] .'")';

		return array('status'=>true, 'msg'=>'创建区块成功', 'section'=>$returnHtml);
	}
	
	public function getTemplate() 
	{
		$folder = '';
		if($this->out_type == '0'){
			$folder = 'html';
		}elseif($this->out_type == '1'){
			$folder = 'xml';
		}else {
			return '';
		}
		
		
		
		
		$filePath = app_path() . '/views/' . self::$sectionFolder . '/' . $folder . '/';
		$fileName = $this->sectionid . '.blade.php';
		
		if(!File::exists($filePath . $fileName))
		{
			return '';
		}
		
		$html = File::get($filePath . $fileName);
		return $html;
		
	}
	
	public function getTemplatePath()
	{
		$folder = '';
		if($this->out_type == '0'){
			$folder = 'html';
		}elseif($this->out_type == '1'){
			$folder = 'xml';
		}else {
			return '';
		}
		$filePath = app_path() . '/views/' . self::$sectionFolder . '/' . $folder . '/';
		$fileName = $this->sectionid . '.blade.php';
	
		if(!File::exists($filePath . $fileName))
		{
			return '';
		}
		return $filePath . $fileName;
	
	}

	// @param $sections
	public function getDataByExtendField($sections)
	{
		$select = $table = $data = array();
		$left = '';
//dd($sections);
		$fields = $sections['fields'];
		foreach($fields as $k => $field)
		{
			$select[$field['table_name'] . '.' .$field['table_field'] . '@'.$k] = $field['table'] . '_' . $field['field'];
			$table[$field['table_name']][$field['field']] = $field['table_name'] .'.'. $field['table_field']; 
		}
                $classid = DB::table('section')->where('sectionid',$sections['sectionid'])->pluck('classid');
		$type = DB::table('section_class')->where('classid',$classid)->pluck('type');
                if($type == 2){
                   $selectString = '';

                    foreach($select as $where => $val)
                    {
                            $where = substr($where, 0, strripos($where, '@'));
                            $selectString .= $where . ' as ' . $val . ',';
                    }

                    $select = trim($selectString, ',');
                  
                    $obj = $sections['type'] == 0 ? DB::table('sc_details') : DB::table('section_data');
                    $obj->select(DB::raw($select));
                    if(!empty($fields)){
                        if($sections['type'] == 1 && $fields[0]['table']!='section_data')
                        {
                                $obj = $obj->leftJoin('sc_details', 'sc_details.id', '=', 'section_data.contentid');
                        }
                    }

                    if(!is_object($left))
                            $left = $obj;
                    
                    // 注册搜索条件
                    $where = $left->whereRaw($sections['where']);
                    $rs = $where->take($sections['size'])->get();

                    // 过滤掉为空的字段
                    if(!empty($rs))
                    {
                            $rs = $this->_filterscField($rs);
                    }	
                    
                }else{
                    $selectString = 'content_type.type as ctype,';

                    foreach($select as $where => $val)
                    {
                            $where = substr($where, 0, strripos($where, '@'));
                            $selectString .= $where . ' as ' . $val . ',';
                    }

                    $select = trim($selectString, ',');
                    $obj = $sections['type'] == 0 ? DB::table('content') : DB::table('section_data');

                    $obj->select(DB::raw($select));

                    if($sections['type'] == 1)
                    {
                            $obj = $obj->leftJoin('content', 'content.id', '=', 'section_data.contentid');
                    }
                    $obj = $obj->leftJoin('content_type', 'content.modelid', '=', 'content_type.id');

                    foreach($table as $table=>$field)
                    {
                            if($table == 'content' || $table == 'section_data')
                                    continue;

                            $left = $obj->leftJoin($table, 'content.id', '=', $table.'.contentid');
                    }

                    if(!is_object($left))
                            $left = $obj;
                    
                    
                    // 注册搜索条件
                    $where = $left->whereRaw($sections['where']);
                    $rs = $where->take($sections['size'])->get();

                    // 过滤掉为空的字段
                    if(!empty($rs))
                    {
                            $rs = $this->_filterField($rs);
                    }	
                    
                }
		
	
		return $rs;
	}

	// 过滤数组，将不存在的字段取消
	// return array
	private function _filterField($data)
	{
		$rs = array();

		foreach($data as $key=>$val)
		{	
			if(!isset($rs[$key]) || !is_array($rs[$key])) 
				$rs[$key]= array();

			$type = 'content_' . $val->ctype;
			foreach($val as $fieldNameTmp => $field)
			{
				$point = strripos($fieldNameTmp, "_");
				$fieldName = substr($fieldNameTmp, $point+1);
				$tableName = substr($fieldNameTmp, 0, $point);
			
				if($type == $tableName)
				{
					$rs[$key][$fieldName] = $field;
				}
			}
		}

		return $rs;
	}
        
        // 过滤数组，将不存在的字段取消
	// return array
	private function _filterscField($data)
	{
		$rs = array();

		foreach($data as $key=>$val)
		{	
			if(!isset($rs[$key]) || !is_array($rs[$key])) 
				$rs[$key]= array();

			//$type = 'sc_details';
			foreach($val as $fieldNameTmp => $field)
			{
				$point = strripos($fieldNameTmp, "_");
				$fieldName = substr($fieldNameTmp, $point+1);
				$tableName = substr($fieldNameTmp, 0, $point);
			
				//if($type == $tableName)
				//{
					$rs[$key][$fieldName] = $field;
				//}
			}
		}

		return $rs;
	}

	// custom
	// del
	public function getDataByCustomField($limit = 10)
	{
		$obj = new Content;
		$rs = $obj->getListByPage(0, $limit);
		
		return empty($rs) ? false : $rs->toArray();
	}

	//过滤数组
	public function  filter_arr($filed, $filter = null)
	{
		
		if(is_null($filter)) {
			$filter = $this->fillable;
		}
	
		if(is_array($filed)) {
				
			foreach ($filed as $k => $v) {
				if(!in_array($k, $filter)) {
					unset($filed[$k]);
				}
					
			}
				
		}
		return $filed;
	
	}
	
	public function fillData($attr)
	{
	
		if(isset($attr['settings']) && !empty($attr['settings'])) {
			$attr['settings'] = implode(',', $attr['settings']);
		}else {
			$attr['settings'] = '1';
		}
	
		
		return $attr;
	}
	

	public function add ($attr) {
		$attr = $this->filter_arr($attr);
		
		$attr = $this->fillData($attr);
		
		$this->setRawAttributes($attr);
		
		if ($this->save()) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function edit ($attr) {
		$arr = $this->attributes;
		$attr = $this->filter_arr($attr);
		$attr = $this->fillData($attr);
		
		foreach($arr as $cattrk => $cattrv) {
				
			if(isset($attr[$cattrk])){
				$arr[$cattrk] = $attr[$cattrk];
			}
				
		}
		
		$this->setRawAttributes($arr);
	
		if ($this->save()) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function getType() 
	{
		$typeArr = array(
			'0' => '自动',
			'1' => '手动',
			'2' => '代码',
		);
		
		$type = isset($typeArr[$this->type]) ? $typeArr[$this->type] : '';
		
		return $type;
	}
	
	public function getUpdated() 
	{
		return date('Y-m-d H:i:s', $this->updated);
	}
	
	public function getClassid() 
	{
		$classid = $this->belongsTo('SectionsClass', 'classid')->get()->toArray();
		
		if(isset($classid[0])){
			$classid = $classid[0]['name'];
		}else {
			$classid = '';
		}

		return $classid;
	}
	
	public function getCreated() 
	{
		return date('Y-m-d H:i:s', $this->created);
	}
	
	public function getNextupdate() 
	{
		return date('Y-m-d H:i:s', $this->nextupdate);
	}
	
	public function getPublished() 
	{
		return date('Y-m-d H:i:s', $this->published);
	}
	
	public function getCreatedby() 
	{
		$user = $this->belongsTo('User', 'createdby')->get()->toArray();
		
		if(isset($user[0])){
			$user = $user[0]['user_name'];
		}else {
			$user = '';
		}
		return $user;
	}
	
	public function getUpdatedby() 
	{
		$user = $this->belongsTo('User', 'updatedby')->get()->toArray();
		
		if(isset($user[0])){
			$user = $user[0]['username'];
		}else {
			$user = '';
		}
		
		return $user;
	}

	/**
	 *  获取 单个 信息
	 *  支持 传入 主键 或者 setWhere后 再获取数据 
	 *  
	 *  @param int primaryKey
	 *  @return array 角色信息
	 */
	public function getShow($primaryKey = null)
	{
		if(!empty($primaryKey))
		{
			$rs = $this->where($this->primaryKey, '=', $primaryKey)->first();
			//dd($rs);
		}
		else
		{
			$obj = $this;
			
			// 注册搜索条件
			if(!empty($this->_where))
			{
				$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
			}
			
			$rs = $obj->first();
		}

		if(!empty($rs))
		{
			$fields = $rs->fields;
			$rs = $rs->toArray();
			$rs['fields'] = $fields->toArray();
			return $rs;
		}
		else
		{
			return false;
		}
	}
	
	
	// -------------------------------------------------------------------------------------  设置查询条件

	/**
	 *  设置条件
	 *  
	 *  @param array $where 键值对， 键为 字段和条件   值为 值
	 *  		如果条件键为相同的，则在传递的数组键名前加 "数字@"
	 *  @param string $type   "and" "or"
	 *  @return $this
	 */
	public function setWhere($where = null, $type = 'and')
	{
		if(empty($where) || !is_array($where))
			return $this;
			
		$data = array();
		$searchString = $this->_where = '';
		
		foreach($where as $search => $value)
		{
			$searchParam = trim($search);
			$cut = stripos($searchParam, '@');
			
			// 如果键带有 "@" 则会去掉 "@" 前面的
			if( $cut > 0)
			{
				$searchString .= ' ' . substr($searchParam, $cut + 1) . ' ? ' . $type;
			}
			else 
			{
				$searchString .= ' ' . $searchParam . ' ? ' . $type;
			}
			
			$data['bind'][] = trim($value);
		}

		$cutNum = -strlen($type) - 1;
		$data['param'] = substr_replace( trim($searchString, ' '), '',  $cutNum);	// 去掉最后的 " and"
		$this->_where = $data;
		
		return $this;
	}
	
	/**
	 *  获取条件
	 */
	public function getWhere()
	{
		return $this->_where;
	}
	
	/**
	 *	设置排序
	 *
	 * @param array $order 键值对  键为字段 值为排序类型
	 * @return $this
	 */
	public function setOrder($order = null)
	{
		if(empty($order) || !is_array($order))
			return $this;
			
		$orderString = '';
		
		foreach($order as $field => $type)
		{
			$orderString .= $field . ' ' . $type . ',';
		}
		$this->_order = trim($orderString . $this->_order, ',');
		
		return $this;
	}
	
	/**
	 *  获取排序
	 */
	public function getOrder()
	{
		return $this->_order;
	}
	
	
	public function del()
	{
		$this->delete();
		File::delete($this->getTemplatePath());
		return 1;
	}
	

}