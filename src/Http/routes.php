<?php
// 登录界面
Route::get('/admin/login', array('as'=>'admin.user.login', 'uses'=>'LoginController@login'));
// ajax登录验证
Route::post('/admin/do/login', array('as'=>'admin.user.doLogin', 'uses'=>'LoginController@doLogin'));
// 用户退出
Route::get('/admin/logout', array('as'=>'admin.user.logout', 'uses'=>'LoginController@logout'));
// 临时创建的管理员用户
Route::get('/admin/init', array('as'=>'admin.init', 'uses'=>'LoginController@init'));

//普通用户显示
//Route::get('/admin/user/index', array('as'=>'admin.user.index', 'uses'=>'UserController@index'));
//Route::get('/admin/user/ajaxIndex', array('as'=>'admin.user.ajaxIndex', 'uses'=>'UserController@ajaxIndex'));
//用户添加
Route::get('/admin/user/add', array('as'=>'admin.user.add', 'uses'=>'UserController@add'));
//用户添加插入
Route::post('/admin/user/insert', array('as'=>'admin.user.insert', 'uses'=>'UserController@insert'));
// 用户编辑	
Route::get('/admin/core/user/user/edit', array('as'=>'admin.core.user.edit', 'uses'=>'UserController@edit'));
Route::post('/admin/core/user/user/update', array('as'=>'admin.core.user.update', 'uses'=>'UserController@update'));



// 用户软删除
Route::get('/user/delete', array('before'=>'user:delete', 'as'=>'admin.user.delete', 'uses'=>'UserController@delete'));
// 用户彻底删除
Route::get('/user/destroy', array('before'=>'user:destroy', 'as'=>'admin.user.destroy', 'uses'=>'UserController@destroy'));
//查看用户信息
Route::get('/user/show', array('as'=>'admin.user.show', 'uses'=>'UserController@show'));
//个人记录统计
Route::get('/user/showAjax', array('as'=>'admin.user.showAjax', 'uses'=>'UserController@showAjax'));
//中奖记录
Route::get('/user/zjAjax', array('as'=>'admin.user.zjAjax', 'uses'=>'UserController@zjAjax'));
//关键字加载
Route::get('/user/kwAjax', array('as'=>'admin.user.kwAjax', 'uses'=>'UserController@kwAjax'));
//活动页面
Route::get('/user/hddetail', array('as'=>'admin.user.hddetail', 'uses'=>'UserController@hddetail'));
//活动加载
Route::get('/user/hdAjax', array('as'=>'admin.user.hdAjax', 'uses'=>'UserController@hdAjax'));
//修改密码
Route::get('/user/pwd', array('as'=>'admin.user.pwd', 'uses'=>'UserController@pwd'));
Route::post('/user/do.pwd', array('as'=>'admin.user.do.pwd', 'uses'=>'UserController@doPwd'));

