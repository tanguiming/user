<link rel="stylesheet" href="{{ASSET_URL}}system/admin/css/jquery-ui-1.10.3.full.min.css" />
<link href="{{ASSET_URL}}system/hplus/css/font-awesome.mine0a5.css?v=4.3.0" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/animate.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ASSET_URL}}system/admin/js/date-time/bootstrap-datetimepicker.css" />
<link href="{{ASSET_URL}}system/hplus/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="{{ASSET_URL}}system/hplus/css/style.min2513.css?v=3.0.0" rel="stylesheet">

<div class="modal-content animated bounceInRight">
	<div class="modal-body">
		<div class="main-container container-fluid">
			<div class="page-content">
				<div class="row-fluid">
					<div class="span12">
						<form id="wt-forms">
							<!--PAGE CONTENT BEGINS-->                  
							<div class="control-group" style="display: none;">
								<label class="control-label" for="form-field-1">id：</label>

								<div class="controls">
									<input type="text" data-rel="tooltip" title="请填写标题" name ="id" value="{{$id}}" id="form-field-1" placeholder="" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-field-1">部门：</label>

								<div class="controls">
									<input type="text" data-rel="tooltip" title="请填写部门名称" name ="name" value="{{$name}}" id="form-field-1" placeholder="" />
								</div>
							</div>
							<div class="control-group">

								<label for="form-field-select-1" class="control-label">父栏目:</label>
								<div class="controls">
									<select id="form-field-select-1" name="parentid">
										<option value="" style="width:120px;">--请选择上级部门--</option>
										@foreach ($pids as $k => $v)
											@if(!empty($v))
											<option <?php echo $parentid == $k ? "selected = \"selected\" " : ""; ?>  value="{{$k}}">{{$v}}</option>
											@endif
										@endforeach
									</select>

								</div>
							</div>
						   <div class="control-group">
								<label class="control-label" for="form-field-1">排序：</label>

								<div class="controls">
									<input type="text" data-rel="tooltip" title="请填写排序" name ="sort" value="{{$sort}}" id="form-field-1" placeholder="" />
								</div>
							</div>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</form>
					</div><!--/.span-->
				</div><!--/.row-fluid-->
			</div><!--/.page-content-->

		</div><!--/.main-container-->
<br/>
		<div class="modal-footer">
			<button type="submit" class="btn btn-small btn-primary" onclick="window.parent.update({{$id}});">
				<i class="icon-ok"></i>确认
			</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button data-dismiss="modal" class="btn btn-danger btn-small tooltip-error" onclick="window.parent.datadel({{$id}});">
				<i class="icon-remove"></i>
				删除
			</button>
		</div>
	</div>
</div>


