<?php @session_start();unset($_SESSION["hyid"]);?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>安全生产隐患巡检管理系统</title>
<link rel="shortcut icon" href="../image/logo.png">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>
<style>
body {
/* 加载背景图 */
background-image: url(image/bg.jpg);
/* 背景图垂直、水平均居中 */
background-position: center center;
/* 背景图不平铺 */
background-repeat: no-repeat;
/* 当内容高度大于图片高度时，背景图像的位置相对于viewport固定 */
background-attachment: fixed;
/* 让背景图基于容器大小伸缩 */
background-size: 100% 100%; 
/* 设置背景颜色，背景图加载过程中会显示背景色 */
background-color: #464646;

}
.element::-webkit-scrollbar { width: 0!important }
.form-control{ width:220px; display:unset}
.form-group{ text-align:center}
</style>
<body style="margin:0; padding:0;overflow:hidden;">
<div style="width:100%; padding:10px 0; margin-top:15%; text-align:center;">
<img src="image/bglogo.png" style="height:60px"/><br/><br/><br/>
<div style="font-size:14px; color:#120976; margin-top:10px">运维电话：18608518772，18508518768</div>
</div>
<div style="position:fixed; right:0; top:0; height:100%; width:300px; background-color:#fff; opacity:0.8">
<div style="margin-top:80%; text-align:center; background-color:#fff; border:1px solid #fff"><em class="glyphicon glyphicon-user glyphicon-l" style="font-size:4em; color:#5897f8;"></em>
	<div style="font-weight:bold; margin-bottom:10px">用户登陆</div>
					<form class="form1">
            		<input type="hidden" name="tag" value="admlg" />
						<fieldset>
							<div class="form-group">
								<input class="form-control req" style="border:1px solid #333" placeholder="请输入用户名" type="text" name="hyTel" maxlength="20">
							</div>
							<div class="form-group">
								<input class="form-control req" style="border:1px solid #333" placeholder="请输入密码" type="password" name="hyPwd" maxlength="20">
							</div>
							<div class="btn btn-primary save" style="padding:5px 30px; margin-top:5px;; width:220px">登陆</div>
						</fieldset>
					</form>
			</div>	
</div>
<script src="js/jquery-1.11.1.min.js"></script> 
<script src="../js/pub.js?V=4"></script>
<script>
document.onkeydown = function (e)
{ 
    var theEvent = window.event || e;
    var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
    if (code == 13)
	{
        $('.save').click();
    }
}
</script>
</body>
</html>
