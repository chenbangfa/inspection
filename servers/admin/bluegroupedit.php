<?php require_once("../data/db.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>巡检点分类 - 安全生产隐患巡检管理系统</title>
<link rel="shortcut icon" href="../image/logo.png">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
    <script src="upfile/jquery.form.js" type="text/javascript"></script>
<script src="../js/pub.js"></script>


<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<style>.form-control{ width:400px; display:inline}</style>
<body>
<?php include("menu.php");?>
<!--/.sidebar-->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
				<li class="active">巡检点分类</li>
                <li class="active">编辑分类</li>
			</ol>
		</div><!--/.row-->
		
		<!--/.row-->	
		<div class="row" style="margin:5px 0">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
                    <?php 
					$id=$db->getpar("id");
					$rebate=$db->getOne("blueGroup","id='".$id."'");
					$row=$rebate["BlueGroup"];
					?>
						<div class="col-md-6" style="width:70%; float:left">
							<form class="form1">
                            <input type="hidden" name="tag" value="inspectgroupedit" />
                            <input type="hidden" name="tab" value="blueGroup" />
                            <input type="hidden" value="<?php echo $row["id"];?>" name="id">
                                <div class="form-group">
									<label>分组名称：</label>
                                    <input class="form-control req" value="<?php echo $row["gName"];?>" name="gName">
								</div>
                                <div class="form-group">
									<label>分组排序：</label>
                                    <input class="form-control req" value="<?php echo $row["gSort"];?>" name="gSort">
								</div>
							<button type="button" class="btn btn-primary save">保存</button>
						</form>
                        
                        </div>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->		
	</div><!--/.main-->
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
