@extends('default') 

@section('content')
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/jquery-ui-1.10.3.full.min.css" />
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/ace.min.css" />


			
<div class="page-content">
	<div class="page-header position-relative">
		<h1>
			用户详情
		</h1>
	</div><!--/.page-header-->

	<div class="row-fluid">
		<div class="span12">
			<!--PAGE CONTENT BEGINS-->

			<div>
				<div id="user-profile-1" class="user-profile row-fluid">
					<div class="span3 center">
						<div>
							<span class="profile-picture">
								<img id="avatar" class="editable" alt="" src="{{WWW_URL}}/{{$detail['head_picture']}}" />
							</span> 

							<div class="space-4"></div>

							<div class="width-80 label label-info label-large arrowed-in arrowed-in-right">
								<div class="inline position-relative">
									<a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
										<i class="icon-circle light-green middle"></i>
										&nbsp; 
										<span class="white middle bigger-120">{{$user['username']}}</span>
									</a>

									<ul class="align-left dropdown-menu dropdown-caret dropdown-lighter">
										<li class="nav-header"> Change Status </li>

										<li>
											<a href="#">
												<i class="icon-circle green"></i>
												&nbsp;
												<span class="green">Available</span>
											</a>
										</li>

										<li>
											<a href="#">
												<i class="icon-circle red"></i>
												&nbsp;
												<span class="red">Busy</span>
											</a>
										</li>

										<li>
											<a href="#">
												<i class="icon-circle grey"></i>
												&nbsp;
												<span class="grey">Invisible</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						
						<div class="hr hr16 dotted"></div>
						
						<div class="profile-contact-info">
							<div class="profile-contact-links align-left">
								<a class="btn btn-link" href="#">
									<i class="icon-plus-sign bigger-120 green"></i>
									状态：@if($user['status'] == 1) 注册 @else 未注册 @endif
								</a>
								<br/>
								<a class="btn btn-link" href="#">
									<i class="icon-envelope bigger-120 pink"></i>
									管理员： @if($user['system'] == 1) 	是 @else 否 @endif 
								</a>
								<br/>
									<a class="btn btn-link" href="#">
										<i class="icon-globe bigger-125 blue"></i>
										角色：@if($role !=null ) @foreach($role as $k=>$v) {{$v['name']}} @endforeach	@endif
									</a>
								<br/>
							</div>
						</div>
						
						<h5 class="align-left">报料统计：共计爆料{{$baoliao}}条</h5>
						<h5 class="align-left">消息统计：共计发送{{$wxsum}}条信息</h5>
							  
						<div class="hr hr16 dotted"></div>
						<div class="profile-contact-info">
							<div class="profile-contact-links align-left">
								@if(!empty($wxcount))
									@foreach($wxcount as $k=>$v)
										<a class="btn btn-link" href="#">
											<i class="icon-envelope bigger-120 pink"></i>
											{{$v->token}}：共计发送{{$v->sum}}条信息
										</a>
									@endforeach
								@else
									还没有参与互动。
								@endif
							</div>
						</div>
						<div class="hr hr16 dotted"></div>
					</div>

					<div class="span9">
						<div class="space-12"></div>
						
						<h4 class="blue smaller">
							<i class="icon-rss orange"></i>
							基本信息
						</h4>
						<div class="hr hr16 dotted"></div>
						<div class="profile-activity clearfix">
							姓名：@if($detail['name'] !=null) {{$detail['name']}} @else 还未设置真实姓名 @endif
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							手机：@if($detail['mobile'] !=null) {{$detail['mobile']}} @else 还未设置手机 @endif
							<div class="btn btn-success btn-small tooltip-success" style="float:right;" id="button">
								展示更多
							</div>
						</div>
						<div class="hr hr16 dotted"></div>
						<div class="profile-user-info profile-user-info-striped" id="duo" style="display:none;">
							<div class="profile-info-row">
								<div class="profile-info-name"> 姓名 </div>

								<div class="profile-info-value">
									<span class="editable" id="username">@if($detail['name'] !=null) {{$detail['name']}} @else 还未设置真实姓名 @endif</span>
								</div>
							</div>
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 性别 </div>

								<div class="profile-info-value">
									<span class="editable" id="sex">@if(!empty($center['sex'])) {{$center['sex']}} @else 还未设置 @endif</span>
								</div>
							</div>
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 生日 </div>

								<div class="profile-info-value">
									<span class="editable" id="birthday">@if(!empty($center['year'])) {{$center['year']}} @else 还未填生日 @endif</span>
								</div>
							</div>
							
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 手机 </div>

								<div class="profile-info-value">
									<span class="editable" id="mobile">@if($detail['mobile'] !=null) {{$detail['mobile']}} @else 还未设置手机 @endif</span>
								</div>
							</div>
							
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 地址 </div>

								<div class="profile-info-value">
									<span class="editable" id="address">@if(!empty($center['address'])) {{$center['address']}} @else 还未设置地址 @endif</span>
								</div>
							</div>
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 职业 </div>

								<div class="profile-info-value">
									<span class="editable" id="zipcode">@if(!empty($center['occupation'])) {{$center['occupation']}} @else 还未设置职业 @endif</span>
								</div>
							</div>
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 积分 </div>

								<div class="profile-info-value">
									<span class="editable" id="point">{{$detail['point']}}</span>
								</div>
							</div>
							
							<div class="profile-info-row">
								<div class="profile-info-name"> 等级 </div>

								<div class="profile-info-value">
									<span class="editable" id="point">{{$detail['grade_id']}}</span>
								</div>
							</div>
							
							<div class="profile-info-row" style="display:none;">
								<input type="text" id="openid" name="openid" value="{{$detail['openid']}}"/>
							</div>
						</div>
						<div class="widget-box transparent">
							<div class="widget-header widget-header-small">
								<h4 class="blue smaller">
									<i class="icon-rss orange"></i>
									个人参与记录
								</h4>
							</div>
							<div class="widget-body">
								<div class="widget-main padding-8">
									<div id="profile-feed-1" class="profile-feed">
										<div class="profile-activity clearfix">
											<form id="form1" action="javascript:;">
												内容：<input name="Content" value="" type="text" id="Content" placeholder="请输入搜索的内容"/>
												&nbsp;&nbsp;&nbsp;
												类型：
												<select id="search_validate" onchange="renType(this.value)" class="span2">
													<option value="" selected="selected">全部</option>
													<option value="text">text</option>
													<option value="image">image</option>
													<option value="voice">voice</option>
													<option value="video">video</option>
													<option value="location">location</option>
													<option value="link">link</option>
												</select>
												栏目：
												<select id="search_validate" onchange="renToken(this.value)" class="span2">
													<option value="" selected="selected">全部</option>
													@if(!empty($lanmu))
														@foreach($lanmu as $k=>$v)
															<option value="{{$v['token']}}">{{$v['wxname']}}</option>
														@endforeach
													@endif
												</select>
												<button type="button" class="btn btn-primary btn-small" onclick="myContent();">搜索</button>
												<table id="sample-table-1" class="table table-striped table-bordered table-hover  dataTable">
													<thead>
														<tr>
															<th name="token" width="100px">栏目</th>
															<th name="MsgType" width="100px">类型</th>
															<th name="Content">内容</th>
															<th name="CreateTime" width="200px">时间</th>
														</tr>
													</thead>

													<tbody>

													</tbody>
												</table>
											</form>
										</div>
										<div class="span4">
											<div class="dataTables_info" id="sample-table-1_info">
												共有<span id="pagetotal1"></span>条&nbsp;&nbsp;&nbsp;每页
												<input type="text" value="" id="pagesize1" size="3" name="pagesize" style="width:30px;">条</div>
										</div>
										
										<div class="span7">
											<div class="dataTables_paginate paging_bootstrap pagination">
												<ul id="pagination1">
													<li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
													<li class="active"><a href="#">1</a></li>
													<li class="next"><a href="#"><i class="icon-double-angle-right"></i></a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="space-20"></div>
						<div class="widget-box transparent">
							<div class="widget-header widget-header-small">
								<h4 class="blue smaller">
									<i class="icon-rss orange"></i>
									中奖记录
								</h4>

								
							</div>

							<div class="widget-body">
								<div class="widget-main padding-8">
									<div id="profile-feed-1" class="profile-feed">
										<div class="profile-activity clearfix">
											<form id="form2" action="javascript:;">
												内容：<input name="Content" value="" type="text" id="content" placeholder="请输入搜索的内容"/>
												&nbsp;&nbsp;&nbsp;
												栏目：
												<select id="search_validate" onchange="zjType(this.value)" class="span2">
													<option value="" selected="selected">全部</option>
													@if(!empty($lanmu))
														@foreach($lanmu as $k=>$v)
															<option value="{{$v['token']}}">{{$v['wxname']}}</option>
														@endforeach
													@endif
												</select>
												<button type="button" class="btn btn-primary btn-small" onclick="zjSearch();">搜索</button>
												<table id="sample-table-2" class="table table-striped table-bordered table-hover  dataTable">
													<thead>
														<tr>
															<th name="token" width="100px">栏目</th>
															<th name="text">内容</th>
															<th name="create_time" width="200px">时间</th>
														</tr>
													</thead>

													<tbody>

													</tbody>
												</table>
											</form>
										</div>
										<div class="span4">
											<div class="dataTables_info" id="sample-table-2_info">
												共有<span id="pagetotal2"></span>条&nbsp;&nbsp;&nbsp;每页
												<input type="text" value="" id="pagesize2" size="3" name="pagesize" style="width:30px;">条</div>
										</div>
										
										<div class="span7">
											<div class="dataTables_paginate paging_bootstrap pagination">
												<ul id="pagination2">
													<li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
													<li class="active"><a href="#">1</a></li>
													<li class="next"><a href="#"><i class="icon-double-angle-right"></i></a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="space-20"></div>
						<div class="widget-box transparent">
							<div class="widget-header widget-header-small">
								<h4 class="blue smaller">
									<i class="icon-rss orange"></i>
									参加活动记录
								</h4>

							</div>

							<div class="widget-body">
								<div class="widget-main padding-8">
									<div id="profile-feed-1" class="profile-feed">
										<div class="profile-activity clearfix">
											<form id="form3" action="javascript:;">
												活动名称：<input name="Content" value="" type="text" id="key" placeholder="请输入搜索的内容"/>
												&nbsp;&nbsp;&nbsp;
												<select id="search_validate" onchange="hdType(this.value)" class="span2">
													<option value="" selected="selected">全部</option>
													@if(!empty($lanmu))
														@foreach($lanmu as $k=>$v)
															<option value="{{$v['token']}}">{{$v['wxname']}}</option>
														@endforeach
													@endif
												</select>
												<button type="button" class="btn btn-primary btn-small" onclick="hdSeach();">搜索</button>
												<table id="sample-table-3" class="table table-striped table-bordered table-hover  dataTable">
													<thead>
														<tr>
															<th name="token" width="100px">栏目</th>
															<th name="keywords" width="100px">活动名称</th>
															<th name="" width="100px">操作</th>
														</tr>
													</thead>

													<tbody>
														
													</tbody>
												</table>
											</form>
										</div>
										<div class="span4">
											<div class="dataTables_info" id="sample-table-3_info">
												共有<span id="pagetotal3"></span>条&nbsp;&nbsp;&nbsp;每页
												<input type="text" value="" id="pagesize3" size="3" name="pagesize" style="width:30px;">条</div>
										</div>
										
										<div class="span7">
											<div class="dataTables_paginate paging_bootstrap pagination">
												<ul id="pagination3">
													<li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
													<li class="active"><a href="#">1</a></li>
													<li class="next"><a href="#"><i class="icon-double-angle-right"></i></a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>


			<!--PAGE CONTENT ENDS-->
		</div><!--/.span-->
	</div><!--/.row-fluid-->
