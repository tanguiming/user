<!DOCTYPE html>
<html>
    <head>
        <title>登录日志</title>

        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href='{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css' rel='stylesheet' type='text/css'>
        <link href='{{ASSET_URL}}system/hplus/css/style1.min2964.css?v=3.0.0' rel='stylesheet' type='text/css'>

    </head>
    <body>
        <div class="page-content">
            <div class="main-container container-fluid">
                <div id="headshow">
                    <button type="button" class="btn  btn-primary btn-sm"><i class="fa fa-plus"></i>导出备份</button>
                </div>
                <table id="table"></table>
            </div>
        </div>


        <!-- 全局js -->
        <script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
        <script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>

        <!-- boot-table -->
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/js/bootstrap-table.js"></script>
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>

        <script>

$('#table').bootstrapTable({
    classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
    method: 'get',
    url: "/userLoginLog/ajaxIndex",
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
    columns: [
		//{
            //checkbox: true
        //}, 
		{
            field: 'username',
            title: '用户名',
            width: 300, //宽度
            align: 'center', //
            valign: 'middle',
            sortable: true  //是否排序
        }, {
            field: 'ip',
            title: '登录IP',
            visible: 'middle', //刚开始是否显示此字段
            sortable: true  //是否排序
        }, {
			field:'time',
			title:'执行时间',
			visible:'middle',
			sortable:true
		}, {
			field:'type',
			title:'类型',
			visible:'middle',
			sortable:true
		}, {
			field:'status',
			title:'状态',
			visible:'middle',
			sortable:true
		}
		//, {
        //    field: 'token',
        //    title: 'token',
        //    formatter:aa,
        //}
		]
//     onSearch: function (text) {  事件
//        // alert("ddd");
//     },
//      onSort: function (name, order) {
//         // alert(name);
//         // alert(order);
//      }



});


function aa(value, row, index){
    
     return [
            '<a class="like" href="javascript:void(0)" title="Like">',
                '<i class="glyphicon glyphicon-heart"></i>',
            '</a>',
            '<a class="edit ml10" href="javascript:void(0)" title="Edit">',
                '<i class="glyphicon glyphicon-edit"></i>',
            '</a>',
            '<a class="remove ml10" href="javascript:void(0)" title="Remove">',
                '<i class="glyphicon glyphicon-remove"></i>',
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
        params.sort = 'time'; //默认排序字段
        params.order = 'desc';
    }

    params.UserName = 4;

    //alert(JSON.stringify(params));
    return params;

}
        </script>
    </body>
</html>