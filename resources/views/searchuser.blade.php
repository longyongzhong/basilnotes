<!DOCTYPE html>
<html>
<head>
    <title>mynote</title>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <script charset="utf-8" src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        function cf(obj){
            if(window.confirm('确定删除吗?删除后将不能恢复！')){
                var setting = {
                    type:"GET",
                    url:"{{url('/deleteuser')}}",
                    data:{id:obj.id,email:obj.rel},
                    headers:{"Content-type":"application/x-www-form-urlencoded"},
                    success:function (data,status) {
                        if (status=="success"){
                            alert(data);
                            location.reload();
                        }else{
                            alert("请求失败");
                        }
                    }

                };
                $.ajax(setting);
            }else{
                return false;
            }

        }
        function showstatuspage(obj){
            $("#inputmodal").modal("show");
            document.getElementById('via').setAttribute("name",obj.id);
        }

        function changestatus(obj) {
            var userid = obj.name;
            var statusChinese = $("#userstatus").find("option:selected").text();
            if(statusChinese=="正常"){
                var status = "normal";
            }else if(statusChinese=="未激活"){
                var status = "notyet";
            }else{
                var status = "frozen";
            }
            var setting = {
                type:"POST",
                url:"{{url('/changeuserstatus')}}",
                data:{id:userid,status:status,_token:"{{csrf_token()}}"},
                headers:{"Content-type":"application/x-www-form-urlencoded"},
                success:function (data,status) {
                    if (status=="success"){
                        alert(data);
                    }else{
                        alert("请求失败");
                    }
                }

            };
            $.ajax(setting);
        }
        function checkcontent() {
            var word = document.getElementById("name").value;
            if (word==""){
                alert("您未输入用户邮箱");
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
<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="{{url('/manage')}}" class="glyphicon glyphicon-user" title="用户管理"></a></li>
    <li><a href="{{url('/modifyadminpass')}}" class="glyphicon glyphicon-lock" title="修改密码"></a></li>
    <li><a href="{{url('/managerloginout')}}" class="glyphicon glyphicon-off" title="注销"></a></li>
</ul>
<div class="container-fluid">
    <form method="post" action="{{url("/searchuser")}}">
        {{csrf_field()}}
        <div class="row" style="margin-top: 10px;margin-left: 5px;padding-bottom: 0px;">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="name" placeholder="在这里输入用户邮箱" name="useremail"
                @if(isset($keyword))
                    value="{{$keyword}}"
                    @endif>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn btn-default" onclick="return checkcontent()">继续搜索</button>
                    <a class="btn btn-default" href="{{url('/manage')}}">返回</a>
                </div>
            </div>
            @if(session('info')!=null)
                <span style="color:red;">{{session('info')}}</span>
            @endif

        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-hover">
                <tr style="background-color: #F8F8F8">
                    <td title="序号"><span class="glyphicon glyphicon-apple"></span></td>
                    <td title="用户邮箱"><span class="glyphicon glyphicon-envelope"></span></td>
                    <td title="注册时间"><span class="glyphicon glyphicon-time"></span></td>
                    <td title="笔记数量"><span class="glyphicon glyphicon-grain"></span></td>
                    <td title="用户状态"><span class="glyphicon glyphicon-heart"></span></td>
                    <td title="操作"><span class="glyphicon glyphicon-wrench"></span></td>
                </tr>


                @foreach($userdata as $user)
                    <tr>
                        <td>{{$ordernumber}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{date("Y-m-d H:i:s",$user->regtime)}}</td>
                        <td>{{$usernote[$user->email]}}</td>
                        <td>
                            @if($user->status=="normal")
                                正常
                            @elseif($user->status=="notyet")
                                未激活
                            @else
                                冻结
                            @endif
                        </td>

                        <td>
                            <a title="更改状态" class="glyphicon glyphicon-transfer" id="{{$user->id}}" onclick="showstatuspage(this)"></a>
                            <a title="删除" class="glyphicon glyphicon-trash" id="{{$user->id}}" rel="{{$user->email}}" onclick="return cf(this);"></a></td>
                    </tr>
                    <span style="display: none">{{$ordernumber++}}</span>
                @endforeach

            </table>
        </div>
    </div>
</div>
</div>
<nav class="navbar-fixed-bottom" style="height:25px;font-style: italic; text-align: center;color: black;background-color: whitesmoke;">
    <p id="note">Mynote Copyright by CXWT Studio</p>
    <p id="note_id" style="display:none;"></P>
</nav>
<div class="modal fade share" id="inputmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" style="display: none;" data-vivaldi-spatnav-clickable="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">更改状态</h4>
            </div>
            <div class="modal-body">
                <h5>请选择要更改的状态</h5>
                <form id="form">

                    <select class="form-control" name="userstatus" id="userstatus">
                        <option>正常</option>
                        <option>未激活</option>
                        <option>冻结</option>
                    </select>
                </form>
                <span id="shareinfo"></span>
                <p id="password"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload()">返回</button>
                <button type="button" id="via" class="btn btn-primary" name="pingzi25" onclick="changestatus(this)">确定</button>

            </div>
        </div>
    </div>
</div>
</body>
</html>