//权限树ajax源
// Route::any('/aca.tree', array('as'=>'admin.aca.ajax.tree', 'uses'=>'AcaController@ajaxTree'));
//用户权限
Route::get('/admin/core/user/aca/index', array('as'=>'admin.core.user.aca.index', 'uses'=>'AcaController@index'));
Route::get('/admin/core/user/aca/ajaxindex', array('as'=>'admin.core.user.aca.ajaxindex', 'uses'=>'AcaController@ajaxindex'));
Route::get('/admin/core/user/aca/add', array('as'=>'admin.core.user.aca.add', 'uses'=>'AcaController@add'));
Route::get('/admin/core/user/aca/sid', array('as'=>'admin.core.user.aca.sid', 'uses'=>'AcaController@sid'));
Route::get('/admin/core/user/aca/insert', array('as'=>'admin.core.user.aca.insert', 'uses'=>'AcaController@insert'));
Route::get('/admin/core/user/aca/edit', array('as'=>'admin.core.user.aca.edit', 'uses'=>'AcaController@edit'));
Route::get('/admin/core/user/aca/update', array('as'=>'admin.core.user.aca.update', 'uses'=>'AcaController@update'));
Route::get('/admin/core/user/aca/del', array('as'=>'admin.core.user.aca.del', 'uses'=>'AcaController@del'));
Route::get('/admin/core/user/aca/export', array('as'=>'admin.core.user.aca.export', 'uses'=>'AcaController@export'));
Route::get('/admin/core/user/aca/import_add', array('as'=>'admin.core.user.aca.import_add', 'uses'=>'AcaController@import_add'));
Route::post('/admin/core/user/aca/import_insert', array('as'=>'admin.core.user.aca.import_insert', 'uses'=>'AcaController@import_insert'));
Route::post('/admin/core/user/aca/import_upload', array('as'=>'admin.core.user.aca.import_upload', 'uses'=>'AcaController@import_upload'));
//===================================角色基本的增 删  改 查===================================
Route::get('/admin/core/user/roleuser/index', array('as'=>'admin.core.user.roleuser.index', 'uses'=>'RoleUserController@index'));
Route::get('/admin/core/user/roleuser/ajaxindex', array('as'=>'admin.core.user.roleuser.ajaxindex', 'uses'=>'RoleUserController@ajaxindex'));
Route::get('/admin/core/user/roleuser/add', array('as'=>'admin.core.user.roleuser.add', 'uses'=>'RoleUserController@add'));
Route::get('/admin/core/user/roleuser/insert', array('as'=>'admin.core.user.roleuser.insert', 'uses'=>'RoleUserController@insert'));
Route::get('/admin/core/user/roleuser/edit', array('as'=>'admin.core.user.roleuser.edit', 'uses'=>'RoleUserController@edit'));
Route::get('/admin/core/user/roleuser/update', array('as'=>'admin.core.user.roleuser.update', 'uses'=>'RoleUserController@update'));
Route::get('/admin/core/user/roleuser/del', array('as'=>'admin.core.user.roleuser.del', 'uses'=>'RoleUserController@del'));
//--------目录结构
Route::get('/admin/core/user/roleuser/role_aca', array('as'=>'admin.core.user.roleuser.role_aca', 'uses'=>'RoleUserController@role_aca'));
Route::get('/admin/core/user/roleuser/roleidaca', array('as'=>'admin.core.user.roleuser.roleidaca', 'uses'=>'RoleUserController@roleidaca'));

// 用户管理列表
Route::get('/admin/core/user/user/index', array('as'=>'admin.core.user.user.index', 'uses'=>'UserController@index'));
//管理员显示
Route::get('/admin/core/user/useradmin/admin', array('as'=>'admin.core.user.useradmin.admin', 'uses'=>'UserAdminController@index'));
//管理员加载
Route::get('/admin/core/user/useradmin/ajaxadmin', array('as'=>'admin.core.user.useradmin.ajaxadmin', 'uses'=>'UserAdminController@ajaxIndex'));
//-----新加-------
Route::get('/admin/core/user/useradmin/award', array('as'=>'admin.core.user.useradmin.award', 'uses'=>'UserAdminController@award'));
Route::get('/admin/core/user/useradmin/more', array('as'=>'admin.core.user.useradmin.more', 'uses'=>'UserController@more'));

//--------------------------------2016-10-15-----齐鲁频道管理员快捷方式--------------------------------
Route::get('/admin/core/user/user/qiluindex', array('as'=>'admin.core.user.qiluindex', 'uses'=>'UserAdminController@qiluindex'));
Route::get('/admin/core/user/user/qiluajax', array('as'=>'admin.core.user.qiluajax', 'uses'=>'UserAdminController@qiluajax'));

Route::get('/admin/core/user/user/qiluadd', array('as'=>'admin.core.user.qiluadd', 'uses'=>'UserAdminController@qiluadd'));

Route::get('/admin/core/user/user/qiluedit', array('as'=>'admin.core.user.qiluedit', 'uses'=>'UserAdminController@qiluedit'));








