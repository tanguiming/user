<!DOCTYPE html>
<html>

    <!-- Mirrored from www.zi-han.net/theme/hplus/index_v2.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 06 Sep 2015 05:15:10 GMT -->
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">

        <title>部门分类后台首页</title>
        <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
        <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/jquery-ui-1.10.3.full.min.css" />
        <link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">

        <link href="{{ASSET_URL}}system/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
        <link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet">

        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-fonts.css" />
        <!--<link rel="stylesheet" href="{{ASSET_URL}}admin/css/ace.min.css" />-->
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-skins.min.css" />
        <link href="{{ASSET_URL}}system/admin/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/font-awesome.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace.min.css" />

    </head>

    <body class="gray-bg">

        <div class="page-content">

            <div class="main-container container-fluid">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>
                <div class="row-fluid">
                    <div class="span12">
						<h3 class="header blue lighter smaller span12" style="margin-top:0px;margin-bottom:7px;">
							<div class="btn-group span1">
								<a href="javascript:;" onclick="add();" class="btn btn-primary btn-small">添加部门</a>
								<a href="javascript:;" onclick="parentitb();" class="btn btn-info btn-small">同步部门数据</a>
							</div>
							<div class="row-fluid  dataTables_wrapper span11"></div>	
						</h3>
						<div class="widget-box span11 ">
							<!--tree标题-->
							<div class="widget-header header-color-blue">
								<h5 class="lighter smaller">部门列表</h5>（注意：修改或删除请点击右键）
							</div>

							<div class="widget-body"  style="height:320px;overflow:auto;">
								<div class="widget-main padding-8">
									<!-- 存放数据的 -->
									<div id="treeview" class="tree"></div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div><!--/.main-container-->
        </div>

        <!-- 操作后提示框 -->
        <div id="wt-alert" class="hide" style="margin-bottom:-1.5em"></div>
        <!-- 全局js -->
        <script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
        <script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>
        <script src="{{ASSET_URL}}system/hplus/js/content.mine209.js?v=1.0.0"></script>
        <!-- layer javascript -->
        <script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
        <script src="{{ASSET_URL}}system/admin/js/jquery.ui.touch-punch.min.js"></script>
        <script src="{{ASSET_URL}}system/admin/js/table/weitac.js"></script>		
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/weitac.table.js"></script>
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.pagination.js"></script>
		<script src="{{ASSET_URL}}system/admin/js/jquery.dataTables.min.js"></script>
		<script src="{{ASSET_URL}}system/admin/js/jquery.dataTables.bootstrap.js"></script>
		<!--<script src="{{ASSET_URL}}admin/js/jquery-ui-1.10.3.full.min.js')}}"></script>-->

		<!--<script src="{{ASSET_URL}}admin/js/weitac/weitac.global.js"></script>-->
		<script src="{{ASSET_URL}}system/admin/js/table/weitac.js"></script>	
		<!-- 必须加载的文件 -->
		<script src="{{ASSET_URL}}user/admin/js/fuelux/fuelux.ntree.min.js"></script>
		<script src="{{ASSET_URL}}system/admin/js/ace-elements.min.js"></script>
		
        <script type="text/javascript">
            var tree;
	
        jQuery(function($) {

		// ------------------------------------------------------  组织树 的 json数据 
		var DataSourceTree = function(options) {
			this._data 	= options.data;
			this._delay = options.delay;
		}

		DataSourceTree.prototype.data = function(options, callback) {
			var self = this;
			var $data = null;

			if(!("name" in options) && !("type" in options)){
				$data = this._data;
				callback({ data: $data });
				return;
			}
			else if("type" in options && options.type == "folder") {
				if("additionalParameters" in options && "children" in options.additionalParameters)
					$data = options.additionalParameters.children;
				else $data = {}//no data
			}
			
			// 如果数据不为空，则直接放入数据
			if($data != null)
				callback({ data: $data });
		};
				
		var treeDataSource = '';
		
		$.ajax({
			url: "{{URL::route('admin.user.User_DeparTmentController.ajaxIndex')}}",//admin.user.ajax.tree admin.aca.ajax.tree
			type: 'GET',
			dataType: 'json',
			success : function(response) {
				if(response.status)
				{
                                  
					treeDataSource = new DataSourceTree({data: response.data});
				}
				else
				{
					layer.confirm(response.msg, 0, 2);
				}
					
				tree = $('#treeview').ace_tree({
					dataSource: treeDataSource ,
					multiSelect:true,								// 允许多选
					allItems : true,									// 是否生成所有节点	默认为 false 不生成全部节点
					loadingHTML:'',								// 等待读取图标 <div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>
					'open-icon' : 'icon-minus',					
					'close-icon' : 'icon-plus',
					'selectable' : false,								// 是否允许选择
					'selected-icon' : 'icon-ok'
				});
			},
			error: function(response) {
				layer.confirm('网络通信错误！', 0);
			}
		})
		
	});
		</script>
        <script type="text/javascript">
			//取消右键事件
			window.document.oncontextmenu=function(){
				return false;
			}
			//绑定右键事件
			function right(id){
				var evt = window.event || arguments.callee.caller.arguments[0]; // 获取event对象
				var id =id;   
				if(evt.button!="0"){
					var index = layer.open({
						type: 2, 
						// skin:'demo-class',
						title: ['修改和删除部门分类', 'font-size:18px;background:#307ECC;color:#fff'],
						move: '.layui-layer-title',  //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['650px', '450px'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose:true, //是否允许点击遮罩层关闭弹窗 true /false
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift:0,  //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['{{URL::route('admin.user.User_DeparTmentController.edit')}}?id='+id,'no'], 
					});
				}
			}
			//添加页面
			function add(){
				var index = layer.open({
					type: 2, 
					// skin:'demo-class',
					title: ['添加部门分类', 'font-size:18px;background:#307ECC;color:#fff'],
					move: '.layui-layer-title',  //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['650px', '450px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose:true, //是否允许点击遮罩层关闭弹窗 true /false
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift:0,  //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.user.User_DeparTmentController.add')}}','no'], 
					
				});    
			}
			
			//添加
			function insert(){
				var obj = layer.getChildFrame('#wt-forms');
				$.ajax({
					type:'post',
					url:'{{URL::route('admin.user.User_DeparTmentController.insert')}}',
					data:obj.serialize(),
					async: false,
					//cache:false,
					success:function(data){
						// 验证不通过
						layer.confirm('添加成功', {icon: 3}, function(index){
							layer.close(index); //关闭
							location.reload();
						});
						//关闭弹出页面
						layer.closeAll('iframe');
						//tableApp.load();
					},
					error:function(data){
						layer.confirm('添加失败', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
						layer.closeAll('iframe');
						//tableApp.load();
					}
				});
			}

			//修改
			function update(){
				var obj = layer.getChildFrame('#wt-forms');
				var actionUrl = "{{URL::route('admin.user.User_DeparTmentController.update')}}";
				$.ajax({
					type:'post',
					url:actionUrl,
					data:obj.serialize(),
					async: false,
					//cache:false,
					success:function(data){
						// 验证不通过
						if (data.status != true){
							layer.confirm(data.msg, {icon: 3}, function(index){
								layer.close(index); //关闭
								location.reload();
							});
						}else{
							layer.confirm('修改成功！', {icon: 3}, function(index){
								layer.close(index); //关闭
								location.reload();
							});
						}
					},
					error:function(data){
					layer.confirm(data.msg, {icon: 3}, function(index){
					layer.close(index); //关闭
					});
							//关闭弹出页面
							layer.closeAll('iframe');
							//tableApp.load();
					}
				});
			}
			
			//判断删除
			function datadel(id){
				layer.confirm('确定操作？', {icon: 3}, function(index){
						tvdel(id);
				});
			}
			// 删除
			function tvdel(id)
			{
				var obj = 'id=' + id;
				var actionUrl = "{{URL::route('admin.user.User_DeparTmentController.delete')}}";
				$.ajax({
					type: 'get',
					url: actionUrl,
					data: obj,
					async: false,
					//cache:false,
					success: function(data) {
						// 验证不通过
						if (data.status != true){
							layer.confirm(data.msg, {icon: 3}, function(index){
								layer.close(index); //关闭
								location.reload();
							});
						}else{
							layer.confirm('删除成功！', {icon: 3}, function(index){
								layer.close(index); //关闭
								location.reload();
							});
							//tableApp.load();
						}
					},
					error: function(data) {
						layer.confirm('网络通信错误！', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
					}
				});
			}
			function daoru($id){
				var obj = 'aid=' + $id;
				var actionUrl = "{{URL::route('admin.user.User_DeparTmentController.daoru')}}";
				$.ajax({
					type:'get',
					url:actionUrl,
					data:obj,
					async: false,
					//cache:false,
					success:function(data){
						// 验证不通过
						if (data.status != true){
							layer.confirm('导出失败！', {icon: 3}, function(index){
								layer.close(index); //关闭
								location.reload();
							});
						}else{
							layer.confirm('导出成功！', {icon: 3}, function(index){
								layer.close(index); //关闭
								location.reload();
							});
						}
					},
					error:function(data){
					layer.confirm(data.msg, {icon: 3}, function(index){
					layer.close(index); //关闭
					});
							//关闭弹出页面
							layer.closeAll('iframe');
							//tableApp.load();
					}
				});
			}
			
			function parentitb(){
				var obj = layer.getChildFrame('#wt-forms');
				$.ajax({
					type:'get',
					url:'{{URL::route('admin.user.User_DeparTmentController.parentitb')}}',
					async: false,
					success:function(data){
						// 验证不通过
						layer.confirm('同步成功', {icon: 3}, function(index){
							layer.close(index); //关闭
							location.reload();
						});
						//关闭弹出页面
						layer.closeAll('iframe');
						//tableApp.load();
					},
					error:function(data){
						layer.confirm('同步失败', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
						layer.closeAll('iframe');
						//tableApp.load();
					}
				});
			}
        </script>
    </body>
</html>