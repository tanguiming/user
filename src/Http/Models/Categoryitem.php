<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Content
 *
 * @author tgm
 */
namespace Core\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
class Categoryitem extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_item';
    public $timestamps = false;
    protected $guarded = array('id');
    private $treeData = array();

    //public $timestamps = false;
    //content 表 添加 video
    public function check($data)
    {

//        if (!empty()) {
//            return array('status' => 101, 'msg' => '用户名重复');
//        }
//
//        $result = $this->findByEmail($users['email']);
//        if (!empty($result)) {
//            return array('status' => 102, 'msg' => '用户邮箱重复');
//        }

        return array('status' => TRUE, 'msg' => '表单验证通过');
    }

    public function fullData($data)
    {
        return array(
            'id' => $data['id'],
            'catid' => $data['catid'],
            'name' => $data['name'],
            'parentid' => $data['parentid'],
            'descrition' => $data['descrition'],
            'thumb' => $data['thumb'],
            'sort' => isset($data['sort']) ? $data['sort'] : 0
        );
    }

    /**
     * 
     * 
     */
    public function add($data)
    {

        $this->attributes = $data;
        if ($this->save()) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }

    /**
     * 
     * 
     */
    public function edit($data)
    {

        Log::info("category_edit", $data);
        $category = $this->find($data['id']);
        $category->name = $data['name'];
        $category->description = $data['description'];
        $category->catid = $data['catid'];
        $category->template = $data['template'];
        $category->thumb = $data['thumb'];
        $category->parentid = !empty($data['parentid']) ? $data['parentid'] : NULL;

        if ($category->save()) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }

    public function des($arr)
    {
        if ($this->destroy($arr)) {
            return array('status' => TRUE, 'msg' => '删除成功！');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败！');
        }
    }

    /**
     * 
     * @return type
     */
    public function getCategory($catid, $parentid = 'top', $type = 'html', $level = 0)
    {

        if (empty($this->treeData)) {
            $this->setTreeData($catid);
        }

        $data = $this->treeData;
        $arr = array();
        $str = '';

        $level = $level + 1;

        if (isset($data[$parentid])) {

            foreach ($data[$parentid] as $k => $v) {
                if ($type == 'html') {
                    $str.='<li><a href="#" onclick="onCategory(' . $v['id'] . ');" class="dropdown-toggle"><span class="menu-text">' . $v['name'] . '</span>';

                    if ($this->isChild($v['id'])) {

                        $str .= '<b class="arrow icon-angle-down"></b>';
                    }
                    $str .= '</a>';
                    $str.= '<ul class="submenu">' . $this->getCategory($v['catid'], $v['id'], $type, $level) . '</ul></li>';
                }
            }
        }

        return $str;
    }

    private function setTreeData($catid = 1)
    {

        $allData = self::whereRaw('catid = ? ', array($catid))
                ->orderBy('sort')
                ->get()
                ->toArray();

        $rtnArr = array();

        foreach ($allData as $dk => $dv) {

            if (empty($dv['parentid'])) {
                $rtnArr['top'][$dv['id']] = $dv;
            } else {
                $rtnArr[$dv['parentid']][$dv['id']] = $dv;
            }
        }


        $this->treeData = $rtnArr;
    }

    private function isChild($id)
    {

        $count = $this::where('parentid', '=', $id)->count();

        if ($count) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @return type
     */
    public function getChild($catid, $parentid = 'top', $type = 'html', $level = 0)
    {

        if (empty($this->treeData)) {
            $this->setTreeData($catid);
        }

        $data = $this->treeData;
        $arr = array();
        $str = '';

        $level = $level + 1;

        if (isset($data[$parentid])) {


            foreach ($data[$parentid] as $k => $v) {
                if ($type == 'html') {

                    $str.='<li class="dd-item item-green" id="' . $v['id'] . '"><div class="dd-handle">' . $v['name'];

                    $str.= '<div class="pull-right action-buttons">'
                            . '<a class="blue" href="#" onclick="b_s_content(this)" title="编辑" editid="' . $v['id'] . '">
                                      <i class="icon-pencil bigger-130"></i>
                               </a>
                                <a class="red" href="#" onclick="del(' . $v['id'] . ');" title="删除">
                                  <i class="icon-trash bigger-130"></i>
                                </a>
                              </div>
                            </div>';

                    if ($this->isChild($v['id'])) {

                        $str.= '<ol class="dd-list">' . $this->getChild($v['catid'], $v['id'], $type, $level) . '</ol>';
                    }

                    $str .= "</li>";
                }
            }
        }

        return $str;
    }

    /**
     * 
     * @param string $data
     * @param type $categoryitem
     * @param type $parentid
     * @param type $separate
     */
    protected function getAll(&$data, $categoryitem, $parentid = NULL, $separate = '')
    {
        foreach ($categoryitem as $k => $v) {
            if ($v['parentid'] == $parentid) {
                $v['name'] = $separate . $v['name'];
                $data[] = $v;
                $this->getAll($data, $categoryitem, $v['id'], $separate . "--");
            }
        }
    }

    /**
     * 得到栏目并分级
     * @param type $id
     * @return array
     */
    public function getShowCategory($id)
    {
        $categoryitem = Categoryitem::where('catid', '=', $id)->orderBy('sort')->get();

        $data = array();
        $datas = array();
        $this->getAll($data, $categoryitem);

        foreach ($data as $item) {
            $datas[$item['id']] = $item['name'];
        }
        return $datas;
    }

    /**
     * 或取二级分类
     * @return array()
     */
    public function getFirstChildId($parentid = null)
    {

        if (empty($parentid)) {
            $parentid = $this->parentid;
        }

        $arr = $this->where('parentid', $parentid)->select('id')->get()->toArray();

        return $arr;
    }

    /**
     * 获取二级分类
     * @return str 
     */
    public function getFirstChildIdStr($parentid = null)
    {

        if (empty($parentid)) {
            $parentid = $this->parentid;
        }

        $arr = $this->getFirstChildId($parentid);

        $rtnStr = '';
        $count = count($arr);

        for ($i = 0; $i < $count; $i++) {
            if ($count == $i + 1) {
                $rtnStr .= $arr[$i]['id'];
            } else {
                $rtnStr .= $arr[$i]['id'] . '","';
            }
        }

        return $rtnStr;
    }

    /**
     * 
     * @return type
     */
    public function setCategoryTree($catid)
    {

        $user_id = Input::get('userid');
        $user_arr = array();
        $catgorys = Categoryitem::where('catid', $catid)->select('id', 'name', 'catid', 'parentid')->get()->toArray();

        $user_temp = DB::table('user_category')->where('user_id', intval($user_id))->first();

        if ($user_temp) {
            $user = $user_temp->value;
            $user_str = rtrim($user, '|');
            $user_arr = explode("|", $user_str);
        }

        foreach ($catgorys as $k => $v) {

            $select = FALSE;
            if ($user_arr) {
                if (in_array($v['id'], $user_arr)) {
                    $select = TRUE;
                }
            }
            if (!$v['parentid']) {
                $is_ok = Categoryitem::where('parentid', $v['id'])->count();
                if ($is_ok) {
                    $rs['data'][$k] = array('name' => '<i onclick="category(' . $v['id'] . ')">' . $v['name'] . '</i>', 'type' => 'folder', 'additionalParameters' => $this->getChCategory($v['id'], $user_arr));
                } else {
                    $rs['data'][$k] = array('name' => '<i onclick="category(' . $v['id'] . ')">' . $v['name'] . '</i>', 'type' => 'item', 'catid' => $v['id'], 'onclick' => 'aa()', 'selected' => $select);
                }
            }
        }

        $rs['status'] = true;

        return Response::json($rs);
    }

    /**
     * 子栏目
     * @param type $catid
     * @return type
     */
    private function getChCategory($catid, $user_arr)
    {
        $data = array();
        $catgorys = Categoryitem::where('parentid', $catid)->select('id', 'name', 'catid')->get()->toArray();

        foreach ($catgorys as $k => $v) {

            $select = FALSE;
            if (count($user_arr)) {
                if (in_array($v['id'], $user_arr)) {
                    $select = TRUE;
                }
            }
            $is_ok = Categoryitem::where('parentid', $v['id'])->count();
            if ($is_ok) {
                $data['children'][] = array('name' => '<i onclick="category(' . $v['id'] . ')">' . $v['name'] . '</i>', 'type' => 'folder', 'additionalParameters' => $this->getChCategory($v['id'], $user_arr));
            } else {
                $data['children'][] = array('name' => '<i onclick="category(' . $v['id'] . ')">' . $v['name'] . '</i>', 'type' => 'item', 'catid' => $v['id'], 'selected' => $select);
            }
        }

        return $data;
    }
  
//获取投票的类型
    public function findtype($catid){
      $item = new Categoryitem;
      $where = array('catid=' => $catid);
      $result = $item->where($where);
       return $result;
    }

}
