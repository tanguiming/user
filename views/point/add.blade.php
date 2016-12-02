<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>添加规则</title>

        <!--        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.mind797.css?v=3.4.0" rel="stylesheet">-->
        <!-- 新 Bootstrap 核心 CSS 文件 -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- 可选的Bootstrap主题文件（一般不用引入） -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

        <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
    </head>
    <body>
		<!-- <div class="modal-content animated bounceInRight"> -->
		<!--<div class="modal-body">-->
		<div>
			<form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
				<input type="hidden" id="pointcount" value="{{$pointcount}}"></div>
				
				<div class="allinput" style="display:none;width:600px;margin-left:50px">
					<div style="width:390px;margin-top: 20px;margin-bottom:20px"><button type="button" class="btn btn-small btn-success" onclick="pointback();" style="width:150px;">再添加一个新分类</button></div>
					
					<div class="form-group">
						<label class="col-xs-3 control-label">规则分类：</label>
						<div class="col-xs-8">
							<select name="classid" class="form-control" id="selectchoose">
								<option value="">请选择一个分类</option>
								@foreach($classtitle as $k=>$v)
								<option value="{{$v['classid']}}">{{$v['classtitle']}}</option>
								@endforeach
							</select> 
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" style="margin-top:10px;">规则名称：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<input type="text" name="name" class="form-control input-sm" placeholder="请输入规则名称"> 
						</div>
					</div>  
					<div class="form-group ">
						<label class="col-xs-3 control-label" style="margin-top:10px;">规则拼音：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<input type="text" name="pinyin" class="form-control input-sm" placeholder="请输入规则拼音"><div style="color:red;">规则拼音不可修改，请认真输入</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-3 control-label" style="margin-top:10px;">选择方式：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<select name="chooseway" class="form-control" onchange="choicebrand(this.value)">
								<option value="">请选择一种方式</option>
								<option value="1">加{{$globalname}}（按固定值）</option>
								<option value="2">加{{$globalname}}（按比例）</option>
								<option value="3">减{{$globalname}}（按固定值）</option>
								<option value="4">减{{$globalname}}（按比例）</option>
							</select> 
						</div>
					</div>
					<div class="form-group " id="beans">
						<label class="col-xs-3 control-label" style="margin-top:10px;">{{$globalname}}：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<div type="text" name="reward_bean" class="form-control input-sm">请选择方式</div>
						</div>
						
					</div>
					<div class="form-group "style="display:none;">
						<label class="col-xs-3 control-label" style="margin-top:10px;">奖励豆：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<input type="text" name="reward_bean" class="form-control input-sm" placeholder="请输入奖励豆" id="addbeans" value=""> 
						</div>
					</div>
					<div class="form-group "style="display:none;">
						<label class="col-xs-3 control-label" style="margin-top:10px;">扣除豆：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<input type="text" name="deduct_bean" class="form-control input-sm" placeholder="请输入扣除豆" id="jianbeans" value=""> 
						</div>
					</div>
					<div class="form-group " style="display:none;">
						<label class="col-xs-3 control-label" style="margin-top:10px;">规则积分：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<input type="text" name="point" class="form-control input-sm" placeholder="请输入积分,例如:+10或-10"> 
						</div>
					</div>
					<div class="form-group ">
						<label class="col-xs-3 control-label" style="margin-top:10px;">规则经验：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<input type="text" name="experience" class="form-control input-sm" placeholder="请输入经验,例如:+10或-10"> 
						</div>
					</div>
					<div class="form-group ">
						<label class="col-xs-3 control-label" style="margin-top:10px;">规则描述：</label>
						<div class="col-xs-8" style="margin-top:10px;">
							<textarea name="content" rows="3" cols="43"></textarea> 
						</div>
					</div>
					<input type="hidden" name="enabled" class="form-control input-sm" value="1"> 
					<!-- FormEnd -->
					<div class="modal-footer" style="text-align:center;">
						<button type="button" id="aniu" class="btn btn-small btn-success" onclick="window.parent.insert();" style="width:180px;float:left;margin-left:180px;margin-top:20px;display:none;">
							<i class="icon-ok"></i>确认
						</button>
						<button type="button" id="anius" class="btn btn-small btn-success" onclick="inserts();" style="width:180px;float:left;margin-left:180px;margin-top:20px">
							<i class="icon-ok"></i>确认
						</button>
					</div>
				</div>
				
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
				<div class="firstiput" style="display:none;">
					<div class="form-group ">
						<label class="col-xs-3 control-label">规则分类：</label>
						<div class="col-xs-8">
							<input type="text" id="classtitle" name="classtitle" class="form-control input-sm" placeholder="请先添加一项分类"> 
						</div>
					</div>
					<button type="button" class="btn btn-small btn-success" onclick="pointinput();" style="width:180px;float:left;margin-left:250px;margin-top:120px">
						<i class="icon-ok"></i>确认
					</button>
				</div>
			</form>
		</div>