</div><!--/.page-content-->

<form id="wt-form" class="modal fade hide form-horizontal" method="post" onsubmit="return false;"></form>


@stop

@section('jsfile')

<script src="{{ASSET_URL}}system/admin/js/jquery.ui.touch-punch.min.js"></script>

<script src="{{ASSET_URL}}system/admin/js/weitac/weitac.global.js"></script>	
<script src="{{ASSET_URL}}system/admin/js/table/weitac.js"></script>	
<script src="{{ASSET_URL}}system/admin/js/date-time/moment.min.js"></script>
<script src="{{ASSET_URL}}system/admin/js/date-time/daterangepicker.min.js"></script>
	
<script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/weitac1.table.js"></script>
<script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.tablesorter.js"></script>
<script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.pagination.js"></script>

<!--inline scripts related to this page-->
<!--个人参与记录-->
<script type="text/javascript">
	var FromUserName = $("#openid").val();
	var row_template1 = '<tr>';
	row_template1 += '<td>{token}</td>';
	row_template1 += '<td>{MsgType}</td>';
	row_template1 += '<td>{Content}</td>';
	row_template1 += '<td>{CreateTime}</td>';
	row_template1 += '</tr>';
	var tableApp1 = new ct.table('#sample-table-1', {
		rowIdPrefix: 'row1_',
		pageSize: 15,
		rowCallback: 'init_row_event1',
		jsonLoaded: json_loaded1,
		dblclickHandler: '',
		pagination: 'pagination1',
		template: row_template1,
		baseUrl: '{{URL::route('admin.user.showAjax')}}?orderby=id|desc&FromUserName='+FromUserName
	});
	
	function json_loaded1(a) {
		$('#pagetotal1').html(a.total);
		for (d = 0; a.data[d]; d++) {
			if (a.data[d]) {
				a.data[d].key = d + 1;
			}
		}
	}
	
	function init_row_event1(id, tr)
	{


	}
	$(function() {
		tableApp1.load();
		$('#pagesize1').val(tableApp1.getPageSize());
		$('#pagesize1').blur(function() {
			var p = $(this).val();
			tableApp1.setPageSize(p);
			tableApp1.load();
		});


	});
	
	$("#button").click(function(){
		$("#duo").toggle();
	});
	
	//类型搜索
	function renType($obj){
		name="type="+$obj;
		tableApp1.load(name);
	}
	
	//栏目搜索
	function renToken($obj){
		name="token="+$obj;
		tableApp1.load(name);
	}
	
	//内容搜索
	function myContent(){
		name="Content="+$("#Content").val();
		tableApp1.load(name);
	}
	
