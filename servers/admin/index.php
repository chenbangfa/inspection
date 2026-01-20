<?php require_once('../data/db.php');?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>安全生产隐患巡检管理系统</title>
<link rel="shortcut icon" href="http://www.bnng.net/hbyx/images/abus.png" type="image/x-icon">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="js/Chart.css" rel="stylesheet">
<script src="js/jquery-1.11.1.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/chart.min.js"></script> 
<script src="js/Chart.js"></script> 
<script src="js/chart-data.js"></script> 
<script src="js/easypiechart.js"></script> 
<script src="js/easypiechart-data.js"></script> 
<script src="js/bootstrap-datepicker.js"></script> 
<script src="../js/pub.js"></script>
<style>
a{text-decoration: none;}
a:visited{text-decoration: none;}
a:hover {text-decoration: none;}
a:active{text-decoration:none;}
.vspan{width:10%; border:#fff 1px solid; border-radius:9px; text-align:center; background-color:#fff; padding:10px 0; margin-left:15px}
</style>
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

<script>setCookie("menu","sjgk");</script>
</head>

<body>
<?php include("menu.php");
$start = date('Y-m-d 00:00:00');
?>
<!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="active">数据概况</li>
    </ol>
  </div>
  <!--/.row-->
  <div class="row" style="margin-top:5px;">
    <div class="col-xs-12 col-md-6 col-lg-3">
      <div class="panel panel-red panel-widget">
        <div class="row no-padding">
          <div class="col-sm-3 col-lg-5 widget-left"> <em class="glyphicon glyphicon-th glyphicon-l"></em> </div>
          <div class="col-sm-9 col-lg-7 widget-right">
          <a href="hylist.php">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("hyTeam","1=1");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">总团队数</div>
            </a>
          </div>
        </div>
      </div>
    </div>
    
	<div class="col-xs-12 col-md-6 col-lg-3">
      <div class="panel panel-red panel-widget">
        <div class="row no-padding">
          <div class="col-sm-3 col-lg-5 widget-left" style="background:#690"> <em class="glyphicon glyphicon-th glyphicon-l"></em> </div>
          <div class="col-sm-9 col-lg-7 widget-right">
          <a href="hylist.php">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("hyUser","1=1");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">总用户数</div>
            </a>
          </div>
        </div>
      </div>
    </div>
        
    <div class="col-xs-12 col-md-6 col-lg-3">
      <div class="panel panel-red panel-widget">
        <div class="row no-padding">
          <div class="col-sm-3 col-lg-5 widget-left" style="background:#F99"> <em class="glyphicon glyphicon-th glyphicon-l"></em> </div>
          <div class="col-sm-9 col-lg-7 widget-right">
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("inspect","1=1");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">日常巡检</div>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-xs-12 col-md-6 col-lg-3">
      <div class="panel panel-red panel-widget">
        <div class="row no-padding">
          <div class="col-sm-3 col-lg-5 widget-left" style="background:#F99"> <em class="glyphicon glyphicon-th glyphicon-l"></em> </div>
          <div class="col-sm-9 col-lg-7 widget-right">
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("blueOrder","1=1");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">周期巡检</div>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-xs-12 col-md-6 col-lg-3 vspan">
     
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("blueOrder","addTime>'$start'");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">今日未巡检</div>
            </a>
          
    </div>
    
    <div class="col-xs-12 col-md-6 col-lg-3 vspan">
     
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("blueOrder","addTime>'$start'");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">本周未巡检</div>
            </a>
          
    </div>
    <div class="col-xs-12 col-md-6 col-lg-3 vspan">
     
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("blueOrder","addTime>'$start'");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">本月未巡检</div>
            </a>
          
    </div>
    <div class="col-xs-12 col-md-6 col-lg-3 vspan">
     
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("blueOrder","addTime>'$start'");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">本季未巡检</div>
            </a>
          
    </div>
    <div class="col-xs-12 col-md-6 col-lg-3 vspan">
     
          <a href="hylist.php?state=1">
            <div class="large">
			 <?php
			$num_rows = $db->getCount("blueOrder","addTime>'$start'");
			echo $num_rows;
			?>
        	</div>
            <div class="text-muted" style="font-size:12px; margin-top:5px;">本年未巡检</div>
            </a>
          
    </div>
    
    
  </div>  
</div>
<!--/.main--> 

<script>
var w=window.screen.width-220;
$(".main").css("width",w+"px");
</script>
<style>
.main{margin-left:200px; margin-top:33px}
.breadcrumb{background-color:#fff;}
.col-lg-12{ padding:0}
</style>
</body>
</html>