Route::get('/user/index', array('as'=>'admin.user.index', 'uses'=>'UserController@index'));
Route::get('/admin/core/user/user/ajaxindex', array('as'=>'admin.core.user.user.ajaxindex', 'uses'=>'UserController@ajaxIndex'));
Route::any('/user.pdelete', array('as'=>'admin.user.pdelete', 'uses'=>'UserController@pdelete'));
Route::any('/user.pexamine', array('as'=>'admin.user.pexamine', 'uses'=>'UserController@pexamine'));
Route::any('/user.ban', array('as'=>'admin.user.ban', 'uses'=>'UserController@ban'));

//------------------------------栏目权限分配路由------------------------------------------------------
Route::get('/admin/user/setCategoryShow', array('as'=>'admin.user.setCategoryShow', 'uses'=>'UserAdminController@setCategoryShow'));
Route::get('/admin/user/setCategoryTree', array('as'=>'admin.user.ajax.tree', 'uses'=>'UserAdminController@setCategoryTree'));
Route::post('/admin/user/setCategory/update', array('as'=>'admin.user.setCategory.update', 'uses'=>'UserAdminController@setCategoryUpdate'));

//---------------------------------区块权限分配路由-----------------------------------------------------
Route::get('/admin/user/setSectionShow', array('as'=>'admin.user.setSectionShow', 'uses'=>'UserAdminController@setSectionShow'));
Route::get('/admin/user/setSectionTree', array('as'=>'admin.user.ajax.secton.tree', 'uses'=>'UserAdminController@setSectionTree'));
Route::post('/admin/user/setSection/update', array('as'=>'admin.user.setSection.update', 'uses'=>'UserAdminController@setSectionUpdate'));


//---------------------------------菜单权限分配路由----------------------------------
Route::any('/admin/user/setMenushow',array('as'=>'admin.user.setMenushow','uses'=>'UserAdminController@setMenushow'));
Route::any('/admin/user/setMenuTree',array('as'=>'admin.user.ajax.setMenu.tree','uses'=>'UserAdminController@setMenuTree'));
Route::any('/admin/user/setMenu/update',array('as'=>'admin.user.setMenu.update','uses'=>'UserAdminController@setMenuupdae'));

// ----------------------------------------------------------  用户登录日志 -------------------------------------------------------- //
// 登录日志列表
Route::get('/userLoginLog', array('as'=>'admin.userLoginLog', 'uses'=>'UserLoginLogController@index'));
Route::get('/userLoginLog/index', array('as'=>'admin.userLoginLog.index', 'uses'=>'UserLoginLogController@index'));
Route::get('/userLoginLog/ajaxIndex', array('as'=>'admin.userLoginLog.ajaxIndex', 'uses'=>'UserLoginLogController@ajaxIndex'));
Route::get('/userLoginLog/backup', array('before'=>'userLoginLog:backup', 'as'=>'admin.userLoginLog.backup', 'uses'=>'UserLoginLogController@backup'));

// ----------------------------------------------------------  用户操作日志 -------------------------------------------------------- //
// 操作日志列表
Route::get('/userLog', array('as'=>'admin.userLog', 'uses'=>'UserLogController@index'));
Route::get('/userLog/index', array('as'=>'admin.userLog.index', 'uses'=>'UserLogController@index'));
Route::get('/userLog/ajaxIndex', array('as'=>'admin.userLog.ajaxIndex', 'uses'=>'UserLogController@ajaxIndex'));
Route::get('/userLog/backup', array('before'=>'userLog:backup', 'as'=>'admin.userLog.backup', 'uses'=>'UserLogController@backup'));

//--------------------------------------------------------------积分管理-------------------------------------------
Route::get('/admin/user/pdetail/index',array('as'=> 'admin.user.pdetail.index', 'uses'=> 'UserPointDetailController@index'));
Route::any('/admin/user/pdetail/ajaxindex', array('as' => 'admin.user.pdetail.ajaxindex', 'uses' => 'UserPointDetailController@ajaxIndex'));

