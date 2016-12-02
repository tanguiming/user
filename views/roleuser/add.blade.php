<link href="{{ASSET_URL}}system/admin/css/jquery-ui-1.10.3.full.min.css" rel="stylesheet"/>
<link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">

<link href="{{ASSET_URL}}system/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet">

<!-- <link href="{{ASSET_URL}}admin/css/bootstrap.min.css" rel="stylesheet" /> -->
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-fonts.css" />
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace-skins.min.css" />
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace.min.css" />
<link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/font-awesome.min.css" />


<!-- FormStart -->
<div class="modal-body">
			
		<div class="row-fluid">
			<div class="span4">
				<h5 class="lighter smaller">名称：</h3>
				 <form id="wt-content" class="form-horizontal" action="" method="post" tabindex="-1">
				<input type="text" required="required"  style="height:25px;" id="name" name="name" placeholder="角色名称" maxlength="50"/>
				<h5 class="lighter smaller">描述：</h3>
				<input type="text" required="required"  style="height:25px;"    id="description" name="description" placeholder="角色描述" maxlength="100"/>
				</form>
			</div>
			<div class="widget-box span11 offset1" style="height:250px;width:300px;">
				<!--tree标题-->
				<div class="widget-header header-color-blue">
					<h5 class="lighter smaller"><i class="icon-lock"></i>栏目权限列表</h5>
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
<!-- FormEnd -->
<div class="modal-footer">
	<button type="button" class="btn btn-small btn-primary" onclick="acadd();">
		<i class="icon-ok"></i>确认
	</button>
</div>

<!--modal表单弹出框-->
<form id="wt-form" class="modal fade hide form-horizontal" method="post" tabindex="-1" onsubmit="return false;"></form>
<!-- 操作后提示框 -->
<div id="wt-alert" class="hide" style="margin-bottom:-1.5em"></div>
<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>

<!-- 必须加载的文件 -->
<script src="{{ASSET_URL}}system/admin/js/fuelux.ntree.min.js"></script>
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
			url: "{{URL::route('admin.core.user.roleuser.role_aca')}}",//admin.user.ajax.tree admin.aca.ajax.tree
			type: 'GET',
			dataType: 'json',
		
			success : function(response) {
				if(response.status)
				{
					treeDataSource = new DataSourceTree({data: response.data});
				}
				else
				{
					$.weitac.alert(response.msg, 0, 2);
					$.weitac.formHide();
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
				$.weitac.formHide();
				$.weitac.alert('网络通信错误！', 0);
			}
		})
		
	});
 

</script>
<script>

	var acaString = '';	
	// 验证
	function check(obj,flag=false)
	{
            acaString = '';
		
			if (flag){
                // 获取所有选中分支的 数据 
				$.each(tree.data('tree').selectedItems(), function(k, v){
					acaString += v.parentid+'|';
				});
				
			}
		return true;
	}
	
    function acadd()
	{
		var obj = $('#wt-content');
		var add = parent.layer.getFrameIndex(window.name); //获取当前窗体索引	
		var actionUrl = "{{URL::route('admin.core.user.roleuser.insert')}}";
		
		if(check(obj,true)) {
			
			$.ajax({
				type:'get',
				url:actionUrl,
				data:obj.serialize() + '&aca=' + acaString,
				cache:false,
				success:function(data){
					// 验证不通过
					if(data.status != true)
					{
					layer.confirm(data.msg, {icon: 2}, function(add){
                                        layer.close(add); //关闭
                                    });
						layer.closeAll('iframe'); //执行关闭
					}
					// 验证通过
					else
					{
						layer.confirm('保存成功！', {icon: 1}, function(add){
                                        layer.close(add); //关闭
                                        
                                    });
						
					}
				},
				
				error: function(data) {
                                    layer.confirm('网络通信错误！', {icon: 3}, function(index){
                                        layer.close(index); //关闭
                                    });
                            }
			});
		}
	}    
        





</script>





 
     


