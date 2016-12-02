<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>积分管理</title>
<style>
	.total{
		padding:20px;
		}
	.nav_top button{
		width:100px; 
		height:36px; 
		background-color:#FFF; 
		border:1px solid #CCC; 
		border-radius:6px; 
		cursor:pointer;/*鼠标经过变小手*/
		}
	.nav_top button:hover{background-color:rgba(153, 204, 102, 1); color:#FFF; border:none;}
	.list{
		margin-top:20px;
		}
	.beans,.experience{
		float:left; 
		background-color:#f1f1f1;
		padding:10px;
		border-radius:6px;
		}
	.experience{
		margin-left:20px;}
	.content .con{display:none;}
	.content .con:first-child{display:block;}
	.zonge{border:1px solid black;width:350px;height:40px;line-height:40px;text-align:center;font-weight:bold;font-size:20px;}
	.zonge2{border:1px solid black;width:350px;height:40px;line-height:40px;text-align:center;font-weight:bold;font-size:20px;}
	input{
		float:right;}
	p{font-size:12px; width:500px;}
	h3{text-align:center;}
	table{ border-collapse:collapse; background-color:#FFF;}
	table tr:nth-child(2n){background-color:#eee;}
	table tr th{min-width:120px;}
	table tr td{text-align:center;}
	table tr td,table tr th{padding:4px;}
</style>
<script src="{{ASSET_URL}}system/admin/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="{{ASSET_URL}}user/admin/js/echarts.min.js"></script>
</head>

<body>
<div class="total">
	<div class="nav_top">
    	<button class="btn1">最新总排行</button>
        <!-- <button class="btn2">地域分布</button> -->
        <button class="btn3">增减趋势</button>
        <button class="btn4">消费能力</button>
        <button class="btn5">男女比例</button>
        <button class="btn6">级别统计</button>
    </div>
    <div class="list">
    	<div class="content">
        	<div class="con" id="rank">
            	<div class="beans">
                	<input type="text" placeholder="输入排行榜展示条数" id="a" onblur="search()">
                    <h3>当前{{$name}}排行榜</h3>
                    <p>默认显示当前普通最高分值前10名，可以根据需要输入展示条数</p>
            		<table border="1" bordercolor="#666666" id="one">
                    	
                    </table>
            	</div>
            	<div class="experience">
            		<input type="text" placeholder="输入经验值展示条数" id="b" onblur="search()">
                    <h3>当前经验值排行榜</h3>
                    <p>默认显示当前普通最高分值前10名，可以根据需要输入展示条数</p>
            		<table border="1" bordercolor="#666666" id="two">
                    	
                    </table>
            	</div>
            </div>
            <!-- <div class="con" id="geographical">
            	地域分布内容
            </div> -->
            <div class="con" id="trend" style="width:800px;height:400px;margin: 0 auto;">
            	<div class="zonge2">累计支出{{$name}}总额</div>
            	<div><br></div>
            	<div id="add" style="width:800px;height:400px;margin: 0 auto;"></div>
            </div>
            <div class="con" id="consumption" style="width:800px;height:400px;margin: 0 auto;">
            	<div class="zonge">累计支出{{$name}}总额</div><br>
            	用户的消费水平计算： 个人消费总额 /  平台支出总额 所占的比例越高，说明消费水平越大
            	<div><br></div>
				<div id="main" style="width:800px;height:400px;margin: 0 auto;"></div>
            </div>
            <div class="con" id="proportion">
            	<div><br><br></div>
            	<div id="sex" style="width:800px;height:400px;margin: 0 auto;"></div>
            </div>
            <div class="con" id="statistics">
            <div class="beans">
                    <h3>级别用户统计</h3>
                    <p style="font-size:16px;">用户级别是根据当前用户获得的经验值高低划分产生的</p>
            		<table border="1" bordercolor="#666666" id="grade">
                    	<tr>
                        	<th style="width:200px;">级别名称</th>
                            <th style="width:300px;">用户数量（单位：个）</th>
                        </tr>
                        <tr>
                        	<td>1</td>
                            <td></td>
                        </tr>
                    </table>
            	</div>
        </div>
    </div>
</div>
<div id="main" style="width:800px;height:400px;margin: 0 auto;"></div>
<script>
	$('.btn1').click(function(){
		$('#rank').show();
		$('#geographical').hide();
		$('#trend').hide();
		$('#consumption').hide();
		$('#proportion').hide();
		$('#statistics').hide();
		});
	$('.btn2').click(function(){
		$('#rank').hide();
		$('#geographical').show();
		$('#trend').hide();
		$('#consumption').hide();
		$('#proportion').hide();
		$('#statistics').hide();
		});
	$('.btn3').click(function(){
		$('#rank').hide();
		$('#geographical').hide();
		$('#trend').show();
		$('#consumption').hide();
		$('#proportion').hide();
		$('#statistics').hide();
		});
	$('.btn4').click(function(){
		$('#rank').hide();
		$('#geographical').hide();
		$('#trend').hide();
		$('#consumption').show();
		$('#proportion').hide();
		$('#statistics').hide();
		});	
	$('.btn5').click(function(){
		$('#rank').hide();
		$('#geographical').hide();
		$('#trend').hide();
		$('#consumption').hide();
		$('#proportion').show();
		$('#statistics').hide();
		});
	$('.btn6').click(function(){
		$('#rank').hide();
		$('#geographical').hide();
		$('#trend').hide();
		$('#consumption').hide();
		$('#proportion').hide();
		$('#statistics').show();
		});
</script>

<script type="text/javascript">
    
    // 获取数据
    function getRank(limit1,limit2){
        $.get("{{URL::route('admin.user.pointstatistics.getrank')}}",{limit1:limit1,limit2:limit2},function(data){
            // 积分
            var html0 = '<tr><th>排行</th><th>用户</th><th>{{$name}}</th><th>占比例</th></tr>';
            var j = 1;
            for(var i = 0;i<data[0].length;i++){
                
                html0 += '<tr><td>'+j+'</td><td>'+data[0][i].name+'</td><td>'+data[0][i].point+'</td><td>超越了'+data[0][i].percent+'的用户</td></tr>';
                j++;
            }           
            
            var ss = $('#one');
            ss.html(html0);

            // 经验
            var html1 = '<tr><th>排行</th><th>用户</th><th>经验值</th><th>占比例</th></tr>';
            var j = 1;
            for(var i = 0;i<data[1].length;i++){
                
                html1 += '<tr><td>'+j+'</td><td>'+data[1][i].name+'</td><td>'+data[1][i].experience+'</td><td>超越了'+data[1][i].percent+'的用户</td></tr>';
                j++;
            }           
            
            var tt = $('#two');
            tt.html(html1);

        });
    }
    getRank(10,10);

    // 选择显示条数
    function search(){
        var limit1 = $('#a').val();
        var limit2 = $('#b').val();

        if(limit1 == ''){
            limit1 = 10;
        }

        if(limit2 == ''){
            limit2 = 10;
        }

        getRank(limit1,limit2);
    }
</script>
<script type="text/javascript">

	function getAbility(){
		$.get("{{URL::route('admin.user.pointstatistics.getability')}}",function(data){
			$('.zonge').text('累计支出{{$name}}总额:'+data[0]+'');
           	var myChart = echarts.init(document.getElementById('main'));
			var option = {
			    title : {
			        text: '消费水平统计图',
			        // subtext: '纯属虚构',
			        x:'center'
			    },
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        orient: 'vertical',
			        left: 'left',
			        data: ['低于60%','60%-70%','70%-80%','80%-90%','90%以上']
			    },
			    series : [
			        {
			            name: '访问来源',
			            type: 'pie',
			            radius : '55%',
			            center: ['50%', '60%'],
			            data:[
			                {value:data[5], name:'低于60%'},
			                {value:data[6], name:'60%-70%'},
			                {value:data[7], name:'70%-80%'},
			                {value:data[8], name:'80%-90%'},
			                {value:data[9], name:'90%以上'}
			            ],
			            itemStyle: {
			                emphasis: {
			                    shadowBlur: 10,
			                    shadowOffsetX: 0,
			                    shadowColor: 'rgba(0, 0, 0, 0.5)'
			                }
			            }
			        }
			    ]
			};
			myChart.setOption(option);

        });
	}
getAbility();
			
</script>
<script type="text/javascript">
	
	function getSex(){
		$.get("{{URL::route('admin.user.pointstatistics.getsex')}}",function(data){
           	var myChart = echarts.init(document.getElementById('sex'));
			var option = {
			    title : {
			        text: '消费水平统计图',
			        // subtext: '纯属虚构',
			        x:'center'
			    },
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        orient: 'vertical',
			        left: 'left',
			        data: ['男','女']
			    },
			    series : [
			        {
			            name: '访问来源',
			            type: 'pie',
			            radius : '55%',
			            center: ['50%', '60%'],
			            data:[
			                {value:data[0], name:'男'},
			                {value:data[1], name:'女'}
			            ],
			            itemStyle: {
			                emphasis: {
			                    shadowBlur: 10,
			                    shadowOffsetX: 0,
			                    shadowColor: 'rgba(0, 0, 0, 0.5)'
			                }
			            }
			        }
			    ]
			};
			myChart.setOption(option);

        });
	}
getSex();
</script>

<script type="text/javascript">
	function getTrend(){
		$.get("{{URL::route('admin.user.pointstatistics.gettrend')}}",function(data){
			$('.zonge2').text('累计支出{{$name}}总额:'+data[2]+'');
			var myChart = echarts.init(document.getElementById('add'));
			var option = {
			    title: {
			        text: '最近七天趋势图'
			    },
			    tooltip : {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['{{$name}}','经验']
			    },
			    toolbox: {
			        feature: {
			            saveAsImage: {}
			        }
			    },
			    grid: {
			        left: '3%',
			        right: '4%',
			        bottom: '3%',
			        containLabel: true
			    },
			    xAxis : [
			        {
			            type : 'category',
			            boundaryGap : false,
			            data : ['第一天','第二天','第三天','第四天','第五天','第六天','今天']
			        }
			    ],
			    yAxis : [
			        {
			            type : 'value'
			        }
			    ],
			    series : [
			        {
			            name:'{{$name}}',
			            type:'line',
			            stack: '总量',
			            areaStyle: {normal: {}},
			            data:[data[0][1], data[0][2], data[0][3], data[0][4], data[0][5], data[0][6], data[0][7]]
			        },
			        {
			            name:'经验',
			            type:'line',
			            stack: '总量',
			            areaStyle: {normal: {}},
			            data:[data[1][1], data[1][2], data[1][3], data[1][4], data[1][5], data[1][6], data[1][7]]
			        }
			    ]
			};
		myChart.setOption(option);
 		});
	}	
	getTrend();
</script>

<script type="text/javascript">
    
    // 获取数据
    function getGrade(){
        $.get("{{URL::route('admin.user.pointstatistics.getgrade')}}",function(data){
            // 积分
            var html = '<tr><th style="width:200px;">级别名称</th><th style="width:300px;">用户数量（单位：个）</th></tr>';
            for(var i = 0;i<data['title'].length;i++){
                
                html += '<tr><td>'+data['title'][i]+'</td><td>'+data['count'][i]+'</td></tr>';

            }           
            
            var ss = $('#grade');
            ss.html(html);
        });
    }
    getGrade();
</script>
</body>
</html>
