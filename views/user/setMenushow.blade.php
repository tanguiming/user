<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">

        <title>菜单权限分配</title>
		
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/font-awesome.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-fonts.css" />
		<!--ace styles-->
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-skins.min.css" />
		
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
		
		<script src="{{ASSET_URL}}system/admin/js/ace-extra.min.js"></script>
    
	</head>

    <body class="gray-bg">
		<div class="modal-body">
			<form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;">
				<div class="row-fluid">
					<div class="span12">
						<!--PAGE CONTENT BEGINS-->

						<div class="row-fluid">
							<div class="widget-box span6">
								<div class="widget-header header-color-blue2">
									<h4 class="lighter smaller">菜单权限分配</h4>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-8">
										
											<div id="treeview" class="tree"></div>
										
									</div>
								</div>
							</div>

						</div>
						<!--PAGE CONTENT ENDS-->
					</div><!--/.span-->
				</div><!--/.row-fluid-->
				
				<div class="ace-settings-container" id="ace-settings-container">
					<div class="ace-settings-box" id="ace-settings-box">
						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
						</div>
					</div>
				</div><!--/#ace-settings-container-->
				<input type="hidden" name="user_id" value="{{$user_id}}" />
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		</div>
		<div class="modal-footer">
			<button data-dismiss="modal" class="btn btn-small" onclick="window.parent.Closes()">
				取消
			</button>
			<button type="button" class="btn btn-small btn-primary" onclick="check()">
				确认
			</button>
		</div>
		
		<!-- 操作后提示框 -->
		<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
		<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>

		<!-- 必须加载的文件 -->
		<script src="{{ASSET_URL}}user/admin/js/fuelux/fuelux.ntree.min.js"></script>
		<script src="{{ASSET_URL}}system/admin/js/ace-elements.min.js"></script>

		<!--inline scripts related to this page-->
		
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
					url: "{{URL::route('admin.user.ajax.setMenu.tree')}}",//admin.user.ajax.tree admin.aca.ajax.tree
					type: 'GET',
					dataType: 'json',
					data : 'user_id={{$user_id}}',
					success : function(response) {
						if(response.status)
						{
							treeDataSource = new DataSourceTree({data: response.data});
						}
						else
						{
							alert('保存失败');
							tableApp.load();
						}
							
						tree = $('#treeview').ace_tree({
							dataSource: treeDataSource ,
							multiSelect:true,								// 允许多选
							allItems : true,									// 是否生成所有节点	默认为 false 不生成全部节点
							loadingHTML:'',								// 等待读取图标 <div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>
							'open-icon' : 'icon-minus',					
							'close-icon' : 'icon-plus',
							'selectable' : true,								// 是否允许选择
							'selected-icon' : 'icon-ok',
							'unselected-icon' : 'icon-remove'
						});
					},
					error: function(response) {
						alert('网络通信错误！');
					}
				})
				
			});
			
			//验证
			function check(flag=true){
				acaString = '';
				// if (flag){
					// 获取所有选中分支的 数据 
					$.each(tree.data('tree').selectedItems(), function(k, v){
						acaString += v.parentid+'|';
					});
					var index= index = window.parent.setMenuUpdate(acaString);
					// if(acaString !=''){
						
					// }else{
						// alert('请选择对应权限或者关闭');
						// return false;
					// }
				// }else{
					// alert('请选择对应权限或者关闭');
					// return false;
				// }
			}			
			 
		</script>
	</body>
</html>