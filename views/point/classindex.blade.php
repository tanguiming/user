<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>分类修改</title>

        <!--        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.mind797.css?v=3.4.0" rel="stylesheet">-->
        <!-- 新 Bootstrap 核心 CSS 文件 -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- 可选的Bootstrap主题文件（一般不用引入） -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

        <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!-- layerDate plugin javascript -->
        <script src="{{ASSET_URL}}system/hplus/js/plugins/layer/laydate/laydate.js"></script>

        <style>
        /*标题样式*/
        .fromTitle{
			background-color:#F5F5F5;font-size:16px;padding:5px 0px 5px 12px;

        }
        .modal-body{padding: 5px 15px 15px 15px;border:2px solid #ededed;border-radius: 5px;}

        </style>

    </head>
	<body>
<div class="modal-content animated bounceInRight">
    <div class="modal-body">
		<form id="wt-forms" class="form-horizontal" method="post" tabindex="-1" onsubmit="return false;">
			<!-- FormStart -->
			<div class="modal-body overflow-visible" id="self">
				<div class="row-fluid">
					<div class="span12">
						<div class="form-group" id="select_1">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:120px;margin-top:7px;">分类名称：</label>
							<div class="col-xs-8">
								<input type="text" class="form-control" style="width:200px;" id="form-field-3" placeholder="请输入分类名称" value="{{$classtitle}}" name="classtitle" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="classid" value="{{$classid}}">
			<!-- FormEnd -->
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-small btn-success" onclick="window.parent.classupdate();" style="width:180px;float:left;margin-left:100px;">
					<i class="icon-ok"></i>确认
				</button>
			</div>

		</form>
	</div>

</div>
<!--modal表单弹出框-->
<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript" src="{{ASSET_URL}}system/admin/plupload/js/plupload.full.min.js"></script>

</body>
</html>