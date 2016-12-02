<!DOCTYPE html>
<html>
    <head>
        <title>赚币规则</title>

        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href='{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css' rel='stylesheet' type='text/css'>
        <link href='{{ASSET_URL}}system/hplus/css/style1.min2964.css?v=3.0.0' rel='stylesheet' type='text/css'>

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
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>


        <!-- 全局js -->
        <script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
        <script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>
        <script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
        <!-- boot-table -->
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/js/bootstrap-table.js"></script>
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>

        <script>

			$('#table').bootstrapTable({
					classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
					method: 'get',
					url: "ajaxIndex",
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
					field: 'id',
							title: 'ID',
							width: 100, //宽度
							align: 'center', //
							valign: 'middle',
							sortable: true  //是否排序
					}, {
					field: 'name',
							title: '名称',
						   // visible: false, //刚开始是否显示此字段
							//sortable: false  //是否排序
					}, {
					field: 'content',
							title: '描述',
						   // visible: false, //刚开始是否显示此字段
							//sortable: true  //是否排序
					}, {
					field: 'currency',
							title: '币种',
						   // visible: false, //刚开始是否显示此字段
							//sortable: true  //是否排序
					}, {
					field: 'value',
							title: '经验值'
					}, {
					field: '',
							title: '操作',
							formatter: handle,
					}],
					onSearch: function (text) {  //事件
					//        // alert("ddd");
					},
					//      onSort: function (name, order) {
					//         // alert(name);
					//         // alert(order);
					//      }



			});
			function handle(value, row, index) {
				//console.log(row.id);
				return [
						'</a>',
						'<a class="edit ml10" href="javascript:void(0)" onclick="edit(' + row.id + ')" title="编辑">',
						'编辑',
						'</a>',
						'&nbsp;&nbsp;',
						'<a class="remove ml10" href="javascript:void(0)" onclick="delmore(' + row.id + ')" title="删除">',
						'删除',
						'</a>'
				].join('');
			}
			function responseHandler(res) {

				if (res.total) {
					return{
						rows: res.data,
						total: res.total
					}
				} else {
					return {
						rows: [],
						total: 0
					}
				}
			}
			//传参数
			function queryParams(params) {

				if (typeof (params.sort) == "undefined") {
					params.sort = 'id'; //默认排序字段
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
					title: ['添加规则', 'font-size:14px;background:#2b9af6;color:#fff'],
					move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['500px', '430px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
					//closeBtn:2,
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.user.pointrule.add')}}', 'no'],
					btn: ['确定', '取消']
					, yes: function (index) {

					var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
							var actionUrl = "{{URL::route('admin.user.pointrule.insert')}}";
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

			//编辑
			function edit(id) {

				var index = layer.open({
					type: 2,
					skin: 'demo-class',
					title: ['编辑规则', 'font-size:14px;background:#2b9af6;color:#fff'],
					move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['500px', '400px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
					//closeBtn:2,
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.user.pointrule.edit')}}?id=' + id, 'no'],
					btn: ['确定', '取消']
					, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms-edit', index); //获取form的值
						var actionUrl = "{{URL::route('admin.user.pointrule.update')}}";
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
							ids += value.id + ',';
						});
				} else {
					var ids = id + ',';
				}

				var actionUrl = "{{URL::route('admin.user.pointrule.delete')}}";
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
        </script>
    </body>
</html>