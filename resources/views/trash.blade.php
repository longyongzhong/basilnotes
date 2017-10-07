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
        function cf(){
            if(window.confirm('确定删除吗？删除之后不能恢复哦')){
                return true;
            }else{
                return false;
            }

        }


        function showpage(obj){
//            var formerclass = $(obj).attr("rel");
//
////            alert(l);
            $("#inputmodal").modal("show");
            document.getElementById('via').setAttribute("name",obj.id);
        }

        function recover(ob) {
            var newclass = $("#newclass").find("option:selected").text();
            var noteid = ob.name;
//            alert(id+formerclass+newclass);
            var setting = {
                type:"POST",
                url:"{{url('/recovertoclass')}}",
                data:{id:noteid,toclass:newclass,_token:"{{csrf_token()}}"},
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
<li style="margin-right: 20px;margin-left: 20px;font-style:italic"><h4 style="text-align: center;">basilnotes</h4></li>
    <li ><a href="{{url('/home')}}" class="glyphicon glyphicon-home" title="首页"></a></li>
    <li><a href="{{url('/write')}}" class="glyphicon glyphicon-pencil" title="写笔记"></a></li>
    <li ><a href="{{url('/class')}}" class="glyphicon glyphicon-tag" title="分类管理"></a></li>
    <li><a href="{{url('/mdpassword')}}" class="glyphicon glyphicon-cog" title="修改密码"></a></li>
    <li class="active"><a href="{{url('/trash')}}" class="glyphicon glyphicon-trash" title="回收站"></a></li>
    <li><a href="{{url('/loginout')}}" class="glyphicon glyphicon-off" title="注销"></a></li>
</ul>
<div class="container-fluid">

    <form method="post" action="">
        {{csrf_field()}}
        <div class="row" style="margin-top: 5px;">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="name" placeholder="在这里输入笔记名称" name="name">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn btn-success ">搜索笔记</button>
                </div>
            </div>

            @if(session('info')!=null)
                <span style="color:red;">{{session('info')}}</span>
            @endif
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>

                    <td>笔记名称</td>
                    <td>操作</td>
                </tr>
                @foreach($notes as $note)

                <tr>

                    <td>{{$note->title}}</td>
                    <td><a class="glyphicon glyphicon-trash" href="{{url('/foreverdelnote'."/".$note->id)}}" onclick="return cf();"></a>&nbsp;
                        <span class="glyphicon glyphicon-repeat" rel="{{$note->class}}" id="{{$note->id}}" onclick="showpage(this);"></span>
                    </td>
                </tr>
                @endforeach

            </table>

        </div>
    </div>
</div>
</div>

<div class="modal fade share" id="inputmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" style="display: none;" data-vivaldi-spatnav-clickable="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">恢复笔记</h4>
            </div>
            <div class="modal-body">
                <h5>请选择要恢复到的分类</h5>
                <form id="form">

                    <select class="form-control" name="newclass" id="newclass">
                        <option>common</option>
                        @foreach($noteclass as $classdata)
                            <option>{{$classdata->name}}</option>
                        @endforeach
                    </select>
                </form>
                <span id="shareinfo"></span>
                <p id="password"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload()">返回</button>
                <button type="button" id="via" class="btn btn-primary" name="pingzi25" onclick="recover(this)">确定</button>

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