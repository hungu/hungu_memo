<?php 
	require_once('wall.php');
 ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>私人备忘录</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="css/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="css/fancybox.css">
<style type="text/css">

#calendar{width:960px; margin:20px auto 10px auto}
.fancy{width:450px; height:auto}
.fancy h3{height:30px; line-height:30px; border-bottom:1px solid #d3d3d3; font-size:14px}
.fancy form{padding:10px}
.fancy p{height:28px; line-height:28px; padding:4px; color:#999}
.input{height:20px; line-height:20px; padding:2px; border:1px solid #d3d3d3; width:100px}
.btn{-webkit-border-radius: 3px;-moz-border-radius:3px;padding:5px 12px; cursor:pointer}
.btn_ok{background: #360;border: 1px solid #390;color:#fff}
.btn_cancel{background:#f0f0f0;border: 1px solid #d3d3d3; color:#666 }
.btn_del{background:#f90;border: 1px solid #f80; color:#fff }
.sub_btn{height:32px; line-height:32px; padding-top:6px; border-top:1px solid #f0f0f0; text-align:right; position:relative}
.sub_btn .del{position:absolute; left:2px}
</style>
<script src='js/jquery-1.9.js'></script>
<script src='js/jquery-ui.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src='js/jquery.fancybox-1.3.1.pack.js'></script>
<script type="text/javascript">
$(function() {
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		dragOpacity: {
			agenda: .5,
			'':.6
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			$.post("do.php?action=drag",{id:event.id,daydiff:dayDelta,minudiff:minuteDelta,allday:allDay},function(msg){
				if(msg!=1){
					alert(msg);
					revertFunc();
				}
			});
    	},
		
		 eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
			$.post("do.php?action=resize",{id:event.id,daydiff:dayDelta,minudiff:minuteDelta},function(msg){
				if(msg!=1){
					alert(msg);
					revertFunc();
				}
			});
    	},
		
		
		selectable: true,
		select: function( startDate, endDate, allDay, jsEvent, view ){
			var start =$.fullCalendar.formatDate(startDate,'yyyy-MM-dd');
			var end =$.fullCalendar.formatDate(endDate,'yyyy-MM-dd');
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=add&date='+start+'&end='+end
			});
		},
		
		
		
		
		events: 'json.php',
		dayClick: function(date, allDay, jsEvent, view) {
			var selDate =$.fullCalendar.formatDate(date,'yyyy-MM-dd');
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=add&date='+selDate
			});
    	},
		eventClick: function(calEvent, jsEvent, view) {
			$.fancybox({
				'type':'ajax',
				'href':'event.php?action=edit&id='+calEvent.id
			});
		}
	});
	
});
</script>
</head>

<body>
<div id="header">
   <div id="logo"><h1><a >rabbityyy</a></h1></div>
   <div class="demo_topad"><script src="/js/ad_js/demo_topad.js" type="text/javascript"></script></div>
</div>

<div id="main" style="width:1060px">
   <h2 class="top_title"><b style='font-size:25px;color:#009FCC;'><?php echo $_SESSION['memo_name']; ?></b>&nbsp;<b style='color:black'>的备忘录</b></h2>
   <div id='calendar'></div>
   <div class="ad_76090"><script src="/js/ad_js/bd_76090.js" type="text/javascript"></script></div><br/>
</div>
<!-- <div id="footer">
    <p>Powered by helloweba.com  允许转载、修改和使用本站的DEMO，但请注明出处：<a href="http://www.helloweba.com">www.helloweba.com</a></p>
</div> -->
<p id="stat"><script type="text/javascript" src="/js/tongji.js"></script></p>
</body>
</html>