</script>

<!--中奖记录-->

<script type="text/javascript">
	var FromUserName = $("#openid").val();
	var row_template2 = '<tr>';
	row_template2 += '<td>{token}</td>';
	row_template2 += '<td>{text}</td>';
	row_template2 += '<td>{create_time}</td>';
	row_template2 += '</tr>';
	var tableApp2 = new ct.table('#sample-table-2', {
		rowIdPrefix: 'row2_',
		pageSize: 15,
		rowCallback: 'init_row_event2',
		jsonLoaded: json_loaded2,
		dblclickHandler: '',
		template: row_template2,
		pagination: 'pagination2',
		baseUrl: '{{URL::route('admin.user.zjAjax')}}?orderby=id|desc&user_id='+{{$user['user_id']}}+'&openid='+FromUserName
	});
	
	function json_loaded2(a) {

		$('#pagetotal2').html(a.total);
		for (d = 0; a.data[d]; d++) {
			if (a.data[d]) {
				a.data[d].key = d + 1;
			}
		}
	}
	
	function init_row_event2(id, tr)
	{


	}
	$(function() {
		tableApp2.load();
		$('#pagesize2').val(tableApp2.getPageSize());
		$('#pagesize2').blur(function() {
			var p = $(this).val();
			tableApp2.setPageSize(p);
			tableApp2.load();
		});
	});
	
	//中奖栏目
	function zjType($obj){
		name="token="+$obj;
		tableApp2.load(name);
	}
	
	//中奖内容
	function zjSearch(){
		name="content="+$("#content").val();
		tableApp2.load(name);
	}
