function time(){
		var date = new Date();
		var hour = date.getHours();
		var minute = date.getMinutes();
		var second = date.getSeconds();
		var h =checktime(hour);
		var m =checktime(minute);
		var s =checktime(second);
		document.getElementById('h').innerHTML = h+":"+m+":"+s;
		t = setTimeout(function(){
			time();
		},1000);
	}
		function checktime(time){
			if (time<10) {
				time = "0"+time;
			}
			return time;
		}