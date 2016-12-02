<!DOCTYPE html>
<html>
    <head>
        <title>管理员</title>

        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href='{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css' rel='stylesheet' type='text/css'>
        <link href='{{ASSET_URL}}system/hplus/css/style1.min2964.css?v=3.0.0' rel='stylesheet' type='text/css'>
		<style>
			.dropdown-menu{
				 background-clip: padding-box;
				background-color: #fff;
				border: 1px solid rgba(0, 0, 0, 0.15);
				border-radius: 4px;
				box-shadow: 0 6px 12px rgba(0, 0, 0, 0.176);
				display: none;
				float: left;
				font-size: 14px;
				left: 0;
				list-style: outside none none;
				margin: 2px 0 0;
				min-width: 0px;
				padding: 5px 0;
				position: absolute;
				text-align: left;
				top: 100%;
				z-index: 1000;
			}
		</style>
    </head>
    <body>
        <div class="page-content">
            <div class="main-container container-fluid">
                <div id="headshow">
                    <button type="button" class="btn  btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i>添加</button>
                    <!--<button class="btn btn-warning btn-sm" type="button" onclick="edit()"><span><i class="fa fa-pencil"></i>编辑</span></button>-->
                    <button class="btn btn-danger btn-sm" type="button" onclick="delmore(null)"><span><i class="fa fa-times"></i>删除</span></button>
                </div>
                <table id="table"></table>
            </div>
        </div>


        <!-- 全局js -->
        <script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
        <script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>
		<script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
        <!-- 自定义js -->
        <script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/content.mine209.js?v=1.0.0"></script>
		
        <!-- boot-table -->
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/js/bootstrap-table.js"></script>
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>

        <script>

			$('#table').bootstrapTable({
				classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
				method: 'get',
				url: "qiluajax",
				//cache: false,
				height: $(window).height(),
				striped: true, //是否显示条纹的行。
				dataType: "json",
				//showHeader: false,// 去隐藏表头
				pagination: true,
				queryParamsType: "limit",
				singleSelect: false,
				pageSize: 15, //每页显示多少条
				pageList: [10, 25, 50, 100],
				pageNumber: 1,
				sidePagination: "server", //设置为服务器端分页
				search: true, //不显示 搜索框
				toolbar: "#headshow", //显示在头部的条，值为ID 和class
				//searchAlign: 'right',  
				//detailView:true,  设置为 True 可以显示详细页面模式。
				showRefresh: true,
				showToggle: true,
				contentType: "application/x-www-form-urlencoded",
				showColumns: true, //不显示下拉框选择显示的字段（选择显示的列）
				minimumCountColumns: 1, //是少显示多少个字段
				clickToSelect: true,
				queryParams: queryParams, //所带参数
				responseHandler: responseHandler, //服务端返回的参数
				columns: [{
				checkbox: true
				}, {
				field: 'user_id',
						title: 'ID',
						width: 30, //宽度
						align: 'center', //
						valign: 'middle',
						sortable: true  //是否排序
				}, {
				field: 'username',
						title: '用户名',
						width: 100, //宽度
					   // visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
				}, {
				field: 'nickname',
						title: '昵称',
						width: 100, //宽度
					   // visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
				}, {
						field: 'status',
						title: '状态',
						width: 50, //宽度
					   // visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
				}, {
					field: 'role',
					title: '角色',
					width: 300, //宽度
				}, {
					field: 'created_at',
					title: '注册时间',
					width: 160, //宽度
				   // visible: false, //刚开始是否显示此字段
				   // sortable: true  //是否排序
				}, {
					field: 'last_login',
					title: '最后时间',
					width: 160, //宽度
					//formatter: handle,
				}, {
					field: '',
					title: '操作',
					formatter: handle
				   // visible: false, //刚开始是否显示此字段
				   // sortable: true  //是否排序
				}],
				    onSearch: function (text) { // 事件
				   // alert("ddd");
				}
			});
			
			function handle(value, row, index) {
				console.log(row);
				return [
					'<div  class="btn-group">\
						<button data-toggle="dropdown" class="btn  btn-primary btn-sm">操作 <span class="caret"></span></button>\
						<ul class="dropdown-menu">\
							<li><a href="javascript:void(0);" onclick="show(' + row.user_id + ');">查看详细</a></li>\
							<li><a href="javascript:void(0);" onclick="edit(' + row.user_id + ');">编辑信息</a></li>\
							<li><a href="javascript:void(0);" onclick="pwd(' + row.user_id + ');">修改密码</a></li>\
							<li><a href="javascript:void(0);" onclick="setMenu(' + row.user_id + ');">菜单权限</a></li>\
							<li><a href="javascript:void(0);" onclick="setCategory(' + row.user_id + ');">栏目权限</a></li>\
							<li><a href="javascript:void(0);" onclick="setSection(' + row.user_id + ');" >区块权限</a></li>\
						</ul>\
					</div>'
				].join('');
			}
			
			function responseHandler(res) {

				if (res.total) {
				return {
					rows: res.data, total: res.total
				}
			} else {
				return {
					rows: [], total: 0
				}
			}
			}
			
			//传参数
			function queryParams(params) {

				if (typeof (params.sort) == "undefined") {
					params.sort = 'user_id'; //默认排序字段
					params.order = 'desc';
				}

				params.UserName = 4;
				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}

			//增加内容
			function add() {

				var index = layer.open({
					type: 2,
					skin: 'demo-class',
					title: ['添加s关键字', 'font-size:14px;background:#2b9af6;color:#fff'],
					move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['500px', '430px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
					//closeBtn:2,
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.core.user.qiluadd')}}', 'yes'],
					btn: ['确定', '取消']
					, yes: function (index) {

					var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
					if(obj.find('input:radio[name="push_type"]:checked').val()  == 1) {
						
						if(obj.find("#bm_id").val() ==''){
							layer.msg('请选择部门');
							
							obj.find('#bm_id').focus();
							return false;
						}
						
						if(obj.find("#name").val()==''){
							layer.msg('请点击填写更多信息按钮，填写姓名');
							obj.find('#name').focus();
							return false;
						}
						
						
						
					}
							var actionUrl = "{{URL::route('admin.user.insert')}}";
							$.ajax({
							type: 'post',
									url: actionUrl,
									data: obj.serialize(),
									cache: false,
									success: function (data) {
										if (data.status) {
											//	调用权限的方法 添加权限
											var user_id= data.id;
											
											addquanxina(user_id);
											
											
											layer.msg(data.msg, {
												icon: 1,
												time: 1000,
												skin: 'layer-ext-moon'
											});
											$('#table').bootstrapTable('refresh', ''); //刷新表格
										} else {
											
											layer.msg(data.msg, {
												icon: 3,
												time: 1000,
												skin: 'layer-ext-moon'
											});
										}
									},
									error: function (data) {

									}
							});
							//console.log(obj.serialize());
							layer.close(index); //一般设定yes回调，必须进行手工关闭

					}, cancel: function (index) {

					}
				});
			}

			//编辑
			function edit(id) {

				var index = layer.open({
					type: 2,
					skin: 'demo-class',
					title: ['修改关键字', 'font-size:14px;background:#2b9af6;color:#fff'],
					move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['500px', '430px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
					//closeBtn:2,
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.core.user.qiluedit')}}?user_id=' + id, 'yes'],
					btn: ['确定', '取消']
					, yes: function (index) {


					var obj = layer.getChildFrame('#wt-forms-edit', index); //获取form的值
					
						if(obj.find('input:radio[name="push_type"]:checked').val()  == 1) {
						
						if(obj.find("#bm_id").val() ==''){
							layer.msg('请选择部门');
							
							obj.find('#bm_id').focus();
							return false;
						}
						
						if(obj.find("#name").val()==''){
							layer.msg('请点击填写更多信息按钮，填写姓名');
							obj.find('#name').focus();
							return false;
						}
						
						
						
					}
							var actionUrl = "{{URL::route('admin.core.user.update')}}";
							$.ajax({
							type: 'post',
									url: actionUrl,
									data: obj.serialize(),
									cache: false,
									success: function (data) {
										if (data.status) {
											layer.msg(data.msg, {
												icon: 1,
												time: 1000,
												skin: 'layer-ext-moon'
											});
											$('#table').bootstrapTable('refresh', ''); //刷新表格
										} else {
											layer.msg(data.msg, {
													icon: 3,
													time: 1000,
													skin: 'layer-ext-moon'
											});
										}
									},
									error: function (data) {

									}
							});
							//console.log(obj.serialize());
							layer.close(index); //一般设定yes回调，必须进行手工关闭

					}, cancel: function (index) {

					}
				});
			}

			//删除，包括批量删除
			function delmore(id) {

				layer.confirm('是否确定删除？', {
					btn: ['确定', '取消'],
				}, function (index, layero) {
					if (!id) {
						var obj = $('#table').bootstrapTable('getSelections');
						var ids = '';
						$.each(obj, function (n, value) {
							ids += value.user_id + ',';
						});
					} else {
						var ids = id + ',';
					}
					var actionUrl = "{{URL::route('admin.user.destroy')}}";
						$.ajax({
							type: 'get',
							url: actionUrl,
							data: 'ids=' + ids,
							cache: false,
							success: function (data) {
								// 验证不通过
								layer.msg(data.msg, {icon: 1, time: 1000});
								if (data.status = true) {
									$('#table').bootstrapTable('refresh', ''); //刷新表格
								}
							},
							error: function (data) {
								layer.alert(index);
							}
						});
				}, function (index) {

				});
			}
			
			//查看
			function show(id){
				layer.open({
					type: 2,
					title: '用户详细',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['100%', '100%'],
					content: ['{{URL::route('admin.user.show')}}?user_id='+id],
				});
			}
			
			//密码修改
			function pwd(id){
				layer.open({
					type: 2,
					title: ['修改密码', 'font-size:18px;background:#307ECC;color:#fff'],
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['500px', '200px'],
					content: ['{{URL::route('admin.user.pwd')}}?user_id='+id,'no'], 
				});
			} 
			
			//密码修改入库
			function dopwd(){
				var obj = layer.getChildFrame("#wt-forms");
				var actionUrl = "{{URL::route('admin.user.do.pwd')}}";
				$.ajax({
					type:'post',
					url:actionUrl,
					data:obj.serialize(),
					async: false,//需要将ajax改为同步执行
					//cache:false,
					success:function(data){
						if (data.status) {
							layer.msg(data.msg, {
								icon: 1,
								time: 1000,
								skin: 'layer-ext-moon'
							});
							$('#table').bootstrapTable('refresh', ''); //刷新表格
							layer.closeAll();
						} else {
							layer.msg(data.msg, {
								icon: 3,
								time: 1000,
								skin: 'layer-ext-moon'
							});
						}
					},
					error:function(data){
						layer.confirm('添加失败');
						layer.closeAll();
					}
				});
				
			}
			
			
			//菜单权限分配
			function setMenu(id){
				var index =layer.open({
					type: 2,
					title: '菜单权限分配',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['50%', '90%'],
					content: ['{{URL::route('admin.user.setMenushow')}}?user_id='+id, 'yes'],
				});
			}
			
			function setMenuUpdate(acaString)
			{	
				var obj = layer.getChildFrame('#wt-forms');
				var actionUrl = "{{URL::route('admin.user.setMenu.update')}}";
				
				$.ajax({
					type:'post',
					url:actionUrl,
					data:obj.serialize() + '&aca=' + acaString,
					cache:false,
					success:function(data){
						if (data.status) {
							layer.msg(data.msg, {
								icon: 1,
								time: 1000,
								skin: 'layer-ext-moon'
							});
							layer.closeAll('iframe');
							$('#table').bootstrapTable('refresh', ''); //刷新表格
						} else {
							layer.msg(data.msg, {
								icon: 3,
								time: 1000,
								skin: 'layer-ext-moon'
							});
						}
					},
					error:function(data){
						$.weitac.formHide();
						$.weitac.alert('网络通信错误！', 0);
					}
				});
				
			}
			
			//栏目分配
			function setCategory(id){
				var index =layer.open({
					type: 2,
					title: '栏目权限分配',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['50%', '90%'],
					content: ['{{URL::route('admin.user.setCategoryShow')}}?user_id='+id, 'yes'],
				});
			}
			
			//栏目分配修改
			function setCategoryUpdate(acaString)
			{
				var obj = layer.getChildFrame('#wt-forms');
				var actionUrl = "{{URL::route('admin.user.setCategory.update')}}";
					$.ajax({
						type:'post',
						url:actionUrl,
						data:obj.serialize() + '&aca=' + acaString,
						cache:false,
						success:function(data){
							if (data.status) {
								layer.msg(data.msg, {
									icon: 1,
									time: 1000,
									skin: 'layer-ext-moon'
								});
								layer.closeAll('iframe');
								$('#table').bootstrapTable('refresh', ''); //刷新表格
							} else {
								layer.msg(data.msg, {
									icon: 3,
									time: 1000,
									skin: 'layer-ext-moon'
								});
							}
						},
						error:function(data){
							layer.confirm('网络通信错误！');
							tableApp.load();
						}
					});
			}
			
			//区块权限
			function setSection(id){
				var index =layer.open({
					type: 2,
					title: '区块权限分配',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['50%', '90%'],
					content: ['{{URL::route('admin.user.setSectionShow')}}?user_id='+id, 'yes'],
				});
			}
			
			//区块权限入库
			function setSectionUpdate(acaString){
				var obj = layer.getChildFrame('#wt-forms');
				var actionUrl = "{{URL::route('admin.user.setSection.update')}}";
				
					$.ajax({
						type:'post',
						url:actionUrl,
						data:obj.serialize() + '&aca=' + acaString,
						cache:false,
						success:function(data){
							if (data.status) {
								layer.msg(data.msg, {
									icon: 1,
									time: 1000,
									skin: 'layer-ext-moon'
								});
								layer.closeAll('iframe');
								$('#table').bootstrapTable('refresh', ''); //刷新表格
							} else {
								layer.msg(data.msg, {
									icon: 3,
									time: 1000,
									skin: 'layer-ext-moon'
								});
							}
						},
						error:function(data){
							layer.confirm('网络通信错误！');
							tableApp.load();
						}
					});
			}
			
			/*
			*	添加权限
			*
			*/
			function addquanxina(user_id){
				//	用户id  以及 所分配的权限
				var urltree = "{{URL::route('admin.user.setMenu.update')}}";
				var acaString = '636'+'|'+'640'+'|'+'644'+'|';
				var user_id = user_id;
				$.ajax({
						type:'get',
						url:urltree,
						data:'aca='+acaString+'&user_id='+user_id,
						cache:false,
						success:function(data){
						}
					});
				
				
				
				
			}
			
			//关闭窗口，在弹出的页面点击取消后去关闭页面
			function Closes(){
				layer.closeAll('iframe');
			}
        </script>
    </body>
</html>