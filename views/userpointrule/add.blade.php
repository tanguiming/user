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
                    <div class="form-group">
                        <label class="col-xs-3 control-label">赚币规则名称</label>
                        <div class="col-xs-8">
                            <input type="text" name="name" class="form-control input-sm"> 
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-xs-3 control-label">赚币名称描述</label>
                        <div class="col-xs-8">
                            <textarea rows="5" cols="43" value="" placeholder="请填写赚币名称描述例如:用户登陆" id="content" name="content"></textarea> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">获得币种</label>
                        <div class="col-xs-8">
                            <input type="text" name="currency" class="form-control input-sm"> 
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-xs-3 control-label">经验值</label>
                        <div class="col-xs-8">
                            <input type="text" name="value" class="form-control input-sm"> 
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>

        </div>

    </body>
</html>



