<!DOCTYPE html>
<html>
<head>
	<title>登录-mynote</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
	<style>
    .bg{background-image:  url('{{asset('images/bg.jpg')}}');
    }
	</style>
	<script type="text/javascript">
		function checkinput()
		{
			var email = document.getElementById('email').value;
			var password = document.getElementById('password').value;
		if (email=="") {
			alert("请输入邮箱!");
			return false;
		}
		if (password=="") {
			alert('请输入密码!');
			return false;
		}
	}
        $(function () {
            $("#email").blur(function () {
                var e = $("#email").val();
                $.post("{{url('/checkemail')}}",{email:e},function (data,status) {
                    if (status=="success"){
                        if (data=="havesame"){
                            $("#emailinfo").text("");
                            $("#reg").attr('disabled',false);
                        }else if(data=="no"){
                            $("#emailinfo").text("您输入的邮箱还没有注册，请检查是否输入错误！");
                            $("#email").focus();
                            $("#reg").attr('disabled',true);
                        }else{

                        }
                    }else{
                        $("#emailinfo").text("请求失败");
                    }
                });
            });


        });
	</script>
</head>
<body>
<div class="bg">
<nav class="navbar navbar-default navbar-fixed-top" id="nav">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{url('/')}}">mynote</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="{{url('/')}}">首页</a></li>
				<li><a href="{{url('/about')}}">关于</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="actived"><a href="{{url('/login')}}">登录 <span class="sr-only">(current)</span></a></li>
				<li><a href="{{url('/reg')}}">注册</a></li>
				<li ><a href="{{url('/findpassword')}}">找回密码 <span class="sr-only">(current)</span></a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
	<div class="container">
<div class="col-md-4 col-md-offset-4 loginbox" style="opacity: 0.8">
	<form method="post" action="">
		{{csrf_field()}}
	<div class="title">
		<h4>用户登录</h4>
	</div>
  <div class="form-group">
    <label for="name">邮箱 <span class="glyphicon glyphicon-envelope"></span></label>
    <input type="email" class="form-control" id="email" placeholder="在这里输入邮箱" name="email">
	  <span id="emailinfo" style="color: red;"></span>

  </div>
  <div class="form-group">
    <label for="password">密码 <span class="glyphicon glyphicon-lock"></span></label>
	  <input type="password" class="form-control" id="password" placeholder="在这里输入密码" name="password">
	  @if(isset($errmessage))
		  <span id="codeinfo" style="color: red;">{{$errmessage}}</span>
	  @endif
  </div>
		<div class="form-group">
			<label for="code" >验证码 <span class="glyphicon glyphicon-picture"></span></label>
			<img src="{{url('makecode')}}" style="border: 1px solid lightgray" onclick="this.src='{{url('makecode')}}?'+Math.random();">
			<input type="text" id="code" placeholder="在这里输入验证码" name="code" onclick="cleartext()">
			@if(isset($err))
				<span id="codeinfo" style="color: red;">{{$err}}</span>
			@endif
		</div>
  <div class="col-md-6 col-md-offset-3">
  	<button type="submit" class="btn btn-success btn-block" onclick="return checkinput()">登录</button>
  </div>
</form>
</div>
	</div>
</div>
<div class="container-fluid">
	<div class="navbar-fixed-bottom" style="text-align: center;color:white;background-color:darkslategray;">
		<span>永忠工作室版权所有</span>
	</div>
</div>
</body>
</html>