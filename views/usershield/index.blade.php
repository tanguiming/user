<!DOCTYPE html>
<html>
	<head>
		<title>屏蔽表</title>
		<link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" type="text/css">
		<link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet" type="text/css">
		<link href="{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css" rel="stylesheet" type="text/css">
		<link href="{{ASSET_URL}}system/hplus/css/style.min2964.css?v=3.0.0" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="page-content">
			<div class="main-container container-fluid">
				<div id="headshow">
					<button class="btn btn-success btn-sm " type="button" onclick="add();">添加</button>
                    <button class="btn btn-danger btn-sm" type="button" onclick="delmore(null)">删除</button>
                </div>
                <table id="table"></table>
				<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
			</div>
		</div>

		<!--
		js加载
		-->
		<script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>
		<script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
		<script type="text/javascript" src="{{ASSET_URL}}system/admin/bootstrap-table/js/bootstrap-table.js"></script>
		<script type="text/javascript" src="{{ASSET_URL}}system/admin/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>


		<!--自定义js-->
		<script type="text/javascript">
		$("#table").bootstrapTable({
				classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
					method: 'get',
					url: "ajaxIndexshield",
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
					search: false, //不显示 搜索框
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
						field: 'periodtime',
						title: '屏蔽时间段',
						valign: 'middle',
						   // visible: false, //刚开始是否显示此字段
							//sortable: false  //是否排序
					},{
						field: '',
						title: '操作',
						formatter: handle,
						width: 200, //宽度
					}],
					onSearch:function(text){
						
					}
			});

		function handle(value, row, index) {				
								
               	//console.log(row.id);
				return [
						'<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="edit(' + row.id + ')" title="修改">',
						'修改',
						'</a>&nbsp;&nbsp;&nbsp;',
						'<a class="btn btn-danger btn-sm" href="javascript:void(0)" onclick="delmore(' + row.id + ')" title="删除">',
						'删除',
						'</a>'
				].join('');
            }

			//返回结果
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

				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
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

				var actionUrl = "{{URL::route('admin.user.UsertableController.deleteshield')}}";
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
			
			//添加屏蔽时间
			function add(){
				var index = layer.open({
					type: 2,
					skin: 'demo-class',
					title: ['添加屏蔽时间', 'font-size:14px;background:#2b9af6;color:#fff'],
					move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['550px', '420px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
					//closeBtn:2,
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.user.UsertableController.add')}}','no'], 
					btn: ['确定', '取消']
					, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var actionUrl = "{{URL::route('admin.user.UsertableController.insert')}}";
						$.ajax({
						type: 'post',
								url: actionUrl,
								data: obj.serialize(),
								cache: false,
								success: function (data) {
								if (!data.status)
								{
									 layer.msg(data.msg, {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
									 $(":button[name='refresh']").click();
									 layer.closeAll('iframe');//关闭弹出页面

								}else
								{
									layer.msg(data.msg, {icon: 1});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
									 $(":button[name='refresh']").click();
									 layer.closeAll('iframe');//关闭弹出页面
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

			//修改屏蔽时间
			function edit(id){
				var index = layer.open({
					type: 2,
					skin: 'demo-class',
					title: ['修改屏蔽时间', 'font-size:14px;background:#2b9af6;color:#fff'],
					move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['550px', '420px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
					//closeBtn:2,
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.user.UsertableController.edit')}}?id='+id,'no'], 
					btn: ['确定', '取消']
					, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var actionUrl = "{{URL::route('admin.user.UsertableController.update')}}";
						$.ajax({
						type: 'post',
								url: actionUrl,
								data: obj.serialize(),
								cache: false,
								success: function (data) {
								if (!data.status)
								{
									 layer.msg(data.msg, {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
									 $(":button[name='refresh']").click();
									 layer.closeAll('iframe');//关闭弹出页面

								}else
								{
									layer.msg(data.msg, {icon: 1});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
									 $(":button[name='refresh']").click();
									 layer.closeAll('iframe');//关闭弹出页面
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
		</script>

	</body>
</html>