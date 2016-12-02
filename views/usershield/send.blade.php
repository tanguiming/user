<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>Laravel</title>

        <!--        <link href="{{ASSET_URL}}system/hplus/css/bootstrap.mind797.css?v=3.4.0" rel="stylesheet">-->
        <!-- 新 Bootstrap 核心 CSS 文件 -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- 可选的Bootstrap主题文件（一般不用引入） -->
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

        <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div >
            <!-- <div class="modal-content animated bounceInRight"> -->
            <div class="modal-body">
				
                <form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
					@if($yemian)
						该用户还处于屏蔽期！
					@else
						<div class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:21%;">积分规则：</label>
							<div class="col-xs-8" >
								<input type="text" name="integral" class="form-control" style="width:80%;" placeholder="请输入积分规则：是拼音格式的"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:21%;">屏蔽时间：</label>
							<div class="col-xs-8" >
								<select name="periodtime" class="form-control" style="width: 110%;">
									<option value="0">不屏蔽</option>
									<option value="1">一天</option>
									<option value="2">一周</option>
									<option value="3">一月</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:21%;">描述：</label>
							<div class="col-xs-8" >
								<textarea class="form-control" cols="38" rows="3" name="describe" placeholder="请输入描述"/></textarea>
							</div>
						</div>
					@endif
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="huodongopenid" value="{{$huodongopenid}}">
					<input type="hidden" name="huodongtoken" value="{{$huodongtoken}}">
					
                </form>
				
            </div>

        </div>

    </body>
</html>



