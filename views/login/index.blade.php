<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>伟塔运营管理系统</title>
		<meta content="User login page" name="description">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<!--<link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />-->
		<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/classone.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}user/login/css/animate.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}user/login/css/bootstrap.min.css-v=3.3.6.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}user/login/css/font-awesome.min.css-v=4.4.0.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}user/login/css/login.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}user/login/css/style.min.css" />
		<link rel="stylesheet" href="{{ASSET_URL}}user/login/css/style.min.css-v=4.1.0.css" />
	</head>
	<body class="signin">
		<div class="signinpanel">
			<div class="row">
				<div class="col-sm-5" style="margin-left:8%;">
					<form method="post" name="login" id="loginForm">
						<img class="img-responsive" alt="weitac" style="display:inline-block; margin-bottom:4px;" src="{{ASSET_URL}}user/login/img/logo.png">
						<h3 class="no-margins" style="display:inline;">  媒体运营管理系统：</h3>
						<br><br>
						<fieldset>
							<input type="text" required="required" name="username" placeholder="用户名" class="form-control uname">
							<input type="password" required="required" name="password" placeholder="密码" class="form-control pword m-b"/>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="space"></div>
							<div class="clearfix">
								<label class="inline" id="divclass">
									<!--<input class="ace" name="remember" type="checkbox" value="" id="check"/>
									<span class="lbl">忘记密码了？</span>-->
									<a href="">忘记密码了？</a>
								</label>
								<button type="submit" class="btn btn-success btn-block" style="background-color:#ec6a3a; border:none;">登录</button>
							</div>
							<div class="space-4"></div>
						</fieldset>
					</form>					
				</div>
			</div>
		</div>
		<!--basic scripts-->

		<!--[if !IE]>-->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='{{ASSET_URL}}system/admin/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<!--<![endif]-->
		
		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='{{ASSET_URL}}admin/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='{{ASSET_URL}}admin/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="{{ASSET_URL}}system/admin/js/bootstrap.min.js"></script>
		<script src="{{ASSET_URL}}system/admin/js/jquery.validate.min.js"></script>
		<script src="{{ASSET_URL}}user/login/js/bootbox_4.27.min.js"></script>

		<script type="text/javascript">
			jQuery(function($) {
			
				// 改提交方式为AJAX验证
				$('#loginForm').submit(function(){
					var obj = $(this);
					if(!obj.valid())
					{
						return false;
					}
					
					var actionUrl = '{{URL::route('admin.user.doLogin')}}';
					$.ajax({
						type:"post",
						url:actionUrl,
						data:obj.serialize(),
						success: successHandle,
						error: errorHandle
					});
					return false;
				});
			});
			
			// 成功 跳转
			function successHandle(data)
			{
				// 验证不通过
				if(data.status != true)
				{
					bootbox.dialog(data.msg, [{
							"label" : "确定",
							"class" : "btn-small btn-danger",
							"callback" : function(){
								// 回调
							}
						}]
					);
				}
				// 验证通过
				else
				{

					/* bootbox.dialog(data.msg, [{
							"label" : "确定",
							"class" : "btn-small btn-success",
							"callback" : function(){
								window.location.href="{{URL::route('admin.system.index')}}";
							}
						}]
					); */

					window.location.href="{{URL::route('admin.system.index')}}";
				}
			}
			
			// 异常问题
			function errorHandle(data)
			{
				bootbox.dialog('登录失败，网络通信错误！', [{
						"label" : "确定",
						"class" : "btn-small btn-danger",
						"callback" : function(){
							
						}
					}]
				);
			}
		</script>
	</body>
</html>