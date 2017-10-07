<!DOCTYPE html>
<html>
<head>
	<title>mynote在线笔记</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
	<style>
        .bg{
            background-image:  url('{{asset('images/bg.jpg')}}');
        }
	.loginbox{
		border: 1px lightgray solid; padding: 15px; margin-top: 15%;background-color: white; opacity: 0.7; border-radius: 7px;
	}
	.title{
		text-align: center;
	}

	</style>
</head>
<body>
<div class="bg">
    <div class="onepage">
        <div class="onepage-bg" id="onepagebg" style="background-image: url('{{asset('resources/views/public/image/bg.jpg')}}')">

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
                            <li class="actived"><a href="{{url('/')}}">首页</a></li>

                            <li><a href="{{url('/about')}}">关于</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="{{url('/login')}}">登录 <span class="sr-only">(current)</span></a></li>
                            <li><a href="{{url('/reg')}}">注册</a></li>

                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </nav>
            <div class="container">
                <div class="row">
                <div class="title-text">
                    <div class="col-md-12 headfontsize">
                        <h1 class="headh1content" style="font-style: italic">
                            mynote</br>
                            在这里你可以写笔记，记录你想记录的东西
                        </h1>
                        <p style="color: white;font-size: large;">在某一天，某一时刻，你是否想将一些东西写下来,
                            将它记录在笔记本里<br>
                        mynote将为您提供一本简洁，方便的在线笔记本
                        </p>
                        <p class="btn-app-store">
                            <a class="btn btn-success btn-lg" href="{{url('reg')}}">立即注册，开始写笔记</a>
                        </p>
                    </div>
                </div>
            </div>
            </div>
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