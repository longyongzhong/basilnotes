<!DOCTYPE html>
<html>
<head>
    <title>注册成功--mynote</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }


    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <h2>{{$message}}</h2>

        <h3>{{$info}}</h3>

        <button id="gohome" class="btn btn-primary" onclick="window.location.href='{{url('/login')}}'">去登录</button>
    </div>
</div>
</body>
</html>
