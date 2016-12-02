<link href="{{ASSET_URL}}system/hplus/css/bootstrap.mind797.css?v=3.4.0" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/plugins/chosen/chosen.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/plugins/switchery/switchery.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet">


<style>
    .form-group{margin-bottom: 5px;}
    #tijiao_id{width: 100px;height: 30px;line-height: 30px; float:right;margin-top: 20px;padding: 0px 15px 15px 15px;}
</style>
<div >
    <!-- <div class="modal-content animated bounceInRight"> -->
    <div class="modal-body">
        <form id="wt-forms-edit"  action="javascript:">

            <div class="span12">
                <div class="col-md-12">
                    <div id="user-main-box" class="show">
                        <div class="form-group">
                            <div class="form-group"><label>用户：</label> <input type="text" name="username" value="{{$username}}"  placeholder="用户名"  disabled></div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>手机：</label> <input type="text" name="mobile" value="{{$detail['mobile']}}" placeholder="手机" ></div>

                        </div>
						
						<div class="form-group">
							<label >是否推送</label>
							@if(!empty($bm_id))
								<div class="radio radio-success radio-inline">
	                                <input type="radio" id="push_type" value="1"  name="push_type"checked >
	                                <label for="push_type">推送 </label>
	                            </div>
								 <div class="radio radio-success radio-inline">
	                                <input type="radio" id="push_type2" value="0" name="push_type"  >
	                                <label for="input_source_type2">不推送</label>
	                            </div>
								@else
								<div class="radio radio-success radio-inline">
	                                <input type="radio" id="push_type" value="1"  name="push_type">
	                                <label for="push_type">推送 </label>
	                            </div>
								<div class="radio radio-success radio-inline">
	                                <input type="radio" id="push_type2" value="0" name="push_type"  checked>
	                                <label for="input_source_type2">不推送</label>
	                            </div>
							@endif
	                           
							
						</div>
						
						
						<div class="form-group">
							<label >选择部门：</label>
							
								<select  name="bm_id" id="bm_id">
								
								@if(!empty($category) && !empty($bm_id))
									<?php foreach($category as $k=>$v)
										
										if($bm_id == $v['id']){
											echo "<option value=".$v['id']."  selected='selected'>".$v['department']."</option>";
										}else{
											
										    echo "<option value=".$v['id'].">".$v['department']."</option>";
										}
									
										
									?>
								@else
									<option value=''> </option>
									<?php foreach($category as $k=>$v)
									 echo "<option value=".$v['id'].">".$v['department']."</option>";									
								?>
								@endif
								</select>
						</div>
						
						
						
						
                        <div class="form-group"><label>是否启用</label> <input type="checkbox" name ="status"  id="status"  value="1" class="js-switch1" @if ($status == 1) checked="checked" @endif/>&nbsp;&nbsp;&nbsp;<label>是否为管理员</label> <input type="checkbox" name ="system"  id="system"  value="1" class="js-switch2" @if($system == 1) checked="checked" @endif /></div>
                        <div class="form-group">
                            <label >选择角色</label>
                            <select data-placeholder="选择该用户所属角色..." name="role[]" class="chosen-select" multiple style="width:85%;" tabindex="4">
                                @if(!empty($allRole)) 
									@foreach($allRole as $role)
										@if(!empty($roles) && in_array($role['role_id'], $roles))
											<option value="{{$role['role_id']}}" selected="selected">{{$role['name']}}</option>
										@else
											<option value="{{$role['role_id']}}">{{$role['name']}}</option>
										@endif

									@endforeach
                                @endif
                            </select>
                        </div>

                        <div class="row-fluid">
                            <div class="span8 offset4">
                                <buttont type="button" class="btn btn-primary btn-block m-t" onclick="toggleShow('user-detail-box', 'user-main-box')">
                                    <i class="fa fa-arrow-down"></i>
                                    填写更多信息
                                    </button>
                            </div>
                        </div>

                    </div>
                    <div id="user-detail-box" class="hide">
                        <div class="form-group">
                            <div class="form-group" ><label for="head_picture">头像：</label> 
                                <input type="text" name="head_picture" value="{{$detail['head_picture']}}" class="inp" style="width: 200px;" placeholder="180*200" id="pic_url"/>
                                <a href="javascript:;"   class="btn btn-small btn-primary" style="margin-top: -10px;"  id="pickbut1" onclick="ownup1('pickbut1', 'upbut1', 'pic_url');">上传</a>
                                <a id="upbut1" href="javascript:;"></a>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>姓名：</label> <input type="text" name="name" value="{{$detail['name']}}" placeholder="姓名" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="form-group"><label>性别：</label> <input type="radio" name ="sex" id="sex"  value="1" @if($detail['sex'] == 1)checked="checked"@endif/>男&nbsp;&nbsp;&nbsp;
                                                                          <input type="radio" name ="sex" id="sex"  value="2" @if($detail['sex'] == 2)checked="checked"@endif/>女</div>

                        <div class="form-group">
                            <div class="form-group"><label>生日：</label> <input type="text" name="birthday" value="{{$detail['birthday']}}" placeholder="9999/99/99" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>座机：</label> <input type="text" name="telephone" value="{{$detail['telephone']}}" placeholder="座机" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>邮箱：</label> <input type="text" name="email" value="{{$email}}" placeholder="email" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>地址：</label> <input type="text" name="address" value="{{$detail['address']}}" placeholder="地址" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>邮编：</label> <input type="text" name="zipcode" value="{{$detail['zipcode']}}" placeholder="邮编" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="form-group">
                            <div class="form-group"><label>QQ：</label> <input type="text" name="qq" value="{{$detail['qq']}}" placeholder="QQ" style="height: 30px;color: inherit;width:250px;"></div>

                        </div>

                        <div class="row-fluid">
                            <div class="span8 offset4">
                                <buttont type="button" class="btn btn-primary btn-block m-t" onclick="toggleShow('user-main-box', 'user-detail-box')">
                                    <i class="fa fa-arrow-down"></i>
                                    返回上一页
                                    </button>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="username" value="{{$username}}" />
                    <input type="hidden" name="user_id" value="{{$user_id}}" />
                    <input type="hidden" name="old_email" value="{{$email}}" />
                </div>

            </div>
        </form>
    </div>

