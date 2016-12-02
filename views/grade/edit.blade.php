<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>等级</title>
        
		<link rel="stylesheet" href="{{ASSET_URL}}user/color/css/colpick.css">
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
        
        
		#picker {

			margin:0;
		
			padding:0;
		
			border:1px solid pink;
		
			width:70px;
		
			height:20px;
		
			border-right:20px solid green;
		
			line-height:20px;
		
		}
        </style>

    </head>
	<body>
<div class="modal-content animated bounceInRight">
    <div class="modal-body">
		<form id="wt-forms-edit" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
			<!-- FormStart -->
			<div class="modal-body overflow-visible" id="self">
				<div class="row-fluid">
					<div class="span12">
						<div class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">级别名称：</label>
							<div class="col-xs-8">
								<input type="text" class="form-control" id="form-field-1" style="width:200px;float:left;" placeholder="请输入级别名称" value="{{$data['title']}}" name="title" />
								<div style="width:100px;height:20px;float:left;margin-top:8px;margin-left:20px;">
									<input type="text" id="picker" name="colorvalue" value="{{$data['colorvalue']}}"></input>
								</div>
							</div>
						</div>
						<div id="title" class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">等级：</label>
							<div class="col-xs-8" >
								<input type="number" name="grade" value="{{$data['grade']}}" class="form-control" style="width: 200px;" placeholder="请输入等级"/>
							</div>
						</div>
						<div class="form-group" id="select_2">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">范围：</label>
							<div class="col-xs-8" style="margin-top:5px;float:left;">
								<div class="am-form-group">
									<input class="form-control" name="lower" value="{{$data['lower']}}" style="width:28%;float:left;" placeholder="请输入最低值"/>
									<div style="float:left;margin-top: 8px;">&nbsp;&nbsp;至&nbsp;&nbsp;</div>
									<input class="form-control" name="toplimit" value="{{$data['toplimit']}}" style="width:28%;float:left;" placeholder="请输入最高值"/>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-xs-3 control-label" for="form-field-1" style="width:15%;">图标：</label>
							<div class="col-xs-8" >
								<input type="text" name="icon" value="{{$data['icon']}}" class="form-control" style="width: 350px;float:left;margin-right:7px;" id="thumb" placeholder="请输入商品图片【请控制在100K】"/><a href="javascript:;" class="btn btn-sm btn-primary" id="pickbut1" onclick="ownup('pickbut1','thumb');">上传</a>
							</div>
						</div>
						<div>
							@if($data['icon'])
								<img src="{{UPLOAD_URL}}{{$data['icon']}}" class="picupload" style="width:50px;height:50px;margin-left:125px">
							@else
								<img src="" class="picupload" style="display:none;width:50px;height:50px;margin-left:125px">
							@endif
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="id" value="{{$data['id']}}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		</form>
	</div>

</div>

<!--modal表单弹出框-->
<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript" src="{{ASSET_URL}}system/admin/plupload/js/plupload.full.min.js"></script>
<script src="{{ASSET_URL}}user/color/js/colpick.js"></script>

<script type="text/javascript">
$('#picker').colpick({

	layout:'hex',

	submit:0,

	colorScheme:'dark',

	onChange:function(hsb,hex,rgb,el,bySetColor) {
		
		console.log(hsb,1);
		console.log(hex,'这是颜色值');
		console.log(rgb,3);
		console.log(el,4);
		console.log(bySetColor,5);
		
		$('#picker').attr('value',hex);
		
		$(el).css('border-color','#'+hex);

		// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.

		if(!bySetColor) $(el).val(hex);

	}

}).keyup(function(){

	$(this).colpickSetColor(this.value);

});

//自定义方法，上传处理
function ownup(pickbut,textinp){
	var token = $("input[name='_token']").val();
    var uploader = new plupload.Uploader({
        // General settings
        runtimes : 'silverlight,html4',
        browse_button : pickbut, // you can pass in id...
        url : "{{URL::route('admin.user.usergrade.upload')}}?_token="+token,
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
                                myimginp.value = imgsrc;
                                console.log(imgsrc,1111);
                                
                                var picurl= "{{UPLOAD_URL}}"+imgsrc;
								$('.picupload').attr("src",picurl);
								$('.picupload').css({display:"block"});
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