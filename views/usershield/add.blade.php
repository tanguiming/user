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
					<div class="form-group" id="select_2">
						<label class="col-xs-3 control-label" for="form-field-1" style="width:25%;">限制屏蔽时间：</label>

						<div class="col-xs-8" style="margin-top:5px;">
							<div class="am-form-group">
								<input class="laydate-icon" id="start" name="periodtimestart" readonly="readonly" style="width:60%;" placeholder="请输入开始屏蔽时间"/>
								<br/><br/>
								<input class="laydate-icon" id="end"  name="periodtimend" readonly="readonly" style="width:60%;" placeholder="请输入结束屏蔽时间"/>
							</div>
						</div>
					</div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>

        </div>
		<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/laydate/laydate.js"></script>
		<script type="text/javascript">
			var start = {
				elem: '#start',
				format: 'YYYY/MM/DD hh:mm:ss',
				min: laydate.now(), //设定最小日期为当前日期
				max: '2099-06-16 23:59:59', //最大日期
				istime: true,
				istoday: false,
				choose: function(datas){
					 end.min = datas; //开始日选好后，重置结束日的最小日期
					 end.start = datas //将结束日的初始值设定为开始日
				}
			};
			var end = {
				elem: '#end',
				format: 'YYYY/MM/DD hh:mm:ss',
				min: laydate.now(),
				max: '2099-06-16 23:59:59',
				istime: true,
				istoday: false,
				choose: function(datas){
					start.max = datas; //结束日选好后，重置开始日的最大日期
				}
			};
			laydate(start);
			laydate(end);

		</script>

    </body>
	
</html>



