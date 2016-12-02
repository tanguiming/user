<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>导入</title>
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ASSET_URL}}system/admin/bootstrap/css/bootstrap-theme.min.css">
        <script src="{{ASSET_URL}}system/admin/bootstrap/js/jquery.min.js"></script>
        <script src="{{ASSET_URL}}system/admin/bootstrap/js/bootstrap.min.js"></script>
        <!-- layer javascript -->
        <script src="{{ASSET_URL}}system/hplus/js/plugins/layer/laydate/laydate.js"></script>
		<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
		<!-- WebIcon图标 -->
		<link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css" rel="stylesheet">
		<!-- 上传插件 -->
		<script type="text/javascript" src="{{ASSET_URL}}system/admin/plupload/js/plupload.full.min.js"></script>
		
        <script>
			laydate.skin('molv');
        </script>

		</head>
    <body>
		<div class="col-lg-12">
            <div class="modal-body">
                <form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
					<div class="form-group">
                        <label class="col-xs-3 control-label">上传文件</label>
                        <div class="col-xs-7">
							<input type="text" name="thumb" value="" class="form-control" style="width: 200px;float:left;margin-right:7px;" id="thumb" placeholder=""/><a href="javascript:;" class="btn btn-small btn-primary" id="uploader">上传</a>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                </form>
            </div>
        </div>
        <script type="text/javascript">
        $('document').ready(function(){
            var uploader = new plupload.Uploader({
                browse_button:'uploader',//触发选择文件的元素
                url:"{{URL::route('admin.core.user.aca.import_upload')}}?_token={{csrf_token()}}",//向服务器提交的URL
                flash_swf_url : '{{ASSET_URL}}system/admin/plupload/js/Moxie.swf',//插件文件
                silverlight_xap_url : '{{ASSET_URL}}system/admin/plupload/js/Moxie.xap',//插件文件
                filters:{//过滤
                    // mime_types:[{'title':'image','extensions':'jpg,png,jepg,gif,bmp'}],//[{'title':'XXX','extensions':'jpg,png'},{'title':'','extensions':''}]
                    //max_file_size:'10mb',
                    //prevent_duplicates : false//是否允许重复选择文件 false:允许
                },
                multi_selection:false,//是否支持多选
                unique_names:true,//唯一的文件名
            });
            uploader.init();
            uploader.bind('FilesAdded',function(uploader,file){//绑定一些事件,通过这些和插件进行交互
                //console.log(file[0]);
                uploader.start();
            });
            uploader.bind('FileUploaded',function(uploader,file,res){
                console.log(res.response);
                $('#thumb').val(res.response);
            });
            uploader.bind('Error',function(uploader,errorObject){//绑定一些事件,通过这些和插件进行交互
                console.log(errorObject.message);
            });
        });
        </script>
    </body>
</html>