<!DOCTYPE html>
<html>
<head>
	<title>mynote</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

	<link rel="stylesheet" href="{{asset('plugin/kindeditor/themes/default/default.css')}}" />
	<script charset="utf-8" src="{{asset('plugin/kindeditor/kindeditor-min.js')}}"></script>
	<script charset="utf-8" src="{{asset('pulin/kindeditor/lang/zh_CN.js')}}"></script>
		<script>
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="content"]', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'undo','redo','print','|','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|','formatblock','lineheight', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link','code','selectall','cut','copy','paste','plainpaste']
				});
			});



		</script>
		<script type="text/javascript">
		function checkcontent()
		{
			var title = document.getElementById('title').value;
			var content = document.getElementById('content').value;
		if (title=="") {
			alert('请输入标题!');
			return false;
		}

	}

		@if(session('error'))
        alert("{{session('error')}}");
			@endif
	</script>
	<script type="text/javascript" src="{{asset('js/clock.js')}}" ></script>
				<style>
		
::-webkit-scrollbar  
{  
    width: 4px;  
    height: 4px;  
    background-color: #F5F5F5;  
}  
  
/*定义滚动条轨道 内阴影+圆角*/  
::-webkit-scrollbar-track  
{  
    -webkit-box-shadow: inset 0 0 2px rgba(0,0,0,0.3);  
    border-radius: 2px;  
    background-color: #F5F5F5;  
}  
  
/*定义滑块 内阴影+圆角*/  
::-webkit-scrollbar-thumb  
{  
    border-radius: 2px;  
    -webkit-box-shadow: inset 0 0 2px rgba(0,0,0,.3);  
    background-color: lightgray;  
}
h1,h3,p,a,button{
	font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
}
#sub:hover{
	background-color: #00a0df;
	color:white;
}
	</style>
</head>
<body onload="time()">
<ul class="nav nav-tabs" role="tablist">
<li style="margin-right: 20px;margin-left: 20px;font-style:italic"><h4 style="text-align: center;">basilnotes</h4></li>
	<li ><a href="{{url('/home')}}" class="glyphicon glyphicon-home" title="首页"></a></li>
	<li class="active"><a href="{{url('/write')}}" class="glyphicon glyphicon-pencil" title="写笔记"></a></li>
	<li><a href="{{url('/class')}}" class="glyphicon glyphicon-tag" title="分类管理"></a></li>
	<li><a href="{{url('/mdpassword')}}" class="glyphicon glyphicon-cog" title="修改密码"></a></li>
	<li><a href="{{url('/trash')}}" class="glyphicon glyphicon-trash" title="回收站"></a></li>
	<li><a href="{{url('/loginout')}}" class="glyphicon glyphicon-off" title="注销"></a></li>
  </ul>
  <div class="container-fluid">
  		<form method="post" action="{{url('/write')}}">
			{{csrf_field()}}
			<div class="row" style="margin-top:10px;height:30px;">
			  	<div class="col-md-12" style="padding-left:0px;">

			  	<div class="col-md-2" style="margin-left: 5px">
			  		<select class="form-control" name="class" id="class">
						 <option>common</option>
						@foreach($class as $classdata)
						<option>{{$classdata->name}}</option>
							@endforeach
					</select>
			  	</div>
			  	<div class="col-md-3">
			  	<div class="form-group">
			    <input type="text" class="form-control" placeholder="在这里输入标题" name="title" id="title">
			    </div>
		  	</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-default" id="sub" onclick="return checkcontent()">保存笔记</button>
					</div>
					<div class="col-md-3">
							{{--<ul class="list-inline">--}}
								{{--<li style="border: 1px solid gainsboro;border-radius: 5px;color: #005aff"><h5 id="h"></h5></li>--}}
							{{--</ul>--}}
							<audio src="{{asset('/music/hello.mp3')}}" controls="controls">
								这里本来有音乐听的哦，可惜你的浏览器不支持呢
							</audio>


					</div>


				</div>
			</div>

		<hr style="margin-bottom: 5px;padding-top: 0px;">
		<div class="row" >
		<div class="col-md-12" style="margin-left:0px;padding-left: 0px;padding-top: 0px">
			<div class="col-md-2" >
				<img src="{{asset('images/slide.jpg')}}"style="height: 560px;">
			</div>
			<div class="col-md-9" style="margin-left: 30px;">

				<textarea name="content" id="content" style="width:920px;height:480px;visibility: visible;"></textarea>

			</div>

			</div>


</div>

		</form>
			<nav class="navbar-fixed-bottom" style="height:25px;font-style: italic; text-align: center;color: black;background-color: whitesmoke;">
				<p id="note">Mynote Copyright by CXWT Studio</p>
				<p id="note_id" style="display:none;"></P>
			</nav>

</div>
</body>
</html>
