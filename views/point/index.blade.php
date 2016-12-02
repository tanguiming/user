<!DOCTYPE html>
<html>
    <head>
        <title>积分规则</title>

        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.min.css?v=3.4.0" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href='{{ASSET_URL}}system/admin/bootstrap-table/css/bootstrap-table.min.css' rel='stylesheet' type='text/css'>
		<style>
			.thcss{
				padding:1px;
				text-align: center;
			}
			.tdcss{
				padding:1px;
				text-align: center;
			}
		</style>
    </head>
    <body>
        <div class="page-content">
            <div class="main-container container-fluid">
                <div id="headshow" style="margin:10px 0px 10px 20px;">
                    <button type="button" class="btn  btn-primary btn-sm" onclick="add()"><i class="fa fa-plus"></i>添加</button>
                    <button class="btn btn-warning btn-sm" type="button" onclick="set()"><span><i class="fa fa-pencil"></i>全局配置</span></button>
                    <!--<button class="btn btn-danger btn-sm" type="button" onclick="delmore(null)"><span><i class="fa fa-times"></i>删除</span></button>-->
                </div>
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                @foreach($res as $k=>$v)
				  <div class="panel panel-default">
				    <div style="background-color: #99CC66;" class="panel-heading">
				      <h4 class="panel-title">
				      	<div style="width:82%;" data-toggle="collapse" data-parent="#accordion" href="#{{$v['classid']}}" aria-expanded="false" aria-controls="{{$v['classid']}}" class="collapsed" role="tab" id="{{$k}}{{$v['classid']}}">
				        <a style="color:white;cursor:pointer;">
				          {{$v['classtitle']}}
				        </a>
				        </div>
				        <div style="color:white;float: right;width: 100px;margin-top:-18px">
				        	<div style="float: left;cursor:pointer;" onclick="classedit({{$v['classid']}})">修改</div>
				        	<div style="float: right;cursor:pointer;" onclick="classdel({{$v['classid']}})">删除</div>
				        </div>
				        
				      </h4>
				    </div>
				    <div id="{{$v['classid']}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="{{$k}}{{$v['classid']}}">
				      <div class="panel-body">
				        <table id="table{{$v['classid']}}" border="1" style="width:100%;height:auto;">
							<thead>
								<tr>
									<th class="thcss">ID</th>
									<th class="thcss">规则名称</th>
									<th class="thcss">规则字符</th>
									<th class="thcss">奖励{{$globalname}}</th>
									<th class="thcss">扣除{{$globalname}}</th>
									<th class="thcss">经验值</th>
									<th class="thcss">启动状态</th>
									<th style="width:150px;padding:1px;text-align: center;">操作</th>
								</tr>
							</thead>
							@if($v['point'])
								@foreach($v['point'] as $kp=>$vp)
								<tbody>
									<tr>
										<td class="tdcss">{{$vp['id']}}</td> 
										<td class="tdcss">{{$vp['name']}}</td>
										<td class="tdcss">{{$vp['pinyin']}}</td>
										
										@if($vp['chooseway']==1)
										<td class="tdcss">{{$vp['reward_bean']}}（固定值）</td>
										<td class="tdcss">{{$vp['deduct_bean']}}</td>
										@elseif($vp['chooseway']==2)
										<td class="tdcss">{{$vp['reward_bean']}}（比例值）</td>
										<td class="tdcss">{{$vp['deduct_bean']}}</td>
										@endif
										
										@if($vp['chooseway']==3)
										<td class="tdcss">{{$vp['reward_bean']}}</td>
										<td class="tdcss">{{$vp['deduct_bean']}}（固定值）</td>
										@elseif($vp['chooseway']==4)
										<td class="tdcss">{{$vp['reward_bean']}}</td>
										<td class="tdcss">{{$vp['deduct_bean']}}（比例值）</td>
										@endif
										
										<td class="tdcss">{{$vp['experience']}}</td>
										@if($vp['enabled']==1)
										<td class="tdcss"><img src="{{ASSET_URL}}user/images/cuo.png" onclick="audit({{$vp['id']}},{{$vp['enabled']}})" style="width:30px;"></td>
										@else
										<td class="tdcss"><img src="{{ASSET_URL}}user/images/right.png" onclick="audit({{$vp['id']}},{{$vp['enabled']}})" style="width:30px;"></td>
										@endif
										<td class="tdcss">
											<button class="btn btn-success btn-xs" onclick="edit({{$vp['id']}})" title="编辑">修改</button>
											<button class="btn btn-info btn-xs" onclick="delmore({{$vp['id']}})" title="删除">删除</button>
										</td>
									</tr>
								</tbody>
								@endforeach
							@else
								<tbody></tbody>
							@endif
				        </table>
				      </div>
				    </div>
				  </div>
				@endforeach
				</div>
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

			
			//添加页面
			function add() {
				layer.open({
					type: 2,
					title: false,//'添加红包活动',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['700px', '560px'],
					content: ['{{URL::route('admin.userpoint.add')}}'],
				});
			}

			//添加
			function insert(){
				var obj = layer.getChildFrame('#wt-forms');
				$.ajax({
					type:'post',
					url:'{{URL::route('admin.userpoint.insert')}}',
					data:obj.serialize(),
					async: false,
					//cache:false,
					success:function(data){

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
						 window.location.href = '{{URL::route('admin.user.userpoint')}}';
					}
					},
					error:function(data){
						layer.msg('网络通信错误', {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
						layer.closeAll('iframe');
						//tableApp.load();
					}
				});
			}
			
			//配置页面
			function set() {
				layer.open({
					type: 2,
					title: false,//'添加红包活动',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['1000px', '260px'],
					content: ['{{URL::route('admin.userpoint.set')}}'],
				});
			}

			//配置页面添加
			function setupdate(){
				var obj = layer.getChildFrame('#wt-forms');
				$.ajax({
					type:'post',
					url:'{{URL::route('admin.userpoint.setupdate')}}',
					data:obj.serialize(),
					async: false,
					//cache:false,
					success:function(data){

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
						 window.location.href = '{{URL::route('admin.user.userpoint')}}';
					}
					},
					error:function(data){
						layer.msg('网络通信错误', {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
						layer.closeAll('iframe');
						//tableApp.load();
					}
				});
			}
			

			//编辑
			function edit(id) {
				layer.open({
					type: 2,
					title: false,//'添加红包活动',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['700px', '560px'],
					content: ['{{URL::route('admin.userpoint.edit')}}?id=' + id, 'no'],
				});
			}

			//添加
			function update(){
				var obj = layer.getChildFrame('#wt-forms');
				$.ajax({
					type:'post',
					url:'{{URL::route('admin.userpoint.update')}}',
					data:obj.serialize(),
					async: false,
					//cache:false,
					success:function(data){

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
						 window.location.href = '{{URL::route('admin.user.userpoint')}}';
					}
					},
					error:function(data){
						layer.msg('网络通信错误', {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
						layer.closeAll('iframe');
						//tableApp.load();
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

				var actionUrl = "{{URL::route('admin.userpoint.delete')}}";
						$.ajax({
						type: 'get',
								url: actionUrl,
								data: 'ids=' + ids,
								cache: false,
								success: function (data) {
								// 验证不通过
								layer.msg(data.msg, {icon: 1, time: 1000});
									if (data.status = true) {
										//$('#table').bootstrapTable('refresh', ''); //刷新表格
										window.location.href = '{{URL::route('admin.user.userpoint')}}';
									}
								},
								error: function (data) {
								layer.alert(index);
								}
						});
				}, function (index) {

				});
			}
			
			//关闭窗口，在弹出的页面点击取消后去关闭页面
			function Closes(){
				layer.closeAll('iframe');
			}
			//切换启动状态
			function audit(id,enabled){
				var token =$("input[name^='_token']").val();
				var actionUrl = "{{URL::route('admin.userpoint.pointaudit')}}";
				$.ajax({
					type:"post",
					url:actionUrl,
					data:'id='+id+'&enabled='+enabled+'&_token='+token,
					cache:false,
					success:function(data){
						if(data.status) {
							layer.msg(data.msg, {
								icon: 1,
								time: 1000,
								skin: 'layer-ext-moon'
							});
							$('#table').bootstrapTable('refresh', ''); //刷新表格
							window.location.href = '{{URL::route('admin.user.userpoint')}}';
						} else {
							layer.msg(data.msg, {
								icon: 3,
								time: 1000,
								skin: 'layer-ext-moon'
							});
						}
					},
					error:function(data){
						
					}
				});
			}
			
			//-----------------------分类的修改与删除
			//编辑
			function classedit(id) {
				layer.open({
					type: 2,
					title: false,//'添加红包活动',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['420px', '160px'],
					content: ['{{URL::route('admin.userpoint.classedit')}}?id=' + id, 'no'],
				});
			}

			//修改数据
			function classupdate(){
				var obj = layer.getChildFrame('#wt-forms');
				$.ajax({
					type:'post',
					url:'{{URL::route('admin.userpoint.classupdate')}}',
					data:obj.serialize(),
					async: false,
					//cache:false,
					success:function(data){

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
						 window.location.href = '{{URL::route('admin.user.userpoint')}}';
					}
					},
					error:function(data){
						layer.msg('网络通信错误', {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
						layer.closeAll('iframe');
						//tableApp.load();
					}
				});
			}
			
			//删除，包括批量删除
			function classdel(id) {

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

				var actionUrl = "{{URL::route('admin.userpoint.classdel')}}";
						$.ajax({
						type: 'get',
								url: actionUrl,
								data: 'ids=' + ids,
								cache: false,
								success: function (data) {
								// 验证不通过
								layer.msg(data.msg, {icon: 1, time: 1000});
									if (data.status = true) {
										//$('#table').bootstrapTable('refresh', ''); //刷新表格
										window.location.href = '{{URL::route('admin.user.userpoint')}}';
									}else{
										layer.msg(data.msg, {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
										 $(":button[name='refresh']").click();
										 layer.closeAll('iframe');//关闭弹出页面
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