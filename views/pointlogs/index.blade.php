<!DOCTYPE html>
<html>
    <head>
        <title>积分日志</title>

        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href='{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css' rel='stylesheet' type='text/css'>
        <link href='{{ASSET_URL}}system/hplus/css/style1.min2964.css?v=3.0.0' rel='stylesheet' type='text/css'>

    </head>
    <body>
        <div class="page-content">
            <div class="main-container container-fluid">
            	<div id="headshow">
                    <button type="button" class="btn  btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i> 加减{{$name}}</button>
                    
                </div>
                <table id="table"></table>
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
							width: 80, //宽度
							align: 'center', //
							valign: 'middle',
							sortable: true  //是否排序
					}, {
					field: 'user_id',
							title: '用户',
							width: 250, //宽度
						   // visible: false, //刚开始是否显示此字段
							//sortable: false  //是否排序
					}, {
					field: 'cpoint',
							title: '支出{{$name}}',
							width: 140, //宽度
						   // visible: false, //刚开始是否显示此字段
							//sortable: true  //是否排序
					}, {
					field: 'jpoint',
							title: '获取{{$name}}',
							width: 140, //宽度
						   // visible: false, //刚开始是否显示此字段
							//sortable: true  //是否排序
					},{
					field: 'experience',
							title: '经验值',
							width: 140, //宽度
					}, {
					field: 'ip',
							title: 'IP地址',
							width: 160, //宽度
					},{
					field: 'datetime',
							title: '操作时间',
							width: 220, //宽度
					},{
					field: 'operation',
							title: '描述',
							width: 300, //宽度
							visible: false //刚开始是否显示此字段
					},{
					field: 'pinyin',
							title: '拼音',
							visible: false //刚开始是否显示此字段
					}],
					onSearch: function (text) {  //事件
					//        // alert("ddd");
					},
					//      onSort: function (name, order) {
					//         // alert(name);
					//         // alert(order);
					//      }



			});
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

				//params.UserName = 4;
				//params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}

			//新建模板
            function add() {

                index = layer.open({
                    type: 2,
                    skin: 'demo-class',
                    title: ['{{$name}}操作', 'font-size:16px;background:#2b9af6;color:#fff'],
                    move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
                    area: ['450px', '330px'], //设置弹出框的宽高
                    shade: [0.5, '#000'], //配置遮罩层颜色和透明度
                    shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
                    //closeBtn:2,
                    // time:1000,  设置自动关闭窗口时间 1秒=1000；
                    shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
                    content: ["{{URL::route('admin.user.pointlogs.add')}}", 'yes'],//no不加滚动条 yes加滚动条
                });
            }
            //添加数据
            function insert() {
                var obj = layer.getChildFrame('#wt-forms');
                var actionUrl = "{{URL::route('admin.user.pointlogs.insert')}}?_token={{ csrf_token() }}";
                $.ajax({
                type: 'post',
                        url: actionUrl,
                        data: obj.serialize(),
                        async: false,
                        success: function(data) {// 验证通过
                            if(data){
                                layer.msg('添加成功', {
                                    icon: 6, //绿色笑脸
                                    time: 1240,
                                    skin: 'layer-ext-moon'
                                    },
                                    layer.closeAll() //关闭 括号后不能加分号
                                );
                                $('#table').bootstrapTable('refresh', '');  //重载页面index 页面
                            }
                        },
                        error: function (data) {

                        } 
                });
            }

        </script>
    </body>
</html>