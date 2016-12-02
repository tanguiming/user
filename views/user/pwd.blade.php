<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/jquery-ui-1.10.3.full.min.css" />
<link href="{{ASSET_URL}}system/admin/css/bootstrap.min.css" rel="stylesheet" />
<link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">

<link href="{{ASSET_URL}}system/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet"><!--该样式控制颜色-->
<link href="{{ASSET_URL}}system/admin/css/bootstrap-responsive.min.css" rel="stylesheet" /><!-- 按钮的排版样式 -->

<div class="modal-content animated bounceInRight">
    <div class="modal-body">
		<form id="wt-forms">
			<div class="form-group">
				<div style="height:30px;">
					<label class="col-sm-2 control-label">新密码</label>
				</div>
				<div class="col-sm-4 col-sm-offset-2" >
					<input type="hidden" name="user_id" value="{{$user_id}}" />
					<input style="height:30px;" type="password" required="required" id="password" name="password" placeholder="新的密码" maxlength="20"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-4 col-sm-offset-2">
					<button class="btn btn-white" type="submit" onclick="window.parent.dopwd();">保存内容</button>
					<button class="btn btn-white" type="submit">取消</button>
				</div>
			</div>
			
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			
		</form>
	</div>
</div>
<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