<script type="text/javascript">
//一进页面就看分类有没有，有的话直接进入填写详细规则的区块，没有的话就先添加分类
$(function(){
       var pointc = $('#pointcount').val();
	   if(pointc > 0){
		   $('.allinput').css({display:'block'});
	   }else{
		   $('.firstiput').css({display:'block'});
	   }
});
//点击下拉框中不同的方式，则显示对应的内容
function choicebrand(contenttypeid){
	var fieldhtml = "";
	console.log(contenttypeid);
	if(contenttypeid == 1){
		fieldhtml += '<label class="col-xs-3 control-label" style="margin-top:10px;">加{{$globalname}}（固定值）：</label>';
		fieldhtml += '<div class="col-xs-8" style="margin-top:10px;">';
		fieldhtml += '<input type="text" name="reward_bean" class="form-control input-sm" placeholder="请输入奖励豆"><div style="color:red;">输入需要奖励的欢乐豆数，整数</div>'; 
		fieldhtml += '</div>';
	}else if(contenttypeid == 2){
		fieldhtml += '<label class="col-xs-3 control-label" style="margin-top:10px;">加{{$globalname}}（百分比）：</label>';
		fieldhtml += '<div class="col-xs-8" style="margin-top:10px;">';
		fieldhtml += '<input type="text" name="reward_bean" class="form-control input-sm" placeholder="请输入奖励豆"><div style="color:red;">%&nbsp;&nbsp;&nbsp;欢乐豆=当前欢乐豆+当前欢乐豆X 百分比</div>'; 
		fieldhtml += '</div>';
	}else if(contenttypeid == 3){
		fieldhtml += '<label class="col-xs-3 control-label" style="margin-top:10px;">减{{$globalname}}（固定值）：</label>';
		fieldhtml += '<div class="col-xs-8" style="margin-top:10px;">';
		fieldhtml += '<input type="text" name="deduct_bean" class="form-control input-sm" placeholder="请输入扣除豆"><div style="color:red;">输入需要奖励的欢乐豆数，整数</div>';
		fieldhtml += '</div>';
	}else{
		fieldhtml += '<label class="col-xs-3 control-label" style="margin-top:10px;">减{{$globalname}}（百分比）：</label>';
		fieldhtml += '<div class="col-xs-8" style="margin-top:10px;">';
		fieldhtml += '<input type="text" name="deduct_bean" class="form-control input-sm" placeholder="请输入扣除豆"><div style="color:red;">%&nbsp;&nbsp;&nbsp;欢乐豆=当前欢乐豆+当前欢乐豆X 百分比</div>';
		fieldhtml += '</div>';
	}
	
	$('#beans').empty();
	$('#beans').append(fieldhtml);
}
//因为下拉框显示的内容不同，所以走choicebrand方法，没有加载ajax，所以抓不到choicebrand方法下的字段。该方法（updates）是为了重新抓到字段在赋值，这样就可以抓到choicebrand下的字段。【需要点两次“确定”按钮，就这个方法整的】
function inserts(){
	var reward_bean =$("input[name^='reward_bean']").val();
	var deduct_bean =$("input[name^='deduct_bean']").val();
	if(reward_bean){
		$('#addbeans').attr('value',reward_bean);
	}
	if(deduct_bean){
		$('#jianbeans').attr('value',deduct_bean);
	}
	
	$('#aniu').show();
	$('#anius').hide();
}
//返回“再添加一个新分类”
function pointback(){
	$('.allinput').css({display:'none'});
	$('.firstiput').css({display:'block'});
}
//添加分类
function pointinput(){
	var classtitle =$("#classtitle").val();
	var token =$("input[name^='_token']").val();
	$.ajax({
		type:'post',
		url:'{{URL::route('admin.userpoint.pointinput')}}',
		data:{classtitle:classtitle,_token:token},
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
			var fieldhtml = "";
			if(data.classtitle.length){
				for(var i =0;i<data.classtitle.length;i++){
					fieldhtml += '<option value="'+data.classtitle[i]['classid']+'">'+data.classtitle[i]['classtitle']+'</option>';
				}
			}else{
				fieldhtml += '<option value="0">无</option>';
			}
			
			$('#selectchoose').empty();
			$('#selectchoose').append(fieldhtml);
			
			$('.allinput').css({display:'block'});
			$('.firstiput').css({display:'none'});
		}
		},
		error:function(data){
			layer.msg('网络通信错误', {icon: 2});  //1是勾，2是X，3是？，4琐，5不开心笑脸，6笑脸表情
			layer.closeAll('iframe');
			//tableApp.load();
		}
	});
}
</script>
    </body>
</html>