</script>

<!--活动记录-->
<script type="text/javascript">
	var FromUserName = $("#openid").val();
	var row_template3 = '<tr>';
	row_template3 += '<td>{token}</td>';
	row_template3 += '<td>{name}</td>';
	row_template3 += '<td><button type="button" class="btn btn-primary btn-small" onclick="hddetail(\'{name}\')">查看详细</button></td>';
	row_template3 += '</tr>';
	var tableApp3 = new ct.table('#sample-table-3', {
		rowIdPrefix: 'row3_',
		pageSize: 15,
		rowCallback: 'init_row_event3',
		jsonLoaded: json_loaded3,
		dblclickHandler: '',
		pagination: 'pagination3',
		template: row_template3,
		baseUrl: '{{URL::route('admin.user.kwAjax')}}?orderby=id|desc&FromUserName='+FromUserName
	});
	
	function json_loaded3(a) {
		$('#pagetotal3').html(a.total);
		for (d = 0; a.data[d]; d++) {
			if (a.data[d]) {
				a.data[d].key = d + 1;
			}
		}
	}
	
	function init_row_event3(id, tr)
	{


	}
	$(function() {
		tableApp3.load();
		$('#pagesize3').val(tableApp3.getPageSize());
		$('#pagesize3').blur(function() {
			var p = $(this).val();
			tableApp3.setPageSize(p);
			tableApp3.load();
		});


	});
	
	
	//类型搜索
	function hdSeach(){
		name="key="+$("#key").val();
		tableApp3.load(name);
	}
	
	//栏目搜索
	function hdType($obj){
		name="token="+$obj;
		tableApp3.load(name);
	}

</script>
<script type="text/javascript">
	function hddetail($name){
		$editUrl ="{{URL::route('admin.user.hddetail')}}?name="+$name+"&user_id="+{{$user['user_id']}};
		$.weitac.formShow($editUrl,'wt-form');
	}
</script>
@stop