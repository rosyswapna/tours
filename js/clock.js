$(document).ready(function(){
    function updateTime(){
    var d = new Date();
    var hours = d.getHours();
    var mins = d.getMinutes();
    var sec = d.getSeconds();
    if(hours > 12){
        var hour = (hours - 12);
        var ampm = "PM";
    }
    else{
        var hour = hours;
        var ampm = "AM";
    }
	if(hour<10){
	hour='0'+hour;
	}
	if(mins<10){
	mins='0'+mins;
	}
	if(sec<10){
	sec='0'+sec;
	}
        var time = hour + ":" + mins + ":"+ sec +" "+ ampm;
    $(".clock").html(time);
    
    }
    window.setInterval(function(){
    updateTime();
    }, 100);
    
});
