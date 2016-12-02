<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>积分操作</title>

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
        <script src="{{ASSET_URL}}system/admin/js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="{{ASSET_URL}}system/admin/plupload/js/plupload.full.min.js"></script>
        <script>
laydate.skin('molv');
        </script>
    </head>
    <body>
        <div >
            <!-- <div class="modal-content animated bounceInRight"> -->
            <div class="modal-body">
                <form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">

                    <div class="form-group">
                        <label class="col-xs-3 control-label">操作对象</label>
                        <div class="col-xs-9" >
                            <label class="radio-inline">
                                <input type="radio" name="type"  value="1"  checked>单个用户操作   
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type"  value="2" >批量操作
                            </label>
                        </div>
                    </div>
                    <br>
                    <div id="singl">
                        <div class="form-group">
                            <label class="col-xs-3 control-label">用户ID</label>
                            <div class="col-xs-5">
                                <input type="text" id="id" name="id" class="form-control input-sm" onblur="search();">
                            </div><span style="font-size:10px;" id="jifen"></span>
                        </div><br>
                    </div>
                    

                    <div id="batch" style="display:none;">
                        <div class="form-group">
                            <label class="col-xs-3 control-label">选择等级</label>
                            <div class="col-xs-5">
                                <select  class="chosen-select" name="grade" data-placeholder="请选择等级" id="one" style="width:250px;height:30px;">
                                @foreach($res3 as $k => $v)
                                    <option value="{{$v['id']}}">{{$v['title']}}</option>
                                @endforeach
                                </select>                     
                            </div>                           
                        </div>
                        <br>                       
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">选择规则</label>
                        <div class="col-xs-5">
                            <select  class="chosen-select" name="guize" data-placeholder="请选择规则" id="one" style="width:250px;height:30px;">
                            @foreach($res1 as $k => $v)
                                <option value="" disabled="disabled">{{$v['classtitle']}}</option>
                                @foreach($res2[$k] as $key => $value)
                                    <option value="{{$value['pinyin']}}">--{{$value['name']}}</option>
                                @endforeach
                            @endforeach
                            </select>                     
                        </div>                           
                    </div>
                    <br>  
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-3">
                            <span class="pull-right">
                                <button class="btn btn-primary" type="button" onclick="insert();">
                                    添加
                                </button>
                                <button class="btn btn-default" type="button" onclick="cancel();">
                                    &nbsp;取消
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </body>
    <script type="text/javascript" src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
    
    <script type="text/javascript">

    $(function(){
        $("input[type='radio']").click(function(){

            var type = $(this).val();

            if(type == 1){
                $('#singl').show();
                $('#batch').hide();
            }else{
                $('#singl').hide();
                $('#batch').show();
            }
            
        });
    });

    // 查询用户积分
    function search(){
        var id = $('#id').val();

        if(id != ''){
            $.get("{{URL::route('admin.user.pointlogs.search')}}",{id:id},function(data){
               $('#jifen').text('当前积分：'+data);
            });
        }
    }
    // 添加数据
    function insert(){

        var type = $("input[type='radio']:checked").val();

        if(type == 1){

            var id = $('#id').val();

            if(id == "") {
                layer.msg('请输入用户积分', {time: 800});  //另一种提醒方式
                $('#id').focus();
                    return false;
            }
        }           

        var obj = $('#wt-forms');
                        
        var index = window.parent.insert(obj);
        
    }
        
    //取消按钮
    function cancel(){
        var index = window.parent.back();
    }
    </script>
</html>