//积分规则查看
Route::any('admin/user/userpoint', array('as'=>'admin.user.userpoint', 'uses'=>'UserPointController@index'));
Route::any('/admin/userpoint/ajaxIndex', array('as'=>'admin.userpoint.ajaxIndex', 'uses'=>'UserPointController@ajaxIndex'));

//积分操作日志
Route::get('admin/user/pointlogs/index', array('as'=>'admin.user.pointlogs.index', 'uses'=>'PointLogsController@index'));
Route::any('admin/user/pointlogs/ajaxIndex', array('as'=>'admin.user.pointlogs.ajaxIndex', 'uses'=>'PointLogsController@ajaxIndex'));
Route::get('admin/user/pointlogs/add', array('as'=>'admin.user.pointlogs.add', 'uses'=>'PointLogsController@add'));
Route::get('admin/user/pointlogs/search', array('as'=>'admin.user.pointlogs.search', 'uses'=>'PointLogsController@search'));
Route::post('admin/user/pointlogs/insert', array('as'=>'admin.user.pointlogs.insert', 'uses'=>'PointLogsController@insert'));

// 数据统计
Route::get('admin/user/pointstatistics/index', array('as'=>'admin.user.pointstatistics.index', 'uses'=>'PointStatisticsController@index'));
Route::get('admin/user/pointstatistics/ajaxIndex', array('as'=>'admin.user.pointstatistics.ajaxIndex', 'uses'=>'PointStatisticsController@ajaxIndex'));
Route::get('admin/user/pointstatistics/getrank', array('as'=>'admin.user.pointstatistics.getrank', 'uses'=>'PointStatisticsController@getrank'));
Route::get('admin/user/pointstatistics/getability', array('as'=>'admin.user.pointstatistics.getability', 'uses'=>'PointStatisticsController@getability'));
Route::get('admin/user/pointstatistics/getsex', array('as'=>'admin.user.pointstatistics.getsex', 'uses'=>'PointStatisticsController@getsex'));
Route::get('admin/user/pointstatistics/gettrend', array('as'=>'admin.user.pointstatistics.gettrend', 'uses'=>'PointStatisticsController@gettrend'));
Route::get('admin/user/pointstatistics/getgrade', array('as'=>'admin.user.pointstatistics.getgrade', 'uses'=>'PointStatisticsController@getgrade'));

//创建规则
Route::any('/admin/userpoint/add', array('as'=>'admin.userpoint.add', 'uses'=>'UserPointController@add'));
Route::post('/admin/userpoint/insert', array('as'=>'admin.userpoint.insert', 'uses'=>'UserPointController@insert'));

//积分规则修改
Route::get('/admin/userpoint/edit', array('as'=>'admin.userpoint.edit', 'uses'=>'UserPointController@edit'));
Route::any('/admin/userpoint/update', array('as'=>'admin.userpoint.update', 'uses'=>'UserPointController@update'));
//积分规则删除
Route::any('/admin/userpoint/delete', array('as'=>'admin.userpoint.delete', 'uses'=>'UserPointController@delete'));

//积分配置
Route::get('/admin/userpoint/set', array('as'=>'admin.userpoint.set', 'uses'=>'UserPointController@set'));
Route::post('/admin/userpoint/setupdate', array('as'=>'admin.userpoint.setupdate', 'uses'=>'UserPointController@setupdate'));
Route::post('/admin/userpoint/upload',array('as'=>'admin.userpoint.upload','uses'=>'UserPointController@upload'));
Route::post('/admin/userpoint/pointinput',array('as'=>'admin.userpoint.pointinput','uses'=>'UserPointController@pointinput'));
Route::post('/admin/userpoint/pointaudit',array('as'=>'admin.userpoint.pointaudit','uses'=>'UserPointController@pointaudit'));
//------------------------11.17新加
Route::get('/admin/userpoint/classedit',array('as'=>'admin.userpoint.classedit','uses'=>'UserPointController@classedit'));
Route::post('/admin/userpoint/classupdate',array('as'=>'admin.userpoint.classupdate','uses'=>'UserPointController@classupdate'));
Route::get('/admin/userpoint/classdel',array('as'=>'admin.userpoint.classdel','uses'=>'UserPointController@classdel'));

