<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>角色管理</title>
      
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace.min.css" />
		
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-fonts.css" />
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-skins.min.css" />
        <link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/font-awesome.min.css" />
		
		
        <style>
            /*删除按钮红色样式*/
            body .del-class .layui-layer-btn .layui-layer-btn0{background:#CD8A37;} 
			.table thead tr{
						background:#ffffff
			}
        </style>

    </head>
    <body class="gray-bg">
        
        <!-- content -->
        <div class="page-content">
            <!--main-container-->
            <div class="main-container container-fluid">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>
                <div class="row-fluid">
                        <!-- 工具栏 -->
                        <div class="span12">
                           
                        </div><!-- 工具栏 end -->

                        <!-- 列表内容 -->
                        <div role="grid" class="dataTables_wrapper" id="sample-table-2_wrapper">
                            <div class="span7">
                                 <table id="sample-table-2" class="table table-striped table-bordered table-hover  dataTable">
                                    <!-- 列表标题 -->
                                    <thead >
                                        <tr>
                                            <th>ID</th>
                                            <th> 角色名称(用户组)</th>
                                            <th>用户数</th>
                                            <th> 创建时间</th>
                                            <th class="hidden-480">
                                                <i class="icon-time bigger-110 hidden-phone"></i>
                                                操作
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- 列表标题 end -->

                                    <!-- 加载数据列表 -->
                                    <tbody>
                                    </tbody>
                                   <!-- 加载数据列表 end -->
                                </table>

                                <!-- 底部操作栏 -->
								<div class="row-fluid" style="display:none">
                                    <div class="span4">

                              <!--<button class="btn btn-small btn-primary"><i class="fa fa-trash-o"></i>删除</button>
                                  <button class="btn btn-small btn-primary"><i class="icon-undo bigger-110"></i>回复</button>
                                <button class="btn btn-small btn-danger"><i class="icon-trash bigger-110"></i>删除</button>-->


                                    </div>
                                    <div class="span4">
                                        <div class="dataTables_info" id="sample-table-2_info">
                                            共有<span id="pagetotal"></span>条记录&nbsp;&nbsp;&nbsp;每页
                                            <input type="text" value="" id="pagesize" size="3" name="pagesize" style="width:42px;"> 条</div>
                                    </div>
                                    <div class="span4">
                                        <div class="dataTables_paginate paging_bootstrap pagination" style="margin: 0px 0;">
                                            <ul  id="pagination">
                                                <li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li class="next"><a href="#"><i class="icon-double-angle-right"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
								
                                <!-- 底部操作栏 end -->
                            </div>
								
							 <div class="ibox-content span5" >
                                <div class="col-sm-6">
                                    <div id="treeview7" class="test">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h5>
                                        角色设置：
									 <div class="ibox-title" style="float:right;">
										<button class="btn btn-primary" id="regsub" type="submit" onclick="add(0);">添加角色</button>
										
									</div>
                                    </h5>
                                    <hr>
                                    <div id="event_output">
                                    </div>
                                </div>
                                <div class="clearfix">
                                </div>
                            </div>
                      	

                        </div>
                        <!-- 列表内容 end -->
                </div>
            </div>
            <!--main-container end-->
        </div> 
        <!-- content end-->
		
<!-- 操作后提示框 -->
        <div id="wt-alert" class="hide" style="margin-bottom:-1.5em"></div>
        <form id="wt-category" class="modal fade hide form-horizontal" method="post" tabindex="-1" onsubmit="return false;" enctype="multipart/form-data"></form>
        <form id="wt-field" class="modal fade hide form-horizontal" method="post" tabindex="-1" onsubmit="return false;"></form>
<!-- 操作后提示框 -->

        <!-- 全局js -->
        <script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
        <script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>
        <!-- // <script src="{{ASSET_URL}}hplus/js/content.mine209.js?v=1.0.0"></script> -->

        <!-- layer javascript -->
        <script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
        <script src="{{ASSET_URL}}system/admin/js/table/weitac.js"></script>       
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/weitac.table.js"></script>
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.pagination.js"></script>   

		<script src="{{ASSET_URL}}user/admin/js/fuelux/fuelux.ntree.min.js"></script>
        <script src="{{ASSET_URL}}system/admin/js/ace-elements.min.js"></script>
		
		

        <script type="text/javascript">
            var manage_operation = '<td><button class="btn btn-info btn-mini" type="button" onclick="add({role_id})">修改</button> <button class="btn btn-danger btn-mini" type="button" onclick="del({role_id})">删除</button></td>';
            var row_template = '<tr>';
            //row_template += '<td class="center"><label><input type="checkbox" class="ace" /><span class="lbl"></span></label></td>';
            row_template += '<td>{role_id}</td>';
            row_template += '<td>{name}</td>';
            row_template += '<td>{system}</td>';
            row_template += '<td>{created_at}</td>';
            //row_template += '<td class="hidden-phone">{type}</td>';
            //row_template +='<td>{isweixin}</td>';
            row_template += manage_operation;
             row_template += '</tr>';
            var tableApp = new ct.table('#sample-table-2', {
                rowIdPrefix: 'row_',
                pageSize:10000,
                rowCallback: 'init_row_event',
                jsonLoaded: json_loaded,
                dblclickHandler: '',
                template: row_template,
                baseUrl: '{{URL::route('admin.core.user.roleuser.ajaxindex')}}?orderby=role_id|asc'
            });
            function json_loaded(a) {

                $('#pagetotal').html(a.total);
                for (d = 0; a.data[d]; d++) {
                    if (a.data[d]) {
                        a.data[d].key = d + 1;
                    }
                }
            }


            function init_row_event(id, tr)
            {


            }
            $(function () {

                tableApp.load();
                $('#pagesize').val(tableApp.getPageSize());
                $('#pagesize').blur(function () {
                    var p = $(this).val();
                    tableApp.setPageSize(p);
                    tableApp.load();
                });


            });

        </script>
      
    </body>

</html>

<script>
			var tree;

            function add(status) {
                //判断status的状态 =a 则是添加  =b则是修改
                //将input框  和目录结构 拼接上去
                $("#event_output").empty();
                var h = '';
                if (status == 0) {
                    h = '<form id="wt-content"><div class="form-group"><label>角色名称：<input type="text" id="name" name="name"   placeholder="请输入角色名称" ></div></label>';
                    h += '<div class="form-group"><label>简介名称：<input type="text" id="description" name="description"  placeholder="请输入简介" ></div></label>';
                    h += '<div class=" header-color-blue">';
                    h += '<h7 class="lighter smaller"></h7>';
                    h += '</div>';
                    h += '<div class="widget-body" style="border:1px solid #FFFFFF">';
                    h += '<div class="widget-main padding-8">';
                    h += '<div id="treeview" class="tree"></div>';
                    h += '</div></div><br/>';
                    h += '<div><button class="btn btn-info btn-mini" id="regsub" type="button" onclick="acadd();">保存内容</button>&nbsp;&nbsp;&nbsp;';
                    h += '<button class="btn btn-small" type="button" onclick="Closes()">取消</button></div></form>';
                    //将设置好的内容插入进去
                    var act_url = "{{URL::route('admin.core.user.roleuser.role_aca')}}";
                    var role_id = '';
                    tree_data(act_url,role_id);
                    $("#event_output").prepend(h);
                } else {
                    //修改数据
                    var role_id = status;
                    $.ajax({
                        type: 'get',
                        url: '{{URL::route('admin.core.user.roleuser.edit')}}?role_id=' + role_id,
                        //data:role_id,
                        async: false,
                        //需要将ajax改为同步执行
                        //cache:false,
                        success: function(data) {
                            $.each(data,
                            function(i, v) {
                                h = '<form id="wt-content"><div class="form-group"><label>角色名称：<input type="text" id="name" name="name"  value=' + v.name + ' ></div></label>';
                                h += '<div class="form-group"><label>简介名称：<input type="text" id="description" name="description" value=' + v.description +'></div></label>';
                                h += '<input type="hidden" id="role_id" name="role_id" value=' + v.role_id + '>';
                                h += '<div class=" header-color-blue">';
                                h += '<h7 class="lighter smaller"></h7>';
                                h += '</div>';
                                h += '<div class="widget-body" style="border:1px solid #FFFFFF">';
                                h += '<div class="widget-main padding-8">';
                                h += '<div id="treeview" class="tree"></div>';
                                h += '</div></div><br/>';
                                h += '<div><button class="btn btn-info btn-mini" id="regsub" type="button" onclick="updaterold();">保存内容</button>&nbsp;&nbsp;&nbsp;';
                                h += '<button class="btn btn-small" type="button" onclick="Closes()">取消</button></div></form>';
                            });
                            //将设置好的内容插入进去
                            var act_url = "{{URL::route('admin.core.user.roleuser.roleidaca')}}";
                            tree_data(act_url, role_id);
                            $("#event_output").prepend(h);
                        },
                        error: function(data) {
                            layer.confirm('加载失败', {
                                icon: 3
                            },
                            function(index) {
                                layer.close(index); //关闭
                            });
                        }
                    });

                }

            }

            function tree_data(act_url, role_id) {
                jQuery(function($) {

                    // ------------------------------------------------------  组织树 的 json数据 
                    var DataSourceTree = function(options) {
                        this._data = options.data;
                        this._delay = options.delay;
                    }

                    DataSourceTree.prototype.data = function(options, callback) {
                        var self = this;
                        var $data = null;

                        if (! ("name" in options) && !("type" in options)) {
                            $data = this._data;
                            callback({
                                data: $data
                            });
                            return;
                        } else if ("type" in options && options.type == "folder") {
                            if ("additionalParameters" in options && "children" in options.additionalParameters) $data = options.additionalParameters.children;
                            else $data = {} //no data
                        }

                        // 如果数据不为空，则直接放入数据
                        if ($data != null) callback({
                            data: $data
                        });
                    };

                    var treeDataSource = '';

                    $.ajax({
                        //url: "{{URL::route('admin.core.user.roleuser.role_aca')}}",//admin.user.ajax.tree admin.aca.ajax.tree
                        url: act_url,
                        //admin.user.ajax.tree admin.aca.ajax.tree
                        type: 'GET',
                        dataType: 'json',
                        data: 'role_id=' + role_id,
                        success: function(response) {
                            if (response.status) {
                                treeDataSource = new DataSourceTree({
                                    data: response.data
                                });
                            } else {
                                layer.alert(response.msg);
                            }

                            tree = $('#treeview').ace_tree({
                                dataSource: treeDataSource,
                                multiSelect: true,
                                // 允许多选
                                allItems: true,
                                // 是否生成所有节点	默认为 false 不生成全部节点
                                loadingHTML: '',
                                // 等待读取图标 <div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>
                                'open-icon': 'icon-minus',
                                'close-icon': 'icon-plus',
                                'selectable': true,
                                // 是否允许选择
                                'selected-icon': 'icon-ok',
                                'unselected-icon': 'icon-remove'
                            });
                        },
                        error: function(response) {
                           layer.alert('网络通信错误！');
                        }
                    })

                });

            }

            var acaString = '';	
            // 验证
            function check(obj,flag) {
                 acaString = '';

                if (flag) {
                    // 获取所有选中分支的 数据 
                    $.each(tree.data('tree').selectedItems(),
                    function(k, v) {
                        acaString += v.parentid + '|';
                    });

                }
                return true;
            }

            //添加数据	
            function acadd() {
				var name = $("#name").val();
				//判断角色名称不能为空
				if(name.length==0){
						layer.confirm('角色名不能为空', {
							icon: 2
						},
						function(index) {
							layer.close(index); //关闭
						});
					return false;
				}
                var obj = $('#wt-content');
                var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引	
                var actionUrl = "{{URL::route('admin.core.user.roleuser.insert')}}";
               
				if (check(obj, true)) {

                    $.ajax({
                        type: 'get',
                        url: actionUrl,
                        data: obj.serialize() + '&aca=' + acaString,
                        cache: false,
                        success: function(data) {
                            // 验证不通过
                            if (data.status != true) {
                                layer.confirm(data.msg, {
                                    icon: 2
                                },
                                function(index) {
                                    layer.close(index); //关闭
                                });
                                layer.closeAll('iframe'); //执行关闭
                            }
                            // 验证通过
                            else {
                                layer.confirm('保存成功！', {
                                    icon: 1
                                },
                                function(index) {
                                    layer.close(index); //关闭
                                    //触法取消方法
                                    Closes();
                                    tableApp.load();
                                })

                            }
                        },

                        error: function(data) {
                            layer.confirm('网络通信错误！', {
                                icon: 3
                            },
                            function(index) {
                                layer.close(index); //关闭
                            });
                        }
                    });
                }
            }

            //修改
            function updaterold() {
				//判断角色名不能为空
				var name = $("#name").val();
				//判断角色名称不能为空
				if(name.length==0){
					layer.confirm('角色名不能为空', {
							icon: 2
						},
						function(index) {
							layer.close(index); //关闭
						});
					return false;
				}	
				
                var obj = $('#wt-content');
                var actionUrl = "{{URL::route('admin.core.user.roleuser.update')}}";
                var index = parent.layer.getFrameIndex(window.name);
                var role_id = $("#role_id").val();
                if (check(obj, true)) {

                    $.ajax({
                        type: 'get',
                        url: actionUrl,
                        data: obj.serialize() + '&aca=' + acaString + '&role_id=' + role_id,
                        cache: false,
                        success: function(data) {
                            // 验证不通过
                            if (data.status != true) {
                                layer.confirm(data.msg, {
                                    icon: 2
                                },
                                function(index) {
                                    layer.close(index); //关闭
                                });
                            }
                            // 验证通过
                            else {
                                layer.confirm('修改成功！', {
                                    icon: 1
                                },
                                function(index) {
                                    layer.close(index); //关闭
                                    Closes();
                                    tableApp.load();
                                });
                            }
                        },

                        error: function(data) {
                            layer.confirm('网络通信错误！', {
                                icon: 3
                            },
                            function(index) {
                                layer.close(index); //关闭
                            });
                        }
                    });
                }
            }

            // 删除
            function del(role_id) {

                //询问框
                layer.confirm('您确定要删除该信息？', {
                    title: ['危险操作', 'color:#fff;background:#fbb450'],
                    //提示标题
                    btn: ['确定(Y)', '取消(N)'],
                    //按钮
                    skin: 'del-class',
                    //可自定义一个皮肤类，然后通过css来设置对应的样式
                },
                function() {
                    // layer.msg('的确很重要', {icon: 1});
                    var obj = 'role_id=' + role_id;
                    var actionUrl = "{{URL::route('admin.core.user.roleuser.del')}}";
                    $.ajax({
                        type: 'get',
                        url: actionUrl,
                        data: obj,
                        async: false,
                        //cache:false,
                        success: function(data) {
                            // 验证不通过
                            if (data.status != true) {
                                layer.confirm(data.msg, {
                                    icon: 2
                                },
                                function(index) {
                                    layer.close(index); //关闭
                                });

                            }
                            // 验证通过
                            else {
                                // layer.msg('删除成功', {shift: 5});   //数字可以是0--6 ，5表示渐变显示
                                //  layer.close(index); //关闭
                                //         tableApp.load();
                                layer.confirm('删除成功！', {
                                    icon: 1
                                },
                                function(index) {
                                    layer.close(index); //关闭
                                    tableApp.load();
                                });

                            }
                        },
                        error: function(data) {
                            layer.confirm('网络通信错误！', {
                                icon: 3
                            },
                            function(index) {
                                layer.close(index); //关闭
                            });
                        }
                    });

                },
                function() {
                    layer.msg('操作已取消！', {
                        shift: 5
                    }); //数字可以是0--6 ，5表示渐变显示
                });

            }
            //添加取消
            function Closes() {
                $("#event_output").empty();
                tableApp.load();
            }
</script>