</div>
<!-- 全局js -->
<script src="{{ASSET_URL}}system/hplus/js/jquery-2.1.1.min.js"></script>
<script src="{{ASSET_URL}}system/hplus/js/bootstrap.mind797.js?v=3.4.0"></script>

<!-- 自定义js -->
<script src="{{ASSET_URL}}system/hplus/js/content.mine209.js?v=1.0.0"></script>

<!-- Chosen -->
<script src="{{ASSET_URL}}system/hplus/js/plugins/chosen/chosen.jquery.js"></script>

<!-- Data picker -->
<script src="{{ASSET_URL}}system/hplus/js/plugins/datapicker/bootstrap-datepicker.js"></script>


<!-- Switchery -->
<script src="{{ASSET_URL}}system/hplus/js/plugins/switchery/switchery.js"></script>

<!-- Image cropper -->
<script src="{{ASSET_URL}}system/hplus/js/plugins/cropper/cropper.min.js"></script>


<script type="text/javascript" src="{{ASSET_URL}}system/admin/plupload/js/plupload.full.min.js"></script>
<script>
                                          function toggleShow(showId, hideId)
                                          {
                                              $('#' + showId).removeClass('hide').addClass('show');
                                              $('#' + hideId).removeClass('show').addClass('hide');
                                          }

                                          $(document).ready(function() {
                                              var a = document.querySelector(".js-switch1");
                                              var b = document.querySelector(".js-switch2");
                                              var c = new Switchery(a, {color: "#1AB394"});
                                              var d = new Switchery(b, {color: "#1AB394"});
                                          });
                                          var config = {
                                              ".chosen-select": {},
                                              ".chosen-select-deselect": {allow_single_deselect: true},
                                              ".chosen-select-no-single": {disable_search_threshold: 10},
                                              ".chosen-select-no-results": {no_results_text: "Oops, nothing found!"},
                                              ".chosen-select-width": {width: "95%"}
                                          };
                                          for (var selector in config) {
                                              $(selector).chosen(config[selector])
                                          }

</script>

<script type="text/javascript">
//       上传处理
    //这里是做一个标识变量，用来不上其多次弹出，或者说实例化
    var kk = 1;
    function ownup1(pickbut, upbut, textinp) {
        if (kk == 1) {
            ownup(pickbut, upbut, textinp);
            kk = 2;
        } else {
            return;
        }
    }
//     自定义方法，上传处理
    function ownup(pickbut, upbut, textinp) {
		var token =$("input[name^='_token']").val();
        var uploader = new plupload.Uploader({
                    // General settings
                    runtimes: 'silverlight,html4',
            browse_button: pickbut, // you can pass in id...
                    url: "{{URL::route('admin.user.usergrade.upload')}}?_token="+token,
                    chunk_size: '1mb',
                    unique_names: true,
 
                    // Resize images on client-side if we can
                    resize: {width: 320, height: 240, quality: 90},
        
                    filters: {
                            max_file_size: '1mb',
                // Specify what files to browse for
                            mime_types: [
                                    {title: "Image files", extensions: "gif,png,jpeg,jpg"},
                            ]
                    },
 
            flash_swf_url: '{{ASSET_URL}}admin/plupload/js/Moxie.swf',
            silverlight_xap_url: '{{ASSET_URL}}admin/plupload/js/Moxie.xap',
         
                    // PreInit events, bound before the internal events
                    preinit: {
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
                    init: {
                PostInit: function() {
                    // Called after initialization is finished and internal event handlers bound
                    log('[PostInit]');
                    document.getElementById(upbut).onclick = function() {

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
                    var imgsrc = "upload/user/grade/{{date('y-m-d',time())}}/" + file.id + type;
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
 
                            if (typeof (arg) != "string") {
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
 
                                            if (typeof (value) != "function") {
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


