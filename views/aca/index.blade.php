<!DOCTYPE html>
<html>

    <!-- Mirrored from www.zi-han.net/theme/hplus/index_v2.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 06 Sep 2015 05:15:10 GMT -->
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">

        <title>权限列表</title>
        <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
        <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">
        <link rel="stylesheet" href="{{ASSET_URL}}admin/css/jquery-ui-1.10.3.full.min.css" />
        <link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">

        <link href="{{ASSET_URL}}system/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
        <link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet">
		
		<link href="{{ASSET_URL}}system/hplus/css/bootstrap.mind797.css?v=3.4.0" rel="stylesheet">


		<link href="{{ASSET_URL}}system/hplus/css/plugins/treeview/bootstrap-treeview.css" rel="stylesheet">

        <!--<link rel="stylesheet" href="{{ASSET_URL}}admin/css/ace-fonts.css" />-->
        <!--<link rel="stylesheet" href="{{ASSET_URL}}admin/css/ace.min.css" />-->
        <!--<link rel="stylesheet" href="{{ASSET_URL}}admin/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="{{ASSET_URL}}admin/css/ace-skins.min.css" />-->
        <link href="{{ASSET_URL}}system/admin/css/bootstrap-responsive.min.css" rel="stylesheet" /><!-- 按钮的排版样式 -->
       <!-- <link rel="stylesheet" href="{{ASSET_URL}}admin/css/font-awesome.min.css" /> 原按钮的样式-->


    </head>

    <body class="gray-bg">

		<div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>权限管理</h5>

                    <div class="ibox-tools">
                        <a onclick="add();" class="btn btn-outline btn-default" style='margin-bottom: 0px;padding: 1px 2px;'>
                            <i class="fa fa-plus text-navy"></i>添加
                        </a>
                        <a onclick="import333();" class="btn btn-outline btn-default" style='margin-bottom: 0px;padding: 1px 2px;'>
                            <i class="fa fa-plus text-navy"></i>导入
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
					<div class="col-sm-6">
						<div id="treeview7" class="test"></div>
					</div>
					<div class="col-sm-6">
                        <h5>权限详情：</h5>
                        <hr>
                        <div id="event_output"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
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




    <!-- Bootstrap-Treeview plugin javascript -->
    <script src="{{ASSET_URL}}system/hplus/js/plugins/treeview/bootstrap-treeview.js"></script>

 <script type="text/javascript">
	
	//加载全部节点
	load();
	
	function load(){
		var t='';
		//获取所有权限节点
		$.ajax({
			type:'get',
			url:'{{URL::route('admin.core.user.aca.ajaxindex')}}',
			async: false,//需要将ajax改为同步执行
			//cache:false,
			success:function(data){
			// 验证不通过
				t = data;
			},
			error:function(data){
				layer.confirm('加载失败', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
		});
		
		
		$("#treeview7").treeview({
			color:"#428bca",
			showBorder:!1,
			data:t,
			onNodeSelected:function(e,o){
				//$("#event_output").prepend("<p>您单击了 "+o.id+"</p>")
				edit(o.id);
			}
		});
	}									
	
	
	
	
	//修改
	function edit(id){
		var id = 'id='+id;
		$("#event_output").empty();
	
		$.ajax({
			type:'get',
			url:'{{URL::route('admin.core.user.aca.edit')}}',
			data:id,
			async: false,//需要将ajax改为同步执行
			//cache:false,
			success:function(data){
				var h = '<form id="wt-forms"><input type="hidden" name="aca_id"  value="'+data['data']['aca_id']+'"><div class="form-group"><label>父类：</label> <select id="form-field-select-1" name="parent_id" onchange="search_type(this.value)" ><option value="0">———包名———</option>';
				for(var i=0;i<data['aca'].length;i++){
					h+= data['aca'][i];
				} 
				h += '</select>';
				h += '<select name="sid" id="sid">';
				h +='<option value="">请选择</option>';
				if(data['biaoshi'] != 0 ){
					for(var j=0;j<data['classify'].length;j++){
						h += data['classify'][j];
					}
				}else{
					for(var j=0;j<data['classify'].length;j++){
						h += data['classify'][j];
					}
				}
				h += '</select>';
				h += '</div>';
				h += '<div class="form-group"><label>名称：</label> <input type="text" name="remark" value="'+data['data']['remark']+'" placeholder="请输入名称" style="height: 35px"></div>';
                h += '<div class="form-group"><label>路由：</label> <input type="text" name="action" value="'+data['data']['action']+'" placeholder="请输入路由名称" style="height: 35px"></div>';
                h += '<div class="form-group"><label>描述：</label> <textarea name="package">'+data['data']['package']+'</textarea></div>';
				
				h += '<div><button class="btn btn-primary" id="regsub" type="submit" onclick="update();">保存内容</button>';
				h += '&nbsp;&nbsp;<button class="btn btn-danger" type="submit" onclick="datadel('+data['data']['aca_id']+')">删除</button>';
				if(!data['data']['parent_id']){
					h += '&nbsp;&nbsp;<button class="btn btn-danger" type="submit" onclick="export333('+data['data']['aca_id']+')">导出</button>';
				}
				h += '</div></form>';
				
				$("#event_output").prepend(h);
			},
			error:function(data){
				layer.confirm('加载失败', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
		});
	
	}
	
	//修改内容
	function update(){
		var obj = $('#wt-forms');
		$.ajax({
			type:'get',
			data:obj.serialize(),
			url:'{{URL::route('admin.core.user.aca.update')}}',
			async: false,//需要将ajax改为同步执行
			//cache:false,
			success:function(data){
				if(data['status']){
					layer.confirm(data['msg'], {icon: 3}, function(index){
						layer.close(index); //关闭
					});
					$("#event_output").empty();
					load();
				}else{
					layer.confirm(data['msg'], {icon: 3}, function(index){
						layer.close(index); //关闭
					});
				}
			},
			error:function(data){
				layer.confirm('加载失败', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
		});
	}
	function export333(id){
		var url = "{{URL::route('admin.core.user.aca.export')}}?id=" +id;
		window.open(url);
	}
	function import333(){
		var import_add_url = "{{URL::route('admin.core.user.aca.import_add')}}";
		layer.open({
		    type:2,
		    content:[import_add_url,'yes'],
		    area:['600px','300px'],
		    title:'导入权限',
		    shadeClose:true,
		    closeBtn:1,
		    btn:['添加','取消'],
		    btn1:function(){
		        // var obj = layer.getChildFrame('#wt-forms').serialize();
		        var obj = layer.getChildFrame('#wt-forms');
		        $.ajax({
		            type:'post',
		            url:"{{URL::route('admin.core.user.aca.import_insert')}}",
		            data:obj.serialize(),
		            cache:false,
		            success:function(data){
		                if(data.status){
		                    // $(":button[name='refresh']").click();
		                    layer.msg(data.msg,{icon:1,time:800},function(){}); 
		                }else{
		                    layer.msg(data.msg,{icon:1,time:800},function(){});
		                }
		            },
		            error:function(data){
		                layer.alert('网络通信错误',{icon:2,title:false,closeBtn:1},function(index){layer.close(index);
		                    
		                });
		            }
		        });
		    },
		    btn2:function(){},
		}); 
		var url = "{{URL::route('admin.core.user.aca.export')}}?id=" +id;
		window.open(url);
	}
	
	function add(){
		$("#event_output").empty();	
		$.ajax({
			type:'get',
			url:'{{URL::route('admin.core.user.aca.add')}}',
			async: false,//需要将ajax改为同步执行
			//cache:false,
			success:function(data){
				var h = '<form id="wt-forms"><input type="hidden" name="aca_id"  value=""><div class="form-group"><label>父类：</label> <select id="form-field-select-1" name="parent_id" onchange="search_type(this.value)" ><option value="0">———包名———</option>';
				for(var i=0;i<data.length;i++){
					h+= data[i];
				} 
				h += '</select>';
				h += '<select name="sid" id="sid"><option value="">请选择</option></select></div>';
				h += '<div class="form-group"><label>名称：</label> <input type="text" name="remark" value="" placeholder="请输入名称" style="height: 35px"></div>';
				h += '<div class="form-group"><label>路由：</label> <input type="text" name="action" value="" placeholder="请输入路由名称" style="height: 35px"></div>';
				h += '<div class="form-group"><label>描述：</label> <textarea name="package" placeholder="请输入包名称"></textarea></div>';

				h += '<div><button class="btn btn-primary" id="regsub" type="submit" onclick="insert();">保存内容</button>';
				h += '<button class="btn btn-white" type="submit" onclick="Closes()">取消</button></div></form>';

				$("#event_output").prepend(h);
			},
			error:function(data){
				layer.confirm('加载失败', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
		});
	}
	
	
	//字菜单查询
	function search_type(val){
		$("#sid").empty();	
		$.ajax({
			type:'get',
			url:'{{URL::route('admin.core.user.aca.sid')}}?id='+val,
			async: false,//需要将ajax改为同步执行
			//cache:false,
			success:function(data){
				var h = '';
				h += '<option>请选择</option>';
				if(data.length > 0){
					
					for(var i=0;i<data.length;i++){
						h+= data[i];
					}
					h += '</select>';
					
				}
				$("#sid").prepend(h);
			},
			error:function(data){
				layer.confirm('加载失败', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
		});
	}
	
	//插入
	function insert(){
		var obj = $('#wt-forms');
		//var reg= /^.{1,}$/;
		/* var action = $("#action").val();
		var pack = $("#pack").val();  
		//alert(reg.test(action));return;
		if(action == ""){
			alert('请输入路由中as对应内容！');
		}else{
			if(pack == ""){
				alert('请填写真实的包名！');
			}else{ */
				$.ajax({
					type:'get',
					data:obj.serialize(),
					url:'{{URL::route('admin.core.user.aca.insert')}}',
					async: false,//需要将ajax改为同步执行
					//cache:false,
					success:function(data){
						if(data['status']){
							layer.confirm(data['msg'], {icon: 3}, function(index){
								layer.close(index); //关闭
							});
							$("#event_output").empty();
							load();
						}else{
							//失败
							layer.confirm(data['msg'], {icon: 3});
						}
					},
					error:function(data){
						layer.confirm('加载失败', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
					}
				});
		/* 	}
		} */
	}
	
	
	
	
	//判断删除
	function datadel(id){
		//奇怪的问题，当不清除表单时，页面会刷新
		$("#event_output").empty();
		layer.confirm('确定操作？', {icon: 3}, function(index){
			tvdel(id);
			layer.close(index); //关闭
		});
	}
	// 删除
	function tvdel(id)
	{
		var obj = 'listid=' + id;
		var actionUrl = "{{URL::route('admin.core.user.aca.del')}}";
		$.ajax({
			type: 'get',
			url: actionUrl,
			data: obj,
			async: false,
			//cache:false,
			success: function(data) {
			// 验证不通过
			if (data.status != true)
			{
				layer.confirm(data['msg'], {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
			// 验证通过
			else
			{
				layer.confirm('删除成功！', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
				$("#event_output").empty();
				load();
			}
			},
			error: function(data) {
				layer.confirm('网络通信错误！', {icon: 3}, function(index){
					layer.close(index); //关闭
				});
			}
		});
	}
	

	//添加取消
	function Closes(){
		$("#event_output").empty();
	}	
	
	
	
	
	
	
 </script>

    </body>


    <!-- Mirrored from www.zi-han.net/theme/hplus/index_v2.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 06 Sep 2015 05:15:29 GMT -->
</html>