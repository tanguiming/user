<?php
//自由列表内容
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
class SectionsClass extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'section_class';
	protected $guarded = array('id', 'classid');
	protected $primaryKey = 'classid';
	public $timestamps = false;
	protected $fillable = array('name', 'sort', 'memo','type');
	
	public $treeData = array();


	
	/**
	 * 过滤数组
	 * @param Array $filed
	 * @param Array $filter
	 * @return Array
	 */
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
		//print_r($filed);
		return $filed;
	
	}
	
	
	public function listData() {
		
		$data	= $this->whereRaw('1=1')
				->orderBy('sort','asc')
				->get();
		
		return $data;
	}

	public function add ($attr) {
		$sort = $this->select(DB::Raw('MAX(sort) as mx'))->get();
		$attr['sort'] = ($sort[0]->mx + 1);
               
		$attr = $this->filter_arr($attr);
                
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

	/**
	 * 获取分类 下的 区块
	 * @param Array $select 要查询的字段
	 * @return  Array 
	 */
	
	public function getSections($select = array('*')) 
	{
		return $this->hasMany('Sections','classid')->select($select)->get();
	}
	
	

}