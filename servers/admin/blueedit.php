<?php require_once("../data/db.php");?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>巡检点管理 - 安全生产隐患巡检管理系统</title>
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
				<li class="active">巡检点管理</li>
                <li class="active">编辑巡检点</li>
			</ol>
		</div><!--/.row-->
		
		<!--/.row-->	
		<div class="row" style=" margin:5px -15px;">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
                    <?php 
					$id=$db->getpar("id");
					$rebate=$db->getOne("blueInspect","id='".$id."'");
					$row=$rebate["BlueInspect"];
					?>
						<div class="col-md-6" style="width:60%; float:left">
							<form class="form1">
                            <input type="hidden" name="tag" value="blueedit" />
                            <input type="hidden" value="<?php echo $row["id"];?>" name="id">
                                <div class="form-group">
									<label>巡检编号：</label>
                                    <input class="form-control req" value="<?php echo $row["dropNo"];?>" name="dropNo">
								</div>
                                <div class="form-group">
									<label>巡检名称：</label>
                                    <input class="form-control req" value="<?php echo $row["dropName"];?>" name="dropName">
								</div>
                                <div class="form-group">
									<label>巡检分类：</label>                                    
                                    <select id="yhSpeciality" class="form-control" name="yhSpeciality">
                                    <?php $res=$db->getAll("blueGroup","tId='".$_SESSION["tId"]."'","gSort asc");
									foreach ($res as $rowg)
										{
											$rowg = $rowg["BlueGroup"];
									?>
									<option value="<?php echo $rowg["gName"];?>"><?php echo $rowg["gName"];?></option>
									<?php }?>
                                    </select>
								</div>
                                <div class="form-group">
									<label>巡检周期：</label>
                                    <select id="patrolCycle" class="form-control" name="patrolCycle">
									<option value="每日">每日</option>
									<option value="每周">每周</option>
									<option value="每月">每月</option>
									<option value="每季">每季</option>
									<option value="每年">每年</option>
                                    </select>
								</div>
                                <div class="form-group">
									<label>巡检次数：</label>
                                    <input class="form-control req" value="<?php echo $row["patrolNum"];?>" name="patrolNum">
								</div>
                                <div class="form-group">
									<label>间隔时间：</label>
                                    <input class="form-control req" value="<?php echo $row["patrolDiff"];?>" name="patrolDiff">
								</div>
                                <div class="form-group">
									<label>巡检人员：</label>
                                    <?php echo $row["hyAppointName"];?>
								</div>
                                <div class="form-group">
									<label>巡检次数：</label>
                                    <?php echo $row["inspectNum"];?> 次（周期内的巡检次数）
								</div>
                                <div class="form-group">
									<label>巡检时间：</label>
                                    <?php echo $row["inspectTime"];?>
								</div>
                                 <script type="text/javascript">
								$("#yhSpeciality").val("<?php echo $row["yhSpeciality"];?>");
                                </script>
							<button type="button" class="btn btn-primary save">保存</button>
						</form>
                        
                        </div>
                        <div style="float:right; width:40%; line-height:32px">
                        <p style="font-size:16px; font-weight:bold">巡检项目</p>    
                        <?php $pro=$db->getAll("bluePro","iId='$id'","proSort asc");
						$i=0;
						foreach ($pro as $rowg)
						{
							$i++;
						$rowg=$rowg["BluePro"];
						echo $i.".".$rowg["proName"]."<br/>";
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
