<!DOCTYPE html>
<html>
    <head>
        <title>用户积分排行</title>

        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href='{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css' rel='stylesheet' type='text/css'>
        <link href='{{ASSET_URL}}system/hplus/css/style1.min2964.css?v=3.0.0' rel='stylesheet' type='text/css'>

    </head>
    <body>
        <div class="page-content">
            <div class="main-container container-fluid">
                <div id="headshow">
                    <button class="btn btn-danger btn-sm" type="button" onclick="delmore(null)"><span><i class="fa fa-times"></i>删除</span></button>
                </div>
                <table id="table"></table>
				<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
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
					field: 'user_id',
					title: 'UserID',
					width: 100, //宽度
					align: 'center', //
					valign: 'middle',
					sortable: true  //是否排序
				}, {
					field: 'name',
					title: '用户名称',
					// visible: false, //刚开始是否显示此字段
					//sortable: false  //是否排序
				}, {
					field: 'point',
					title: '积分',
					// visible: false, //刚开始是否显示此字段
					//sortable: true  //是否排序
				}, {
					field: 'experience',
					title: '经验值',
					// visible: false, //刚开始是否显示此字段
					//sortable: true  //是否排序
				}, {
					field: 'last_time',
					title: '关注时间'
				},{
					field: 'id',
					title: 'id',
					visible: false, //刚开始是否显示此字段
					//sortable: true  //是否排序
				}],
				onSearch: function (text) { // 事件
				//        // alert("ddd");
				},
				//      onSort: function (name, order) {
				//         // alert(name);
				//         // alert(order);
				//      }
				});
				function handle(value, row, index) {
					console.log(row.id);
					return [
							'</a>',
							'<a class="edit ml10" href="javascript:void(0)" onclick="edit(' + row.id + ')" title="编辑">',
							'编辑',
							'</a>',
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
						params.sort = 'user_id'; //默认排序字段
						params.order = 'desc';
					}

					params.UserName = 4;
					params.page = params.pageNumber;
					//alert(JSON.stringify(params));
					return params;
				}
				
			/*
		删除操作
		*/
		function delmore(id){
			layer.confirm("是否确定要删除？",{
					btn:['确定','取消']
				},function(index,layero){
					var obj=$("#table").bootstrapTable("getSelections");  //获取被选择行的值，其中是以键值的形式存放，例如：[{"id":0,"name":"item 0"}]
					var ids="";
					$.each(obj,function(n,value){
						ids+=value.id+",";
					});
					var _token = $("#_token").val();
					var actionUrl="{{URL::route('admin.user.WxUserPointDetail.delete')}}";
					$.ajax({
						type:"post",
						url:actionUrl,
						data:{"ids":ids,"_token":_token},
						cache:false,
						success:function(data){
							
							if(data.status){
								layer.msg(data.msg,{icon:1,time:1000});
								$("#table").bootstrapTable("refresh","");
							}
							else{
								layer.msg(data.msg,{icon:2,time:1000});
							}
						},
						error:function(data){
							layer.alert(index);
						}
					});
				},function(index){
					
				});
		}
        </script>
    </body>
</html>