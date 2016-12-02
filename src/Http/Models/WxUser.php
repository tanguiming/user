<?php

/**
 * 微信内容
 * 
 * @author      tgm
 * @date        2014-014-16
 * @version     1.0
 */
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
class WxUser extends Model {

    protected $table = 'wx_wxuser';
    protected $primaryKey = 'id';
    protected $_where;
    protected $_order = 'id desc';
    static public $status = array('未推送', '已推送');
    static public $type = array('text' => '文本', 'image' => '图片', 'voice' => '音频', 'video' => '视频', 'location' => '位置', 'link' => '连接');
    public $timestamps = false;
    // static public $app = 'weixin-comment';
    // 设置的字段 key为字段 value为空的时候的默认值
    static public $settingFields = array('allowMulti' => 0);

    /**
     * 
     * @param type $date
     * @return type
     */
    public function check($date) {

        return array('status' => TRUE, 'msg' => '表单验证通过');
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function add($data) {

        $this->attributes = $data;
        if ($this->save()) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }

    /**
     * 添加总
     * @param type $data
     * @return type
     */
    public function edit($data) {

        $id = $data['id'];
        unset($data['id']);

        if ($this::where('id', '=', $id)->update($data)) {
            return array('status' => TRUE, 'msg' => '保存成功');
        } else {
            return array('status' => FALSE, 'msg' => '保存失败');
        }
    }

    /**
     * 
     * @param type $id
     */
    public function del($id) {

        $user = $this::find($id);

        if ($user->delete()) {
            return array('status' => TRUE, 'msg' => '删除成功!');
        } else {
            return array('status' => FALSE, 'msg' => '删除失败!');
        }
    }

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
        $this->_where = $data;
        // print_r($this->_where);exit;

        return $this;
    }

    /**
     *  获取条件
     */
    public function getWhere() {
        return $this->_where;
    }

    /**
     *  设置排序
     *
     * @param array $order 键值对  键为字段 值为排序类型
     * @return $this
     */
    public function setOrder($order = null) {
        if (empty($order) || !is_array($order))
            return $this;

        $orderString = '';

        foreach ($order as $field => $type) {
            $orderString .= $field . ' ' . $type . ',';
        }
        $this->_order = trim($orderString . $this->_order, ',');

        return $this;
    }

    /**
     *  获取排序
     */
    public function getOrder() {
        return $this->_order;
    }

    /**
     * 
     * @param type $data
     * @return type
     */
    public function getSearch($data) {
        return $datas;
    }

    /**
     *  分页查询
     * 
     * @param int       $page       当前页数
     * @param limit     $limit          每页显示条数
     */
    public function getListByPage($page = 0, $limit = 3) {
        $obj = $this;

        // 注册搜索条件
        if (!empty($this->_where)) {
            $obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
        }

        return $obj->offset($page)->limit($limit)
                        ->orderByRaw($this->_order)
                        ->get();
    }

    /**
     *  获取数据总数
     */
    public function getCount() {
        $obj = $this;

        if (!empty($this->_where)) {
            $obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
        }

        return $obj->count();
    }

    /**
     *  获取数组，返回给dataTables
     */
    public function fullDataForTables($data) {
        if (empty($data))
            return false;

        $rs = array();

        foreach ($data as $key => $val) {


            $rs[$key][] = '<label>
                    <input type="checkbox" class="ace" name="check[]" value="' . $val[$this->primaryKey] . '"/>
                    <span class="lbl"></span>
                </label>';
            $rs[$key][] = $val[$this->primaryKey];
            $rs[$key][] = $val['wxname']; //$this->getUser($val['FromUserName']);
            $rs[$key][] = $val['weixin'];
            $rs[$key][] = $val['winxintype'];
            $rs[$key][] = date('Y-m-d H:i:s', $val['createtime']);
            $rs[$key][] = $this->_getButton($val);
        }

        return $rs;
    }


    /**
     *  返回 按钮html
     *  
     *  @param array $val
     *  @return html
     */
    private function _getButton($comment) {
        $primaryKey = $comment[$this->primaryKey];

        $editUrl = URL::route('admin.weixin.user.edit', array('id' => $primaryKey));

        $html = <<<HTML
        <div class="visible-desktop">

                    <div class="btn btn-mini btn-info" title="中奖" onclick="$.weitac.formShow('$editUrl');">
                修改
            </div>
            <div class="btn btn-mini btn-danger" title="删除该参与用户" onclick="del($primaryKey);">
                删除
            </div>
        </div>
HTML;

        return $html;
    }

}
