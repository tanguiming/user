<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="renderer" content="webkit">

		<title>个人资料</title>
		<meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
		<meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

		<link href="{{ASSET_URL}}system/hplus/css/bootstrap.mind797.css?v=3.4.0" rel="stylesheet">
		<link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
		<link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">
		<link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet">
		
		<script src="{{ASSET_URL}}user/more/jquery.js"></script>
		<script src="{{ASSET_URL}}user/more/jquery.SuperSlide.js"></script>
		<script src="{{ASSET_URL}}user/more/jquery.infinitescroll.js"></script>
		<script type="text/javascript">
		$(function(){
			function item_callback(){ 
				$('.item').mouseover(function(){
					//$(this).css('box-shadow', '0 1px 5px rgba(35,25,25,0.5)');
					$('.btns',this).show();
				}).mouseout(function(){
					//$(this).css('box-shadow', '0 1px 3px rgba(34,25,25,0.2)');
					$('.btns',this).hide();         
				});

			}

			item_callback();  

			$('.item').fadeIn();

			var sp = 1
			
			$(".infinite_scroll").infinitescroll({
				navSelector     : "#cha",
				nextSelector    : "#cha a",
				itemSelector    : ".item",
				
				loading:{
					msgText: ' ',
					finishedMsg: '抱歉，没有啦！',
					finished: function(){
						sp++;
						if(sp>=100000){ //到第10页结束事件
							$("#page").show();
						}
						$("#infscr-loading").hide();
					}   
				},errorCallback:function(){ 
					$("#page").show();
				}        
			},function(newElements){
				
				var $newElems = $(newElements);
				$newElems.fadeIn();
				item_callback();
				return;
			});
			$(window).unbind('.infscr');
		  // 手动点击的元素
			$('#cha a').click(function(){
					jQuery('.infinite_scroll').infinitescroll('retrieve');
				 return false;

			});
		});
		</script>
	</head>

	<body class="gray-bg">
		<div class="wrapper wrapper-content">
			<div class="row animated fadeInRight">
				<div class="col-sm-4">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>个人资料</h5>
						</div>
						<div>
							<div class="ibox-content no-padding border-left-right">
								@if(!empty($detail['head_picture']))
									<img alt="image" style="width:120px;height:120px;padding:10px;" class="img-responsive" src="{{WWW_URL}}/{{$detail['head_picture']}}"/>
								@else
									<img alt="image" class="img-responsive" src="{{ASSET_URL}}/hplus/img/profile_big.jpg">
								@endif
							</div>
							<div class="ibox-content profile-content">
								<h4><strong>@if(!empty($user['username'])) {{$user['username']}} @else 未设置姓名 @endif</strong></h4>
								<p><i class="fa fa-map-marker"></i> @if(!empty($detail['mobile'])){{$detail['mobile']}} @else 未关联手机号 @endif<i class="fa fa-chevron-down" style="float:right;" id="button"></i></p>
								<div class="row m-t-lg" id="duo" style="display:none;">
									<div class="col-sm-6">
										<span>姓名:<strong>@if($detail['name'] !=null) {{$detail['name']}} @else 还未设置真实姓名 @endif</strong></span>
									</div>
									<div class="col-sm-6">
										<span>性别:
											<strong>
											@if(!empty($detail['sex']))
												@if($detail['sex']==1)
													男
												@else
													女
												@endif
											@else
												还未设置
											@endif
											</strong>
										</span>
									</div>
									<div class="col-sm-6">
										<span>生日:<strong>@if(!empty($detail['birthday'])) {{$detail['birthday']}} @else 还未填生日 @endif</strong></span>
									</div>
									<div class="col-sm-6">
										<span>手机:<strong>@if($detail['mobile'] !=null) {{$detail['mobile']}} @else 还未设置手机 @endif</strong></span>
									</div>
									<div class="col-sm-6">
										<span>地址:<strong>@if(!empty($detail['address'])) {{$detail['address']}} @else 还未设置地址 @endif</strong></span>
									</div>
									<div class="col-sm-6">
										<span>职业:<strong>@if(!empty($center['occupation'])) {{$center['occupation']}} @else 还未设置职业 @endif</strong></span>
									</div>
									<div class="col-sm-6">
										<span>积分:<strong>{{$detail['point']}}</strong></span>
									</div>
									<div class="col-sm-6">
										<span>等级:<strong>{{$detail['grade_id']}}</strong></span>
									</div>
								</div>
								<br/>
								<h5>
									关于我
								</h5>
								<p>
									会点前端技术，div+css啊，jQuery之类的，不是很精；热爱生活，热爱互联网，热爱新技术；有一个小的团队，在不断的寻求新的突破。
								</p>
								<div class="row m-t-lg">
									<div class="col-sm-4">
										<span class="bar">5,3,9,6,5,9,7,3,5,2</span>
										<h5>状态：@if($user['status'] == 1) 注册 @else 未注册 @endif</h5>
									</div>
									<div class="col-sm-4">
										<span class="line">5,3,9,6,5,9,7,3,5,2</span>
										<h5>管理员：@if($user['system'] == 1) 	是 @else 否 @endif </h5>
									</div>
									<div class="col-sm-4">
										<span class="bar">5,3,2,-1,-3,-2,2,3,5,2</span>
										<h5>角色：@if($role !=null ) @foreach($role as $k=>$v) {{$v['name']}} @endforeach	@endif</h5>
									</div>
								</div>
								<div class="user-button">
									<div class="row">
										<div class="col-sm-6">
											<button type="button" class="btn btn-primary btn-sm btn-block" onclick="send({{$detail['user_id']}})"><i class="fa fa-envelope"></i> 发送消息</button>
										</div>
										<div class="col-sm-6">
											<button type="button" class="btn btn-default btn-sm btn-block"><i class="fa fa-coffee"></i> 赞助</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>最新动态</h5>
							<!--<div class="ibox-tools">
								<a class="collapse-link">
									<i class="fa fa-chevron-up"></i>
								</a>
								<a class="dropdown-toggle" data-toggle="dropdown" href="profile.html#">
									<i class="fa fa-wrench"></i>
								</a>
								<ul class="dropdown-menu dropdown-user">
									<li><a href="profile.html#">选项1</a>
									</li>
									<li><a href="profile.html#">选项2</a>
									</li>
								</ul>
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>-->
						</div>
						<div class="ibox-content infinite_scroll">
							
							
							<div class="item">
								@if($information)
								@foreach($information as $k=>$v)
								<div class="feed-activity-list" style="height:45px;line-height:45px;border-bottom:1px solid #E7EAEC">
									<div>
										{{date('Y-m-d H:i:s',$v['CreateTime'])}} ：{{$v['Content']}}
									</div>
								</div>
								@endforeach
								@else
									暂无数据
								@endif
							</div>
							
						</div>
						
						<div id="cha" class="btn btn-primary btn-block m"><a href="{{URL::route('admin.core.user.useradmin.more')}}?page=2&user_id={{$user_id}}" style="text-decoration:none; color:white;"><i class="fa fa-arrow-down"></i>显示更多</a></div>
					</div>
				</div>

			</div>
		</div>



	</body>
	
		<!-- 全局js 
		<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>-->
		<script src="{{ASSET_URL}}system/hplus/js/content.mine209.js?v=1.0.0"></script>
		<script src="{{ASSET_URL}}system/hplus/js/plugins/peity/jquery.peity.min.js"></script>
		<script src="{{ASSET_URL}}system/hplus/js/demo/peity-demo.min.js"></script>
		<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/js/bootstrap-table.js"></script>
        <script src="{{ASSET_URL}}system/admin/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
		
		<script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
		<script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>
		<script>
			$("#button").click(function(){
				$("#duo").toggle();
			});
		</script>
		
        <script>
			function send(id){
				layer.open({
					type: 2,
					title: ['发送消息', 'font-size:18px;background:#307ECC;color:#fff'],
					//maxmin: true,
					shadeClose: true, //点击遮罩关闭层
					area : ['700px', '500px'],
					content: ['{{URL::route('admin.core.user.useradmin.award')}}?id='+id,'no'], 
				});
			} 
		</script>

<!-- Mirrored from www.zi-han.net/theme/hplus/profile.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 06 Sep 2015 05:14:53 GMT -->
</html>