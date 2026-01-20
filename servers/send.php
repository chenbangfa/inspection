<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="js/jquery.min.js"></script>
<script src="js/pub.js"></script>
<title>巡检状态更新</title>
</head>
<script type="text/javascript">
$(function()
{
	var send = function()
	{
		$.ajax({
			url:_url+"data/send.php",
			type:"POST",
			data:{tag:"send"},
			dataType:"JSON",
			cache:false,
			success:function(suc)
			{
				$("#send").html(suc)
			},
			error:function(err){
				$("#send").html(err.responseText)
			}
		});
		
		setTimeout(function()
		{send()},60000) 
	}
	send();
})
</script>
<body>
<div id="send" style="font-size:24px; text-align:center; margin-top:20%;"></div>
</body>
</html>