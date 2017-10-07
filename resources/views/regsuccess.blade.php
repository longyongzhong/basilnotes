<!DOCTYPE html>
<html>
    <head>
        <title>注册成功--mynote</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
        <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" >
        var counts = 60;
        function sendemail() {
            if (counts==-1){
                $("#send").attr('disabled',false);
                $("#send").text("仍没收到？重新发送");
                clearTimeout();
            }else{
                $("#send").attr('disabled',true);
                $("#send").text(counts+"已发送");
                counts--;
            }
            setTimeout(function () {
                sendemail();
            },1000);
        }
    $(function () {
        $("#send").click(function () {
            var em = "{{$email}}"
            var rand= "{{$randomcode}}";
            $.post("{{url('/sendvadiemail')}}",{email:em,randomcode:rand},function (message,status) {
                if (status=="success"){
                    alert(message);
                   sendemail();
                }else{
                    alert("发送失败，请重试！");
                }
            });

        });
    });
    </script>
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

            .title {
                font-size: 50px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">注册成功！</div>

                <h3>mynote笔记已经向您的邮箱：
                    <span id="email">{{$email}}</span>
                    发送了一封验证邮件，请前往验证！</h3>
                <button id="send" class="btn btn-success">没有收到？重新发送</button>
                <button id="gohome" class="btn btn-primary" onclick="window.location.href='{{url('/login')}}'">去登录</button>
            </div>
        </div>
    </body>
</html>
