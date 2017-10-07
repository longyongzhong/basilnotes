<!DOCTYPE html>
<html>
<head>
	<title>mynote</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
	<script charset="utf-8" src="{{asset('js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

	<link rel="stylesheet" href="{{asset('plugin/kindeditor/themes/default/default.css')}}" />
		<script charset="utf-8" src="{{asset('plugin/kindeditor/kindeditor-min.js')}}"></script>
		<script charset="utf-8" src="{{asset('pulin/kindeditor/lang/zh_CN.js')}}"></script>
		<script>
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="content"]', {
					resizeType : 1,
					allowPreviewEmoticons : true,
					allowImageUpload : false,
					items : [
						'undo','redo','print','|','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|','lineheight', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link','code','selectall','cut','copy','paste']
				});
				K('a[name=a]').click(
					//通过ajax获取笔记数据
								function(id){
								var xmlhttp;
								if (window.XMLHttpRequest)
								 {// code for IE7+, Firefox, Chrome, Opera, Safari
								 xmlhttp=new XMLHttpRequest();
								 }
								else
								 {// code for IE6, IE5
								 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
								 }
								xmlhttp.onreadystatechange=function()
								 {
								 if (xmlhttp.readyState==4&& xmlhttp.status==200)
								  {
								  editor.html(xmlhttp.responseText);
//									alert(xmlhttp.responseText);
								  //editor.insertHtml('#content',xmlhttp.responseText);
								  }
								 }
								xmlhttp.open("GET","{{url('/getcontent/')}}"+"/"+this.id,true);
								xmlhttp.send();
								document.getElementById('note_id').innerHTML=this.id;
								});

				K('button[id=button]').click(
					//通过ajax保存笔记数据
								function(id){
									var note_id = document.getElementById('note_id').innerHTML;
                                    var html = editor.html();
                                    var setting ={
									    type:"POST",
									    url:"{{url('/savemodify')}}",
										data:{id:note_id,content:html,_token:"{{csrf_token()}}"},
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

								});
				//删除笔记
						K('span[name=deleteid]').click(
								function(id){
								var xmlhttp;
								if (window.XMLHttpRequest)
								 {// code for IE7+, Firefox, Chrome, Opera, Safari
								 xmlhttp=new XMLHttpRequest();
								 }
								else
								 {// code for IE6, IE5
								 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
								 }
								xmlhttp.onreadystatechange=function()
								 {
								 if (xmlhttp.readyState==4&& xmlhttp.status==200)
								  {
								  alert(xmlhttp.responseText);
								  	location.reload();
								  //editor.insertHtml('#content',xmlhttp.responseText);
								  }
								 }
								 var com = confirm('确定删除吗？');
								 if (com) {
								 	xmlhttp.open("GET","{{url('/throwtogarbage/')}}"+"/"+this.id,true);
									xmlhttp.send();
								 }



								});



			});



		</script>

	<script type="text/javascript">
        function show(ob) {

                $(ob).children().eq(1).css({'opacity':1});
                $(ob).children().eq(2).css({'opacity':1});




//				.css({'opacity':1});
        }
        function display(ob){
            $(ob).children().eq(1).css({'opacity':0.1});
            $(ob).children().eq(2).css({'opacity':0.1});
//			.fadeTo("fast",0);

		}

        function showmovepage(obj){
            var l = $(obj).attr("rel");
            $("#inputmodal").modal("show");
            document.getElementById('via').setAttribute("name",obj.id);
            $("#oldclass").text(l);
        }

        function move(ob) {
			var noteid = ob.name;
			var cla = $("#movetoclass").find("option:selected").text();
			var oldcla = $("#oldclass").text();
//			alert(noteid+oldclass);
			var setting = {
			    type:"POST",
				url:"{{url('/movetoclass')}}",
				data:{id:noteid,toclass:cla,oldclass:oldcla,_token:"{{csrf_token()}}"},
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
        var lastid = null;
        function changecolor(obj){
        	if (lastid==null) {
        		lastid=obj;
        	}else{
    			lastid.style.backgroundColor="white";
    			lastid=obj;
        	}
        	obj.style.backgroundColor="lavender";
        }
	</script>
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
h1,h3,p,button{
    font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
}

#atd{
	background-color:dodgerblue ;
	color: white;
	
}
	</style>

</head>
<body onload="time()">
<span id="oldclass" style="display: none;"></span>
<ul class="nav nav-tabs" role="tablist">
<li style="margin-right: 20px;margin-left: 20px;font-style:italic"><h4 style="text-align: center;">basilnotes</h4></li>
  <li class="active"><a href="{{url('/home')}}" class="glyphicon glyphicon-home" title="首页"></a></li>
  <li><a href="{{url('/write')}}" class="glyphicon glyphicon-pencil" title="写笔记"></a></li>
  <li><a href="{{url('/class')}}" class="glyphicon glyphicon-tag" title="分类管理"></a></li>
	<li><a href="{{url('/mdpassword')}}" class="glyphicon glyphicon-cog" title="修改密码"></a></li>
	<li><a href="{{url('/trash')}}" class="glyphicon glyphicon-trash" title="回收站"></a></li>
	<li><a href="{{url('/loginout')}}" class="glyphicon glyphicon-off" title="注销"></a></li>

  </ul>

  <div class="container-fluid">
	  <p id="note_id" style="display:none;"></P>
		<div class="row" style="margin-top: 5px;">
			<div class="col-lg-2" style=" overflow-y:auto; overflow-x:auto; width:180px; height:585px;padding:0px;border-bottom:1px solid lightgray;">
				<ul class="list-group">
				  <li class="list-group-item" >
				    分类
				  </li>
				  <a class='list-group-item'  href="{{url('/searchbyclass/common')}}">
					  <span class='badge'>{{$commoncounts}}</span>
					  common
				  </a>
					@foreach($noteclass as $class)
						@if(isset($formclass)&&$class->name==$formclass)
						<a class='list-group-item' id="atd"  href="{{url("/searchbyclass/$class->name")}}">
							<span class='badge'>{{$class->counts}}</span>
							{{$class->name}}
						</a>
						@else
						<a class='list-group-item'  href="{{url("/searchbyclass/$class->name")}}">
							<span class='badge'>{{$class->counts}}</span>
							{{$class->name}}
						</a>
						@endif
						@endforeach
				</ul>
			</div>
			<div class="col-lg-10" style="padding-left: 0px;">
				<div class="col-md-4" style="padding-right: 0px;padding-left: 0px;width:250px; padding:0px;">
				<form method="get" action="{{url('/search')}}">
				<div class="input-group">
				      	 <input type="text" name="keywords" class="form-control" @if(isset($keywords))
						 value="{{$keywords}}"
						 @endif>
				      		<span class="input-group-btn">
				        		<input class="btn btn-success" type="submit" value="查找">
				     		 </span>
				    </div>
				  </form>
					<ul class="list-group" style="padding-right: 0px;padding-left: 0px;overflow-y:auto; overflow-x:auto; padding:0px; height:550px;border-bottom:1px solid lightgray;">
					  
					@foreach($notes as $note)
							<a name='a' id='{{$note->id}}' onmouseleave="display(this)"  onmouseenter="show(this)" onclick='changecolor(this)'class='list-group-item'>{{$note->title}}
								<h6 style='color: #007ED1;margin-top: 1px;margin-bottom: 1px;'>{{date("Y-m-d H:i:s",$note->date)}}</h6>
								<span class='glyphicon glyphicon-move'  onclick="showmovepage(this)"  rel="{{$note->class}}" aria-hidden='true' id='{{$note->id}}' style="opacity:0.1;"> </span>
								<span  class='glyphicon glyphicon-trash' name="deleteid" aria-hidden='true' id='{{$note->id}}' style="opacity: 0.1;"> </span>
							</a>
						@endforeach
					  
				</ul>
				</div>



	<div class="col-md-7" style="padding-left: 2px;padding-right: 0px;">
		<textarea name="content" id="content" style="width:900px;height:583px;visibility:hidden;"></textarea>
				
			</div>
	<div class="col-md-1">
				<button class="btn btn-default btn-sm" style="height: 25px;text-align: center;" id="button" >修改保存</button>
			</div>
			</div>


			</div>
		</div>
	</div>

	<nav class="navbar-fixed-bottom" style="height:25px;font-style: italic; text-align: center;color: black;background-color: whitesmoke;">
    <p id="note">Mynote Copyright by CXWT Studio</p>

</nav>
<div class="modal fade share" id="inputmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" style="display: none;" data-vivaldi-spatnav-clickable="1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">移动笔记</h4>
			</div>
			<div class="modal-body">
				<h5>请选择要移动到的分类</h5>
				<form id="form">

						<select class="form-control" name="movetoclass" id="movetoclass">
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
				<button type="button" id="via" class="btn btn-primary" name="pingzi25" onclick="move(this)">确定</button>

			</div>
		</div>
	</div>
</div>
</body>
</html>
