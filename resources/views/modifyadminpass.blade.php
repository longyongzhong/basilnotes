<!DOCTYPE html>
<html>
<head>
    <title>mynote</title>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        function check() {
            var oldpassword = document.getElementById("oldpassword").value;
            var password = document.getElementById("password").value;
            var cfpassword = document.getElementById("cfpassword").value;
            if (oldpassword==""){
                alert("原密码为空");
                return false;
            }
            if (password!=cfpassword){
                alert("您输入的两次密码不一样");
                return false;
            }
            if(password.length<6){
                alert("密码长度不小于6");
                return false;
            }
        }
    </script>
    <style>
        h1,h3,p,a,button{
            font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body>
<span id="formerclass" style="display: none"></span>
<ul class="nav nav-tabs" role="tablist">
    <li><a href="{{url('/manage')}}" class="glyphicon glyphicon-user" title="用户管理"></a></li>
    <li class="active"><a href="{{url('/modifyadminpass')}}" class="glyphicon glyphicon-lock" title="修改密码"></a></li>
    <li><a href="{{url('/managerloginout')}}" class="glyphicon glyphicon-off" title="注销"></a></li>
</ul>
<div class="container-fluid">
    @if(session('info')!=null)
        <span style="color:red;">{{session('info')}}</span>
    @endif
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="border: 1px solid lightgray;margin-top: 20px;padding: 15px;border-radius: 5px;background-color:#F8F8F8;">
            <form method="post" action="">
                {{csrf_field()}}
                <div class="form-group">
                    <label for="password">旧密码</label>
                    <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="原密码">
                </div>
                <div class="form-group">
                    <label for="password">新密码</label>
                    <input type="text" class="form-control" name="password" id="password" placeholder="新密码">
                </div>
                <div class="form-group">
                    <label for="cfpassword">确认密码</label>
                    <input type="text" class="form-control" name="cfpassword" id="cfpassword" placeholder="确认密码">
                </div>

                <button type="submit" class="btn btn-default" onclick=" return check()">确定</button>
            </form>
        </div>

    </div>

</div>
</div>

<nav class="navbar-fixed-bottom" style="height:25px;font-style: italic; text-align: center;color: black;background-color: whitesmoke;">
    <p id="note">Mynote Copyright by CXWT Studio</p>
    <p id="note_id" style="display:none;"></P>
</nav>
</body>
</html>