//用户赚币路由
Route::get('admin/user/pointrule/index', array('as'=>'admin.user.pointrule.index', 'uses'=>'UserPointRuleController@index'));
Route::get('admin/user/pointrule/ajaxIndex', array('as'=>'admin.user.pointrule.ajaxIndex', 'uses'=>'UserPointRuleController@ajaxIndex'));
Route::get('admin/user/pointrule/add', array('as'=>'admin.user.pointrule.add', 'uses'=>'UserPointRuleController@add'));
Route::post('admin/user/pointrule/insert', array('as'=>'admin.user.pointrule.insert', 'uses'=>'UserPointRuleController@insert'));
Route::get('admin/user/pointrule/edit', array('as'=>'admin.user.pointrule.edit', 'uses'=>'UserPointRuleController@edit'));
Route::post('admin/user/pointrule/update', array('as'=>'admin.user.pointrule.update', 'uses'=>'UserPointRuleController@update'));
Route::get('admin/user/pointrule/delete', array('as'=>'admin.user.pointrule.delete', 'uses'=>'UserPointRuleController@delete'));


//-----------------------------------------------------------积分等级规则-------------------------------------------------------------//
Route::get('admin/user/usergrade/index',array('as'=>'admin.user.usergrade.index','uses'=>'UserGradeController@index'));
Route::get('admin/user/usergrade/ajaxIndex',array('as'=>'admin.user.usergrade.ajaxIndex','uses'=>'UserGradeController@ajaxIndex'));
Route::get('/admin/user/usergrade/add',array('as'=>'admin.user.usergrade.add','uses'=>'UserGradeController@add'));
Route::post('/admin/user/usergrade/insert',array('as'=>'admin.user.usergrade.insert','uses'=>'UserGradeController@insert'));
Route::get('/admin/user/usergrade/edit',array('as'=>'admin.user.usergrade.edit','uses'=>'UserGradeController@edit'));
Route::post('/admin/user/usergrade/update',array('as'=>'admin.user.usergrade.update','uses'=>'UserGradeController@update'));
Route::get('/admin/user/usergrade/del',array('as'=>'admin.user.usergrade.del','uses'=>'UserGradeController@del'));
Route::post('/admin/user/usergrade/upload',array('as'=>'admin.user.usergrade.upload','uses'=>'UserGradeController@upload'));


/*****************************************用户积分详情查询开始************************************/
Route::get('/admin/user/WxUserPointDetail/index',array('as'=>'admin.user.WxUserPointDetail.index','uses'=>'WxUserPointDetailController@index'));
Route::get('/admin/user/WxUserPointDetail/ajaxIndex',array('as'=>'admin.user.WxUserPointDetail.ajaxIndex','uses'=>'WxUserPointDetailController@ajaxIndex'));
Route::post('/admin/user/WxUserPointDetail/delete',array('as'=>'admin.user.WxUserPointDetail.delete','uses'=>'WxUserPointDetailController@delete'));
/*****************************************用户积分详情查询结束************************************/

Route::get('admin/user/WxUserPointDetail/demo',array('as'=>'admin.user.WxUserPointDetail.demo','uses'=>'WxUserPointDetailController@demo'));






