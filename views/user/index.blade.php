<!DOCTYPE html>
<html>

    <!-- Mirrored from www.zi-han.net/theme/hplus/index_v2.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 06 Sep 2015 05:15:10 GMT -->
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">

        <title>用户列表</title>
        <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
        <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/jquery-ui-1.10.3.full.min.css" />
        <link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
        <link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">

        <link href="{{ASSET_URL}}system/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
        <link href="{{ASSET_URL}}system/admin/css/bootstrap-responsive.min.css" rel="stylesheet" /><!-- 按钮的排版样式 -->
		
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/js/bootstrap-datetimepicker.css" />
		<style>
			/* 自定义显示的form高度 */
			.hang1 {
				margin-top:12px;
				height:32px;
				border-bottom:1px dashed #E0E0E0;
			}
			.hang1 div{
				float:left;
				margin:auto 10px;
				font-size:16px;
			}
			.lie1{
				width:11%;
				text-align:right;
				color:#9B9DA0;
				font-size:16px;
			}
			.first{
				width:10%;
				text-align:right;
				font-weight:bolder;
				color:#9B9DA0;
				font-size:18px;
			}
			.fontcss{
				text-decoration:none;
				color:#393939;
			}
		</style>
    </head>

    <body class="gray-bg">

        <div class="page-content">
            <!--main-container-->
            <div class="main-container container-fluid">
                <a class="menu-toggler" id="menu-toggler" href="#">
                    <span class="menu-text"></span>
                </a>
                <div class="row-fluid">
                    <div class="span12">

                        <h3 class="header blue lighter smaller span12" style="margin-top:10px;margin-bottom:7px;">
                            <div class="span12">
                                <div class="span2">
                                    <a href="javascript:;" onclick="add();" class="btn btn-outline btn-default"><i class="fa fa-plus text-navy"></i>创建用户</a>

                                </div>


                                <div class="span5" >

                                    <label class="col-sm-6 control-label" >用户名：
									
										<input name="search_name" value="" type="text" id="search_name" placeholder="请输入用户名"/>
										<button type="button" class="btn btn-primary btn-small" id="search_submit" onclick="search();"><i class="fa fa-search"></i>搜索</button>
									
									</label>
									
                                </div>
								<div class="span5">
									<button type="button" class="btn btn-primary btn-small" id="precise" data-toggle="dropdown">
										精确搜索
										<span class="caret"></span>
									</button>
								</div>
							</div>                                         <!--<button class="btn btn-small btn-primary " id="addtv" onclick="history.go(-1);" ><i class="icon-reply bigger-110" ></i>返回</button>-->
                        </h3>
                        <br>
						<div style="width:100%;height:400px;display:none;" id="qiehuantwo">
							<!--<div style="margin:0px;padding:0px;font-size:18px;">共&nbsp;&nbsp;<span style="color:red;"></span>&nbsp;&nbsp;条</div>-->
							<div style="margin:28px auto auto 0px;" >
								<div class="hang1">
									<div class="first">
										已选条件：
									</div>	
									<div id="divv">
										
									</div>
								</div>
								<div class="hang1">
									<div class="lie1">用户个人信息：</div>
									<div>
										地区：<input type="text" id="diqu" name="diqu" placeholder="请输入城市例如：济南" onblur="myCity();"/>&nbsp;&nbsp;&nbsp;
									</div>
									<div>
										性别：<input type="radio" class="ace" name="sex" value="" checked="checked" onclick="mySex('');"/>&nbsp;<span class="lbl">全部</span>&nbsp;&nbsp;
											  <input type="radio" class="ace" name="sex" value="1" onclick="mySex('1');"/>&nbsp;<span class="lbl">男</span>&nbsp;&nbsp;
											  <input type="radio" class="ace" name="sex" value="2" onclick="mySex('2');"/>&nbsp;<span class="lbl">女</span>
									</div>
								</div>
								<div class="hang1">
									<div class="lie1">
										栏目导航：
									</div>
									<div>
										<a href="#" class="fontcss" onclick="myToken();">全部</a>
									</div>
									@foreach($dat as $k=>$v)
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myToken('{{$v['token']}}','{{$v['wxname']}}');">{{$v['wxname']}}</a>
									</div>
									@endforeach
									
								</div>
								<div class="hang1">
									<div class="lie1">
										用户来源：
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="mySource('');">全部</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="mySource('1');">客户端</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="mySource('0');">微信</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="mySource('2');">短信</a>
									</div>
								</div>
								<div class="hang1">
									<div class="lie1">
										验证：
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myCheck('');">全部</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myCheck('1');">已验证</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myCheck('0');">未验证</a>
									</div>
								</div>
								
								<div class="hang1">
									<div class="lie1">
										状态：
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myStatus('');">全部</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myStatus('1');">正常</a>
									</div>
									<div>
										<a href="javascript:void(0);" class="fontcss" onclick="myStatus('0');">待审</a>
									</div>
								</div>
								
								<div class="hang1">
									<div class="lie1">
										IP地址：
									</div>
									<div>
										<input id="ip" type="text" style="width:183px" name="ip" placeholder="请输入IP地址" onblur="myIp();">
									</div>
								</div>
								
								<div class="hang1">
									<div class="lie1">
										时间：
									</div>
									<div>
										注册时间：
									</div>
									<div>
										<input id="startdate" type="text" style="width:183px" name="created_at" placeholder="请输入注册时间" >
									</div>
									
									<div>
										最后登录时间：
									</div>
									<div>
										<input id="enddate" type="text" style="width:183px" name="last_login" placeholder="请输入最后登陆时间">
									</div>
									<div>
										<button class="btn btn-primary btn-small" onclick="myLogo();">搜索</button>
									</div>
								</div>
							</div>
							
						</div>
                        <div role="grid" class="dataTables_wrapper" id="sample-table-2_wrapper">
                            <div class="span12">
                                <table id="sample-table-2" class="table table-striped table-bordered table-hover  dataTable">
                                    <thead>
										<tr >
											<th class="center" width="2%">
												<label>
													<input type="checkbox" class="ace"/>
													<span class="lbl"></span>
												</label>
											</th>
											<th width="30px">ID</th>
											<th >用户名</th>
											<th width="150px">昵称</th>	
											<th width="40px">
												状态
											</th>
											<th width="150px">
												角色
											</th>
											<th width="80px">
												注册时间
											</th>
											<th width="80px">
												最后登录
											</th>
											<th width="165px">操作</th>
										</tr>
									</thead>
                                    <tbody>
                                    </tbody>
                                </table>								
                                <div class="row-fluid">
                                    <div class="span4">

                              <button class="btn btn-small btn-primary"><i class="fa fa-trash-o"></i>删除</button>
                               <!--   <button class="btn btn-small btn-primary"><i class="icon-undo bigger-110"></i>回复</button>
                                <button class="btn btn-small btn-danger"><i class="icon-trash bigger-110"></i>删除</button>-->


                                    </div>
                                    <div class="span4">
                                        <div class="dataTables_info" id="sample-table-2_info">
                                            共有<span id="pagetotal"></span>条记录&nbsp;&nbsp;&nbsp;每页
                                            <input type="text" value="" id="pagesize" size="3" name="pagesize" style="width:42px;"> 条</div>
                                    </div>
                                    <div class="span4">
                                        <div class="dataTables_paginate paging_bootstrap pagination" style="margin: 0px 0;">
                                            <ul  id="pagination">
                                                <li class="prev disabled"><a href="#"><i class="icon-double-angle-left"></i></a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li class="next"><a href="#"><i class="icon-double-angle-right"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>

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
		<script src="{{ASSET_URL}}system/admin/js/bootstrap-datetimepicker.js"></script>
        <!-- layer javascript -->
		<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
		

        <script src="{{ASSET_URL}}system/admin/js/jquery.ui.touch-punch.min.js"></script>

        <script src="{{ASSET_URL}}system/admin/js/table/weitac.js"></script>		
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/weitac.table.js"></script>
		<script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/js/table/jquery.pagination.js"></script> 

		<script type="text/javascript">
			var manage_operation = '<td>\
			<div class="hidden-phone visible-desktop action-buttons">\
			<a class="btn btn-white btn-bitbucket"  href="javascript:void(0);" title="查看" onclick="show({user_id});">\
			<i class="fa fa-search"></i>\
			</a>\
			<a class="btn btn-white btn-bitbucket"  href="javascript:void(0);" title="修改" onclick="tvedit({user_id});">\
			<i class="fa fa-pencil"></i>\
			</a>\
			<a class="btn btn-white btn-bitbucket" href="javascript:void(0);" title="修改密码" onclick="pwd({user_id});">\
			<i class="fa fa-lock" ></i>\
			</a>\
			<a class="btn btn-white btn-bitbucket" href="javascript:void(0);" title="删除" onclick="datadel({user_id});">\
			<i class="fa fa-trash-o" ></i>\
			</a>\
			</div>\
			</td>';
			var row_template = '<tr>';
			row_template += '<td class="center"><label><input type="checkbox" class="ace" name="check[]" value="{user_id}"/><span class="lbl"></span></label></td>';
			row_template += '<td>{user_id}</td>';
			row_template += '<td style="width:80px;text-align:center;">{username}</td>';
			row_template += '<td>{nickname}</td>';
			row_template += '<td>{status}</td>';
			row_template += '<td>{role}</td>';
			row_template += '<td>{created_at}</td>';
			row_template += '<td>{last_login}</td>';
			//row_template +='<td>{isweixin}</td>';
			row_template += manage_operation;
			row_template += '</tr>';
			var tableApp = new ct.table('#sample-table-2', {
			rowIdPrefix: 'row_',
					pageSize: 15,
					rowCallback: 'init_row_event',
					jsonLoaded: json_loaded,
					dblclickHandler: '',
					template: row_template,
					baseUrl: '{{URL::route('admin.core.user.user.ajaxindex')}}?orderby=user_id|desc'
			});
			function json_loaded(a) {

				$('#pagetotal').html(a.total);
				for (d = 0; a.data[d]; d++) {
					if (a.data[d]) {
						a.data[d].key = d + 1;
					}
				}
			}

			function init_row_event(id, tr)
			{


			}
			$(function () {

				tableApp.load();
				$('#pagesize').val(tableApp.getPageSize());
				$('#pagesize').blur(function () {
						var p = $(this).val();
						tableApp.setPageSize(p);
						tableApp.load();
				});
			});
			
			
		</script>

		<script type="text/javascript">
			part4_bool=false;
			pbool=false;
			$("#precise").click(function(){
				if(part4_bool) {
					part4_bool=false;
					$("#qiehuantwo").hide(); 
				}else {
					part4_bool=true;
					$("#qiehuantwo").show();
				}
			});
			
			
			//状态搜索
			function myStatus(obj)
			{		
					var obj = obj;
					var ques='';
					if(obj ==''){
						ques+='<div class="sc_status" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>状态：全部</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_status'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';
					}else if(obj =='1'){
						ques+='<div class="sc_status" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>状态：正常</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_status'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';
					}else{
						ques+='<div class="sc_status" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>状态：待审</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_status'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';  
					}
					$(".sc_status").remove(); 
					$('#divv').append(ques);
					status = obj;
					name = "status="+obj;
					tableApp.load(name);
			}
			
			//来源搜索
			function mySource(obj)
			{		var ques='';
					
					var obj = obj;
					if(obj ==''){
						ques+='<div class="sc_source" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>来源：全部</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_source'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';
					}else if(obj =='1'){
						ques+='<div class="sc_source" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>来源：客户端</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_source'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';
					}else if(obj =='0'){
						ques+='<div class="sc_source" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>来源：微信</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_source'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';
					}else{
						ques+='<div class="sc_source" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
						ques+='<span>来源：短信</span>';
						ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_source'"+');">';
						ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
						ques+='</a>';
						ques+='</div>';
					}
					$(".sc_source").remove(); 
					$('#divv').append(ques);
					source = obj;
					name = "source="+obj;
					tableApp.load(name);
			}
			
			//栏目
			function myToken(obj,wxname)
			{
				var obj = obj;
				var wxname = wxname;
				var ques='';
				if(wxname != undefined){
					ques+='<div class="sc_token" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>栏目：'+wxname+'</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_token'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else{
					ques+='<div class="sc_token" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>栏目：全部</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_token'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}
				$(".sc_token").remove(); 
				$('#divv').append(ques);
				token = obj;
				name = "token="+obj;
				tableApp.load(name);
			}
			
			//地区
			function myCity()
			{	
				var ques='';
				city = $("#diqu").val();
				if(city != ''){
					ques+='<div class="sc_city" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>地区：'+city+'</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_city'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}
				$(".sc_city").remove(); 
				$('#divv').append(ques);
				name = "city="+city;
				tableApp.load(name);
			}
			
			
			//性别
			function mySex(obj)
			{	var obj = obj;
				var ques='';
				if(obj == ''){
					ques+='<div class="sc_sex" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>性别：全部</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_sex'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else if(obj == '1'){
					ques+='<div class="sc_sex" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>性别：男</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_sex'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else{
					ques+='<div class="sc_sex" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>性别：女</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_sex'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}
				$(".sc_sex").remove(); 
				$('#divv').append(ques);
				sex = obj;
				name ="sex="+obj;
				tableApp.load(name);
			}
			
			//验证搜索
			function myCheck(obj)
			{
				var obj = obj;
				var ques='';
				if(obj == ''){
					ques+='<div class="sc_check" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>验证：全部</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_check'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else if(obj == '1'){
					ques+='<div class="sc_check" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>验证：已验证</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_check'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else{
					ques+='<div class="sc_check" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>验证：未验证</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_check'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}
				$(".sc_check").remove(); 
				$('#divv').append(ques);
				is_validate = obj;
				name = "is_validate="+obj
				tableApp.load(name);
			}
			
			
			//最后登陆时间
			function myLogo()
			{
			
				var ques='';
				created_at = $('#startdate').val();
				last_login = $('#enddate').val();
				if(created_at !="" && last_login !=""){
					name = "created_at="+created_at+"&last_login="+last_login;
					ques+='<div class="sc_logo" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>创建时间/最后登陆时间</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_logo'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else if(created_at !="" && last_login ==""){
					name = "created_at="+created_at;
					name = "created_at="+created_at+"&last_login="+last_login;
					ques+='<div class="sc_logo" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>创建时间</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_logo'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}else{
					name = "last_login="+last_login; 
					name = "created_at="+created_at+"&last_login="+last_login;
					ques+='<div class="sc_logo" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
					ques+='<span>最后登陆时间</span>';
					ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_logo'"+');">';
					ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
					ques+='</a>';
					ques+='</div>';
				}
				$(".sc_logo").remove(); 
				$('#divv').append(ques);
				name = "created_at="+created_at+"&last_login"+last_login;
				tableApp.load(name);
			}
			
			//IP地址
			function myIp(){
				var ques='';
				ques+='<div class="sc_ip" style="border:1px solid #cf1b33;margin:0 5px 0 10px;padding:0 4px;">';
				ques+='<span>IP</span>';
				ques+='<a href="javascript:void(0);" onclick="shanchu('+"'sc_ip'"+');">';
				ques+='<img src="{{ASSET_URL}}/user/images/fangyuan_icon2.png"/>';
				ques+='</a>';
				ques+='</div>';
				$(".sc_ip").remove(); 
				$('#divv').append(ques);
				last_ip = $('#ip').val();
				name = "last_ip="+last_ip;
				tableApp.load(name);
			}
			
			//删除标签
			function shanchu(name){
				var name = name;
				$('.'+name).remove();  
				//alert($("#divv span").text());
			}
		</script>
        <script type="text/javascript">
			//密码修改
			function pwd(id){
				layer.open({
					type: 2,
					title: ['修改密码', 'font-size:18px;background:#307ECC;color:#fff'],
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['500px', '200px'],
					content: ['{{URL::route('admin.user.pwd')}}?user_id='+id,'no'], 
				});
			} 
			
			//密码修改入库
			function dopwd(){
				var obj = layer.getChildFrame("#wt-forms");
				var actionUrl = "{{URL::route('admin.user.do.pwd')}}";
				$.ajax({
					type:'post',
					url:actionUrl,
					data:obj.serialize(),
					async: false,//需要将ajax改为同步执行
					//cache:false,
					success:function(data){
						if (data.status != true)
						{
							layer.confirm(data.msg);
						}
						// 验证通过
						else
						{
							layer.confirm(data.msg);
							layer.closeAll('iframe');
						}
					},
					error:function(data){
						layer.confirm('添加失败');
						layer.closeAll();
					}
				});
				
			}
			
			
			//详细信息
			function show(id){
				layer.open({
					type: 2,
					title: '用户详细',
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['100%', '100%'],
					content: ['{{URL::route('admin.user.show')}}?user_id='+id],
				});
			}
			
			//添加
			function add() {
			  var index = layer.open({
					type: 2, 
					// skin:'demo-class',
					title: ['添加用户', 'font-size:18px;background:#307ECC;color:#fff'],
					move: '.layui-layer-title',  //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['700px', '480px'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose:true, //是否允许点击遮罩层关闭弹窗 true /false
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift:0,  //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.user.add')}}','yes'], 
					btn: ['确定', '取消'], 
					yes: function (index) {
						var obj = layer.getChildFrame('#wt-forms');
						var actionUrl = "{{URL::route('admin.user.insert')}}";
						$.ajax({
						type:'post',
								url:actionUrl,
								data:obj.serialize(),
								async: false,//需要将ajax改为同步执行
								//cache:false,
								success:function(data){
									// 验证不通过
									layer.confirm(data.msg, {icon: 3}, function(index){
										layer.close(index); //关闭
									});
									//关闭弹出页面
									layer.closeAll('iframe');
									tableApp.load();//刷新页面
								},
								error:function(data){
									layer.confirm('添加失败', {icon: 3}, function(index){
										layer.close(index); //关闭
									});
									layer.closeAll('iframe');
									tableApp.load();
								}
						});
					
					}
				});    
			}
			//编辑
			function tvedit(id){
				var index = layer.open({
					type: 2, 
					// skin:'demo-class',
					title: ['修改用户', 'font-size:18px;background:#307ECC;color:#fff'],
					move: '.layui-layer-title',  //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
					area: ['700px', '480px'], //设置弹出框的宽高
					//area: ['100%', '100%'], //设置弹出框的宽高
					shade: [0.5, '#000'], //配置遮罩层颜色和透明度
					shadeClose:true, //是否允许点击遮罩层关闭弹窗 true /false
					// time:1000,  设置自动关闭窗口时间 1秒=1000；
					shift:0,  //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
					content: ['{{URL::route('admin.core.user.edit')}}?user_id=' + id,'yes'], 
					btn: ['确定', '取消'], 
					yes: function (index) {
						//获取修改页面的数据
						var obj = layer.getChildFrame('#wt-forms-edit');
						var actionUrl = "{{URL::route('admin.core.user.update')}}";
						$.ajax({
							type:'post',
							url:actionUrl,
							data:obj.serialize(),
							async: false,
							//cache:false,
							success:function(data){
								// 验证不通过，提示弹框
								layer.confirm('修改成功', {icon: 3}, function(index){
									layer.close(index); //关闭
								});
								//关闭弹出页面
								layer.closeAll('iframe');
								tableApp.load();//刷新页面
							},
							error:function(data){
								layer.confirm('修改失败', {icon: 3}, function(index){
									layer.close(index); //关闭
								});
								//关闭弹出页面
								layer.closeAll('iframe');
								tableApp.load();
							}
						});
					}
				}); 
			}

			//判断删除
			function datadel(id){
				layer.confirm('确定操作？', {icon: 3}, function(index){
					tvdel(id);
					layer.close(index); //关闭
				});
			}
			// 删除
			function tvdel(id)
			{
				var obj = 'user_id=' + id;
				var actionUrl = "{{URL::route('admin.user.destroy')}}";
				$.ajax({
					type: 'GET',
					url: actionUrl,
					data: obj,
					async: false,
					//cache:false,
					success: function(data) {
					// 验证不通过
					if (data.status != true)
					{
						layer.confirm('删除失败！', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
					}
					// 验证通过
					else
					{
						layer.confirm('删除成功！', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
							tableApp.load();
					}
					},
					error: function(data) {
						layer.confirm('网络通信错误！', {icon: 3}, function(index){
							layer.close(index); //关闭
						});
					}
				});
			}

			//模糊搜索
            function search()
            {
				name = "username="+$("#search_name").val();
				tableApp.load(name);
            }
			
			//关闭窗口，在弹出的页面点击取消后去关闭页面
			function Closes(){
				layer.closeAll('iframe');
			}
        </script>
		
		<script>
			 $(function(){
				   var keshih = $(window).height();
				   $('#btn-scroll-up').css('position','fixed');
			 });

			 jQuery(function($) {
				$('#startdate,#enddate').datetimepicker({step:15, format:"yyyy-mm-dd hh:ii", autoclose:true});
				});
				function update(date){
				alert(date);
			}

			 jQuery(function($) {
				$('#livedata').datetimepicker({step:15, format:"yyyy-mm-dd hh:ii", autoclose:true});
				});
				function update(date){
				alert(date);
			}

			function res(){
					$("form input,textarea").val("");
					$("form input,textarea").val("");
				}
				
			</script>
    </body>
</html>