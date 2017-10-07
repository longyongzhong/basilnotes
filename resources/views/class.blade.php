<!DOCTYPE html>
<html>
<head>
	<title>mynote</title>
	<meta charset="utf8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
	<script type="text/javascript">
		function cf(){
			if(window.confirm('确定删除吗？分类里的笔记将被移至回收站')){
				return true;
			}else{
				return false;
			}

		}
		function checkcontent() {
			var word = document.getElementById("name").value;
			if (word==""){
			    alert("您未输入分类名称");
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
<li style="margin-right: 20px;margin-left: 20px;font-style:italic"><h4 style="text-align: center;">basilnotes</h4></li>
	<li ><a href="{{url('/home')}}" class="glyphicon glyphicon-home" title="首页"></a></li>
	<li><a href="{{url('/write')}}" class="glyphicon glyphicon-pencil" title="写笔记"></a></li>
	<li class="active"><a href="{{url('/class')}}" class="glyphicon glyphicon-tag" title="分类管理"></a></li>
	<li><a href="{{url('/mdpassword')}}" class="glyphicon glyphicon-cog" title="修改密码"></a></li>
	<li><a href="{{url('/trash')}}" class="glyphicon glyphicon-trash" title="回收站"></a></li>
	<li><a href="{{url('/loginout')}}" class="glyphicon glyphicon-off" title="注销"></a></li>
  </ul>
  <div class="container-fluid">
			
			<form method="post" action="">
				{{csrf_field()}}
			<div class="row" style="margin-top: 5px;">
			<div class="col-md-3">
				<div class="form-group">
			    	<input type="text" class="form-control" id="name" placeholder="在这里输入分类名称" name="classname">
			  	</div>
			  	</div>
			  	<div class="col-md-2">
			  		<div class="form-group">
			  		<button class="btn btn-success" onclick="return checkcontent()">添加分类</button>
			  	</div>
			  	</div>
			  	@if(session('info')!=null)
					<span style="color:red;">{{session('info')}}</span>
					@endif
			
			</div>
			</form>
			
			<div class="row">
			<div class="col-md-12">
			分类管理
			</br>
			<table class="table table-bordered">
				<tr>
					<td>序号</td>
					<td>分类名称</td>
					<td>操作</td>
				</tr>
		@foreach($class as $classdata)
				<tr>
					<td>{{$ordernumber}}</td>
					<td>{{$classdata->name}}</td>
					<td><a class="glyphicon glyphicon-trash" href="{{url("/deleteclass"."/".$classdata->id."/".$classdata->name)}}" onclick="return cf();"></a></td>
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
</body>
</html>