//=================================部门信息=======================================
Route::get('/admin/user/User_DeparTmentController/index',array('as'=>'admin.user.User_DeparTmentController.index','uses'=>'User_DeparTmentController@index'));
Route::get('/admin/user/User_DeparTmentController/ajaxIndex',array('as'=>'admin.user.User_DeparTmentController.ajaxIndex','uses'=>'User_DeparTmentController@ajaxIndex'));
Route::get('/admin/user/User_DeparTmentController/add',array('as'=>'admin.user.User_DeparTmentController.add','uses'=>'User_DeparTmentController@add'));
Route::post('/admin/user/User_DeparTmentController/insert',array('as'=>'admin.user.User_DeparTmentController.insert','uses'=>'User_DeparTmentController@insert'));
Route::get('/admin/user/User_DeparTmentController/edit',array('as'=>'admin.user.User_DeparTmentController.edit','uses'=>'User_DeparTmentController@edit'));
Route::post('/admin/user/User_DeparTmentController/update',array('as'=>'admin.user.User_DeparTmentController.update','uses'=>'User_DeparTmentController@update'));
Route::get('/admin/user/User_DeparTmentController/delete',array('as'=>'admin.user.User_DeparTmentController.delete','uses'=>'User_DeparTmentController@delete'));
Route::get('/admin/user/User_DeparTmentController/daoru',array('as'=>'admin.user.User_DeparTmentController.daoru','uses'=>'User_DeparTmentController@daoru'));
Route::get('/admin/user/User_DeparTmentController/parentitb',array('as'=>'admin.user.User_DeparTmentController.parentitb','uses'=>'User_DeparTmentController@parentitb'));




//-----------------------------------------------5.25手动为用户添加积分和屏蔽开始-------------------------------
//手动为用户添加积分
Route::get('/admin/user/UsertableController/index',array('as'=>'admin.user.UsertableController.index','uses'=>'UsertableController@index'));
Route::get('/admin/user/UsertableController/ajaxIndex',array('as'=>'admin.user.UsertableController.ajaxIndex','uses'=>'UsertableController@ajaxIndex'));
Route::get('/admin/user/UsertableController/delete',array('as'=>'admin.user.UsertableController.delete','uses'=>'UsertableController@delete'));
//屏蔽
Route::get('/admin/user/UsertableController/indexshield',array('as'=>'admin.user.UsertableController.indexshield','uses'=>'UsertableController@indexshield'));
Route::get('/admin/user/UsertableController/ajaxIndexshield',array('as'=>'admin.user.UsertableController.ajaxIndexshield','uses'=>'UsertableController@ajaxIndexshield'));
Route::get('/admin/user/UsertableController/deleteshield',array('as'=>'admin.user.UsertableController.deleteshield','uses'=>'UsertableController@deleteshield'));
Route::get('/admin/user/UsertableController/add',array('as'=>'admin.user.UsertableController.add','uses'=>'UsertableController@add'));
Route::post('/admin/user/UsertableController/insert',array('as'=>'admin.user.UsertableController.insert','uses'=>'UsertableController@insert'));
Route::get('/admin/user/UsertableController/edit',array('as'=>'admin.user.UsertableController.edit','uses'=>'UsertableController@edit'));
Route::post('/admin/user/UsertableController/update',array('as'=>'admin.user.UsertableController.update','uses'=>'UsertableController@update'));
//单个数据：弹出手动为用户添加积分的页面（从某个应用点按钮弹出的页面）
Route::get('/admin/user/UsertableController/send', array('as' => 'admin.user.UsertableController.send', 'uses' => 'UsertableController@send'));
Route::post('/admin/user/UsertableController/postsend', array('as' => 'admin.user.UsertableController.postsend', 'uses' => 'UsertableController@postsend'));
//多个数据：弹出手动为用户添加积分的页面（从某个应用点按钮弹出的页面）
Route::get('/admin/user/UsertableController/sendes', array('as' => 'admin.user.UsertableController.sendes', 'uses' => 'UsertableController@sendes'));
Route::post('/admin/user/UsertableController/postsendes', array('as' => 'admin.user.UsertableController.postsendes', 'uses' => 'UsertableController@postsendes'));

//-----------------------------------------------5.25手动为用户添加积分和屏蔽开始-----------------------------------




