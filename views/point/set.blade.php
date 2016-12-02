<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>全局配置</title>

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
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">虚拟币名称：</label>
							<div class="col-xs-8">
								<input type="hidden" value="{{$coinid}}" name="coinid" />
								<input type="text" class="form-control" style="width:200px;" id="form-field-3" placeholder="请输入虚拟币名称" value="{{$name}}" name="name" />
							</div>
						</div>
						<div class="form-group" id="select_2">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">图标：</label>
							<div class="col-xs-8" >
								<input type="text" name="picurl" value="{{$picurl}}" class="form-control" style="width: 432px;float:left;margin-right:7px;" id="thumb" placeholder="请输入图标"/><a href="javascript:;" class="btn btn-sm btn-primary" id="pickbut" onclick="ownup('pickbut','thumb');">上传</a>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">用户等级方式：</label>
							<div class="col-xs-8">
								<label><input type="radio" name="coinway" value="1" @if($coinway == 1)checked="checked" @endif/>按积分范围控制等级</label>
								<label><input type="radio" name="coinway" value="2" @if($coinway == 2)checked="checked" @endif>按经验值范围控制等级</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<!-- FormEnd -->
			<div class="modal-footer" style="text-align:center;">
				<button type="button" class="btn btn-small btn-success" onclick="window.parent.setupdate();" style="width:180px;float:left;margin-left:280px;">
					<i class="icon-ok"></i>确认
				</button>
			</div>

		</form>
	</div>

</div>
<!--浏览图片的弹出框-->
<div style="display:none;" id="picdiv">
	<image id="picurl" src="http://video-js.zencoder.com/oceans-clip.png" type='image/jpg' style="width:35%;"/>
</div>
<!--modal表单弹出框-->
<script src="{{ASSET_URL}}system/hplus/js/laydate/laydate.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
<script src="{{ASSET_URL}}system/admin/js/date-time/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="{{ASSET_URL}}system/admin/plupload/js/plupload.full.min.js"></script>

<script type="text/javascript">


//自定义方法，上传处理
function ownup(pickbut,textinp){
	var token = $("input[name='_token']").val();
    var uploader = new plupload.Uploader({
        // General settings
        runtimes : 'silverlight,html4',
        browse_button : pickbut, // you can pass in id...
        url : "{{URL::route('admin.userpoint.upload')}}?_token="+token,
        chunk_size : '1mb',
        unique_names : true,
 
        // Resize images on client-side if we can
//         resize : { width : 320, height : 240, quality : 90 },

        filters : {
            max_file_size : '10mb',

            // Specify what files to browse for
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png,mp3"},
                {title : "Zip files", extensions : "zip"}
            ]
        },
 
        flash_swf_url : '{{ASSET_URL}}admin/plupload/js/Moxie.swf',
        silverlight_xap_url : '{{ASSET_URL}}admin/plupload/js/Moxie.xap',
         
        // PreInit events, bound before the internal events
        preinit : {
            Init: function(up, info) {
                log('[Init]', 'Info:', info, 'Features:', up.features);
            },
 
            UploadFile: function(up, file) {
                log('[UploadFile]', file);
 
                // You can override settings before the file is uploaded
                // up.setOption('url', 'upload.php?id=' + file.id);
                // up.setOption('multipart_params', {param1 : 'value1', param2 : 'value2'});
            }
        },
 
        // Post init events, bound after the internal events
        init : {
            PostInit: function() {
                // Called after initialization is finished and internal event handlers bound
                log('[PostInit]');
                document.getElementById(pickbut).onclick = function() {
                                       
                    uploader.start();
                    return false;
                };
            },

            Browse: function(up) {
                // Called when file picker is clicked
                log('[Browse]');
            },

            Refresh: function(up) {
                // Called when the position or dimensions of the picker change
                log('[Refresh]');
            },
 
            StateChanged: function(up) {
                // Called when the state of the queue is changed
                log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
            },
 
            QueueChanged: function(up) {
                // Called when queue is changed by adding or removing files
                log('[QueueChanged]');
            },

            OptionChanged: function(up, name, value, oldValue) {
                // Called when one of the configuration options is changed
                log('[OptionChanged]', 'Option Name: ', name, 'Value: ', value, 'Old Value: ', oldValue);
            },

            BeforeUpload: function(up, file) {
                // Called right before the upload for a given file starts, can be used to cancel it if required
                log('[BeforeUpload]', 'File: ', file);
            },
 
            UploadProgress: function(up, file) {
                // Called while file is being uploaded
                log('[UploadProgress]', 'File:', file, "Total:", up.total);
            },

            FileFiltered: function(up, file) {
                // Called when file successfully files all the filters
                log('[FileFiltered]', 'File:', file);
            },
 
            FilesAdded: function(up, files) {
    
                // Called when files are added to queue
                log('[FilesAdded]');
                               uploader.start();
                plupload.each(files, function(file) {
                    log('  File:', file);
                });
            },
 
            FilesRemoved: function(up, files) {
                // Called when files are removed from queue
                log('[FilesRemoved]');
 
                plupload.each(files, function(file) {
                    log('  File:', file);
                });
            },
 
            FileUploaded: function(up, file, info) {
                                
                // Called when file has finished uploading
                log('[FileUploaded] File:', file, "Info:", info);
                                //获取后缀类型
                                var type = file.name.substr(file.name.lastIndexOf('.'));
//                                var echoimg = document.getElementById('myimg');
                                var myimginp = document.getElementById(textinp);
                                var imgsrc = "user/grade/{{date('y-m-d',time())}}/"+file.id+type;
								//var imgsrc = "user/grade/"+file.id+type;
                                myimginp.value = imgsrc;
//                                echoimg.innerHTML = "<img style='float:left;margin-right:5px;height:80px;width:60px;height:60px;' src="+imgsrc + ">";
            },
 
            ChunkUploaded: function(up, file, info) {
                // Called when file chunk has finished uploading
                log('[ChunkUploaded] File:', file, "Info:", info);
            },

            UploadComplete: function(up, files) {
                // Called when all files are either uploaded or failed
                log('[UploadComplete]');
            },

            Destroy: function(up) {
                // Called when uploader is destroyed
                log('[Destroy] ');
            },
 
            Error: function(up, args) {
                // Called when error occurs
                log('[Error] ', args);
            }
        }
    }); 
    function log() {
        var str = "";
 
        plupload.each(arguments, function(arg) {
            var row = "";
 
            if (typeof(arg) != "string") {
                plupload.each(arg, function(value, key) {
                    // Convert items in File objects to human readable form
                    if (arg instanceof plupload.File) {
                        // Convert status to human readable
                        switch (value) {
                            case plupload.QUEUED:
                                value = 'QUEUED';
                                break;
 
                            case plupload.UPLOADING:
                                value = 'UPLOADING';
                                break;
 
                            case plupload.FAILED:
                                value = 'FAILED';
                                break;
 
                            case plupload.DONE:
                                value = 'DONE';
                                break;
                        }
                    }
 
                    if (typeof(value) != "function") {
                        row += (row ? ', ' : '') + key + '=' + value;
                    }
                });
 
                str += row + " ";
            } else {
                str += arg + " ";
            }
        });
//        var log = document.getElementById('console');
//        log.innerHTML += str + "\n";
    }

    uploader.init(); 
}
     
</script>
</body>
</html>