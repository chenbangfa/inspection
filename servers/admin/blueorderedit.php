<?php require_once("../data/db.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>巡检记录 - 安全生产隐患巡检管理系统</title>
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
				<li class="active">巡检记录</li>
                <li class="active">巡检项目</li>
			</ol>
		</div><!--/.row-->
		
		<!--/.row-->	
		<div class="row" style=" margin:5px -15px;">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
                    <?php 
					$id=$db->getpar("id");
					$rebate=$db->getOne("blueOrder","id='".$id."'");
					$row=$rebate["BlueOrder"];
					?>
						<div class="col-md-6" style="width:60%; float:left">
							<form class="form1">
                            <input type="hidden" name="tag" value="blueedit" />
                            <input type="hidden" value="<?php echo $row["id"];?>" name="id">
                                <div class="form-group">
									<label>巡检编号：</label>
                                    <?php echo $row["dropNo"];?>
								</div>
                                <div class="form-group">
									<label>巡检名称：</label>
                                    <?php echo $row["dropName"];?>
								</div>
                                <div class="form-group">
									<label>巡检人员：</label>
                                    <?php echo $row["hyName"];?>
								</div>
                                <div class="form-group">
									<label>随检人员：</label>
                                    <?php echo $row["odtogether"];?>
								</div>
                                <div class="form-group">
									<label>巡检备注：</label>
                                    <?php echo $row["odInfo"];?>
								</div>
                                <div class="form-group">
									<label>巡检时间：</label>
                                    <?php echo $row["addTime"];?>
								</div>
						</form>
                        
                        </div>
                        <div style="float:right; width:40%; line-height:32px">
                        <p style="font-size:16px; font-weight:bold">巡检项目</p>    
                        <?php $pro=$db->getAll("orderPro","oId='$id'","addTime desc");
						$i=0;
						foreach ($pro as $rowg)
						{
							$i++;
						$rowg=$rowg["OrderPro"];
						$proState=$rowg["proState"]==0?"正常":"<span style='color:red;'>异常</span>";
						$proinfo=$rowg["proInfo"]==""?"":"（".$rowg["proInfo"]."）";
						echo $i.".".$rowg["proName"]."------>".$proState."".$proinfo."<br/>";
						}
						?>
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
